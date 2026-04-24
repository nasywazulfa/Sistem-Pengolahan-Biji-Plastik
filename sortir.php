<?php
require_once '../functions.php';
cekAkses(['penyortir', 'kepala_produksi']);

$title = 'Hasil Sortir';
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// PROSES TAMBAH DATA
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah'])) {
    $jenis_plastik = esc($_POST['jenis_plastik']);
    $kualitas = esc($_POST['kualitas']);
    $jumlah = (float)$_POST['jumlah'];
    $tanggal = esc($_POST['tanggal']);
    $catatan = isset($_POST['catatan']) ? esc($_POST['catatan']) : '';
    
    $sql = "INSERT INTO hasil_sortir (user_id, jenis_plastik, kualitas, jumlah, tanggal, catatan) 
            VALUES ($user_id, '$jenis_plastik', '$kualitas', $jumlah, '$tanggal', '$catatan')";
    
    if (query($sql)) {
        setAlert('Data sortir berhasil ditambahkan!', 'success');
    } else {
        setAlert('Gagal menambahkan data!', 'danger');
    }
    redirect('sortir.php');
}

// PROSES EDIT DATA
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {
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
        setAlert('Data sortir berhasil diupdate!', 'success');
    } else {
        setAlert('Gagal mengupdate data!', 'danger');
    }
    redirect('sortir.php');
}

// PROSES HAPUS DATA
if ($action == 'hapus' && $id > 0) {
    if ($role == 'kepala_produksi') {
        $sql = "DELETE FROM hasil_sortir WHERE id = $id";
        if (query($sql)) {
            setAlert('Data berhasil dihapus!', 'success');
        } else {
            setAlert('Gagal menghapus data!', 'danger');
        }
    } else {
        setAlert('Anda tidak memiliki izin menghapus data!', 'danger');
    }
    redirect('sortir.php');
}

// Ambil data untuk edit
$data_edit = null;
if ($action == 'edit' && $id > 0) {
    $sql_edit = "SELECT * FROM hasil_sortir WHERE id = $id";
    $result_edit = query($sql_edit);
    if ($result_edit && numRows($result_edit) > 0) {
        $data_edit = fetch($result_edit);
    } else {
        setAlert('Data tidak ditemukan!', 'danger');
        redirect('sortir.php');
    }
}

// Ambil data untuk ditampilkan
if ($role == 'penyortir') {
    $sql = "SELECT * FROM hasil_sortir WHERE user_id = $user_id ORDER BY tanggal DESC";
} else {
    $sql = "SELECT hs.*, u.nama_lengkap FROM hasil_sortir hs 
            JOIN users u ON hs.user_id = u.id 
            ORDER BY hs.tanggal DESC";
}
$result = query($sql);

include_once '../includes/header.php';
include_once '../includes/sidebar_penyortir.php';
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
    
    <div class="action-buttons">
        <?php if ($role == 'penyortir'): ?>
        <a href="?action=tambah" class="btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Sortir Baru
        </a>
        <?php endif; ?>
        <a href="dashboard.php" class="btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
    
    <?php showAlert(); ?>
    
    <!-- Form Tambah -->
    <?php if ($action == 'tambah' && $role == 'penyortir'): ?>
    <div class="form-container">
        <div class="form-title">Tambah Data Sortir</div>
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
                    <label>Kualitas</label>
                    <select name="kualitas" required>
                        <option value="">Pilih Kualitas</option>
                        <option value="A">A (Sangat Baik)</option>
                        <option value="B">B (Baik)</option>
                        <option value="C">C (Cukup)</option>
                        <option value="D">D (Kurang)</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Jumlah (kg)</label>
                    <input type="number" step="0.01" name="jumlah" required>
                </div>
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
            </div>
            
            <div class="form-group">
                <label>Catatan</label>
                <textarea name="catatan" rows="3" placeholder="Catatan tambahan..."></textarea>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <button type="submit" name="tambah" class="btn-submit">Simpan</button>
                <a href="sortir.php" class="btn-cancel">Batal</a>
            </div>
        </form>
    </div>
    <?php endif; ?>
    
    <!-- Form Edit -->
    <?php if ($data_edit): ?>
    <div class="form-container">
        <div class="form-title">Edit Data Sortir</div>
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
            
            <div style="display: flex; gap: 15px;">
                <button type="submit" name="edit" class="btn-submit">Update</button>
                <a href="sortir.php" class="btn-cancel">Batal</a>
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
                    <th>Penyortir</th>
                    <?php endif; ?>
                    <th>Tanggal</th>
                    <th>Jenis Plastik</th>
                    <th>Kualitas</th>
                    <th>Jumlah (kg)</th>
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
                    <td colspan="<?php echo $role == 'kepala_produksi' ? 8 : 7; ?>" style="text-align: center; padding: 30px;">
                        <i class="bi bi-inbox" style="font-size: 24px; color: #ccc;"></i>
                        <p style="margin-top: 10px; color: #999;">Belum ada data sortir</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
</div>

<?php include_once '../includes/footer.php'; ?>