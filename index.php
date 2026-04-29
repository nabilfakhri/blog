<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistem Manajemen Blog (CMS)</title>
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 14px; background: #f0f2f5; color: #333; min-height: 100vh; }

  .header {
    background: #fff;
    border-bottom: 1px solid #e0e0e0;
    padding: 0 24px;
    height: 56px;
    display: flex;
    align-items: center;
    gap: 12px;
    position: fixed;
    top: 0; left: 0; right: 0;
    z-index: 100;
    box-shadow: 0 1px 4px rgba(0,0,0,0.08);
  }
  .header-icon { font-size: 22px; color: #4361ee; }
  .header-title { font-size: 16px; font-weight: 700; color: #1a1a2e; }
  .header-sub { font-size: 12px; color: #888; margin-top: 1px; }

  .layout { display: flex; margin-top: 56px; min-height: calc(100vh - 56px); }

  .sidebar {
    width: 220px;
    background: #fff;
    border-right: 1px solid #e0e0e0;
    padding: 20px 0;
    position: fixed;
    top: 56px; bottom: 0; left: 0;
    overflow-y: auto;
  }
  .sidebar-label { font-size: 11px; font-weight: 700; color: #aaa; text-transform: uppercase; letter-spacing: 1px; padding: 0 20px 8px; }
  .nav-item {
    display: flex; align-items: center; gap: 10px;
    padding: 11px 20px;
    cursor: pointer;
    color: #555;
    font-size: 13.5px;
    border-left: 3px solid transparent;
    transition: all .15s;
  }
  .nav-item:hover { background: #f5f7ff; color: #4361ee; }
  .nav-item.active { background: #eef0ff; color: #4361ee; border-left-color: #4361ee; font-weight: 600; }
  .nav-icon { font-size: 16px; }

  .main { margin-left: 220px; padding: 24px; flex: 1; }

  .section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px; }
  .section-title { font-size: 18px; font-weight: 700; color: #1a1a2e; }

  .btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; border: none; border-radius: 6px;
    font-size: 13px; font-weight: 600; cursor: pointer; transition: all .15s;
    text-decoration: none;
  }
  .btn-primary { background: #4361ee; color: #fff; }
  .btn-primary:hover { background: #3451d1; }
  .btn-success { background: #4cc9a0; color: #fff; }
  .btn-success:hover { background: #3ab88e; }
  .btn-edit { background: #4361ee; color: #fff; padding: 5px 12px; font-size: 12px; border-radius: 4px; }
  .btn-edit:hover { background: #3451d1; }
  .btn-hapus { background: #ef476f; color: #fff; padding: 5px 12px; font-size: 12px; border-radius: 4px; }
  .btn-hapus:hover { background: #d63660; }
  .btn-secondary { background: #e9ecef; color: #555; }
  .btn-secondary:hover { background: #dee2e6; }
  .btn-danger { background: #ef476f; color: #fff; }
  .btn-danger:hover { background: #d63660; }

  .card { background: #fff; border-radius: 10px; box-shadow: 0 1px 6px rgba(0,0,0,0.07); overflow: hidden; }
  table { width: 100%; border-collapse: collapse; }
  thead tr { background: #f8f9fb; }
  th { padding: 12px 14px; text-align: left; font-size: 12px; font-weight: 700; color: #888; text-transform: uppercase; letter-spacing: .5px; border-bottom: 1px solid #eee; }
  td { padding: 12px 14px; border-bottom: 1px solid #f0f0f0; vertical-align: middle; }
  tbody tr:last-child td { border-bottom: none; }
  tbody tr:hover { background: #fafbff; }

  .foto-kecil { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; background: #e0e0e0; }
  .gambar-artikel { width: 56px; height: 40px; border-radius: 4px; object-fit: cover; background: #e0e0e0; }

  .badge {
    display: inline-block; padding: 3px 10px; border-radius: 20px;
    font-size: 12px; font-weight: 600; background: #e8f4fd; color: #2980b9;
  }
  .badge-green { background: #e8f8f0; color: #27ae60; }
  .badge-purple { background: #f3eeff; color: #7c3aed; }

  .aksi-grup { display: flex; gap: 6px; }
  .pw-mask { font-family: monospace; color: #aaa; letter-spacing: 2px; }

  .modal-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,0.45); z-index: 200;
    align-items: center; justify-content: center;
  }
  .modal-overlay.aktif { display: flex; }
  .modal {
    background: #fff; border-radius: 12px;
    width: 100%; max-width: 340px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.2);
    animation: slideIn .2s ease;
    max-height: 90vh; overflow-y: auto;
  }
  @keyframes slideIn { from { transform: translateY(-20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
  .modal-header { padding: 20px 24px 12px; }
  .modal-header h3 { font-size: 17px; font-weight: 700; color: #1a1a2e; }
  .modal-body { padding: 0 24px 8px; }
  .modal-footer { padding: 16px 24px 20px; display: flex; justify-content: flex-end; gap: 10px; }

  .form-row { display: flex; gap: 14px; }
  .form-group { margin-bottom: 14px; flex: 1; }
  .form-group label { display: block; font-size: 13px; font-weight: 600; color: #444; margin-bottom: 5px; }
  .form-group input[type="text"],
  .form-group input[type="password"],
  .form-group input[type="file"],
  .form-group select,
  .form-group textarea {
    width: 100%; padding: 9px 12px;
    border: 1px solid #ddd; border-radius: 6px;
    font-size: 13.5px; color: #333;
    transition: border-color .15s;
    outline: none; background: #fff;
  }
  .form-group input:focus,
  .form-group select:focus,
  .form-group textarea:focus { border-color: #4361ee; box-shadow: 0 0 0 3px rgba(67,97,238,.1); }
  .form-group textarea { resize: vertical; min-height: 90px; }
  .form-hint { font-size: 11px; color: #aaa; margin-top: 3px; }

  .modal-hapus { max-width: 340px; text-align: center; padding: 32px 24px; }
  .modal-hapus .icon-hapus { font-size: 40px; color: #ef476f; margin-bottom: 12px; }
  .modal-hapus h3 { font-size: 16px; font-weight: 700; margin-bottom: 6px; }
  .modal-hapus p { font-size: 13px; color: #888; margin-bottom: 20px; }
  .modal-hapus .hapus-footer { display: flex; justify-content: center; gap: 12px; }

  #toast {
    position: fixed; bottom: 28px; right: 28px;
    background: #1a1a2e; color: #fff;
    padding: 12px 20px; border-radius: 8px;
    font-size: 13.5px; font-weight: 500;
    box-shadow: 0 4px 16px rgba(0,0,0,0.2);
    z-index: 999; display: none;
    animation: fadeInUp .25s ease;
  }
  #toast.error { background: #ef476f; }
  #toast.success { background: #06d6a0; }
  @keyframes fadeInUp { from { transform: translateY(10px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

  .loading-row td { text-align: center; padding: 32px; color: #aaa; }
</style>
</head>
<body>

<div class="header">
  <div class="header-icon">📋</div>
  <div>
    <div class="header-title">Sistem Manajemen Blog (CMS)</div>
    <div class="header-sub">Blognya Nabil Fakhri</div>
  </div>
</div>

<div class="layout">

  <nav class="sidebar">
    <div class="sidebar-label">Menu Utama</div>
    <div class="nav-item active" data-menu="penulis" onclick="gantiMenu('penulis', this)">
      <span class="nav-icon">👤</span> Kelola Penulis
    </div>
    <div class="nav-item" data-menu="artikel" onclick="gantiMenu('artikel', this)">
      <span class="nav-icon">📄</span> Kelola Artikel
    </div>
    <div class="nav-item" data-menu="kategori" onclick="gantiMenu('kategori', this)">
      <span class="nav-icon">🗂️</span> Kelola Kategori
    </div>
  </nav>

  <main class="main">

    <div id="panel-penulis">
      <div class="section-header">
        <div class="section-title">Data Penulis</div>
        <button class="btn btn-primary" onclick="bukaModalTambahPenulis()">+ Tambah Penulis</button>
      </div>
      <div class="card">
        <table>
          <thead>
            <tr>
              <th>Foto</th>
              <th>Nama</th>
              <th>Username</th>
              <th>Password</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody id="tbody-penulis">
            <tr class="loading-row"><td colspan="5">Memuat data…</td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <div id="panel-artikel" style="display:none">
      <div class="section-header">
        <div class="section-title">Data Artikel</div>
        <button class="btn btn-primary" onclick="bukaModalTambahArtikel()">+ Tambah Artikel</button>
      </div>
      <div class="card">
        <table>
          <thead>
            <tr>
              <th>Gambar</th>
              <th>Judul</th>
              <th>Kategori</th>
              <th>Penulis</th>
              <th>Tanggal</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody id="tbody-artikel">
            <tr class="loading-row"><td colspan="6">Memuat data…</td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <div id="panel-kategori" style="display:none">
      <div class="section-header">
        <div class="section-title">Data Kategori Artikel</div>
        <button class="btn btn-primary" onclick="bukaModalTambahKategori()">+ Tambah Kategori</button>
      </div>
      <div class="card">
        <table>
          <thead>
            <tr>
              <th>Nama Kategori</th>
              <th>Keterangan</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody id="tbody-kategori">
            <tr class="loading-row"><td colspan="3">Memuat data…</td></tr>
          </tbody>
        </table>
      </div>
    </div>

  </main>
</div>

<div class="modal-overlay" id="modal-tambah-penulis">
  <div class="modal">
    <div class="modal-header"><h3>Tambah Penulis</h3></div>
    <div class="modal-body">
      <div class="form-row">
        <div class="form-group">
          <label>Nama Depan</label>
          <input type="text" id="tp-nama-depan" placeholder="Ahmad">
        </div>
        <div class="form-group">
          <label>Nama Belakang</label>
          <input type="text" id="tp-nama-belakang" placeholder="Fauzi">
        </div>
      </div>
      <div class="form-group">
        <label>Username</label>
        <input type="text" id="tp-username" placeholder="ahmad_f">
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" id="tp-password" placeholder="••••••••••••••••">
      </div>
      <div class="form-group">
        <label>Foto Profil</label>
        <input type="file" id="tp-foto" accept="image/*">
        <div class="form-hint">Format: JPG, PNG, GIF, WebP. Maks 2 MB. (Opsional)</div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="tutupModal('modal-tambah-penulis')">Batal</button>
      <button class="btn btn-primary" onclick="simpanPenulis()">Simpan Data</button>
    </div>
  </div>
</div>

<!-- MODAL PENULIS - EDIT -->
<div class="modal-overlay" id="modal-edit-penulis">
  <div class="modal">
    <div class="modal-header"><h3>Edit Penulis</h3></div>
    <div class="modal-body">
      <input type="hidden" id="ep-id">
      <div class="form-row">
        <div class="form-group">
          <label>Nama Depan</label>
          <input type="text" id="ep-nama-depan">
        </div>
        <div class="form-group">
          <label>Nama Belakang</label>
          <input type="text" id="ep-nama-belakang">
        </div>
      </div>
      <div class="form-group">
        <label>Username</label>
        <input type="text" id="ep-username">
      </div>
      <div class="form-group">
        <label>Password Baru <span style="font-weight:400;color:#aaa">(kosongkan jika tidak diganti)</span></label>
        <input type="password" id="ep-password" placeholder="••••••••••••••••">
      </div>
      <div class="form-group">
        <label>Foto Profil <span style="font-weight:400;color:#aaa">(kosongkan jika tidak diganti)</span></label>
        <input type="file" id="ep-foto" accept="image/*">
        <div class="form-hint">Format: JPG, PNG, GIF, WebP. Maks 2 MB.</div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="tutupModal('modal-edit-penulis')">Batal</button>
      <button class="btn btn-primary" onclick="updatePenulis()">Simpan Perubahan</button>
    </div>
  </div>
</div>

<!-- MODAL HAPUS PENULIS -->
<div class="modal-overlay" id="modal-hapus-penulis">
  <div class="modal">
    <div class="modal-hapus">
      <div class="icon-hapus">🗑️</div>
      <h3>Hapus data ini?</h3>
      <p>Data yang dihapus tidak dapat dikembalikan.</p>
      <input type="hidden" id="hapus-penulis-id">
      <div class="hapus-footer">
        <button class="btn btn-secondary" onclick="tutupModal('modal-hapus-penulis')">Batal</button>
        <button class="btn btn-danger" onclick="konfirmasiHapusPenulis()">Ya, Hapus</button>
      </div>
    </div>
  </div>
</div>

<div class="modal-overlay" id="modal-tambah-artikel">
  <div class="modal">
    <div class="modal-header"><h3>Tambah Artikel</h3></div>
    <div class="modal-body">
      <div class="form-group">
        <label>Judul</label>
        <input type="text" id="ta-judul" placeholder="Judul artikel...">
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Penulis</label>
          <select id="ta-penulis"></select>
        </div>
        <div class="form-group">
          <label>Kategori</label>
          <select id="ta-kategori"></select>
        </div>
      </div>
      <div class="form-group">
        <label>Isi Artikel</label>
        <textarea id="ta-isi" placeholder="Tulis isi artikel di sini..." style="min-height:110px"></textarea>
      </div>
      <div class="form-group">
        <label>Gambar</label>
        <input type="file" id="ta-gambar" accept="image/*">
        <div class="form-hint">Format: JPG, PNG, GIF, WebP. Maks 2 MB. (Wajib)</div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="tutupModal('modal-tambah-artikel')">Batal</button>
      <button class="btn btn-primary" onclick="simpanArtikel()">Simpan Data</button>
    </div>
  </div>
</div>

<!-- MODAL ARTIKEL - EDIT -->
<div class="modal-overlay" id="modal-edit-artikel">
  <div class="modal">
    <div class="modal-header"><h3>Edit Artikel</h3></div>
    <div class="modal-body">
      <input type="hidden" id="ea-id">
      <div class="form-group">
        <label>Judul</label>
        <input type="text" id="ea-judul">
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Penulis</label>
          <select id="ea-penulis"></select>
        </div>
        <div class="form-group">
          <label>Kategori</label>
          <select id="ea-kategori"></select>
        </div>
      </div>
      <div class="form-group">
        <label>Isi Artikel</label>
        <textarea id="ea-isi" style="min-height:110px"></textarea>
      </div>
      <div class="form-group">
        <label>Gambar <span style="font-weight:400;color:#aaa">(kosongkan jika tidak diganti)</span></label>
        <input type="file" id="ea-gambar" accept="image/*">
        <div class="form-hint">Format: JPG, PNG, GIF, WebP. Maks 2 MB.</div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="tutupModal('modal-edit-artikel')">Batal</button>
      <button class="btn btn-primary" onclick="updateArtikel()">Simpan Perubahan</button>
    </div>
  </div>
</div>

<!-- MODAL HAPUS ARTIKEL -->
<div class="modal-overlay" id="modal-hapus-artikel">
  <div class="modal">
    <div class="modal-hapus">
      <div class="icon-hapus">🗑️</div>
      <h3>Hapus data ini?</h3>
      <p>Data yang dihapus tidak dapat dikembalikan.</p>
      <input type="hidden" id="hapus-artikel-id">
      <div class="hapus-footer">
        <button class="btn btn-secondary" onclick="tutupModal('modal-hapus-artikel')">Batal</button>
        <button class="btn btn-danger" onclick="konfirmasiHapusArtikel()">Ya, Hapus</button>
      </div>
    </div>
  </div>
</div>

<div class="modal-overlay" id="modal-tambah-kategori">
  <div class="modal">
    <div class="modal-header"><h3>Tambah Kategori</h3></div>
    <div class="modal-body">
      <div class="form-group">
        <label>Nama Kategori</label>
        <input type="text" id="tk-nama" placeholder="Nama kategori...">
      </div>
      <div class="form-group">
        <label>Keterangan</label>
        <textarea id="tk-keterangan" placeholder="Deskripsi kategori..."></textarea>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="tutupModal('modal-tambah-kategori')">Batal</button>
      <button class="btn btn-primary" onclick="simpanKategori()">Simpan Data</button>
    </div>
  </div>
</div>

<!-- MODAL KATEGORI - EDIT -->
<div class="modal-overlay" id="modal-edit-kategori">
  <div class="modal">
    <div class="modal-header"><h3>Edit Kategori</h3></div>
    <div class="modal-body">
      <input type="hidden" id="ek-id">
      <div class="form-group">
        <label>Nama Kategori</label>
        <input type="text" id="ek-nama">
      </div>
      <div class="form-group">
        <label>Keterangan</label>
        <textarea id="ek-keterangan"></textarea>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="tutupModal('modal-edit-kategori')">Batal</button>
      <button class="btn btn-primary" onclick="updateKategori()">Simpan Perubahan</button>
    </div>
  </div>
</div>

<!-- MODAL HAPUS KATEGORI -->
<div class="modal-overlay" id="modal-hapus-kategori">
  <div class="modal">
    <div class="modal-hapus">
      <div class="icon-hapus">🗑️</div>
      <h3>Hapus data ini?</h3>
      <p>Data yang dihapus tidak dapat dikembalikan.</p>
      <input type="hidden" id="hapus-kategori-id">
      <div class="hapus-footer">
        <button class="btn btn-secondary" onclick="tutupModal('modal-hapus-kategori')">Batal</button>
        <button class="btn btn-danger" onclick="konfirmasiHapusKategori()">Ya, Hapus</button>
      </div>
    </div>
  </div>
</div>

<!-- TOAST -->
<div id="toast"></div>


<script>
function esc(str) {
  return String(str ?? '')
    .replace(/&/g,'&amp;').replace(/</g,'&lt;')
    .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function tampilkanToast(pesan, tipe = 'success') {
  const t = document.getElementById('toast');
  t.textContent = pesan;
  t.className = tipe;
  t.style.display = 'block';
  setTimeout(() => { t.style.display = 'none'; }, 3000);
}

function bukaModal(id) {
  document.getElementById(id).classList.add('aktif');
}
function tutupModal(id) {
  document.getElementById(id).classList.remove('aktif');
}

// Tutup modal jika klik overlay
document.querySelectorAll('.modal-overlay').forEach(el => {
  el.addEventListener('click', function(e) {
    if (e.target === this) this.classList.remove('aktif');
  });
});

function gantiMenu(menu, el) {
  document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
  el.classList.add('active');
  ['penulis','artikel','kategori'].forEach(p => {
    document.getElementById('panel-' + p).style.display = p === menu ? '' : 'none';
  });
  if (menu === 'penulis') muatPenulis();
  if (menu === 'artikel') muatArtikel();
  if (menu === 'kategori') muatKategori();
}

function muatPenulis() {
  fetch('ambil_penulis.php')
    .then(r => r.json())
    .then(res => {
      const tbody = document.getElementById('tbody-penulis');
      if (res.status !== 'ok' || !res.data.length) {
        tbody.innerHTML = '<tr class="loading-row"><td colspan="5">Belum ada data penulis.</td></tr>';
        return;
      }
      tbody.innerHTML = res.data.map(p => `
        <tr>
          <td><img src="uploads_penulis/${esc(p.foto)}" class="foto-kecil" onerror="this.src='uploads_penulis/default.png'"></td>
          <td>${esc(p.nama_depan)} ${esc(p.nama_belakang)}</td>
          <td>${esc(p.user_name)}</td>
          <td><span class="pw-mask">${esc(p.password).substring(0,20)}…</span></td>
          <td>
            <div class="aksi-grup">
              <button class="btn btn-edit" onclick="bukaEditPenulis(${p.id})">Edit</button>
              <button class="btn btn-hapus" onclick="bukaHapusPenulis(${p.id})">Hapus</button>
            </div>
          </td>
        </tr>`).join('');
    })
    .catch(() => tampilkanToast('Gagal memuat data penulis', 'error'));
}

function bukaModalTambahPenulis() {
  document.getElementById('tp-nama-depan').value = '';
  document.getElementById('tp-nama-belakang').value = '';
  document.getElementById('tp-username').value = '';
  document.getElementById('tp-password').value = '';
  document.getElementById('tp-foto').value = '';
  bukaModal('modal-tambah-penulis');
}

function simpanPenulis() {
  const fd = new FormData();
  fd.append('nama_depan',    document.getElementById('tp-nama-depan').value.trim());
  fd.append('nama_belakang', document.getElementById('tp-nama-belakang').value.trim());
  fd.append('user_name',     document.getElementById('tp-username').value.trim());
  fd.append('password',      document.getElementById('tp-password').value);
  const foto = document.getElementById('tp-foto').files[0];
  if (foto) fd.append('foto', foto);

  fetch('simpan_penulis.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(res => {
      if (res.status === 'ok') {
        tutupModal('modal-tambah-penulis');
        tampilkanToast(res.pesan, 'success');
        muatPenulis();
      } else {
        tampilkanToast(res.pesan, 'error');
      }
    })
    .catch(() => tampilkanToast('Terjadi kesalahan', 'error'));
}

function bukaEditPenulis(id) {
  fetch('ambil_satu_penulis.php?id=' + id)
    .then(r => r.json())
    .then(res => {
      if (res.status !== 'ok') { tampilkanToast(res.pesan, 'error'); return; }
      const d = res.data;
      document.getElementById('ep-id').value = d.id;
      document.getElementById('ep-nama-depan').value = d.nama_depan;
      document.getElementById('ep-nama-belakang').value = d.nama_belakang;
      document.getElementById('ep-username').value = d.user_name;
      document.getElementById('ep-password').value = '';
      document.getElementById('ep-foto').value = '';
      bukaModal('modal-edit-penulis');
    })
    .catch(() => tampilkanToast('Gagal memuat data', 'error'));
}

function updatePenulis() {
  const fd = new FormData();
  fd.append('id',            document.getElementById('ep-id').value);
  fd.append('nama_depan',    document.getElementById('ep-nama-depan').value.trim());
  fd.append('nama_belakang', document.getElementById('ep-nama-belakang').value.trim());
  fd.append('user_name',     document.getElementById('ep-username').value.trim());
  fd.append('password',      document.getElementById('ep-password').value);
  const foto = document.getElementById('ep-foto').files[0];
  if (foto) fd.append('foto', foto);

  fetch('update_penulis.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(res => {
      if (res.status === 'ok') {
        tutupModal('modal-edit-penulis');
        tampilkanToast(res.pesan, 'success');
        muatPenulis();
      } else {
        tampilkanToast(res.pesan, 'error');
      }
    })
    .catch(() => tampilkanToast('Terjadi kesalahan', 'error'));
}

function bukaHapusPenulis(id) {
  document.getElementById('hapus-penulis-id').value = id;
  bukaModal('modal-hapus-penulis');
}
function konfirmasiHapusPenulis() {
  const fd = new FormData();
  fd.append('id', document.getElementById('hapus-penulis-id').value);
  fetch('hapus_penulis.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(res => {
      tutupModal('modal-hapus-penulis');
      tampilkanToast(res.pesan, res.status === 'ok' ? 'success' : 'error');
      if (res.status === 'ok') muatPenulis();
    })
    .catch(() => tampilkanToast('Terjadi kesalahan', 'error'));
}

function muatKategori() {
  fetch('ambil_kategori.php')
    .then(r => r.json())
    .then(res => {
      const tbody = document.getElementById('tbody-kategori');
      if (res.status !== 'ok' || !res.data.length) {
        tbody.innerHTML = '<tr class="loading-row"><td colspan="3">Belum ada data kategori.</td></tr>';
        return;
      }
      tbody.innerHTML = res.data.map(k => `
        <tr>
          <td><span class="badge">${esc(k.nama_kategori)}</span></td>
          <td>${esc(k.keterangan)}</td>
          <td>
            <div class="aksi-grup">
              <button class="btn btn-edit" onclick="bukaEditKategori(${k.id})">Edit</button>
              <button class="btn btn-hapus" onclick="bukaHapusKategori(${k.id})">Hapus</button>
            </div>
          </td>
        </tr>`).join('');
    })
    .catch(() => tampilkanToast('Gagal memuat kategori', 'error'));
}

function bukaModalTambahKategori() {
  document.getElementById('tk-nama').value = '';
  document.getElementById('tk-keterangan').value = '';
  bukaModal('modal-tambah-kategori');
}

function simpanKategori() {
  const fd = new FormData();
  fd.append('nama_kategori', document.getElementById('tk-nama').value.trim());
  fd.append('keterangan',    document.getElementById('tk-keterangan').value.trim());
  fetch('simpan_kategori.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(res => {
      if (res.status === 'ok') {
        tutupModal('modal-tambah-kategori');
        tampilkanToast(res.pesan, 'success');
        muatKategori();
      } else {
        tampilkanToast(res.pesan, 'error');
      }
    })
    .catch(() => tampilkanToast('Terjadi kesalahan', 'error'));
}

function bukaEditKategori(id) {
  fetch('ambil_satu_kategori.php?id=' + id)
    .then(r => r.json())
    .then(res => {
      if (res.status !== 'ok') { tampilkanToast(res.pesan, 'error'); return; }
      document.getElementById('ek-id').value = res.data.id;
      document.getElementById('ek-nama').value = res.data.nama_kategori;
      document.getElementById('ek-keterangan').value = res.data.keterangan;
      bukaModal('modal-edit-kategori');
    });
}

function updateKategori() {
  const fd = new FormData();
  fd.append('id',            document.getElementById('ek-id').value);
  fd.append('nama_kategori', document.getElementById('ek-nama').value.trim());
  fd.append('keterangan',    document.getElementById('ek-keterangan').value.trim());
  fetch('update_kategori.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(res => {
      if (res.status === 'ok') {
        tutupModal('modal-edit-kategori');
        tampilkanToast(res.pesan, 'success');
        muatKategori();
      } else {
        tampilkanToast(res.pesan, 'error');
      }
    });
}

function bukaHapusKategori(id) {
  document.getElementById('hapus-kategori-id').value = id;
  bukaModal('modal-hapus-kategori');
}
function konfirmasiHapusKategori() {
  const fd = new FormData();
  fd.append('id', document.getElementById('hapus-kategori-id').value);
  fetch('hapus_kategori.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(res => {
      tutupModal('modal-hapus-kategori');
      tampilkanToast(res.pesan, res.status === 'ok' ? 'success' : 'error');
      if (res.status === 'ok') muatKategori();
    });
}

function muatArtikel() {
  fetch('ambil_artikel.php')
    .then(r => r.json())
    .then(res => {
      const tbody = document.getElementById('tbody-artikel');
      if (res.status !== 'ok' || !res.data.length) {
        tbody.innerHTML = '<tr class="loading-row"><td colspan="6">Belum ada data artikel.</td></tr>';
        return;
      }
      tbody.innerHTML = res.data.map(a => `
        <tr>
          <td><img src="uploads_artikel/${esc(a.gambar)}" class="gambar-artikel" onerror="this.src='uploads_penulis/default.png'"></td>
          <td>${esc(a.judul)}</td>
          <td><span class="badge badge-purple">${esc(a.nama_kategori)}</span></td>
          <td>${esc(a.nama_depan)} ${esc(a.nama_belakang)}</td>
          <td style="font-size:12px;color:#888">${esc(a.hari_tanggal)}</td>
          <td>
            <div class="aksi-grup">
              <button class="btn btn-edit" onclick="bukaEditArtikel(${a.id})">Edit</button>
              <button class="btn btn-hapus" onclick="bukaHapusArtikel(${a.id})">Hapus</button>
            </div>
          </td>
        </tr>`).join('');
    })
    .catch(() => tampilkanToast('Gagal memuat artikel', 'error'));
}

function isiDropdownPenulisKategori(selectPenuliId, selectKategoriId, pilihPenulis = null, pilihKategori = null) {
  return Promise.all([
    fetch('ambil_penulis.php').then(r => r.json()),
    fetch('ambil_kategori.php').then(r => r.json())
  ]).then(([resPenulis, resKategori]) => {
    const selP = document.getElementById(selectPenuliId);
    const selK = document.getElementById(selectKategoriId);
    selP.innerHTML = resPenulis.data.map(p =>
      `<option value="${p.id}" ${pilihPenulis == p.id ? 'selected' : ''}>${esc(p.nama_depan)} ${esc(p.nama_belakang)}</option>`
    ).join('');
    selK.innerHTML = resKategori.data.map(k =>
      `<option value="${k.id}" ${pilihKategori == k.id ? 'selected' : ''}>${esc(k.nama_kategori)}</option>`
    ).join('');
  });
}

function bukaModalTambahArtikel() {
  document.getElementById('ta-judul').value = '';
  document.getElementById('ta-isi').value = '';
  document.getElementById('ta-gambar').value = '';
  isiDropdownPenulisKategori('ta-penulis','ta-kategori');
  bukaModal('modal-tambah-artikel');
}

function simpanArtikel() {
  const fd = new FormData();
  fd.append('judul',       document.getElementById('ta-judul').value.trim());
  fd.append('id_penulis',  document.getElementById('ta-penulis').value);
  fd.append('id_kategori', document.getElementById('ta-kategori').value);
  fd.append('isi',         document.getElementById('ta-isi').value.trim());
  const gambar = document.getElementById('ta-gambar').files[0];
  if (gambar) fd.append('gambar', gambar);

  fetch('simpan_artikel.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(res => {
      if (res.status === 'ok') {
        tutupModal('modal-tambah-artikel');
        tampilkanToast(res.pesan, 'success');
        muatArtikel();
      } else {
        tampilkanToast(res.pesan, 'error');
      }
    })
    .catch(() => tampilkanToast('Terjadi kesalahan', 'error'));
}

function bukaEditArtikel(id) {
  fetch('ambil_satu_artikel.php?id=' + id)
    .then(r => r.json())
    .then(res => {
      if (res.status !== 'ok') { tampilkanToast(res.pesan, 'error'); return; }
      const d = res.data;
      document.getElementById('ea-id').value = d.id;
      document.getElementById('ea-judul').value = d.judul;
      document.getElementById('ea-isi').value = d.isi;
      document.getElementById('ea-gambar').value = '';
      isiDropdownPenulisKategori('ea-penulis','ea-kategori', d.id_penulis, d.id_kategori)
        .then(() => bukaModal('modal-edit-artikel'));
    });
}

function updateArtikel() {
  const fd = new FormData();
  fd.append('id',          document.getElementById('ea-id').value);
  fd.append('judul',       document.getElementById('ea-judul').value.trim());
  fd.append('id_penulis',  document.getElementById('ea-penulis').value);
  fd.append('id_kategori', document.getElementById('ea-kategori').value);
  fd.append('isi',         document.getElementById('ea-isi').value.trim());
  const gambar = document.getElementById('ea-gambar').files[0];
  if (gambar) fd.append('gambar', gambar);

  fetch('update_artikel.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(res => {
      if (res.status === 'ok') {
        tutupModal('modal-edit-artikel');
        tampilkanToast(res.pesan, 'success');
        muatArtikel();
      } else {
        tampilkanToast(res.pesan, 'error');
      }
    });
}

function bukaHapusArtikel(id) {
  document.getElementById('hapus-artikel-id').value = id;
  bukaModal('modal-hapus-artikel');
}
function konfirmasiHapusArtikel() {
  const fd = new FormData();
  fd.append('id', document.getElementById('hapus-artikel-id').value);
  fetch('hapus_artikel.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(res => {
      tutupModal('modal-hapus-artikel');
      tampilkanToast(res.pesan, res.status === 'ok' ? 'success' : 'error');
      if (res.status === 'ok') muatArtikel();
    });
}

muatPenulis();
</script>
</body>
</html>
