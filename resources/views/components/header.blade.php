@push('global')
    <script src="{{ asset('js/global.js') }}"></script>
@endpush
<header class="site-header">
    <div class="site-header__inner">
        <a href="{{ url('/') }}" class="site-header__logo">
            <img src="" alt="Logo" id="dynamicSiteLogo" style="display:none; max-height: 40px; object-fit: contain;">
            <span id="dynamicSiteTitle">SlowLiving</span>
        </a>

        <button type="button" class="site-header__toggle" id="navToggle" aria-label="Toggle navigation" aria-expanded="false" aria-controls="siteNav">
            <span class="site-header__bar"></span>
            <span class="site-header__bar"></span>
            <span class="site-header__bar"></span>
        </button>

        <nav class="site-header__nav" id="siteNav">
            <ul class="site-header__menu">
                <li><a href="{{ url('/') }}" class="site-header__link {{ request()->is('/') ? 'active' : '' }}">Home</a></li>
                <li><a href="{{ url('/about') }}" class="site-header__link {{ request()->is('about') ? 'active' : '' }}">About</a></li>
                <li><a href="{{ url('/contact') }}" class="site-header__link {{ request()->is('contact') ? 'active' : '' }}">Contact</a></li>
            </ul>
            @if (auth()->check())
                @php
                    $nameParts = explode(' ', trim(auth()->user()->name));
                    $initials = '';
                    if (count($nameParts) >= 2) {
                        $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
                    } else {
                        $initials = strtoupper(substr($nameParts[0], 0, 2));
                    }
                @endphp

                <!-- User Profile Dropdown -->
                <div class="user-profile-wrapper">
                    
                    <!-- Avatar Circle -->
                    <div class="user-avatar-circle" id="avatarToggle">
                        @if(auth()->user()->image)
                            <!-- Agar user ki image set hai -->
                            <img src="{{ asset('uploads/' . auth()->user()->image) }}" alt="{{ auth()->user()->name }}">
                        @else
                            <!-- Agar image nahi hai toh Initials dikhao -->
                            <span class="avatar-initials">{{ $initials }}</span>
                        @endif
                    </div>

                        <!-- Dropdown Menu -->
                    <div class="user-dropdown" id="userDropdown">
                        <div class="dropdown-header">
                            <span class="dropdown-name">{{ auth()->user()->name }}</span>
                            <span class="dropdown-email">{{ auth()->user()->email }}</span>
                        </div>
                        <hr class="dropdown-divider">
                        
                        <!-- Logout Button -->
                        <button class="logout dropdown-logout-btn" id="logoutBtn">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                        </button>
                    </div>
                </div>
                        
            @else
            <div class="site-header__auth">
                <a href="{{ url('/login') }}" class="site-header__login">Login</a>
                <a href="{{ url('/register') }}" class="site-header__register">Register</a>
            </div>
            @endif
        </nav>
    </div>
</header>
@push('header')
    <script src="{{ asset('js/auth.js') }}"></script>
@endpush