{{-- <div class="fi-layout min-h-screen max-h-auto px-8 max-w-5xl mx-auto mb-5" id="volunteer-registration-page">
    <div class="justify-center items-center mx-auto p-4">
        <h1 class="fi-header-heading text-5xl font-bold tracking-tight text-gray-950 sm:text-5xl dark:text-white my-4">
            Become a Volunteer
        </h1>

        <form wire:submit="submit" class="mb-8">
            {{ $this->form }}

            <x-filament::button wire:click="submit" style="float:right; margin-top:25px">
                Register
            </x-filament::button>
        </form>
    </div>
</div> --}}


<style>
    .clip-path-custom {
		clip-path: polygon(0 0, 100% 0, 100% 100%, 50% 100%)
	}
    </style>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Registration</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMc8yAqPA6rrAnmYqZmeYhWl3LSXywMk8RseHgh" crossorigin="anonymous"> --}}
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        .hidden {
            display: none;
        }
    </style>

</head>
<body class="flex justify-center items-center min-h-screen bg-grayx`-200">
    <div class="min-h-screen flex items-center justify-center bg-white">
        <div class="max-w-5xl w-full grid grid-cols-1 lg:grid-cols-2 "style="background-image: url('http://ayala-workflow.test/images/VR.jpg');
 background-position: center; background-repeat: no-repeat; background-size: cover;">
            <!-- Left Side -->
            <div class=" text-white p-8 lg:p-12">
                <h1 class="text-4xl font-bold mb-4">Become a Volunteer</h1>
                <p class="text-lg mb-6">Ayala Corporate Citizenship and Volunteer Program</p>
                <p class="text-md mb-8">Start your registration here.</p>

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Organization Radio Buttons -->
                <div class="mb-6">
                    <label class="block mb-2 text-white font-semibold">Please select your organization*</label>
                    <div class="flex items-center mb-2">
                        <input type="radio" id="ayala_employee" name="organization" class="mr-2" />
                        <label for="ayala_employee" class="text-white">Ayala Employee</label>
                    </div>
                    <div class="flex items-center mb-2">
                        <input type="radio" id="external_partner" name="organization" class="mr-2" />
                        <label for="external_partner" class="text-white">Accredited External Partner</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="non_ayala" name="organization" class="mr-2" />
                        <label for="non_ayala" class="text-white">Non-Ayala Group</label>
                    </div>
                </div>

                <div class="mb-6">
                    <select class="w-full p-2 bg-white text-gray-900 rounded" name="company_id" required>
                        <option value="" disabled selected></option>
                        @foreach(\App\Models\Company::all() as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>


                <!-- Program Interest Dropdown -->
                <div class="mb-6">
                    <label class="block mb-2 text-white font-semibold">What programs are you interested in?</label>
                    <select class="w-full p-2 bg-white text-gray-900 rounded">
                        <option>Education</option>
                        <option>Health</option>
                        <option>Environment</option>
                    </select>
                </div>

                <!-- Privacy and Terms Notice -->
                <p class="text-sm mt-8 text-white">
                    We will not share your information without your permission. By signing up you agree to our
                    <a href="#" class="underline">Terms and Conditions</a>. Learn how we use your data in our
                    <a href="#" class="underline">Privacy Policy</a>.
                </p>
            </div>

            <!-- Right Side (Multi-Step Form) -->
            <div class="relative pr-[45%]">
                <div class="absolute inset-0 "></div>
                  <div class="relative p-8 text-white">

                    <h2 class="text-xl font-semibold mb-4">Personal Information</h2>

                    <!-- Multi-Step Form Structure -->
                        <form action="{{ route('volunteer.form.store') }}" method="POST">
                            @csrf
                            <!-- Step 1 -->
                            <div id="step-1">
                                <div>
                                    <label class="block text-sm font-semibold">Username*</label>
                                    <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" name="username"/>
                                    @error('username') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold">First Name*</label>
                                    <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" wire:model="firstname"/>
                                    @error('firstname') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold">Middle Name</label>
                                    <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" wire:model="middle_name"/>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold">Last Name*</label>
                                    <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" wire:model="lastname"/>
                                    @error('lastname') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold">Email*</label>
                                    <input type="email" class="w-full p-2 border border-gray-300 rounded text-black" wire:model="email"/>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold">Birthday*</label>
                                    <input type="date" class="w-full p-2 border border-gray-300 rounded text-black" wire:model="birthday"/>
                                </div>
                                <button type="button" onclick="nextStep(2)" class="w-full mt-6 py-3 bg-blue-600 text-white font-bold rounded hover:bg-blue-700">
                                    Next
                                </button>
                            </div>

                            <!-- Step 2 -->
                            <div id="step-2" class="hidden">
                                <!-- Toggle between Company and School -->
                                <div class="mt-4">
                                    <label class="block text-sm font-semibold">Select Type*</label>
                                    <div class="flex items-center mb-2">
                                        <input type="radio" id="toggle_company" name="toggle_type" value="company" class="mr-2" onclick="toggleFields()" />
                                        <label for="toggle_company">Company</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" id="toggle_school" name="toggle_type" value="school" class="mr-2" onclick="toggleFields()" />
                                        <label for="toggle_school">School</label>
                                    </div>
                                </div>

                                <!-- Company Fields -->
                                <div id="company_fields" class="hidden">
                                    <div>
                                        <label class="block text-sm font-semibold">Company Name*</label>
                                        <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" wire:model="company_name"/>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold">Company Address*</label>
                                        <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" wire:model="company_address"/>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold">Company Contact Number*</label>
                                        <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" wire:model="company_contact_number"/>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold">Company Representative*</label>
                                        <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" wire:model="company_representative"/>
                                    </div>
                                </div>

                                <!-- School Fields -->
                                <div id="school_fields" class="hidden">
                                    <div>
                                        <label class="block text-sm font-semibold">School Name*</label>
                                        <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" wire:model="school"/>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold">School Address*</label>
                                        <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" wire:model="school_address"/>
                                    </div>
                                </div>
                                <div class="flex justify-between">
                                    <button type="button" onclick="nextStep(1)" class="w-full mt-6 py-3 bg-blue-600 text-white font-bold rounded hover:bg-blue-700">Previous</button>
                                </div>
                                <div class="flex justify-between">
                                    <button type="button" onclick="nextStep(3)" class="w-full mt-6 py-3 bg-blue-600 text-white font-bold rounded hover:bg-blue-700">Next</button>
                                </div>
                            </div>

                            <!-- Step 3 -->
                            <div id="step-3" class="hidden">

                                <h3 class="text-lg font-semibold mt-6">In Case of Emergency Contact Details</h3>
                                <div>
                                    <label class="block text-sm font-semibold">Emergency Contact Name*</label>
                                    <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" wire:model="emergency_contact_name"/>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold">Emergency Contact Number*</label>
                                    <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" wire:model="emergency_contact_number"/>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold">Password*</label>
                                    <input type="password" class="w-full p-2 border border-gray-300 rounded text-black" wire:model="password"/>
                                    @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold">Confirm Password*</label>
                                    <input type="password" class="w-full p-2 border border-gray-300 rounded text-black" wire:model="passwordConfirmation"/>
                                </div>
                                <div class="flex justify-between">
                                    <button type="button" onclick="nextStep(2)" class="w-full mt-6 py-3 bg-blue-600 text-white font-bold rounded hover:bg-blue-700">Previous</button>
                                </div>
                                <div class="flex justify-between">
                                    <button type="submit" class="w-full mt-6 py-3 bg-blue-600 text-white font-bold rounded hover:bg-blue-700">Register</button>
                                </div>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function nextStep(step) {
            // Hide all steps
            for (let i = 1; i <= 3; i++) {
                document.getElementById('step-' + i).classList.add('hidden');
            }
            // Show the desired step
            document.getElementById('step-' + step).classList.remove('hidden');
        }

        function toggleFields() {
            const companyFields = document.getElementById('company_fields');
            const schoolFields = document.getElementById('school_fields');

            if (document.getElementById('toggle_company').checked) {
                companyFields.classList.remove('hidden');
                schoolFields.classList.add('hidden');
            } else if (document.getElementById('toggle_school').checked) {
                schoolFields.classList.remove('hidden');
                companyFields.classList.add('hidden');
            }
        }
    </script>
</body>
</html>

