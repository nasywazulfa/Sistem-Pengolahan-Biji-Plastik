<?php
require_once '../functions.php';
cekAkses(['kepala_produksi']);

$title = 'Generate Laporan';

$tipe = isset($_GET['tipe']) ? $_GET['tipe'] : 'semua';
$dari = isset($_GET['dari']) ? $_GET['dari'] : date('Y-m-01');
$sampai = isset($_GET['sampai']) ? $_GET['sampai'] : date('Y-m-d');

// Ambil data untuk preview
if ($tipe == 'semua' || $tipe == 'sortir') {
    $sql_sortir = "SELECT hs.*, u.nama_lengkap 
                   FROM hasil_sortir hs 
                   JOIN users u ON hs.user_id = u.id 
                   WHERE hs.tanggal BETWEEN '$dari' AND '$sampai'
                   ORDER BY hs.tanggal DESC";
    $result_sortir = query($sql_sortir);
    
    $sql_total_sortir = "SELECT COALESCE(SUM(jumlah), 0) as total 
                         FROM hasil_sortir 
                         WHERE tanggal BETWEEN '$dari' AND '$sampai'";
    $result_total_sortir = query($sql_total_sortir);
    $total_sortir = 0;
    if ($result_total_sortir) {
        $data = fetch($result_total_sortir);
        $total_sortir = $data['total'] ?? 0;
    }
}

if ($tipe == 'semua' || $tipe == 'pengiriman') {
    $sql_pengiriman = "SELECT p.*, u.nama_lengkap 
                       FROM pengiriman p 
                       JOIN users u ON p.user_id = u.id 
                       WHERE p.tanggal BETWEEN '$dari' AND '$sampai'
                       ORDER BY p.tanggal DESC";
    $result_pengiriman = query($sql_pengiriman);
    
    $sql_total_pengiriman = "SELECT COALESCE(SUM(jumlah_muatan), 0) as total 
                             FROM pengiriman 
                             WHERE tanggal BETWEEN '$dari' AND '$sampai'";
    $result_total_pengiriman = query($sql_total_pengiriman);
    $total_pengiriman = 0;
    if ($result_total_pengiriman) {
        $data = fetch($result_total_pengiriman);
        $total_pengiriman = $data['total'] ?? 0;
    }
}

if ($tipe == 'semua' || $tipe == 'produksi') {
    $sql_produksi = "SELECT hp.*, u.nama_lengkap 
                     FROM hasil_produksi hp 
                     JOIN users u ON hp.user_id = u.id 
                     WHERE hp.tgl_produksi BETWEEN '$dari' AND '$sampai'
                     ORDER BY hp.tgl_produksi DESC";
    $result_produksi = query($sql_produksi);
    
    $sql_total_produksi = "SELECT COALESCE(SUM(jumlah_hasil), 0) as total 
                           FROM hasil_produksi 
                           WHERE tgl_produksi BETWEEN '$dari' AND '$sampai'";
    $result_total_produksi = query($sql_total_produksi);
    $total_produksi = 0;
    if ($result_total_produksi) {
        $data = fetch($result_total_produksi);
        $total_produksi = $data['total'] ?? 0;
    }
}

include_once '../includes/header.php';
include_once '../includes/sidebar_kepala.php';
?>

