<?php
require 'koneksi.php';
header('Content-Type: application/json');

$nama_depan   = trim($_POST['nama_depan']   ?? '');
$nama_belakang = trim($_POST['nama_belakang'] ?? '');
$user_name    = trim($_POST['user_name']    ?? '');
$password_raw = trim($_POST['password']    ?? '');

if (!$nama_depan || !$nama_belakang || !$user_name || !$password_raw) {
    echo json_encode(['status' => 'error', 'pesan' => 'Semua field wajib diisi']);
    exit;
}

// Handle foto upload
$foto = 'default.png';
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $tmp  = $_FILES['foto']['tmp_name'];
    $size = $_FILES['foto']['size'];

    if ($size > 2 * 1024 * 1024) {
        echo json_encode(['status' => 'error', 'pesan' => 'Ukuran file maksimal 2 MB']);
        exit;
    }

    $finfo    = new finfo(FILEINFO_MIME_TYPE);
    $mime     = $finfo->file($tmp);
    $allowed  = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    if (!in_array($mime, $allowed)) {
        echo json_encode(['status' => 'error', 'pesan' => 'Tipe file tidak diizinkan. Gunakan JPG, PNG, GIF, atau WebP']);
        exit;
    }

    $ext  = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $foto = uniqid('penulis_', true) . '.' . $ext;
    move_uploaded_file($tmp, __DIR__ . '/uploads_penulis/' . $foto);
}

$hash = password_hash($password_raw, PASSWORD_BCRYPT);

$stmt = $koneksi->prepare("INSERT INTO penulis (nama_depan, nama_belakang, user_name, password, foto) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param('sssss', $nama_depan, $nama_belakang, $user_name, $hash, $foto);

if ($stmt->execute()) {
    echo json_encode(['status' => 'ok', 'pesan' => 'Data penulis berhasil disimpan']);
} else {
    // Duplicate username check
    if ($koneksi->errno === 1062) {
        echo json_encode(['status' => 'error', 'pesan' => 'Username sudah digunakan']);
    } else {
        echo json_encode(['status' => 'error', 'pesan' => 'Gagal menyimpan data: ' . $koneksi->error]);
    }
}

$stmt->close();
$koneksi->close();
