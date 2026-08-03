<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Absensi Sekolah QR Code - Sistem absensi modern berbasis QR Code">
    <meta name="theme-color" content="#9c27b0">

    <title>{{ $title ?? 'E-absensi TPQ' }}</title>
    
    <link rel="icon" type="image/png" href="{{ asset('uploads/logo/logo-tpq.png') }}">
    
    <!-- CSS Dependencies from legacy -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link href="{{ asset('assets/css/material-dashboard.min.css') }}" rel="stylesheet" />

    <style>
        .bg {
            background: url("{{ asset('assets/img/city-profile.jpg') }}") center;
            opacity: 0.4;
            background-size: cover;
            height: 100vh;
            width: 100%;
            position: fixed;
            bottom: 0;
            top: 0;
            z-index: -1;
        }

        .main-panel {
            position: relative;
            float: left;
            width: 100%;
            transition: 0.33s, cubic-bezier(0.685, 0.0473, 0.346, 1);
            min-height: 100vh;
            padding-top: 80px;
        }

        video#previewKamera {
            width: 100%;
            height: auto;
            max-height: 400px;
            margin: 0;
            background: #000;
        }

        .previewParent {
            width: 100%;
            height: auto;
            margin: auto;
            border: 2px solid grey;
            position: relative;
        }

        .form-select {
            min-width: 200px;
        }
    </style>
    @livewireStyles
</head>
<body>
    <div class="bg bg-image"></div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-absolute fixed-top">
        <div class="container-fluid">
            <div class="navbar-wrapper row w-100 mx-0">
                <div class="col-md-6 d-flex justify-content-center justify-content-md-start">
                    <p class="navbar-brand my-auto text-center mx-0"><b>{{ $title ?? 'Scan Absensi' }}</b></p>
                </div>
                <div class="col-md-6 d-flex justify-content-center justify-content-md-end">
                    @php
                        $dashUrl = url('/');
                        if (auth()->check()) {
                            $dashUrl = !empty(auth()->user()->id_guru) ? url('/teacher/dashboard') : url('/dashboard');
                        }
                     @endphp
                     <a href="{{ $dashUrl }}" class="btn btn-primary pull-right pl-3 mr-2">
                        <i class="material-icons mr-2">dashboard</i>
                        Dashboard
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <!-- End Navbar -->

    {{ $slot }}

    <!-- JS Scripts -->
    <script src="{{ asset('assets/js/core/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap-material-design.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/zxing/zxing.min.js') }}"></script>
    
    @livewireScripts
</body>
</html>
