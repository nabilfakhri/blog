-- ============================================================
-- Sistem Manajemen Blog (CMS) - db_blog
-- Ekspor database untuk UTS Pemrograman Web
-- Semester Genap 2025/2026
-- ============================================================

-- Membuat database
CREATE DATABASE IF NOT EXISTS db_blog
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

-- Menggunakan database
USE db_blog;

-- ─── Tabel penulis ───────────────────────────────────────
CREATE TABLE penulis (
  id            INT          NOT NULL AUTO_INCREMENT,
  nama_depan    VARCHAR(100) NOT NULL,
  nama_belakang VARCHAR(100) NOT NULL,
  user_name     VARCHAR(50)  NOT NULL,
  password      VARCHAR(255) NOT NULL,
  foto          VARCHAR(255) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_user_name (user_name)
) ENGINE=InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- ─── Tabel kategori_artikel ──────────────────────────────
CREATE TABLE kategori_artikel (
  id             INT          NOT NULL AUTO_INCREMENT,
  nama_kategori  VARCHAR(100) NOT NULL,
  keterangan     TEXT,
  PRIMARY KEY (id),
  UNIQUE KEY uq_nama_kategori (nama_kategori)
) ENGINE=InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- ─── Tabel artikel ───────────────────────────────────────
CREATE TABLE artikel (
  id           INT          NOT NULL AUTO_INCREMENT,
  id_penulis   INT          NOT NULL,
  id_kategori  INT          NOT NULL,
  judul        VARCHAR(255) NOT NULL,
  isi          TEXT         NOT NULL,
  gambar       VARCHAR(255) NOT NULL,
  hari_tanggal VARCHAR(50)  NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_artikel_penulis
    FOREIGN KEY (id_penulis)
    REFERENCES penulis (id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT fk_artikel_kategori
    FOREIGN KEY (id_kategori)
    REFERENCES kategori_artikel (id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- ─── Data contoh penulis ─────────────────────────────────
-- Password semua: password123 (di-hash dengan bcrypt)
INSERT INTO penulis (nama_depan, nama_belakang, user_name, password, foto) VALUES
('Ahmad',  'Fauzi',   'ahmad_f', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', 'default.png'),
('Siti',   'Rahayu',  'siti_r',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', 'default.png'),
('Budi',   'Santoso', 'budi_s',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', 'default.png');

-- ─── Data contoh kategori ────────────────────────────────
INSERT INTO kategori_artikel (nama_kategori, keterangan) VALUES
('Tutorial',   'Artikel panduan langkah demi langkah'),
('Database',   'Artikel seputar pengelolaan database'),
('Web Design', 'Artikel tentang desain antarmuka web');

-- ─── Data contoh artikel ─────────────────────────────────
INSERT INTO artikel (id_penulis, id_kategori, judul, isi, gambar, hari_tanggal) VALUES
(1, 1, 'Pengenalan PHP 8', 'PHP 8 membawa banyak fitur baru yang menarik...', 'default_artikel.jpg', 'Senin, 13 April 2026 | 09:00'),
(2, 2, 'Belajar MySQL Dasar', 'MySQL adalah sistem manajemen database relasional...', 'default_artikel2.jpg', 'Selasa, 14 April 2026 | 10:30');
