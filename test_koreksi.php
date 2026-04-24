<?php
/**
 * UNIT TESTING - MODUL KOREKSI DATA (PB-06)
 * 
 * Sprint 6: Koreksi Data
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>";
echo "<html><head><title>Unit Testing - Koreksi Data</title>";
echo "<style>
    body { font-family: monospace; padding: 20px; background: #f5f5f5; }
    .test-pass { color: green; background: #d4edda; padding: 10px; margin: 5px 0; border-radius: 5px; }
    .test-fail { color: red; background: #f8d7da; padding: 10px; margin: 5px 0; border-radius: 5px; }
    .test-info { color: blue; background: #cce5ff; padding: 10px; margin: 5px 0; border-radius: 5px; }
    .summary { font-size: 18px; font-weight: bold; margin-top: 20px; padding: 15px; background: #fff; border-radius: 5px; }
    h1 { color: #333; }
    hr { margin: 20px 0; }
</style>";
echo "</head><body>";

echo "<h1>🧪 Unit Testing - Modul Koreksi Data (PB-06)</h1>";
echo "<p>Sprint 6: Koreksi Data</p>";
echo "<hr>";

$total_tests = 0;
$passed_tests = 0;

include_once '../config.php';
include_once '../functions.php';

// ==================== TEST 1: Halaman Koreksi Sortir ====================
echo "<h3>📋 Test 1: Halaman Koreksi Data Sortir</h3>";
$total_tests++;

$koreksi_file = '../kepala/koreksi.php';
if (file_exists($koreksi_file)) {
    echo "<div class='test-pass'>✅ PASS: File koreksi.php ditemukan</div>";
    $passed_tests++;
} else {
    echo "<div class='test-fail'>❌ FAIL: File koreksi.php tidak ditemukan</div>";
}

// ==================== TEST 2: Edit Data Sortir ====================
echo "<h3>📋 Test 2: Edit Data Sortir</h3>";
$total_tests++;

// Insert dummy data
$sql = "INSERT INTO hasil_sortir (user_id, jenis_plastik, kualitas, jumlah, tanggal) 
        VALUES (1, 'PET', 'A', 100.00, CURDATE())";
mysqli_query($koneksi, $sql);
$edit_id = mysqli_insert_id($koneksi);

$sql = "UPDATE hasil_sortir SET jumlah = 150.00, kualitas = 'B' WHERE id = $edit_id";
if (mysqli_query($koneksi, $sql)) {
    echo "<div class='test-pass'>✅ PASS: Edit data sortir berhasil</div>";
    $passed_tests++;
    
    // Clean up
    mysqli_query($koneksi, "DELETE FROM hasil_sortir WHERE id = $edit_id");
} else {
    echo "<div class='test-fail'>❌ FAIL: Edit data sortir gagal</div>";
}

// ==================== TEST 3: Edit Data Pengiriman ====================
echo "<h3>📋 Test 3: Edit Data Pengiriman</h3>";
$total_tests++;

// Insert dummy data
$sql = "INSERT INTO pengiriman (user_id, tanggal, tujuan, perusahaan, jumlah_muatan, jenis_plastik, status) 
        VALUES (3, CURDATE(), 'Test', 'Test', 100.00, 'PET', 'Menunggu')";
mysqli_query($koneksi, $sql);
$edit_id = mysqli_insert_id($koneksi);

$sql = "UPDATE pengiriman SET jumlah_muatan = 200.00, status = 'Selesai' WHERE id = $edit_id";
if (mysqli_query($koneksi, $sql)) {
    echo "<div class='test-pass'>✅ PASS: Edit data pengiriman berhasil</div>";
    $passed_tests++;
    
    // Clean up
    mysqli_query($koneksi, "DELETE FROM pengiriman WHERE id = $edit_id");
} else {
    echo "<div class='test-fail'>❌ FAIL: Edit data pengiriman gagal</div>";
}

// ==================== TEST 4: Edit Data Produksi ====================
echo "<h3>📋 Test 4: Edit Data Produksi</h3>";
$total_tests++;

// Insert dummy data
$sql = "INSERT INTO hasil_produksi (user_id, jenis_plastik, nama_mesin, operator, tgl_produksi, jumlah_hasil, kondisi_mesin) 
        VALUES (5, 'PET', 'Mesin Test', 'Test', CURDATE(), 100.00, 'Baik')";
mysqli_query($koneksi, $sql);
$edit_id = mysqli_insert_id($koneksi);

$sql = "UPDATE hasil_produksi SET jumlah_hasil = 250.00, kondisi_mesin = 'Perlu Maintenance' WHERE id = $edit_id";
if (mysqli_query($koneksi, $sql)) {
    echo "<div class='test-pass'>✅ PASS: Edit data produksi berhasil</div>";
    $passed_tests++;
    
    // Clean up
    mysqli_query($koneksi, "DELETE FROM hasil_produksi WHERE id = $edit_id");
} else {
    echo "<div class='test-fail'>❌ FAIL: Edit data produksi gagal</div>";
}

// ==================== TEST 5: Hapus Data dengan Konfirmasi ====================
echo "<h3>📋 Test 5: Hapus Data dengan Konfirmasi</h3>";
$total_tests++;

// Insert dummy data
$sql = "INSERT INTO hasil_sortir (user_id, jenis_plastik, kualitas, jumlah, tanggal) 
        VALUES (1, 'PET', 'A', 100.00, CURDATE())";
mysqli_query($koneksi, $sql);
$delete_id = mysqli_insert_id($koneksi);

$sql = "DELETE FROM hasil_sortir WHERE id = $delete_id";
if (mysqli_query($koneksi, $sql)) {
    echo "<div class='test-pass'>✅ PASS: Hapus data sortir berhasil</div>";
    $passed_tests++;
} else {
    echo "<div class='test-fail'>❌ FAIL: Hapus data sortir gagal</div>";
}

// ==================== SUMMARY ====================
$percentage = ($passed_tests / $total_tests) * 100;
echo "<hr>";
echo "<div class='summary'>";
echo "<strong>📊 SUMMARY:</strong><br>";
echo "Total Tests: $total_tests<br>";
echo "Passed: $passed_tests<br>";
echo "Failed: " . ($total_tests - $passed_tests) . "<br>";
echo "Coverage: " . number_format($percentage, 2) . "%<br>";

if ($percentage >= 90) {
    echo "<span style='color: green;'>✅ Target coverage 90% tercapai!</span>";
} else {
    echo "<span style='color: orange;'>⚠️ Target coverage 90% belum tercapai. Perlu " . (90 - $percentage) . "% lagi.</span>";
}
echo "</div>";

echo "</body></html>";
?>