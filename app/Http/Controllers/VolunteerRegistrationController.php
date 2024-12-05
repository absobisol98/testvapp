<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Company;
use App\Models\Program;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class VolunteerRegistrationController extends Controller
{
    /**
     * Show the registration form.
     */
    public function view()
    {
        // Fetch companies and programs from the database
        $companies = Company::all();
        $programs = Program::all();

        // Pass both companies and programs to the view
        return view('custom.volunteer-registration', compact('companies', 'programs'));
    }


    /**
     * Handle AJAX form submission with validation.
     */
    public function store(Request $request)
    {
        // Define validation rules
        $rules = [
            'username' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'birthday' => 'required|date',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_number' => 'nullable|string|max:15',
            'company_name' => 'nullable|string|max:255',
            'company_address' => 'nullable|string|max:255',
            'company_contact_number' => 'nullable|string|max:15',
            'school' => 'nullable|string|max:255',
            'school_address' => 'nullable|string|max:255',
            'affiliate_type_id' => 'nullable|integer',
            'company_id' => 'nullable|integer',
            'program_id' => 'nullable|integer',
        ];

        // Perform validation
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }else
        {
            $input = $request->all();
            // Save data if validation passes
        $user = User::create([
            'email_verified_at' => now(), // Temporary;for testing only
            'volunteer' => 1, // Volunteer
            'username' => $input['username'],
            'email' => $input['email'],
            'firstname' => $input['firstname'],
            'lastname' => $input['lastname'],
            'password' => Hash::make($input['password']),
            'middle_name' => $input['middle_name'],
            'birthday' => $input['birthday'],
            'is_company' => $input['is_company'],
            'company_name' => $input['company_name'],
            'company_address' => $input['company_address'],
            'company_contact_number' => $input['company_contact_number'],
            'school' => $input['school'],
            'school_address' => $input['school_address'],
            'emergency_contact_name' => $input['emergency_contact_name'],
            'emergency_contact_number' => $input['emergency_contact_number'],
            'affiliate_type_id' => $input['affiliate_type_id'],
            'company_id' => $input['company_id'],
            'program_id' =>  $input['program_id'],
        ]);

            $role = Role::where('name','volunteer')->first();

            DB::table('model_has_roles')->insert([
                'role_id' => $role->id,
                'model_id' => $user->id,
                'model_type' => 'App\Models\User',
            ]);
            // Return success response
            return response()->json(['success' => 'Volunteer registration completed successfully.']);
        }




    }

    /**
     * Display success message.
     */
    public function success()
    {
        return view('volunteer-registration-success');
    }
}
