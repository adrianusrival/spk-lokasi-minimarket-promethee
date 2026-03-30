<?php
session_start();
include 'db.php';
include 'header.php';

// ambil alternatif terbaik (net flow tertinggi)
$qTop = "
    SELECT h.net_flow, a.nama_alternatif, a.keterangan
    FROM tb_hasil h
    JOIN tb_alternatif a ON a.id_alternatif = h.id_alternatif
    ORDER BY h.net_flow DESC
    LIMIT 1
";
$top = $conn->query($qTop)->fetch_assoc();
?>

<!-- LOGO + JUDUL -->
<div style="display:flex; align-items:center; margin-bottom:20px;">
    <img src="assets/src.jpg" width="80" style="margin-right:15px;">
    <div>
        <h2 style="margin:0;">Laporan Hasil Perhitungan PROMETHEE</h2>
        <small>Tanggal Cetak: <?= date('d-m-Y'); ?></small>
    </div>
</div>

<hr>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr style="background:#2b6cb0;color:white;">
        <th>Alternatif</th>
        <th>Nama Alternatif</th>
        <th>Leaving Flow (φ+)</th>
        <th>Entering Flow (φ-)</th>
        <th>Net Flow (φ)</th>
        <th>Ranking</th>
    </tr>

<?php
$q = "
    SELECT h.*, a.nama_alternatif, a.keterangan
    FROM tb_hasil h
    JOIN tb_alternatif a ON a.id_alternatif = h.id_alternatif
    ORDER BY h.net_flow DESC
";
$res = $conn->query($q);

while ($row = $res->fetch_assoc()) {
    echo "<tr>";
    echo "<td align='center'><b>{$row['keterangan']}</b></td>";
    echo "<td>{$row['nama_alternatif']}</td>";
    echo "<td align='center'>" . number_format($row['leaving_flow'], 3) . "</td>";
    echo "<td align='center'>" . number_format($row['entering_flow'], 3) . "</td>";
    echo "<td align='center'><b>" . number_format($row['net_flow'], 3) . "</b></td>";
    echo "<td align='center'>{$row['ranking']}</td>";
    echo "</tr>";
}
?>
</table>

<!-- KESIMPULAN -->
<div style="margin-top:25px;">
    <h3>Kesimpulan</h3>
    <p style="text-align:justify;">
        Dari tabel di atas diperoleh keputusan bahwa rekomendasi alternatif
        yang mendapatkan ranking tertinggi dengan nilai Net Flow
        <b><?= number_format($top['net_flow'], 3); ?></b>
        adalah alternatif <b><?= $top['keterangan']; ?></b>
        yaitu <b><?= $top['nama_alternatif']; ?></b>.
    </p>
</div>

    <!-- TANDA TANGAN (TEXT SAJA) -->
<div style="margin-top:40px; width:300px; float:right; text-align:center;">
    <p>Solok, <?= date('d-m-Y'); ?></p>
    <br><br><br>
    <p><b>( ........................................ )</b></p>
    <p>Manajer</p>
</div>

<div style="clear:both;"></div>

</div>

<div style="clear:both;"></div>

<br><br>

<a href="cetak_laporan.php" 
   style="background:#157347;color:white;padding:10px 16px;text-decoration:none;border-radius:6px;">
   🖨 Cetak PDF
</a>

<?php include 'footer.php'; ?>
