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

          /* Make selected text black */
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            color: black !important;
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

        <div class="bg-[#FFFFFFE5] m-[-20vh] mb-8 xl:w-[80%] lg:w-[85%] md:w-[90%] sm:w-[95%] w-[98%] z-10 bg-[#F55E1D] bg-cover" style="background: url('{{ asset('img/registration-bg.png') }}') no-repeat right center; background-size: cover;">
                <div class="items-start justify-center min-h-[756px]">
                    <!-- Left Side -->
                    <form  action="{{ route('volunteer.form.store') }}" method="POST">
                        @csrf
                        <div id="step-1" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-[3fr_3fr_2fr]  ">
                            <div class=" text-white p-8 lg:p-12">
                                <h1 class="text-4xl font-bold mb-4">Become a Volunteer</h1>
                                <p class="text-2xl  mb-4">Ayala Corporate Citizenship and Volunteer Program</p>
                                <p class="text-2xl mb-4">Start your registration here.</p>

                                {{-- <div>
                                    <label class="block text-sm font-semibold required">Username</label>
                                    <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" name="username"/>
                                    <span class="text-danger text-red-400 text-sm username_err"></span>
                                </div> --}}
                                <div>
                                    <label class="block text-sm font-semibold required">First Name</label>
                                    <input type="text" class="shadow-lg w-full p-2 border border-gray-300 rounded text-black" name="firstname"/>
                                    <span class="text-danger text-red-400 text-sm firstname_err"></span>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold">Middle Name</label>
                                    <input type="text" class="shadow-lg w-full p-2 border border-gray-300 rounded text-black" name="middle_name"/>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold required">Last Name</label>
                                    <input type="text" class="shadow-lg w-full p-2 border border-gray-300 rounded text-black" name="lastname"/>
                                    <span class="text-danger text-red-400 text-sm lastname_err"></span>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold required">Email</label>
                                    <input type="email" class="shadow-lg w-full p-2 border border-gray-300 rounded text-black" name="email"/>
                                    <span class="text-danger text-red-400 text-sm email_err"></span>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold required">Birthday</label>
                                    <input type="date" class="shadow-lg w-full p-2 border border-gray-300 rounded text-black" name="birthday"/>
                                    <span class="text-danger text-red-400 text-sm birthday_err"></span>
                                </div>

                                <!-- Program Interest Dropdown -->
                                <div class="mb-8">
                                    <label class="block mb-2 text-white font-semibold">What programs are you interested in?</label>
                                    <select id="program-select" class="shadow-lg w-full p-2 bg-white text-gray-900 rounded"
                                    name="program_ids[]"
                                    multiple
                                    required>
                                <option value="">Select programs</option>
                                @foreach($programs as $program)
                                    <option value="{{ $program->id }}">{{ $program->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger program_ids_err"></span>

                                </div>

                                <!-- Privacy and Terms Notice -->
                                <p class="text-sm mt-8 text-white">
                                    We will not share your information without your permission. By signing up you agree to our
                                    <a href="{{ route('terms-and-conditions') }}"  style="color: blue;" class="underline">Terms and Conditions</a>. Learn how we use your data in our
                                    <a href="{{ route('data-privacy-policy') }}"  style="color: blue;" class="underline">Privacy Policy</a>.
                                </p>
                            </div>

                            <!-- Right Side (Multi-Step Form) -->
                            <div class="p-8 lg:p-12 flex items-start justify-start">
                                <div class="text-white w-full">
                                    <h2 class="text-3xl font-normal mb-8">Personal Information</h2>

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
                                                    <input type="radio" id="ayala_employee" name="affiliate_type_id" checked value="1" class="mr-2" onchange="updateDropdowns()" />
                                                    <label for="ayala_employee" class="text-white">Ayala Employee</label>
                                                </div>
                                                <div class="flex items-center mb-2">
                                                    <input type="radio" id="external_partner" name="affiliate_type_id" value="2" class="mr-2" onchange="updateDropdowns()" />
                                                    <label for="external_partner" class="text-white">Accredited External Partner</label>
                                                </div>
                                                <div class="flex items-center">
                                                    <input type="radio" id="non_ayala" name="affiliate_type_id" value="3" class="mr-2" onchange="updateDropdowns()" />
                                                    <label for="non_ayala" class="text-white">Non-Ayala Group</label>
                                                </div>
                                            </div>

                                            <!-- Cluster Select Dropdown -->

                                            <div class="" id="clusterSelectWrapper">
                                                <label class="block text-sm font-semibold required">Cluster</label>
                                                <select required class="shadow-lg w-full p-2 bg-white text-gray-900 rounded" name="cluster_id" id="clusterSelect" class="form-select">
                                                    <option value="" disabled selected></option>
                                                    @foreach ($clusters as $cluster)
                                                        <option value="{{ $cluster->id }}">{{ $cluster->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Company Select Dropdown -->
                                            <div class="" id="companySelectWrapper">
                                                <label class="block text-sm font-semibold required">Company Name</label>
                                                <select required class="shadow-lg w-full p-2 bg-white text-gray-900 rounded" name="company_id" id="companySelect" class="form-select">
                                                    <option value="" disabled selected></option>
                                                    @foreach ($companies as $company)
                                                        <option value="{{ $company->id }}" data-cluster="{{ $company->cluster_id }}">{{ $company->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{-- <div>
                                                <label class="block text-sm font-semibold required">Company Name</label>
                                                <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" name="company_name" />
                                            </div> --}}

                                        </div>

                                        <!-- School Fields -->
                                        <div id="school_fields" class="hidden">
                                            <div>
                                                <label class="block text-sm font-semibold required">School Name</label>
                                                <input type="text" class="shadow-lg w-full p-2 border border-gray-300 rounded text-black" name="school" />
                                            </div>
                                            <div>
                                                <label class="block text-sm font-semibold required">School Address</label>
                                                <input type="text" class="shadow-lg w-full p-2 border border-gray-300 rounded text-black" name="school_address" />
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold required">Emergency Contact Name</label>
                                            <input type="text" class="shadow-lg w-full p-2 border border-gray-300 rounded text-black" name="emergency_contact_name"/>
                                            <span class="text-danger text-red-400 text-sm text-sm emergency_contact_name_err"></span>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold required">Emergency Contact Number</label>
                                            <input type="text" class="shadow-lg w-full p-2 border border-gray-300 rounded text-black"
                                                name="emergency_contact_number" pattern="[0-9]*" inputmode="numeric"
                                                maxlength="15"
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 15)"
                                                required />
                                            <span class="text-danger text-red-400 text-sm emergency_contact_number_err"></span>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold required">Password</label>
                                            <input type="password" class="shadow-lg w-full p-2 border border-gray-300 rounded text-black" name="password"/>
                                            <span class="text-danger text-red-400 text-sm password_err"></span>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold required">Confirm Password</label>
                                            <input type="password" class="shadow-lg w-full p-2 border border-gray-300 rounded text-black" name="passwordConfirmation"/>
                                            <span class="text-danger text-red-400 text-sm password_err"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <button id="btn-register"
                                                    wire:confirm="Are you sure you want to save this form?"
                                                    class="w-full mt-6 py-3 bg-blue-600 text-white font-bold rounded hover:bg-blue-700">Register
                                            </button>
                                        </div>

                                        <div>
                                            <input id="checkbox" type="checkbox" required />
                                            <label class="text-color" for="checkbox">
                                                &nbsp; I agree to these
                                                <a href="{{ route('terms-and-conditions') }}" class="hover:underline text-color">Terms and Conditions</a>.
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="step-2" class="hidden">
                            <div   class="p-8 lg:p-12 flex items-start justify-start">
                                <div class="text-white w-full">
                                    <!-- Step 2 -->
                                    <div class="md:w-1/2 w-full">

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

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

        $(document).ready(function() {
        $('#program-select').select2({
            placeholder: "Select programs",
            allowClear: true
        });
    });

    //     $(document).ready(function() {
    //     $('#program-select').select2({
    //         placeholder: "Select programs",
    //         allowClear: true
    //     });
    // });

        function updateDropdowns() {
            const selectedOrganization = document.querySelector('input[name="affiliate_type_id"]:checked')?.id;
            const clusterSelect = document.getElementById('clusterSelect');
            const clusterSelectWrapper = document.getElementById('clusterSelectWrapper');
            const companySelect = document.getElementById('companySelect');
            const companySelectWrapper = document.getElementById('companySelectWrapper');

            // Show or hide the cluster dropdown based on the selected organization
            if (selectedOrganization === 'ayala_employee') {
                clusterSelectWrapper.style.display = 'block'; // Show cluster dropdown
            } else {
                clusterSelectWrapper.style.display = 'none'; // Hide cluster dropdown
            }

            // Show or hide the company dropdown based on the selected organization
            if (selectedOrganization === 'non_ayala') {
                companySelectWrapper.style.display = 'none'; // Hide company dropdown
            } else {
                companySelectWrapper.style.display = 'block'; // Show company dropdown
            }

            // Update company dropdown based on selected cluster
            clusterSelect.addEventListener('change', function () {
                const selectedClusterId = clusterSelect.value;
                const allOptions = companySelect.querySelectorAll('option');

                allOptions.forEach(option => {
                    const clusterId = option.getAttribute('data-cluster');

                    if (!selectedClusterId || clusterId === selectedClusterId) {
                        option.style.display = 'block'; // Show matching companies
                    } else {
                        option.style.display = 'none'; // Hide non-matching companies
                    }
                });

                // Reset selected company
                companySelect.value = '';
        });
    }

    // Add event listeners to update dropdowns when the organization changes
        document.querySelectorAll('input[name="affiliate_type_id"]').forEach(radio => {
            radio.addEventListener('change', updateDropdowns);
    });

    // Call the function on page load to ensure the initial state is set correctly
        updateDropdowns();


        $(document).ready(function() {
            // Prevent default form submission
            $('form').on('submit', function(e) {
                e.preventDefault();
            });

            $("#btn-register").click(function(e) {
                e.preventDefault();
                let isValid = validateForm();

                if (isValid) {
                    submitForm();
                }
            });

            function validateForm() {
                // Clear previous errors
                $('.text-danger').html('');
                $('.invalid-field').removeClass('invalid-field');

                let isValid = true;
                const requiredFields = {
                    firstname: 'First Name',
                    lastname: 'Last Name',
                    email: 'Email',
                    birthday: 'Birthday',
                    emergency_contact_name: 'Emergency Contact Name',
                    emergency_contact_number: 'Emergency Contact Number',
                    password: 'Password',
                    passwordConfirmation: 'Confirm Password'
                };

                // Check toggle type
                const isCompany = $('#company_toggle').is(':checked');
                if (isCompany) {
                    if ($('#ayala_employee').is(':checked')) {
                        if (!$('select[name="cluster_id"]').val()) {
                            $('.cluster_id_err').html('Cluster is required');
                            $('select[name="cluster_id"]').addClass('invalid-field');
                            isValid = false;
                        }
                    }
                    if (!$('#non_ayala').is(':checked')) {
                        if (!$('select[name="company_id"]').val()) {
                            $('.company_id_err').html('Company is required');
                            $('select[name="company_id"]').addClass('invalid-field');
                            isValid = false;
                        }
                    }
                } else {
                    requiredFields.school = 'School Name';
                    requiredFields.school_address = 'School Address';
                }

                // Validate required fields
                Object.entries(requiredFields).forEach(([field, label]) => {
                    const element = $(`[name="${field}"]`);
                    const value = element.val();

                    if (!value || value.trim() === '') {
                        element.addClass('invalid-field');
                        $(`.${field}_err`).html(`${label} is required`);
                        isValid = false;
                    }
                });

                //Validate program select
                const selectedPrograms = $('#program-select').val();
                if (!selectedPrograms || selectedPrograms.length === 0) {
                    $('#program-select').addClass('invalid-field');
                    $('.program_ids_err').html('Please select at least one program');
                    isValid = false;
                }


                // Email validation
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test($('[name="email"]').val())) {
                    $('[name="email"]').addClass('invalid-field');
                    $('.email_err').html('Please enter a valid email address');
                    isValid = false;
                }

                // Password validation
                if ($('[name="password"]').val() !== $('[name="passwordConfirmation"]').val()) {
                    $('[name="password"], [name="passwordConfirmation"]').addClass('invalid-field');
                    $('.password_err').html('Passwords do not match');
                    isValid = false;
                }

                // Terms checkbox
                if (!$('#checkbox').is(':checked')) {
                    $('.terms_err').html('Please agree to the Terms and Conditions');
                    isValid = false;
                }

                // Scroll to first error if validation fails
                if (!isValid) {
                    const firstError = $('.invalid-field').first();
                    if (firstError.length) {
                        $('html, body').animate({
                            scrollTop: firstError.offset().top - 100
                        }, 500);
                    }
                }

                return isValid;
            }

            function submitForm() {
                const affiliateTypeId = $("input[type=radio][name=affiliate_type_id]:checked").val();

                const formData = {
                    _token: $("input[name='_token']").val(),
                    email: $("input[name='email']").val(),
                    firstname: $("input[name='firstname']").val(),
                    lastname: $("input[name='lastname']").val(),
                    middle_name: $("input[name='middle_name']").val(),
                    password: $("input[name='password']").val(),
                    birthday: $("input[name='birthday']").val(),
                    emergency_contact_name: $("input[name='emergency_contact_name']").val(),
                    emergency_contact_number: $("input[name='emergency_contact_number']").val(),
                    affiliate_type_id: parseInt(affiliateTypeId),
                    program_ids: $('#program-select').val(),
                    is_company: affiliateTypeId !== '3' ? true : false, // Explicitly set boolean
                };

                $("#btn-register").prop('disabled', true).text('Registering...');

                // Log the form data being sent
                console.log('Submitting form data:', formData);

                $.ajax({
                    url: "{{ route('volunteer.form.store') }}",
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        console.log('Success response:', response);
                        $("#btn-register").prop('disabled', true).text('Registration successful...');

                        // Use setTimeout to ensure the response is processed
                        setTimeout(function() {
                            if (response.success) {
                                window.location.replace("{{ route('verification.sent') }}");
                            } else {
                                $("#btn-register").prop('disabled', false).text('Register');
                                handleErrors(response.errors || {});
                            }
                        }, 1000);
                    },
                    error: function(xhr, status, error) {
                        $("#btn-register").prop('disabled', false).text('Register');
                        console.error('Ajax error:', {
                            status: xhr.status,
                            statusText: xhr.statusText,
                            responseText: xhr.responseText,
                            error: error
                        });

                        if (xhr.status === 422) {
                            handleErrors(xhr.responseJSON.errors);
                        } else {
                            alert('An error occurred. Please try again later.');
                        }
                    }
                });
            }

            function handleErrors(errors) {
                console.log('Handling errors:', errors);

                if (!errors) {
                    console.error('No errors object provided to handleErrors');
                    return;
                }

                Object.entries(errors).forEach(([field, messages]) => {
                    console.log(`Setting error for ${field}:`, messages);
                    $(`.${field}_err`).html(messages[0]);
                    $(`[name="${field}"]`).addClass('invalid-field');
                });

                // Scroll to first error
                const firstError = $('.invalid-field').first();
                if (firstError.length) {
                    $('html, body').animate({
                        scrollTop: firstError.offset().top - 100
                    }, 500);
                }
            }
        });
    </script>
@endsection

