<?php
include 'header.php';
include 'db.php';

// Tambah data kriteria
if (isset($_POST['tambah'])) {
    $nama = trim($_POST['nama_kriteria']);
    $bobot = floatval($_POST['bobot']);
    $tipe = $_POST['tipe'];
    $p = intval($_POST['p_threshold']);

    $sql = "INSERT INTO tb_kriteria (nama_kriteria, bobot, tipe, p_threshold) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdsi", $nama, $bobot, $tipe, $p);
    $stmt->execute();

    echo "<script>alert('Kriteria berhasil ditambahkan!');window.location='kriteria.php';</script>";
    exit;
}

// Hapus data
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $stmt = $conn->prepare("DELETE FROM tb_kriteria WHERE id_kriteria=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    echo "<script>alert('Kriteria berhasil dihapus!');window.location='kriteria.php';</script>";
    exit;
}

// Update data
if (isset($_POST['update'])) {
    $id = intval($_POST['id_kriteria']);
    $nama = trim($_POST['nama_kriteria']);
    $bobot = floatval($_POST['bobot']);
    $tipe = $_POST['tipe'];
    $p = intval($_POST['p_threshold']);

    $sql = "UPDATE tb_kriteria SET nama_kriteria=?, bobot=?, tipe=?, p_threshold=? WHERE id_kriteria=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdsii", $nama, $bobot, $tipe, $p, $id);
    $stmt->execute();

    echo "<script>alert('Kriteria berhasil diperbarui!');window.location='kriteria.php';</script>";
    exit;
}
?>

<h2>Data Kriteria</h2>
<hr>

<form method="POST" style="margin-bottom:20px;">
    <input type="hidden" name="id_kriteria" id="id_kriteria">

    <label>Nama Kriteria</label><br>
    <input type="text" name="nama_kriteria" id="nama_kriteria" required><br><br>

    <label>Bobot</label><br>
    <input type="number" step="0.01" name="bobot" id="bobot" required><br><br>

    <label>Tipe</label><br>
    <select name="tipe" id="tipe" required>
        <option value="">-- Pilih --</option>
        <option value="benefit">Benefit</option>
        <option value="cost">Cost</option>
    </select><br><br>

    <label>P Threshold</label><br>
    <input type="number" name="p_threshold" id="p_threshold" required><br><br>

    <button type="submit" name="tambah" id="btnTambah">Tambah</button>
    <button type="submit" name="update" id="btnUpdate" style="background:orange; display:none;">Update</button>
</form>

<table border="1" cellspacing="0" cellpadding="8" width="100%">
    <tr style="background:#2b6cb0;color:white;">
        <th>No</th>
        <th>Nama Kriteria</th>
        <th>Bobot</th>
        <th>Tipe</th>
        <th>P Threshold</th>
        <th>Aksi</th>
    </tr>

    <?php
    $no = 1;
    $result = $conn->query("SELECT * FROM tb_kriteria ORDER BY id_kriteria");
    while ($row = $result->fetch_assoc()):
        $json = json_encode($row, JSON_HEX_APOS | JSON_HEX_QUOT);
    ?>
    <tr>
        <td><?= $no++; ?></td>
        <td><?= htmlspecialchars($row['nama_kriteria']); ?></td>
        <td><?= number_format($row['bobot'], 2, ',', '.'); ?></td>
        <td><?= ucfirst($row['tipe']); ?></td>
        <td><?= $row['p_threshold']; ?></td>
        <td>
            <a href="#" onclick='editData(<?= $json ?>)'>Edit</a> |
            <a href="kriteria.php?hapus=<?= $row['id_kriteria']; ?>" onclick="return confirm('Yakin ingin hapus?')">Hapus</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<script>
function editData(row) {
    document.getElementById('id_kriteria').value = row.id_kriteria;
    document.getElementById('nama_kriteria').value = row.nama_kriteria;
    document.getElementById('bobot').value = row.bobot;
    document.getElementById('tipe').value = row.tipe;
    document.getElementById('p_threshold').value = row.p_threshold;

    document.getElementById('btnTambah').style.display = 'none';
    document.getElementById('btnUpdate').style.display = 'inline-block';

    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>

<?php include 'footer.php'; ?>
