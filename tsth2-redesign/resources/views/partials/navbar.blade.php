@php
    $headerStyle = 'background: white; box-shadow: 0 4px 20px rgba(0,0,0,0.05);';
    $textColor = 'color: var(--color-text-dark);';
    $logoColor = 'color: var(--color-primary-dark);';
@endphp

<header class="main-header" id="header" style="{{ $headerStyle }}">
    <div class="container header-container">
        <a href="{{ route('home') }}" class="logo-container" style="gap: 1rem;">
            <img src="{{ asset('assets/logo.png') }}" alt="TSTH2 Logo" style="height: 56px; object-fit: contain;">
            <div style="display: flex; flex-direction: column; justify-content: center;">
                <span class="logo-text" style="{{ $logoColor }}; font-size: 1.4rem; line-height: 1.1; margin-bottom: 2px;">{{ $contents['navbar_title']->value ?? '' }}</span>
                <span style="{{ $logoColor }}; font-size: 0.7rem; font-weight: 600; line-height: 1.2; text-transform: uppercase; letter-spacing: 0.5px;">{!! $contents['navbar_subtitle']->value ?? '' !!}</span>
            </div>
        </a>
        
        <nav class="nav-menu" style="text-transform: uppercase;">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" style="{{ $textColor }}">Home</a>
            <a href="{{ route('program') }}" class="nav-link {{ request()->routeIs('program') ? 'active' : '' }}" style="{{ $textColor }}">Program</a>
            <a href="{{ route('research') }}" class="nav-link {{ request()->routeIs('research') ? 'active' : '' }}" style="{{ $textColor }}">Research</a>
            <a href="{{ route('news') }}" class="nav-link {{ request()->routeIs('news') ? 'active' : '' }}" style="{{ $textColor }}">News</a>
            <a href="{{ route('gallery') }}" class="nav-link {{ request()->routeIs('gallery') ? 'active' : '' }}" style="{{ $textColor }}">Gallery</a>
            <a href="{{ route('katalog') }}" class="nav-link {{ request()->routeIs('katalog') ? 'active' : '' }}" style="{{ $textColor }}">Katalog</a>
        </nav>

        <div class="nav-actions" style="display: flex; gap: 0.5rem; align-items: center;">
            @auth
                <a href="{{ route('dashboard') }}" class="btn" style="background: var(--color-primary); color: white;">Admin Panel</a>
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn btn-accent" style="cursor: pointer;">Log Out</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-accent">Log In</a>
            @endauth
        </div>
    </div>
</header>
