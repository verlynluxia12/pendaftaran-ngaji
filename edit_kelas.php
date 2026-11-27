
<?php
session_start();
require_once "koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    die("ID kelas tidak valid.");
}

$stmt = mysqli_prepare($conn, "SELECT * FROM kelas WHERE id = ?");

if (!$stmt) {
    die("Gagal menyiapkan query: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$data) {
    die("Data kelas tidak ditemukan.");
}

$pesan = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama = trim($_POST['nama'] ?? "");
    $deskripsi = trim($_POST['deskripsi'] ?? "");

    if ($nama === "") {
        $pesan = "Nama kelas wajib diisi.";
    } else {
        $update = mysqli_prepare($conn, "UPDATE kelas SET nama = ?, deskripsi = ? WHERE id = ?");

        if ($update) {
            mysqli_stmt_bind_param($update, "ssi", $nama, $deskripsi, $id);

            if (mysqli_stmt_execute($update)) {
                mysqli_stmt_close($update);
                header("Location: kelas.php");
                exit;
            } else {
                $pesan = "Gagal mengubah data kelas: " . mysqli_stmt_error($update);
            }

            mysqli_stmt_close($update);
        } else {
            $pesan = "Gagal menyiapkan query: " . mysqli_error($conn);
        }

        // Pertahankan data yang sedang diedit jika terjadi kesalahan.
        $data['nama'] = $nama;
        $data['deskripsi'] = $deskripsi;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kelas - NgajiKu</title>
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
                <h1>Edit Kelas</h1>
                <p>Perbarui informasi kelas NgajiKu.</p>
            </div>
        </div>

        <div class="card">
            <h2>Formulir Edit Kelas</h2>

            <?php if ($pesan !== "") { ?>
                <div class="alert">
                    <?php echo htmlspecialchars($pesan); ?>
                </div>
            <?php } ?>

            <form method="POST">
                <div class="field">
                    <label>Nama Kelas</label>
                    <input
                        type="text"
                        name="nama"
                        value="<?php echo htmlspecialchars($data['nama']); ?>"
                        required
                    >
                </div>

                <div class="field">
                    <label>Deskripsi</label>
                    <textarea
                        name="deskripsi"
                        rows="4"
                    ><?php echo htmlspecialchars($data['deskripsi'] ?? ''); ?></textarea>
                </div>

                <div class="inline">
                    <button type="submit">Simpan Perubahan</button>
                    <a href="kelas.php">Batal</a>
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
