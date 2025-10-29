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
                        <p class="font-[400] text-[28px]">Ayala Corporate Citizenship and Volunteer Platform</p>
                    </div>
                </div>

                <div class="h-[100vh] col-span-3 clip-path-custom" style="background: url('{{ asset('img/banner.jpg') }}') no-repeat center left; background-size: cover;">
                </div>
            </div>
        </section>
        {{-- Tablet & mobile: Hero Banner Section --}}
        <section class="block lg:hidden h-[100vh] w-full flex flex-col items-center justify-center p-[5%] text-white relative" style="background: url('{{ asset('img/banner.jpg') }}') no-repeat center center; background-size: cover;">
            <div class="absolute inset-0 bg-[#03498D] opacity-50"></div>
            <div class="flex flex-col items-center justify-center relative text-center z-10">
                <p class="font-[700] text-4xl leading-none">Your involvement is important to us!</p>
                <p class="font-[400] text-2xl">Ayala Corporate Citizenship and Volunteer Platform</p>
            </div>
        </section>

        {{-- style="background: url('{{ asset('img/registration-bg.png') }}') no-repeat right center; background-size: cover;" --}}
        <div class="rounded-lg m-[-20vh] mb-8 xl:w-[80%] lg:w-[85%] md:w-[90%] sm:w-[95%] w-[98%] z-10 bg-[#F55E1D]">
                <div class="items-start justify-center min-h-[756px]">
                    <!-- Left Side -->
                     <form  action="{{ route('volunteer.form.store') }}" method="POST">
                        @csrf
                        <div id="step-1" class="grid grid-cols-1 md:grid-cols-2">
                            <div class=" text-white p-8 lg:p-12">
                                <h1 class="text-4xl font-bold mb-4">Become a Volunteer</h1>
                                <p class="text-xl  mb-4">Ayala Corporate Citizenship and Volunteer Platform</p>
                                <p class="text-xl font-bold mb-4">Personal Information</p>

                                {{-- <div>
                                    <label class="block text-sm font-semibold required">Username</label>
                                    <input type="text" class="w-full p-2 border border-gray-300 rounded text-black" name="username"/>
                                    <span class="text-danger text-red-400 text-sm username_err"></span>
                                </div> --}}
                                <div>
                                    <label class="block text-sm font-semibold required">Given Name</label>
                                    <input type="text" class="shadow-lg w-full p-2 border border-gray-300 rounded text-black" name="firstname"/>
                                    <span class="text-danger text-red-400 text-sm firstname_err"></span>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold">Nickname</label>
                                    <input type="text" class="shadow-lg w-full p-2 border border-gray-300 rounded text-black" name="nickname"/>
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
                                    <label class="block text-sm font-semibold required">Age Range</label>
                                    <select class="shadow-lg w-full p-2 bg-white border-gray-300 text-gray-900 rounded" name="age_range">
                                        <option value="" disabled selected></option>
                                        <option value="10-17">10-17 years old</option>
                                        <option value="18-24">18-24 years old</option>
                                        <option value="25-34">25-34 years old</option>
                                        <option value="35-44">35-44 years old</option>
                                        <option value="45-54">45-54 years old</option>
                                        <option value="55-64">55-64 years old</option>
                                        <option value="65+">65 years and above</option>
                                    </select>
                                    <span class="text-danger text-red-400 text-sm age_range_err"></span>
                                </div>

                                <!-- Program Interest Dropdown -->
                                <div class="">
                                    <label class="block text-white font-semibold">What programs are you interested in? (Multi-Select)</label>
                                    <select id="program-select" class="shadow-lg w-full p-2 bg-white border-gray-300 text-gray-900 rounded"
                                    name="program_ids[]"
                                    multiple
                                    required>
                                <option value="">Select programs</option>
                                @foreach($programs as $program)
                                    <option value="{{ $program->id }}">{{ $program->name }}</option>
                                @endforeach
                                <option value="other">Others (Please Specify)</option>
                            </select>
                            <span class="text-danger program_ids_err"></span>

                                <!-- Other Program Text Field (initially hidden) -->
                                <div id="other-program-field" class="mt-2 hidden">
                                    <input type="text"
                                        name="other_program"
                                        class="shadow-lg w-full p-2 border border-gray-300 rounded text-black"
                                        placeholder="Please specify other program(s)"/>
                                    <span class="text-danger other_program_err"></span>
                                </div>
                            </div>

                            <!-- How did you hear about us -->
                            <div class="mb-8">
                                <label class="block text-white font-semibold required">How did you hear about us?</label>
                                <select id="referral-source"
                                        class="shadow-lg w-full p-2 bg-white border-gray-300 text-gray-900 rounded"
                                        name="referral_source[]"
                                        multiple
                                        required>
                                    <option value="afi_website">AFI website</option>
                                    <option value="social_media">Social media platforms (Facebook, Instagram, X, and TikTok)</option>
                                    <option value="referral">Referral programs</option>
                                    <option value="advertisements">Advertisements</option>
                                    <option value="activations">On-the-ground activations and print</option>
                                    <option value="news">Online news articles</option>
                                </select>
                                <span class="text-danger referral_source_err"></span>
                            </div>

                                <!-- Privacy and Terms Notice -->
                                {{-- <p class="text-sm mt-8 text-white">
                                    We will not share your information without your permission. By signing up you agree to our
                                    <a href="{{ route('terms-and-conditions') }}"  style="color: blue;" class="underline">Terms and Conditions</a>. Learn how we use your data in our
                                    <a href="{{ route('data-privacy-policy') }}"  style="color: blue;" class="underline">Privacy Policy</a>.
                                </p> --}}
                            </div>

                            <!-- Right Side (Multi-Step Form) -->
                            <div class="p-8 lg:p-12 flex items-start justify-start">
                                <div class="text-white w-full">
                                    <h2 class="text-3xl font-normal mb-4">Company/Affiliation</h2>

                                    <!-- Multi-Step Form Structure -->

                                    <!-- Step 1 -->
                                    <div id="step-1" class="w-full">

                                        <!-- Organization Radio Buttons -->
                                            <div class="mb-4">
                                                <label class="block mb-2 text-white font-semibold required">Please select your organization</label>
                                                <div class="flex items-center mb-2">
                                                    <input type="radio" id="ayala_employee" name="affiliate_type_id" checked value="1" class="mr-2" onchange="updateDropdowns()" />
                                                    <label for="ayala_employee" class="text-white">Ayala Employee</label>
                                                </div>
                                                <div class="flex items-center">
                                                    <input type="radio" id="non_ayala" name="affiliate_type_id" value="2" class="mr-2" onchange="updateDropdowns()" />
                                                    <label for="non_ayala" class="text-white">Non-Ayala Employee</label>
                                                </div>
                                            </div>

                                            <!-- Company Fields -->
                                            <div id="company-fields">
                                                <!-- For Ayala Employees -->
                                                <div id="ayala-fields">
                                                    <div class="">
                                                        <label class="block text-sm font-semibold required">Cluster</label>
                                                        <select class="shadow-lg w-full p-2 bg-white border-gray-300 text-gray-900 rounded" name="cluster_id" id="clusterSelect">
                                                            <option value="" disabled selected></option>
                                                            @foreach ($clusters as $cluster)
                                                                <option value="{{ $cluster->id }}">{{ $cluster->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="text-danger cluster_id_err"></span>
                                                    </div>
                                                    <div class="">
                                                        <label class="block text-sm font-semibold required">Company Name</label>
                                                        <select class="shadow-lg w-full p-2 bg-white border-gray-300 text-gray-900 rounded" name="company_id" id="companySelect">
                                                            <option value="" disabled selected></option>
                                                            @foreach ($companies as $company)
                                                                <option value="{{ $company->id }}" data-cluster="{{ $company->cluster_id }}">{{ $company->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="text-danger company_id_err"></span>
                                                    </div>
                                                </div>

                                                <!-- For Non-Ayala Employees -->
                                                <div id="non-ayala-fields" class="hidden">
                                                    <div class="mb-4">
                                                        <label class="block text-sm font-semibold required">Company Name</label>
                                                        <input type="text" class="shadow-lg w-full p-2 border border-gray-300 rounded text-black" name="external_company_name"/>
                                                        <span class="text-danger external_company_name_err"></span>
                                                    </div>
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
                                        <div class="mt-6">
                                            <input id="checkbox" type="checkbox" required />
                                            <label class="text-color" for="checkbox">
                                                &nbsp; I have read and agree to the Ayala Foundation's
                                                <a href="{{ route('data-privacy-policy') }}" class="hover:underline text-color">Data Privacy Policy</a>.
                                            </label>
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
        $(document).ready(function() {
            // Initialize Select2 Components
            initializeSelect2Components();

            // Form Event Handlers
            setupFormHandlers();

            // Organization Type Toggle
            setupOrganizationToggle();
        });

        function initializeSelect2Components() {
            // Programs Select2
            $('#program-select').select2({
                placeholder: "Select programs",
                allowClear: true
            }).on('change', handleProgramChange);

            // Referral Source Select2
            $('#referral-source').select2({
                placeholder: "Select how you heard about us",
                allowClear: true
            });
        }

        function setupFormHandlers() {
            // Prevent default form submission
            $('form').on('submit', function(e) {
                e.preventDefault();
            });

            // Register button click handler
            $("#btn-register").click(function(e) {
                e.preventDefault();
                if (validateForm()) {
                    submitForm();
                }
            });
        }

        function handleProgramChange() {
            const hasOther = $(this).val()?.includes('other');
            $('#other-program-field').toggleClass('hidden', !hasOther);
            if (!hasOther) {
                $('input[name="other_program"]').val('');
            }
        }

        function validateForm() {
            clearValidationErrors();
            let isValid = true;

            // Validate required fields
            isValid = validateRequiredFields() && isValid;

            // Validate organization fields
            isValid = validateOrganizationFields() && isValid;

            // Validate selections
            isValid = validateSelections() && isValid;

            // Validate email and password
            isValid = validateEmailAndPassword() && isValid;

            // Validate terms acceptance
            isValid = validateTerms() && isValid;

            if (!isValid) {
                scrollToFirstError();
            }

            return isValid;
        }

        function clearValidationErrors() {
            $('.text-danger').html('');
            $('.invalid-field').removeClass('invalid-field');
        }

        function validateRequiredFields() {
            let isValid = true;
            const requiredFields = {
                firstname: 'Given Name',
                lastname: 'Last Name',
                email: 'Email',
                age_range: 'Age Range',
                emergency_contact_name: 'Emergency Contact Name',
                emergency_contact_number: 'Emergency Contact Number',
                password: 'Password',
                passwordConfirmation: 'Confirm Password'
            };

            Object.entries(requiredFields).forEach(([field, label]) => {
                const element = $(`[name="${field}"]`);
                if (!element.val()?.trim()) {
                    markFieldInvalid(field, `${label} is required`);
                    isValid = false;
                }
            });

            return isValid;
        }

        function validateOrganizationFields() {
            let isValid = true;

            if ($('#ayala_employee').is(':checked')) {
                if (!$('select[name="cluster_id"]').val()) {
                    markFieldInvalid('cluster_id', 'Cluster is required');
                    isValid = false;
                }
                if (!$('select[name="company_id"]').val()) {
                    markFieldInvalid('company_id', 'Company is required');
                    isValid = false;
                }
            } else if (!$('input[name="external_company_name"]').val()?.trim()) {
                markFieldInvalid('external_company_name', 'Company name is required');
                isValid = false;
            }

            return isValid;
        }

        function validateSelections() {
            let isValid = true;

            // Programs
            const selectedPrograms = $('#program-select').val();
            if (!selectedPrograms?.length) {
                markFieldInvalid('program_ids', 'Please select at least one program');
                isValid = false;
            }

            // Other program if selected
            if (selectedPrograms?.includes('other') && !$('input[name="other_program"]').val()?.trim()) {
                markFieldInvalid('other_program', 'Please specify other program(s)');
                isValid = false;
            }

            // Referral source
            if (!$('#referral-source').val()?.length) {
                markFieldInvalid('referral_source', 'Please select at least one option');
                isValid = false;
            }

            return isValid;
        }

        function validateEmailAndPassword() {
            let isValid = true;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailRegex.test($('[name="email"]').val())) {
                markFieldInvalid('email', 'Please enter a valid email address');
                isValid = false;
            }

            if ($('[name="password"]').val() !== $('[name="passwordConfirmation"]').val()) {
                markFieldInvalid('password', 'Passwords do not match');
                $('[name="passwordConfirmation"]').addClass('invalid-field');
                isValid = false;
            }

            return isValid;
        }

        function validateTerms() {
            if (!$('#checkbox').is(':checked')) {
                $('.terms_err').html('Please agree to the Terms and Conditions');
                return false;
            }
            return true;
        }

        function markFieldInvalid(field, message) {
            $(`.${field}_err`).html(message);
            $(`[name="${field}"]`).addClass('invalid-field');
        }

        function scrollToFirstError() {
            const firstError = $('.invalid-field').first();
            if (firstError.length) {
                $('html, body').animate({
                    scrollTop: firstError.offset().top - 100
                }, 500);
            }
        }

        function submitForm() {
            const formData = collectFormData();

            $("#btn-register").prop('disabled', true).text('Registering...');

            $.ajax({
                url: "/volunteer-registration-store",
                type: 'POST',
                data: formData,
                success: handleSubmitSuccess,
                error: handleSubmitError
            });
        }

        function collectFormData() {
            const affiliateTypeId = $("input[name=affiliate_type_id]:checked").val();
            const isAyalaEmployee = $('#ayala_employee').is(':checked');

            return {
                _token: $("input[name='_token']").val(),
                email: $("input[name='email']").val(),
                firstname: $("input[name='firstname']").val(),
                lastname: $("input[name='lastname']").val(),
                nickname: $("input[name='nickname']").val(),
                age_range: $("select[name='age_range']").val(),
                password: $("input[name='password']").val(),
                passwordConfirmation: $("input[name='passwordConfirmation']").val(),
                emergency_contact_name: $("input[name='emergency_contact_name']").val(),
                emergency_contact_number: $("input[name='emergency_contact_number']").val(),
                affiliate_type_id: parseInt(affiliateTypeId),
                program_ids: $('#program-select').val(),
                other_program: $('input[name="other_program"]').val(),
                referral_source: $('#referral-source').val(),
                cluster_id: isAyalaEmployee ? $("#clusterSelect").val() : null,
                company_id: isAyalaEmployee ? $("#companySelect").val() : null,
                external_company_name: !isAyalaEmployee ? $("input[name='external_company_name']").val() : null,
            };
        }

        function handleSubmitSuccess(response) {
            $("#btn-register").text('Registration successful...');

            setTimeout(() => {
                if (response.success) {
                    window.location.replace("/email/verify/sent");
                } else {
                    $("#btn-register").prop('disabled', false).text('Register');
                    handleErrors(response.errors || {});
                }
            }, 1000);
        }

        function handleSubmitError(xhr, status, error) {
            $("#btn-register").prop('disabled', false).text('Register');
            console.error('Form submission error:', { status: xhr.status, error });

            if (xhr.status === 422) {
                handleErrors(xhr.responseJSON.errors);
            } else {
                alert('An error occurred. Please try again later.');
            }
        }

        function handleErrors(errors) {
            if (!errors) return;

            Object.entries(errors).forEach(([field, messages]) => {
                markFieldInvalid(field, messages[0]);
            });

            scrollToFirstError();
        }

                function setupOrganizationToggle() {
            $('input[name="affiliate_type_id"]').on('change', function() {
                const isAyalaEmployee = $('#ayala_employee').is(':checked');
                $('#ayala-fields').toggleClass('hidden', !isAyalaEmployee);
                $('#non-ayala-fields').toggleClass('hidden', isAyalaEmployee);

                // Reset fields
                if (isAyalaEmployee) {
                    $('input[name="external_company_name"]').val('');
                } else {
                    $('#clusterSelect, #companySelect').val('').trigger('change');
                }
            });

            // Initialize cluster-company filtering
            setupClusterCompanyFilter();
        }

        function setupClusterCompanyFilter() {
    // When cluster dropdown changes
            $('#clusterSelect').on('change', function() {
                const selectedClusterId = $(this).val();

                // Clear the company dropdown except for the first placeholder option
                const $firstOption = $('#companySelect option:first-child');
                $('#companySelect').empty().append($firstOption);

                // If a cluster is selected, populate with only matching companies
                if (selectedClusterId) {
                    // Get all original company options and filter them
                    const companyOptions = [];

                    // Loop through all companies and add only those matching the selected cluster
                    @foreach ($companies as $company)
                        companyOptions.push({
                            id: {{ $company->id }},
                            name: "{{ $company->name }}",
                            clusterId: {{ $company->cluster_id }}
                        });
                    @endforeach

                    // Add filtered companies to dropdown
                    companyOptions.forEach(company => {
                        if (company.clusterId == selectedClusterId) {
                            $('#companySelect').append(
                                $('<option></option>')
                                    .attr('value', company.id)
                                    .attr('data-cluster', company.clusterId)
                                    .text(company.name)
                            );
                        }
                    });
                }

                // Reset selection
                $('#companySelect').val('');

                // If using Select2, refresh it
                if ($.fn.select2) {
                    $('#companySelect').select2('destroy').select2();
                }
            });

            // Initial setup on page load
            if ($('#clusterSelect').val()) {
                $('#clusterSelect').trigger('change');
            }
        }
    </script>
@endsection

