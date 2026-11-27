
<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$pesan = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
    $tanggal_lahir = mysqli_real_escape_string($conn, $_POST['tanggal_lahir']);

    $ortu_id = $_SESSION['user_id'];

    $sql = "INSERT INTO murid
            (users_id, ortu_id, kelas_id, nama, jenis_kelamin, tanggal_lahir, status)
            VALUES
            (NULL, '$ortu_id', NULL, '$nama', '$jenis_kelamin', '$tanggal_lahir', 'aktif')";

    if (mysqli_query($conn, $sql)) {
        $pesan = "Data murid berhasil ditambahkan!";
    } else {
        $pesan = "Gagal: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Murid - NgajiKu</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="sidebar">
    <div class="sb-head">
        <div class="logo">N</div>
        <div class="brand">
            <strong>NgajiKu</strong>
            <span>Sistem Informasi Ngaji</span>
        </div>
    </div>

    <nav class="sb-nav">
        <a href="dashboard.php">Dashboard</a>
        <a href="murid.php">Data Murid</a>
        <a href="kelas.php">Data Kelas</a>
        <a href="jadwal.php">Jadwal Ngaji</a>
        <a href="absensi.php">Data Absensi</a>
        <a href="pengumuman.php">Pengumuman</a>
    </nav>

    <div class="sb-foot">
        <a href="logout.php">Keluar</a>
    </div>
</div>

<div class="overlay" onclick="document.body.classList.remove('menu-open')"></div>

<div class="app">
    <header class="topbar">
        <button class="burger" onclick="document.body.classList.toggle('menu-open')">
            ☰
        </button>

        <div class="right">
            <span><?php echo htmlspecialchars($_SESSION['nama']); ?></span>
            <div class="avatar">
                <?php echo strtoupper(substr($_SESSION['nama'], 0, 1)); ?>
            </div>
        </div>
    </header>

    <main class="main">
        <div class="lead">
            <h1>Tambah Data Murid</h1>
            <p>Lengkapi formulir berikut untuk mendaftarkan murid baru.</p>
        </div>

        <div class="card">
            <h2>Formulir Data Murid</h2>
            <p>Masukkan informasi murid dengan benar.</p>

            <?php if ($pesan != ""): ?>
                <div class="alert">
                    <?php echo htmlspecialchars($pesan); ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="field">
                    <label>Nama Murid</label>
                    <input
                        type="text"
                        name="nama"
                        placeholder="Masukkan nama lengkap murid"
                        required
                    >
                </div>

                <div class="field">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>

                <div class="field">
                    <label>Tanggal Lahir</label>
                    <input
                        type="date"
                        name="tanggal_lahir"
                        required
                    >
                </div>

                <button type="submit">Simpan Data</button>
            </form>

            <div class="inline">
                <a href="murid.php">Lihat Data Murid</a>
                <a href="dashboard.php">Kembali ke Dashboard</a>
            </div>
        </div>
    </main>
</div>

</body>
</html>
