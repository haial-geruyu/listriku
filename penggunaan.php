<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user']) || $_SESSION['level'] != 'pelanggan') {
    header("Location: login.php");
    exit;
}

// ambil id pelanggan
$id_user = $_SESSION['id_user'];
$q = mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE id_user = $id_user");
$data = mysqli_fetch_assoc($q);
$id_pelanggan = $data['id_pelanggan'];

// Tambah
if (isset($_POST['tambah'])) {
    $bulan = $_POST['bulan'];
    $tahun = $_POST['tahun'];
    $awal = $_POST['meter_awal'];
    $akhir = $_POST['meter_akhir'];

    mysqli_query($koneksi, "INSERT INTO penggunaan (id_pelanggan, bulan, tahun, meter_awal, meter_akhir)
        VALUES ($id_pelanggan, '$bulan', $tahun, $awal, $akhir)");
    header("Location: penggunaan.php");
    exit;
}

// Hapus
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    mysqli_query($koneksi, "DELETE FROM penggunaan WHERE id_penggunaan = $id AND id_pelanggan = $id_pelanggan");
    header("Location: penggunaan.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Penggunaan Listrik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h4>Penggunaan Listrik</h4>

    <form method="POST" class="mb-4">
        <div class="row g-2">
            <div class="col-md-2">
                <input type="text" name="bulan" class="form-control" placeholder="Bulan" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="tahun" class="form-control" placeholder="Tahun" required>
            </div>
            <div class="col-md-3">
                <input type="number" name="meter_awal" class="form-control" placeholder="Meter Awal" required>
            </div>
            <div class="col-md-3">
                <input type="number" name="meter_akhir" class="form-control" placeholder="Meter Akhir" required>
            </div>
            <div class="col-md-2">
                <button type="submit" name="tambah" class="btn btn-primary w-100">Tambah</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Bulan</th><th>Tahun</th><th>Meter Awal</th><th>Meter Akhir</th><th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $q = mysqli_query($koneksi, "SELECT * FROM penggunaan WHERE id_pelanggan = $id_pelanggan ORDER BY tahun DESC, bulan DESC");
            while ($row = mysqli_fetch_assoc($q)) {
                echo "<tr>
                    <td>{$row['bulan']}</td>
                    <td>{$row['tahun']}</td>
                    <td>{$row['meter_awal']}</td>
                    <td>{$row['meter_akhir']}</td>
                    <td><a href='?hapus={$row['id_penggunaan']}' class='btn btn-danger btn-sm'>Hapus</a></td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
    <a href="logout.php" class="btn btn-secondary">Logout</a>
</div>
</body>
</html>
