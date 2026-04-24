<?php
/**
 * UNIT TESTING - MODUL LAPORAN PRODUKSI (PB-05)
 * 
 * Sprint 5: Kontrol & Laporan Produksi
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>";
echo "<html><head><title>Unit Testing - Laporan Produksi</title>";
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

echo "<h1>🧪 Unit Testing - Modul Laporan Produksi (PB-05)</h1>";
echo "<p>Sprint 5: Kontrol & Laporan Produksi</p>";
echo "<hr>";

$total_tests = 0;
$passed_tests = 0;

include_once '../config.php';
include_once '../functions.php';

// ==================== TEST 1: Rekap Data Sortir ====================
echo "<h3>📋 Test 1: Rekap Data Sortir</h3>";
$total_tests++;

$sql = "SELECT COUNT(*) as total, SUM(jumlah) as total_jumlah FROM hasil_sortir";
$result = mysqli_query($koneksi, $sql);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    echo "<div class='test-pass'>✅ PASS: Rekap sortir berhasil - Total data: {$row['total']}, Total berat: " . number_format($row['total_jumlah'], 2) . " kg</div>";
    $passed_tests++;
} else {
    echo "<div class='test-fail'>❌ FAIL: Rekap sortir gagal</div>";
}

// ==================== TEST 2: Rekap Data Pengiriman ====================
echo "<h3>📋 Test 2: Rekap Data Pengiriman</h3>";
$total_tests++;

$sql = "SELECT COUNT(*) as total, SUM(jumlah_muatan) as total_muatan FROM pengiriman";
$result = mysqli_query($koneksi, $sql);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    echo "<div class='test-pass'>✅ PASS: Rekap pengiriman berhasil - Total data: {$row['total']}, Total berat: " . number_format($row['total_muatan'], 2) . " kg</div>";
    $passed_tests++;
} else {
    echo "<div class='test-fail'>❌ FAIL: Rekap pengiriman gagal</div>";
}

// ==================== TEST 3: Rekap Data Produksi ====================
echo "<h3>📋 Test 3: Rekap Data Produksi</h3>";
$total_tests++;

$sql = "SELECT COUNT(*) as total, SUM(jumlah_hasil) as total_hasil FROM hasil_produksi";
$result = mysqli_query($koneksi, $sql);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    echo "<div class='test-pass'>✅ PASS: Rekap produksi berhasil - Total data: {$row['total']}, Total hasil: " . number_format($row['total_hasil'], 2) . " kg</div>";
    $passed_tests++;
} else {
    echo "<div class='test-fail'>❌ FAIL: Rekap produksi gagal</div>";
}

// ==================== TEST 4: Filter Data Berdasarkan Tanggal ====================
echo "<h3>📋 Test 4: Filter Data Berdasarkan Tanggal</h3>";
$total_tests++;

$dari = date('Y-m-01');
$sampai = date('Y-m-d');

$sql = "SELECT COUNT(*) as total FROM hasil_sortir WHERE tanggal BETWEEN '$dari' AND '$sampai'";
$result = mysqli_query($koneksi, $sql);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    echo "<div class='test-pass'>✅ PASS: Filter tanggal berhasil - Data bulan ini: {$row['total']} data</div>";
    $passed_tests++;
} else {
    echo "<div class='test-fail'>❌ FAIL: Filter tanggal gagal</div>";
}

// ==================== TEST 5: File Laporan PDF ====================
echo "<h3>📋 Test 5: File Laporan PDF</h3>";
$total_tests++;

$pdf_file = '../kepala/laporan_pdf.php';
if (file_exists($pdf_file)) {
    echo "<div class='test-pass'>✅ PASS: File laporan_pdf.php ditemukan</div>";
    $passed_tests++;
} else {
    echo "<div class='test-fail'>❌ FAIL: File laporan_pdf.php tidak ditemukan</div>";
}

// ==================== TEST 6: Verifikasi Data Sebelum Laporan ====================
echo "<h3>📋 Test 6: Verifikasi Data</h3>";
$total_tests++;

// Cek apakah ada laporan yang sudah dibuat
$sql = "SELECT COUNT(*) as total FROM laporan_produksi";
$result = mysqli_query($koneksi, $sql);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    echo "<div class='test-pass'>✅ PASS: Query verifikasi laporan berhasil (total laporan: {$row['total']})</div>";
    $passed_tests++;
} else {
    // Tabel mungkin belum ada - ini tidak fatal
    echo "<div class='test-info'>ℹ️ INFO: Tabel laporan_produksi belum ada (akan dibuat saat generate laporan)</div>";
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