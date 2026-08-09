<html>

<head>
   @php
      $logoImg = (!empty($appSettings->logo) && file_exists(public_path('uploads/logo/' . $appSettings->logo))) 
         ? asset('uploads/logo/' . $appSettings->logo) 
         : asset('uploads/logo/logo-tpq.png');
   @endphp
   <link rel="icon" type="image/png" href="{{ $logoImg }}">
   <style>
      body {
         font-family: Arial, Helvetica, sans-serif;
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