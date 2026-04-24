<?php
require_once '../functions.php';
cekAkses(['operator_mesin', 'kepala_produksi']);

$title = 'Hasil Produksi';
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// PROSES TAMBAH DATA
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah'])) {
    $jenis_plastik = esc($_POST['jenis_plastik']);
    $nama_mesin = esc($_POST['nama_mesin']);
    $operator = esc($_POST['operator']);
    $tgl_produksi = esc($_POST['tgl_produksi']);
    $jumlah_hasil = (float)$_POST['jumlah_hasil'];
    $kondisi_mesin = esc($_POST['kondisi_mesin']);
    $catatan = isset($_POST['catatan']) ? esc($_POST['catatan']) : '';
    
    $sql = "INSERT INTO hasil_produksi (user_id, jenis_plastik, nama_mesin, operator, tgl_produksi, jumlah_hasil, kondisi_mesin, catatan) 
            VALUES ($user_id, '$jenis_plastik', '$nama_mesin', '$operator', '$tgl_produksi', $jumlah_hasil, '$kondisi_mesin', '$catatan')";
    
    if (query($sql)) {
        setAlert('Data produksi berhasil ditambahkan!', 'success');
    } else {
        setAlert('Gagal menambahkan data!', 'danger');
    }
    redirect('produksi.php');
}

// PROSES EDIT DATA
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {
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
        setAlert('Data produksi berhasil diupdate!', 'success');
    } else {
        setAlert('Gagal mengupdate data!', 'danger');
    }
    redirect('produksi.php');
}

// PROSES HAPUS DATA
if ($action == 'hapus' && $id > 0) {
    if ($role == 'kepala_produksi') {
        $sql = "DELETE FROM hasil_produksi WHERE id = $id";
        if (query($sql)) {
            setAlert('Data berhasil dihapus!', 'success');
        } else {
            setAlert('Gagal menghapus data!', 'danger');
        }
    } else {
        setAlert('Anda tidak memiliki izin menghapus data!', 'danger');
    }
    redirect('produksi.php');
}

// Ambil data untuk edit
$data_edit = null;
if ($action == 'edit' && $id > 0) {
    $sql_edit = "SELECT * FROM hasil_produksi WHERE id = $id";
    $result_edit = query($sql_edit);
    if ($result_edit && numRows($result_edit) > 0) {
        $data_edit = fetch($result_edit);
    } else {
        setAlert('Data tidak ditemukan!', 'danger');
        redirect('produksi.php');
    }
}

// Ambil data untuk ditampilkan
if ($role == 'operator_mesin') {
    $sql = "SELECT * FROM hasil_produksi WHERE user_id = $user_id ORDER BY tgl_produksi DESC";
} else {
    $sql = "SELECT hp.*, u.nama_lengkap FROM hasil_produksi hp 
            JOIN users u ON hp.user_id = u.id 
            ORDER BY hp.tgl_produksi DESC";
}
$result = query($sql);

include_once '../includes/header.php';
include_once '../includes/sidebar_operator.php';
?>

<style>
    .content-wrapper {
        padding: 30px;
    }
    
    .action-buttons {
        display: flex;
        gap: 15px;
        margin-bottom: 30px;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        transition: all 0.3s;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }
    
    .btn-secondary {
        background: #6c757d;
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
        transition: all 0.3s;
    }
    
    .btn-secondary:hover {
        background: #5a6268;
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
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: #555;
        margin-bottom: 8px;
    }
    
    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s;
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
        gap: 20px;
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
        transition: all 0.3s;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
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
        text-align: center;
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
        padding: 15px;
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
    
    .badge-danger {
        background: #f8d7da;
        color: #721c24;
    }
</style>

<div class="content-wrapper">
    
    <div class="action-buttons">
        <?php if ($role == 'operator_mesin'): ?>
        <a href="?action=tambah" class="btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Produksi Baru
        </a>
        <?php endif; ?>
        <a href="dashboard.php" class="btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
    
    <?php showAlert(); ?>
    
    <!-- Form Tambah -->
    <?php if ($action == 'tambah' && $role == 'operator_mesin'): ?>
    <div class="form-container">
        <div class="form-title">Tambah Data Produksi</div>
        <form method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label>Jenis Plastik</label>
                    <select name="jenis_plastik" required>
                        <option value="">Pilih Jenis Plastik</option>
                        <option value="PET">PET</option>
                        <option value="HDPE">HDPE</option>
                        <option value="PVC">PVC</option>
                        <option value="LDPE">LDPE</option>
                        <option value="PP">PP</option>
                        <option value="PS">PS</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Nama Mesin</label>
                    <input type="text" name="nama_mesin" placeholder="Contoh: Mesin A" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Nama Operator</label>
                    <input type="text" name="operator" value="<?php echo $_SESSION['nama_lengkap']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Tanggal Produksi</label>
                    <input type="date" name="tgl_produksi" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Jumlah Hasil (kg)</label>
                    <input type="number" step="0.01" name="jumlah_hasil" required>
                </div>
                <div class="form-group">
                    <label>Kondisi Mesin</label>
                    <select name="kondisi_mesin" required>
                        <option value="Baik">Baik</option>
                        <option value="Perlu Maintenance">Perlu Maintenance</option>
                        <option value="Rusak">Rusak</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label>Catatan</label>
                <textarea name="catatan" rows="3" placeholder="Catatan tambahan..."></textarea>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <button type="submit" name="tambah" class="btn-submit">Simpan</button>
                <a href="produksi.php" class="btn-cancel">Batal</a>
            </div>
        </form>
    </div>
    <?php endif; ?>
    
    <!-- Form Edit -->
    <?php if ($data_edit): ?>
    <div class="form-container">
        <div class="form-title">Edit Data Produksi</div>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $data_edit['id']; ?>">
            
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
            
            <div style="display: flex; gap: 15px;">
                <button type="submit" name="edit" class="btn-submit">Update</button>
                <a href="produksi.php" class="btn-cancel">Batal</a>
            </div>
        </form>
    </div>
    <?php endif; ?>
    
    <!-- Tabel Data -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <?php if ($role == 'kepala_produksi'): ?>
                    <th>Operator</th>
                    <?php endif; ?>
                    <th>Tanggal</th>
                    <th>Jenis Plastik</th>
                    <th>Mesin</th>
                    <th>Hasil (kg)</th>
                    <th>Kondisi</th>
                    <th>Catatan</th>
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
                    <?php if ($role == 'kepala_produksi'): ?>
                    <td><?php echo $row['nama_lengkap']; ?></td>
                    <?php endif; ?>
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
                    <td><?php echo $row['catatan'] ? substr($row['catatan'], 0, 20) . '...' : '-'; ?></td>
                    <td>
                        <a href="?action=edit&id=<?php echo $row['id']; ?>" class="btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <?php if ($role == 'kepala_produksi'): ?>
                        <a href="?action=hapus&id=<?php echo $row['id']; ?>" class="btn-danger" onclick="return confirmDelete('?action=hapus&id=<?php echo $row['id']; ?>')">
                            <i class="bi bi-trash"></i>
                        </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php 
                    endwhile;
                else:
                ?>
                <tr>
                    <td colspan="<?php echo $role == 'kepala_produksi' ? 9 : 8; ?>" style="text-align: center; padding: 30px;">
                        <i class="bi bi-inbox" style="font-size: 24px; color: #ccc;"></i>
                        <p style="margin-top: 10px; color: #999;">Belum ada data produksi</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
</div>

<?php include_once '../includes/footer.php'; ?>