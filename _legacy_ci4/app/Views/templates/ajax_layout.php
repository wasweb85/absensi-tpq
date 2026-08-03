<?php
/**
 * Ajax Layout — Shell-less layout for SPA/Fetch navigation responses.
 * Returns ONLY the page-specific styles, content, and scripts.
 * The outer shell (html/head/body/sidebar/navbar/footer) already lives
 * in the browser from the first full-page load — never duplicated.
 */
?>
<?= $this->renderSection('styles') ?>
<?= $this->renderSection('content') ?>
<?= $this->renderSection('scripts') ?>
