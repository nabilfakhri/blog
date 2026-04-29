# 📋 Sistem Manajemen Blog (CMS)

Aplikasi **Content Management System (CMS)** berbasis PHP dan MySQL untuk mengelola artikel blog, penulis, dan kategori artikel. Dibangun menggunakan PHP native dengan MySQL sebagai database, tanpa framework tambahan.

> 📚 Tugas UTS Pemrograman Web — Semester Genap 2025/2026

---

## 📁 Struktur Proyek

```
blog/
├── index.php                  # Halaman utama (frontend SPA)
├── koneksi.php                # Konfigurasi koneksi database
├── db_blog.sql                # File SQL untuk membuat database
│
├── uploads_artikel/           # Folder penyimpanan gambar artikel
│   └── .htaccess
├── uploads_penulis/           # Folder penyimpanan foto penulis
│   ├── .htaccess
│   └── default.png            # Foto profil default
│
│── Penulis (Author)
├── ambil_penulis.php          # GET  - Ambil semua data penulis
├── ambil_satu_penulis.php     # GET  - Ambil satu penulis by ID
├── simpan_penulis.php         # POST - Tambah penulis baru
├── update_penulis.php         # POST - Update data penulis
├── hapus_penulis.php          # POST - Hapus penulis
│
│── Artikel
├── ambil_artikel.php          # GET  - Ambil semua artikel (JOIN)
├── ambil_satu_artikel.php     # GET  - Ambil satu artikel by ID
├── simpan_artikel.php         # POST - Tambah artikel baru
├── update_artikel.php         # POST - Update artikel
├── hapus_artikel.php          # POST - Hapus artikel
│
│── Kategori
├── ambil_kategori.php         # GET  - Ambil semua kategori
├── ambil_satu_kategori.php    # GET  - Ambil satu kategori by ID
├── simpan_kategori.php        # POST - Tambah kategori baru
├── update_kategori.php        # POST - Update kategori
└── hapus_kategori.php         # POST - Hapus kategori
```

---

## 🗄️ Skema Database

Database: `db_blog`

### Tabel `penulis`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | INT AUTO_INCREMENT | Primary Key |
| `nama_depan` | VARCHAR(100) | Nama depan penulis |
| `nama_belakang` | VARCHAR(100) | Nama belakang penulis |
| `user_name` | VARCHAR(50) UNIQUE | Username login |
| `password` | VARCHAR(255) | Password ter-hash (bcrypt) |
| `foto` | VARCHAR(255) | Nama file foto profil |

### Tabel `kategori_artikel`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | INT AUTO_INCREMENT | Primary Key |
| `nama_kategori` | VARCHAR(100) UNIQUE | Nama kategori |
| `keterangan` | TEXT | Deskripsi kategori |

### Tabel `artikel`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | INT AUTO_INCREMENT | Primary Key |
| `id_penulis` | INT | Foreign Key → `penulis.id` |
| `id_kategori` | INT | Foreign Key → `kategori_artikel.id` |
| `judul` | VARCHAR(255) | Judul artikel |
| `isi` | TEXT | Isi artikel |
| `gambar` | VARCHAR(255) | Nama file gambar artikel |
| `hari_tanggal` | VARCHAR(50) | Tanggal publikasi (otomatis) |

**Relasi:** `artikel` → `penulis` (RESTRICT) dan `artikel` → `kategori_artikel` (RESTRICT)

---

## 🚀 Cara Instalasi

