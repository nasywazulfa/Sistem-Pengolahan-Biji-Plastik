<?php
require_once '../functions.php';
cekAkses(['kepala_produksi']);

$title = 'Feedback Pelanggan';
$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'semua';

// PROSES TAMBAH FEEDBACK
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah'])) {
    $id_pelanggan = (int)$_POST['id_pelanggan'];
    $rating = (int)$_POST['rating'];
    $komentar = esc($_POST['komentar']);
    $tanggal = esc($_POST['tanggal']);
    $status = esc($_POST['status']);
    $tindak_lanjut = isset($_POST['tindak_lanjut']) ? esc($_POST['tindak_lanjut']) : '';
    
    $sql = "INSERT INTO feedback (id_pelanggan, rating, komentar, tanggal, status, tindak_lanjut) 
            VALUES ($id_pelanggan, $rating, '$komentar', '$tanggal', '$status', '$tindak_lanjut')";
    
    if (query($sql)) {
        setAlert('Feedback berhasil ditambahkan!', 'success');
    } else {
        setAlert('Gagal menambahkan feedback!', 'danger');
    }
    redirect('feedback.php');
}

// PROSES EDIT FEEDBACK
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {
    $id = (int)$_POST['id'];
    $rating = (int)$_POST['rating'];
    $komentar = esc($_POST['komentar']);
    $status = esc($_POST['status']);
    $tindak_lanjut = isset($_POST['tindak_lanjut']) ? esc($_POST['tindak_lanjut']) : '';
    
    $sql = "UPDATE feedback SET 
            rating = $rating,
            komentar = '$komentar',
            status = '$status',
            tindak_lanjut = '$tindak_lanjut'
            WHERE id = $id";
    
    if (query($sql)) {
        setAlert('Feedback berhasil diupdate!', 'success');
    } else {
        setAlert('Gagal mengupdate feedback!', 'danger');
    }
    redirect('feedback.php');
}

// PROSES UPDATE STATUS
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $id = (int)$_POST['id'];
    $status = esc($_POST['status']);
    
    $sql = "UPDATE feedback SET status = '$status' WHERE id = $id";
    if (query($sql)) {
        setAlert('Status feedback berhasil diupdate!', 'success');
    }
    redirect('feedback.php');
}

// PROSES HAPUS FEEDBACK
if ($action == 'hapus' && $id > 0) {
    $sql = "DELETE FROM feedback WHERE id = $id";
    if (query($sql)) {
        setAlert('Feedback berhasil dihapus!', 'success');
    } else {
        setAlert('Gagal menghapus feedback!', 'danger');
    }
    redirect('feedback.php');
}

// Ambil data untuk edit
$data_edit = null;
if ($action == 'edit' && $id > 0) {
    $sql_edit = "SELECT f.*, p.nama as nama_pelanggan, p.perusahaan 
                 FROM feedback f 
                 JOIN pelanggan p ON f.id_pelanggan = p.id 
                 WHERE f.id = $id";
    $result_edit = query($sql_edit);
    if ($result_edit && numRows($result_edit) > 0) {
        $data_edit = fetch($result_edit);
    } else {
        setAlert('Data tidak ditemukan!', 'danger');
        redirect('feedback.php');
    }
}

// Query feedback dengan filter status
$sql_feedback = "SELECT f.*, p.nama as nama_pelanggan, p.perusahaan 
                 FROM feedback f 
                 JOIN pelanggan p ON f.id_pelanggan = p.id";
if ($status_filter != 'semua') {
    $sql_feedback .= " WHERE f.status = '$status_filter'";
}
$sql_feedback .= " ORDER BY 
                   CASE f.status 
                       WHEN 'Baru' THEN 1 
                       WHEN 'Ditinjau' THEN 2 
                       WHEN 'Selesai' THEN 3 
                   END, 
                   f.tanggal DESC";
$result_feedback = query($sql_feedback);

// Ambil data pelanggan untuk dropdown
$sql_pelanggan = "SELECT * FROM pelanggan ORDER BY nama";
$result_pelanggan = query($sql_pelanggan);

include_once '../includes/header.php';
include_once '../includes/sidebar_kepala.php';
?>