<style>
    .content-wrapper {
        padding: 30px;
    }
    
    .filter-container {
        background: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid #f0f0f0;
    }
    
    .filter-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin-bottom: 20px;
    }
    
    .filter-form {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        align-items: flex-end;
    }
    
    .filter-group {
        flex: 1;
        min-width: 150px;
    }
    
    .filter-group label {
        display: block;
        font-size: 13px;
        font-weight: 500;
        color: #555;
        margin-bottom: 5px;
    }
    
    .filter-group input,
    .filter-group select {
        width: 100%;
        padding: 10px 12px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
    }
    
    .filter-group input:focus,
    .filter-group select:focus {
        border-color: #667eea;
        outline: none;
    }
    
    .btn-filter {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 11px 25px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
    }
    
    .report-container {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid #f0f0f0;
    }
    
    .report-header {
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .report-header h2 {
        font-size: 24px;
        color: #333;
        margin-bottom: 5px;
    }
    
    .report-header h3 {
        font-size: 20px;
        color: #666;
        margin-bottom: 10px;
    }
    
    .report-header p {
        color: #888;
    }
    
    .summary-cards {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
        justify-content: center;
    }
    
    .summary-card {
        flex: 1;
        max-width: 200px;
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
    }
    
    .summary-card .label {
        font-size: 14px;
        color: #666;
        margin-bottom: 10px;
    }
    
    .summary-card .value {
        font-size: 24px;
        font-weight: 700;
        color: #667eea;
    }
    
    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin: 25px 0 15px 0;
    }
    
    .table-container {
        overflow-x: auto;
        margin-bottom: 25px;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }
    
    th {
        background: #667eea;
        color: white;
        padding: 12px;
        text-align: left;
    }
    
    td {
        padding: 10px 12px;
        border-bottom: 1px solid #f0f0f0;
    }
    
    tfoot {
        background: #f8f9fa;
        font-weight: 700;
    }
    
    .signature {
        margin-top: 50px;
        display: flex;
        justify-content: flex-end;
    }
    
    .signature-box {
        text-align: center;
        width: 250px;
    }
    
    .signature-box p {
        margin-bottom: 5px;
    }
    
    .signature-line {
        margin-top: 40px;
        margin-bottom: 5px;
    }
    
    .btn-export {
        background: #28a745;
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 8px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 600;
        margin-right: 10px;
    }
    
    .btn-print {
        background: #17a2b8;
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 8px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 600;
    }
    
    .action-buttons {
        margin-bottom: 20px;
        text-align: right;
    }
</style>

