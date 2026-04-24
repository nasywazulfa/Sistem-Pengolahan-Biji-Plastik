<?php
require_once '../functions.php';
cekAkses(['sopir', 'kepala_produksi']);

$title = 'Data Pengiriman';
$user_id = $_SESSION['user_id'];

// Ambil data pengiriman
$sql = "SELECT * FROM pengiriman WHERE user_id = $user_id ORDER BY tanggal DESC";
$result = query($sql);

include_once '../includes/header.php';
?>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    }
    
    body {
        background: #f5f5f5;
        overflow-x: hidden;
    }
    
    /* Layout */
    .app-wrapper {
        display: flex;
        min-height: 100vh;
    }
    
    /* ==================== SIDEBAR ==================== */
    .sidebar {
        width: 280px;
        background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        flex-direction: column;
        position: fixed;
        left: 0;
        top: 0;
        bottom: 0;
        overflow-y: auto;
        box-shadow: 2px 0 20px rgba(0,0,0,0.1);
        z-index: 1000;
    }
    
    .sidebar-header {
        padding: 30px 20px;
        text-align: center;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    
    .sidebar-header .logo {
        font-size: 50px;
        margin-bottom: 15px;
        color: white;
    }
    
    .sidebar-header h3 {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .sidebar-header p {
        font-size: 14px;
        opacity: 0.8;
    }
    
    .sidebar-menu {
        flex: 1;
        padding: 20px 0;
    }
    
    .sidebar-menu ul {
        list-style: none;
    }
    
    .sidebar-menu ul li {
        margin-bottom: 5px;
    }
    
    .sidebar-menu ul li a {
        display: flex;
        align-items: center;
        padding: 15px 25px;
        color: rgba(255,255,255,0.8);
        text-decoration: none;
        font-size: 16px;
        transition: all 0.3s;
        border-left: 4px solid transparent;
    }
    
    .sidebar-menu ul li a:hover {
        background: rgba(255,255,255,0.1);
        color: white;
        border-left-color: white;
    }
    
    .sidebar-menu ul li a.active {
        background: rgba(255,255,255,0.15);
        color: white;
        border-left-color: white;
        font-weight: 600;
    }
    
    .sidebar-menu ul li a i {
        margin-right: 15px;
        font-size: 20px;
        width: 25px;
        text-align: center;
    }
    
    .sidebar-footer {
        padding: 20px;
        border-top: 1px solid rgba(255,255,255,0.1);
    }
    
    .sidebar-footer a {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        color: rgba(255,255,255,0.8);
        text-decoration: none;
        font-size: 16px;
        border-radius: 10px;
        transition: all 0.3s;
    }
    
    .sidebar-footer a:hover {
        background: rgba(255,255,255,0.1);
        color: white;
    }
    
    .sidebar-footer a i {
        margin-right: 15px;
        font-size: 20px;
        width: 25px;
        text-align: center;
    }
    
    /* ==================== MAIN CONTENT ==================== */
    .main-content {
        flex: 1;
        margin-left: 280px;
        width: calc(100% - 280px);
        background: #f5f5f5;
    }
    
    /* Header */
    .main-header {
        background: white;
        padding: 20px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .header-title h1 {
        font-size: 24px;
        font-weight: 600;
        color: #333;
    }
    
    .header-title p {
        font-size: 14px;
        color: #666;
        margin-top: 5px;
    }
    
    .header-user {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .user-info {
        text-align: right;
    }
    
    .user-info .name {
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }
    
    .user-info .role {
        font-size: 14px;
        color: #666;
    }
    
    .user-avatar {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        font-weight: 600;
    }
    
    /* Content Wrapper */
    .content-wrapper {
        padding: 30px;
    }
    
    /* Section Title */
    .section-title {
        font-size: 24px;
        font-weight: 700;
        color: #333;
        margin-bottom: 5px;
    }
    
    .section-subtitle {
        font-size: 16px;
        color: #666;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e0e0e0;
    }
    
    /* Data Cards */
    .data-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid #f0f0f0;
        transition: all 0.3s;
    }
    
    .data-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .card-title {
        font-size: 22px;
        font-weight: 700;
        color: #333;
    }
    
    .card-title i {
        margin-right: 10px;
        color: #667eea;
    }
    
    .edit-btn {
        background: #ffc107;
        color: #333;
        padding: 8px 20px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    .edit-btn:hover {
        background: #e0a800;
    }
    
    .card-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }
    
    .detail-item {
        padding: 15px;
        background: #f8f9fa;
        border-radius: 12px;
    }
    
    .detail-label {
        font-size: 13px;
        color: #666;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .detail-value {
        font-size: 16px;
        font-weight: 600;
        color: #333;
        line-height: 1.4;
    }
    
    .detail-value.highlight {
        color: #667eea;
        font-size: 18px;
    }
    
    /* Status Badge */
    .status-badge {
        display: inline-block;
        padding: 6px 15px;
        border-radius: 30px;
        font-size: 14px;
        font-weight: 500;
    }
    
    .status-menunggu {
        background: #fff3cd;
        color: #856404;
    }
    
    .status-dalam {
        background: #cce5ff;
        color: #004085;
    }
    
    .status-selesai {
        background: #d4edda;
        color: #155724;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 15px;
        color: #999;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .empty-state i {
        font-size: 60px;
        margin-bottom: 20px;
        color: #ccc;
    }
    
    .empty-state h4 {
        font-size: 20px;
        color: #666;
        margin-bottom: 10px;
    }
    
    .empty-state p {
        font-size: 14px;
        color: #999;
    }
    
    /* Button Kembali */
    .btn-back {
        display: inline-block;
        padding: 10px 20px;
        background: #667eea;
        color: white;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        margin-top: 20px;
        transition: all 0.3s;
    }
    
    .btn-back:hover {
        background: #5a67d8;
        transform: translateY(-2px);
    }
</style>

<div class="app-wrapper">
    <!-- ==================== SIDEBAR ==================== -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <i class="bi bi-recycle"></i>
            </div>
            <h3><?php echo $_SESSION['nama_lengkap']; ?></h3>
            <p>Sopir</p>
        </div>
        
        <div class="sidebar-menu">
            <ul>
                <li>
                    <a href="dashboard.php">
                        <i class="bi bi-speedometer2"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="pengiriman.php" class="active">
                        <i class="bi bi-truck"></i>
                        Data Pengiriman
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="sidebar-footer">
            <a href="../logout.php">
                <i class="bi bi-box-arrow-right"></i>
                Logout
            </a>
        </div>
    </div>
    
    <!-- ==================== MAIN CONTENT ==================== -->
    <div class="main-content">
        <!-- Header -->
        <div class="main-header">
            <div class="header-title">
                <h1>Data Pengiriman</h1>
                <p>Kelola data pengiriman anda</p>
            </div>
            <div class="header-user">
                <div class="user-info">
                    <div class="name"><?php echo $_SESSION['nama_lengkap']; ?></div>
                    <div class="role">Sopir</div>
                </div>
                <div class="user-avatar">
                    <?php echo substr($_SESSION['nama_lengkap'], 0, 1); ?>
                </div>
            </div>
        </div>
        
        <!-- Content Wrapper -->
        <div class="content-wrapper">
            
            <div class="section-title">Data Pengiriman</div>
            <div class="section-subtitle">Daftar semua pengiriman anda</div>
            
            <?php if ($result && numRows($result) > 0): ?>
                <?php while($row = fetch($result)): ?>
                <!-- Data Card -->
                <div class="data-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="bi bi-box-seam"></i>
                            <?php echo $row['jenis_plastik']; ?>
                        </div>
                        <a href="#" class="edit-btn">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                    </div>
                    
                    <div class="card-details">
                        <div class="detail-item">
                            <div class="detail-label">Perusahaan</div>
                            <div class="detail-value"><?php echo $row['perusahaan']; ?></div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">Jumlah</div>
                            <div class="detail-value highlight"><?php echo angka($row['jumlah_muatan'], 2); ?> kg</div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">Tujuan</div>
                            <div class="detail-value"><?php echo $row['tujuan']; ?></div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">Tanggal</div>
                            <div class="detail-value"><?php echo tglIndo($row['tanggal']); ?></div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">Status</div>
                            <div class="detail-value">
                                <?php
                                $status_class = '';
                                if ($row['status'] == 'Menunggu') $status_class = 'status-menunggu';
                                elseif ($row['status'] == 'Dalam Perjalanan') $status_class = 'status-dalam';
                                elseif ($row['status'] == 'Selesai') $status_class = 'status-selesai';
                                ?>
                                <span class="status-badge <?php echo $status_class; ?>">
                                    <?php echo $row['status']; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
                
                <!-- Tombol Kembali ke Dashboard -->
                <div style="text-align: center; margin-top: 30px;">
                    <a href="dashboard.php" class="btn-back">
                        <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
                    </a>
                </div>
                
            <?php else: ?>
                <div class="empty-state">
                    <i class="bi bi-truck"></i>
                    <h4>Belum ada data pengiriman</h4>
                    <p>Silakan tambah data pengiriman baru di halaman dashboard</p>
                    <a href="dashboard.php" class="btn-back" style="margin-top: 20px;">
                        <i class="bi bi-plus-circle"></i> Tambah Pengiriman
                    </a>
                </div>
            <?php endif; ?>
            
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.js"></script>
<?php include_once '../includes/footer.php'; ?>