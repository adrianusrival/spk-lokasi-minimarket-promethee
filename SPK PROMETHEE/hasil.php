<?php
session_start();
include 'db.php';
include 'header.php';
?>

<h2>Hasil Perhitungan Metode PROMETHEE</h2>
<hr>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr style="background:#2b6cb0;color:white;">
        <th style="width:100px;">Alternatif</th>
        <th>Nama Alternatif</th>
        <th style="width:140px;">Leaving Flow (φ+)</th>
        <th style="width:140px;">Entering Flow (φ-)</th>
        <th style="width:140px;">Net Flow (φ)</th>
        <th style="width:80px;">Ranking</th>
    </tr>

<?php
$q = "
    SELECT 
    h.*, 
    a.nama_alternatif,
    a.keterangan
FROM tb_hasil h
JOIN tb_alternatif a ON a.id_alternatif = h.id_alternatif
ORDER BY h.net_flow DESC

";

$res = $conn->query($q);
$no = 1;

if ($res->num_rows == 0) {
    echo "<tr><td colspan='6' align='center'>Belum ada hasil perhitungan PROMETHEE.</td></tr>";
} else {
    while ($row = $res->fetch_assoc()) {
        echo "<tr>";
        echo "<td align='center'><strong>{$row['keterangan']}</strong></td>";
        echo "<td>" . htmlspecialchars($row['nama_alternatif']) . "</td>";
        echo "<td align='center'>" . number_format($row['leaving_flow'], 3) . "</td>";
        echo "<td align='center'>" . number_format($row['entering_flow'], 3) . "</td>";
        echo "<td align='center'><strong>" . number_format($row['net_flow'], 3) . "</strong></td>";
        echo "<td align='center'><strong>{$row['ranking']}</strong></td>";
        echo "</tr>";
        $no++;
    }
}
?>
</table>

<div style="margin-top:20px;">
    <a href="proses_promethee.php" 
       style="background:#2b6cb0;color:white;padding:10px 14px;text-decoration:none;border-radius:6px;">
       🔄 Proses Ulang Perhitungan
    </a>

    <a href="laporan.php" 
       style="background:#157347;color:white;padding:10px 14px;text-decoration:none;border-radius:6px;margin-left:10px;">
       🖨 Cetak Laporan
    </a>
</div>

<?php include 'footer.php'; ?>
