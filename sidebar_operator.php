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
        <p>Operator Mesin</p>
    </div>
    
    <!-- Menu Sidebar -->
    <div class="sidebar-menu">
        <ul>
            <li>
                <a href="../operator/dashboard.php" class="<?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="../operator/produksi.php" class="<?php echo $current_page == 'produksi.php' ? 'active' : ''; ?>">
                    <i class="bi bi-gear"></i> Hasil Produksi
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