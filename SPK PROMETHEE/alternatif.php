<?php
include 'header.php';
include 'db.php';

// Tambah alternatif
if (isset($_POST['tambah'])) {
    $nama = trim($_POST['nama_alternatif']);
    $ket  = trim($_POST['keterangan']);

    $sql = "INSERT INTO tb_alternatif (nama_alternatif, keterangan) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $nama, $ket);
    $stmt->execute();

    echo "<script>alert('Data alternatif berhasil ditambahkan!');window.location='alternatif.php';</script>";
    exit;
}

// Hapus alternatif (prepared)
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $stmt = $conn->prepare("DELETE FROM tb_alternatif WHERE id_alternatif = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    echo "<script>alert('Data alternatif berhasil dihapus!');window.location='alternatif.php';</script>";
    exit;
}

// Update alternatif
if (isset($_POST['update'])) {
    $id   = intval($_POST['id_alternatif']);
    $nama = trim($_POST['nama_alternatif']);
    $ket  = trim($_POST['keterangan']);

    $sql = "UPDATE tb_alternatif SET nama_alternatif=?, keterangan=? WHERE id_alternatif=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $nama, $ket, $id);
    $stmt->execute();

    echo "<script>alert('Data alternatif berhasil diperbarui!');window.location='alternatif.php';</script>";
    exit;
}
?>

<h2>Data Alternatif</h2>
<hr>
<form method="POST" style="margin-bottom:20px;">
    <input type="hidden" name="id_alternatif" id="id_alternatif">
    <label>Nama Alternatif</label><br>
    <input type="text" name="nama_alternatif" id="nama_alternatif" required><br><br>

    <label>Keterangan</label><br>
    <textarea name="keterangan" id="keterangan" rows="3" style="width:100%;" required></textarea><br><br>

    <button type="submit" name="tambah" id="btnTambah">Tambah</button>
    <button type="submit" name="update" id="btnUpdate" style="background:orange; display:none;">Update</button>
</form>

<table border="1" cellspacing="0" cellpadding="8" width="100%">
    <tr style="background:#2b6cb0;color:white;">
        <th>No</th>
        <th>Nama Alternatif</th>
        <th>Keterangan</th>
        <th>Aksi</th>
    </tr>
    <?php
    $no = 1;
    $result = $conn->query("SELECT * FROM tb_alternatif ORDER BY id_alternatif ASC");
    while ($row = $result->fetch_assoc()):
        // safe array for json_encode (so JS receives correct structure)
        $row_js = [
            'id_alternatif' => (int)$row['id_alternatif'],
            'nama_alternatif' => $row['nama_alternatif'],
            'keterangan' => $row['keterangan']
        ];
    ?>
    <tr>
        <td><?= $no++; ?></td>
        <td><?= htmlspecialchars($row['nama_alternatif']); ?></td>
        <td><?= nl2br(htmlspecialchars($row['keterangan'])); ?></td>
        <td>
            <a href="#" onclick='editData(<?= json_encode($row_js, JSON_HEX_APOS|JSON_HEX_QUOT); ?>)'>Edit</a> |
            <a href="alternatif.php?hapus=<?= $row['id_alternatif']; ?>" onclick="return confirm('Yakin ingin hapus data ini?')">Hapus</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<script>
function editData(row) {
    // row is a JS object thanks to json_encode
    document.getElementById('id_alternatif').value = row.id_alternatif;
    document.getElementById('nama_alternatif').value = row.nama_alternatif;
    document.getElementById('keterangan').value = row.keterangan;

    document.getElementById('btnTambah').style.display = 'none';
    document.getElementById('btnUpdate').style.display = 'inline-block';

    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>

<?php include 'footer.php'; ?>
