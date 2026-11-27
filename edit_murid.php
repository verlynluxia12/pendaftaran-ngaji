
<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    die("ID murid tidak valid.");
}

$pesan = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
    $tanggal_lahir = mysqli_real_escape_string($conn, $_POST['tanggal_lahir']);

    $sql = "UPDATE murid
            SET nama='$nama',
                jenis_kelamin='$jenis_kelamin',
                tanggal_lahir='$tanggal_lahir'
            WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        header("Location: murid.php");
        exit;
    } else {
        $pesan = "Gagal mengubah data: " . mysqli_error($conn);
    }
}

$hasil = mysqli_query($conn, "SELECT * FROM murid WHERE id=$id");

if (!$hasil) {
    die("Gagal mengambil data: " . mysqli_error($conn));
}

$murid = mysqli_fetch_assoc($hasil);

if (!$murid) {
    die("Data murid tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Murid - NgajiKu</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="sidebar">
    <div class="sb-head">
        <div class="logo">N</div>
        <div class="brand">NgajiKu</div>
    </div>

    <nav class="sb-nav">
        <a href="dashboard.php">Dashboard</a>
        <a href="murid.php">Data Murid</a>
        <a href="kelas.php">Data Kelas</a>
        <a href="jadwal.php">Jadwal</a>
        <a href="absensi.php">Absensi</a>
        <a href="pengumuman.php">Pengumuman</a>
    </nav>

    <div class="sb-foot">
        <a href="logout.php">Keluar</a>
    </div>
</div>

<div class="overlay"></div>

<div class="app">
    <header class="topbar">
        <button class="burger" type="button">☰</button>
        <div class="right">
            <div class="avatar">
                <?php echo strtoupper(substr($_SESSION['nama'] ?? 'U', 0, 1)); ?>
            </div>
        </div>
    </header>

    <main class="main">
        <div class="lead">
            <div>
                <h1>Edit Data Murid</h1>
                <p>Perbarui informasi murid NgajiKu.</p>
            </div>
        </div>

        <div class="card">
            <h2>Formulir Edit Murid</h2>

            <?php if ($pesan != "") { ?>
                <div class="alert">
                    <?php echo htmlspecialchars($pesan); ?>
                </div>
            <?php } ?>

            <form method="POST">

                <div class="field">
                    <label>Nama Murid</label>
                    <input type="text" name="nama"
                        value="<?php echo htmlspecialchars($murid['nama']); ?>"
                        required>
                </div>

                <div class="field">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-laki"
                            <?php if ($murid['jenis_kelamin'] == 'Laki-laki') echo 'selected'; ?>>
                            Laki-laki
                        </option>
                        <option value="Perempuan"
                            <?php if ($murid['jenis_kelamin'] == 'Perempuan') echo 'selected'; ?>>
                            Perempuan
                        </option>
                    </select>
                </div>

                <div class="field">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir"
                        value="<?php echo htmlspecialchars($murid['tanggal_lahir']); ?>"
                        required>
                </div>

                <div class="inline">
                    <button type="submit">Simpan Perubahan</button>
                    <a href="murid.php">Batal</a>
                </div>

            </form>
        </div>
    </main>
</div>

<script>
const burger = document.querySelector('.burger');
const overlay = document.querySelector('.overlay');

if (burger && overlay) {
    burger.addEventListener('click', () => {
        document.body.classList.toggle('menu-open');
    });

    overlay.addEventListener('click', () => {
        document.body.classList.remove('menu-open');
    });
}
</script>

</body>
</html>
