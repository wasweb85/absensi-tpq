<!DOCTYPE html>
<html lang="id">

<?= $this->include("templates/head") ?>

<body>
   <!-- Mobile sidebar overlay backdrop -->
   <div id="sidebarOverlay"></div>

   <div class="app-wrapper">
      <?= $this->include("templates/sidebar") ?>
      <div class="main-panel">

         <?= $this->include("templates/navbar") ?>

         <!-- SPA Loading Overlay -->
         <div id="spa-loading">
            <div class="spa-loading-card">
               <div class="spa-spinner-ring"></div>
            </div>
         </div>

         <!-- SPA Content Area (injected by spa-nav.js on AJAX nav) -->
         <div id="spa-content">
            <?= $this->renderSection("content") ?>
         </div>

         <?= $this->include("templates/footer") ?>

         <!-- komentar jika tidak dipakai -->
         <?php
         // echo $this->include('templates/fixed_plugin')
         ?>

      </div>
   </div>

   <?= $this->include("templates/js") ?>
   <!-- SPA Navigation Engine -->
   <script src="<?= base_url('assets/js/spa-nav.js?v=1.0.0') ?>"></script>

   <script>
      var BaseConfig = {
         baseURL: '<?= base_url() ?>',
         csrfTokenName: '<?= csrf_token() ?>',
         textOk: "Ok",
         textCancel: "Batalkan"
      };
   </script>

    <?= $this->renderSection("scripts") ?>
</body>

</html>
