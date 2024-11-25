<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Volunteer;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class VolunteerRegistrationController extends Controller
{
    /**
     * Show the registration form.
     */
    public function create()
    {
        $companies = Company::all(); // Fetch companies from the database
        return view('livewire.volunteer-registration', compact('companies'));
    }

    /**
     * Handle the form submission.
     */
    public function store(Request $request)
    {
        // dd($reuq['username'],
        //    $request['firstname'],
        //    $request['lastname'],
        //    $request['email'],
        //    Hash::make($request['password']),);
        // Validation
        $request = $request->validate([
            'username' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email',

            // 'birthday' => 'required|date',
            // 'organization' => 'required|string',
            // 'program_interest' => 'required|string',
            // 'emergency_contact_name' => 'required|string|max:255',
            // 'emergency_contact_number' => 'required|string|max:15',
            'password' => 'required|min:8|confirmed',
            // 'company_name' => 'nullable|string|required_if:toggle_type,company|max:255',
            // 'company_address' => 'nullable|string|required_if:toggle_type,company|max:255',
            // 'company_contact_number' => 'nullable|string|required_if:toggle_type,company|max:15',
            // 'company_representative' => 'nullable|string|required_if:toggle_type,company|max:255',
            // 'school_name' => 'nullable|string|required_if:toggle_type,school|max:255',
            // 'school_address' => 'nullable|string|required_if:toggle_type,school|max:255',
        ]);

        // Store volunteer data
        //$request['password'] = Hash::make($request['password']);

        User::create([
            'username' => $request['username'],
            'firstname' => $request['firstname'],
            'lastname' => $request['lastname'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        return redirect()->route('volunteer.registration.success');
    }

    /**
     * Display success message.
     */
    public function success()
    {
        return view('volunteer-registration-success');
    }
}
