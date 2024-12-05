@extends('custom.layouts.app')
@section('title', 'Volunteer Registration')

@section('content')
    <style>
        .text-danger {
            color: #ffffff; /* White text */
            background-color: red; /* Bright red-orange background */
            font-size: 0.975rem; /* Equivalent to Tailwind's `text-sm` */
            border-radius: 5px; /* Optional: Slightly rounded corners */
        }
        .clip-path-custom {
            clip-path: polygon(0 0, 100% 0, 100% 100%, 50% 100%)
        }
        input:checked ~ .radio {
            color:white;
            background-color:  #2563eb;
        }
        .required:after{
            content:'*';
            color:whitesmoke;
            padding-left:5px;
        }
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

        <div class=" bg-[#FFFFFFE5] m-[-20vh] mb-8 xl:w-[80%] lg:w-[85%] md:w-[90%] sm:w-[95%] w-[98%] sm:w-[95%] z-10 bg-[#F55E1D] bg-cover lg:bg-right"  style="background: url('{{ asset('img/registration-bg.png') }}');">
                <div class="items-start justify-center min-h-[756px]">
                    <!-- Left Side -->
                    <form  action="{{ route('volunteer.form.store') }}" method="POST">
                        @csrf
                        <div id="step-1" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-[3fr_3fr_2fr]  ">
                            <div class=" text-white p-8 lg:p-12">
                                <h1 class="text-4xl font-bold mb-4">Become a Volunteer</h1>
                                <p class="text-2xl  mb-4">Ayala Corporate Citizenship and Volunteer Program</p>
                                <p class="text-2xl mb-4">Start your registration here.</p>

                                <div>
                                    <label class="block text-sm font-semibold required">Username</label>
                                    <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" name="username"/>
                                    <span class="text-danger text-red-400 text-sm username_err"></span>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold required">First Name</label>
                                    <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" name="firstname"/>
                                    <span class="text-danger text-red-400 text-sm firstname_err"></span>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold">Middle Name</label>
                                    <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" name="middle_name"/>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold required">Last Name</label>
                                    <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" name="lastname"/>
                                    <span class="text-danger text-red-400 text-sm lastname_err"></span>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold required">Email</label>
                                    <input type="email" class="w-full p-2 border border-gray-300 rounded text-black" name="email"/>
                                    <span class="text-danger text-red-400 text-sm email_err"></span>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold required">Birthday</label>
                                    <input type="date" class="w-full p-2 border border-gray-300 rounded text-black" name="birthday"/>
                                    <span class="text-danger text-red-400 text-sm birthday_err"></span>
                                </div>

                                <!-- Program Interest Dropdown -->
                                <div class="mb-8">
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
                                    <a href="#"  style="color: blue;" class="underline">Terms and Conditions</a>. Learn how we use your data in our
                                    <a href="#"  style="color: blue;" class="underline">Privacy Policy</a>.
                                </p>
                            </div>

                            <!-- Right Side (Multi-Step Form) -->
                            <div class="p-8 lg:p-12 flex items-start justify-start">
                                <div class="text-white w-full">
                                    <h2 class="text-3xl font-normal mb-12">Personal Information</h2>

                                    <!-- Multi-Step Form Structure -->

                                    <!-- Step 1 -->
                                    <div id="step-1" class="w-full">

                                        <!-- Toggle between Company and School -->
                                        <div class="inline-flex rounded-lg">
                                            <input type="radio" name="toggle_type" id="company_toggle" checked hidden onclick="toggleFields('company')" />
                                            <label for="company_toggle" class="radio text-center self-center py-2 px-4 rounded-lg cursor-pointer hover:opacity-75">Company</label>
                                        </div>
                                        <div class="inline-flex rounded-lg">
                                            <input type="radio" name="toggle_type" id="school_toggle" hidden onclick="toggleFields('school')" />
                                            <label for="school_toggle" class="radio text-center self-center py-2 px-4 rounded-lg cursor-pointer hover:opacity-75">School</label>
                                        </div>

                                        <!-- Company Fields -->
                                            <div id="company_fields" >

                                        <!-- Organization Radio Buttons -->
                                            <div class="mb-4">
                                                <label class="block mb-2 text-white font-semibold required">Please select your organization</label>
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
                                            <div class="" id="companySelectWrapper">
                                                <select required class="w-full p-2 bg-white text-gray-900 rounded" name="company_id" id="companySelect" class="form-select">
                                                    <option value="" disabled selected></option>
                                                    @foreach ($companies as $company)
                                                        <option value="{{ $company->id }}" data-cluster="{{ $company->cluster_id }}">{{ $company->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-semibold required">Company Name</label>
                                                <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" name="company_name" />
                                            </div>
                                            <div>
                                                <label class="block text-sm font-semibold required">Company Address</label>
                                                <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" name="company_address" />
                                            </div>
                                            <div>
                                                <label class="block text-sm font-semibold required">Company Contact Number</label>
                                                <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" name="company_contact_number" />
                                            </div>
                                            <div>
                                                <label class="block text-sm font-semibold required">Company Representative</label>
                                                <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" name="company_representative" />
                                            </div>
                                        </div>

                                        <!-- School Fields -->
                                        <div id="school_fields" class="hidden">
                                            <div>
                                                <label class="block text-sm font-semibold required">School Name</label>
                                                <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" name="school" />
                                            </div>
                                            <div>
                                                <label class="block text-sm font-semibold required">School Address</label>
                                                <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" name="school_address" />
                                            </div>
                                        </div>
                                        <button type="button" onclick="nextStep(2)" class="w-full mt-8 py-3 bg-blue-600 text-white font-bold rounded hover:bg-blue-700">
                                            Next
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="step-2" class="hidden">
                            <div   class="p-8 lg:p-12 flex items-start justify-start">
                                <div class="text-white w-full">
                                    <!-- Step 2 -->
                                    <div class="w-1/2">
                                        <h3 class="text-4xl font-bold mb-4">In Case of Emergency Contact Details</h3>
                                        <div>
                                            <label class="block text-sm font-semibold required">Emergency Contact Name</label>
                                            <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" name="emergency_contact_name"/>
                                            <span class="text-danger text-red-400 text-sm text-sm emergency_contact_name_err"></span>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold required">Emergency Contact Number</label>
                                            <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" name="emergency_contact_number"/>
                                            <span class="text-danger text-red-400 text-sm emergency_contact_number_err"></span>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold required">Password</label>
                                            <input type="password" class="w-full p-2 border border-gray-300 rounded text-black" name="password"/>
                                            <span class="text-danger text-red-400 text-sm password_err"></span>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold required">Confirm Password</label>
                                            <input type="password" class="w-full p-2 border border-gray-300 rounded text-black" name="passwordConfirmation"/>
                                            <span class="text-danger text-red-400 text-sm password_err"></span>
                                        </div>

                                        <div class="flex justify-between">
                                            <button type="button" onclick="nextStep(1)" class="w-full mt-6 py-3 bg-blue-600 text-white font-bold rounded hover:bg-blue-700">Previous</button>
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
                </div>
        </div>
    </div>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <script>
        function nextStep(step) {
            // Hide all steps
            if(step == 2){
                $('#step-1').addClass('hidden')
                $('#step-2').removeClass('hidden')
            }
            else{
                $('#step-2').addClass('hidden')
                $('#step-1').removeClass('hidden')
            }
            // for (let i = 1; i <= 2; i++) {
            //     document.getElementById('step-' + i).classList.add('hidden');
            // }
            // // Show the desired step
            // document.getElementById('step-' + step).classList.remove('hidden');
        }

        function toggleFields(type) {
            const companyFields = document.getElementById('company_fields');
            const schoolFields = document.getElementById('school_fields');

            if (type === 'school') {
                companyFields.classList.add('hidden');
                schoolFields.classList.remove('hidden');
            } else {
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
@endsection

