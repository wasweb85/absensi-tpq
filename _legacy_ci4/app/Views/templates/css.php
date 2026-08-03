<?php
// Update this when you make changes to CSS files
$assetVersion = '1.0.2';
?>
<link rel="stylesheet" href="<?= base_url('assets/fonts/fonts.css?v=' . $assetVersion); ?>" />
<link rel="stylesheet" href="<?= base_url('assets/css/material-dashboard.min.css?v=' . $assetVersion); ?>" />
<link rel="stylesheet" href="<?= base_url('assets/css/style.min.css?v=' . $assetVersion); ?>" />
<link rel="stylesheet" href="<?= base_url('assets/css/sidebar.css?v=' . $assetVersion); ?>" />
<link rel="stylesheet" href="<?= base_url('assets/js/plugins/file-uploader/css/jquery.dm-uploader.min.css?v=' . $assetVersion); ?>" />
<link rel="stylesheet" href="<?= base_url('assets/js/plugins/file-uploader/css/styles-1.0.css?v=' . $assetVersion); ?>" />

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
</style>

<?= $this->renderSection("styles") ?>