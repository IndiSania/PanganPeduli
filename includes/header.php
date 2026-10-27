<?php
require_once __DIR__ . '/functions.php';
$pageTitle = $pageTitle ?? 'PanganPeduli';
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#174c3c">
  <meta name="description" content="PanganPeduli menghubungkan makanan berlebih dengan komunitas yang membutuhkan.">
  <title><?= e($pageTitle) ?> | PanganPeduli</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <script defer src="assets/js/app.js"></script>
</head>
<body>
<header class="site-header">
  <div class="container nav-wrap">
    <a class="brand" href="index.php"><span class="brand-mark">P</span><span>Pangan<span class="brand-light">Peduli</span></span></a>
    <button class="menu-toggle" aria-label="Buka navigasi" aria-expanded="false">☰</button>
    <nav class="main-nav" aria-label="Navigasi utama">
      <a href="index.php">Beranda</a>
      <a href="donasi.php">Cari Donasi</a>
      <a href="buat-donasi.php">Donasi Makanan</a>
      <a href="admin/login.php" class="nav-admin">Admin</a>
    </nav>
  </div>
</header>
<main>
<?php if ($flash = get_flash()): ?>
  <div class="container"><div class="alert <?= e($flash['type']) ?>"><?= e($flash['message']) ?></div></div>
<?php endif; ?>
