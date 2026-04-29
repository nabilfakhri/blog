<?php
require 'koneksi.php';
header('Content-Type: application/json');

$sql = "SELECT a.id, a.judul, a.gambar, a.hari_tanggal, a.isi,
               p.nama_depan, p.nama_belakang,
               k.nama_kategori,
               a.id_penulis, a.id_kategori
        FROM artikel a
        JOIN penulis p ON a.id_penulis = p.id
        JOIN kategori_artikel k ON a.id_kategori = k.id
        ORDER BY a.id ASC";

$hasil = $koneksi->query($sql);
$data  = [];
while ($baris = $hasil->fetch_assoc()) {
    $data[] = $baris;
}

echo json_encode(['status' => 'ok', 'data' => $data]);
$koneksi->close();
