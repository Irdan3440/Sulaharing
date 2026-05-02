<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'Dashboard SulaHaring — Pantau Kesehatan Mental Anda')">
    <title>@yield('title', 'Dashboard') — SulaHaring</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/lucide@latest"></script>

    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @stack('styles')

    <style>body { opacity: 0; animation: fadeIn 0.3s ease-out 0.1s forwards; }</style>
</head>
<body>
    <div class="dashboard-layout">
        <!-- Sidebar Overlay (Mobile) -->
        <div class="mobile-nav-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="{{ url('/') }}" class="sidebar-logo">
                    <img src="{{ asset('images/logo-sulaharing.png') }}" alt="SulaHaring" style="width:36px;height:36px;object-fit:contain">
                </a>
                <div class="sidebar-brand">Sula<span>Haring</span></div>
            </div>

            <nav class="sidebar-nav">
                @yield('sidebar')
            </nav>

            <div class="sidebar-footer">
                <div class="sidebar-user">
                    <div class="sidebar-avatar">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="sidebar-user-info">
                        <div class="sidebar-user-name">{{ auth()->user()->name ?? 'User' }}</div>
                        <div class="sidebar-user-role">{{ ucfirst(auth()->user()->role ?? 'mahasiswa') }}</div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Bar -->
            <header class="topbar">
                <div class="topbar-left">
                    <button class="sidebar-toggle" onclick="toggleSidebar()" aria-label="Toggle sidebar">
                        <i data-lucide="menu" style="width:22px;height:22px"></i>
                    </button>
                    <div class="topbar-title">
                        <h4>@yield('page_title', 'Dashboard')</h4>
                        <p>@yield('page_subtitle', '')</p>
                    </div>
                </div>
                <div class="topbar-right">
                    <a href="{{ route('notifications') ?? '#' }}" class="notification-btn" aria-label="Notifications">
                        <i data-lucide="bell" style="width:20px;height:20px"></i>
                        <span class="notification-dot" style="display:none"></span>
                    </a>
                    <form method="POST" action="{{ route('logout') ?? '#' }}" style="display:inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-secondary">
                            <i data-lucide="log-out" style="width:16px;height:16px"></i>
                            Keluar
                        </button>
                    </form>
                </div>
            </header>

            <!-- Page Content -->
            <div class="dashboard-content">
                @if(session('success'))
                    <div class="alert alert-success animate-fade-in-down">
                        <i data-lucide="check-circle" style="width:20px;height:20px;flex-shrink:0"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger animate-fade-in-down">
                        <i data-lucide="alert-circle" style="width:20px;height:20px;flex-shrink:0"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('open');
        }
    </script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
