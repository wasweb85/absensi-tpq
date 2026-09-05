<!DOCTYPE html>
<html lang="id">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="description" content="Absensi Sekolah QR Code - Sistem absensi modern berbasis QR Code">
   <meta name="theme-color" content="#00ddd9ff">
   <meta name="csrf-token" content="{{ csrf_token() }}">

   @php
      $logoUrl = (!empty($appSettings->logo) && file_exists(public_path('uploads/logo/' . $appSettings->logo))) 
         ? asset('uploads/logo/' . $appSettings->logo) 
         : asset('uploads/logo/logo-tpq.png');
   @endphp
   <link rel="apple-touch-icon" sizes="76x76" href="{{ $logoUrl }}">
   <link rel="icon" type="image/png" href="{{ $logoUrl }}">

   <!-- Google Fonts: Plus Jakarta Sans -->
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
   
   <style>
       body {
           font-family: 'Plus Jakarta Sans', sans-serif;
       }
       h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
           font-family: 'Plus Jakarta Sans', sans-serif;
           font-weight: 700;
       }
   </style>

   @php $assetVersion = '1.0.2'; @endphp
   <link rel="stylesheet" href="{{ asset('assets/fonts/fonts.css?v=' . $assetVersion) }}" />
   <link rel="stylesheet" href="{{ asset('assets/css/material-dashboard.min.css?v=' . $assetVersion) }}" />
   <link rel="stylesheet" href="{{ asset('assets/css/style.min.css?v=' . $assetVersion) }}" />
   <link rel="stylesheet" href="{{ asset('assets/css/sidebar.css?v=' . $assetVersion) }}" />
   <link rel="stylesheet" href="{{ asset('assets/css/teacher-theme.css?v=' . $assetVersion) }}" />
   <link rel="stylesheet" href="{{ asset('assets/js/plugins/file-uploader/css/jquery.dm-uploader.min.css?v=' . $assetVersion) }}" />
   <link rel="stylesheet" href="{{ asset('assets/js/plugins/file-uploader/css/styles-1.0.css?v=' . $assetVersion) }}" />

   <style>
   /* Override Material Dashboard Card Header to be flat */
   .card [class*="card-header-"]:not(.card-header-icon):not(.card-header-text) {
       margin-top: 0 !important;
       box-shadow: none !important;
       background: transparent !important;
       padding-bottom: 0 !important;
       padding-left: 1.25rem !important;
       padding-right: 1.25rem !important;
   }
   .card [class*="card-header-"]:not(.card-header-icon):not(.card-header-text) .card-title {
       margin-top: 0 !important;
       font-weight: 600;
   }
   .card .card-header-primary .card-title {
       color: #9c27b0 !important;
   }
   .card .card-header-info .card-title {
       color: #00bcd4 !important;
   }
   .card .card-header-success .card-title {
       color: #4caf50 !important;
   }
   .card .card-header-warning .card-title {
       color: #ff9800 !important;
   }
   .card .card-header-danger .card-title {
       color: #f44336 !important;
   }
   .card .card-header-rose .card-title {
       color: #e91e63 !important;
   }
   .card [class*="card-header-"] .card-category {
       color: #888 !important;
   }
   /* Tabs override */
   .card-header-tabs .nav-tabs {
       background: transparent !important;
   }
   .card-header-tabs .nav-tabs .nav-item .nav-link, 
   .card-header-tabs .nav-tabs .nav-item .nav-link .material-icons {
       color: #777 !important;
   }
   .card-header-tabs .nav-tabs .nav-item .nav-link.active,
   .card-header-tabs .nav-tabs .nav-item .nav-link.active .material-icons {
       color: #fff !important;
   }
   .card.card-nav-tabs .card-header-primary .nav-tabs .nav-link.active {
       background-color: #9c27b0 !important;
       border-radius: 3px;
       box-shadow: 0 4px 20px 0px rgba(0, 0, 0, 0.14), 0 7px 10px -5px rgba(156, 39, 176, 0.4);
   }
   .card.card-nav-tabs .card-header-info .nav-tabs .nav-link.active {
       background-color: #00bcd4 !important;
       border-radius: 3px;
       box-shadow: 0 4px 20px 0px rgba(0, 0, 0, 0.14), 0 7px 10px -5px rgba(0, 188, 212, 0.4);
   }
   /* Livewire Navigation Progress & Smooth Transition */
   .livewire-progress-bar {
       background: linear-gradient(90deg, #2563eb, #3b82f6) !important;
       height: 3px !important;
       box-shadow: 0 1px 3px rgba(37, 99, 235, 0.4);
   }
   #spa-content {
       transition: opacity 0.15s ease-in-out;
   }
    body.page-navigating #spa-content {
        opacity: 0.5;
        pointer-events: none;
    }
    
    /* GLOBAL UI CONSISTENCY FIX */
    /* Menyamakan jarak (padding) halaman Admin dengan halaman Guru (24px / 1.5rem) */
    @media (min-width: 768px) {
        .main-panel > #spa-content > div > .content,
        .main-panel > #spa-content > .content {
            padding: 1.5rem !important; 
        }
        .main-panel > #spa-content .content .card {
            margin-top: 0 !important;
        }
        .main-panel > #spa-content > div > .content .container-fluid,
        .main-panel > #spa-content > .content .container-fluid {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
    }
   </style>

   @yield('styles')
   @livewireStyles
   @vite(['resources/css/app.css', 'resources/js/app.js'])

   <title>{{ $title ?? ($appSettings->school_name ?? 'Absensi Digital') }}</title>
