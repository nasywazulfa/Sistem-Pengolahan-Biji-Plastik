<?php
/**
 * UNIT TESTING - RUNNER SEMUA MODUL
 * 
 * Menjalankan semua unit testing sekaligus
 */

echo "<!DOCTYPE html>";
echo "<html><head><title>Unit Testing - All Modules</title>";
echo "<style>
    body { font-family: monospace; padding: 20px; background: #1a1a2e; color: #eee; }
    .container { max-width: 1200px; margin: 0 auto; }
    .module { background: #16213e; margin: 20px 0; padding: 20px; border-radius: 10px; }
    .module h2 { color: #0f3460; border-bottom: 2px solid #0f3460; padding-bottom: 10px; }
    .test-pass { color: #4cd964; }
    .test-fail { color: #ff3b30; }
    .summary { background: #0f3460; padding: 15px; border-radius: 10px; margin-top: 20px; text-align: center; }
    h1 { text-align: center; color: #e94560; }
    hr { border-color: #0f3460; }
    iframe { width: 100%; min-height: 600px; border: none; background: white; border-radius: 10px; }
</style>";
echo "</head><body>";

echo "<div class='container'>";
echo "<h1>🧪 UNIT TESTING - SISTEM INFORMASI PLASTIK</h1>";
echo "<p style='text-align: center;'>Menjalankan semua unit testing untuk 8 modul</p>";
echo "<hr>";

$modules = [
    'login' => 'test_login.php',
    'sortir' => 'test_sortir.php',
    'pengiriman' => 'test_pengiriman.php',
    'produksi' => 'test_produksi.php',
    'laporan' => 'test_laporan.php',
    'koreksi' => 'test_koreksi.php',
    'pelanggan' => 'test_pelanggan.php',
    'feedback' => 'test_feedback.php',
    'dashboard' => 'test_dashboard.php'
];

foreach($modules as $name => $file) {
    $file_path = __DIR__ . '/' . $file;
    if(file_exists($file_path)) {
        echo "<div class='module'>";
        echo "<h2>📋 Modul " . ucfirst($name) . "</h2>";
        
        // Capture output
        ob_start();
        include $file_path;
        $output = ob_get_clean();
        
        // Extract summary
        if(preg_match('/Total Tests: (\d+).*Passed: (\d+)/s', $output, $matches)) {
            $total = $matches[1] ?? 0;
            $passed = $matches[2] ?? 0;
            $percentage = $total > 0 ? ($passed / $total) * 100 : 0;
            
            $color = $percentage >= 90 ? '#4cd964' : ($percentage >= 70 ? '#ffcc00' : '#ff3b30');
            echo "<div style='margin-bottom: 10px;'>";
            echo "<span style='color: $color; font-weight: bold;'>📊 Coverage: " . number_format($percentage, 2) . "%</span>";
            echo " ($passed/$total tests passed)";
            echo "</div>";
        }
        
        echo "<details>";
        echo "<summary style='cursor: pointer; color: #e94560;'>Detail Output</summary>";
        echo "<div style='margin-top: 10px; background: white; padding: 15px; border-radius: 5px; color: #333;'>";
        echo $output;
        echo "</div>";
        echo "</details>";
        echo "</div>";
    } else {
        echo "<div class='module'>";
        echo "<h2>📋 Modul " . ucfirst($name) . "</h2>";
        echo "<div class='test-fail'>⚠️ File $file tidak ditemukan</div>";
        echo "</div>";
    }
}

echo "<div class='summary'>";
echo "<strong>✅ Unit Testing Selesai!</strong><br>";
echo "Pastikan semua modul memiliki coverage minimal 90% sesuai Definition of Done.<br>";
echo "Total modul: " . count($modules);
echo "</div>";

echo "</div>";
echo "</body></html>";
?>