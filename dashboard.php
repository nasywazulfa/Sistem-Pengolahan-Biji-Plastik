<?php
require_once '../functions.php';
cekAkses(['kepala_produksi']);

$title = 'Dashboard Kepala Produksi';

// Statistik Utama
$total_sortir = 0;
$total_pengiriman = 0;
$total_produksi = 0;
$total_pelanggan = 0;
$feedback_baru = 0;
$mesin_rusak = 0;

$sql_sortir = "SELECT COALESCE(SUM(jumlah), 0) as total FROM hasil_sortir";
$result_sortir = query($sql_sortir);
if ($result_sortir) {
    $data = fetch($result_sortir);
    $total_sortir = $data['total'] ?? 0;
}

$sql_pengiriman = "SELECT COALESCE(SUM(jumlah_muatan), 0) as total FROM pengiriman";
$result_pengiriman = query($sql_pengiriman);
if ($result_pengiriman) {
    $data = fetch($result_pengiriman);
    $total_pengiriman = $data['total'] ?? 0;
}

$sql_produksi = "SELECT COALESCE(SUM(jumlah_hasil), 0) as total FROM hasil_produksi";
$result_produksi = query($sql_produksi);
if ($result_produksi) {
    $data = fetch($result_produksi);
    $total_produksi = $data['total'] ?? 0;
}

$sql_pelanggan = "SELECT COUNT(*) as total FROM pelanggan";
$result_pelanggan = query($sql_pelanggan);
if ($result_pelanggan) {
    $data = fetch($result_pelanggan);
    $total_pelanggan = $data['total'] ?? 0;
}

$sql_feedback = "SELECT COUNT(*) as total FROM feedback WHERE status = 'Baru'";
$result_feedback = query($sql_feedback);
if ($result_feedback) {
    $data = fetch($result_feedback);
    $feedback_baru = $data['total'] ?? 0;
}

$sql_mesin = "SELECT COUNT(*) as total FROM hasil_produksi WHERE kondisi_mesin = 'Rusak'";
$result_mesin = query($sql_mesin);
if ($result_mesin) {
    $data = fetch($result_mesin);
    $mesin_rusak = $data['total'] ?? 0;
}

include_once '../includes/header.php';
include_once '../includes/sidebar_kepala.php';
?>

<style>
    .content-wrapper {
        padding: 30px;
    }
    
    .page-title {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .page-title h1 {
        font-size: 28px;
        font-weight: 700;
        color: #333;
    }
    
    .page-title .date {
        font-size: 16px;
        color: #666;
        background: #f0f0f0;
        padding: 8px 15px;
        border-radius: 8px;
    }
    
    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        border: 1px solid #f5c6cb;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .alert-danger i {
        font-size: 24px;
    }
    
    .alert-warning {
        background: #fff3cd;
        color: #856404;
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        border: 1px solid #ffeeba;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .alert-warning i {
        font-size: 24px;
    }
    
    .action-buttons {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        margin: 30px 0;
    }
    
    .btn-action {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 12px 25px;
        border-radius: 8px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }
    
    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid #f0f0f0;
        border-left: 5px solid #667eea;
    }
    
    .stat-card .stat-label {
        font-size: 14px;
        color: #666;
        margin-bottom: 10px;
    }
    
    .stat-card .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: #333;
    }
    
    .stat-card .stat-unit {
        font-size: 14px;
        color: #999;
        margin-left: 5px;
    }
</style>

<div class="content-wrapper">
    
    <!-- Page Title -->
    <div class="page-title">
        <h1>Dashboard Kepala Produksi</h1>
        <div class="date"><?php echo tglIndo(date('Y-m-d')); ?></div>
    </div>
    
    <?php showAlert(); ?>
    
    <!-- Alerts -->
    <?php if ($mesin_rusak > 0): ?>
    <div class="alert-danger">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div>
            <strong>PERHATIAN!</strong> Terdapat <?php echo $mesin_rusak; ?> mesin dalam kondisi RUSAK!
        </div>
    </div>
    <?php endif; ?>
    
    <?php if ($feedback_baru > 0): ?>
    <div class="alert-warning">
        <i class="bi bi-chat-dots-fill"></i>
        <div>
            <strong>Informasi!</strong> Ada <?php echo $feedback_baru; ?> feedback baru dari pelanggan.
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="rekap.php" class="btn-action">
            <i class="bi bi-file-text"></i> Lihat Rekap
        </a>
        <a href="laporan.php" class="btn-action">
            <i class="bi bi-file-earmark-pdf"></i> Generate Laporan
        </a>
        <a href="feedback.php" class="btn-action">
            <i class="bi bi-chat-dots"></i> Lihat Feedback
        </a>
        <a href="koreksi.php" class="btn-action">
            <i class="bi bi-pencil-square"></i> Koreksi Data
        </a>
    </div>
    
    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Sortir</div>
            <div class="stat-value"><?php echo angka($total_sortir, 2); ?><span class="stat-unit">kg</span></div>
        </div>
        
        <div class="stat-card">
            <div class="stat-label">Total Pengiriman</div>
            <div class="stat-value"><?php echo angka($total_pengiriman, 2); ?><span class="stat-unit">kg</span></div>
        </div>
        
        <div class="stat-card">
            <div class="stat-label">Total Produksi</div>
            <div class="stat-value"><?php echo angka($total_produksi, 2); ?><span class="stat-unit">kg</span></div>
        </div>
        
        <div class="stat-card">
            <div class="stat-label">Total Pelanggan</div>
            <div class="stat-value"><?php echo $total_pelanggan; ?></div>
        </div>
    </div>
    
</div>

<?php include_once '../includes/footer.php'; ?>