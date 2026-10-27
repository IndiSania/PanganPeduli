<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
$q = trim((string)($_GET['q'] ?? ''));
$category = trim((string)($_GET['kategori'] ?? ''));
$sql = "SELECT id, title, category, quantity, unit, location, expiry_at, donor_name, description FROM donations WHERE status='Tersedia' AND expiry_at > NOW()";
$params = [];
if ($q !== '') { $sql .= " AND (title LIKE :q OR location LIKE :q OR donor_name LIKE :q)"; $params['q'] = '%' . $q . '%'; }
if ($category !== '') { $sql .= " AND category = :category"; $params['category'] = $category; }
$sql .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql); $stmt->execute($params); $donations = $stmt->fetchAll();
$pageTitle = 'Cari Donasi';
require __DIR__ . '/includes/header.php';
?>
<section class="page-hero"><div class="container"><span class="eyebrow">JELAJAHI KEBAIKAN</span><h1>Temukan donasi di sekitarmu.</h1><p>Jelajahi makanan yang dibagikan dan tinjau informasi pengambilan sebelum menghubungi donatur.</p></div></section>
<section class="section"><div class="container">
  <form class="filter-bar" method="get"><label class="search-field"><span>⌕</span><input type="search" name="q" value="<?= e($q) ?>" placeholder="Cari makanan, donatur, atau lokasi..."></label><select name="kategori" aria-label="Filter kategori"><option value="">Semua kategori</option><?php foreach (['Buah','Sayuran','Roti','Makanan Siap Saji','Bahan Pokok','Lainnya'] as $cat): ?><option value="<?= e($cat) ?>" <?= $category === $cat ? 'selected' : '' ?>><?= e($cat) ?></option><?php endforeach; ?></select><button class="btn btn-primary" type="submit">Cari Donasi</button></form>
  <div class="results-heading"><div><h2>Donasi tersedia</h2><p><?= count($donations) ?> hasil ditemukan</p></div><a class="text-link" href="buat-donasi.php">Bagikan makanan ↗</a></div>
  <?php if ($donations): ?><div class="donation-grid">
  <?php foreach ($donations as $item): ?>
    <article class="donation-card"><div class="food-card-art"><span><?= $item['category'] === 'Sayuran' ? '🥬' : ($item['category'] === 'Roti' ? '🥐' : ($item['category'] === 'Makanan Siap Saji' ? '🍱' : '🍎')) ?></span><span class="status-pill">Tersedia</span></div><div class="donation-body"><span class="category-label"><?= e($item['category']) ?></span><h3><?= e($item['title']) ?></h3><p class="donor-line">Dari <?= e($item['donor_name']) ?></p><p class="card-description"><?= e($item['description']) ?></p><div class="card-meta"><span>📦 <?= (int)$item['quantity'] . ' ' . e($item['unit']) ?></span><span>📍 <?= e($item['location']) ?></span></div><div class="expiry-note">Ambil sebelum <?= date('d M Y, H:i', strtotime($item['expiry_at'])) ?></div><a class="card-link" href="mailto:admin@panganpeduli.local?subject=Informasi%20donasi%20<?= rawurlencode($item['title']) ?>">Tanyakan donasi <span>→</span></a></div></article>
  <?php endforeach; ?></div><?php else: ?><div class="empty-state"><span>🔎</span><h3>Belum ada donasi yang cocok</h3><p>Coba kata kunci atau kategori lain, atau jadilah donatur pertama.</p><a class="btn btn-primary" href="buat-donasi.php">Buat Donasi</a></div><?php endif; ?>
</div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
