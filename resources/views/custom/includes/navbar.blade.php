<style>
    :root {
        --nav-blue: #0e4f99;
        --nav-blue-hover: #0a3a6e;
        --nav-orange: #f55e1d;
        --nav-orange-hover: #d44e14;
    }

    #navBar {
        padding: 0 28px;
        height: 68px;
        background: rgba(255,255,255,.92);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        position: sticky;
        top: 0;
        z-index: 50;
        border-bottom: 1px solid transparent;
        box-shadow: none;
        transition: border-color .2s, box-shadow .2s;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    #navBar.scrolled {
        border-color: #e6e8ec;
        box-shadow: 0 1px 2px rgba(16,32,56,.06), 0 1px 3px rgba(16,32,56,.05);
    }

    .nav-link {
        padding: 9px 14px;
        font-size: 14.5px;
        font-weight: 600;
        border-radius: 8px;
        color: #454c58;
        transition: .15s;
        text-decoration: none;
        background: transparent;
    }

    .nav-link:hover { color: var(--nav-blue); background: #eef4fc; }
    .nav-link.active { color: var(--nav-blue); background: #eef4fc; }

    .btn-nav-login {
        font-size: 14.5px;
        font-weight: 700;
        color: var(--nav-blue);
        padding: 9px 12px;
        background: none;
        border: none;
        cursor: pointer;
        text-decoration: none;
        transition: .15s;
    }
    .btn-nav-login:hover { color: var(--nav-blue-hover); }

    .btn-nav-primary {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: var(--nav-orange);
        color: #fff;
        font-weight: 700;
        font-size: 14px;
        padding: 10px 18px;
        border-radius: 999px;
        box-shadow: 0 4px 12px rgba(245,94,29,.28);
        transition: .15s;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }
    .btn-nav-primary:hover { background: var(--nav-orange-hover); transform: translateY(-1px); }

    .btn-nav-dashboard {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: var(--nav-blue);
        color: #fff;
        font-weight: 700;
        font-size: 14px;
        padding: 10px 18px;
        border-radius: 999px;
        transition: .15s;
        text-decoration: none;
    }
    .btn-nav-dashboard:hover { background: var(--nav-blue-hover); transform: translateY(-1px); }

    /* Mobile */
    .hdr-burger { display: none; padding: 8px; color: #15181d; cursor: pointer; background: none; border: none; }
    .hdr-nav { display: flex; align-items: center; gap: 4px; }
    .hdr-actions { display: flex; align-items: center; gap: 10px; }

    .mobile-menu {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0;
        min-height: 60vh;
        background: #fff;
        z-index: 90;
        padding: 20px;
        border-bottom: 1px solid #e6e8ec;
        box-shadow: 0 18px 48px rgba(16,32,56,.14);
        animation: slideDown .25s ease;
    }
    .mobile-menu.active { display: block; }

    @keyframes slideDown {
        from { opacity:0; transform:translateY(-10px); }
        to   { opacity:1; transform:none; }
    }

    @media(max-width:900px) {
        .hdr-nav { display: none !important; }
        .hdr-actions { display: none !important; }
        .hdr-burger { display: flex !important; }
    }
</style>

<div id="navBar">
    {{-- Logo --}}
    <a href="{{ route('main.homepage.view') }}" style="display:flex; align-items:center; gap:10px; text-decoration:none;" aria-label="VApp home">
        <img src="{{ asset('img/logo-vapp.svg') }}" alt="VApp" style="height:32px; width:auto;">
    </a>

    {{-- Desktop nav --}}
    <nav class="hdr-nav">
        <a href="{{ route('main.homepage.view') }}" class="nav-link {{ request()->routeIs('main.homepage.view') ? 'active' : '' }}">Home</a>
        <a href="{{ url('/admin/events') }}" class="nav-link">Opportunities</a>
        <a href="{{ route('stories.view') }}" class="nav-link {{ request()->routeIs('stories.view') ? 'active' : '' }}">Stories</a>
        <a href="{{ route('ourpartners.view') }}" class="nav-link {{ request()->routeIs('ourpartners.view') ? 'active' : '' }}">Partners</a>
    </nav>

    {{-- Desktop actions --}}
    <div class="hdr-actions">
        @guest
            <a href="{{ route('filament.admin.auth.login') }}" class="btn-nav-login">Log in</a>
            <a href="{{ route('volunteer.form.view') }}" class="btn-nav-primary">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M18 11V6a2 2 0 0 0-4 0v5M14 10V4a2 2 0 0 0-4 0v7M10 10.5V6a2 2 0 0 0-4 0v8a8 8 0 0 0 8 8h0a8 8 0 0 0 8-8v-3a2 2 0 0 0-4 0"/></svg>
                Become a Volunteer
            </a>
        @endguest
        @auth
            <a href="{{ route('filament.admin.pages.dashboard') }}" class="btn-nav-dashboard">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
        @endauth
    </div>

    {{-- Hamburger --}}
    <button class="hdr-burger" id="hamburgerBtn" aria-label="Open menu">
        <svg id="iconMenu" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
        <svg id="iconClose" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" style="display:none;"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
</div>

{{-- Mobile menu --}}
<div class="mobile-menu" id="mobileMenu">
    <div style="display:flex; justify-content:flex-end; margin-bottom:12px;">
        <button id="closeMenuBtn" style="padding:8px; background:none; border:none; cursor:pointer; color:#454c58;">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12"/></svg>
        </button>
    </div>
    <div style="display:flex; flex-direction:column; gap:4px; margin-bottom:20px;">
        <a href="{{ route('main.homepage.view') }}" style="display:block; width:100%; text-align:left; padding:13px 8px; font-size:16px; font-weight:600; color:#15181d; border-bottom:1px solid #eef0f3; text-decoration:none;">Home</a>
        <a href="{{ url('/admin/events') }}" style="display:block; width:100%; text-align:left; padding:13px 8px; font-size:16px; font-weight:600; color:#15181d; border-bottom:1px solid #eef0f3; text-decoration:none;">Opportunities</a>
        <a href="{{ route('stories.view') }}" style="display:block; width:100%; text-align:left; padding:13px 8px; font-size:16px; font-weight:600; color:#15181d; border-bottom:1px solid #eef0f3; text-decoration:none;">Stories</a>
        <a href="{{ route('ourpartners.view') }}" style="display:block; width:100%; text-align:left; padding:13px 8px; font-size:16px; font-weight:600; color:#15181d; text-decoration:none;">Partners</a>
    </div>
    <div style="display:flex; flex-direction:column; gap:10px;">
        @guest
            <a href="{{ route('filament.admin.auth.login') }}" style="display:flex; align-items:center; justify-content:center; gap:8px; padding:13px 20px; border-radius:999px; border:1.5px solid #e6e8ec; font-weight:700; font-size:15px; color:#454c58; text-decoration:none;">Log in</a>
            <a href="{{ route('volunteer.form.view') }}" class="btn-nav-primary" style="justify-content:center; padding:13px 20px; font-size:15px;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M18 11V6a2 2 0 0 0-4 0v5M14 10V4a2 2 0 0 0-4 0v7M10 10.5V6a2 2 0 0 0-4 0v8a8 8 0 0 0 8 8h0a8 8 0 0 0 8-8v-3a2 2 0 0 0-4 0"/></svg>
                Become a Volunteer
            </a>
        @endguest
        @auth
            <a href="{{ route('filament.admin.pages.dashboard') }}" class="btn-nav-dashboard" style="justify-content:center; padding:13px 20px; font-size:15px;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
        @endauth
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const closeMenuBtn = document.getElementById('closeMenuBtn');
    const mobileMenu   = document.getElementById('mobileMenu');
    const iconMenu     = document.getElementById('iconMenu');
    const iconClose    = document.getElementById('iconClose');
    const navBar       = document.getElementById('navBar');

    function openMenu() {
        mobileMenu.classList.add('active');
        iconMenu.style.display  = 'none';
        iconClose.style.display = 'block';
    }
    function closeMenu() {
        mobileMenu.classList.remove('active');
        iconMenu.style.display  = 'block';
        iconClose.style.display = 'none';
    }

    hamburgerBtn.addEventListener('click', openMenu);
    closeMenuBtn.addEventListener('click', closeMenu);

    window.addEventListener('scroll', function () {
        if (window.scrollY > 8) navBar.classList.add('scrolled');
        else navBar.classList.remove('scrolled');
    });
});
</script>
