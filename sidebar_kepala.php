<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar">
    <!-- Header dengan informasi user -->
    <div class="sidebar-header">
        <div class="user-avatar">
            <?php echo substr($_SESSION['nama_lengkap'], 0, 1); ?>
        </div>
        <h3><?php echo $_SESSION['nama_lengkap']; ?></h3>
        <p>Kepala Produksi</p>
    </div>
    
    <!-- Menu Sidebar -->
    <div class="sidebar-menu">
        <ul>
            <li>
                <a href="../kepala/dashboard.php" class="<?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="../kepala/rekap.php" class="<?php echo $current_page == 'rekap.php' ? 'active' : ''; ?>">
                    <i class="bi bi-file-text"></i> Rekap Data
                </a>
            </li>
            <li>
                <a href="../kepala/koreksi.php" class="<?php echo $current_page == 'koreksi.php' ? 'active' : ''; ?>">
                    <i class="bi bi-pencil-square"></i> Koreksi Data
                </a>
            </li>
            <li>
                <a href="../kepala/pelanggan.php" class="<?php echo $current_page == 'pelanggan.php' ? 'active' : ''; ?>">
                    <i class="bi bi-people"></i> Data Pelanggan
                </a>
            </li>
            <li>
                <a href="../kepala/feedback.php" class="<?php echo $current_page == 'feedback.php' ? 'active' : ''; ?>">
                    <i class="bi bi-chat-dots"></i> Feedback
                </a>
            </li>
            <li>
                <a href="../kepala/laporan.php" class="<?php echo $current_page == 'laporan.php' ? 'active' : ''; ?>">
                    <i class="bi bi-file-earmark-pdf"></i> Laporan
                </a>
            </li>
        </ul>
    </div>
    
    <!-- Footer dengan Logout -->
    <div class="sidebar-footer">
        <a href="../logout.php">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>
</div>
<div class="main-content">