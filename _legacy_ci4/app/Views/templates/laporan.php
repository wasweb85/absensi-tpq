<html>

<head>
   <title>Rekap absen <?= $grup ?></title>
   <link rel="icon" type="image/jpeg" href="<?= getLogo(); ?>">
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

   <?= $this->renderSection('content') ?>

</body>

</html>