<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    $path = '';
    if (strpos($_SERVER['PHP_SELF'], '/penyortir/') !== false) $path = '../';
    elseif (strpos($_SERVER['PHP_SELF'], '/sopir/') !== false) $path = '../';
    elseif (strpos($_SERVER['PHP_SELF'], '/operator/') !== false) $path = '../';
    elseif (strpos($_SERVER['PHP_SELF'], '/kepala/') !== false) $path = '../';
    header("Location: " . $path . "login.php");
    exit;
}

$role = $_SESSION['role'];
$nama = $_SESSION['nama_lengkap'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' - ' : ''; ?><?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
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
        
        .sidebar-header .user-avatar {
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 36px;
            font-weight: 600;
            color: white;
            border: 3px solid rgba(255,255,255,0.5);
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
            min-height: 100vh;
        }
        
        .content-wrapper {
            padding: 30px;
        }
    </style>
</head>
<body>