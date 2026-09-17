<html>

<head>
   @php
      $logoImg = (!empty($appSettings->logo) && file_exists(public_path('uploads/logo/' . $appSettings->logo))) 
         ? asset('uploads/logo/' . $appSettings->logo) 
         : asset('uploads/logo/logo-tpq.png');
   @endphp
   <link rel="icon" type="image/png" href="{{ $logoImg }}">
   <style>
      @page {
         size: A4 landscape;
         margin: 8mm;
      }
      @media print {
         body {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
         }
      }
      body {
         font-family: Arial, Helvetica, sans-serif;
         margin: 0;
         padding: 0;
         color: #1e293b;
      }

      table {
         border-collapse: collapse;
      }
   </style>
</head>


<body>

   @yield('content')

</body>

</html>