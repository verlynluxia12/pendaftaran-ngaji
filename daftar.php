
<?php
require_once "koneksi.php";

$pesan = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $telepon = trim($_POST['telepon']);
    $alamat = trim($_POST['alamat']);
    $password = $_POST['password'];

    $password = password_hash($password, PASSWORD_DEFAULT);

    $cek = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
    mysqli_stmt_bind_param($cek, "s", $email);
    mysqli_stmt_execute($cek);
    $hasil = mysqli_stmt_get_result($cek);

    if (mysqli_num_rows($hasil) > 0) {
        $pesan = "Email sudah terdaftar!";
    } else {

        $sql = mysqli_prepare($conn, "
            INSERT INTO users
            (nama, email, telepon, alamat, password, role)
            VALUES (?, ?, ?, ?, ?, 'murid')
        ");

        mysqli_stmt_bind_param(
            $sql,
            "sssss",
            $nama,
            $email,
            $telepon,
            $alamat,
            $password
        );

        if (mysqli_stmt_execute($sql)) {
            header("Location: login.php");
            exit;
        } else {
            $pesan = "Pendaftaran gagal. Silakan coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - NgajiKu</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="auth-page">
    <div class="auth-card">

        <div class="auth-head">
            <h1>Daftar Akun</h1>
            <p>Bergabunglah bersama NgajiKu dan mulai perjalanan belajar Al-Qur'an.</p>
        </div>

        <?php if ($pesan !== ""): ?>
            <div class="alert">
                <?= htmlspecialchars($pesan) ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <div class="field">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" placeholder="Masukkan nama lengkap" required>
            </div>

            <div class="field">
                <label>Email</label>
                <input type="email" name="email" placeholder="Masukkan email" required>
            </div>

            <div class="field">
                <label>Nomor Telepon</label>
                <input type="text" name="telepon" placeholder="Masukkan nomor telepon" required>
            </div>

            <div class="field">
                <label>Alamat</label>
                <textarea name="alamat" placeholder="Masukkan alamat lengkap" required></textarea>
            </div>

            <div class="field">
                <label>Password</label>
                <input type="password" name="password" placeholder="Buat password" required>
            </div>

            <button type="submit" class="btn btn-primary">
                Daftar Sekarang
            </button>

        </form>

        <p class="auth-footer">
            Sudah punya akun?
            <a href="login.php">Masuk di sini</a>
        </p>

    </div>
</div>

</body>
</html>
