<?php
$koneksi = mysqli_connect("localhost", "root", "", "listrik"); // <--- Ganti jika perlu

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
