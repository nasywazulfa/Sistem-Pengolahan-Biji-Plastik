# PANDUAN INSTALASI - SISTEM INFORMASI PENGOLAHAN LIMBAH PLASTIK

## PERSYARATAN SISTEM

### Minimal

| Komponen | Spesifikasi |
|----------|-------------|
| Web Server | Apache 2.4+ |
| PHP | PHP 7.4+ |
| Database | MySQL 5.7+ |
| RAM | 2 GB |
| Storage | 500 MB |

### Rekomendasi

| Komponen | Spesifikasi |
|----------|-------------|
| Web Server | Apache 2.4+ / Nginx |
| PHP | PHP 8.0+ |
| Database | MySQL 8.0+ |
| RAM | 4 GB |
| Storage | 1 GB |

---

## LANGKAH INSTALASI

### 1. Install XAMPP

1. Download XAMPP dari [apachefriends.org](https://www.apachefriends.org/)
2. Install XAMPP di `C:\xampp`
3. Jalankan **XAMPP Control Panel**
4. Start **Apache** dan **MySQL**

### 2. Copy File Aplikasi

1. Buka folder `C:\xampp\htdocs`
2. Buat folder baru: `sistem-plastik`
3. Copy semua file aplikasi ke folder tersebut

### 3. Buat Database

1. Buka browser: `http://localhost/phpmyadmin`
2. Klik **New** di sidebar kiri
3. Isi nama database: `db_plastik`
4. Klik **Create**
5. Klik tab **SQL**
6. Copy dan paste SQL dari file `database.sql`
7. Klik **Go**

### 4. Konfigurasi Database

Edit file `config.php`:

```php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'db_plastik';