<?php
require 'koneksi.php';
header('Content-Type: application/json');

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($id <= 0) {
    echo json_encode(['status' => 'error', 'pesan' => 'ID tidak valid']);
    exit;
}

// Cek apakah penulis masih memiliki artikel
$stmtCek = $koneksi->prepare("SELECT COUNT(*) AS jml FROM artikel WHERE id_penulis = ?");
$stmtCek->bind_param('i', $id);
$stmtCek->execute();
$cek = $stmtCek->get_result()->fetch_assoc();
$stmtCek->close();

if ($cek['jml'] > 0) {
    echo json_encode(['status' => 'error', 'pesan' => 'Penulis tidak dapat dihapus karena masih memiliki artikel']);
    exit;
}

// Ambil foto untuk dihapus
$stmtFoto = $koneksi->prepare("SELECT foto FROM penulis WHERE id = ?");
$stmtFoto->bind_param('i', $id);
$stmtFoto->execute();
$baris = $stmtFoto->get_result()->fetch_assoc();
$stmtFoto->close();

if (!$baris) {
    echo json_encode(['status' => 'error', 'pesan' => 'Data tidak ditemukan']);
    exit;
}

$stmt = $koneksi->prepare("DELETE FROM penulis WHERE id = ?");
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    // Hapus file foto jika bukan default
    if ($baris['foto'] !== 'default.png') {
        $path = __DIR__ . '/uploads_penulis/' . $baris['foto'];
        if (file_exists($path)) unlink($path);
    }
    echo json_encode(['status' => 'ok', 'pesan' => 'Data penulis berhasil dihapus']);
} else {
    echo json_encode(['status' => 'error', 'pesan' => 'Gagal menghapus data']);
}

$stmt->close();
$koneksi->close();
