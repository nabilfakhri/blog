<?php
require 'koneksi.php';
header('Content-Type: application/json');

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($id <= 0) {
    echo json_encode(['status' => 'error', 'pesan' => 'ID tidak valid']);
    exit;
}

// Ambil gambar untuk dihapus
$stmtGambar = $koneksi->prepare("SELECT gambar FROM artikel WHERE id = ?");
$stmtGambar->bind_param('i', $id);
$stmtGambar->execute();
$baris = $stmtGambar->get_result()->fetch_assoc();
$stmtGambar->close();

if (!$baris) {
    echo json_encode(['status' => 'error', 'pesan' => 'Artikel tidak ditemukan']);
    exit;
}

$stmt = $koneksi->prepare("DELETE FROM artikel WHERE id = ?");
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    // Hapus file gambar
    $path = __DIR__ . '/uploads_artikel/' . $baris['gambar'];
    if (file_exists($path)) unlink($path);
    echo json_encode(['status' => 'ok', 'pesan' => 'Artikel berhasil dihapus']);
} else {
    echo json_encode(['status' => 'error', 'pesan' => 'Gagal menghapus artikel']);
}

$stmt->close();
$koneksi->close();
