<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'db_plastik';

$koneksi = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

mysqli_set_charset($koneksi, "utf8mb4");

define('BASE_URL', 'http://localhost/sistem-plastik');
define('APP_NAME', 'Sistem Informasi Plastik');
?>