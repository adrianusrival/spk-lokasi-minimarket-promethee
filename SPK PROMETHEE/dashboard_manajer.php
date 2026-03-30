<?php
include 'header.php';

// Pastikan manajer tidak mengakses admin page
if (!isset($_SESSION['level'])) {
    header("Location: index.php");
    exit;
}
?>

<h2>Selamat Datang, <?= htmlspecialchars($_SESSION['username']); ?> 👋</h2>
<p>Anda login sebagai <strong><?= htmlspecialchars($_SESSION['level']); ?></strong></p>

<div style="display: grid; gap: 15px; margin-top: 25px;">
    <a href="hasil.php" style="background:#2b6cb0;color:white;padding:12px 18px;border-radius:8px;text-decoration:none;">Lihat Hasil Perankingan</a>
    <a href="laporan.php" style="background:#2b6cb0;color:white;padding:12px 18px;border-radius:8px;text-decoration:none;">Cetak Laporan</a>
</div>

<?php include 'footer.php'; ?>
