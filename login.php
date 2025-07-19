<?php
session_start();
include 'koneksi.php';

$pesan_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' AND password='$password'");
    $user = mysqli_fetch_assoc($query);

    if ($user) {
        $level_query = mysqli_query($koneksi, "SELECT nama_level FROM level WHERE id_level = " . $user['id_level']);
        $level = mysqli_fetch_assoc($level_query)['nama_level'];

        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['level'] = $level;

        if ($level == 'admin') {
            header("Location: dashboard_admin.php");
        } elseif ($level == 'pelanggan') {
            header("Location: dashboard_pelanggan.php");
        } else {
            $pesan_error = "Level pengguna tidak dikenali.";
        }
    } else {
        $pesan_error = "Username atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Listrik Pascabayar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header text-center bg-primary text-white">
                        <h4>Login</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($pesan_error): ?>
                            <div class="alert alert-danger"><?= $pesan_error ?></div>
                        <?php endif; ?>
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <small>&copy; <?= date('Y') ?> Listrik Pascabayar</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
