<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - SlowLiving')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Admin CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    @stack('post')
    @stack('addpost')
    @stack('category')
    @stack('comments')
    @stack('users')
    @stack('editpost')
    @stack('post-preview')
    @stack('adduser')
    @stack('edituser')
    @stack('message')
    @stack('setting')

    <!-- Responsive overrides (must stay last) -->
    <link rel="stylesheet" href="{{ asset('css/responsive-admin.css') }}">

</head>
<body>

    
    <!-- Sidebar overlay (tablet / mobile) -->
    <div class="admin-sidebar-overlay" id="adminSidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <button type="button" class="admin-sidebar-close" id="adminSidebarClose" aria-label="Close menu">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div class="sidebar-header">
            <h1 class="sidebar-brand text-white">Admin<br>Workspace</h1>
            <p class="sidebar-subtitle">Management Portal</p>
        </div>

        <ul class="sidebar-menu">
            <li><a href="{{ url('/dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}"><i class="fa-solid fa-table-columns"></i> Dashboard</a></li>
            <li><a href="{{ url('/posts') }}" class="{{ request()->is('posts') ? 'active' : '' }}"><i class="fa-regular fa-file-lines"></i> Posts</a></li>
            @can('manage-categories')
                <li><a href="{{ url('/categories') }}" class="{{ request()->is('categories') ? 'active' : '' }}"><i class="fa-solid fa-tags"></i> Categories</a></li>
            @endcan
            <li><a href="{{ url('/comments') }}" class="{{ request()->is('comments') ? 'active' : '' }}"> <i class="fa-regular fa-comments"></i> Comments</a></li>
            @can('manage-users')
                <li><a href="{{ url('/users') }}" class="{{ request()->is('users') ? 'active' : '' }}"><i class="fa-solid fa-users"></i> Users</a></li>
            @endcan
            @can('manage-messages')
            <li><a href="{{ url('/messages') }}" class="{{ request()->is('messages') ? 'active' : '' }}"><i class="fa-regular fa-envelope"></i> Messages</a></li>
            @endcan
            @can('manage-settings')
                <li><a href="{{ url('/setting') }}" class="{{ request()->is('setting') ? 'active' : '' }}"><i class="fa-solid fa-gear"></i> Settings</a></li>
            @endcan
        </ul>

        <div class="sidebar-footer">
            <a id="logoutBtn" href=""><i class="fa-solid fa-arrow-right-from-bracket" ></i> Logout</a>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="admin-main">
        <input type="hidden" id="reverbKey" value="{{ config('broadcasting.connections.reverb.key') }}">
        <input type="hidden" id="reverbHost" value="{{ config('broadcasting.connections.reverb.options.host') }}">
        <input type="hidden" id="reverbPort" value="{{ config('broadcasting.connections.reverb.options.port') }}">

        <!-- Top Header -->
        <header class="admin-header">
            <div class="admin-header-left">
                <button type="button" class="admin-menu-toggle" id="adminMenuToggle" aria-label="Open menu" aria-controls="adminSidebar" aria-expanded="false">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h2 class="header-title">@yield('page_title', 'Dashboard')</h2>
            </div>

            <div class="header-right">
                <div class="notification-icon">
                    <i class="fa-regular fa-bell"></i>
                    <a href="{{ url('/messages') }}"><span class="notification-badge p-2">0</span></a>
                </div>
                
               <div class="user-profile">
            @auth
                <div class="user-info">
                    <p class="user-name">{{ auth()->user()->name }}</p>
                    <p class="user-role text-capitalize">{{ auth()->user()->role }}</p>
                </div>
                <div class="user-avatar">
                    @php
                        $nameParts = explode(' ', trim(auth()->user()->name));
                        $initials = '';
                        if (count($nameParts) >= 2) {
                            $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
                        } else {
                            $initials = strtoupper(substr($nameParts[0], 0, 2));
                        }
                    @endphp
                    {{ $initials }}
                </div>
            @endauth
</div>
            </div>
        </header>

        <!-- Dynamic Content -->
        <main class="admin-content">
            @yield('content')
        </main>

    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Chart.js (for graphs) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="{{ asset('js/helper.js') }}"></script>
     <!-- Pusher JS -->
    @can('manage-messages')
        <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>
        <script src="{{ asset('js/message.js') }}"></script>
    @endcan
    <!-- Laravel Echo -->
    <script src="{{ asset('js/auth.js') }}"></script>
    <script>
        // Admin sidebar toggle (tablet / mobile)
        (function () {
            var sidebar = document.getElementById('adminSidebar');
            var overlay = document.getElementById('adminSidebarOverlay');
            var toggle = document.getElementById('adminMenuToggle');
            var closeBtn = document.getElementById('adminSidebarClose');

            function setOpen(isOpen) {
                sidebar.classList.toggle('is-open', isOpen);
                overlay.classList.toggle('is-open', isOpen);
                document.body.classList.toggle('admin-sidebar-open', isOpen);
                toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            }

            toggle.addEventListener('click', function () { setOpen(!sidebar.classList.contains('is-open')); });
            closeBtn.addEventListener('click', function () { setOpen(false); });
            overlay.addEventListener('click', function () { setOpen(false); });
            document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { setOpen(false); } });
            sidebar.querySelectorAll('.sidebar-menu a').forEach(function (link) {
                link.addEventListener('click', function () { setOpen(false); });
            });
            window.addEventListener('resize', function () { if (window.innerWidth >= 992) { setOpen(false); } });
        })();
    </script>
    @stack('scripts')
    @stack('usersjs')
    @stack('postjs')
    @stack('fechpost')
    @stack('settingjs')
    @stack('dashboard')
    @stack('publicpost')
    @stack('messagejs')
    @stack('scriptCategory')

</body>
</html>