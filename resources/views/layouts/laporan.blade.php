<html>

<head>
   <title>Rekap absen {{ $grup ?? '' }}</title>
   <link rel="icon" type="image/jpeg" href="{{ asset('uploads/logo/logo-tpq.png') }}">
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