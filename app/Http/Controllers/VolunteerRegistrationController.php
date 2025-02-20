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
use Filament\Notifications\Auth\VerifyEmail;
use Filament\Notifications\Notification;
use Filament\Facades\Filament;
use App\Settings\MailSettings;
use Illuminate\Support\Facades\Mail;
use App\Notifications\VerifyEmailNotification;
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

            // Define validation rules
            $rules = [
                // 'username' => 'required|string|max:255',
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
                'birthday' => 'required|date',
                'emergency_contact_name' => 'nullable|string|max:255',
                'emergency_contact_number' => 'nullable|string|max:15|regex:/^[0-9]+$/',
                'company_name' => 'nullable|string|max:255',
                'company_address' => 'nullable|string|max:255',
                'company_contact_number' => 'nullable|string|max:15|regex:/^[0-9]+$/',
                'school' => 'nullable|string|max:255',
                'school_address' => 'nullable|string|max:255',
                'affiliate_type_id' => 'required|integer|in:1,2,3',
                'company_id' => 'nullable|integer',
                'program_ids' => ['required', 'array', 'min:1'],
                'program_ids.*' => ['exists:programs,id'],
                'cluster_id' => 'nullable|integer',
                'is_company' => 'required|boolean', // Add validation rule for is_company
            ];

            // Perform validation

            DB::beginTransaction();
            try {
                $input = $request->all();
                if(isset($input['affiliate_type_id']) && $input['affiliate_type_id'] != 3 )
                {
                    $input['is_company'] = true;
                }
                else
                {
                    $input['is_company'] = false;
                }
                // Save data if validation passes
                $user = User::create([
                    'volunteer' => 1,
                    'username' => $input['username'] ?? null,
                    'email' => $input['email'],
                    'firstname' => $input['firstname'],
                    'lastname' => $input['lastname'],
                    'password' => Hash::make($input['password']),
                    'middle_name' => $input['middle_name'],
                    'birthday' => $input['birthday'],
                    'is_company' => $input['is_company'], // Set based on affiliate type
                    'company_name' => $input['company_name'] ?? null,
                    'school' => $input['school'] ?? null,
                    'school_address' => $input['school_address'] ?? null,
                    'emergency_contact_name' => $input['emergency_contact_name'],
                    'emergency_contact_number' => $input['emergency_contact_number'],
                    'affiliate_type_id' => $input['affiliate_type_id'],
                    'company_id' => $input['company_id'] ?? null,
                    'cluster_id' => $input['cluster_id'] ?? null,
                ]);

                // Assign volunteer role
                $role = Role::where('name', 'volunteer')->first();
                $user->assignRole($role);

                // Save programs with primary program
                foreach ($input['program_ids'] as $index => $programId) {
                    DB::table('program_volunteer')->insert([
                        'program_id' => $programId,
                        'volunteer_id' => $user->id,
                        'is_primary' => $index === 0, // First program is primary
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    if ($index === 0) {
                        DB::table('users')->where('id', $user->id)->update(['program_id' => $programId]);
                    }
                }

                $user->notify(new VerifyEmailNotification());

                DB::commit();
                return redirect()->route('verification.sent');

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Volunteer registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
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

        $user->notify(new VerifyEmailNotification());

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
