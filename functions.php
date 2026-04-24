<?php
require_once __DIR__ . '/config.php';

// ==================== AUTHENTIKASI ====================

function isLogin() {
    return isset($_SESSION['user_id']);
}

function cekRole($role) {
    return (isset($_SESSION['role']) && $_SESSION['role'] == $role);
}

function redirect($url) {
    if (!headers_sent()) {
        header("Location: $url");
        exit;
    } else {
        echo "<script>window.location.href='$url';</script>";
        exit;
    }
}

function logout() {
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    redirect('login.php');
}

// ==================== ALERT ====================

function setAlert($pesan, $tipe = 'success') {
    $_SESSION['alert'] = [
        'pesan' => $pesan,
        'tipe' => $tipe
    ];
}

function showAlert() {
    if (isset($_SESSION['alert'])) {
        $pesan = htmlspecialchars($_SESSION['alert']['pesan']);
        $tipe = $_SESSION['alert']['tipe'];
        
        $bgColor = '#28a745';
        if ($tipe == 'danger') $bgColor = '#dc3545';
        elseif ($tipe == 'warning') $bgColor = '#ffc107';
        elseif ($tipe == 'info') $bgColor = '#17a2b8';
        
        echo "<div style='background: $bgColor; color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; font-weight: 600;'>
                <i class='bi bi-check-circle'></i> $pesan
              </div>";
        
        unset($_SESSION['alert']);
    }
}

// ==================== DATABASE ====================

function esc($string) {
    global $koneksi;
    return mysqli_real_escape_string($koneksi, trim($string));
}

function query($sql) {
    global $koneksi;
    mysqli_report(MYSQLI_REPORT_OFF);
    $result = mysqli_query($koneksi, $sql);
    if (!$result) {
        error_log("Query Error: " . mysqli_error($koneksi) . " SQL: " . $sql);
    }
    return $result;
}

function fetch($result) {
    return mysqli_fetch_assoc($result);
}

function fetchAll($result) {
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function numRows($result) {
    return mysqli_num_rows($result);
}

function lastId() {
    global $koneksi;
    return mysqli_insert_id($koneksi);
}

// ==================== INPUT ====================

function isPost() {
    return $_SERVER['REQUEST_METHOD'] == 'POST';
}

function post($key, $default = '') {
    return isset($_POST[$key]) ? trim($_POST[$key]) : $default;
}

function get($key, $default = '') {
    return isset($_GET[$key]) ? trim($_GET[$key]) : $default;
}

// ==================== FORMAT DATA ====================

function tglIndo($tanggal) {
    if (!$tanggal || $tanggal == '0000-00-00') return '-';
    
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    
    $tgl = explode('-', $tanggal);
    return $tgl[2] . ' ' . $bulan[(int)$tgl[1]] . ' ' . $tgl[0];
}

function tglWaktuIndo($datetime) {
    if (!$datetime) return '-';
    return date('d-m-Y H:i', strtotime($datetime));
}

function angka($angka, $desimal = 0) {
    return number_format((float)$angka, $desimal, ',', '.');
}

function rupiah($angka) {
    return 'Rp ' . number_format((float)$angka, 0, ',', '.');
}

// ==================== CEK AKSES ====================

function cekAkses($role_diperbolehkan) {
    if (!isLogin()) {
        setAlert('Silakan login terlebih dahulu!', 'danger');
        $path = '';
        if (strpos($_SERVER['PHP_SELF'], '/penyortir/') !== false ||
            strpos($_SERVER['PHP_SELF'], '/sopir/') !== false ||
            strpos($_SERVER['PHP_SELF'], '/operator/') !== false ||
            strpos($_SERVER['PHP_SELF'], '/kepala/') !== false) {
            $path = '../';
        }
        redirect($path . 'login.php');
    }
    
    if (!in_array($_SESSION['role'], $role_diperbolehkan)) {
        setAlert('Anda tidak memiliki akses ke halaman ini!', 'danger');
        if ($_SESSION['role'] == 'penyortir') {
            redirect('penyortir/dashboard.php');
        } elseif ($_SESSION['role'] == 'sopir') {
            redirect('sopir/dashboard.php');
        } elseif ($_SESSION['role'] == 'operator_mesin') {
            redirect('operator/dashboard.php');
        } else {
            redirect('kepala/dashboard.php');
        }
    }
}

// ==================== LOG ACTIVITY ====================

function logActivity($aktivitas, $tabel = null, $data_id = null) {
    global $koneksi;
    
    if (!isset($_SESSION['user_id'])) return;
    
    $user_id = (int)$_SESSION['user_id'];
    $aktivitas = mysqli_real_escape_string($koneksi, $aktivitas);
    $tabel = $tabel ? "'" . mysqli_real_escape_string($koneksi, $tabel) . "'" : "NULL";
    $data_id = $data_id ? (int)$data_id : "NULL";
    $ip = mysqli_real_escape_string($koneksi, $_SERVER['REMOTE_ADDR'] ?? '');
    $user_agent = mysqli_real_escape_string($koneksi, $_SERVER['HTTP_USER_AGENT'] ?? '');
    
    $sql = "INSERT INTO log_aktivitas (user_id, aktivitas, tabel, data_id, ip_address, user_agent) 
            VALUES ($user_id, '$aktivitas', $tabel, $data_id, '$ip', '$user_agent')";
    
    @mysqli_query($koneksi, $sql);
}

// ==================== NOTIFIKASI ====================

function addNotifikasi($user_id, $judul, $pesan, $tipe = 'info') {
    global $koneksi;
    
    $user_id = (int)$user_id;
    $judul = mysqli_real_escape_string($koneksi, $judul);
    $pesan = mysqli_real_escape_string($koneksi, $pesan);
    $tipe = mysqli_real_escape_string($koneksi, $tipe);
    
    $sql = "INSERT INTO notifikasi (user_id, judul, pesan, tipe) 
            VALUES ($user_id, '$judul', '$pesan', '$tipe')";
    
    return mysqli_query($koneksi, $sql);
}

function getNotifikasi($user_id, $limit = 5) {
    global $koneksi;
    $user_id = (int)$user_id;
    $sql = "SELECT * FROM notifikasi WHERE user_id = $user_id ORDER BY created_at DESC LIMIT $limit";
    return mysqli_query($koneksi, $sql);
}

function countNotifikasiUnread($user_id) {
    global $koneksi;
    $user_id = (int)$user_id;
    $sql = "SELECT COUNT(*) as total FROM notifikasi WHERE user_id = $user_id AND is_read = 0";
    $result = mysqli_query($koneksi, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        return (int)$data['total'];
    }
    return 0;
}

// ==================== SETTING ====================

function getSetting($nama) {
    global $koneksi;
    $nama = mysqli_real_escape_string($koneksi, $nama);
    $sql = "SELECT nilai_setting FROM setting WHERE nama_setting = '$nama'";
    $result = mysqli_query($koneksi, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        return $data['nilai_setting'];
    }
    return null;
}
?>