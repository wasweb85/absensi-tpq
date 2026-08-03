<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>{{ $title ?? 'Presensi Siswa' }} | E-absensi TPQ</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    
    <!-- Fonts and icons -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Outfit:300,400,500,700|Material+Icons" />
    
    <!-- Icons -->
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('uploads/logo/logo-tpq.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('uploads/logo/logo-tpq.png') }}">
    
    <!-- CSS Files -->
    <link href="{{ asset('assets/css/siswa_modern.css') }}" rel="stylesheet" />
    
    @livewireStyles
    @stack('styles')
</head>

<body>
    
    <div class="modern-container">
        <!-- Standard Header -->
        <header class="modern-header">
            <div class="brand-wrap">
                <img src="{{ asset('uploads/logo/logo-tpq.png') }}" class="brand-logo" alt="Logo" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                <span class="brand-name">E-absensi TPQ</span>
            </div>
            <div class="header-actions">
                <div class="profile-avatar">
                    <i class="material-icons" style="color: #64748b;">person</i>
                </div>
                <a href="{{ route('siswa.logout') }}" class="logout-link-header" title="Logout">
                    <i class="material-icons">logout</i>
                </a>
            </div>
        </header>

        {{ $slot }}
    </div>

    <!-- Bottom Navigation -->
    <div class="bottom-nav">
        <a href="{{ route('siswa.dashboard') }}" class="nav-item {{ ($context ?? '') == 'dashboard' ? 'active' : '' }}">
            <i class="material-icons">home</i>
            <span>Beranda</span>
        </a>
        <a href="{{ route('siswa.jadwal') }}" class="nav-item {{ ($context ?? '') == 'jadwal' ? 'active' : '' }}">
            <i class="material-icons">calendar_today</i>
            <span>Jadwal</span>
        </a>
    </div>

    @livewireScripts
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('scripts')
</body>

</html>
