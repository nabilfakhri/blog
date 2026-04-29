<?php
require 'koneksi.php';
header('Content-Type: application/json');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    echo json_encode(['status' => 'error', 'pesan' => 'ID tidak valid']);
    exit;
}

$stmt = $koneksi->prepare("SELECT id, nama_depan, nama_belakang, user_name, foto FROM penulis WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$hasil = $stmt->get_result();
$data  = $hasil->fetch_assoc();

if ($data) {
    echo json_encode(['status' => 'ok', 'data' => $data]);
} else {
    echo json_encode(['status' => 'error', 'pesan' => 'Data tidak ditemukan']);
}

$stmt->close();
$koneksi->close();
