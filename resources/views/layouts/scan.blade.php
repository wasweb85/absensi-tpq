<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Absensi Sekolah QR Code - Sistem absensi modern berbasis QR Code">
    <meta name="theme-color" content="#0284c7">

    <title>{{ $title ?? 'Scan QR Code Absensi' }}</title>
    
    <link rel="icon" type="image/png" href="{{ asset('uploads/logo/logo-tpq.png') }}">
    
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:400,500,600,700,800|Material+Icons" />
    <link href="{{ asset('assets/css/material-dashboard.min.css') }}" rel="stylesheet" />

    <!-- JS Scripts in Head for immediate scanner availability -->
    <script src="{{ asset('assets/js/core/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap-material-design.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/zxing/zxing.min.js') }}"></script>

    <style>
        body {
            background-color: #f8fafc !important;
            font-family: 'Inter', system-ui, -apple-system, sans-serif !important;
            margin: 0;
            padding: 0;
        }
    </style>
    @livewireStyles
</head>
<body>

    {{ $slot }}

    @livewireScripts
</body>
</html>
