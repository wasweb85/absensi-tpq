<?php
// 1. Tentukan Warna Sidebar Siswa (Misal: biru/azure)
$sidebarColor = 'azure'; 

// 2. Menu Item Khusus Siswa (Hanya Dashboard & Logout)
$menuItems = [
   [
       'title'   => 'Dashboard',
       'url'     => 'siswa/dashboard',
       'icon'    => 'dashboard',
       'context' => 'dashboard' // Harus sama dengan $ctx di Controller
   ],
   [
       'title'   => 'Logout',
       'url'     => 'siswa/logout',
       'icon'    => 'exit_to_app', // Icon logout
       'context' => 'logout'
   ]
];
?>

<div class="sidebar" data-color="<?= $sidebarColor; ?>" data-image="<?= base_url('assets/img/sidebar/sidebar-1.jpg'); ?>">
   <div class="logo">
      <a href="#" class="simple-text logo-normal">
         <b>Panel Siswa</b>
         <br>
         <small>Halo, <?= session()->get('nama_siswa') ?? 'Siswa'; ?></small>
      </a>
   </div>
   
   <div class="sidebar-wrapper">
      <ul class="nav">
         <?php foreach ($menuItems as $item): ?>
            <li class="nav-item <?= (isset($ctx) && $ctx == $item['context']) ? 'active' : ''; ?>">
               <a class="nav-link" href="<?= base_url($item['url']); ?>">
                  <i class="material-icons"><?= $item['icon']; ?></i>
                  <p><?= $item['title']; ?></p>
               </a>
            </li>
         <?php endforeach; ?>
      </ul>
   </div>
</div>