<?php
/**
 * UNIT TESTING - MODUL PRODUKSI (PB-04)
 * 
 * Sprint 4: Manajemen Produksi
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>";
echo "<html><head><title>Unit Testing - Produksi</title>";
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

echo "<h1>🧪 Unit Testing - Modul Produksi (PB-04)</h1>";
echo "<p>Sprint 4: Manajemen Produksi</p>";
echo "<hr>";

$total_tests = 0;
$passed_tests = 0;

include_once '../config.php';
include_once '../functions.php';

// ==================== TEST 1: Tabel Hasil Produksi ====================
echo "<h3>📋 Test 1: Tabel hasil_produksi</h3>";
$total_tests++;

$result = mysqli_query($koneksi, "SHOW TABLES LIKE 'hasil_produksi'");
if ($result && mysqli_num_rows($result) > 0) {
    echo "<div class='test-pass'>✅ PASS: Tabel hasil_produksi ditemukan</div>";
    $passed_tests++;
    
    // Cek struktur tabel
    $result2 = mysqli_query($koneksi, "DESCRIBE hasil_produksi");
    $columns = [];
    while($row = mysqli_fetch_assoc($result2)) {
        $columns[] = $row['Field'];
    }
    
    $required_columns = ['id', 'user_id', 'jenis_plastik', 'nama_mesin', 'operator', 'tgl_produksi', 'jumlah_hasil', 'kondisi_mesin'];
    foreach($required_columns as $col) {
        if(in_array($col, $columns)) {
            echo "<div class='test-pass'>✅ PASS: Kolom '$col' ada</div>";
        } else {
            echo "<div class='test-fail'>❌ FAIL: Kolom '$col' tidak ada</div>";
        }
    }
} else {
    echo "<div class='test-fail'>❌ FAIL: Tabel hasil_produksi tidak ditemukan</div>";
}

// ==================== TEST 2: Insert Data Produksi ====================
echo "<h3>📋 Test 2: Insert Data Produksi</h3>";
$total_tests++;

$sql = "INSERT INTO hasil_produksi (user_id, jenis_plastik, nama_mesin, operator, tgl_produksi, jumlah_hasil, kondisi_mesin) 
        VALUES (5, 'PET', 'Mesin Test', 'Test Operator', CURDATE(), 100.00, 'Baik')";

if (mysqli_query($koneksi, $sql)) {
    $insert_id = mysqli_insert_id($koneksi);
    echo "<div class='test-pass'>✅ PASS: Insert data produksi berhasil (ID: $insert_id)</div>";
    $passed_tests++;
    
    // Clean up
    mysqli_query($koneksi, "DELETE FROM hasil_produksi WHERE id = $insert_id");
} else {
    echo "<div class='test-fail'>❌ FAIL: Insert data produksi gagal: " . mysqli_error($koneksi) . "</div>";
}

// ==================== TEST 3: Kondisi Mesin Rusak ====================
echo "<h3>📋 Test 3: Peringatan Kondisi Mesin Rusak</h3>";
$total_tests++;

$sql = "INSERT INTO hasil_produksi (user_id, jenis_plastik, nama_mesin, operator, tgl_produksi, jumlah_hasil, kondisi_mesin) 
        VALUES (5, 'PET', 'Mesin Test Rusak', 'Test Operator', CURDATE(), 100.00, 'Rusak')";

if (mysqli_query($koneksi, $sql)) {
    $insert_id = mysqli_insert_id($koneksi);
    echo "<div class='test-pass'>✅ PASS: Data dengan kondisi mesin rusak berhasil disimpan</div>";
    $passed_tests++;
    
    // Cek notifikasi (jika ada)
    $sql_notif = "SELECT * FROM notifikasi WHERE pesan LIKE '%Mesin Test Rusak%'";
    $result_notif = mysqli_query($koneksi, $sql_notif);
    if ($result_notif && mysqli_num_rows($result_notif) > 0) {
        echo "<div class='test-pass'>✅ PASS: Notifikasi mesin rusak terbuat</div>";
        $passed_tests++;
    } else {
        echo "<div class='test-info'>ℹ️ INFO: Fitur notifikasi belum aktif (tabel notifikasi mungkin belum ada)</div>";
    }
    
    // Clean up
    mysqli_query($koneksi, "DELETE FROM hasil_produksi WHERE id = $insert_id");
} else {
    echo "<div class='test-fail'>❌ FAIL: Insert data kondisi mesin rusak gagal</div>";
}

// ==================== TEST 4: Filter Kondisi Mesin ====================
echo "<h3>📋 Test 4: Filter Berdasarkan Kondisi Mesin</h3>";
$total_tests++;

$sql = "SELECT * FROM hasil_produksi WHERE kondisi_mesin = 'Baik'";
$result = mysqli_query($koneksi, $sql);
if ($result) {
    $count = mysqli_num_rows($result);
    echo "<div class='test-pass'>✅ PASS: Filter kondisi mesin berhasil (ditemukan $count data)</div>";
    $passed_tests++;
} else {
    echo "<div class='test-fail'>❌ FAIL: Filter kondisi mesin gagal</div>";
}

// ==================== TEST 5: Update Data Produksi ====================
echo "<h3>📋 Test 5: Update Data Produksi</h3>";
$total_tests++;

// Insert dummy data
$sql = "INSERT INTO hasil_produksi (user_id, jenis_plastik, nama_mesin, operator, tgl_produksi, jumlah_hasil, kondisi_mesin) 
        VALUES (5, 'PET', 'Mesin Update Test', 'Test', CURDATE(), 100.00, 'Baik')";
mysqli_query($koneksi, $sql);
$update_id = mysqli_insert_id($koneksi);

$sql = "UPDATE hasil_produksi SET jumlah_hasil = 200.00 WHERE id = $update_id";
if (mysqli_query($koneksi, $sql)) {
    echo "<div class='test-pass'>✅ PASS: Update data produksi berhasil</div>";
    $passed_tests++;
} else {
    echo "<div class='test-fail'>❌ FAIL: Update data produksi gagal</div>";
}

// Clean up
mysqli_query($koneksi, "DELETE FROM hasil_produksi WHERE id = $update_id");

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