<?php
require 'koneksi.php';
header('Content-Type: application/json');

$id            = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$nama_depan    = trim($_POST['nama_depan']    ?? '');
$nama_belakang = trim($_POST['nama_belakang'] ?? '');
$user_name     = trim($_POST['user_name']     ?? '');
$password_raw  = trim($_POST['password']      ?? '');

if ($id <= 0 || !$nama_depan || !$nama_belakang || !$user_name) {
    echo json_encode(['status' => 'error', 'pesan' => 'Data tidak lengkap']);
    exit;
}

// Ambil data lama
$stmtLama = $koneksi->prepare("SELECT foto FROM penulis WHERE id = ?");
$stmtLama->bind_param('i', $id);
$stmtLama->execute();
$lama = $stmtLama->get_result()->fetch_assoc();
$stmtLama->close();

if (!$lama) {
    echo json_encode(['status' => 'error', 'pesan' => 'Data tidak ditemukan']);
    exit;
}

$foto = $lama['foto'];

// Handle foto baru
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $tmp  = $_FILES['foto']['tmp_name'];
    $size = $_FILES['foto']['size'];

    if ($size > 2 * 1024 * 1024) {
        echo json_encode(['status' => 'error', 'pesan' => 'Ukuran file maksimal 2 MB']);
        exit;
    }

    $finfo   = new finfo(FILEINFO_MIME_TYPE);
    $mime    = $finfo->file($tmp);
    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    if (!in_array($mime, $allowed)) {
        echo json_encode(['status' => 'error', 'pesan' => 'Tipe file tidak diizinkan']);
        exit;
    }

    // Hapus foto lama jika bukan default
    if ($lama['foto'] !== 'default.png') {
        $pathLama = __DIR__ . '/uploads_penulis/' . $lama['foto'];
        if (file_exists($pathLama)) unlink($pathLama);
    }

    $ext  = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $foto = uniqid('penulis_', true) . '.' . $ext;
    move_uploaded_file($tmp, __DIR__ . '/uploads_penulis/' . $foto);
}

// Build query
if ($password_raw !== '') {
    $hash = password_hash($password_raw, PASSWORD_BCRYPT);
    $stmt = $koneksi->prepare("UPDATE penulis SET nama_depan=?, nama_belakang=?, user_name=?, password=?, foto=? WHERE id=?");
    $stmt->bind_param('sssssi', $nama_depan, $nama_belakang, $user_name, $hash, $foto, $id);
} else {
    $stmt = $koneksi->prepare("UPDATE penulis SET nama_depan=?, nama_belakang=?, user_name=?, foto=? WHERE id=?");
    $stmt->bind_param('ssssi', $nama_depan, $nama_belakang, $user_name, $foto, $id);
}

if ($stmt->execute()) {
    echo json_encode(['status' => 'ok', 'pesan' => 'Data penulis berhasil diperbarui']);
} else {
    if ($koneksi->errno === 1062) {
        echo json_encode(['status' => 'error', 'pesan' => 'Username sudah digunakan']);
    } else {
        echo json_encode(['status' => 'error', 'pesan' => 'Gagal memperbarui data']);
    }
}

$stmt->close();
$koneksi->close();
