<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="description" content="Absensi Sekolah QR Code - Sistem absensi modern berbasis QR Code">
   <meta name="theme-color" content="#00ddd9ff">
   <?= csrf_meta(); ?>

   <link rel="apple-touch-icon" sizes="76x76" href="<?= base_url('uploads/logo/logo-tpq.png'); ?>">
   <link rel="icon" type="image/png" href="<?= base_url('uploads/logo/logo-tpq.png'); ?>">

   <!-- Google Fonts: Plus Jakarta Sans -->
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
   
   <style>
       body {
           font-family: 'Plus Jakarta Sans', sans-serif;
       }
       h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
           font-family: 'Plus Jakarta Sans', sans-serif;
           font-weight: 700;
       }
   </style>

   <?= $this->include('templates/css'); ?>

   <title><?= $title ?></title>
</head>
