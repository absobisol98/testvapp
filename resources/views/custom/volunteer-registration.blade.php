@extends('custom.layouts.app')
@section('title', 'Volunteer Registration')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,700&family=Public+Sans:wght@400;500;600;700&display=swap');

    :root {
        --blue-900: #072b54;
        --blue-800: #0a3a6e;
        --blue-700: #0e4f99;
        --blue-600: #1565c4;
        --blue-500: #2a7de0;
        --blue-100: #d6e6f8;
        --blue-50:  #eef4fc;
        --orange-600: #d9650c;
        --orange-500: #f07a1e;
        --green-600: #1d8a52;
        --green-50:  #e8f5ee;
        --ink:   #15181d;
        --slate: #454c58;
        --muted: #737a87;
        --faint: #9aa1ad;
        --line:  #e6e8ec;
        --line-soft: #eef0f3;
        --bg:      #ffffff;
        --bg-soft: #f6f7f9;
    }

    [x-cloak] { display: none !important; }

    body { font-family: 'Public Sans', system-ui, sans-serif; color: var(--ink); }
    h1, h2, h3 { font-family: 'Bricolage Grotesque', system-ui, sans-serif; font-weight: 700; letter-spacing: -.015em; }

    .vol-input, .vol-select {
        width: 100%; padding: 12px 14px;
        border: 1.5px solid var(--line); border-radius: 12px;
        font: inherit; font-size: 15px; color: var(--ink);
        background: #fff; transition: all .14s;
    }
    .vol-input:focus, .vol-select:focus {
        outline: none; border-color: var(--blue-500);
        box-shadow: 0 0 0 3px var(--blue-50);
    }
    .vol-input::placeholder { color: var(--faint); }

    .vol-label {
        display: block; font-size: 13.5px; font-weight: 700;
        color: var(--slate); margin-bottom: 7px;
    }
    .vol-label.req::after { content: ' *'; color: var(--orange-600); }

    .vol-hint  { font-size: 12.5px; color: var(--muted); margin-top: 5px; }
    .vol-error { font-size: 12.5px; color: var(--orange-600); font-weight: 600; margin-top: 5px; }

    .vol-chip {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 14px; font-weight: 600;
        padding: 9px 15px; border-radius: 999px;
        border: 1.5px solid var(--line);
        background: #fff; color: var(--slate);
        cursor: pointer; transition: .14s;
    }
    .vol-chip.selected { background: var(--blue-700); border-color: var(--blue-700); color: #fff; }
    .vol-chip:hover:not(.selected) { border-color: var(--blue-500); color: var(--blue-700); }

    .affil-card {
        display: flex; align-items: center; gap: 10px;
        padding: 13px 16px; border: 1.5px solid var(--line);
        border-radius: 12px; background: #fff; flex: 1;
        cursor: pointer; font-weight: 600; font-size: 14.5px;
        transition: .14s;
    }
    .affil-card.selected { border-color: var(--blue-500); background: var(--blue-50); }

    .check-row {
        display: flex; align-items: flex-start; gap: 11px;
        padding: 3px 0; cursor: pointer; font-size: 15px; line-height: 1.5;
    }

    .step-indicator {
        width: 28px; height: 28px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 13px; transition: all .2s; flex-shrink: 0;
    }
    .step-indicator.active    { background: var(--blue-700); color: #fff; }
    .step-indicator.completed { background: var(--green-600); color: #fff; }
    .step-indicator.inactive  { background: var(--line); color: var(--muted); }
    .step-line { flex: 1; height: 2px; margin: 0 10px; transition: all .2s; }
    .step-line.active  { background: var(--green-600); }
    .step-line.inactive { background: var(--line); }

    @media(max-width:520px) {
        .form-row2 { grid-template-columns: 1fr !important; }
        .step-label { display: none !important; }
    }
</style>

<div class="min-h-screen flex flex-col" x-data="volunteerForm()" style="background: var(--bg-soft);">

    {{-- ── PAGE HEADER ─────────────────────────────────────────────────── --}}
    <div style="background:#fff; border-bottom:1px solid var(--line); padding:36px 20px 0;">
        <div style="max-width:640px; margin:0 auto;">
            <button @click="goBack()" style="display:inline-flex; align-items:center; gap:7px; color:var(--muted); font-size:14px; font-weight:600; margin-bottom:20px; background:none; border:none; cursor:pointer; padding:0;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
                Cancel
            </button>
            <div style="margin-bottom:28px;">
                <p style="font-size:13px; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:var(--orange-600); margin-bottom:8px;">VApp</p>
                <h1 style="font-size:28px; margin:0 0 7px;">Become a volunteer</h1>
                <p style="color:var(--slate); font-size:15.5px; margin:0;">Create your profile. Takes about two minutes.</p>
            </div>

            {{-- Stepper --}}
            <div style="display:flex; align-items:center; padding-bottom:28px;">
                <template x-for="(label, i) in ['About you', 'Programs', 'Account']" :key="i">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <div class="step-indicator" :class="i < currentStep ? 'completed' : i === currentStep ? 'active' : 'inactive'">
                            <template x-if="i < currentStep">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg>
                            </template>
                            <template x-if="!(i < currentStep)">
                                <span x-text="i + 1"></span>
                            </template>
                        </div>
                        <span class="step-label" style="font-size:13px; font-weight:700;"
                              :style="i === currentStep ? 'color:var(--ink)' : i < currentStep ? 'color:var(--slate)' : 'color:var(--muted)'"
                              x-text="label"></span>
                        <template x-if="i < 2">
                            <div class="step-line" :class="i < currentStep ? 'active' : 'inactive'"></div>
                        </template>
                    </div>
                </template>
            </div>
        </div>
    </div>

    {{-- ── FORM ─────────────────────────────────────────────────────────── --}}
    <div style="flex:1; padding:36px 20px 80px;">
        <div style="max-width:640px; margin:0 auto;">
            <form @submit.prevent="submitForm()" style="background:#fff; border:1px solid var(--line); border-radius:22px; padding:28px 30px; box-shadow:0 1px 2px rgba(16,32,56,.06), 0 1px 3px rgba(16,32,56,.05);">
                @csrf

                {{-- ── STEP 0: About you ───────────────────────────────────── --}}
                <div x-show="currentStep === 0" x-transition style="display:flex; flex-direction:column; gap:18px;">
                    <h2 style="font-size:20px; margin:0 0 2px;">Personal information</h2>

                    <div class="form-row2" style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">
                        <div>
                            <label class="vol-label req">Given name</label>
                            <input type="text" x-model="form.firstname" class="vol-input" placeholder="Juan" autocomplete="given-name">
                            <p class="vol-error" x-show="errors.firstname" x-text="errors.firstname"></p>
                        </div>
                        <div>
                            <label class="vol-label">Nickname</label>
                            <input type="text" x-model="form.nickname" class="vol-input" placeholder="How should we call you?">
                            <p class="vol-hint">Optional</p>
                        </div>
                    </div>

                    <div>
                        <label class="vol-label req">Last name</label>
                        <input type="text" x-model="form.lastname" class="vol-input" placeholder="Dela Cruz" autocomplete="family-name">
                        <p class="vol-error" x-show="errors.lastname" x-text="errors.lastname"></p>
                    </div>

                    <div>
                        <label class="vol-label req">Email</label>
                        <input type="email" x-model="form.email" class="vol-input" placeholder="you@email.com" autocomplete="email">
                        <p class="vol-error" x-show="errors.email" x-text="errors.email"></p>
                    </div>

                    <div>
                        <label class="vol-label req">Age range</label>
                        <select x-model="form.age_range" class="vol-select">
                            <option value="">Select your age range</option>
                            <option value="10-17">10–17 years old</option>
                            <option value="18-24">18–24 years old</option>
                            <option value="25-34">25–34 years old</option>
                            <option value="35-44">35–44 years old</option>
                            <option value="45-54">45–54 years old</option>
                            <option value="55-64">55–64 years old</option>
                            <option value="65+">65 and above</option>
                        </select>
                        <p class="vol-error" x-show="errors.age_range" x-text="errors.age_range"></p>
                    </div>
                </div>

                {{-- ── STEP 1: Programs & affiliation ─────────────────────── --}}
                <div x-show="currentStep === 1" x-transition style="display:flex; flex-direction:column; gap:22px;">
                    <h2 style="font-size:20px; margin:0 0 2px;">Programs &amp; affiliation</h2>

                    {{-- Programs multi-select --}}
                    <div>
                        <label class="vol-label">What programs are you interested in? <span style="font-weight:400; color:var(--muted);">(select all that apply)</span></label>
                        <div style="display:flex; flex-wrap:wrap; gap:8px; margin-top:8px;">
                            @foreach($programs ?? [] as $program)
                            <button type="button"
                                @click="toggleProgram('{{ $program->id }}')"
                                :class="form.program_ids.includes('{{ $program->id }}') ? 'vol-chip selected' : 'vol-chip'">
                                <template x-if="form.program_ids.includes('{{ $program->id }}')">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg>
                                </template>
                                {{ $program->name }}
                            </button>
                            @endforeach
                            <button type="button"
                                @click="toggleProgram('other')"
                                :class="form.program_ids.includes('other') ? 'vol-chip selected' : 'vol-chip'">
                                <template x-if="form.program_ids.includes('other')">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg>
                                </template>
                                Others (please specify)
                            </button>
                        </div>
                        <div x-show="form.program_ids.includes('other')" style="margin-top:10px;">
                            <input type="text" x-model="form.other_program" class="vol-input" placeholder="Please specify…">
                        </div>
                        <p class="vol-error" x-show="errors.program_ids" x-text="errors.program_ids"></p>
                    </div>

                    {{-- How did you hear about us --}}
                    <div>
                        <label class="vol-label req">How did you hear about us?</label>
                        <div style="display:flex; flex-direction:column; gap:10px; margin-top:8px;">
                            @foreach([
                                ['afi_website',   'AFI website'],
                                ['social_media',  'Social media (Facebook, Instagram, X, TikTok)'],
                                ['referral',      'Referral programs'],
                                ['advertisements','Advertisements'],
                                ['activations',   'On-the-ground activations and print'],
                                ['news',          'Online news articles'],
                            ] as [$value, $label])
                            <label class="check-row">
                                <input type="checkbox"
                                    value="{{ $value }}"
                                    @change="toggleReferral('{{ $value }}')"
                                    :checked="form.referral_source.includes('{{ $value }}')"
                                    style="width:17px; height:17px; accent-color:var(--blue-700); flex-shrink:0; margin-top:2px;">
                                {{ $label }}
                            </label>
                            @endforeach
                        </div>
                        <p class="vol-error" x-show="errors.referral_source" x-text="errors.referral_source"></p>
                    </div>

                    {{-- Affiliation --}}
                    <div>
                        <label class="vol-label req">Company / Affiliation</label>
                        <div style="display:flex; gap:12px; margin-top:8px;">
                            <label class="affil-card" :class="form.affiliate_type_id == 1 ? 'selected' : ''">
                                <input type="radio" name="affiliate_type_id" value="1"
                                    x-model="form.affiliate_type_id"
                                    style="width:17px; height:17px; accent-color:var(--blue-700); flex-shrink:0;">
                                Ayala Employee
                            </label>
                            <label class="affil-card" :class="form.affiliate_type_id == 2 ? 'selected' : ''">
                                <input type="radio" name="affiliate_type_id" value="2"
                                    x-model="form.affiliate_type_id"
                                    style="width:17px; height:17px; accent-color:var(--blue-700); flex-shrink:0;">
                                Non-Ayala Employee
                            </label>
                        </div>
                        <p class="vol-error" x-show="errors.affiliate_type_id" x-text="errors.affiliate_type_id"></p>
                    </div>

                    {{-- Ayala employee: cluster + company --}}
                    <div x-show="form.affiliate_type_id == 1" style="display:flex; flex-direction:column; gap:14px;">
                        <div>
                            <label class="vol-label req">Cluster</label>
                            <select x-model="form.cluster_id" class="vol-select">
                                <option value="">Select cluster</option>
                                @foreach($clusters ?? [] as $cluster)
                                <option value="{{ $cluster->id }}">{{ $cluster->name }}</option>
                                @endforeach
                            </select>
                            <p class="vol-error" x-show="errors.cluster_id" x-text="errors.cluster_id"></p>
                        </div>
                        <div>
                            <label class="vol-label req">Company</label>
                            <select x-model="form.company_id" class="vol-select">
                                <option value="">Select company</option>
                                @foreach($companies ?? [] as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                            <p class="vol-error" x-show="errors.company_id" x-text="errors.company_id"></p>
                        </div>
                    </div>

                    {{-- Non-Ayala: external company name --}}
                    <div x-show="form.affiliate_type_id == 2">
                        <label class="vol-label req">Company name</label>
                        <input type="text" x-model="form.external_company_name" class="vol-input" placeholder="Your organization">
                        <p class="vol-error" x-show="errors.external_company_name" x-text="errors.external_company_name"></p>
                    </div>
                </div>

                {{-- ── STEP 2: Account setup ───────────────────────────────── --}}
                <div x-show="currentStep === 2" x-transition style="display:flex; flex-direction:column; gap:18px;">
                    <h2 style="font-size:20px; margin:0 0 2px;">Account setup</h2>

                    <div style="padding:14px 16px; background:var(--blue-50); border-radius:12px; display:flex; gap:10px; align-items:flex-start;">
                        <svg width="17" height="17" fill="none" stroke="var(--blue-700)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" style="flex-shrink:0; margin-top:2px;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/></svg>
                        <p style="font-size:13.5px; color:var(--blue-800); line-height:1.5; margin:0;">In case of emergencies on volunteer days, we need someone we can contact. This information is kept strictly private.</p>
                    </div>

                    <div class="form-row2" style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">
                        <div>
                            <label class="vol-label req">Emergency contact name</label>
                            <input type="text" x-model="form.emergency_contact_name" class="vol-input" placeholder="Full name">
                            <p class="vol-error" x-show="errors.emergency_contact_name" x-text="errors.emergency_contact_name"></p>
                        </div>
                        <div>
                            <label class="vol-label req">Emergency contact number</label>
                            <input type="tel" x-model="form.emergency_contact_number" class="vol-input" placeholder="+63 9XX XXX XXXX">
                            <p class="vol-error" x-show="errors.emergency_contact_number" x-text="errors.emergency_contact_number"></p>
                        </div>
                    </div>

                    <hr style="border:none; border-top:1px solid var(--line); margin:4px 0;">

                    <div>
                        <label class="vol-label req">Password</label>
                        <div style="position:relative;">
                            <input :type="showPw ? 'text' : 'password'" x-model="form.password" class="vol-input" placeholder="••••••••" style="padding-right:52px;">
                            <button type="button" @click="showPw = !showPw"
                                style="position:absolute; right:13px; top:50%; transform:translateY(-50%); font-size:12px; font-weight:700; color:var(--blue-700); background:none; border:none; cursor:pointer;"
                                x-text="showPw ? 'Hide' : 'Show'"></button>
                        </div>
                        <p class="vol-hint">Minimum 8 characters</p>
                        <p class="vol-error" x-show="errors.password" x-text="errors.password"></p>
                    </div>

                    <div>
                        <label class="vol-label req">Confirm password</label>
                        <input :type="showPw ? 'text' : 'password'" x-model="form.password_confirmation" class="vol-input" placeholder="••••••••">
                        <p class="vol-error" x-show="errors.password_confirmation" x-text="errors.password_confirmation"></p>
                    </div>

                    <label style="display:flex; gap:10px; align-items:flex-start; cursor:pointer; background:var(--bg-soft); padding:13px 15px; border-radius:12px;">
                        <input type="checkbox" x-model="form.agree_terms" style="width:17px; height:17px; margin-top:2px; accent-color:var(--blue-700); flex-shrink:0;">
                        <span style="font-size:13.5px; color:var(--slate); line-height:1.55;">
                            I agree to the <a href="{{ route('terms-and-conditions') }}" target="_blank" style="color:var(--blue-700); font-weight:600; text-decoration:underline;">Volunteer Code of Conduct</a> and consent to the processing of my personal data per Ayala Foundation's <a href="{{ route('data-privacy-policy') }}" target="_blank" style="color:var(--blue-700); font-weight:600; text-decoration:underline;">Data Privacy Policy</a>.
                        </span>
                    </label>
                    <p class="vol-error" x-show="errors.agree_terms" x-text="errors.agree_terms"></p>
                </div>

                {{-- ── Error banner ─────────────────────────────────────────── --}}
                <div x-show="errors.general" style="margin-top:16px; padding:13px 15px; background:#fff2f0; border:1px solid #ffccc7; border-radius:10px; font-size:13.5px; color:#cf1322; font-weight:600;" x-text="errors.general"></div>

                {{-- ── Navigation buttons ───────────────────────────────────── --}}
                <div style="display:flex; gap:12px; margin-top:26px; border-top:1px solid var(--line-soft); padding-top:26px;">
                    <button type="button" @click="prevStep()"
                        style="padding:13px 24px; font-size:15px; font-weight:700; color:var(--slate); background:transparent; border:1.5px solid var(--line); border-radius:999px; cursor:pointer; transition:.14s;"
                        onmouseover="this.style.borderColor='var(--blue-500)'; this.style.color='var(--blue-700)'; this.style.background='var(--blue-50)'"
                        onmouseout="this.style.borderColor='var(--line)'; this.style.color='var(--slate)'; this.style.background='transparent'">
                        <span x-text="currentStep === 0 ? 'Cancel' : 'Back'"></span>
                    </button>

                    <button type="button" x-show="currentStep < 2" @click="nextStep()"
                        style="flex:1; background:var(--orange-500); color:#fff; font-weight:700; font-size:15px; padding:13px 24px; border-radius:999px; border:none; cursor:pointer; box-shadow:0 6px 16px rgba(240,122,30,.28); transition:.16s;"
                        onmouseover="this.style.background='var(--orange-600)'; this.style.transform='translateY(-1px)'"
                        onmouseout="this.style.background='var(--orange-500)'; this.style.transform='none'">
                        <span style="display:inline-flex; align-items:center; justify-content:center; gap:9px;">
                            Continue
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </span>
                    </button>

                    <button type="submit" x-show="currentStep === 2"
                        :disabled="!form.agree_terms || isSubmitting"
                        style="flex:1; background:var(--orange-500); color:#fff; font-weight:700; font-size:15px; padding:13px 24px; border-radius:999px; border:none; cursor:pointer; box-shadow:0 6px 16px rgba(240,122,30,.28); transition:.16s;"
                        :style="(!form.agree_terms || isSubmitting) ? 'opacity:.5; cursor:not-allowed; transform:none' : ''"
                        onmouseover="if (!this.disabled) { this.style.background='var(--orange-600)'; this.style.transform='translateY(-1px)'; }"
                        onmouseout="this.style.background='var(--orange-500)'; this.style.transform='none'">
                        <span style="display:inline-flex; align-items:center; justify-content:center; gap:9px;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg>
                            <span x-show="!isSubmitting">Create my profile</span>
                            <span x-show="isSubmitting">Submitting…</span>
                        </span>
                    </button>
                </div>
            </form>

            <p style="text-align:center; font-size:13px; color:var(--muted); margin-top:16px; display:flex; align-items:center; justify-content:center; gap:7px;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/></svg>
                Your information is kept private and secure.
            </p>
        </div>
    </div>
</div>

<script>
function volunteerForm() {
    return {
        currentStep: 0,
        isSubmitting: false,
        showPw: false,
        form: {
            firstname: '',
            lastname: '',
            nickname: '',
            email: '',
            age_range: '',
            program_ids: [],
            other_program: '',
            referral_source: [],
            affiliate_type_id: '',
            cluster_id: '',
            company_id: '',
            external_company_name: '',
            emergency_contact_name: '',
            emergency_contact_number: '',
            password: '',
            password_confirmation: '',
            agree_terms: false,
        },
        errors: {},

        toggleProgram(id) {
            const idx = this.form.program_ids.indexOf(id);
            if (idx > -1) this.form.program_ids.splice(idx, 1);
            else this.form.program_ids.push(id);
        },

        toggleReferral(val) {
            const idx = this.form.referral_source.indexOf(val);
            if (idx > -1) this.form.referral_source.splice(idx, 1);
            else this.form.referral_source.push(val);
        },

        validateStep(step) {
            this.errors = {};
            if (step === 0) {
                if (!this.form.firstname.trim()) this.errors.firstname = 'Given name is required';
                if (!this.form.lastname.trim())  this.errors.lastname  = 'Last name is required';
                if (!/^\S+@\S+\.\S+$/.test(this.form.email)) this.errors.email = 'Enter a valid email';
                if (!this.form.age_range) this.errors.age_range = 'Please select your age range';
            }
            if (step === 1) {
                if (this.form.program_ids.length === 0) this.errors.program_ids = 'Select at least one program';
                if (this.form.referral_source.length === 0) this.errors.referral_source = 'Please select how you heard about us';
                if (!this.form.affiliate_type_id) this.errors.affiliate_type_id = 'Please select your affiliation';
                if (this.form.affiliate_type_id == 1) {
                    if (!this.form.cluster_id) this.errors.cluster_id = 'Please select your cluster';
                    if (!this.form.company_id) this.errors.company_id = 'Please select your company';
                }
                if (this.form.affiliate_type_id == 2 && !this.form.external_company_name.trim())
                    this.errors.external_company_name = 'Company name is required';
            }
            if (step === 2) {
                if (!this.form.emergency_contact_name.trim()) this.errors.emergency_contact_name = 'Emergency contact name is required';
                if (!this.form.emergency_contact_number.trim()) this.errors.emergency_contact_number = 'Emergency contact number is required';
                if (!this.form.password || this.form.password.length < 8) this.errors.password = 'At least 8 characters required';
                if (this.form.password !== this.form.password_confirmation) this.errors.password_confirmation = 'Passwords do not match';
                if (!this.form.agree_terms) this.errors.agree_terms = 'You must agree to continue';
            }
            return Object.keys(this.errors).length === 0;
        },

        nextStep() {
            if (this.validateStep(this.currentStep)) {
                this.currentStep++;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },

        prevStep() {
            if (this.currentStep === 0) {
                if (confirm('Are you sure you want to cancel? Your progress will be lost.')) {
                    window.location.href = '/';
                }
            } else {
                this.currentStep--;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },

        goBack() {
            if (confirm('Are you sure you want to cancel? Your progress will be lost.')) {
                window.location.href = '/';
            }
        },

        async submitForm() {
            if (!this.validateStep(2)) return;
            this.isSubmitting = true;
            this.errors = {};
            try {
                const response = await fetch('{{ route("volunteer.form.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify(this.form),
                });
                const data = await response.json();
                if (data.success) {
                    window.location.href = '/';
                } else {
                    this.errors = data.errors || {};
                    // If server errors reference step-0 or step-1 fields, jump back
                    const step0Keys = ['firstname','lastname','email','age_range'];
                    const step1Keys = ['program_ids','referral_source','affiliate_type_id','cluster_id','company_id','external_company_name'];
                    const errKeys = Object.keys(this.errors);
                    if (errKeys.some(k => step0Keys.includes(k))) this.currentStep = 0;
                    else if (errKeys.some(k => step1Keys.includes(k))) this.currentStep = 1;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            } catch (error) {
                this.errors = { general: 'An error occurred. Please try again.' };
            } finally {
                this.isSubmitting = false;
            }
        }
    };
}
</script>
@endsection
