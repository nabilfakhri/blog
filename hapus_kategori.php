<?php
require 'koneksi.php';
header('Content-Type: application/json');

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($id <= 0) {
    echo json_encode(['status' => 'error', 'pesan' => 'ID tidak valid']);
    exit;
}

// Cek apakah kategori masih digunakan artikel
$stmtCek = $koneksi->prepare("SELECT COUNT(*) AS jml FROM artikel WHERE id_kategori = ?");
$stmtCek->bind_param('i', $id);
$stmtCek->execute();
$cek = $stmtCek->get_result()->fetch_assoc();
$stmtCek->close();

if ($cek['jml'] > 0) {
    echo json_encode(['status' => 'error', 'pesan' => 'Kategori tidak dapat dihapus karena masih memiliki artikel']);
    exit;
}

$stmt = $koneksi->prepare("DELETE FROM kategori_artikel WHERE id = ?");
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'ok', 'pesan' => 'Kategori berhasil dihapus']);
} else {
    echo json_encode(['status' => 'error', 'pesan' => 'Gagal menghapus kategori']);
}

$stmt->close();
$koneksi->close();
