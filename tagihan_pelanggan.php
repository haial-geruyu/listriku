<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user']) || $_SESSION['level'] != 'pelanggan') {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id_user'];
$q = mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE id_user = $id_user");
$pelanggan = mysqli_fetch_assoc($q);
$id_pelanggan = $pelanggan['id_pelanggan'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tagihan Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h4>Tagihan Listrik Anda</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Bulan</th><th>Tahun</th><th>Jumlah Meter</th><th>Total Tagihan</th><th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $tagihan = mysqli_query($koneksi, "SELECT * FROM tagihan WHERE id_pelanggan = $id_pelanggan ORDER BY tahun DESC, bulan DESC");
            while ($t = mysqli_fetch_assoc($tagihan)) {
                echo "<tr>
                    <td>{$t['bulan']}</td>
                    <td>{$t['tahun']}</td>
                    <td>{$t['jumlah_meter']}</td>
                    <td>Rp " . number_format($t['total_tagihan'], 0, ',', '.') . "</td>
                    <td><span class='badge " . ($t['status'] == 'Lunas' ? 'bg-success' : 'bg-danger') . "'>{$t['status']}</span></td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>