</head>

<body>
   <!-- Mobile sidebar overlay backdrop -->
   <div id="sidebarOverlay"></div>

   <div class="app-wrapper">
      @include('layouts.partials.sidebar')
      
      <div class="main-panel">
         @include('layouts.partials.navbar')


         <!-- SPA Content Area (injected by spa-nav.js on AJAX nav) -->
         <div id="spa-content" style="padding-top: 65px;">
            @yield('content')
            {{ $slot ?? '' }}
         </div>

         @include('layouts.partials.footer')
      </div>
   </div>

   @php $assetVersion = '1.0.0'; @endphp
   <!--   Core JS Files   -->
   <script data-navigate-once src="{{ asset('assets/js/core/jquery-3.5.1.min.js?v=' . $assetVersion) }}"></script>
   <script data-navigate-once src="{{ asset('assets/js/plugins.js?v=' . $assetVersion) }}" type="text/javascript"></script>
   <script data-navigate-once src="{{ asset('assets/js/core/bootstrap.bundle.min.js?v=' . $assetVersion) }}"></script>
   <script data-navigate-once src="{{ asset('assets/js/core/popper.min.js?v=' . $assetVersion) }}"></script>
   <script data-navigate-once src="{{ asset('assets/js/core/bootstrap-material-design.min.js?v=' . $assetVersion) }}"></script>
   <script data-navigate-once src="{{ asset('assets/js/plugins/perfect-scrollbar.jquery.min.js?v=' . $assetVersion) }}"></script>
   <script data-navigate-once src="{{ asset('assets/js/plugins/nouislider.min.js?v=' . $assetVersion) }}"></script>
   <script data-navigate-once src="{{ asset('assets/js/material-dashboard.js?v=' . $assetVersion) }}" type="text/javascript"></script>
   <script data-navigate-once src="{{ asset('assets/js/plugins/file-uploader/js/jquery.dm-uploader.min.js?v=' . $assetVersion) }}"></script>
   <script data-navigate-once src="{{ asset('assets/js/plugins/file-uploader/js/ui.js?v=' . $assetVersion) }}"></script>
   <script data-navigate-once src="{{ asset('assets/js/custom.js?v=' . $assetVersion) }}" type="text/javascript"></script>

   <script data-navigate-once>
      var BaseConfig = {
         baseURL: '{{ url('/') }}',
         csrfTokenName: '{{ csrf_token() }}',
         textOk: "Ok",
         textCancel: "Batalkan"
      };

      document.addEventListener('livewire:navigating', function () {
         document.body.classList.add('page-navigating');
      });
      document.addEventListener('livewire:navigated', function () {
         document.body.classList.remove('page-navigating');
         window.scrollTo({ top: 0, behavior: 'instant' });
      });
   </script>

   @yield('scripts')
   @livewireScripts
</body>
</html>
