<?php
require_once '../functions.php';
cekAkses(['kepala_produksi']);

$title = 'Rekap Data';

$filter_tipe = isset($_GET['tipe']) ? $_GET['tipe'] : 'semua';
$filter_dari = isset($_GET['dari']) ? $_GET['dari'] : date('Y-m-01');
$filter_sampai = isset($_GET['sampai']) ? $_GET['sampai'] : date('Y-m-d');

// Query berdasarkan filter
if ($filter_tipe == 'sortir' || $filter_tipe == 'semua') {
    $sql_sortir = "SELECT hs.*, u.nama_lengkap 
                   FROM hasil_sortir hs 
                   JOIN users u ON hs.user_id = u.id 
                   WHERE hs.tanggal BETWEEN '$filter_dari' AND '$filter_sampai'
                   ORDER BY hs.tanggal DESC";
    $result_sortir = query($sql_sortir);
    
    $sql_total_sortir = "SELECT COALESCE(SUM(jumlah), 0) as total 
                         FROM hasil_sortir 
                         WHERE tanggal BETWEEN '$filter_dari' AND '$filter_sampai'";
    $result_total_sortir = query($sql_total_sortir);
    $total_sortir = 0;
    if ($result_total_sortir) {
        $data = fetch($result_total_sortir);
        $total_sortir = $data['total'] ?? 0;
    }
}

if ($filter_tipe == 'pengiriman' || $filter_tipe == 'semua') {
    $sql_pengiriman = "SELECT p.*, u.nama_lengkap 
                       FROM pengiriman p 
                       JOIN users u ON p.user_id = u.id 
                       WHERE p.tanggal BETWEEN '$filter_dari' AND '$filter_sampai'
                       ORDER BY p.tanggal DESC";
    $result_pengiriman = query($sql_pengiriman);
    
    $sql_total_pengiriman = "SELECT COALESCE(SUM(jumlah_muatan), 0) as total 
                             FROM pengiriman 
                             WHERE tanggal BETWEEN '$filter_dari' AND '$filter_sampai'";
    $result_total_pengiriman = query($sql_total_pengiriman);
    $total_pengiriman = 0;
    if ($result_total_pengiriman) {
        $data = fetch($result_total_pengiriman);
        $total_pengiriman = $data['total'] ?? 0;
    }
}

