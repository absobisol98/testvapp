@extends('custom.layouts.app')
@section('title', 'Become a Volunteer')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700;12..96,800&family=Public+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

<style>
:root {
    --blue-900:#072b54; --blue-800:#0a3a6e; --blue-700:#0e4f99;
    --blue-600:#1565c4; --blue-500:#2a7de0; --blue-50:#eef4fc;
    --orange-600:#d9650c; --orange-500:#f07a1e; --orange-50:#fef2e7;
    --green-600:#1d8a52; --green-50:#eaf7f0;
    --ink:#0d1b2e; --slate:#4a5568; --muted:#8896a4; --faint:#c4cdd6;
    --line:#e8ecf0; --line-soft:#f0f3f6; --bg-soft:#f7f9fb;
    --font-display:"Bricolage Grotesque",system-ui,sans-serif;
    --font-body:"Public Sans",system-ui,sans-serif;
    --r-md:12px; --r-lg:16px; --r-xl:22px; --r-pill:999px;
    --sh-sm:0 1px 4px rgba(13,27,46,.07);
    --sh-md:0 4px 16px rgba(13,27,46,.09);
}
.vreg { font-family:var(--font-body); color:var(--ink); background:var(--bg-soft); min-height:100vh; }
.vreg h1,.vreg h2,.vreg h3 { font-family:var(--font-display); margin:0; line-height:1.08; letter-spacing:-.015em; font-weight:700; }

/* Layout */
.vreg-wrap { max-width:680px; margin:0 auto; padding:0 20px; }

