# Panduan Singkat - Sistem Informasi Pengolahan Limbah Plastik

## Cara Login

1. Buka browser dan akses: `http://localhost/sistem-plastik/login.php`
2. Masukkan Username dan Password
3. Klik tombol **Login**

### Akun Demo

| Role | Username | Password |
|------|----------|----------|
| Penyortir | penyortir1 | password |
| Sopir | sopir1 | password |
| Operator Mesin | operator1 | password |
| Kepala Produksi | kepala1 | password |

## Menu Berdasarkan Role

### Penyortir
- **Dashboard** - Melihat statistik sortir
- **Hasil Sortir** - Mencatat data sortir plastik

### Sopir
- **Dashboard** - Melihat statistik pengiriman
- **Data Pengiriman** - Mencatat data pengiriman

### Operator Mesin
- **Dashboard** - Melihat statistik produksi dan kondisi mesin
- **Hasil Produksi** - Mencatat data produksi

### Kepala Produksi
- **Dashboard** - Melihat ringkasan semua data
- **Rekap Data** - Melihat laporan per periode
- **Koreksi Data** - Mengedit/menghapus data yang salah
- **Data Pelanggan** - Mengelola data pelanggan
- **Feedback** - Mengelola feedback pelanggan
- **Laporan** - Generate laporan PDF/Excel

## Cara Mengisi Data

### Sortir (Penyortir)
1. Klik menu **Hasil Sortir**
2. Klik tombol **Tambah Data**
3. Pilih jenis plastik (PET, HDPE, PVC, LDPE, PP, PS)
4. Pilih kualitas (A, B, C, D)
5. Isi jumlah (kg) dan tanggal
6. Klik **Simpan**

### Pengiriman (Sopir)
1. Klik menu **Data Pengiriman**
2. Klik tombol **Tambah Data**
3. Isi tanggal, tujuan, perusahaan, jumlah, jenis plastik
4. Pilih status (Menunggu/Perjalanan/Selesai)
5. Klik **Simpan**

### Produksi (Operator)
1. Klik menu **Hasil Produksi**
2. Klik tombol **Tambah Data**
3. Isi jenis plastik, nama mesin, operator
4. Isi jumlah hasil (kg) dan kondisi mesin
5. Klik **Simpan**

## Cara Generate Laporan (Kepala Produksi)
1. Klik menu **Laporan**
2. Pilih jenis laporan (Lengkap/Sortir/Pengiriman/Produksi)
3. Pilih periode tanggal
4. Klik **Generate**
5. Klik **Export PDF** atau **Cetak**

## Troubleshooting

### Lupa Password
Hubungi administrator untuk reset password.

### Data Tidak Tersimpan
- Pastikan semua field wajib sudah diisi
- Cek koneksi internet
- Refresh halaman dan coba lagi

### Error Login
- Pastikan username dan password benar
- Cek Caps Lock
- Hubungi admin jika masih gagal

## Kontak
Untuk bantuan lebih lanjut, hubungi:
- Email: support@plastik.com
- Telepon: (021) 555-1234