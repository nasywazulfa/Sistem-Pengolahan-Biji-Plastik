<?php
require_once '../functions.php';
cekAkses(['kepala_produksi']);

$title = 'Koreksi Data';
$table = isset($_GET['table']) ? $_GET['table'] : 'sortir';
$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// PROSES UPDATE DATA SORTIR
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_sortir'])) {
    $id = (int)$_POST['id'];
    $jenis_plastik = esc($_POST['jenis_plastik']);
    $kualitas = esc($_POST['kualitas']);
    $jumlah = (float)$_POST['jumlah'];
    $tanggal = esc($_POST['tanggal']);
    $catatan = isset($_POST['catatan']) ? esc($_POST['catatan']) : '';
    
    $sql = "UPDATE hasil_sortir SET 
            jenis_plastik = '$jenis_plastik',
            kualitas = '$kualitas',
            jumlah = $jumlah,
            tanggal = '$tanggal',
            catatan = '$catatan'
            WHERE id = $id";
    
    if (query($sql)) {
        setAlert('Data sortir berhasil dikoreksi!', 'success');
    } else {
        setAlert('Gagal mengkoreksi data!', 'danger');
    }
    redirect('koreksi.php?table=sortir');
}

// PROSES UPDATE DATA PENGIRIMAN
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_pengiriman'])) {
    $id = (int)$_POST['id'];
    $tanggal = esc($_POST['tanggal']);
    $tujuan = esc($_POST['tujuan']);
    $perusahaan = esc($_POST['perusahaan']);
    $jumlah_muatan = (float)$_POST['jumlah_muatan'];
    $jenis_plastik = esc($_POST['jenis_plastik']);
    $status = esc($_POST['status']);
    
    $sql = "UPDATE pengiriman SET 
            tanggal = '$tanggal',
            tujuan = '$tujuan',
            perusahaan = '$perusahaan',
            jumlah_muatan = $jumlah_muatan,
            jenis_plastik = '$jenis_plastik',
            status = '$status'
            WHERE id = $id";
    
    if (query($sql)) {
        setAlert('Data pengiriman berhasil dikoreksi!', 'success');
    } else {
        setAlert('Gagal mengkoreksi data!', 'danger');
    }
    redirect('koreksi.php?table=pengiriman');
}

// PROSES UPDATE DATA PRODUKSI
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_produksi'])) {
    $id = (int)$_POST['id'];
    $jenis_plastik = esc($_POST['jenis_plastik']);
    $nama_mesin = esc($_POST['nama_mesin']);
    $operator = esc($_POST['operator']);
    $tgl_produksi = esc($_POST['tgl_produksi']);
    $jumlah_hasil = (float)$_POST['jumlah_hasil'];
    $kondisi_mesin = esc($_POST['kondisi_mesin']);
    $catatan = isset($_POST['catatan']) ? esc($_POST['catatan']) : '';
    
    $sql = "UPDATE hasil_produksi SET 
            jenis_plastik = '$jenis_plastik',
            nama_mesin = '$nama_mesin',
            operator = '$operator',
            tgl_produksi = '$tgl_produksi',
            jumlah_hasil = $jumlah_hasil,
            kondisi_mesin = '$kondisi_mesin',
            catatan = '$catatan'
            WHERE id = $id";
    
    if (query($sql)) {
        setAlert('Data produksi berhasil dikoreksi!', 'success');
    } else {
        setAlert('Gagal mengkoreksi data!', 'danger');
    }
    redirect('koreksi.php?table=produksi');
}

// PROSES HAPUS DATA
if ($action == 'hapus' && $id > 0) {
    if ($table == 'sortir') {
        $sql = "DELETE FROM hasil_sortir WHERE id = $id";
    } elseif ($table == 'pengiriman') {
        $sql = "DELETE FROM pengiriman WHERE id = $id";
    } elseif ($table == 'produksi') {
        $sql = "DELETE FROM hasil_produksi WHERE id = $id";
    }
    
    if (query($sql)) {
        setAlert('Data berhasil dihapus!', 'success');
    } else {
        setAlert('Gagal menghapus data!', 'danger');
    }
    redirect('koreksi.php?table=' . $table);
}

