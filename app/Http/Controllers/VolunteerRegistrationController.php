<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Company;
use App\Models\Program;
use App\Models\Cluster;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Filament\Notifications\Notification;
use Filament\Facades\Filament;
use App\Settings\MailSettings;
use Illuminate\Support\Facades\Mail;
use App\Mail\Visualbuilder\EmailTemplates\UserVerifyEmail;
use Illuminate\Support\Facades\Log;

class VolunteerRegistrationController extends Controller
{
    /**
     * Show the registration form.
     */
    public function view()
    {
        // Fetch companies, programs, and clusters from the database
        $companies = Company::all();
        $programs = Program::all();
        $clusters = Cluster::all();

        // Pass all data to the view
        return view('custom.volunteer-registration', compact('companies', 'programs', 'clusters'));
    }


    /**
     * Handle AJAX form submission with validation.
     */
    public function store(Request $request)
    {
        try {
            Log::info('Volunteer registration attempt', ['data' => $request->except(['password', 'passwordConfirmation'])]);

            $rules = [
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'nickname' => 'nullable|string|max:255', // Changed from middle_name
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
                'age_range' => 'required|string|in:10-17,18-24,25-34,35-44,45-54,55-64,65+', // Changed from birthday
                'emergency_contact_name' => 'required|string|max:255',
                'emergency_contact_number' => 'required|string|max:15|regex:/^[0-9]+$/',
                'affiliate_type_id' => 'required|integer|in:1,2',
                'program_ids' => 'required|array|min:1',
                'program_ids.*' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        if ($value !== 'other' && !Program::where('id', $value)->exists()) {
                            $fail('The selected program is invalid.');
                        }
                    }
                ],
                'other_program' => [
                    'nullable',
                    function ($attribute, $value, $fail) use ($request) {
                        if (in_array('other', $request->program_ids) && empty($value)) {
                            $fail('Please specify the other program.');
                        }
                    }
                ],
                'referral_source' => 'required|array|min:1',
                'referral_source.*' => ['required', 'in:afi_website,social_media,referral,advertisements,activations,news']
            ];
            // Conditional validation for affiliate type
            if ($request->input('affiliate_type_id') == 1) {
                $rules['cluster_id'] = 'required|exists:clusters,id';
                $rules['company_id'] = 'required|exists:companies,id';
            } else {
                $rules['external_company_name'] = 'required|string|max:255';
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ]);
            }

            DB::beginTransaction();
            try {
                $input = $request->all();

                // Create user
                $user = User::create([
                    'volunteer' => 1,
                    'firstname' => $input['firstname'],
                    'lastname' => $input['lastname'],
                    'nickname' => $input['nickname'], // Changed from middle_name
                    'email' => $input['email'],
                    'password' => Hash::make($input['password']),
                    'age_range' => $input['age_range'], // Changed from birthday
                    'emergency_contact_name' => $input['emergency_contact_name'],
                    'emergency_contact_number' => $input['emergency_contact_number'],
                    'affiliate_type_id' => $input['affiliate_type_id'],
                    'cluster_id' => $input['cluster_id'] ?? null,
                    'company_id' => $input['company_id'] ?? null,
                    'external_company_name' => $input['external_company_name'] ?? null,
                    'referral_source' => $input['referral_source']
                ]);

                // Auto-assign Volunteer role
                $volunteerRole = Role::firstOrCreate(['name' => 'Volunteer', 'guard_name' => 'web']);
                $user->assignRole($volunteerRole);

                // Attach programs
                if (!empty($input['program_ids'])) {
                    $programData = [];
                    foreach ($input['program_ids'] as $programId) {
                        if ($programId !== 'other') {
                            $programData[] = [
                                'program_id' => $programId,
                                'volunteer_id' => $user->id,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                    }

                    if (!empty($programData)) {
                        DB::table('program_volunteer')->insert($programData);
                    }

                    // Handle other program if specified
                    if (in_array('other', $input['program_ids']) && !empty($input['other_program'])) {
                        $user->update(['other_program' => $input['other_program']]);
                    }
                }

                DB::commit();
                return response()->json(['success' => true]);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Volunteer registration failed', ['error' => $e->getMessage()]);
                return response()->json([
                    'success' => false,
                    'errors' => ['general' => 'Registration failed. Please try again.']
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Volunteer registration failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'errors' => ['general' => 'Registration failed. Please try again.']
            ]);
        }
    }

    public function verificationSent()
    {
        return view('custom.verification-sent');
    }

    /**
     * Display success message.
     */
    public function success()
    {
        return view('volunteer-registration-success');
    }


    public function sendVerificationEmail(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified']);
        }

        $verificationUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->getKey(), 'hash' => hash_hmac('sha256', $user->email, config('app.key'))]
        );
        Mail::to($user->email)->send(new UserVerifyEmail($user, $verificationUrl));

        return response()->json(['message' => 'Verification email sent']);
    }

    public function verify(Request $request, $id)
    {
        $user = User::findOrFail($id);
        // Verify the signed URL and hash
        if (! hash_equals(
            hash_hmac('sha256', $user->email, config('app.key')),
            $request->query('hash')
        )) {
            return response()->json(['message' => 'Invalid verification link'], 403);
        }

        // Mark email as verified
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        Notification::make()
            ->title('You have successfully registered.')
            ->success()
            ->send();

        return redirect()->route('filament.admin.pages.dashboard')->with('status', 'Email verified successfully!');
    }


}
