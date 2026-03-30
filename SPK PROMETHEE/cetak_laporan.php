<?php
require('fpdf.php');
include 'db.php';

// ambil alternatif terbaik
$qTop = "
    SELECT h.net_flow, a.nama_alternatif, a.keterangan
    FROM tb_hasil h
    JOIN tb_alternatif a ON a.id_alternatif = h.id_alternatif
    ORDER BY h.net_flow DESC
    LIMIT 1
";
$top = $conn->query($qTop)->fetch_assoc();

// ambil semua hasil
$q = "
    SELECT h.*, a.nama_alternatif, a.keterangan
    FROM tb_hasil h
    JOIN tb_alternatif a ON a.id_alternatif = h.id_alternatif
    ORDER BY h.net_flow DESC
";
$res = $conn->query($q);

// PDF
$pdf = new FPDF('P','mm','A4');
$pdf->AddPage();

// LOGO
$pdf->Image('assets/src.jpg',10,10,25);
$pdf->Ln(18);

// JUDUL
$pdf->SetFont('Arial','B',14);
$pdf->Cell(190,10,'LAPORAN HASIL PERHITUNGAN PROMETHEE',0,1,'C');

$pdf->SetFont('Arial','',11);
$pdf->Cell(190,7,'Tanggal Cetak: '.date('d-m-Y'),0,1);
$pdf->Ln(3);

// HEADER TABEL
$pdf->SetFont('Arial','B',10);
$pdf->SetFillColor(43,108,176);
$pdf->SetTextColor(255);

$pdf->Cell(20,8,'Alt',1,0,'C',true);
$pdf->Cell(60,8,'Nama Alternatif',1,0,'C',true);
$pdf->Cell(30,8,'Leaving',1,0,'C',true);
$pdf->Cell(30,8,'Entering',1,0,'C',true);
$pdf->Cell(30,8,'Net Flow',1,0,'C',true);
$pdf->Cell(20,8,'Rank',1,1,'C',true);

$pdf->SetFont('Arial','',10);
$pdf->SetTextColor(0);

while ($row = $res->fetch_assoc()) {
    $pdf->Cell(20,8,$row['keterangan'],1,0,'C');
    $pdf->Cell(60,8,$row['nama_alternatif'],1,0);
    $pdf->Cell(30,8,number_format($row['leaving_flow'],3),1,0,'C');
    $pdf->Cell(30,8,number_format($row['entering_flow'],3),1,0,'C');
    $pdf->Cell(30,8,number_format($row['net_flow'],3),1,0,'C');
    $pdf->Cell(20,8,$row['ranking'],1,1,'C');
}

// KESIMPULAN
$pdf->Ln(6);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(190,7,'Kesimpulan',0,1);

$pdf->SetFont('Arial','',11);
$pdf->MultiCell(190,7,
    "Dari tabel di atas diperoleh keputusan bahwa rekomendasi alternatif ".
    "yang mendapatkan ranking tertinggi dengan nilai Net Flow ".
    number_format($top['net_flow'],3).
    " adalah alternatif ".$top['keterangan'].
    " yaitu ".$top['nama_alternatif']."."
);

// TANDA TANGAN (TEXT SAJA)
$pdf->Ln(15);
$pdf->Cell(120);
$pdf->Cell(70,7,'Solok, '.date('d-m-Y'),0,1,'C');

$pdf->Ln(15);
$pdf->Cell(120);
$pdf->Cell(70,7,'( ........................................ )',0,1,'C');

$pdf->Cell(120);
$pdf->Cell(70,7,'Manajer',0,1,'C');


$pdf->Output('I','Laporan_PROMETHEE.pdf');
