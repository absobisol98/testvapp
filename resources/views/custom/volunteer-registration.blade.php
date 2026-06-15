@extends('custom.layouts.app')
@section('title', 'Volunteer Registration')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col" x-data="volunteerForm()">
    <style>
        [x-cloak] { display: none !important; }

        .step-indicator {
            @apply w-8 h-8 rounded-full flex items-center justify-center font-semibold text-sm transition-all;
        }

        .step-indicator.active {
            @apply bg-blue-600 text-white;
        }

        .step-indicator.completed {
            @apply bg-blue-600 text-white;
        }

        .step-indicator.inactive {
            @apply bg-gray-200 text-gray-600;
        }

        .step-line {
            @apply h-0.5 flex-1 transition-all;
        }

        .step-line.active {
            @apply bg-gray-300;
        }

        .step-line.inactive {
            @apply bg-gray-200;
        }

        .form-input {
            @apply w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900;
        }

        .form-input::placeholder {
            @apply text-gray-400;
        }

        .form-label {
            @apply block text-sm font-medium text-gray-800 mb-2;
        }

        .form-label.required::after {
            content: ' *';
            @apply text-red-500;
        }

        .error-message {
            @apply text-red-500 text-sm mt-1;
        }
    </style>

    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-3xl mx-auto px-6 py-6">
            <button @click="goBack()" class="flex items-center text-gray-600 hover:text-gray-900 mb-6 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Cancel
            </button>

            <p class="text-orange-500 font-semibold text-sm tracking-wide mb-2">VAPP</p>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Become a volunteer</h1>
            <p class="text-gray-600">Create your profile. Takes about two minutes.</p>
        </div>
    </div>

    <!-- Step Indicators -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-3xl mx-auto px-6 py-8">
            <div class="flex items-center gap-4">
                <div class="step-indicator" :class="currentStep >= 1 ? 'active' : 'inactive'">
                    <template x-if="currentStep > 1">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </template>
                    <template x-if="currentStep <= 1">
                        <span>1</span>
                    </template>
                </div>

                <div class="step-line" :class="currentStep > 1 ? 'active' : 'inactive'"></div>

                <div class="step-indicator" :class="currentStep >= 2 ? 'active' : 'inactive'">
                    <template x-if="currentStep > 2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </template>
                    <template x-if="currentStep <= 2">
                        <span>2</span>
                    </template>
                </div>

                <div class="step-line" :class="currentStep > 2 ? 'active' : 'inactive'"></div>

                <div class="step-indicator" :class="currentStep >= 3 ? 'active' : 'inactive'">
                    <span>3</span>
                </div>
            </div>

            <div class="flex justify-between mt-4 text-sm">
                <span class="text-gray-600 font-medium">About you</span>
                <span class="text-gray-400">Programs</span>
                <span class="text-gray-400">Account</span>
            </div>
        </div>
    </div>

    <!-- Form Content -->
    <div class="flex-1 py-12 px-6">
        <div class="max-w-3xl mx-auto">
            <form @submit.prevent="submitForm()" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                @csrf

                <!-- Step 1: About You -->
                <div x-show="currentStep === 1" x-transition>
                    <h2 class="text-xl font-semibold text-gray-900 mb-8">Personal information</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="form-label required">Given name</label>
                            <input
                                type="text"
                                x-model="form.firstname"
                                placeholder="Juan"
                                class="form-input"
                                required>
                            <template x-if="errors.firstname">
                                <p class="error-message" x-text="errors.firstname"></p>
                            </template>
                        </div>

                        <div>
                            <label class="form-label">Nickname</label>
                            <input
                                type="text"
                                x-model="form.nickname"
                                placeholder="How should we call you?"
                                class="form-input">
                            <p class="text-gray-500 text-xs mt-1">Optional</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="form-label required">Last name</label>
                        <input
                            type="text"
                            x-model="form.lastname"
                            placeholder="Dela Cruz"
                            class="form-input"
                            required>
                        <template x-if="errors.lastname">
                            <p class="error-message" x-text="errors.lastname"></p>
                        </template>
                    </div>

                    <div class="mb-6">
                        <label class="form-label required">Email</label>
                        <input
                            type="email"
                            x-model="form.email"
                            placeholder="you@email.com"
                            class="form-input"
                            required>
                        <template x-if="errors.email">
                            <p class="error-message" x-text="errors.email"></p>
                        </template>
                    </div>

                    <div class="mb-8">
                        <label class="form-label required">Age range</label>
                        <select x-model="form.age_range" class="form-input" required>
                            <option value="">Select your age range</option>
                            <option value="10-17">10-17 years old</option>
                            <option value="18-24">18-24 years old</option>
                            <option value="25-34">25-34 years old</option>
                            <option value="35-44">35-44 years old</option>
                            <option value="45-54">45-54 years old</option>
                            <option value="55-64">55-64 years old</option>
                            <option value="65+">65+ years old</option>
                        </select>
                        <template x-if="errors.age_range">
                            <p class="error-message" x-text="errors.age_range"></p>
                        </template>
                    </div>
                </div>

                <!-- Step 2: Programs -->
                <div x-show="currentStep === 2" x-transition>
                    <h2 class="text-xl font-semibold text-gray-900 mb-8">Select programs</h2>
                    <p class="text-gray-600 mb-6">Choose the programs you're interested in volunteering for.</p>

                    <div class="space-y-3">
                        @foreach($programs ?? [] as $program)
                        <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition">
                            <input
                                type="checkbox"
                                name="programs[]"
                                value="{{ $program->id }}"
                                @change="updatePrograms()"
                                class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-3 font-medium text-gray-700">{{ $program->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Step 3: Account -->
                <div x-show="currentStep === 3" x-transition>
                    <h2 class="text-xl font-semibold text-gray-900 mb-8">Account setup</h2>

                    <div class="mb-6">
                        <label class="form-label required">Business Unit / Company</label>
                        <select x-model="form.company_id" class="form-input" required>
                            <option value="">Select your business unit</option>
                            @foreach($companies ?? [] as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                        <template x-if="errors.company_id">
                            <p class="error-message" x-text="errors.company_id"></p>
                        </template>
                    </div>

                    <div class="mb-8">
                        <label class="flex items-start">
                            <input type="checkbox" x-model="form.agree_terms" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 mt-1">
                            <span class="ml-3 text-sm text-gray-700">I agree to the terms and conditions and privacy policy</span>
                        </label>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-between pt-8 border-t border-gray-200">
                    <button
                        type="button"
                        @click="previousStep()"
                        x-show="currentStep > 1"
                        class="px-6 py-3 text-gray-700 font-semibold hover:text-gray-900 transition">
                        Cancel
                    </button>

                    <button
                        type="button"
                        @click="nextStep()"
                        x-show="currentStep < 3"
                        class="flex items-center gap-2 px-8 py-3 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-xl transition">
                        Continue
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>

                    <button
                        type="submit"
                        x-show="currentStep === 3"
                        :disabled="isSubmitting"
                        class="px-8 py-3 bg-orange-500 hover:bg-orange-600 disabled:bg-gray-400 text-white font-semibold rounded-xl transition">
                        <span x-show="!isSubmitting">Submit</span>
                        <span x-show="isSubmitting">Submitting...</span>
                    </button>
                </div>
            </form>

            <!-- Footer Note -->
            <div class="flex items-start justify-center gap-2 mt-8 text-gray-600">
                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm">Your information is kept private and secure.</p>
            </div>
        </div>
    </div>
</div>

<script>
function volunteerForm() {
    return {
        currentStep: 1,
        isSubmitting: false,
        form: {
            firstname: '',
            lastname: '',
            nickname: '',
            email: '',
            age_range: '',
            programs: [],
            company_id: '',
            agree_terms: false,
        },
        errors: {},

        nextStep() {
            if (this.validateStep(this.currentStep)) {
                this.currentStep++;
            }
        },

        previousStep() {
            if (this.currentStep > 1) {
                this.currentStep--;
            }
        },

        goBack() {
            if (confirm('Are you sure you want to cancel? Your progress will be lost.')) {
                window.location.href = '/';
            }
        },

        validateStep(step) {
            this.errors = {};

            if (step === 1) {
                if (!this.form.firstname.trim()) this.errors.firstname = 'First name is required';
                if (!this.form.lastname.trim()) this.errors.lastname = 'Last name is required';
                if (!this.form.email.trim()) this.errors.email = 'Email is required';
                if (!this.form.age_range) this.errors.age_range = 'Age range is required';
            }

            return Object.keys(this.errors).length === 0;
        },

        updatePrograms() {
            // Handle program selection
        },

        async submitForm() {
            if (!this.validateStep(3)) return;

            this.isSubmitting = true;

            try {
                const response = await fetch('{{ route("volunteer.form.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify(this.form),
                });

                if (response.ok) {
                    window.location.href = '/';
                } else {
                    const data = await response.json();
                    this.errors = data.errors || {};
                }
            } catch (error) {
                alert('An error occurred. Please try again.');
            } finally {
                this.isSubmitting = false;
            }
        }
    }
}
</script>
@endsection
