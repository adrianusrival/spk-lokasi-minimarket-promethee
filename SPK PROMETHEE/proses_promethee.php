<?php
session_start();
include 'db.php';

// ==============================
// 1. Ambil Data Kriteria
// ==============================
$kriteria = [];
$q = $conn->query("SELECT * FROM tb_kriteria ORDER BY id_kriteria ASC");

while ($row = $q->fetch_assoc()) {
    $kriteria[] = [
        'id'    => $row['id_kriteria'],
        'bobot' => (float)$row['bobot'],
        'tipe'  => strtolower($row['tipe']),
        'p'     => (int)$row['p_threshold']
    ];
}

if (count($kriteria) == 0) {
    die("<script>alert('Kriteria belum ada!');window.location='kriteria.php';</script>");
}

// ==============================
// 2. Ambil Data Alternatif
// ==============================
$alternatif = [];
$q2 = $conn->query("SELECT * FROM tb_alternatif ORDER BY id_alternatif ASC");

while ($row = $q2->fetch_assoc()) {
    $alternatif[] = [
        'id'   => $row['id_alternatif'],
        'nama' => $row['nama_alternatif']
    ];
}

$n = count($alternatif);

if ($n < 2) {
    die("<script>alert('Minimal 2 alternatif diperlukan!');window.location='alternatif.php';</script>");
}

// ==============================
// 3. Ambil Nilai Penilaian
// ==============================
$nilai = [];

foreach ($alternatif as $a) {
    foreach ($kriteria as $k) {

        $stmt = $conn->prepare("
            SELECT nilai FROM tb_penilaian 
            WHERE id_alternatif=? AND id_kriteria=?
        ");
        $stmt->bind_param("ii", $a['id'], $k['id']);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = $res->fetch_assoc();

        if (!$data || $data['nilai'] === null) {
            die("<script>alert('Nilai penilaian belum lengkap!');window.location='penilaian.php';</script>");
        }

        $nilai[$a['id']][$k['id']] = (float)$data['nilai'];
    }
}

// ==============================
// 4. Fungsi Preferensi (V-Shape Threshold)
// ==============================
function preferensi($tipe, $va, $vb, $p)
{
    // Hitung selisih
    $d = ($tipe == 'benefit') ? ($va - $vb) : ($vb - $va);

    if ($d <= 0) return 0;

    if ($d >= $p && $p > 0) return 1;

    return ($p > 0) ? ($d / $p) : (($d > 0) ? 1 : 0);
}

// ==============================
// 5. Hitung π(a,b)
// ==============================
$pi = [];

foreach ($alternatif as $a) {
    foreach ($alternatif as $b) {

        if ($a['id'] == $b['id']) continue;

        $sum = 0;

        foreach ($kriteria as $k) {
            $va = $nilai[$a['id']][$k['id']];
            $vb = $nilai[$b['id']][$k['id']];
            $p  = $k['p'];

            $P = preferensi($k['tipe'], $va, $vb, $p);

            $sum += $k['bobot'] * $P;
        }

        $pi[$a['id']][$b['id']] = $sum;
    }
}

// ==============================
// 6. Hitung Leaving, Entering, Net Flow
// ==============================
$conn->query("TRUNCATE TABLE tb_hasil");

foreach ($alternatif as $a) {

    $idA = $a['id'];
    $leaving = 0;
    $entering = 0;

    foreach ($alternatif as $b) {
        if ($idA == $b['id']) continue;

        $leaving  += $pi[$idA][$b['id']] ?? 0;
        $entering += $pi[$b['id']][$idA] ?? 0;
    }

    $leaving  = $leaving / ($n - 1);
    $entering = $entering / ($n - 1);
    $net = $leaving - $entering;

    $stmt = $conn->prepare("
        INSERT INTO tb_hasil (id_alternatif, leaving_flow, entering_flow, net_flow) 
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("iddd", $idA, $leaving, $entering, $net);
    $stmt->execute();
}

// ==============================
// 7. Ranking
// ==============================
$res = $conn->query("SELECT id_hasil, net_flow FROM tb_hasil ORDER BY net_flow DESC");

$rank = 1;
$prev_value = null;
$sama = 1;

while ($row = $res->fetch_assoc()) {
    $this_value = $row['net_flow'];

    if ($prev_value !== null && $this_value == $prev_value) {
        // nilai sama → rank sama
    } else {
        $rank = $sama;
    }

    $conn->query("UPDATE tb_hasil SET ranking=$rank WHERE id_hasil=".$row['id_hasil']);

    $prev_value = $this_value;
    $sama++;
}

echo "<script>alert('Perhitungan PROMETHEE berhasil!');window.location='hasil.php';</script>";
exit;
?>
