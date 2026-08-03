<!DOCTYPE html>
<html lang="id">

<?= $this->include("templates/head") ?>

<body>
   <!-- Mobile sidebar overlay backdrop -->
   <div id="sidebarOverlay"></div>

   <div class="app-wrapper">
   
      <?= $this->include("templates/sidebar_siswa") ?>
      
      <div class="main-panel">
      
         <?= $this->include("templates/navbar_siswa") ?>
         
         <div class="content"> 
            <?= $this->renderSection("content") ?>
         </div>

         <?= $this->include("templates/footer") ?>
      </div>
      
   </div> 

   <?= $this->include("templates/js") ?>

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