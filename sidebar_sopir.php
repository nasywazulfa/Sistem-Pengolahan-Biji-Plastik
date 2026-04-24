<div class="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <i class="bi bi-truck"></i>
        </div>
        <h3><?php echo $_SESSION['nama_lengkap']; ?></h3>
        <p>Sopir</p>
    </div>
    
    <div class="sidebar-menu">
        <ul>
            <li>
                <a href="../sopir/dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="../sopir/pengiriman.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'pengiriman.php' ? 'active' : ''; ?>">
                    <i class="bi bi-truck"></i> Data Pengiriman
                </a>
            </li>
        </ul>
    </div>
    
    <div class="sidebar-footer">
        <a href="../logout.php">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>
</div>
<div class="main-content">