<style>
    .content-wrapper {
        padding: 30px;
    }
    
    .action-buttons {
        display: flex;
        gap: 15px;
        margin-bottom: 30px;
        flex-wrap: wrap;
        justify-content: space-between;
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
    
    .filter-container {
        background: white;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid #f0f0f0;
        display: flex;
        gap: 15px;
        align-items: flex-end;
    }
    
    .filter-group {
        flex: 1;
    }
    
    .filter-group label {
        display: block;
        font-size: 13px;
        font-weight: 500;
        color: #555;
        margin-bottom: 5px;
    }
    
    .filter-group select {
        width: 100%;
        padding: 10px 12px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
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
        margin-top: 15px;
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
    
    .rating {
        color: #ffc107;
        font-size: 14px;
    }
    
    .badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .badge-primary {
        background: #cce5ff;
        color: #004085;
    }
    
    .badge-success {
        background: #d4edda;
        color: #155724;
    }
    
    .badge-warning {
        background: #fff3cd;
        color: #856404;
    }
    
    .status-form {
        display: inline-block;
    }
    
    .status-select {
        padding: 5px;
        border-radius: 5px;
        border: 1px solid #ddd;
        font-size: 12px;
    }
</style>

<div class="content-wrapper">
    
    <h1 style="font-size: 28px; margin-bottom: 20px;">Feedback Pelanggan</h1>
    
    <div class="action-buttons">
        <a href="?action=tambah" class="btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Feedback
        </a>
    </div>
    
    <?php showAlert(); ?>
    
    <!-- Filter Status -->
    <div class="filter-container">
        <div class="filter-group">
            <label>Filter Status</label>
            <select onchange="window.location.href='?status='+this.value">
                <option value="semua" <?php echo $status_filter == 'semua' ? 'selected' : ''; ?>>Semua Status</option>
                <option value="Baru" <?php echo $status_filter == 'Baru' ? 'selected' : ''; ?>>Baru</option>
                <option value="Ditinjau" <?php echo $status_filter == 'Ditinjau' ? 'selected' : ''; ?>>Ditinjau</option>
                <option value="Selesai" <?php echo $status_filter == 'Selesai' ? 'selected' : ''; ?>>Selesai</option>
            </select>
        </div>
    </div>
    
    <!-- Form Tambah -->
    <?php if ($action == 'tambah'): ?>
    <div class="form-container">
        <div class="form-title">Tambah Feedback</div>
        <form method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label>Pelanggan</label>
                    <select name="id_pelanggan" required>
                        <option value="">Pilih Pelanggan</option>
                        <?php 
                        if ($result_pelanggan) {
                            mysqli_data_seek($result_pelanggan, 0);
                            while($row = fetch($result_pelanggan)): 
                        ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['nama']; ?> - <?php echo $row['perusahaan']; ?></option>
                        <?php 
                            endwhile;
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Rating</label>
                    <select name="rating" required>
                        <option value="">Pilih Rating</option>
                        <option value="5">5 - Sangat Puas</option>
                        <option value="4">4 - Puas</option>
                        <option value="3">3 - Cukup</option>
                        <option value="2">2 - Kurang</option>
                        <option value="1">1 - Sangat Kurang</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" required>
                        <option value="Baru">Baru</option>
                        <option value="Ditinjau">Ditinjau</option>
                        <option value="Selesai">Selesai</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label>Komentar</label>
                <textarea name="komentar" rows="4" required placeholder="Masukkan komentar pelanggan..."></textarea>
            </div>
            
            <div class="form-group">
                <label>Tindak Lanjut</label>
                <textarea name="tindak_lanjut" rows="3" placeholder="Tindak lanjut yang dilakukan..."></textarea>
            </div>
            
            <div>
                <button type="submit" name="tambah" class="btn-submit">Simpan</button>
                <a href="feedback.php" class="btn-cancel">Batal</a>
            </div>
        </form>
    </div>
    <?php endif; ?>
    
    <!-- Form Edit -->
    <?php if ($data_edit): ?>
    <div class="form-container">
        <div class="form-title">Edit Feedback</div>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $data_edit['id']; ?>">
            
            <div class="form-row">
                <div class="form-group">
                    <label>Pelanggan</label>
                    <input type="text" class="form-control" value="<?php echo $data_edit['nama_pelanggan']; ?> - <?php echo $data_edit['perusahaan']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label>Rating</label>
                    <select name="rating" required>
                        <option value="5" <?php echo $data_edit['rating'] == 5 ? 'selected' : ''; ?>>5 - Sangat Puas</option>
                        <option value="4" <?php echo $data_edit['rating'] == 4 ? 'selected' : ''; ?>>4 - Puas</option>
                        <option value="3" <?php echo $data_edit['rating'] == 3 ? 'selected' : ''; ?>>3 - Cukup</option>
                        <option value="2" <?php echo $data_edit['rating'] == 2 ? 'selected' : ''; ?>>2 - Kurang</option>
                        <option value="1" <?php echo $data_edit['rating'] == 1 ? 'selected' : ''; ?>>1 - Sangat Kurang</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label>Tanggal</label>
                <input type="date" class="form-control" value="<?php echo $data_edit['tanggal']; ?>" readonly>
            </div>
            
            <div class="form-group">
                <label>Komentar</label>
                <textarea name="komentar" rows="4" required><?php echo $data_edit['komentar']; ?></textarea>
            </div>
            
            <div class="form-group">
                <label>Status</label>
                <select name="status" required>
                    <option value="Baru" <?php echo $data_edit['status'] == 'Baru' ? 'selected' : ''; ?>>Baru</option>
                    <option value="Ditinjau" <?php echo $data_edit['status'] == 'Ditinjau' ? 'selected' : ''; ?>>Ditinjau</option>
                    <option value="Selesai" <?php echo $data_edit['status'] == 'Selesai' ? 'selected' : ''; ?>>Selesai</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Tindak Lanjut</label>
                <textarea name="tindak_lanjut" rows="3"><?php echo $data_edit['tindak_lanjut']; ?></textarea>
            </div>
            
            <div>
                <button type="submit" name="edit" class="btn-submit">Update</button>
                <a href="feedback.php" class="btn-cancel">Batal</a>
            </div>
        </form>
    </div>
    <?php endif; ?>
    
    <!-- Tabel Feedback -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Perusahaan</th>
                    <th>Rating</th>
                    <th>Komentar</th>
                    <th>Status</th>
                    <th>Tindak Lanjut</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($result_feedback && numRows($result_feedback) > 0):
                    $no = 1;
                    while($row = fetch($result_feedback)): 
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo tglIndo($row['tanggal']); ?></td>
                    <td><?php echo $row['nama_pelanggan']; ?></td>
                    <td><?php echo $row['perusahaan']; ?></td>
                    <td class="rating">
                        <?php 
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $row['rating']) {
                                echo '<i class="bi bi-star-fill"></i>';
                            } else {
                                echo '<i class="bi bi-star"></i>';
                            }
                        }
                        ?>
                    </td>
                    <td><?php echo substr($row['komentar'], 0, 50) . '...'; ?></td>
                    <td>
                        <form method="POST" class="status-form">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <select name="status" class="status-select" onchange="this.form.submit()">
                                <option value="Baru" <?php echo $row['status'] == 'Baru' ? 'selected' : ''; ?>>Baru</option>
                                <option value="Ditinjau" <?php echo $row['status'] == 'Ditinjau' ? 'selected' : ''; ?>>Ditinjau</option>
                                <option value="Selesai" <?php echo $row['status'] == 'Selesai' ? 'selected' : ''; ?>>Selesai</option>
                            </select>
                            <input type="hidden" name="update_status" value="1">
                        </form>
                    </td>
                    <td><?php echo $row['tindak_lanjut'] ? substr($row['tindak_lanjut'], 0, 30) . '...' : '-'; ?></td>
                    <td>
                        <a href="?action=edit&id=<?php echo $row['id']; ?>" class="btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="?action=hapus&id=<?php echo $row['id']; ?>" class="btn-danger" onclick="return confirmDelete('?action=hapus&id=<?php echo $row['id']; ?>')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php 
                    endwhile;
                else:
                ?>
                <tr>
                    <td colspan="9" style="text-align: center; padding: 30px;">
                        <i class="bi bi-chat-dots" style="font-size: 24px; color: #ccc;"></i>
                        <p style="margin-top: 10px; color: #999;">Belum ada data feedback</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
</div>

<?php include_once '../includes/footer.php'; ?>