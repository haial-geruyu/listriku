<?php
session_start();
include 'koneksi.php';

// Cek login dan level admin
if (!isset($_SESSION['id_user']) || $_SESSION['level'] != 'admin') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <h3 class="mb-4">Selamat datang Admin, <?= $_SESSION['username'] ?></h3>

        <!-- RINGKASAN -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-primary shadow-sm">
                    <div class="card-body">
                        <?php
                        $pelanggan = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pelanggan");
                        $total_pelanggan = mysqli_fetch_assoc($pelanggan)['total'];
                        ?>
                        <h5>Total Pelanggan</h5>
                        <h3><?= $total_pelanggan ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-success shadow-sm">
                    <div class="card-body">
                        <?php
                        $total_tagihan = mysqli_query($koneksi, "SELECT SUM(total_tagihan) AS total FROM tagihan");
                        $total_tagihan_rp = number_format(mysqli_fetch_assoc($total_tagihan)['total'], 0, ',', '.');
                        ?>
                        <h5>Total Tagihan</h5>
                        <h3>Rp <?= $total_tagihan_rp ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-warning shadow-sm">
                    <div class="card-body">
                        <?php
                        $total_bayar = mysqli_query($koneksi, "SELECT SUM(total_bayar) AS total FROM pembayaran");
                        $total_bayar_rp = number_format(mysqli_fetch_assoc($total_bayar)['total'], 0, ',', '.');
                        ?>
                        <h5>Total Pembayaran</h5>
                        <h3>Rp <?= $total_bayar_rp ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- DATA PELANGGAN -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5>Data Pelanggan</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>nomor kwh</th>
                            <th>Alamat</th>
                            <th>Status Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = mysqli_query($koneksi, "
                            SELECT p.nama_pelanggan, p.nomor_kwh, p.alamat,
                                (
                                    SELECT 
                                        IF(SUM(t.total_tagihan) <= IFNULL(SUM(b.total_bayar),0), 'Lunas', 'Belum Lunas')
                                    FROM tagihan t
                                    LEFT JOIN pembayaran b ON t.id_tagihan = b.id_tagihan
                                    WHERE t.id_pelanggan = p.id_pelanggan
                                ) AS status_bayar
                            FROM pelanggan p
                        ");

                        while ($row = mysqli_fetch_assoc($query)) {
                            echo "<tr>
                                <td>{$row['nama_pelanggan']}</td>
                                <td>{$row['nomor_kwh']}</td>
                                <td>{$row['alamat']}</td>
                                <td><span class='badge ".($row['status_bayar'] == 'Lunas' ? 'bg-success' : 'bg-danger')."'>{$row['status_bayar']}</span></td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <a href="logout.php" class="btn btn-secondary">Logout</a>
    </div>
</body>
</html>
