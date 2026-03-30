<?php
include 'header.php';

// Pastikan hanya admin bisa akses (defensive)
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
    header("Location: dashboard_manajer.php");
    exit;
}
?>

<h2>Selamat Datang, <?= htmlspecialchars($_SESSION['username']); ?> 👋</h2>
<p>Anda login sebagai <strong><?= htmlspecialchars($_SESSION['level']); ?></strong></p>

<div style="display: grid; gap: 15px; margin-top: 25px;">
    <a href="kriteria.php" style="background:#2b6cb0;color:white;padding:12px 18px;border-radius:8px;text-decoration:none;">Kelola Data Kriteria</a>
    <a href="alternatif.php" style="background:#2b6cb0;color:white;padding:12px 18px;border-radius:8px;text-decoration:none;">Kelola Data Alternatif</a>
    <a href="penilaian.php" style="background:#2b6cb0;color:white;padding:12px 18px;border-radius:8px;text-decoration:none;">Input Nilai Penilaian</a>
    <a href="proses_promethee.php" style="background:#2b6cb0;color:white;padding:12px 18px;border-radius:8px;text-decoration:none;">Proses Perhitungan PROMETHEE</a>
    <a href="hasil.php" style="background:#2b6cb0;color:white;padding:12px 18px;border-radius:8px;text-decoration:none;">Lihat Hasil Perankingan</a>
</div>

<?php include 'footer.php'; ?>
