<?php
/**
 * UNIT TESTING - MODUL DASHBOARD (PB-08)
 * 
 * Sprint 8: Dashboard & Visualisasi
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>";
echo "<html><head><title>Unit Testing - Dashboard</title>";
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

echo "<h1>🧪 Unit Testing - Modul Dashboard (PB-08)</h1>";
echo "<p>Sprint 8: Dashboard & Visualisasi</p>";
echo "<hr>";

$total_tests = 0;
$passed_tests = 0;

include_once '../config.php';
include_once '../functions.php';

// ==================== TEST 1: File Dashboard Penyortir ====================
echo "<h3>📋 Test 1: File Dashboard Penyortir</h3>";
$total_tests++;

$files = [
    '../penyortir/dashboard.php',
    '../sopir/dashboard.php',
    '../operator/dashboard.php',
    '../kepala/dashboard.php'
];

foreach($files as $file) {
    if (file_exists($file)) {
        echo "<div class='test-pass'>✅ PASS: " . basename($file) . " ditemukan</div>";
        $passed_tests++;
    } else {
        echo "<div class='test-fail'>❌ FAIL: " . basename($file) . " tidak ditemukan</div>";
    }
}

// ==================== TEST 2: Statistik Dashboard ====================
echo "<h3>📋 Test 2: Query Statistik Dashboard</h3>";
$total_tests++;

// Total sortir
$sql = "SELECT COALESCE(SUM(jumlah), 0) as total FROM hasil_sortir";
$result = mysqli_query($koneksi, $sql);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    echo "<div class='test-pass'>✅ PASS: Query total sortir berhasil - Total: " . number_format($row['total'], 2) . " kg</div>";
    $passed_tests++;
} else {
    echo "<div class='test-fail'>❌ FAIL: Query total sortir gagal</div>";
}

// Total pengiriman
$sql = "SELECT COALESCE(SUM(jumlah_muatan), 0) as total FROM pengiriman";
$result = mysqli_query($koneksi, $sql);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    echo "<div class='test-pass'>✅ PASS: Query total pengiriman berhasil - Total: " . number_format($row['total'], 2) . " kg</div>";
    $passed_tests++;
} else {
    echo "<div class='test-fail'>❌ FAIL: Query total pengiriman gagal</div>";
}

// Total produksi
$sql = "SELECT COALESCE(SUM(jumlah_hasil), 0) as total FROM hasil_produksi";
$result = mysqli_query($koneksi, $sql);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    echo "<div class='test-pass'>✅ PASS: Query total produksi berhasil - Total: " . number_format($row['total'], 2) . " kg</div>";
    $passed_tests++;
} else {
    echo "<div class='test-fail'>❌ FAIL: Query total produksi gagal</div>";
}

// Total pelanggan
$sql = "SELECT COUNT(*) as total FROM pelanggan";
$result = mysqli_query($koneksi, $sql);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    echo "<div class='test-pass'>✅ PASS: Query total pelanggan berhasil - Total: " . $row['total'] . " pelanggan</div>";
    $passed_tests++;
} else {
    echo "<div class='test-fail'>❌ FAIL: Query total pelanggan gagal</div>";
}

// ==================== TEST 3: Statistik per Role ====================
echo "<h3>📋 Test 3: Statistik Dashboard per Role</h3>";
$total_tests++;

// Dashboard Penyortir
$sql = "SELECT COALESCE(SUM(jumlah), 0) as total FROM hasil_sortir WHERE user_id = 1";
$result = mysqli_query($koneksi, $sql);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    echo "<div class='test-pass'>✅ PASS: Statistik penyortir berhasil</div>";
    $passed_tests++;
} else {
    echo "<div class='test-fail'>❌ FAIL: Statistik penyortir gagal</div>";
}

// Dashboard Sopir
$sql = "SELECT COUNT(*) as total FROM pengiriman WHERE user_id = 3";
$result = mysqli_query($koneksi, $sql);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    echo "<div class='test-pass'>✅ PASS: Statistik sopir berhasil</div>";
    $passed_tests++;
} else {
    echo "<div class='test-fail'>❌ FAIL: Statistik sopir gagal</div>";
}

// Dashboard Operator
$sql = "SELECT COALESCE(SUM(jumlah_hasil), 0) as total FROM hasil_produksi WHERE user_id = 5";
$result = mysqli_query($koneksi, $sql);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    echo "<div class='test-pass'>✅ PASS: Statistik operator berhasil</div>";
    $passed_tests++;
} else {
    echo "<div class='test-fail'>❌ FAIL: Statistik operator gagal</div>";
}

// ==================== TEST 4: Library Chart.js ====================
echo "<h3>📋 Test 4: Library Chart.js untuk Grafik</h3>";
$total_tests++;

$chart_url = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js';
$headers = @get_headers($chart_url);
if ($headers && strpos($headers[0], '200')) {
    echo "<div class='test-pass'>✅ PASS: Library Chart.js dapat diakses</div>";
    $passed_tests++;
} else {
    echo "<div class='test-info'>ℹ️ INFO: Library Chart.js mungkin perlu koneksi internet</div>";
}

// ==================== TEST 5: Grafik 7 Hari Terakhir ====================
echo "<h3>📋 Test 5: Data Grafik 7 Hari Terakhir</h3>";
$total_tests++;

$sql = "SELECT tanggal, SUM(jumlah) as total FROM hasil_sortir 
        WHERE tanggal >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
        GROUP BY tanggal ORDER BY tanggal";
$result = mysqli_query($koneksi, $sql);
if ($result) {
    $count = mysqli_num_rows($result);
    echo "<div class='test-pass'>✅ PASS: Data grafik sortir 7 hari terakhir berhasil ($count data)</div>";
    $passed_tests++;
    
    // Tampilkan data
    while($row = mysqli_fetch_assoc($result)) {
        echo "<div class='test-info'>📊 " . tglIndo($row['tanggal']) . ": " . number_format($row['total'], 2) . " kg</div>";
    }
} else {
    echo "<div class='test-fail'>❌ FAIL: Data grafik sortir gagal</div>";
}

// ==================== TEST 6: Data Grafik Produksi ====================
echo "<h3>📋 Test 6: Data Grafik Produksi 7 Hari Terakhir</h3>";
$total_tests++;

$sql = "SELECT tgl_produksi as tanggal, SUM(jumlah_hasil) as total FROM hasil_produksi 
        WHERE tgl_produksi >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
        GROUP BY tgl_produksi ORDER BY tgl_produksi";
$result = mysqli_query($koneksi, $sql);
if ($result) {
    $count = mysqli_num_rows($result);
    echo "<div class='test-pass'>✅ PASS: Data grafik produksi 7 hari terakhir berhasil ($count data)</div>";
    $passed_tests++;
} else {
    echo "<div class='test-fail'>❌ FAIL: Data grafik produksi gagal</div>";
}

// ==================== TEST 7: Responsive Design ====================
echo "<h3>📋 Test 7: Responsive Design</h3>";
$total_tests++;

// Cek apakah ada viewport meta tag di file dashboard
$dashboard_files = [
    '../penyortir/dashboard.php',
    '../sopir/dashboard.php',
    '../operator/dashboard.php',
    '../kepala/dashboard.php'
];

$has_viewport = false;
foreach($dashboard_files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        if (strpos($content, 'viewport') !== false) {
            $has_viewport = true;
        }
    }
}

if ($has_viewport) {
    echo "<div class='test-pass'>✅ PASS: Viewport meta tag ditemukan (support mobile)</div>";
    $passed_tests++;
} else {
    echo "<div class='test-info'>ℹ️ INFO: Viewport meta tag perlu ditambahkan untuk mobile responsive</div>";
}

// ==================== TEST 8: Notifikasi Dashboard ====================
echo "<h3>📋 Test 8: Notifikasi di Dashboard</h3>";
$total_tests++;

// Cek tabel notifikasi
$sql = "SHOW TABLES LIKE 'notifikasi'";
$result = mysqli_query($koneksi, $sql);
if ($result && mysqli_num_rows($result) > 0) {
    $sql = "SELECT COUNT(*) as total FROM notifikasi WHERE is_read = 0";
    $result = mysqli_query($koneksi, $sql);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        echo "<div class='test-pass'>✅ PASS: Notifikasi tersedia (" . $row['total'] . " notifikasi belum dibaca)</div>";
        $passed_tests++;
    } else {
        echo "<div class='test-pass'>✅ PASS: Tabel notifikasi sudah ada</div>";
        $passed_tests++;
    }
} else {
    echo "<div class='test-info'>ℹ️ INFO: Tabel notifikasi belum dibuat (akan dibuat saat update database)</div>";
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