/* Stepper */
.vreg-stepper { display:flex; align-items:center; margin-bottom:28px; }
.vreg-step-dot {
    width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center;
    font-weight:700; font-size:13px; flex:none; transition:.2s;
}
.vreg-step-dot.done  { background:var(--green-600); color:#fff; }
.vreg-step-dot.active{ background:var(--blue-700);  color:#fff; }
.vreg-step-dot.todo  { background:var(--line);       color:var(--muted); }
.vreg-step-label { font-size:13px; font-weight:700; margin-left:8px; transition:.2s; }
.vreg-step-line { flex:1; height:2px; margin:0 10px; transition:.2s; }

/* Card */
.vreg-card {
    background:#fff; border:1px solid var(--line); border-radius:var(--r-xl);
    padding:28px 30px; box-shadow:var(--sh-sm);
}

/* Fields */
.vreg-field { display:flex; flex-direction:column; gap:6px; }
.vreg-field label { font-size:13.5px; font-weight:700; color:var(--slate); }
.vreg-req { color:var(--orange-600); }
.vreg-hint { font-size:12px; color:var(--muted); }
.vreg-input, .vreg-select {
    font-family:var(--font-body); font-size:15px; color:var(--ink); background:#fff;
    border:1.5px solid var(--line); border-radius:var(--r-md); padding:10px 14px;
    width:100%; box-sizing:border-box; transition:border-color .15s, box-shadow .15s;
    appearance:none;
}
.vreg-input:focus, .vreg-select:focus {
    outline:none; border-color:var(--blue-500); box-shadow:0 0 0 3px var(--blue-50);
}
.vreg-input::placeholder { color:var(--faint); }
.vreg-input.invalid, .vreg-select.invalid { border-color:#e53e3e; }
.vreg-err { font-size:12px; color:#e53e3e; font-weight:600; display:none; }
.vreg-err.show { display:block; }

/* 2-col grid */
.vreg-row2 { display:grid; grid-template-columns:1fr 1fr; gap:16px; }

/* Chips (program multi-select) */
.vreg-chips { display:flex; flex-wrap:wrap; gap:8px; margin-top:8px; }
.vreg-chip {
    font-family:var(--font-body); font-weight:600; font-size:13.5px;
    padding:8px 14px; border-radius:var(--r-pill); border:1.5px solid var(--line);
    background:#fff; color:var(--slate); cursor:pointer; transition:.14s; display:inline-flex; align-items:center; gap:6px;
}
.vreg-chip:hover { border-color:var(--blue-500); color:var(--blue-700); }
.vreg-chip.active { background:var(--blue-700); border-color:var(--blue-700); color:#fff; }

/* Radio affiliation cards */
.vreg-radio-card {
    display:flex; align-items:center; gap:10px; cursor:pointer;
    padding:12px 16px; border:1.5px solid var(--line); border-radius:var(--r-md);
    background:#fff; flex:1; font-weight:600; font-size:14px; transition:.14s;
}
.vreg-radio-card.active { border-color:var(--blue-500); background:var(--blue-50); }

/* Checkbox rows */
.vreg-check-row {
    display:flex; align-items:center; gap:11px; cursor:pointer;
    font-size:15px; padding:4px 0;
}
.vreg-check-row input[type=checkbox] { width:17px; height:17px; accent-color:var(--blue-700); flex:none; }

/* Buttons */
.vreg-btn {
    display:inline-flex; align-items:center; justify-content:center; gap:9px;
    font-family:var(--font-body); font-weight:700; font-size:15px;
    padding:12px 22px; border-radius:var(--r-md); border:none; cursor:pointer;
    text-decoration:none; transition:all .18s; white-space:nowrap; line-height:1;
}
.vreg-btn svg { width:17px; height:17px; }
.vreg-btn-primary { background:var(--orange-500); color:#fff; box-shadow:0 6px 16px rgba(240,122,30,.28); }
.vreg-btn-primary:hover:not(:disabled) { background:var(--orange-600); transform:translateY(-1px); }
.vreg-btn-primary:disabled { opacity:.5; cursor:not-allowed; }
.vreg-btn-ghost { background:transparent; color:var(--blue-700); border:1.5px solid var(--line); }
.vreg-btn-ghost:hover { border-color:var(--blue-500); background:var(--blue-50); }

/* Info box */
.vreg-info { padding:13px 15px; background:var(--blue-50); border-radius:var(--r-md); display:flex; gap:10px; align-items:flex-start; }
.vreg-info p { font-size:13.5px; color:var(--blue-800); line-height:1.55; margin:0; }

/* Eyebrow */
.vreg-eyebrow { font-family:var(--font-body); font-weight:700; font-size:12px; letter-spacing:.14em; text-transform:uppercase; color:var(--orange-600); }

/* Password wrapper */
.vreg-pw-wrap { position:relative; }
.vreg-pw-wrap .vreg-input { padding-right:56px; }
.vreg-pw-toggle { position:absolute; right:13px; top:50%; transform:translateY(-50%); font-size:12px; font-weight:700; color:var(--blue-700); background:none; border:none; cursor:pointer; }

/* Confirm banner */
.vreg-confirm { background:#fff; border:1px solid var(--line); border-radius:var(--r-xl); padding:44px 38px; box-shadow:var(--sh-md); text-align:center; }
.vreg-confirm-icon {
    width:76px; height:76px; border-radius:50%; background:var(--green-50); color:var(--green-600);
    display:flex; align-items:center; justify-content:center; margin:0 auto 22px;
    animation:vreg-pop .45s .1s both;
}
@keyframes vreg-fadeUp { from{opacity:0;transform:translateY(14px);}to{opacity:1;transform:none;} }
@keyframes vreg-pop { from{opacity:0;transform:scale(.9);}to{opacity:1;transform:none;} }
.vreg-fade { animation:vreg-fadeUp .3s both; }

@media(max-width:520px){
    .vreg-row2 { grid-template-columns:1fr !important; }
    .vreg-step-label { display:none; }
    .vreg-card { padding:20px 18px; }
}
</style>

<div class="vreg">
    <div style="padding:36px 0 80px;">
        <div class="vreg-wrap">

            {{-- Back link --}}
            <a href="{{ url('/') }}" style="display:inline-flex;align-items:center;gap:7px;color:var(--muted);font-size:14px;font-weight:600;text-decoration:none;margin-bottom:22px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
                Back to home
            </a>

            {{-- Header --}}
            <div style="margin-bottom:24px;">
                <div class="vreg-eyebrow" style="margin-bottom:8px;">Volunteer Platform</div>
                <h1 style="font-size:clamp(26px,4vw,34px);">Become a Volunteer</h1>
                <p style="color:var(--slate);margin-top:7px;font-size:15.5px;">Create your profile. Takes about two minutes.</p>
            </div>

            {{-- Stepper --}}
            <div class="vreg-stepper" id="vreg-stepper">
                @php $stepLabels = ['About you','Programs','Account']; @endphp
                @foreach($stepLabels as $si => $slabel)
                    <div class="vreg-step-dot {{ $si === 0 ? 'active' : 'todo' }}" id="step-dot-{{ $si }}">
                        <span id="step-dot-inner-{{ $si }}">{{ $si + 1 }}</span>
                    </div>
                    <span class="vreg-step-label" id="step-label-{{ $si }}"
                        style="color:{{ $si === 0 ? 'var(--ink)' : 'var(--muted)' }};">{{ $slabel }}</span>
                    @if(!$loop->last)
                        <div class="vreg-step-line" id="step-line-{{ $si }}" style="background:var(--line);"></div>
                    @endif
                @endforeach
            </div>

            {{-- Form wrapper (real POST) --}}
            <form id="vreg-form" action="{{ route('volunteer.form.store') }}" method="POST">
                @csrf

                {{-- =========================================================
                     STEP 1 — About you
                ========================================================= --}}
                <div id="vreg-step-0" class="vreg-card vreg-fade">
                    <h2 style="font-size:20px;margin-bottom:20px;">Personal information</h2>

                    <div style="display:flex;flex-direction:column;gap:16px;">
                        <div class="vreg-row2">
                            <div class="vreg-field">
                                <label>Given name <span class="vreg-req">*</span></label>
                                <input type="text" name="firstname" class="vreg-input" placeholder="Juan" />
                                <span class="vreg-err" id="err-firstname"></span>
                            </div>
                            <div class="vreg-field">
                                <label>Nickname <span class="vreg-hint">(optional)</span></label>
                                <input type="text" name="nickname" class="vreg-input" placeholder="How should we call you?" />
                            </div>
                        </div>

                        <div class="vreg-field">
                            <label>Last name <span class="vreg-req">*</span></label>
                            <input type="text" name="lastname" class="vreg-input" placeholder="Dela Cruz" />
                            <span class="vreg-err" id="err-lastname"></span>
                        </div>

                        <div class="vreg-field">
                            <label>Email <span class="vreg-req">*</span></label>
                            <input type="email" name="email" class="vreg-input" placeholder="you@email.com" />
                            <span class="vreg-err" id="err-email"></span>
                        </div>

                        <div class="vreg-field">
                            <label>Age range <span class="vreg-req">*</span></label>
                            <select name="age_range" class="vreg-select" style="background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%238896a4' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E\");background-repeat:no-repeat;background-position:right 12px center;background-size:18px;padding-right:38px;">
                                <option value="" disabled selected>Select your age range</option>
                                <option value="10-17">Under 18</option>
                                <option value="18-24">18–24</option>
                                <option value="25-34">25–34</option>
                                <option value="35-44">35–44</option>
                                <option value="45-54">45–54</option>
                                <option value="55-64">55–64</option>
                                <option value="65+">65 and above</option>
                            </select>
                            <span class="vreg-err" id="err-age_range"></span>
                        </div>
                    </div>

                    <div style="display:flex;gap:12px;margin-top:26px;">
                        <a href="{{ url('/') }}" class="vreg-btn vreg-btn-ghost">Cancel</a>
                        <button type="button" class="vreg-btn vreg-btn-primary" style="flex:1;" onclick="vregNext(0)">
                            Continue
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </button>
                    </div>
                </div>

                {{-- =========================================================
                     STEP 2 — Programs & Affiliation
                ========================================================= --}}
                <div id="vreg-step-1" class="vreg-card vreg-fade" style="display:none;">
                    <h2 style="font-size:20px;margin-bottom:20px;">Programs &amp; affiliation</h2>

                    <div style="display:flex;flex-direction:column;gap:22px;">

                        {{-- Programs multi-select (chips) --}}
                        <div class="vreg-field">
                            <label>What programs are you interested in? <span style="font-weight:400;color:var(--muted);">(select all that apply)</span></label>
                            <div class="vreg-chips" id="vreg-program-chips">
                                @foreach($programs as $program)
                                    <button type="button" class="vreg-chip" data-value="{{ $program->id }}" onclick="vregToggleChip(this,'program_ids[]')">
                                        {{ $program->name }}
                                    </button>
                                @endforeach
                                <button type="button" class="vreg-chip" data-value="other" onclick="vregToggleChip(this,'program_ids[]');vregToggleOther(this);">
                                    Others (please specify)
                                </button>
                            </div>
                            {{-- Hidden checkboxes to carry values --}}
                            <div id="vreg-program-inputs"></div>
                            <div id="vreg-other-program-wrap" style="display:none;margin-top:10px;">
                                <input type="text" name="other_program" class="vreg-input" placeholder="Please specify…" />
                            </div>
                            <span class="vreg-err" id="err-program_ids"></span>
                        </div>

                        {{-- How did you hear --}}
                        <div class="vreg-field">
                            <label>How did you hear about us? <span class="vreg-req">*</span></label>
                            <div style="display:flex;flex-direction:column;gap:10px;margin-top:8px;">
                                @php
                                    $hearOptions = [
                                        'afi_website'  => 'AFI website',
                                        'social_media' => 'Social media (Facebook, Instagram, X, TikTok)',
                                        'referral'     => 'Referral programs',
                                        'advertisements' => 'Advertisements',
                                        'activations'  => 'On-the-ground activations and print',
                                        'news'         => 'Online news articles',
                                    ];
                                @endphp
                                @foreach($hearOptions as $hval => $hlabel)
                                <label class="vreg-check-row">
                                    <input type="checkbox" name="referral_source[]" value="{{ $hval }}" />
                                    {{ $hlabel }}
                                </label>
                                @endforeach
                            </div>
                            <span class="vreg-err" id="err-referral_source"></span>
                        </div>

                        {{-- Affiliation type --}}
                        <div class="vreg-field">
                            <label>Company / Affiliation <span class="vreg-req">*</span></label>
                            <div style="display:flex;gap:12px;margin-top:8px;" id="vreg-affil-radios">
                                <label class="vreg-radio-card active" id="vreg-affil-ayala" onclick="vregSetAffil('ayala')">
                                    <input type="radio" name="affiliate_type_id" value="1" checked style="accent-color:var(--blue-700);" id="ayala_employee" onchange="updateDropdowns()" />
                                    Ayala Employee
                                </label>
                                <label class="vreg-radio-card" id="vreg-affil-non" onclick="vregSetAffil('non')">
                                    <input type="radio" name="affiliate_type_id" value="2" style="accent-color:var(--blue-700);" id="non_ayala" onchange="updateDropdowns()" />
                                    Non-Ayala Employee
                                </label>
                            </div>
                            <span class="vreg-err" id="err-affiliate_type_id"></span>
                        </div>

                        {{-- Ayala fields --}}
                        <div id="ayala-fields" style="display:flex;flex-direction:column;gap:16px;">
                            <div class="vreg-field">
                                <label>Cluster <span class="vreg-req">*</span></label>
                                <select name="cluster_id" id="clusterSelect" class="vreg-select" style="background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%238896a4' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E\");background-repeat:no-repeat;background-position:right 12px center;background-size:18px;padding-right:38px;" onchange="vregFilterCompanies()">
                                    <option value="" disabled selected>Select cluster</option>
                                    @foreach($clusters as $cluster)
                                        <option value="{{ $cluster->id }}">{{ $cluster->name }}</option>
                                    @endforeach
                                </select>
                                <span class="vreg-err" id="err-cluster_id"></span>
                            </div>
                            <div class="vreg-field">
                                <label>Company name <span class="vreg-req">*</span></label>
                                <select name="company_id" id="companySelect" class="vreg-select" style="background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%238896a4' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E\");background-repeat:no-repeat;background-position:right 12px center;background-size:18px;padding-right:38px;">
                                    <option value="" disabled selected>Select company</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" data-cluster="{{ $company->cluster_id }}">{{ $company->name }}</option>
                                    @endforeach
                                </select>
                                <span class="vreg-err" id="err-company_id"></span>
                            </div>
                        </div>

                        {{-- Non-Ayala fields --}}
                        <div id="non-ayala-fields" style="display:none;">
                            <div class="vreg-field">
                                <label>Company name <span class="vreg-req">*</span></label>
                                <input type="text" name="external_company_name" class="vreg-input" placeholder="Your organization" />
                                <span class="vreg-err" id="err-external_company_name"></span>
                            </div>
                        </div>

                    </div>

                    <div style="display:flex;gap:12px;margin-top:26px;">
                        <button type="button" class="vreg-btn vreg-btn-ghost" onclick="vregBack(1)">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
                            Back
                        </button>
                        <button type="button" class="vreg-btn vreg-btn-primary" style="flex:1;" onclick="vregNext(1)">
                            Continue
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </button>
                    </div>
                </div>

                {{-- =========================================================
                     STEP 3 — Account setup
                ========================================================= --}}
                <div id="vreg-step-2" class="vreg-card vreg-fade" style="display:none;">
                    <h2 style="font-size:20px;margin-bottom:20px;">Account setup</h2>

                    <div style="display:flex;flex-direction:column;gap:16px;">

                        <div class="vreg-info">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:17px;height:17px;color:var(--blue-700);flex:none;margin-top:1px;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/></svg>
                            <p>In case of emergencies on volunteer days, we need someone we can contact. This information is kept strictly private.</p>
                        </div>

                        <div class="vreg-row2">
                            <div class="vreg-field">
                                <label>Emergency contact name <span class="vreg-req">*</span></label>
                                <input type="text" name="emergency_contact_name" class="vreg-input" placeholder="Full name" />
                                <span class="vreg-err" id="err-emergency_contact_name"></span>
                            </div>
                            <div class="vreg-field">
                                <label>Emergency contact number <span class="vreg-req">*</span></label>
                                <input type="text" name="emergency_contact_number" class="vreg-input" placeholder="+63 9XX XXX XXXX"
                                    pattern="[0-9]*" inputmode="numeric" maxlength="15"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,15)" />
                                <span class="vreg-err" id="err-emergency_contact_number"></span>
                            </div>
                        </div>

                        <hr style="border:none;border-top:1px solid var(--line);margin:4px 0;">

                        <div class="vreg-field">
                            <label>Password <span class="vreg-req">*</span></label>
                            <div class="vreg-pw-wrap">
                                <input type="password" name="password" id="vreg-pw" class="vreg-input" placeholder="••••••••" />
                                <button type="button" class="vreg-pw-toggle" onclick="vregTogglePw()">Show</button>
                            </div>
                            <span class="vreg-hint">Minimum 8 characters</span>
                            <span class="vreg-err" id="err-password"></span>
                        </div>

                        <div class="vreg-field">
                            <label>Confirm password <span class="vreg-req">*</span></label>
                            <div class="vreg-pw-wrap">
                                <input type="password" name="passwordConfirmation" id="vreg-pw2" class="vreg-input" placeholder="••••••••" />
                            </div>
                            <span class="vreg-err" id="err-passwordConfirmation"></span>
                        </div>

                        <label style="display:flex;gap:10px;align-items:flex-start;cursor:pointer;background:var(--bg-soft);padding:13px 15px;border-radius:var(--r-md);">
                            <input type="checkbox" id="vreg-agree" style="width:17px;height:17px;accent-color:var(--blue-700);flex:none;margin-top:2px;" />
                            <span style="font-size:13.5px;color:var(--slate);line-height:1.55;">
                                I have read and agree to the Ayala Foundation's
                                <a href="{{ route('data-privacy-policy') }}" style="color:var(--blue-700);font-weight:600;text-decoration:underline;">Data Privacy Policy</a>
                                and <a href="{{ route('terms-and-conditions') }}" style="color:var(--blue-700);font-weight:600;text-decoration:underline;">Terms &amp; Conditions</a>.
                            </span>
                        </label>
                        <span class="vreg-err" id="err-agree"></span>

                    </div>

                    <div style="display:flex;gap:12px;margin-top:26px;">
                        <button type="button" class="vreg-btn vreg-btn-ghost" onclick="vregBack(2)">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
                            Back
                        </button>
                        <button type="button" id="btn-register" class="vreg-btn vreg-btn-primary" style="flex:1;" onclick="vregSubmit()">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:17px;height:17px;"><path d="M20 6 9 17l-5-5"/></svg>
                            Create my profile
                        </button>
                    </div>
                </div>

            </form>{{-- end form --}}

            {{-- =====================================================================
                 CONFIRMATION (hidden, shown on success)
            ===================================================================== --}}
            <div id="vreg-confirmation" style="display:none;" class="vreg-fade">
                <div class="vreg-confirm">
                    <div class="vreg-confirm-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:38px;height:38px;"><path d="M20 6 9 17l-5-5"/></svg>
                    </div>
                    <h1 style="font-size:28px;line-height:1.1;">Welcome, <span id="vreg-success-name">Volunteer</span>!</h1>
                    <p style="font-size:16px;color:var(--slate);margin-top:14px;line-height:1.65;max-width:420px;margin-left:auto;margin-right:auto;">
                        Your volunteer profile is ready. We've sent a confirmation to <strong id="vreg-success-email"></strong>.
                        You can now browse and join opportunities.
                    </p>
                    <div style="background:var(--blue-50);border-radius:var(--r-md);padding:14px 16px;margin:22px auto 0;text-align:left;display:flex;gap:10px;align-items:flex-start;max-width:400px;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:17px;height:17px;color:var(--blue-700);flex:none;margin-top:2px;"><path d="M12 3v4M12 17v4M3 12h4M17 12h4M6 6l2.5 2.5M15.5 15.5 18 18M18 6l-2.5 2.5M8.5 15.5 6 18"/></svg>
                        <p style="font-size:13.5px;color:var(--blue-800);line-height:1.5;margin:0;">Your volunteer hours are tracked automatically as you join and complete activities.</p>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:10px;margin-top:26px;max-width:340px;margin-left:auto;margin-right:auto;">
                        <a href="{{ url('/') }}" class="vreg-btn vreg-btn-primary" style="width:100%;justify-content:center;">
                            Back to home
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            <p style="text-align:center;font-size:13px;color:var(--muted);margin-top:18px;display:flex;align-items:center;gap:7px;justify-content:center;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/></svg>
                Your information is kept private and secure.
            </p>

        </div>{{-- end vreg-wrap --}}
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// ─── Step navigation ───────────────────────────────────────────────────────

var vregCurrent = 0;

function vregShowStep(n) {
    for (var i = 0; i < 3; i++) {
        var el = document.getElementById('vreg-step-' + i);
        if (el) el.style.display = (i === n) ? '' : 'none';
    }
    // update stepper
    for (var i = 0; i < 3; i++) {
        var dot   = document.getElementById('step-dot-' + i);
        var inner = document.getElementById('step-dot-inner-' + i);
        var label = document.getElementById('step-label-' + i);
        var line  = document.getElementById('step-line-' + i);
        if (i < n) {
            dot.className = 'vreg-step-dot done';
            inner.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><path d="M20 6 9 17l-5-5"/></svg>';
            label.style.color = 'var(--slate)';
            if (line) line.style.background = 'var(--green-600)';
        } else if (i === n) {
            dot.className = 'vreg-step-dot active';
            inner.textContent = i + 1;
            label.style.color = 'var(--ink)';
        } else {
            dot.className = 'vreg-step-dot todo';
            inner.textContent = i + 1;
            label.style.color = 'var(--muted)';
            if (line) line.style.background = 'var(--line)';
        }
    }
    vregCurrent = n;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function vregNext(fromStep) {
    if (!vregValidate(fromStep)) return;
    vregShowStep(fromStep + 1);
}

function vregBack(fromStep) {
    vregShowStep(fromStep - 1);
}

// ─── Validation ────────────────────────────────────────────────────────────

function vregShowErr(id, msg) {
    var el = document.getElementById('err-' + id);
    if (!el) return;
    el.textContent = msg;
    el.classList.add('show');
}
function vregClearErrs() {
    document.querySelectorAll('.vreg-err').forEach(function(e){ e.classList.remove('show'); e.textContent=''; });
    document.querySelectorAll('.vreg-input.invalid,.vreg-select.invalid').forEach(function(e){ e.classList.remove('invalid'); });
}

function vregValidate(step) {
    vregClearErrs();
    var ok = true;

    if (step === 0) {
        var fn = document.querySelector('[name="firstname"]').value.trim();
        var ln = document.querySelector('[name="lastname"]').value.trim();
        var em = document.querySelector('[name="email"]').value.trim();
        var ar = document.querySelector('[name="age_range"]').value;
        if (!fn) { vregShowErr('firstname','Given name is required'); ok=false; }
        if (!ln) { vregShowErr('lastname','Last name is required'); ok=false; }
        if (!/^\S+@\S+\.\S+$/.test(em)) { vregShowErr('email','Enter a valid email address'); ok=false; }
        if (!ar) { vregShowErr('age_range','Please select an age range'); ok=false; }
    }

    if (step === 1) {
        var programs = document.querySelectorAll('#vreg-program-inputs input[type=hidden]');
        if (programs.length === 0) { vregShowErr('program_ids','Please select at least one program'); ok=false; }

        var referrals = document.querySelectorAll('[name="referral_source[]"]:checked');
        if (referrals.length === 0) { vregShowErr('referral_source','Please select at least one option'); ok=false; }

        var isAyala = document.getElementById('ayala_employee').checked;
        if (isAyala) {
            if (!document.getElementById('clusterSelect').value) { vregShowErr('cluster_id','Cluster is required'); ok=false; }
            if (!document.getElementById('companySelect').value) { vregShowErr('company_id','Company is required'); ok=false; }
        } else {
            var extCo = document.querySelector('[name="external_company_name"]').value.trim();
            if (!extCo) { vregShowErr('external_company_name','Company name is required'); ok=false; }
        }
    }

    if (step === 2) {
        var ecn  = document.querySelector('[name="emergency_contact_name"]').value.trim();
        var ecno = document.querySelector('[name="emergency_contact_number"]').value.trim();
        var pw   = document.getElementById('vreg-pw').value;
        var pw2  = document.getElementById('vreg-pw2').value;
        var agree = document.getElementById('vreg-agree').checked;
        if (!ecn)  { vregShowErr('emergency_contact_name','Emergency contact name is required'); ok=false; }
        if (!ecno) { vregShowErr('emergency_contact_number','Emergency contact number is required'); ok=false; }
        if (!pw || pw.length < 8) { vregShowErr('password','Password must be at least 8 characters'); ok=false; }
        if (pw !== pw2) { vregShowErr('passwordConfirmation','Passwords do not match'); ok=false; }
        if (!agree) { vregShowErr('agree','Please agree to the Data Privacy Policy'); ok=false; }
    }

    return ok;
}

// ─── Program chips ─────────────────────────────────────────────────────────

function vregToggleChip(btn, name) {
    btn.classList.toggle('active');
    var val = btn.getAttribute('data-value');
    var container = document.getElementById('vreg-program-inputs');
    var existing = container.querySelector('input[value="' + val + '"]');
    if (btn.classList.contains('active')) {
        if (!existing) {
            var inp = document.createElement('input');
            inp.type = 'hidden'; inp.name = name; inp.value = val;
            container.appendChild(inp);
        }
    } else {
        if (existing) existing.remove();
    }
}

function vregToggleOther(btn) {
    var wrap = document.getElementById('vreg-other-program-wrap');
    wrap.style.display = btn.classList.contains('active') ? 'block' : 'none';
}

// ─── Affiliation radio style ────────────────────────────────────────────────

function vregSetAffil(type) {
    document.getElementById('vreg-affil-ayala').classList.toggle('active', type === 'ayala');
    document.getElementById('vreg-affil-non').classList.toggle('active', type === 'non');
    document.getElementById('ayala_employee').checked = (type === 'ayala');
    document.getElementById('non_ayala').checked = (type === 'non');
    updateDropdowns();
}

function updateDropdowns() {
    var isAyala = document.getElementById('ayala_employee').checked;
    document.getElementById('ayala-fields').style.display = isAyala ? '' : 'none';
    document.getElementById('non-ayala-fields').style.display = isAyala ? 'none' : '';
    document.getElementById('clusterSelect').required = isAyala;
    document.getElementById('companySelect').required = isAyala;
    var extCo = document.querySelector('[name="external_company_name"]');
    if (extCo) extCo.required = !isAyala;
}

// ─── Cluster → company filter ───────────────────────────────────────────────

function vregFilterCompanies() {
    var clusterId = document.getElementById('clusterSelect').value;
    var sel = document.getElementById('companySelect');
    sel.value = '';
    Array.from(sel.options).forEach(function(opt) {
        if (!opt.value) return;
        opt.hidden = clusterId ? (opt.getAttribute('data-cluster') !== clusterId) : false;
    });
}

// ─── Password toggle ────────────────────────────────────────────────────────

function vregTogglePw() {
    var pw = document.getElementById('vreg-pw');
    var pw2 = document.getElementById('vreg-pw2');
    var btn = document.querySelector('.vreg-pw-toggle');
    var show = pw.type === 'password';
    pw.type = pw2.type = show ? 'text' : 'password';
    btn.textContent = show ? 'Hide' : 'Show';
}

// ─── Submit ─────────────────────────────────────────────────────────────────

function vregSubmit() {
    if (!vregValidate(2)) return;

    var btn = document.getElementById('btn-register');
    btn.disabled = true;
    btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:17px;height:17px;animation:vreg-spin .7s linear infinite;"><path d="M21 12a9 9 0 1 1-6.2-8.6"/></svg> Registering…';

    var formData = {
        _token:                   $("input[name='_token']").val(),
        firstname:                $('[name="firstname"]').val(),
        lastname:                 $('[name="lastname"]').val(),
        nickname:                 $('[name="nickname"]').val(),
        email:                    $('[name="email"]').val(),
        age_range:                $('[name="age_range"]').val(),
        program_ids:              $('#vreg-program-inputs input[type=hidden]').map(function(){ return $(this).val(); }).get(),
        other_program:            $('[name="other_program"]').val(),
        referral_source:          $('[name="referral_source[]"]:checked').map(function(){ return $(this).val(); }).get(),
        affiliate_type_id:        parseInt($('input[name="affiliate_type_id"]:checked').val()),
        cluster_id:               $('#ayala_employee').is(':checked') ? $('#clusterSelect').val() : null,
        company_id:               $('#ayala_employee').is(':checked') ? $('#companySelect').val() : null,
        external_company_name:    $('#non_ayala').is(':checked') ? $('[name="external_company_name"]').val() : null,
        emergency_contact_name:   $('[name="emergency_contact_name"]').val(),
        emergency_contact_number: $('[name="emergency_contact_number"]').val(),
        password:                 $('[name="password"]').val(),
        passwordConfirmation:     $('[name="passwordConfirmation"]').val(),
    };

    $.ajax({
        url: "{{ route('volunteer.form.store') }}",
        type: 'POST',
        data: formData,
        success: function(response) {
            if (response.success) {
                // Show confirmation step
                document.getElementById('vreg-form').style.display = 'none';
                document.getElementById('vreg-stepper').style.display = 'none';
                document.querySelector('.vreg-eyebrow').style.display = 'none';
                document.querySelector('h1').style.display = 'none';
                document.querySelector('p[style*="slate"]') && (document.querySelector('p[style*="slate"]').style.display = 'none');

                document.getElementById('vreg-success-name').textContent = formData.firstname || 'Volunteer';
                document.getElementById('vreg-success-email').textContent = formData.email;
                document.getElementById('vreg-confirmation').style.display = '';

                // Redirect after short delay (matching original behaviour)
                setTimeout(function() {
                    window.location.replace("{{ route('verification.sent') }}");
                }, 2500);
            } else {
                btn.disabled = false;
                btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:17px;height:17px;"><path d="M20 6 9 17l-5-5"/></svg> Create my profile';
                vregHandleErrors(response.errors || {});
                // If errors relate to step 1 or 2, jump back
                var step0fields = ['firstname','lastname','email','age_range'];
                var hasStep0 = Object.keys(response.errors||{}).some(function(k){ return step0fields.includes(k); });
                if (hasStep0) vregShowStep(0);
            }
        },
        error: function(xhr) {
            btn.disabled = false;
            btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:17px;height:17px;"><path d="M20 6 9 17l-5-5"/></svg> Create my profile';
            if (xhr.status === 422) {
                vregHandleErrors(xhr.responseJSON.errors || {});
            } else {
                alert('An error occurred. Please try again.');
            }
        }
    });
}

function vregHandleErrors(errors) {
    Object.entries(errors).forEach(function([field, msgs]) {
        var errEl = document.getElementById('err-' + field);
        if (errEl) { errEl.textContent = msgs[0]; errEl.classList.add('show'); }
        var inp = document.querySelector('[name="' + field + '"]');
        if (inp) inp.classList.add('invalid');
    });
}

// Spin keyframe for loading
var style = document.createElement('style');
style.textContent = '@keyframes vreg-spin{to{transform:rotate(360deg)}}';
document.head.appendChild(style);

// Init
updateDropdowns();
</script>

@endsection
