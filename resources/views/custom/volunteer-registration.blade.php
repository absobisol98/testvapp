@extends('custom.layouts.app')
@section('title', 'Volunteer Registration')

@section('content')
<div class="min-h-screen flex flex-col" x-data="volunteerForm()" style="background-color: #f6f7f9;">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,700&family=Public+Sans:wght@400;500;600;700&display=swap');

        :root {
            --blue-900: #072b54;
            --blue-800: #0a3a6e;
            --blue-700: #0e4f99;
            --blue-600: #1565c4;
            --blue-500: #2a7de0;
            --blue-100: #d6e6f8;
            --blue-50: #eef4fc;
            --orange-600: #d9650c;
            --orange-500: #f07a1e;
            --orange-400: #f79544;
            --green-600: #1d8a52;
            --green-50: #e8f5ee;
            --ink: #15181d;
            --slate: #454c58;
            --muted: #737a87;
            --line: #e6e8ec;
            --line-soft: #eef0f3;
            --bg: #ffffff;
            --bg-soft: #f6f7f9;
        }

        [x-cloak] { display: none !important; }

        body {
            font-family: 'Public Sans', system-ui, sans-serif;
            color: var(--ink);
        }

        h1, h2, h3 {
            font-family: 'Bricolage Grotesque', system-ui, sans-serif;
            font-weight: 700;
            letter-spacing: -0.015em;
        }

        .step-indicator {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .step-indicator.active {
            background: var(--blue-700);
            color: #fff;
        }

        .step-indicator.completed {
            background: var(--green-600);
            color: #fff;
        }

        .step-indicator.inactive {
            background: var(--line);
            color: var(--muted);
        }

        .step-line {
            flex: 1;
            height: 2px;
            margin: 0 10px;
            transition: all 0.2s ease;
        }

        .step-line.active {
            background: var(--green-600);
        }

        .step-line.inactive {
            background: var(--line);
        }

        .form-input, .form-select {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid var(--line);
            border-radius: 12px;
            font: inherit;
            font-size: 15px;
            color: var(--ink);
            background: #fff;
            transition: all 0.14s ease;
        }

        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: var(--blue-500);
            box-shadow: 0 0 0 3px var(--blue-50);
        }

        .form-input::placeholder {
            color: var(--muted);
        }

        .form-label {
            display: block;
            font-size: 13.5px;
            font-weight: 700;
            color: var(--slate);
            margin-bottom: 7px;
        }

        .form-label.required::after {
            content: ' *';
            color: var(--orange-600);
        }

        .form-hint {
            font-size: 12.5px;
            color: var(--muted);
            margin-top: 5px;
        }

        .error-message {
            font-size: 12.5px;
            color: var(--orange-600);
            font-weight: 600;
            margin-top: 5px;
        }

        .info-banner {
            padding: 14px 16px;
            background: var(--blue-50);
            border-radius: 12px;
            display: flex;
            gap: 10px;
            align-items: flex-start;
            font-size: 13.5px;
            color: var(--blue-800);
            line-height: 1.5;
        }

        .info-icon {
            width: 17px;
            height: 17px;
            color: var(--blue-700);
            flex-shrink: 0;
            margin-top: 2px;
        }
    </style>

    <!-- Header -->
    <div style="background: #fff; border-bottom: 1px solid var(--line);">
        <div style="max-width: 640px; margin: 0 auto; padding: 36px 20px;">
            <button @click="goBack()" style="display: inline-flex; align-items: center; gap: 7px; color: var(--muted); font-size: 14px; font-weight: 600; margin-bottom: 20px; background: none; border: none; cursor: pointer;">
                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Cancel
            </button>

            <div style="margin-bottom: 22px;">
                <p style="font-size: 13px; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: var(--orange-600); margin-bottom: 8px;">VApp</p>
                <h1 style="font-size: 28px; margin-bottom: 7px;">Become a volunteer</h1>
                <p style="color: var(--slate); margin-top: 7px; font-size: 15.5px;">Create your profile. Takes about two minutes.</p>
            </div>
        </div>
    </div>

    <!-- Step Indicators -->
    <div style="background: #fff; border-bottom: 1px solid var(--line);">
        <div style="max-width: 640px; margin: 0 auto; padding: 28px 20px;">
            <!-- Stepper -->
            <div style="display: flex; align-items: center; margin-bottom: 26px;">
                <template x-for="(step, i) in ['About you', 'Programs', 'Account']" :key="i">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <div class="step-indicator" :class="i < currentStep ? 'completed' : i === currentStep ? 'active' : 'inactive'">
                            <template x-if="i < currentStep">
                                <svg style="width: 15px; height: 15px;" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </template>
                            <template x-if="!(i < currentStep)">
                                <span x-text="i + 1"></span>
                            </template>
                        </div>
                        <span style="font-size: 13px; font-weight: 700; transition: all 0.2s ease;" :style="i === currentStep ? 'color: var(--ink)' : i < currentStep ? 'color: var(--slate)' : 'color: var(--muted)';" x-text="step"></span>
                    </div>
                    <template x-if="i < 2">
                        <div class="step-line" :class="i < currentStep ? 'active' : 'inactive'"></div>
                    </template>
                </template>
            </div>
        </div>
    </div>

    <!-- Form Content -->
    <div style="flex: 1; padding: 36px 20px 80px; display: flex; flex-direction: column;">
        <div style="max-width: 640px; margin: 0 auto; width: 100%;">
            <form @submit.prevent="submitForm()" style="background: #fff; border: 1px solid var(--line); border-radius: 22px; padding: 28px 30px; box-shadow: 0 1px 2px rgba(16,32,56,.06), 0 1px 3px rgba(16,32,56,.05);">
                @csrf

                <!-- Step 1: About You -->
                <div x-show="currentStep === 1" x-transition style="display: flex; flex-direction: column; gap: 18px;">
                    <h2 style="font-size: 20px; margin-bottom: 2px;">Personal information</h2>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
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
                            <p class="form-hint">Optional</p>
                        </div>
                    </div>

                    <div>
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

                    <div>
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

                    <div>
                        <label class="form-label required">Age range</label>
                        <select x-model="form.age_range" class="form-select" required>
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
                <div x-show="currentStep === 2" x-transition style="display: flex; flex-direction: column; gap: 22px;">
                    <h2 style="font-size: 20px; margin-bottom: 2px;">Select programs</h2>
                    <p style="color: var(--slate); margin-top: 0;">Choose the programs you're interested in volunteering for.</p>

                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        @foreach($programs ?? [] as $program)
                        <label style="display: flex; align-items: center; padding: 12px 16px; border: 1.5px solid var(--line); border-radius: 12px; cursor: pointer; font-weight: 600; font-size: 14.5px; transition: all 0.14s ease; background: #fff;"
                               onmouseover="this.style.borderColor='var(--blue-400)'; this.style.background='var(--blue-50)'"
                               onmouseout="this.style.borderColor='var(--line)'; this.style.background='#fff'">
                            <input
                                type="checkbox"
                                name="programs[]"
                                value="{{ $program->id }}"
                                @change="updatePrograms()"
                                style="width: 17px; height: 17px; accent-color: var(--blue-700); flex-shrink: 0;">
                            <span style="margin-left: 11px;">{{ $program->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Step 3: Account -->
                <div x-show="currentStep === 3" x-transition style="display: flex; flex-direction: column; gap: 18px;">
                    <h2 style="font-size: 20px; margin-bottom: 2px;">Account setup</h2>

                    <div>
                        <label class="form-label required">Business Unit / Company</label>
                        <select x-model="form.company_id" class="form-select" required>
                            <option value="">Select your business unit</option>
                            @foreach($companies ?? [] as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                        <template x-if="errors.company_id">
                            <p class="error-message" x-text="errors.company_id"></p>
                        </template>
                    </div>

                    <div>
                        <label style="display: flex; align-items: flex-start; gap: 11px; cursor: pointer; font-size: 15px;">
                            <input type="checkbox" x-model="form.agree_terms" style="width: 17px; height: 17px; accent-color: var(--blue-700); flex-shrink: 0; margin-top: 2px;">
                            <span>I agree to the terms and conditions and privacy policy</span>
                        </label>
                    </div>
                </div>

                <!-- Buttons -->
                <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 28px; border-top: 1px solid var(--line); margin-top: 28px;">
                    <button
                        type="button"
                        @click="previousStep()"
                        x-show="currentStep > 1"
                        style="padding: 13px 24px; text-decoration: none; color: var(--slate); font-weight: 700; font-size: 15px; border: none; background: none; cursor: pointer; transition: all 0.16s ease;">
                        Back
                    </button>
                    <div x-show="currentStep === 1"></div>

                    <button
                        type="button"
                        @click="nextStep()"
                        x-show="currentStep < 3"
                        style="display: inline-flex; align-items: center; justify-content: center; gap: 9px; background: var(--orange-500); color: #fff; font-weight: 700; font-size: 15px; padding: 13px 24px; border-radius: 999px; border: none; cursor: pointer; transition: all 0.16s ease; box-shadow: 0 6px 16px rgba(240,122,30,.28);"
                        onmouseover="this.style.background='var(--orange-600)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 10px 22px rgba(240,122,30,.34)'"
                        onmouseout="this.style.background='var(--orange-500)'; this.style.transform='none'; this.style.boxShadow='0 6px 16px rgba(240,122,30,.28)'">
                        Continue
                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>

                    <button
                        type="submit"
                        x-show="currentStep === 3"
                        :disabled="isSubmitting"
                        style="display: inline-flex; align-items: center; justify-content: center; gap: 9px; background: var(--orange-500); color: #fff; font-weight: 700; font-size: 15px; padding: 13px 24px; border-radius: 999px; border: none; cursor: pointer; transition: all 0.16s ease;"
                        onmouseover="!this.disabled && (this.style.background='var(--orange-600)', this.style.transform='translateY(-1px)')"
                        onmouseout="this.style.background='var(--orange-500)'; this.style.transform='none'">
                        <span x-show="!isSubmitting">Submit</span>
                        <span x-show="isSubmitting">Submitting...</span>
                    </button>
                </div>
            </form>

            <!-- Footer Note -->
            <div style="display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 32px; color: var(--muted); font-size: 13.5px;">
                <svg style="width: 16px; height: 16px; color: var(--muted); flex-shrink: 0;" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd"/>
                </svg>
                <p>Your information is kept private and secure.</p>
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
