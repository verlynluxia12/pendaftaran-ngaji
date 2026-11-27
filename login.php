
<?php
session_start();
include 'koneksi.php';

$pesan = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $hasil = mysqli_query($conn, $sql);

    if ($hasil && mysqli_num_rows($hasil) > 0) {

        $user = mysqli_fetch_assoc($hasil);

        $password_benar = password_verify($password, $user['password'])
            || hash_equals(md5($password), $user['password']);

        if ($password_benar) {

            if (hash_equals(md5($password), $user['password'])) {
                $hash_baru = password_hash($password, PASSWORD_DEFAULT);
                $id = (int) $user['id'];

                mysqli_query($conn, "UPDATE users SET password = '$hash_baru' WHERE id = $id");
            }

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['role'] = $user['role'];

            header("Location: dashboard.php");
            exit;

        } else {
            $pesan = "Password salah!";
        }

    } else {
        $pesan = "Email belum terdaftar!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - NgajiKu</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="auth-page">
    <div class="auth-card">

        <div class="auth-head">
            <h1>Selamat Datang!</h1>
            <p>Masuk ke akun NgajiKu untuk melanjutkan kegiatan mengajimu.</p>
        </div>

        <?php if ($pesan !== ""): ?>
            <div class="alert">
                <?= htmlspecialchars($pesan) ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <div class="field">
                <label>Email</label>
                <input type="email" name="email"
                       placeholder="Masukkan email"
                       required>
            </div>

            <div class="field">
                <label>Password</label>
                <input type="password" name="password"
                       placeholder="Masukkan password"
                       required>
            </div>

            <button type="submit" class="btn btn-primary">
                Masuk Sekarang
            </button>

        </form>

        <p class="auth-footer">
            Belum punya akun?
            <a href="daftar.php">Daftar di sini</a>
        </p>

        <p class="auth-footer">
            <a href="index.html">← Kembali ke Beranda</a>
        </p>

    </div>
</div>

</body>
</html>
