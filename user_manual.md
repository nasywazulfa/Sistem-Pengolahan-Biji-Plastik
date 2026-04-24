# USER MANUAL - SISTEM INFORMASI PENGOLAHAN LIMBAH PLASTIK

## DAFTAR ISI

1. [Pendahuluan](#pendahuluan)
2. [Cara Login](#cara-login)
3. [Dashboard Penyortir](#dashboard-penyortir)
4. [Dashboard Sopir](#dashboard-sopir)
5. [Dashboard Operator](#dashboard-operator)
6. [Dashboard Kepala Produksi](#dashboard-kepala-produksi)
7. [Manajemen Data](#manajemen-data)
8. [Laporan](#laporan)
9. [Troubleshooting](#troubleshooting)

---

## 1. PENDAHULUAN

### 1.1 Tentang Sistem

Sistem Informasi Pengolahan Limbah Plastik adalah aplikasi web untuk mengelola seluruh proses operasional daur ulang plastik, mulai dari:
- Penerimaan bahan baku
- Proses sortir
- Pengiriman ke gudang
- Proses produksi biji plastik
- Distribusi ke pelanggan

### 1.2 Peran Pengguna

| Role | Tugas Utama |
|------|-------------|
| **Penyortir** | Mencatat hasil sortir plastik |
| **Sopir** | Mencatat data pengiriman |
| **Operator Mesin** | Mencatat hasil produksi dan kondisi mesin |
| **Kepala Produksi** | Mengawasi seluruh proses, koreksi data, laporan |

---

## 2. CARA LOGIN

### 2.1 Halaman Login

1. Buka browser (Chrome/Firefox/Edge)
2. Akses URL: `http://localhost/sistem-plastik/login.php`
3. Tampilan halaman login akan muncul

### 2.2 Mengisi Form Login

| Field | Deskripsi |
|-------|-----------|
| Username | Nama pengguna yang sudah terdaftar |
| Password | Kata sandi pengguna |

### 2.3 Akun Demo

| Role | Username | Password |
|------|----------|----------|
| Penyortir | penyortir1 | password |
| Sopir | sopir1 | password |
| Operator | operator1 | password |
| Kepala Produksi | kepala1 | password |

### 2.4 Setelah Login

Setelah berhasil login, sistem akan mengarahkan ke **dashboard** sesuai role masing-masing.

---

## 3. DASHBOARD PENYORTIR

### 3.1 Tampilan Dashboard

Dashboard penyortir menampilkan:
- **Total Sortir** - Jumlah seluruh sortir yang pernah dilakukan
- **Sortir Hari Ini** - Jumlah sortir hari ini
- **Sortir Bulan Ini** - Jumlah sortir bulan ini
- **Sortir Terakhir** - 5 data sortir terbaru

### 3.2 Menu Penyortir

| Menu | Fungsi |
|------|--------|
| Dashboard | Halaman utama penyortir |
| Hasil Sortir | Manajemen data sortir |
| Logout | Keluar dari sistem |

---

## 4. DASHBOARD SOPIR

### 4.1 Tampilan Dashboard

Dashboard sopir menampilkan:
- **Total Pengiriman** - Jumlah seluruh pengiriman
- **Selesai** - Pengiriman yang sudah selesai
- **Dalam Perjalanan** - Pengiriman yang sedang berjalan
- **Total Berat** - Total berat yang telah dikirim
- **Pengiriman Terakhir** - 5 data pengiriman terbaru

### 4.2 Menu Sopir

| Menu | Fungsi |
|------|--------|
| Dashboard | Halaman utama sopir |
| Data Pengiriman | Manajemen data pengiriman |
| Logout | Keluar dari sistem |

---

## 5. DASHBOARD OPERATOR

### 5.1 Tampilan Dashboard

Dashboard operator menampilkan:
- **Total Produksi** - Jumlah seluruh produksi
- **Produksi Hari Ini** - Jumlah produksi hari ini
- **Produksi Bulan Ini** - Jumlah produksi bulan ini
- **Mesin Baik** - Jumlah mesin dalam kondisi baik
- **Mesin Rusak** - Jumlah mesin rusak
- **Produksi Terakhir** - 5 data produksi terbaru

### 5.2 Menu Operator

| Menu | Fungsi |
|------|--------|
| Dashboard | Halaman utama operator |
| Hasil Produksi | Manajemen data produksi |
| Logout | Keluar dari sistem |

---

## 6. DASHBOARD KEPALA PRODUKSI

### 6.1 Tampilan Dashboard

Dashboard kepala produksi menampilkan:
- **Total Sortir** - Seluruh data sortir
- **Total Pengiriman** - Seluruh data pengiriman
- **Total Produksi** - Seluruh data produksi
- **Total Pelanggan** - Jumlah pelanggan
- **Alert** - Peringatan mesin rusak dan feedback baru

### 6.2 Menu Kepala Produksi

| Menu | Fungsi |
|------|--------|
| Dashboard | Halaman utama kepala produksi |
| Rekap Data | Melihat rekap per periode |
| Koreksi Data | Edit/hapus data yang salah |
| Data Pelanggan | Manajemen data pelanggan |
| Feedback | Manajemen feedback pelanggan |
| Laporan | Generate laporan PDF/Excel |
| Logout | Keluar dari sistem |

---

## 7. MANAJEMEN DATA

### 7.1 Cara Menambah Data Sortir

1. Login sebagai **Penyortir**
2. Klik menu **Hasil Sortir**
3. Klik tombol **Tambah Data**
4. Isi form:

| Field | Cara Pengisian |
|-------|----------------|
| Jenis Plastik | Pilih dari dropdown (PET, HDPE, PVC, LDPE, PP, PS) |
| Kualitas | Pilih A, B, C, atau D |
| Jumlah (kg) | Isi angka (contoh: 500.50) |
| Tanggal | Pilih tanggal sortir |
| Catatan | Isi catatan (opsional) |

5. Klik **Simpan**

### 7.2 Cara Menambah Data Pengiriman

1. Login sebagai **Sopir**
2. Klik menu **Data Pengiriman**
3. Klik tombol **Tambah Data**
4. Isi form:

| Field | Cara Pengisian |
|-------|----------------|
| Tanggal | Pilih tanggal pengiriman |
| Perusahaan | Nama perusahaan tujuan |
| Tujuan | Kota tujuan |
| Jenis Plastik | Pilih dari dropdown |
| Jumlah Muatan | Isi dalam kg |
| Status | Pilih Menunggu/Dalam Perjalanan/Selesai |

5. Klik **Simpan**

### 7.3 Cara Menambah Data Produksi

1. Login sebagai **Operator Mesin**
2. Klik menu **Hasil Produksi**
3. Klik tombol **Tambah Data**
4. Isi form:

| Field | Cara Pengisian |
|-------|----------------|
| Jenis Plastik | Pilih dari dropdown |
| Nama Mesin | Contoh: Mesin A, Mesin B |
| Operator | Nama operator |
| Tanggal Produksi | Pilih tanggal |
| Jumlah Hasil | Isi dalam kg |
| Kondisi Mesin | Pilih Baik/Perlu Maintenance/Rusak |
| Catatan | Isi catatan (opsional) |

5. Jika memilih **Rusak**, akan muncul peringatan
6. Klik **Simpan**

### 7.4 Cara Mengedit Data (Kepala Produksi)

1. Login sebagai **Kepala Produksi**
2. Klik menu **Koreksi Data**
3. Pilih tab **Sortir**, **Pengiriman**, atau **Produksi**
4. Cari data yang ingin diedit
5. Klik tombol **Edit**
6. Perbaiki data
7. Klik **Simpan Perubahan**

### 7.5 Cara Menghapus Data (Kepala Produksi)

1. Login sebagai **Kepala Produksi**
2. Klik menu **Koreksi Data**
3. Cari data yang ingin dihapus
4. Klik tombol **Hapus**
5. Konfirmasi dengan klik **OK**

---

## 8. MANAJEMEN PELANGGAN & FEEDBACK

### 8.1 Cara Menambah Pelanggan

1. Login sebagai **Kepala Produksi**
2. Klik menu **Data Pelanggan**
3. Klik tombol **Tambah Pelanggan**
4. Isi data pelanggan
5. Klik **Simpan**

### 8.2 Cara Menambah Feedback

1. Login sebagai **Kepala Produksi**
2. Klik menu **Feedback**
3. Klik tombol **Tambah Feedback**
4. Isi:
   - Pilih pelanggan
   - Rating (1-5 bintang)
   - Komentar
   - Tanggal
   - Status
5. Klik **Simpan**

### 8.3 Cara Mengubah Status Feedback

1. Klik menu **Feedback**
2. Pada kolom Status, pilih status baru:
   - **Baru** - Feedback baru diterima
   - **Ditinjau** - Sedang diproses
   - **Selesai** - Sudah ditindaklanjuti
3. Status akan berubah otomatis

---

## 9. LAPORAN

### 9.1 Cara Generate Laporan

1. Login sebagai **Kepala Produksi**
2. Klik menu **Laporan**
3. Pilih parameter:

| Parameter | Keterangan |
|-----------|------------|
| Jenis Laporan | Lengkap, Sortir, Pengiriman, atau Produksi |
| Dari Tanggal | Tanggal awal periode |
| Sampai Tanggal | Tanggal akhir periode |

4. Klik **Generate**
5. Preview laporan akan muncul

### 9.2 Cara Export Laporan

| Format | Cara |
|--------|------|
| **PDF** | Klik tombol **Export PDF** |
| **Excel** | Klik tombol **Export Excel** |
| **Cetak** | Klik tombol **Cetak** atau tekan Ctrl+P |

### 9.3 Contoh Laporan

Laporan akan menampilkan:
- Kop surat perusahaan
- Periode laporan
- Ringkasan data
- Detail data per kategori
- Tanda tangan Kepala Produksi

---

## 10. TROUBLESHOOTING

### 10.1 Error Login

**Masalah:** Tidak bisa login

**Solusi:**
- Pastikan username dan password benar
- Cek Caps Lock tidak aktif
- Hubungi administrator untuk reset password

### 10.2 Data Tidak Muncul

**Masalah:** Data tidak muncul di tabel

**Solusi:**
- Refresh halaman (F5)
- Cek filter tanggal
- Pastikan data sudah diinput dengan benar

### 10.3 Gagal Simpan Data

**Masalah:** Data tidak tersimpan

**Solusi:**
- Pastikan semua field wajib sudah diisi
- Cek koneksi internet
- Refresh halaman dan coba lagi

### 10.4 Gagal Generate Laporan

**Masalah:** Laporan tidak bisa digenerate

**Solusi:**
- Pastikan ada data dalam periode yang dipilih
- Cek koneksi internet (untuk PDF)
- Coba dengan periode lain

### 10.5 Tampilan Tidak Rapi

**Masalah:** Tampilan tidak rapi di HP

**Solusi:**
- Gunakan browser Chrome atau Firefox
- Perbarui browser ke versi terbaru
- Zoom out jika perlu

---

## 11. KONTAK

Jika masih mengalami masalah, hubungi:

| Kontak | Informasi |
|--------|-----------|
| Email | support@plastik.com |
| Telepon | (021) 555-1234 |
| Website | www.sistem-plastik.com |

---

## 12. VERSI DOKUMEN

| Versi | Tanggal | Perubahan |
|-------|---------|-----------|
| 1.0 | 2025-03-04 | Dokumentasi awal |