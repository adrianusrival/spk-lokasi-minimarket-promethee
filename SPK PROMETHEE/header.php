<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirect kalau belum login
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    header("Location: index.php");
    exit;
}

// pastikan ada level
$level = isset($_SESSION['level']) ? $_SESSION['level'] : '';
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'User';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>SPK Cindomart</title>
<style>
    body { font-family: Arial; margin: 0; background: #f7f9fc; }
    nav { background: #2b6cb0; color: white; padding: 10px; }
    nav a { color: white; text-decoration: none; margin: 0 10px; }
    nav a:hover { text-decoration: underline; }
    .container { padding: 20px; }
    .nav-right { float: right; }
</style>
</head>
<body>
<nav>
    <?php if ($level === 'admin'): ?>
        <a href="dashboard_admin.php">Dashboard</a>
        <a href="kriteria.php">Kriteria</a>
        <a href="alternatif.php">Alternatif</a>
        <a href="penilaian.php">Penilaian</a>
        <a href="proses_promethee.php">Proses</a>
        <a href="hasil.php">Hasil</a>
    <?php else: // manajer atau default ?>
        <a href="dashboard_manajer.php">Dashboard</a>
        <a href="hasil.php">Hasil</a>
        <a href="laporan.php">Laporan</a>
    <?php endif; ?>

    <span class="nav-right">
        Halo, <strong><?= htmlspecialchars($username); ?></strong>
        &nbsp;|&nbsp;
        <a href="logout.php" style="color:#fff;">Logout</a>
    </span>
    <div style="clear:both;"></div>
</nav>
<div class="container">
