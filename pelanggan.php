<?php
require_once '../functions.php';
cekAkses(['kepala_produksi']);

$title = 'Data Pelanggan';
$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// PROSES TAMBAH DATA
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah'])) {
    $kode_pelanggan = esc($_POST['kode_pelanggan']);
    $nama = esc($_POST['nama']);
    $perusahaan = esc($_POST['perusahaan']);
    $email = esc($_POST['email']);
    $telepon = esc($_POST['telepon']);
    $alamat = esc($_POST['alamat']);
    $kota = esc($_POST['kota']);
    $provinsi = esc($_POST['provinsi']);
    
    $sql = "INSERT INTO pelanggan (kode_pelanggan, nama, perusahaan, email, telepon, alamat, kota, provinsi) 
            VALUES ('$kode_pelanggan', '$nama', '$perusahaan', '$email', '$telepon', '$alamat', '$kota', '$provinsi')";
    
    if (query($sql)) {
        setAlert('Data pelanggan berhasil ditambahkan!', 'success');
    } else {
        setAlert('Gagal menambahkan data!', 'danger');
    }
    redirect('pelanggan.php');
}

// PROSES EDIT DATA
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {
    $id = (int)$_POST['id'];
    $kode_pelanggan = esc($_POST['kode_pelanggan']);
    $nama = esc($_POST['nama']);
    $perusahaan = esc($_POST['perusahaan']);
    $email = esc($_POST['email']);
    $telepon = esc($_POST['telepon']);
    $alamat = esc($_POST['alamat']);
    $kota = esc($_POST['kota']);
    $provinsi = esc($_POST['provinsi']);
    
    $sql = "UPDATE pelanggan SET 
            kode_pelanggan = '$kode_pelanggan',
            nama = '$nama',
            perusahaan = '$perusahaan',
            email = '$email',
            telepon = '$telepon',
            alamat = '$alamat',
            kota = '$kota',
            provinsi = '$provinsi'
            WHERE id = $id";
    
    if (query($sql)) {
        setAlert('Data pelanggan berhasil diupdate!', 'success');
    } else {
        setAlert('Gagal mengupdate data!', 'danger');
    }
    redirect('pelanggan.php');
}

// PROSES HAPUS DATA
if ($action == 'hapus' && $id > 0) {
    $sql = "DELETE FROM pelanggan WHERE id = $id";
    if (query($sql)) {
        setAlert('Data pelanggan berhasil dihapus!', 'success');
    } else {
        setAlert('Gagal menghapus data!', 'danger');
    }
    redirect('pelanggan.php');
}

// Ambil data untuk edit
$data_edit = null;
if ($action == 'edit' && $id > 0) {
    $sql_edit = "SELECT * FROM pelanggan WHERE id = $id";
    $result_edit = query($sql_edit);
    if ($result_edit && numRows($result_edit) > 0) {
        $data_edit = fetch($result_edit);
    } else {
        setAlert('Data tidak ditemukan!', 'danger');
        redirect('pelanggan.php');
    }
}

// Ambil semua data pelanggan
$sql = "SELECT * FROM pelanggan ORDER BY created_at DESC";
$result = query($sql);

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
</style>

<div class="content-wrapper">
    
    <h1 style="font-size: 28px; margin-bottom: 20px;">Data Pelanggan</h1>
    
    <div class="action-buttons">
        <a href="?action=tambah" class="btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Pelanggan
        </a>
    </div>
    
    <?php showAlert(); ?>
    
    <!-- Form Tambah -->
    <?php if ($action == 'tambah'): ?>
    <div class="form-container">
        <div class="form-title">Tambah Data Pelanggan</div>
        <form method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label>Kode Pelanggan</label>
                    <input type="text" name="kode_pelanggan" placeholder="Contoh: PLG001" required>
                </div>
                <div class="form-group">
                    <label>Nama Pelanggan</label>
                    <input type="text" name="nama" placeholder="Nama lengkap" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Perusahaan</label>
                    <input type="text" name="perusahaan" placeholder="Nama perusahaan">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="email@example.com">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Telepon</label>
                    <input type="text" name="telepon" placeholder="Nomor telepon">
                </div>
                <div class="form-group">
                    <label>Kota</label>
                    <input type="text" name="kota" placeholder="Kota">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Provinsi</label>
                    <input type="text" name="provinsi" placeholder="Provinsi">
                </div>
                <div class="form-group">
                    <label>&nbsp;</label>
                </div>
            </div>
            
            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat" rows="3" placeholder="Alamat lengkap"></textarea>
            </div>
            
            <div>
                <button type="submit" name="tambah" class="btn-submit">Simpan</button>
                <a href="pelanggan.php" class="btn-cancel">Batal</a>
            </div>
        </form>
    </div>
    <?php endif; ?>
    
    <!-- Form Edit -->
    <?php if ($data_edit): ?>
    <div class="form-container">
        <div class="form-title">Edit Data Pelanggan</div>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $data_edit['id']; ?>">
            
            <div class="form-row">
                <div class="form-group">
                    <label>Kode Pelanggan</label>
                    <input type="text" name="kode_pelanggan" value="<?php echo $data_edit['kode_pelanggan']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Nama Pelanggan</label>
                    <input type="text" name="nama" value="<?php echo $data_edit['nama']; ?>" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Perusahaan</label>
                    <input type="text" name="perusahaan" value="<?php echo $data_edit['perusahaan']; ?>">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo $data_edit['email']; ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Telepon</label>
                    <input type="text" name="telepon" value="<?php echo $data_edit['telepon']; ?>">
                </div>
                <div class="form-group">
                    <label>Kota</label>
                    <input type="text" name="kota" value="<?php echo $data_edit['kota']; ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Provinsi</label>
                    <input type="text" name="provinsi" value="<?php echo $data_edit['provinsi']; ?>">
                </div>
                <div class="form-group">
                    <label>&nbsp;</label>
                </div>
            </div>
            
            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat" rows="3"><?php echo $data_edit['alamat']; ?></textarea>
            </div>
            
            <div>
                <button type="submit" name="edit" class="btn-submit">Update</button>
                <a href="pelanggan.php" class="btn-cancel">Batal</a>
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
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Perusahaan</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Kota</th>
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
                    <td><?php echo $row['kode_pelanggan']; ?></td>
                    <td><?php echo $row['nama']; ?></td>
                    <td><?php echo $row['perusahaan']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['telepon']; ?></td>
                    <td><?php echo $row['kota']; ?></td>
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
                    <td colspan="8" style="text-align: center; padding: 30px;">
                        <i class="bi bi-people" style="font-size: 24px; color: #ccc;"></i>
                        <p style="margin-top: 10px; color: #999;">Belum ada data pelanggan</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
</div>

<?php include_once '../includes/footer.php'; ?>