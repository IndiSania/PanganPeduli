<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
if (!is_admin()) redirect('login.php');

$allowedStatuses = ['Tersedia','Dipesan','Diambil','Selesai','Ditolak'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $status = (string)($_POST['status'] ?? '');
    if ($id && in_array($status, $allowedStatuses, true)) {
        $stmt = $pdo->prepare('UPDATE donations SET status = :status WHERE id = :id');
        $stmt->execute(['status'=>$status, 'id'=>$id]);
        set_flash('success', 'Status donasi berhasil diperbarui.');
    } else {
        set_flash('error', 'Data perubahan status tidak valid.');
    }
    redirect('dashboard.php');
}
$counts = $pdo->query("SELECT COUNT(*) AS total, SUM(status='Tersedia') AS available, SUM(status='Selesai') AS completed, SUM(status='Dipesan') AS reserved FROM donations")->fetch();
$filter = trim((string)($_GET['status'] ?? ''));
$sql = 'SELECT id, title, donor_name, category, quantity, unit, location, expiry_at, status, created_at FROM donations';
$params = [];
if (in_array($filter, $allowedStatuses, true)) { $sql .= ' WHERE status = :status'; $params['status'] = $filter; }
$sql .= ' ORDER BY created_at DESC';
$stmt = $pdo->prepare($sql); $stmt->execute($params); $items = $stmt->fetchAll();
$pageTitle = 'Dashboard Admin';
require __DIR__ . '/../includes/header.php';
?>
<section class="admin-page"><div class="container">
  <div class="admin-top"><div><span class="eyebrow">PANGANPEDULI / ADMIN</span><h1>Dashboard pengelolaan</h1><p>Halo, <?= e($_SESSION['admin_name'] ?? 'Admin') ?>. Pantau dan perbarui status donasi.</p></div><form action="logout.php" method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><button class="btn btn-ghost" type="submit">Keluar ↗</button></form></div>
  <div class="admin-stats"><div class="admin-stat"><span>Total donasi</span><strong><?= (int)$counts['total'] ?></strong></div><div class="admin-stat"><span>Tersedia</span><strong><?= (int)$counts['available'] ?></strong></div><div class="admin-stat"><span>Dipesan</span><strong><?= (int)$counts['reserved'] ?></strong></div><div class="admin-stat"><span>Selesai</span><strong><?= (int)$counts['completed'] ?></strong></div></div>
  <div class="admin-table-wrap"><div class="table-heading"><div><h2>Daftar donasi</h2><p>Perbarui status sesuai proses distribusi.</p></div><form method="get"><select name="status" onchange="this.form.submit()" aria-label="Filter status"><option value="">Semua status</option><?php foreach ($allowedStatuses as $s): ?><option value="<?= e($s) ?>" <?= $filter===$s?'selected':'' ?>><?= e($s) ?></option><?php endforeach; ?></select></form></div>
  <div class="table-scroll"><table><thead><tr><th>Donasi</th><th>Donatur</th><th>Jumlah</th><th>Batas waktu</th><th>Status / tindakan</th></tr></thead><tbody>
  <?php foreach ($items as $item): ?><tr><td><strong><?= e($item['title']) ?></strong><small><?= e($item['category']) ?> · <?= e($item['location']) ?></small></td><td><?= e($item['donor_name']) ?></td><td><?= (int)$item['quantity'] . ' ' . e($item['unit']) ?></td><td><?= date('d M Y H:i', strtotime($item['expiry_at'])) ?></td><td><form class="status-form" method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int)$item['id'] ?>"><select name="status" aria-label="Status donasi <?= e($item['title']) ?>"><?php foreach ($allowedStatuses as $s): ?><option value="<?= e($s) ?>" <?= $item['status']===$s?'selected':'' ?>><?= e($s) ?></option><?php endforeach; ?></select><button class="btn btn-small btn-primary" type="submit">Simpan</button></form></td></tr><?php endforeach; ?>
  <?php if (!$items): ?><tr><td colspan="5" class="table-empty">Belum ada data donasi.</td></tr><?php endif; ?>
  </tbody></table></div></div>
</div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
