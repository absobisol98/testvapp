@extends('custom.layouts.app')

@section('content')

    <style>
        /* body {
            font-family: 'Roboto', sans-serif;
        }
        .hidden {
            display: none;
        } */
        .text-danger {
        color: red;
        font-size: 0.875rem; /* Equivalent to Tailwind's `text-sm` */
    }
        /* .clip-path-custom {
            clip-path: polygon(0 0, 100% 0, 100% 100%, 50% 100%)
        }
        .clip-path-custom {
            clip-path: polygon(0 0, 100% 0, 100% 100%, 50% 100%)
        } */
    </style>

    <div id="mainLandingPage" class="w-full flex flex-col items-center justify-center">

        {{-- Desktop: Hero Banner Section --}}
        <section class="hidden lg:block h-[100vh] w-full bg-[#03498D]">
            <div class="grid grid-cols-5">
                <div class="flex justify-center items-center col-span-2 pl-5 pr-0 lg:pl-[10%] pr-10 md:pl-14 pr-5">
                    <div class="container whitespace-pre-line text-white">
                        <p class="font-[700] text-[70px] leading-none">Your involvement is <br> important to us!</p>
                        <p class="font-[400] text-[28px]">Ayala Corporate Citizenship and Volunteer Program</p>
                    </div>
                </div>

                <div class="h-[100vh] col-span-3 clip-path-custom" style="background: url('{{ asset('img/ayala.png') }}') no-repeat center center; background-size: cover;">
                </div>
            </div>
        </section>
        {{-- Tablet & mobile: Hero Banner Section --}}
        <section class="block lg:hidden h-[100vh] w-full flex flex-col items-center justify-center p-[5%] text-white relative" style="background: url('{{ asset('img/ayala.png') }}') no-repeat center center; background-size: cover;">
            <div class="absolute inset-0 bg-[#03498D] opacity-50"></div>
            <div class="flex flex-col items-center justify-center relative text-center z-10">
                <p class="font-[700] text-[70px] leading-none">Your involvement is important to us!</p>
                <p class="font-[400] text-[28px]">Ayala Corporate Citizenship and Volunteer Program</p>
            </div>
        </section>

        <div class=" bg-[#FFFFFFE5] m-[-40vh] mb-8 xl:w-[55%] lg:w-[85%] md:w-[90%] sm:w-[95%] z-10 ">
            <form  action="{{ route('volunteer.form.store') }}" method="POST">
                @csrf
                <div class="flex flex-col md:flex-row items-center justify-center " style="background-image: url('http://ayala-workflow.test/images/VR.jpg'); background-position: center; background-repeat: no-repeat; background-size: cover;">
                    <!-- Left Side -->
                    <div class=" text-white p-8 lg:p-12">
                        <h1 class="text-4xl font-bold mb-4">Become a Volunteer</h1>
                        <p class="text-lg mb-6">Ayala Corporate Citizenship and Volunteer Program</p>
                        <p class="text-md mb-8">Start your registration here.</p>

                        <!-- Organization Radio Buttons -->
                            <div class="mb-6">
                                <label class="block mb-2 text-white font-semibold">Please select your organization*</label>
                                <div class="flex items-center mb-2">
                                    <input type="radio" id="ayala_employee" name="affiliate_type_id" checked value="1" class="mr-2" onchange="updateCompanies()" />
                                    <label for="ayala_employee" class="text-white">Ayala Employee</label>
                                </div>
                                <div class="flex items-center mb-2">
                                    <input type="radio" id="external_partner" name="affiliate_type_id" value="2" class="mr-2" onchange="updateCompanies()" />
                                    <label for="external_partner" class="text-white">Accredited External Partner</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" id="non_ayala" name="affiliate_type_id" value="3" class="mr-2" onchange="updateCompanies()" />
                                    <label for="non_ayala" class="text-white">Non-Ayala Group</label>
                                </div>
                            </div>

                            <!-- Company Select Dropdown -->
                            <div class="mb-6" id="companySelectWrapper">
                                <select required class="w-full p-2 bg-white text-gray-900 rounded" name="company_id" id="companySelect" class="form-select">
                                    <option value="" disabled selected></option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}" data-cluster="{{ $company->cluster_id }}">{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        <!-- Program Interest Dropdown -->
                        <div class="mb-6">
                            <label class="block mb-2 text-white font-semibold">What programs are you interested in?</label>
                            <select class="w-full p-2 bg-white text-gray-900 rounded" name="program_id">
                                @foreach($programs as $program)
                                    <option value="{{ $program->id }}">{{ $program->name }}</option>
                                @endforeach
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
                    <div class="relative pr-[35%]">
                        <div class=" p-8 text-white">
                            <h2 class="text-xl font-semibold mb-4">Personal Information</h2>

                            <!-- Multi-Step Form Structure -->

                            <!-- Step 1 -->
                            <div id="step-1" class="w-full">
                                <div>
                                    <label class="block text-sm font-semibold">Username*</label>
                                    <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" name="username"/>
                                    <span class="text-danger text-red-400 text-sm text-red-400 text-sm username_err"></span>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold">First Name*</label>
                                    <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" name="firstname"/>
                                    <span class="text-danger text-red-400 text-sm firstname_err"></span>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold">Middle Name</label>
                                    <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" name="middle_name"/>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold">Last Name*</label>
                                    <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" name="lastname"/>
                                    <span class="text-danger text-red-400 text-sm lastname_err"></span>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold">Email*</label>
                                    <input type="email" class="w-full p-2 border border-gray-300 rounded text-black" name="email"/>
                                    <span class="text-danger text-red-400 text-sm email_err"></span>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold">Birthday*</label>
                                    <input type="date" class="w-full p-2 border border-gray-300 rounded text-black" name="birthday"/>
                                    <span class="text-danger text-red-400 text-sm birthday_err"></span>
                                </div>
                                <button type="button" onclick="nextStep(2)" class="w-full mt-6 py-3 bg-blue-600 text-white font-bold rounded hover:bg-blue-700">
                                    Next
                                </button>
                            </div>

                            <!-- Step 2 -->
                            <div id="step-2" class="hidden">
                                <!-- Toggle between Company and School -->
                                <div class="mt-4">
                                    <label class="block text-sm font-semibold mb-2">Select Type*</label>
                                    <div class="flex items-center">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" id="toggle_type" class="sr-only peer" onclick="toggleFields()" />
                                            <div class="w-20 h-10 bg-gray-200 peer-focus:ring-2 peer-focus:ring-blue-500 peer dark:bg-gray-700 peer-checked:bg-blue-600 peer-checked:after:translate-x-10 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border after:border-gray-300 after:h-8 after:w-8 after:transition-all dark:border-gray-600"></div>
                                            <span class="ml-3 text-sm font-semibold text-gray-900 dark:text-gray-300" id="toggle_label"></span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Company Fields -->
                                <div id="company_fields" class="hidden">
                                    <div>
                                        <label class="block text-sm font-semibold">Company Name*</label>
                                        <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" name="company_name" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold">Company Address*</label>
                                        <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" name="company_address" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold">Company Contact Number*</label>
                                        <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" name="company_contact_number" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold">Company Representative*</label>
                                        <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" name="company_representative" />
                                    </div>
                                </div>

                                <!-- School Fields -->
                                <div id="school_fields" class="hidden">
                                    <div>
                                        <label class="block text-sm font-semibold">School Name*</label>
                                        <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" name="school" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold">School Address*</label>
                                        <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" name="school_address" />
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
                                    <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" name="emergency_contact_name"/>
                                    <span class="text-danger text-red-400 text-sm text-sm emergency_contact_name_err"></span>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold">Emergency Contact Number*</label>
                                    <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" name="emergency_contact_number"/>
                                    <span class="text-danger text-red-400 text-sm emergency_contact_number_err"></span>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold">Password*</label>
                                    <input type="password" class="w-full p-2 border border-gray-300 rounded text-black" name="password"/>
                                    <span class="text-danger text-red-400 text-sm password_err"></span>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold">Confirm Password*</label>
                                    <input type="password" class="w-full p-2 border border-gray-300 rounded text-black" name="passwordConfirmation"/>
                                    <span class="text-danger text-red-400 text-sm passwordConfirmation_err"></span>
                                </div>
                                <div class="flex justify-between">
                                    <button type="button" onclick="nextStep(2)" class="w-full mt-6 py-3 bg-blue-600 text-white font-bold rounded hover:bg-blue-700">Previous</button>
                                </div>
                                <div class="flex justify-between">
                                    <button id="btn-register"
                                            wire:confirm="Are you sure you want to save this form?"
                                            class="w-full mt-6 py-3 bg-blue-600 text-white font-bold rounded hover:bg-blue-700">Register
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </form>

            <script src="https://cdn.tailwindcss.com"></script>
            <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
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
                    const toggleSwitch = document.getElementById('toggle_type');
                    const companyFields = document.getElementById('company_fields');
                    const schoolFields = document.getElementById('school_fields');
                    const toggleLabel = document.getElementById('toggle_label');

                    if (toggleSwitch.checked) {
                        toggleLabel.textContent = 'School';
                        companyFields.classList.add('hidden');
                        schoolFields.classList.remove('hidden');
                    } else {
                        toggleLabel.textContent = 'Company';
                        schoolFields.classList.add('hidden');
                        companyFields.classList.remove('hidden');
                    }

                }

                function updateCompanies() {
                    const selectedOrganization = document.querySelector('input[name="affiliate_type_id"]:checked')?.id;
                    const companySelect = document.getElementById('companySelect');
                    const companySelectWrapper = document.getElementById('companySelectWrapper');

                    // If "Non-Ayala Group" is selected, hide the company select dropdown
                    if (selectedOrganization === 'non_ayala') {
                        companySelectWrapper.style.display = 'none';
                    } else {
                        companySelectWrapper.style.display = 'block';

                        // Filter and show companies based on the selected organization
                        const allOptions = companySelect.querySelectorAll('option');
                        allOptions.forEach(option => {
                            const clusterId = option.getAttribute('data-cluster');

                            if (selectedOrganization === 'ayala_employee' && clusterId == 1) {
                                option.style.display = 'block'; // Show only cluster_id 1
                            } else if (selectedOrganization === 'external_partner' && clusterId >= 2 && clusterId <= 8) {
                                option.style.display = 'block'; // Show cluster_id between 2 and 8
                            } else {
                                option.style.display = 'none'; // Hide all other options
                            }
                        });
                    }
                }

                $(document).ready(function() {
                    $("#btn-register").click(function(e) {
                        e.preventDefault();
                            var _token = $("input[name='_token']").val();
                            var username = $("input[name='username']").val();
                            var email = $("input[name='email']").val();
                            var firstname = $("input[name='firstname']").val();
                            var lastname = $("input[name='lastname']").val();
                            var password = $("input[name='password']").val();
                            var volunteer = $("input[name='volunteer']").val();
                            var middle_name = $("input[name='middle_name']").val();
                            var birthday = $("input[name='birthday']").val();
                            var is_company = $("input[name='is_company']").prop("checked") ? 1 : 0;
                            var company_name = $("input[name='company_name']").val();
                            var company_address = $("input[name='company_address']").val();
                            var company_contact_number = $("input[name='company_contact_number']").val();
                            var company_representative = $("input[name='company_representative']").val();
                            var company_email = $("input[name='company_email']").val();
                            var school = $("input[name='school']").val();
                            var school_address = $("input[name='school_address']").val();
                            var emergency_contact_name = $("input[name='emergency_contact_name']").val();
                            var emergency_contact_number = $("input[name='emergency_contact_number']").val();
                            var affiliate_type_id = $("input[name='affiliate_type_id']").val();
                            var company_id = $("select[name='company_id']").val();
                            var program_id = $("select[name='program_id']").val();

                        $.ajax({
                            url: "{{ route('volunteer.form.store') }}",
                            type: 'POST',
                            data: {
                                _token: _token,
                                username: username,
                                email: email,
                                firstname: firstname,
                                lastname: lastname,
                                password: password,
                                volunteer: volunteer,
                                middle_name: middle_name,
                                birthday: birthday,
                                is_company: is_company,
                                company_name: company_name,
                                company_address: company_address,
                                company_contact_number: company_contact_number,
                                company_representative: company_representative,
                                company_email: company_email,
                                school: school,
                                school_address: school_address,
                                emergency_contact_name: emergency_contact_name,
                                emergency_contact_number: emergency_contact_number,
                                affiliate_type_id: $("input[type=radio][name=affiliate_type_id]:checked").val(),
                                company_id: company_id,
                                program_id: program_id
                            },
                            success: function (data) {
                                if ($.isEmptyObject(data.error)) {
                                    window.location.href = '{{route("filament.admin.auth.login")}}';

                                } else {
                                    // Handling errors in the `else` block
                                    var formErr = data.error;
                                    for (var err in formErr) {
                                        $('.' + err + '_err').html(formErr[err][0]); // Display errors dynamically
                                    }
                                }
                            },
                            error: function (error) {
                                console.log(error); // Debugging
                                var formErr = error.responseJSON.errors;
                                for (var err in formErr) {
                                    $('.' + err + '_err').html(formErr[err][0]); // Handle server-side errors
                                }
                            }
                        });
                    });


                    function printErrorMsg(msg) {
                            $(".print-error-msg").find("ul").html('');
                            $(".print-error-msg").css('display', 'block');
                            $.each(msg, function(key, value) {
                                $(".print-error-msg").find("ul").append('<li>' + value + '</li>');
                        });
                    }
                });
            </script>
        </div>
    </div>
@endsection

