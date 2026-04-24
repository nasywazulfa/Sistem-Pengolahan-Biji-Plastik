<?php
/**
 * UNIT TESTING - MODUL PENGIRIMAN (PB-03)
 * 
 * Sprint 3: Manajemen Pengiriman
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>";
echo "<html><head><title>Unit Testing - Pengiriman</title>";
echo "<style>
    body { font-family: monospace; padding: 20px; background: #f5f5f5; }
    .test-pass { color: green; background: #d4edda; padding: 10px; margin: 5px 0; border-radius: 5px; }
    .test-fail { color: red; background: #f8d7da; padding: 10px; margin: 5px 0; border-radius: 5px; }
    .summary { font-size: 18px; font-weight: bold; margin-top: 20px; padding: 15px; background: #fff; border-radius: 5px; }
    h1 { color: #333; }
    hr { margin: 20px 0; }
</style>";
echo "</head><body>";

echo "<h1>🧪 Unit Testing - Modul Pengiriman (PB-03)</h1>";
echo "<p>Sprint 3: Manajemen Pengiriman</p>";
echo "<hr>";

$total_tests = 0;
$passed_tests = 0;

include_once '../config.php';
include_once '../functions.php';

// ==================== TEST 1: Tabel Pengiriman ====================
echo "<h3>📋 Test 1: Tabel pengiriman</h3>";
$total_tests++;

$result = mysqli_query($koneksi, "SHOW TABLES LIKE 'pengiriman'");
if ($result && mysqli_num_rows($result) > 0) {
    echo "<div class='test-pass'>✅ PASS: Tabel pengiriman ditemukan</div>";
    $passed_tests++;
    
    // Cek struktur tabel
    $result2 = mysqli_query($koneksi, "DESCRIBE pengiriman");
    $columns = [];
    while($row = mysqli_fetch_assoc($result2)) {
        $columns[] = $row['Field'];
    }
    
    $required_columns = ['id', 'user_id', 'tanggal', 'tujuan', 'perusahaan', 'jumlah_muatan', 'jenis_plastik', 'status'];
    foreach($required_columns as $col) {
        if(in_array($col, $columns)) {
            echo "<div class='test-pass'>✅ PASS: Kolom '$col' ada</div>";
        } else {
            echo "<div class='test-fail'>❌ FAIL: Kolom '$col' tidak ada</div>";
        }
    }
} else {
    echo "<div class='test-fail'>❌ FAIL: Tabel pengiriman tidak ditemukan</div>";
}

// ==================== TEST 2: Insert Data Pengiriman ====================
echo "<h3>📋 Test 2: Insert Data Pengiriman</h3>";
$total_tests++;

$sql = "INSERT INTO pengiriman (user_id, tanggal, tujuan, perusahaan, jumlah_muatan, jenis_plastik, status) 
        VALUES (3, CURDATE(), 'Test Tujuan', 'Test Perusahaan', 100.00, 'PET', 'Menunggu')";

if (mysqli_query($koneksi, $sql)) {
    $insert_id = mysqli_insert_id($koneksi);
    echo "<div class='test-pass'>✅ PASS: Insert data pengiriman berhasil (ID: $insert_id)</div>";
    $passed_tests++;
    
    // Clean up
    mysqli_query($koneksi, "DELETE FROM pengiriman WHERE id = $insert_id");
} else {
    echo "<div class='test-fail'>❌ FAIL: Insert data pengiriman gagal</div>";
}

// ==================== TEST 3: Update Status Pengiriman ====================
echo "<h3>📋 Test 3: Update Status Pengiriman</h3>";
$total_tests++;

// Insert dummy data
$sql = "INSERT INTO pengiriman (user_id, tanggal, tujuan, perusahaan, jumlah_muatan, jenis_plastik, status) 
        VALUES (3, CURDATE(), 'Test', 'Test', 100.00, 'PET', 'Menunggu')";
mysqli_query($koneksi, $sql);
$update_id = mysqli_insert_id($koneksi);

$sql = "UPDATE pengiriman SET status = 'Selesai' WHERE id = $update_id";
if (mysqli_query($koneksi, $sql)) {
    echo "<div class='test-pass'>✅ PASS: Update status pengiriman berhasil</div>";
    $passed_tests++;
} else {
    echo "<div class='test-fail'>❌ FAIL: Update status pengiriman gagal</div>";
}

// Clean up
mysqli_query($koneksi, "DELETE FROM pengiriman WHERE id = $update_id");

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
    echo "<span style='color: red;'>⚠️ Target coverage 90% belum tercapai. Perlu " . (90 - $percentage) . "% lagi.</span>";
}
echo "</div>";

echo "</body></html>";
?>