if ($filter_tipe == 'produksi' || $filter_tipe == 'semua') {
    $sql_produksi = "SELECT hp.*, u.nama_lengkap 
                     FROM hasil_produksi hp 
                     JOIN users u ON hp.user_id = u.id 
                     WHERE hp.tgl_produksi BETWEEN '$filter_dari' AND '$filter_sampai'
                     ORDER BY hp.tgl_produksi DESC";
    $result_produksi = query($sql_produksi);
    
    $sql_total_produksi = "SELECT COALESCE(SUM(jumlah_hasil), 0) as total 
                           FROM hasil_produksi 
                           WHERE tgl_produksi BETWEEN '$filter_dari' AND '$filter_sampai'";
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
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }
    
    .summary-cards {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }
    
    .summary-card {
        flex: 1;
        min-width: 200px;
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border-left: 5px solid #667eea;
    }
    
    .summary-card .label {
        font-size: 14px;
        color: #666;
        margin-bottom: 5px;
    }
    
    .summary-card .value {
        font-size: 28px;
        font-weight: 700;
        color: #333;
    }
    
    .summary-card .unit {
        font-size: 14px;
        color: #999;
        margin-left: 5px;
    }
    
    .section-title {
        font-size: 20px;
        font-weight: 700;
        color: #333;
        margin: 30px 0 15px 0;
    }
    
    .table-container {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        overflow-x: auto;
        margin-bottom: 30px;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
    }
    
    th {
        text-align: left;
        padding: 15px;
        background: #f8f9fa;
        color: #555;
        font-weight: 600;
    }
    
    td {
        padding: 12px 15px;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .text-end {
        text-align: right;
    }
    
    .badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .badge-success {
        background: #d4edda;
        color: #155724;
    }
    
    .badge-warning {
        background: #fff3cd;
        color: #856404;
    }
    
    .badge-info {
        background: #cce5ff;
        color: #004085;
    }
    
    .total-row {
        background: #f8f9fa;
        font-weight: 700;
    }
</style>

<div class="content-wrapper">
    
    <h1 style="font-size: 28px; margin-bottom: 20px;">Rekap Data Produksi</h1>
    
    <!-- Filter Form -->
    <div class="filter-container">
        <div class="filter-title">Filter Data</div>
        <form method="GET" class="filter-form">
            <div class="filter-group">
                <label>Tipe Data</label>
                <select name="tipe">
                    <option value="semua" <?php echo $filter_tipe == 'semua' ? 'selected' : ''; ?>>Semua Data</option>
                    <option value="sortir" <?php echo $filter_tipe == 'sortir' ? 'selected' : ''; ?>>Sortir</option>
                    <option value="pengiriman" <?php echo $filter_tipe == 'pengiriman' ? 'selected' : ''; ?>>Pengiriman</option>
                    <option value="produksi" <?php echo $filter_tipe == 'produksi' ? 'selected' : ''; ?>>Produksi</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Dari Tanggal</label>
                <input type="date" name="dari" value="<?php echo $filter_dari; ?>">
            </div>
            <div class="filter-group">
                <label>Sampai Tanggal</label>
                <input type="date" name="sampai" value="<?php echo $filter_sampai; ?>">
            </div>
            <div class="filter-group">
                <button type="submit" class="btn-filter">
                    <i class="bi bi-search"></i> Tampilkan
                </button>
            </div>
        </form>
    </div>
    
    <?php showAlert(); ?>
    
    <!-- Summary Cards -->
    <div class="summary-cards">
        <?php if ($filter_tipe == 'sortir' || $filter_tipe == 'semua'): ?>
        <div class="summary-card">
            <div class="label">Total Sortir</div>
            <div class="value"><?php echo angka($total_sortir, 2); ?><span class="unit">kg</span></div>
            <small><?php echo tglIndo($filter_dari); ?> - <?php echo tglIndo($filter_sampai); ?></small>
        </div>
        <?php endif; ?>
        
        <?php if ($filter_tipe == 'pengiriman' || $filter_tipe == 'semua'): ?>
        <div class="summary-card">
            <div class="label">Total Pengiriman</div>
            <div class="value"><?php echo angka($total_pengiriman, 2); ?><span class="unit">kg</span></div>
            <small><?php echo tglIndo($filter_dari); ?> - <?php echo tglIndo($filter_sampai); ?></small>
        </div>
        <?php endif; ?>
        
        <?php if ($filter_tipe == 'produksi' || $filter_tipe == 'semua'): ?>
        <div class="summary-card">
            <div class="label">Total Produksi</div>
            <div class="value"><?php echo angka($total_produksi, 2); ?><span class="unit">kg</span></div>
            <small><?php echo tglIndo($filter_dari); ?> - <?php echo tglIndo($filter_sampai); ?></small>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Data Sortir -->
    <?php if (($filter_tipe == 'sortir' || $filter_tipe == 'semua') && isset($result_sortir) && numRows($result_sortir) > 0): ?>
    <div class="section-title">Data Sortir</div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Penyortir</th>
                    <th>Jenis Plastik</th>
                    <th>Kualitas</th>
                    <th class="text-end">Jumlah (kg)</th>
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
                    <td>
                        <span class="badge 
                            <?php 
                            if($row['kualitas'] == 'A') echo 'badge-success';
                            elseif($row['kualitas'] == 'B') echo 'badge-info';
                            elseif($row['kualitas'] == 'C') echo 'badge-warning';
                            else echo 'badge-danger';
                            ?>">
                            <?php echo $row['kualitas']; ?>
                        </span>
                    </td>
                    <td class="text-end"><?php echo angka($row['jumlah'], 2); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="5" class="text-end">TOTAL</td>
                    <td class="text-end"><?php echo angka($total_sortir, 2); ?> kg</td>
                </tr>
            </tfoot>
        </table>
    </div>
    <?php endif; ?>
    
    <!-- Data Pengiriman -->
    <?php if (($filter_tipe == 'pengiriman' || $filter_tipe == 'semua') && isset($result_pengiriman) && numRows($result_pengiriman) > 0): ?>
    <div class="section-title">Data Pengiriman</div>
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
                    <th class="text-end">Berat (kg)</th>
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
                    <td class="text-end"><?php echo angka($row['jumlah_muatan'], 2); ?></td>
                    <td>
                        <span class="badge 
                            <?php 
                            if($row['status'] == 'Selesai') echo 'badge-success';
                            elseif($row['status'] == 'Dalam Perjalanan') echo 'badge-warning';
                            else echo 'badge-secondary';
                            ?>">
                            <?php echo $row['status']; ?>
                        </span>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="6" class="text-end">TOTAL</td>
                    <td class="text-end"><?php echo angka($total_pengiriman, 2); ?> kg</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <?php endif; ?>
    
    <!-- Data Produksi -->
    <?php if (($filter_tipe == 'produksi' || $filter_tipe == 'semua') && isset($result_produksi) && numRows($result_produksi) > 0): ?>
    <div class="section-title">Data Produksi</div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Operator</th>
                    <th>Jenis Plastik</th>
                    <th>Mesin</th>
                    <th class="text-end">Hasil (kg)</th>
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
                    <td class="text-end"><?php echo angka($row['jumlah_hasil'], 2); ?></td>
                    <td>
                        <span class="badge 
                            <?php 
                            if($row['kondisi_mesin'] == 'Baik') echo 'badge-success';
                            elseif($row['kondisi_mesin'] == 'Perlu Maintenance') echo 'badge-warning';
                            else echo 'badge-danger';
                            ?>">
                            <?php echo $row['kondisi_mesin']; ?>
                        </span>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="5" class="text-end">TOTAL</td>
                    <td class="text-end"><?php echo angka($total_produksi, 2); ?> kg</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <?php endif; ?>
    
    <!-- Export Button -->
    <div style="margin-top: 20px; text-align: right;">
        <a href="laporan.php?tipe=<?php echo $filter_tipe; ?>&dari=<?php echo $filter_dari; ?>&sampai=<?php echo $filter_sampai; ?>" class="btn-primary" style="padding: 12px 25px; text-decoration: none;">
            <i class="bi bi-file-pdf"></i> Export ke Laporan PDF
        </a>
    </div>
    
</div>

<?php include_once '../includes/footer.php'; ?>