<?php
/**
 * UNIT TESTING - MODUL PELANGGAN (PB-07)
 * 
 * Sprint 7: Manajemen Pelanggan & Feedback
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>";
echo "<html><head><title>Unit Testing - Pelanggan</title>";
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

echo "<h1>🧪 Unit Testing - Modul Pelanggan (PB-07)</h1>";
echo "<p>Sprint 7: Manajemen Pelanggan & Feedback</p>";
echo "<hr>";

$total_tests = 0;
$passed_tests = 0;

include_once '../config.php';
include_once '../functions.php';

// ==================== TEST 1: Tabel Pelanggan ====================
echo "<h3>📋 Test 1: Tabel pelanggan</h3>";
$total_tests++;

$result = mysqli_query($koneksi, "SHOW TABLES LIKE 'pelanggan'");
if ($result && mysqli_num_rows($result) > 0) {
    echo "<div class='test-pass'>✅ PASS: Tabel pelanggan ditemukan</div>";
    $passed_tests++;
} else {
    echo "<div class='test-fail'>❌ FAIL: Tabel pelanggan tidak ditemukan</div>";
}

// ==================== TEST 2: Insert Data Pelanggan ====================
echo "<h3>📋 Test 2: Insert Data Pelanggan</h3>";
$total_tests++;

$sql = "INSERT INTO pelanggan (nama, perusahaan, email, telepon, alamat) 
        VALUES ('Test Pelanggan', 'Test Perusahaan', 'test@test.com', '08123456789', 'Test Alamat')";

if (mysqli_query($koneksi, $sql)) {
    $insert_id = mysqli_insert_id($koneksi);
    echo "<div class='test-pass'>✅ PASS: Insert data pelanggan berhasil (ID: $insert_id)</div>";
    $passed_tests++;
    
    // Clean up
    mysqli_query($koneksi, "DELETE FROM pelanggan WHERE id = $insert_id");
} else {
    echo "<div class='test-fail'>❌ FAIL: Insert data pelanggan gagal: " . mysqli_error($koneksi) . "</div>";
}

// ==================== TEST 3: Tabel Feedback ====================
echo "<h3>📋 Test 3: Tabel feedback</h3>";
$total_tests++;

$result = mysqli_query($koneksi, "SHOW TABLES LIKE 'feedback'");
if ($result && mysqli_num_rows($result) > 0) {
    echo "<div class='test-pass'>✅ PASS: Tabel feedback ditemukan</div>";
    $passed_tests++;
} else {
    echo "<div class='test-fail'>❌ FAIL: Tabel feedback tidak ditemukan</div>";
}

// ==================== TEST 4: Insert Data Feedback ====================
echo "<h3>📋 Test 4: Insert Data Feedback</h3>";
$total_tests++;

// Insert dummy pelanggan dulu
$sql = "INSERT INTO pelanggan (nama, perusahaan) VALUES ('Test Feedback', 'Test Perusahaan')";
mysqli_query($koneksi, $sql);
$pelanggan_id = mysqli_insert_id($koneksi);

$sql = "INSERT INTO feedback (id_pelanggan, rating, komentar, tanggal, status) 
        VALUES ($pelanggan_id, 5, 'Test feedback', CURDATE(), 'Baru')";

if (mysqli_query($koneksi, $sql)) {
    $insert_id = mysqli_insert_id($koneksi);
    echo "<div class='test-pass'>✅ PASS: Insert data feedback berhasil (ID: $insert_id)</div>";
    $passed_tests++;
    
    // Clean up
    mysqli_query($koneksi, "DELETE FROM feedback WHERE id = $insert_id");
    mysqli_query($koneksi, "DELETE FROM pelanggan WHERE id = $pelanggan_id");
} else {
    echo "<div class='test-fail'>❌ FAIL: Insert data feedback gagal</div>";
}

// ==================== TEST 5: Update Status Feedback ====================
echo "<h3>📋 Test 5: Update Status Feedback</h3>";
$total_tests++;

// Insert dummy data
$sql = "INSERT INTO pelanggan (nama, perusahaan) VALUES ('Test Status', 'Test')";
mysqli_query($koneksi, $sql);
$pelanggan_id = mysqli_insert_id($koneksi);

$sql = "INSERT INTO feedback (id_pelanggan, rating, komentar, tanggal, status) 
        VALUES ($pelanggan_id, 4, 'Test status update', CURDATE(), 'Baru')";
mysqli_query($koneksi, $sql);
$feedback_id = mysqli_insert_id($koneksi);

$sql = "UPDATE feedback SET status = 'Ditinjau' WHERE id = $feedback_id";
if (mysqli_query($koneksi, $sql)) {
    echo "<div class='test-pass'>✅ PASS: Update status feedback berhasil</div>";
    $passed_tests++;
    
    // Clean up
    mysqli_query($koneksi, "DELETE FROM feedback WHERE id = $feedback_id");
    mysqli_query($koneksi, "DELETE FROM pelanggan WHERE id = $pelanggan_id");
} else {
    echo "<div class='test-fail'>❌ FAIL: Update status feedback gagal</div>";
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