// Ambil data untuk diedit
$data_edit = null;
if ($action == 'edit' && $id > 0) {
    if ($table == 'sortir') {
        $sql_edit = "SELECT hs.*, u.nama_lengkap FROM hasil_sortir hs 
                     JOIN users u ON hs.user_id = u.id 
                     WHERE hs.id = $id";
    } elseif ($table == 'pengiriman') {
        $sql_edit = "SELECT p.*, u.nama_lengkap FROM pengiriman p 
                     JOIN users u ON p.user_id = u.id 
                     WHERE p.id = $id";
    } elseif ($table == 'produksi') {
        $sql_edit = "SELECT hp.*, u.nama_lengkap FROM hasil_produksi hp 
                     JOIN users u ON hp.user_id = u.id 
                     WHERE hp.id = $id";
    }
    $result_edit = query($sql_edit);
    if ($result_edit && numRows($result_edit) > 0) {
        $data_edit = fetch($result_edit);
    }
}

include_once '../includes/header.php';
include_once '../includes/sidebar_kepala.php';
?>

<style>
    .content-wrapper {
        padding: 30px;
    }
    
    .tab-menu {
        display: flex;
        gap: 5px;
        margin-bottom: 30px;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 10px;
    }
    
    .tab-link {
        padding: 10px 20px;
        background: #f8f9fa;
        color: #333;
        text-decoration: none;
        border-radius: 8px 8px 0 0;
        font-weight: 500;
        transition: all 0.3s;
    }
    
    .tab-link:hover {
        background: #e9ecef;
    }
    
    .tab-link.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .form-container {
        background: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid #f0f0f0;
    }
    
    .form-title {
        font-size: 20px;
        font-weight: 600;
        color: #333;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .form-group {
        margin-bottom: 15px;
    }
    
    .form-group label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: #555;
        margin-bottom: 5px;
    }
    
    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px 12px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
    }
    
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: #667eea;
        outline: none;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }
    
    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
    }
    
    .btn-cancel {
        background: #f0f0f0;
        color: #333;
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        margin-left: 10px;
    }
    
    .btn-warning {
        background: #ffc107;
        color: #333;
        padding: 6px 12px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 13px;
        display: inline-block;
        margin-right: 5px;
    }
    
    .btn-danger {
        background: #dc3545;
        color: white;
        padding: 6px 12px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 13px;
        display: inline-block;
    }
    
    .table-container {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        overflow-x: auto;
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
    
    .badge-danger {
        background: #f8d7da;
        color: #721c24;
    }
</style>

<div class="content-wrapper">
    
    <h1 style="font-size: 28px; margin-bottom: 20px;">Koreksi Data</h1>
    
    <?php showAlert(); ?>
    
    <!-- Tab Menu -->
    <div class="tab-menu">
        <a href="?table=sortir" class="tab-link <?php echo $table == 'sortir' ? 'active' : ''; ?>">
            <i class="bi bi-sort-up"></i> Data Sortir
        </a>
        <a href="?table=pengiriman" class="tab-link <?php echo $table == 'pengiriman' ? 'active' : ''; ?>">
            <i class="bi bi-truck"></i> Data Pengiriman
        </a>
        <a href="?table=produksi" class="tab-link <?php echo $table == 'produksi' ? 'active' : ''; ?>">
            <i class="bi bi-gear"></i> Data Produksi
        </a>
    </div>
    
    <!-- Form Edit -->
    <?php if ($data_edit && $action == 'edit'): ?>
    <div class="form-container">
        <div class="form-title">Edit Data <?php echo ucfirst($table); ?></div>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $data_edit['id']; ?>">
            
            <?php if ($table == 'sortir'): ?>
            <!-- Form Edit Sortir -->
            <div class="form-group">
                <label>Penyortir</label>
                <input type="text" value="<?php echo $data_edit['nama_lengkap']; ?>" readonly>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Jenis Plastik</label>
                    <select name="jenis_plastik" required>
                        <option value="PET" <?php echo $data_edit['jenis_plastik'] == 'PET' ? 'selected' : ''; ?>>PET</option>
                        <option value="HDPE" <?php echo $data_edit['jenis_plastik'] == 'HDPE' ? 'selected' : ''; ?>>HDPE</option>
                        <option value="PVC" <?php echo $data_edit['jenis_plastik'] == 'PVC' ? 'selected' : ''; ?>>PVC</option>
                        <option value="LDPE" <?php echo $data_edit['jenis_plastik'] == 'LDPE' ? 'selected' : ''; ?>>LDPE</option>
                        <option value="PP"                        <option value="PP" <?php echo $data_edit['jenis_plastik'] == 'PP' ? 'selected' : ''; ?>>PP</option>
                        <option value="PS" <?php echo $data_edit['jenis_plastik'] == 'PS' ? 'selected' : ''; ?>>PS</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Kualitas</label>
                    <select name="kualitas" required>
                        <option value="A" <?php echo $data_edit['kualitas'] == 'A' ? 'selected' : ''; ?>>A (Sangat Baik)</option>
                        <option value="B" <?php echo $data_edit['kualitas'] == 'B' ? 'selected' : ''; ?>>B (Baik)</option>
                        <option value="C" <?php echo $data_edit['kualitas'] == 'C' ? 'selected' : ''; ?>>C (Cukup)</option>
                        <option value="D" <?php echo $data_edit['kualitas'] == 'D' ? 'selected' : ''; ?>>D (Kurang)</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Jumlah (kg)</label>
                    <input type="number" step="0.01" name="jumlah" value="<?php echo $data_edit['jumlah']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" value="<?php echo $data_edit['tanggal']; ?>" required>
                </div>
            </div>
            
            <div class="form-group">
                <label>Catatan</label>
                <textarea name="catatan" rows="3"><?php echo $data_edit['catatan']; ?></textarea>
            </div>
            
            <button type="submit" name="update_sortir" class="btn-submit">Simpan Perubahan</button>
            <a href="koreksi.php?table=sortir" class="btn-cancel">Batal</a>
            
            <?php elseif ($table == 'pengiriman'): ?>
            <!-- Form Edit Pengiriman -->
            <div class="form-group">
                <label>Sopir</label>
                <input type="text" value="<?php echo $data_edit['nama_lengkap']; ?>" readonly>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" value="<?php echo $data_edit['tanggal']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Perusahaan</label>
                    <input type="text" name="perusahaan" value="<?php echo $data_edit['perusahaan']; ?>" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Tujuan</label>
                    <input type="text" name="tujuan" value="<?php echo $data_edit['tujuan']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Jenis Plastik</label>
                    <select name="jenis_plastik" required>
                        <option value="PET" <?php echo $data_edit['jenis_plastik'] == 'PET' ? 'selected' : ''; ?>>PET</option>
                        <option value="HDPE" <?php echo $data_edit['jenis_plastik'] == 'HDPE' ? 'selected' : ''; ?>>HDPE</option>
                        <option value="PVC" <?php echo $data_edit['jenis_plastik'] == 'PVC' ? 'selected' : ''; ?>>PVC</option>
                        <option value="LDPE" <?php echo $data_edit['jenis_plastik'] == 'LDPE' ? 'selected' : ''; ?>>LDPE</option>
                        <option value="PP" <?php echo $data_edit['jenis_plastik'] == 'PP' ? 'selected' : ''; ?>>PP</option>
                        <option value="PS" <?php echo $data_edit['jenis_plastik'] == 'PS' ? 'selected' : ''; ?>>PS</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Jumlah Muatan (kg)</label>
                    <input type="number" step="0.01" name="jumlah_muatan" value="<?php echo $data_edit['jumlah_muatan']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" required>
                        <option value="Menunggu" <?php echo $data_edit['status'] == 'Menunggu' ? 'selected' : ''; ?>>Menunggu</option>
                        <option value="Dalam Perjalanan" <?php echo $data_edit['status'] == 'Dalam Perjalanan' ? 'selected' : ''; ?>>Dalam Perjalanan</option>
                        <option value="Selesai" <?php echo $data_edit['status'] == 'Selesai' ? 'selected' : ''; ?>>Selesai</option>
                    </select>
                </div>
            </div>
            
            <button type="submit" name="update_pengiriman" class="btn-submit">Simpan Perubahan</button>
            <a href="koreksi.php?table=pengiriman" class="btn-cancel">Batal</a>
            
            <?php elseif ($table == 'produksi'): ?>
            <!-- Form Edit Produksi -->
            <div class="form-group">
                <label>Operator</label>
                <input type="text" value="<?php echo $data_edit['nama_lengkap']; ?>" readonly>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Jenis Plastik</label>
                    <select name="jenis_plastik" required>
                        <option value="PET" <?php echo $data_edit['jenis_plastik'] == 'PET' ? 'selected' : ''; ?>>PET</option>
                        <option value="HDPE" <?php echo $data_edit['jenis_plastik'] == 'HDPE' ? 'selected' : ''; ?>>HDPE</option>
                        <option value="PVC" <?php echo $data_edit['jenis_plastik'] == 'PVC' ? 'selected' : ''; ?>>PVC</option>
                        <option value="LDPE" <?php echo $data_edit['jenis_plastik'] == 'LDPE' ? 'selected' : ''; ?>>LDPE</option>
                        <option value="PP" <?php echo $data_edit['jenis_plastik'] == 'PP' ? 'selected' : ''; ?>>PP</option>
                        <option value="PS" <?php echo $data_edit['jenis_plastik'] == 'PS' ? 'selected' : ''; ?>>PS</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Nama Mesin</label>
                    <input type="text" name="nama_mesin" value="<?php echo $data_edit['nama_mesin']; ?>" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Nama Operator</label>
                    <input type="text" name="operator" value="<?php echo $data_edit['operator']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Tanggal Produksi</label>
                    <input type="date" name="tgl_produksi" value="<?php echo $data_edit['tgl_produksi']; ?>" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Jumlah Hasil (kg)</label>
                    <input type="number" step="0.01" name="jumlah_hasil" value="<?php echo $data_edit['jumlah_hasil']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Kondisi Mesin</label>
                    <select name="kondisi_mesin" required>
                        <option value="Baik" <?php echo $data_edit['kondisi_mesin'] == 'Baik' ? 'selected' : ''; ?>>Baik</option>
                        <option value="Perlu Maintenance" <?php echo $data_edit['kondisi_mesin'] == 'Perlu Maintenance' ? 'selected' : ''; ?>>Perlu Maintenance</option>
                        <option value="Rusak" <?php echo $data_edit['kondisi_mesin'] == 'Rusak' ? 'selected' : ''; ?>>Rusak</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label>Catatan</label>
                <textarea name="catatan" rows="3"><?php echo $data_edit['catatan']; ?></textarea>
            </div>
            
            <button type="submit" name="update_produksi" class="btn-submit">Simpan Perubahan</button>
            <a href="koreksi.php?table=produksi" class="btn-cancel">Batal</a>
            <?php endif; ?>
        </form>
    </div>
    <?php endif; ?>
    
    <!-- Tabel Data Sortir -->
    <?php if ($table == 'sortir'): ?>
    <?php
    $sql = "SELECT hs.*, u.nama_lengkap FROM hasil_sortir hs 
            JOIN users u ON hs.user_id = u.id 
            ORDER BY hs.tanggal DESC LIMIT 50";
    $result = query($sql);
    ?>
    <div class="table-container">
        <h3 style="margin-bottom: 15px;">Data Sortir (50 data terakhir)</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Penyortir</th>
                    <th>Tanggal</th>
                    <th>Jenis</th>
                    <th>Kualitas</th>
                    <th>Jumlah (kg)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($result && numRows($result) > 0):
                    $no = 1;
                    while($row = fetch($result)): 
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $row['nama_lengkap']; ?></td>
                    <td><?php echo tglIndo($row['tanggal']); ?></td>
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
                    <td><?php echo angka($row['jumlah'], 2); ?></td>
                    <td>
                        <a href="?table=sortir&action=edit&id=<?php echo $row['id']; ?>" class="btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="?table=sortir&action=hapus&id=<?php echo $row['id']; ?>" class="btn-danger" onclick="return confirmDelete('?table=sortir&action=hapus&id=<?php echo $row['id']; ?>')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php 
                    endwhile;
                else:
                ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 30px;">Tidak ada data sortir</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
    
    <!-- Tabel Data Pengiriman -->
    <?php if ($table == 'pengiriman'): ?>
    <?php
    $sql = "SELECT p.*, u.nama_lengkap FROM pengiriman p 
            JOIN users u ON p.user_id = u.id 
            ORDER BY p.tanggal DESC LIMIT 50";
    $result = query($sql);
    ?>
    <div class="table-container">
        <h3 style="margin-bottom: 15px;">Data Pengiriman (50 data terakhir)</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Sopir</th>
                    <th>Tanggal</th>
                    <th>Perusahaan</th>
                    <th>Tujuan</th>
                    <th>Jenis</th>
                    <th>Berat (kg)</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($result && numRows($result) > 0):
                    $no = 1;
                    while($row = fetch($result)): 
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $row['nama_lengkap']; ?></td>
                    <td><?php echo tglIndo($row['tanggal']); ?></td>
                    <td><?php echo $row['perusahaan']; ?></td>
                    <td><?php echo $row['tujuan']; ?></td>
                    <td><?php echo $row['jenis_plastik']; ?></td>
                    <td><?php echo angka($row['jumlah_muatan'], 2); ?></td>
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
                    <td>
                        <a href="?table=pengiriman&action=edit&id=<?php echo $row['id']; ?>" class="btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="?table=pengiriman&action=hapus&id=<?php echo $row['id']; ?>" class="btn-danger" onclick="return confirmDelete('?table=pengiriman&action=hapus&id=<?php echo $row['id']; ?>')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php 
                    endwhile;
                else:
                ?>
                <tr>
                    <td colspan="9" style="text-align: center; padding: 30px;">Tidak ada data pengiriman</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
    
    <!-- Tabel Data Produksi -->
    <?php if ($table == 'produksi'): ?>
    <?php
    $sql = "SELECT hp.*, u.nama_lengkap FROM hasil_produksi hp 
            JOIN users u ON hp.user_id = u.id 
            ORDER BY hp.tgl_produksi DESC LIMIT 50";
    $result = query($sql);
    ?>
    <div class="table-container">
        <h3 style="margin-bottom: 15px;">Data Produksi (50 data terakhir)</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Operator</th>
                    <th>Tanggal</th>
                    <th>Jenis</th>
                    <th>Mesin</th>
                    <th>Hasil (kg)</th>
                    <th>Kondisi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($result && numRows($result) > 0):
                    $no = 1;
                    while($row = fetch($result)): 
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $row['nama_lengkap']; ?></td>
                    <td><?php echo tglIndo($row['tgl_produksi']); ?></td>
                    <td><?php echo $row['jenis_plastik']; ?></td>
                    <td><?php echo $row['nama_mesin']; ?></td>
                    <td><?php echo angka($row['jumlah_hasil'], 2); ?></td>
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
                    <td>
                        <a href="?table=produksi&action=edit&id=<?php echo $row['id']; ?>" class="btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="?table=produksi&action=hapus&id=<?php echo $row['id']; ?>" class="btn-danger" onclick="return confirmDelete('?table=produksi&action=hapus&id=<?php echo $row['id']; ?>')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php 
                    endwhile;
                else:
                ?>
                <tr>
                    <td colspan="8" style="text-align: center; padding: 30px;">Tidak ada data produksi</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
    
</div>

<?php include_once '../includes/footer.php'; ?>