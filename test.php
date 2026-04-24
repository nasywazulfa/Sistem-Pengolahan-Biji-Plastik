<?php
require_once 'functions.php';

echo "<h1>TEST FUNCTIONS.PHP</h1>";

// Cek fungsi
if (function_exists('cekAkses')) {
    echo "<p style='color: green;'>✓ Fungsi cekAkses() ditemukan</p>";
} else {
    echo "<p style='color: red;'>✗ Fungsi cekAkses() TIDAK ditemukan</p>";
}

if (function_exists('query')) {
    echo "<p style='color: green;'>✓ Fungsi query() ditemukan</p>";
} else {
    echo "<p style='color: red;'>✗ Fungsi query() TIDAK ditemukan</p>";
}

// Test koneksi database
$sql = "SELECT COUNT(*) as total FROM users";
$result = query($sql);
if ($result) {
    $data = fetch($result);
    echo "<p style='color: green;'>✓ Koneksi database OK. Total users: " . $data['total'] . "</p>";
} else {
    echo "<p style='color: red;'>✗ Koneksi database GAGAL</p>";
}
?>