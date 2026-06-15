<footer style="background:var(--blue-900,#072b54); color:rgba(255,255,255,.78); padding-top:64px;">
    <div style="max-width:1200px; margin:0 auto; padding:0 28px;">
        <div class="foot-grid" style="display:grid; grid-template-columns:1.4fr 1fr 1fr 1fr; gap:40px; padding-bottom:44px;">

            {{-- Brand column --}}
            <div>
                <a href="{{ route('main.homepage.view') }}" style="display:flex; align-items:center; gap:10px; text-decoration:none;">
                    <svg width="34" height="34" viewBox="0 0 40 40" aria-hidden="true" style="flex:none;">
                        <path d="M5 7 L11 7 L22 32 L16 32 Z" fill="#ffffff"/>
                        <path d="M35 7 L29 7 L18 32 L24 32 Z" fill="#ffffff"/>
                        <path d="M16 32 L24 32 L20 38 Z" fill="var(--or-500,#f07a1e)"/>
                    </svg>
                    <span style="font-family:'Inter',system-ui,sans-serif; font-weight:800; font-size:18px; letter-spacing:-.03em; color:#fff;">VApp</span>
                </a>
                <p style="margin-top:16px; font-size:14px; line-height:1.65; max-width:300px;">
                    VApp — connecting people who care with causes that need them.
                </p>
                <div style="display:flex; gap:10px; margin-top:20px;">
                    @foreach([
                        ['icon'=>'facebook', 'path'=>'M16 8h-2a2 2 0 0 0-2 2v12M9 13h6'],
                        ['icon'=>'x',        'path'=>'M4 4l16 16M20 4 4 20'],
                        ['icon'=>'instagram','path'=>'M16 3H8a5 5 0 0 0-5 5v8a5 5 0 0 0 5 5h8a5 5 0 0 0 5-5V8a5 5 0 0 0-5-5Z|M12 16a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM17.5 6.5h.01'],
                        ['icon'=>'youtube',  'path'=>'M22 8.2a3 3 0 0 0-2.1-2.1C18 5.5 12 5.5 12 5.5s-6 0-7.9.6A3 3 0 0 0 2 8.2 31 31 0 0 0 1.5 12 31 31 0 0 0 2 15.8a3 3 0 0 0 2.1 2.1c1.9.6 7.9.6 7.9.6s6 0 7.9-.6a3 3 0 0 0 2.1-2.1A31 31 0 0 0 22.5 12 31 31 0 0 0 22 8.2Z|m10 15 5-3-5-3v6Z'],
                        ['icon'=>'linkedin', 'path'=>'M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-4 0v7h-4v-11h4v1.5A4 4 0 0 1 16 8ZM6 9H2v11h4zM4 6a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z'],
                    ] as $soc)
                    <a href="#" onclick="event.preventDefault()" aria-label="{{ $soc['icon'] }}"
                       style="width:38px; height:38px; border-radius:50%; background:rgba(255,255,255,.1); display:flex; align-items:center; justify-content:center; color:#fff; transition:.15s; flex:none;"
                       onmouseover="this.style.background='var(--or-500,#f07a1e)'" onmouseout="this.style.background='rgba(255,255,255,.1)'">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            @foreach(explode('|', $soc['path']) as $p)
                                <path d="{{ $p }}"/>
                            @endforeach
                        </svg>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Get involved column --}}
            <div>
                <h4 style="font-size:13px; letter-spacing:.1em; text-transform:uppercase; color:#fff; margin-bottom:16px; font-family:'Inter',system-ui,sans-serif; font-weight:700;">Get involved</h4>
                <ul style="display:flex; flex-direction:column; gap:11px; list-style:none; margin:0; padding:0;">
                    @foreach([
                        ['Browse opportunities', url('/admin/events')],
                        ['Become a volunteer', route('volunteer.form.view')],
                        ['For partner companies', '#'],
                        ['Volunteer FAQ', '#'],
                    ] as [$label, $href])
                    <li>
                        <a href="{{ $href }}" style="font-size:14px; transition:.15s; color:rgba(255,255,255,.78);"
                           onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,.78)'">{{ $label }}</a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Programs column --}}
            <div>
                <h4 style="font-size:13px; letter-spacing:.1em; text-transform:uppercase; color:#fff; margin-bottom:16px; font-family:'Inter',system-ui,sans-serif; font-weight:700;">Programs</h4>
                <ul style="display:flex; flex-direction:column; gap:11px; list-style:none; margin:0; padding:0;">
                    @foreach(['Brigada Ayala','GreenBrigade','Health Caravan','Youth Lead'] as $prog)
                    <li>
                        <a href="#" onclick="event.preventDefault()" style="font-size:14px; transition:.15s; color:rgba(255,255,255,.78);"
                           onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,.78)'">{{ $prog }}</a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- About column --}}
            <div>
                <h4 style="font-size:13px; letter-spacing:.1em; text-transform:uppercase; color:#fff; margin-bottom:16px; font-family:'Inter',system-ui,sans-serif; font-weight:700;">About</h4>
                <ul style="display:flex; flex-direction:column; gap:11px; list-style:none; margin:0; padding:0;">
                    @foreach([
                        ['About VApp', '#'],
                        ['Our impact', '#'],
                        ['Stories', route('stories.view')],
                        ['Contact us', '#'],
                    ] as [$label, $href])
                    <li>
                        <a href="{{ $href }}" style="font-size:14px; transition:.15s; color:rgba(255,255,255,.78);"
                           onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,.78)'">{{ $label }}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px; border-top:1px solid rgba(255,255,255,.12); padding:22px 0 34px;">
            <div style="font-size:13px; display:flex; gap:20px; flex-wrap:wrap; color:rgba(255,255,255,.78);">
                <span>© {{ date('Y') }} VApp. All rights reserved.</span>
                <a href="{{ route('terms-and-conditions') }}" style="text-decoration:underline; color:rgba(255,255,255,.78); transition:.15s;"
                   onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,.78)'">Terms &amp; Conditions</a>
                <a href="{{ route('data-privacy-policy') }}" style="text-decoration:underline; color:rgba(255,255,255,.78); transition:.15s;"
                   onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,.78)'">Data Privacy Policy</a>
            </div>
            <div style="display:flex; align-items:center; gap:9px; font-size:12px; color:rgba(255,255,255,.6);">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/></svg>
                DPO/DPS Registered
            </div>
        </div>
    </div>

    <style>
        @media(max-width:860px){.foot-grid{grid-template-columns:1fr 1fr!important;gap:32px!important;}}
        @media(max-width:520px){.foot-grid{grid-template-columns:1fr!important;}}
    </style>
</footer>
