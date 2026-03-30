<?php
include 'header.php';
include 'db.php';

// Simpan nilai penilaian
if (isset($_POST['simpan'])) {

    foreach ($_POST['nilai'] as $id_alternatif => $kriteria_values) {
        foreach ($kriteria_values as $id_kriteria => $nilai) {

            $id_alternatif = intval($id_alternatif);
            $id_kriteria   = intval($id_kriteria);
            $nilai         = floatval($nilai);

            // Cek apakah data sudah ada
            $cek = $conn->prepare("SELECT id_penilaian FROM tb_penilaian WHERE id_alternatif=? AND id_kriteria=?");
            $cek->bind_param("ii", $id_alternatif, $id_kriteria);
            $cek->execute();
            $cek_result = $cek->get_result();

            if ($cek_result->num_rows > 0) {
                // UPDATE
                $stmt = $conn->prepare("UPDATE tb_penilaian SET nilai=? WHERE id_alternatif=? AND id_kriteria=?");
                $stmt->bind_param("dii", $nilai, $id_alternatif, $id_kriteria);
                $stmt->execute();
            } else {
                // INSERT
                $stmt = $conn->prepare("INSERT INTO tb_penilaian (id_alternatif, id_kriteria, nilai) VALUES (?,?,?)");
                $stmt->bind_param("iid", $id_alternatif, $id_kriteria, $nilai);
                $stmt->execute();
            }
        }
    }

    echo "<script>alert('Data penilaian berhasil disimpan!');window.location='penilaian.php';</script>";
    exit;
}

// Ambil data alternatif & kriteria
$alternatif = $conn->query("SELECT * FROM tb_alternatif ORDER BY id_alternatif ASC");
$kriteria   = $conn->query("SELECT * FROM tb_kriteria ORDER BY id_kriteria ASC");
?>

<h2>Penilaian Alternatif terhadap Kriteria</h2>
<hr>

<form method="POST">
    <table border="1" cellspacing="0" cellpadding="8" width="100%">
        <tr style="background:#2b6cb0;color:white;">
            <th>Alternatif</th>
            <?php while ($k = $kriteria->fetch_assoc()): ?>
                <th><?= htmlspecialchars($k['nama_kriteria']); ?></th>
            <?php endwhile; ?>
        </tr>

        <?php
        // Reset pointer for kriteria result set
        $kriteria = $conn->query("SELECT * FROM tb_kriteria ORDER BY id_kriteria ASC");

        while ($a = $alternatif->fetch_assoc()):
        ?>
        <tr>
            <td><strong><?= htmlspecialchars($a['nama_alternatif']); ?></strong></td>

            <?php
            $kriteria->data_seek(0);
            while ($k = $kriteria->fetch_assoc()):

                $stmt = $conn->prepare("SELECT nilai FROM tb_penilaian WHERE id_alternatif=? AND id_kriteria=?");
                $stmt->bind_param("ii", $a['id_alternatif'], $k['id_kriteria']);
                $stmt->execute();
                $result_nilai = $stmt->get_result();
                $row_nilai = $result_nilai->fetch_assoc();

                $nilai = $row_nilai ? $row_nilai['nilai'] : "";
            ?>
                <td>
    <input 
        type="number"
        step="0.01"
        min="0"
        name="nilai[<?= $a['id_alternatif']; ?>][<?= $k['id_kriteria']; ?>]"
        value="<?= ($nilai !== '') ? number_format($nilai, 2, '.', '') : ''; ?>"
        style="width:90px;"
        required
    >
</td>
            <?php endwhile; ?>
        </tr>
        <?php endwhile; ?>
    </table>

    <br>
    <button type="submit" name="simpan">Simpan Penilaian</button>
</form>

<?php include 'footer.php'; ?>
