<?php
/**
 * UNIT TESTING - MODUL SORTIR (PB-02)
 * 
 * Sprint 2: Manajemen Sortir
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>";
echo "<html><head><title>Unit Testing - Sortir</title>";
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

echo "<h1>🧪 Unit Testing - Modul Sortir (PB-02)</h1>";
echo "<p>Sprint 2: Manajemen Sortir</p>";
echo "<hr>";

$total_tests = 0;
$passed_tests = 0;

include_once '../config.php';
include_once '../functions.php';

// ==================== TEST 1: Tabel Hasil Sortir ====================
echo "<h3>📋 Test 1: Tabel hasil_sortir</h3>";
$total_tests++;

$result = mysqli_query($koneksi, "SHOW TABLES LIKE 'hasil_sortir'");
if ($result && mysqli_num_rows($result) > 0) {
    echo "<div class='test-pass'>✅ PASS: Tabel hasil_sortir ditemukan</div>";
    $passed_tests++;
    
    // Cek struktur tabel
    $result2 = mysqli_query($koneksi, "DESCRIBE hasil_sortir");
    $columns = [];
    while($row = mysqli_fetch_assoc($result2)) {
        $columns[] = $row['Field'];
    }
    
    $required_columns = ['id', 'user_id', 'jenis_plastik', 'kualitas', 'jumlah', 'tanggal'];
    foreach($required_columns as $col) {
        if(in_array($col, $columns)) {
            echo "<div class='test-pass'>✅ PASS: Kolom '$col' ada</div>";
        } else {
            echo "<div class='test-fail'>❌ FAIL: Kolom '$col' tidak ada</div>";
        }
    }
} else {
    echo "<div class='test-fail'>❌ FAIL: Tabel hasil_sortir tidak ditemukan</div>";
}

// ==================== TEST 2: Data Sortir ====================
echo "<h3>📋 Test 2: Data Sortir</h3>";
$total_tests++;

$result = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM hasil_sortir");
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $total_data = $row['total'];
    echo "<div class='test-pass'>✅ PASS: Terdapat $total_data data sortir</div>";
    $passed_tests++;
} else {
    echo "<div class='test-fail'>❌ FAIL: Gagal mengambil data sortir</div>";
}

// ==================== TEST 3: Insert Data Sortir ====================
echo "<h3>📋 Test 3: Insert Data Sortir</h3>";
$total_tests++;

$test_data = [
    'user_id' => 1,
    'jenis_plastik' => 'PET',
    'kualitas' => 'A',
    'jumlah' => 100.00,
    'tanggal' => date('Y-m-d'),
    'catatan' => 'Test unit'
];

$sql = "INSERT INTO hasil_sortir (user_id, jenis_plastik, kualitas, jumlah, tanggal, catatan) 
        VALUES ({$test_data['user_id']}, '{$test_data['jenis_plastik']}', '{$test_data['kualitas']}', 
                {$test_data['jumlah']}, '{$test_data['tanggal']}', '{$test_data['catatan']}')";

if (mysqli_query($koneksi, $sql)) {
    $insert_id = mysqli_insert_id($koneksi);
    echo "<div class='test-pass'>✅ PASS: Insert data sortir berhasil (ID: $insert_id)</div>";
    $passed_tests++;
    
    // Clean up
    mysqli_query($koneksi, "DELETE FROM hasil_sortir WHERE id = $insert_id");
} else {
    echo "<div class='test-fail'>❌ FAIL: Insert data sortir gagal: " . mysqli_error($koneksi) . "</div>";
}

// ==================== TEST 4: Update Data Sortir ====================
echo "<h3>📋 Test 4: Update Data Sortir</h3>";
$total_tests++;

// Insert dummy data
$sql = "INSERT INTO hasil_sortir (user_id, jenis_plastik, kualitas, jumlah, tanggal) 
        VALUES (1, 'PET', 'A', 100.00, CURDATE())";
mysqli_query($koneksi, $sql);
$update_id = mysqli_insert_id($koneksi);

$sql = "UPDATE hasil_sortir SET jumlah = 200.00 WHERE id = $update_id";
if (mysqli_query($koneksi, $sql)) {
    echo "<div class='test-pass'>✅ PASS: Update data sortir berhasil</div>";
    $passed_tests++;
} else {
    echo "<div class='test-fail'>❌ FAIL: Update data sortir gagal</div>";
}

// Clean up
mysqli_query($koneksi, "DELETE FROM hasil_sortir WHERE id = $update_id");

// ==================== TEST 5: Delete Data Sortir ====================
echo "<h3>📋 Test 5: Delete Data Sortir</h3>";
$total_tests++;

// Insert dummy data
$sql = "INSERT INTO hasil_sortir (user_id, jenis_plastik, kualitas, jumlah, tanggal) 
        VALUES (1, 'PET', 'A', 100.00, CURDATE())";
mysqli_query($koneksi, $sql);
$delete_id = mysqli_insert_id($koneksi);

$sql = "DELETE FROM hasil_sortir WHERE id = $delete_id";
if (mysqli_query($koneksi, $sql)) {
    echo "<div class='test-pass'>✅ PASS: Delete data sortir berhasil</div>";
    $passed_tests++;
} else {
    echo "<div class='test-fail'>❌ FAIL: Delete data sortir gagal</div>";
}

// ==================== TEST 6: Filter Data ====================
echo "<h3>📋 Test 6: Filter Data Sortir</h3>";
$total_tests++;

$sql = "SELECT * FROM hasil_sortir WHERE jenis_plastik = 'PET'";
$result = mysqli_query($koneksi, $sql);
if ($result) {
    echo "<div class='test-pass'>✅ PASS: Filter data sortir berhasil</div>";
    $passed_tests++;
} else {
    echo "<div class='test-fail'>❌ FAIL: Filter data sortir gagal</div>";
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
    echo "<span style='color: red;'>⚠️ Target coverage 90% belum tercapai. Perlu " . (90 - $percentage) . "% lagi.</span>";
}
echo "</div>";

echo "</body></html>";
?>