<div class="content-wrapper">
    
    <h1 style="font-size: 28px; margin-bottom: 20px;">Generate Laporan</h1>
    
    <!-- Filter Form -->
    <div class="filter-container">
        <div class="filter-title">Pilih Parameter Laporan</div>
        <form method="GET" class="filter-form">
            <div class="filter-group">
                <label>Jenis Laporan</label>
                <select name="tipe">
                    <option value="semua" <?php echo $tipe == 'semua' ? 'selected' : ''; ?>>Laporan Lengkap</option>
                    <option value="sortir" <?php echo $tipe == 'sortir' ? 'selected' : ''; ?>>Laporan Sortir</option>
                    <option value="pengiriman" <?php echo $tipe == 'pengiriman' ? 'selected' : ''; ?>>Laporan Pengiriman</option>
                    <option value="produksi" <?php echo $tipe == 'produksi' ? 'selected' : ''; ?>>Laporan Produksi</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Dari Tanggal</label>
                <input type="date" name="dari" value="<?php echo $dari; ?>">
            </div>
            <div class="filter-group">
                <label>Sampai Tanggal</label>
                <input type="date" name="sampai" value="<?php echo $sampai; ?>">
            </div>
            <div class="filter-group">
                <button type="submit" class="btn-filter">
                    <i class="bi bi-search"></i> Generate
                </button>
            </div>
        </form>
    </div>
    
    <?php showAlert(); ?>
    
    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="laporan_pdf.php?tipe=<?php echo $tipe; ?>&dari=<?php echo $dari; ?>&sampai=<?php echo $sampai; ?>" class="btn-export" target="_blank">
            <i class="bi bi-file-pdf"></i> Export PDF
        </a>
        <a href="#" onclick="window.print()" class="btn-print">
            <i class="bi bi-printer"></i> Cetak
        </a>
    </div>
    
    <!-- Preview Laporan -->
    <div class="report-container" id="laporan">
        <div class="report-header">
            <h2><?php echo APP_NAME; ?></h2>
            <h3>LAPORAN <?php echo strtoupper($tipe); ?></h3>
            <p>Periode: <?php echo tglIndo($dari); ?> s/d <?php echo tglIndo($sampai); ?></p>
        </div>
        
        <!-- Summary -->
        <div class="summary-cards">
            <?php if ($tipe == 'semua' || $tipe == 'sortir'): ?>
            <div class="summary-card">
                <div class="label">Total Sortir</div>
                <div class="value"><?php echo angka($total_sortir, 2); ?> kg</div>
            </div>
            <?php endif; ?>
            
            <?php if ($tipe == 'semua' || $tipe == 'pengiriman'): ?>
            <div class="summary-card">
                <div class="label">Total Pengiriman</div>
                <div class="value"><?php echo angka($total_pengiriman, 2); ?> kg</div>
            </div>
            <?php endif; ?>
            
            <?php if ($tipe == 'semua' || $tipe == 'produksi'): ?>
            <div class="summary-card">
                <div class="label">Total Produksi</div>
                <div class="value"><?php echo angka($total_produksi, 2); ?> kg</div>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Data Sortir -->
        <?php if (($tipe == 'semua' || $tipe == 'sortir') && isset($result_sortir) && numRows($result_sortir) > 0): ?>
        <div class="section-title">A. DATA SORTIR</div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Penyortir</th>
                        <th>Jenis Plastik</th>
                        <th>Kualitas</th>
                        <th>Jumlah (kg)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    mysqli_data_seek($result_sortir, 0);
                    while($row = fetch($result_sortir)): 
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo tglIndo($row['tanggal']); ?></td>
                        <td><?php echo $row['nama_lengkap']; ?></td>
                        <td><?php echo $row['jenis_plastik']; ?></td>
                        <td><?php echo $row['kualitas']; ?></td>
                        <td style="text-align: right;"><?php echo angka($row['jumlah'], 2); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" style="text-align: right;">TOTAL</td>
                        <td style="text-align: right;"><?php echo angka($total_sortir, 2); ?> kg</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php endif; ?>
        
        <!-- Data Pengiriman -->
        <?php if (($tipe == 'semua' || $tipe == 'pengiriman') && isset($result_pengiriman) && numRows($result_pengiriman) > 0): ?>
        <div class="section-title">B. DATA PENGIRIMAN</div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Sopir</th>
                        <th>Perusahaan</th>
                        <th>Tujuan</th>
                        <th>Jenis</th>
                        <th>Berat (kg)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    mysqli_data_seek($result_pengiriman, 0);
                    while($row = fetch($result_pengiriman)): 
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo tglIndo($row['tanggal']); ?></td>
                        <td><?php echo $row['nama_lengkap']; ?></td>
                        <td><?php echo $row['perusahaan']; ?></td>
                        <td><?php echo $row['tujuan']; ?></td>
                        <td><?php echo $row['jenis_plastik']; ?></td>
                        <td style="text-align: right;"><?php echo angka($row['jumlah_muatan'], 2); ?></td>
                        <td><?php echo $row['status']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6" style="text-align: right;">TOTAL</td>
                        <td style="text-align: right;"><?php echo angka($total_pengiriman, 2); ?> kg</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php endif; ?>
        
        <!-- Data Produksi -->
        <?php if (($tipe == 'semua' || $tipe == 'produksi') && isset($result_produksi) && numRows($result_produksi) > 0): ?>
        <div class="section-title">C. DATA PRODUKSI</div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Operator</th>
                        <th>Jenis</th                        <th>Mesin</th>
                        <th>Hasil (kg)</th>
                        <th>Kondisi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    mysqli_data_seek($result_produksi, 0);
                    while($row = fetch($result_produksi)): 
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo tglIndo($row['tgl_produksi']); ?></td>
                        <td><?php echo $row['nama_lengkap']; ?></td>
                        <td><?php echo $row['jenis_plastik']; ?></td>
                        <td><?php echo $row['nama_mesin']; ?></td>
                        <td style="text-align: right;"><?php echo angka($row['jumlah_hasil'], 2); ?></td>
                        <td><?php echo $row['kondisi_mesin']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" style="text-align: right;">TOTAL</td>
                        <td style="text-align: right;"><?php echo angka($total_produksi, 2); ?> kg</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php endif; ?>
        
        <!-- Tanda Tangan -->
        <div class="signature">
            <div class="signature-box">
                <p><?php echo APP_NAME; ?>, <?php echo tglIndo(date('Y-m-d')); ?></p>
                <p>Kepala Produksi</p>
                <div class="signature-line"></div>
                <p><?php echo $_SESSION['nama_lengkap']; ?></p>
            </div>
        </div>
    </div>
    
</div>

<style media="print">
    body * {
        visibility: hidden;
    }
    #laporan, #laporan * {
        visibility: visible;
    }
    #laporan {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        padding: 20px;
    }
    .action-buttons, .filter-container, .sidebar, .main-header {
        display: none !important;
    }
</style>

<?php include_once '../includes/footer.php'; ?>