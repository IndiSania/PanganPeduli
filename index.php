<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$stats = ['available' => 0, 'completed' => 0, 'partners' => 0];
try {
    $stats['available'] = (int)$pdo->query("SELECT COUNT(*) FROM donations WHERE status = 'Tersedia' AND expiry_at > NOW()")->fetchColumn();
    $stats['completed'] = (int)$pdo->query("SELECT COUNT(*) FROM donations WHERE status = 'Selesai'")->fetchColumn();
    $stats['partners'] = (int)$pdo->query("SELECT COUNT(DISTINCT donor_name) FROM donations")->fetchColumn();
    $latest = $pdo->query("SELECT id, title, category, quantity, unit, location, expiry_at, donor_name FROM donations WHERE status='Tersedia' AND expiry_at > NOW() ORDER BY created_at DESC LIMIT 3")->fetchAll();
} catch (PDOException $e) { $latest = []; }
$pageTitle = 'Beranda';
require __DIR__ . '/includes/header.php';
?>
<section class="hero">
  <div class="container hero-grid">
    <div class="hero-copy">
      <span class="eyebrow"><span class="dot"></span> Berbagi lebih bermakna</span>
      <h1>Makanan berlebih,<br><span>harapan yang bertambah.</span></h1>
      <p class="hero-lead">PanganPeduli mempertemukan donatur makanan dengan komunitas dan lembaga sosial. Kurangi makanan terbuang, hadirkan manfaat lebih luas.</p>
      <div class="hero-actions"><a class="btn btn-primary" href="buat-donasi.php">Mulai Berdonasi <span>↗</span></a><a class="btn btn-ghost" href="donasi.php">Jelajahi Donasi</a></div>
      <div class="hero-trust"><div class="avatar-stack"><span>🍞</span><span>🥬</span><span>🍱</span></div><span><strong>Berawal dari kepedulian</strong><br><small>Setiap kontribusi punya arti.</small></span></div>
    </div>
    <div class="hero-art">
      <div class="art-orbit orbit-one"></div><div class="art-orbit orbit-two"></div>
      <div class="food-illustration"><span class="food-emoji">🥗</span><span class="leaf leaf-one">✦</span><span class="leaf leaf-two">✦</span></div>
      <div class="floating-card floating-top"><span class="float-icon">♡</span><div><strong>Bagikan kebaikan</strong><small>Mulai dari sekitar kita</small></div></div>
      <div class="floating-card floating-bottom"><span class="float-icon green">↗</span><div><strong><?= number_format($stats['available']) ?> donasi tersedia</strong><small>Siap ditinjau komunitas</small></div></div>
      <span class="art-caption">GOOD FOOD. GOOD PURPOSE.</span>
    </div>
  </div>
</section>
<section class="stats-strip"><div class="container stats-grid">
  <div><span class="stat-number"><?= number_format($stats['available']) ?></span><span class="stat-label">Donasi tersedia</span></div>
  <div><span class="stat-number"><?= number_format($stats['completed']) ?></span><span class="stat-label">Distribusi selesai</span></div>
  <div><span class="stat-number"><?= number_format($stats['partners']) ?></span><span class="stat-label">Donatur terdaftar</span></div>
  <div class="stats-note"><span class="tiny-spark">✳</span> Kebaikan tumbuh saat dibagikan.</div>
</div></section>
<section class="section"><div class="container">
  <div class="section-heading"><div><span class="eyebrow">CARA KERJA</span><h2>Langkah kecil, dampak nyata.</h2><p>Alur sederhana untuk mengubah makanan berlebih menjadi manfaat.</p></div></div>
  <div class="steps-grid">
    <article class="step-card"><span class="step-num">01</span><div class="step-icon">🥘</div><h3>Bagikan makanan</h3><p>Donatur mengisi informasi makanan, jumlah, lokasi, dan batas waktu pengambilan.</p></article>
    <article class="step-card"><span class="step-num">02</span><div class="step-icon">🤝</div><h3>Hubungkan kebutuhan</h3><p>Komunitas meninjau donasi yang tersedia dan menghubungi pihak terkait.</p></article>
    <article class="step-card"><span class="step-num">03</span><div class="step-icon">🌱</div><h3>Distribusikan dengan aman</h3><p>Pengambilan dan distribusi dilakukan dengan memperhatikan keamanan pangan.</p></article>
  </div>
</div></section>
<section class="section section-tint"><div class="container">
  <div class="section-heading section-heading-row"><div><span class="eyebrow">DARI KOMUNITAS</span><h2>Donasi yang tersedia</h2><p>Lihat makanan yang dibagikan oleh para donatur.</p></div><a class="text-link" href="donasi.php">Lihat semua <span>↗</span></a></div>
  <div class="donation-grid">
  <?php if ($latest): foreach ($latest as $item): ?>
    <article class="donation-card"><div class="food-card-art <?= e(strtolower(str_replace(' ', '-', $item['category']))) ?>"><span><?= $item['category'] === 'Sayuran' ? '🥬' : ($item['category'] === 'Roti' ? '🥐' : ($item['category'] === 'Makanan Siap Saji' ? '🍱' : '🍎')) ?></span><span class="status-pill">Tersedia</span></div><div class="donation-body"><span class="category-label"><?= e($item['category']) ?></span><h3><?= e($item['title']) ?></h3><p class="donor-line">Dari <?= e($item['donor_name']) ?></p><div class="card-meta"><span>📦 <?= (int)$item['quantity'] . ' ' . e($item['unit']) ?></span><span>📍 <?= e($item['location']) ?></span></div><a class="card-link" href="donasi.php">Lihat detail <span>→</span></a></div></article>
  <?php endforeach; else: ?>
    <div class="empty-state"><span>🌾</span><h3>Donasi baru segera hadir</h3><p>Jadilah donatur pertama dan bantu makanan berlebih menjadi manfaat.</p><a class="btn btn-primary" href="buat-donasi.php">Bagikan makanan</a></div>
  <?php endif; ?>
  </div>
</div></section>
<section class="cta-section"><div class="container cta-inner"><div><span class="eyebrow eyebrow-light">MARI BERGERAK BERSAMA</span><h2>Punya makanan berlebih?<br>Jangan biarkan sia-sia.</h2><p>Mulai dari satu donasi, ciptakan perubahan di sekitar kita.</p></div><a class="btn btn-light" href="buat-donasi.php">Buat Donasi <span>↗</span></a></div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
