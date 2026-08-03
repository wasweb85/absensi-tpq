<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title><?= $title ?? 'Presensi Siswa' ?> | E-absensi TPQ</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    
    <!-- Fonts and icons -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Outfit:300,400,500,700|Material+Icons" />
    
    <!-- Icons -->
    <link rel="apple-touch-icon" sizes="76x76" href="<?= base_url('uploads/logo/logo-tpq.png'); ?>">
    <link rel="icon" type="image/png" href="<?= base_url('uploads/logo/logo-tpq.png'); ?>">
    
    <!-- CSS Files -->
    <link href="<?= base_url('assets/css/siswa_modern.css') ?>" rel="stylesheet" />
    
    <?= $this->renderSection("styles") ?>
</head>

<body>
    
    <div class="modern-container">
        <!-- Standard Header -->
        <header class="modern-header">
            <div class="brand-wrap">
                <img src="<?= base_url('uploads/logo/logo-tpq.png') ?>" class="brand-logo" alt="Logo" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                <span class="brand-name">E-absensi TPQ</span>
            </div>
            <div class="header-actions">
                <div class="profile-avatar">
                    <i class="material-icons" style="color: #64748b;">person</i>
                </div>
                <a href="<?= base_url('siswa/logout') ?>" class="logout-link-header" title="Logout">
                    <i class="material-icons">logout</i>
                </a>
            </div>
        </header>

        <?= $this->renderSection("content") ?>
    </div>

    <!-- Bottom Navigation -->
    <div class="bottom-nav">
        <a href="<?= base_url('siswa/dashboard') ?>" class="nav-item <?= ($ctx ?? '') == 'dashboard' ? 'active' : '' ?>">
            <i class="material-icons">home</i>
            <span>Beranda</span>
        </a>
        <a href="<?= base_url('siswa/jadwal') ?>" class="nav-item <?= ($ctx ?? '') == 'jadwal' ? 'active' : '' ?>">
            <i class="material-icons">calendar_today</i>
            <span>Jadwal</span>
        </a>
        <a href="<?= base_url('siswa/riwayat') ?>" class="nav-item <?= ($ctx ?? '') == 'riwayat' ? 'active' : '' ?>">
            <i class="material-icons">history</i>
            <span>Riwayat</span>
        </a>
        <a href="<?= base_url('siswa/profil') ?>" class="nav-item <?= ($ctx ?? '') == 'profil' ? 'active' : '' ?>">
            <i class="material-icons">person</i>
            <span>Profil</span>
        </a>
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
