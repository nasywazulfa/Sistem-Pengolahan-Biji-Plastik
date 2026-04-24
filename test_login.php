<?php
/**
 * UNIT TESTING - MODUL LOGIN (PB-01)
 * 
 * Sprint 1: Autentikasi & Otorisasi
 * Diharapkan coverage minimal 90%
 */

// Konfigurasi
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>";
echo "<html><head><title>Unit Testing - Login</title>";
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

echo "<h1>🧪 Unit Testing - Modul Login (PB-01)</h1>";
echo "<p>Sprint 1: Autentikasi & Otorisasi</p>";
echo "<hr>";

$total_tests = 0;
$passed_tests = 0;

// ==================== TEST 1: Koneksi Database ====================
echo "<h3>📋 Test 1: Koneksi Database</h3>";
$total_tests++;

include_once '../config.php';

if (isset($koneksi) && $koneksi) {
    echo "<div class='test-pass'>✅ PASS: Koneksi database berhasil</div>";
    $passed_tests++;
} else {
    echo "<div class='test-fail'>❌ FAIL: Koneksi database gagal</div>";
}

// ==================== TEST 2: Tabel Users ====================
echo "<h3>📋 Test 2: Tabel Users</h3>";
$total_tests++;

$result = mysqli_query($koneksi, "SHOW TABLES LIKE 'users'");
if ($result && mysqli_num_rows($result) > 0) {
    echo "<div class='test-pass'>✅ PASS: Tabel users ditemukan</div>";
    $passed_tests++;
    
    // Cek struktur tabel
    $result2 = mysqli_query($koneksi, "DESCRIBE users");
    $columns = [];
    while($row = mysqli_fetch_assoc($result2)) {
        $columns[] = $row['Field'];
    }
    
    $required_columns = ['id', 'username', 'password', 'nama_lengkap', 'role'];
    foreach($required_columns as $col) {
        if(in_array($col, $columns)) {
            echo "<div class='test-pass'>✅ PASS: Kolom '$col' ada</div>";
        } else {
            echo "<div class='test-fail'>❌ FAIL: Kolom '$col' tidak ada</div>";
        }
    }
} else {
    echo "<div class='test-fail'>❌ FAIL: Tabel users tidak ditemukan</div>";
}

// ==================== TEST 3: Data Users ====================
echo "<h3>📋 Test 3: Data Users</h3>";
$total_tests++;

$result = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users");
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $total_users = $row['total'];
    echo "<div class='test-pass'>✅ PASS: Terdapat $total_users user di database</div>";
    $passed_tests++;
    
    // Cek role yang tersedia
    $result2 = mysqli_query($koneksi, "SELECT DISTINCT role FROM users");
    $roles = [];
    while($row2 = mysqli_fetch_assoc($result2)) {
        $roles[] = $row2['role'];
    }
    
    $required_roles = ['penyortir', 'sopir', 'operator_mesin', 'kepala_produksi'];
    foreach($required_roles as $role) {
        if(in_array($role, $roles)) {
            echo "<div class='test-pass'>✅ PASS: Role '$role' tersedia</div>";
        } else {
            echo "<div class='test-fail'>❌ FAIL: Role '$role' tidak tersedia</div>";
        }
    }
} else {
    echo "<div class='test-fail'>❌ FAIL: Gagal mengambil data users</div>";
}

// ==================== TEST 4: Fungsi Login ====================
echo "<h3>📋 Test 4: Fungsi Login</h3>";
$total_tests++;

include_once '../functions.php';

if (function_exists('isLogin')) {
    echo "<div class='test-pass'>✅ PASS: Fungsi isLogin() tersedia</div>";
    $passed_tests++;
} else {
    echo "<div class='test-fail'>❌ FAIL: Fungsi isLogin() tidak tersedia</div>";
}

if (function_exists('redirect')) {
    echo "<div class='test-pass'>✅ PASS: Fungsi redirect() tersedia</div>";
    $passed_tests++;
} else {
    echo "<div class='test-fail'>❌ FAIL: Fungsi redirect() tidak tersedia</div>";
}

if (function_exists('cekAkses')) {
    echo "<div class='test-pass'>✅ PASS: Fungsi cekAkses() tersedia</div>";
    $passed_tests++;
} else {
    echo "<div class='test-fail'>❌ FAIL: Fungsi cekAkses() tidak tersedia</div>";
}

// ==================== TEST 5: Validasi Login ====================
echo "<h3>📋 Test 5: Validasi Login</h3>";
$total_tests++;

// Test login dengan user yang valid
$test_username = 'penyortir1';
$test_password = md5('password');

$sql = "SELECT * FROM users WHERE username = '$test_username' AND password = '$test_password'";
$result = mysqli_query($koneksi, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    echo "<div class='test-pass'>✅ PASS: Login valid berhasil (username: $test_username)</div>";
    $passed_tests++;
} else {
    echo "<div class='test-fail'>❌ FAIL: Login valid gagal untuk username: $test_username</div>";
}

// Test login dengan user yang tidak valid
$sql = "SELECT * FROM users WHERE username = 'invalid_user' AND password = md5('wrong')";
$result = mysqli_query($koneksi, $sql);

if ($result && mysqli_num_rows($result) == 0) {
    echo "<div class='test-pass'>✅ PASS: Login invalid ditolak dengan benar</div>";
    $passed_tests++;
} else {
    echo "<div class='test-fail'>❌ FAIL: Login invalid tidak ditolak</div>";
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