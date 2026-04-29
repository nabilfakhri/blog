<?php
require 'koneksi.php';
header('Content-Type: application/json');

$sql   = "SELECT id, nama_kategori, keterangan FROM kategori_artikel ORDER BY id ASC";
$hasil = $koneksi->query($sql);

$data = [];
while ($baris = $hasil->fetch_assoc()) {
    $data[] = $baris;
}

echo json_encode(['status' => 'ok', 'data' => $data]);
$koneksi->close();
