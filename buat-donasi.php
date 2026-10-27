<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$errors = [];
$values = ['donor_name'=>'','email'=>'','title'=>'','category'=>'','quantity'=>'1','unit'=>'porsi','location'=>'','expiry_at'=>'','description'=>''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    foreach ($values as $key => $default) { $values[$key] = trim((string)($_POST[$key] ?? $default)); }
    if (mb_strlen($values['donor_name']) < 2) $errors[] = 'Nama donatur minimal 2 karakter.';
    if (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Masukkan alamat email yang valid.';
    if ($values['title'] === '' || mb_strlen($values['title']) > 120) $errors[] = 'Nama makanan wajib diisi (maksimal 120 karakter).';
    if (!in_array($values['category'], ['Buah','Sayuran','Roti','Makanan Siap Saji','Bahan Pokok','Lainnya'], true)) $errors[] = 'Pilih kategori yang tersedia.';
    if (!filter_var($values['quantity'], FILTER_VALIDATE_INT) || (int)$values['quantity'] < 1 || (int)$values['quantity'] > 100000) $errors[] = 'Jumlah harus berupa bilangan bulat positif.';
    if (!in_array($values['unit'], ['porsi','paket','kg','buah','kotak','bungkus'], true)) $errors[] = 'Pilih satuan yang tersedia.';
    if ($values['location'] === '' || mb_strlen($values['location']) > 180) $errors[] = 'Lokasi wajib diisi (maksimal 180 karakter).';
    if (mb_strlen($values['description']) > 1200) $errors[] = 'Deskripsi maksimal 1200 karakter.';
    $expiry = strtotime($values['expiry_at']);
    if (!$expiry || $expiry <= time()) $errors[] = 'Batas pengambilan harus berupa waktu di masa depan.';
    if (!$errors) {
        $stmt = $pdo->prepare("INSERT INTO donations (donor_name, email, title, category, quantity, unit, location, expiry_at, description, status, created_at) VALUES (:donor_name, :email, :title, :category, :quantity, :unit, :location, :expiry_at, :description, 'Tersedia', NOW())");
        $stmt->execute([
            'donor_name'=>$values['donor_name'], 'email'=>$values['email'], 'title'=>$values['title'],
            'category'=>$values['category'], 'quantity'=>(int)$values['quantity'], 'unit'=>$values['unit'],
            'location'=>$values['location'], 'expiry_at'=>date('Y-m-d H:i:s', $expiry),
            'description'=>$values['description']
        ]);
        set_flash('success', 'Terima kasih! Donasi berhasil ditambahkan dan kini tampil di katalog.');
        redirect('donasi.php');
    }
}
$pageTitle = 'Buat Donasi';
require __DIR__ . '/includes/header.php';
?>
<section class="page-hero"><div class="container"><span class="eyebrow">MULAI BERBAGI</span><h1>Bagikan makanan, tebarkan kebaikan.</h1><p>Isi detail donasi dengan jelas agar calon penerima dapat meninjau informasi sebelum pengambilan.</p></div></section>
<section class="section"><div class="container form-layout">
  <div class="form-main"><div class="form-heading"><span class="form-step">FORM DONASI</span><h2>Informasi makanan</h2><p>Kolom bertanda * wajib diisi.</p></div>
  <?php if ($errors): ?><div class="alert error"><strong>Periksa kembali:</strong><ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
  <form method="post" class="donation-form" novalidate>
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
    <div class="form-section-title"><span>01</span><div><strong>Data donatur</strong><small>Informasi pihak yang membagikan makanan.</small></div></div>
    <div class="form-row"><label>Nama donatur *<input name="donor_name" required maxlength="100" value="<?= e($values['donor_name']) ?>" placeholder="Nama orang atau usaha"></label><label>Email aktif *<input type="email" name="email" required maxlength="190" value="<?= e($values['email']) ?>" placeholder="nama@email.com"></label></div>
    <div class="form-section-title"><span>02</span><div><strong>Detail makanan</strong><small>Jelaskan makanan yang tersedia untuk dibagikan.</small></div></div>
    <label>Nama makanan *<input name="title" required maxlength="120" value="<?= e($values['title']) ?>" placeholder="Contoh: Roti tawar kemasan"></label>
    <div class="form-row"><label>Kategori *<select name="category" required><option value="">Pilih kategori</option><?php foreach (['Buah','Sayuran','Roti','Makanan Siap Saji','Bahan Pokok','Lainnya'] as $cat): ?><option <?= $values['category'] === $cat ? 'selected' : '' ?> value="<?= e($cat) ?>"><?= e($cat) ?></option><?php endforeach; ?></select></label><div class="quantity-group"><label>Jumlah *<input type="number" name="quantity" min="1" max="100000" required value="<?= e($values['quantity']) ?>"></label><label>Satuan<select name="unit"><?php foreach (['porsi','paket','kg','buah','kotak','bungkus'] as $unit): ?><option <?= $values['unit'] === $unit ? 'selected' : '' ?> value="<?= e($unit) ?>"><?= e(ucfirst($unit)) ?></option><?php endforeach; ?></select></label></div></div>
    <label>Lokasi pengambilan *<input name="location" required maxlength="180" value="<?= e($values['location']) ?>" placeholder="Alamat atau patokan lokasi yang jelas"></label>
    <label>Batas waktu pengambilan *<input type="datetime-local" name="expiry_at" required value="<?= e($values['expiry_at']) ?>"></label>
    <label>Deskripsi tambahan<textarea name="description" rows="4" maxlength="1200" placeholder="Kondisi makanan, cara penyimpanan, waktu dibuat, dan informasi penting lainnya."><?= e($values['description']) ?></textarea><small class="field-help">Jangan bagikan makanan yang tidak aman dikonsumsi. Cantumkan informasi alergen bila diketahui.</small></label>
    <div class="form-actions"><button type="submit" class="btn btn-primary">Kirim Donasi <span>↗</span></button><span>Dengan mengirim, kamu menyatakan informasi sudah benar.</span></div>
  </form></div>
  <aside class="form-aside"><div class="aside-illustration">🥗</div><h3>Berbagi dengan bertanggung jawab</h3><p>Informasi yang lengkap membantu calon penerima menilai donasi dengan lebih baik.</p><ul><li>✓ Pastikan makanan layak konsumsi</li><li>✓ Jelaskan penyimpanan dan alergen</li><li>✓ Tetapkan batas pengambilan realistis</li><li>✓ Koordinasikan penyerahan secara aman</li></ul><div class="aside-note">PanganPeduli adalah prototipe. Donatur dan penerima tetap bertanggung jawab memeriksa keamanan pangan.</div></aside>
</div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