### Prasyarat
- [XAMPP](https://www.apachefriends.org/) (PHP 8.x + MySQL/MariaDB)
- Browser modern

### Langkah Instalasi

**1. Clone repositori**
```bash
git clone https://github.com/username/nama-repo.git
```
Letakkan folder di dalam `htdocs` XAMPP:
```
C:/xampp/htdocs/blog/        # Windows
/Applications/XAMPP/htdocs/blog/  # macOS
```

**2. Buat database**

Buka phpMyAdmin → Import → pilih file `db_blog.sql`

Atau lewat terminal MySQL:
```sql
source /path/ke/db_blog.sql
```

**3. Konfigurasi koneksi**

Edit file `koneksi.php` sesuaikan dengan konfigurasi MySQL kamu:
```php
$host     = 'localhost';
$user     = 'root';
$password = '';          // sesuaikan jika ada password
$database = 'db_blog';
```

**4. Buat folder upload** (jika belum ada)
```bash
mkdir uploads_penulis
mkdir uploads_artikel
```

Untuk macOS/Linux, pastikan folder bisa ditulis:
```bash
chmod 755 uploads_penulis uploads_artikel
```

**5. Jalankan aplikasi**

Pastikan Apache dan MySQL sudah aktif di XAMPP, lalu buka browser:
```
http://localhost/blog/
```

---

## 📡 Dokumentasi API

Semua endpoint mengembalikan response dalam format **JSON**.

### 👤 Penulis

#### Ambil Semua Penulis
```
GET ambil_penulis.php
```
Response:
```json
{
  "status": "ok",
  "data": [
    {
      "id": 1,
      "nama_depan": "Ahmad",
      "nama_belakang": "Fauzi",
      "user_name": "ahmad_f",
      "password": "$2y$10$...",
      "foto": "default.png"
    }
  ]
}
```

#### Ambil Satu Penulis
```
GET ambil_satu_penulis.php?id={id}
```

#### Tambah Penulis
```
POST simpan_penulis.php
```
| Field | Tipe | Wajib | Keterangan |
|-------|------|-------|------------|
| `nama_depan` | string | ✅ | Nama depan |
| `nama_belakang` | string | ✅ | Nama belakang |
| `user_name` | string | ✅ | Username (unik) |
| `password` | string | ✅ | Password (akan di-hash bcrypt) |
| `foto` | file | ❌ | JPG/PNG/GIF/WebP, maks 2 MB |

#### Update Penulis
```
POST update_penulis.php
```
| Field | Tipe | Wajib | Keterangan |
|-------|------|-------|------------|
| `id` | int | ✅ | ID penulis |
| `nama_depan` | string | ✅ | |
| `nama_belakang` | string | ✅ | |
| `user_name` | string | ✅ | |
| `password` | string | ❌ | Kosongkan jika tidak diganti |
| `foto` | file | ❌ | Kosongkan jika tidak diganti |

#### Hapus Penulis
```
POST hapus_penulis.php
```
| Field | Tipe | Wajib |
|-------|------|-------|
| `id` | int | ✅ |

> ⚠️ Penulis tidak bisa dihapus jika masih memiliki artikel.

---

### 📄 Artikel

#### Ambil Semua Artikel
```
GET ambil_artikel.php
```
Response menyertakan data JOIN dari tabel `penulis` dan `kategori_artikel`.

#### Ambil Satu Artikel
```
GET ambil_satu_artikel.php?id={id}
```

#### Tambah Artikel
```
POST simpan_artikel.php
```
| Field | Tipe | Wajib | Keterangan |
|-------|------|-------|------------|
| `judul` | string | ✅ | Judul artikel |
| `id_penulis` | int | ✅ | ID penulis |
| `id_kategori` | int | ✅ | ID kategori |
| `isi` | string | ✅ | Isi artikel |
| `gambar` | file | ✅ | JPG/PNG/GIF/WebP, maks 2 MB |

> 📅 Field `hari_tanggal` dibuat otomatis oleh server (zona waktu Asia/Jakarta).

#### Update Artikel
```
POST update_artikel.php
```
| Field | Tipe | Wajib | Keterangan |
|-------|------|-------|------------|
| `id` | int | ✅ | ID artikel |
| `judul` | string | ✅ | |
| `id_penulis` | int | ✅ | |
| `id_kategori` | int | ✅ | |
| `isi` | string | ✅ | |
| `gambar` | file | ❌ | Kosongkan jika tidak diganti |

#### Hapus Artikel
```
POST hapus_artikel.php
```
| Field | Tipe | Wajib |
|-------|------|-------|
| `id` | int | ✅ |

---

### 🗂️ Kategori

#### Ambil Semua Kategori
```
GET ambil_kategori.php
```

#### Ambil Satu Kategori
```
GET ambil_satu_kategori.php?id={id}
```

#### Tambah Kategori
```
POST simpan_kategori.php
```
| Field | Tipe | Wajib |
|-------|------|-------|
| `nama_kategori` | string | ✅ |
| `keterangan` | string | ❌ |

#### Update Kategori
```
POST update_kategori.php
```
| Field | Tipe | Wajib |
|-------|------|-------|
| `id` | int | ✅ |
| `nama_kategori` | string | ✅ |
| `keterangan` | string | ❌ |

#### Hapus Kategori
```
POST hapus_kategori.php
```
| Field | Tipe | Wajib |
|-------|------|-------|
| `id` | int | ✅ |

> ⚠️ Kategori tidak bisa dihapus jika masih digunakan oleh artikel.

---

## ✨ Fitur

- Manajemen **Penulis** — tambah, edit, hapus, upload foto profil
- Manajemen **Artikel** — tambah, edit, hapus, upload gambar artikel
- Manajemen **Kategori** — tambah, edit, hapus
- **Single Page Application** — navigasi tanpa reload halaman
- **Upload gambar** dengan validasi tipe file dan ukuran (maks 2 MB)
- **Password di-hash** menggunakan bcrypt
- **Proteksi referensial** — penulis/kategori tidak bisa dihapus jika masih digunakan
- **Keamanan upload** — `.htaccess` mencegah eksekusi PHP di folder upload
- **Prepared Statement** — mencegah SQL Injection

---

## 🔒 Keamanan

- Semua query menggunakan **MySQLi Prepared Statement**
- Password di-hash dengan **`password_hash()` bcrypt**
- Folder `uploads_*` dilindungi `.htaccess` agar file PHP tidak bisa dieksekusi
- Validasi MIME type file upload menggunakan `finfo`
- Validasi ukuran file maksimal 2 MB

---

## 🛠️ Teknologi

| Teknologi | Keterangan |
|-----------|------------|
| PHP 8.x | Backend / API |
| MySQL / MariaDB | Database |
| HTML + CSS + JavaScript | Frontend (Vanilla) |
| Fetch API | Komunikasi AJAX ke backend |
| XAMPP | Web server lokal |

---

## 📝 Lisensi

Proyek ini dibuat untuk keperluan akademik — UTS Pemrograman Web Semester Genap 2025/2026.
