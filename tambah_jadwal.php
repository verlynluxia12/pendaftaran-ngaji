
<?php
session_start();
require_once "koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$kelas = mysqli_query($conn, "SELECT id, nama FROM kelas ORDER BY nama ASC");

if (!$kelas) {
    die("Gagal mengambil data kelas: " . mysqli_error($conn));
}

$pesan = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $kelas_id = (int)($_POST['kelas_id'] ?? 0);
    $hari = trim($_POST['hari'] ?? "");
    $jam_mulai = $_POST['jam_mulai'] ?? "";
    $jam_selesai = $_POST['jam_selesai'] ?? "";
    $ruang = trim($_POST['ruang'] ?? "");

    if ($kelas_id <= 0 || $hari === "" || $jam_mulai === "" || $jam_selesai === "" || $ruang === "") {
        $pesan = "Semua kolom wajib diisi.";
    } elseif ($jam_selesai <= $jam_mulai) {
        $pesan = "Jam selesai harus setelah jam mulai.";
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO jadwal (kelas_id, hari, jam_mulai, jam_selesai, ruang) VALUES (?, ?, ?, ?, ?)");

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "issss", $kelas_id, $hari, $jam_mulai, $jam_selesai, $ruang);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                header("Location: jadwal.php");
                exit;
            } else {
                $pesan = "Gagal menambahkan jadwal. Pastikan kelas yang dipilih tersedia.";
            }

            mysqli_stmt_close($stmt);
        } else {
            $pesan = "Gagal menyiapkan query: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jadwal - NgajiKu</title>
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
                <h1>Tambah Jadwal</h1>
                <p>Atur jadwal kegiatan belajar mengaji.</p>
            </div>
        </div>

        <div class="card">
            <h2>Formulir Jadwal</h2>

            <?php if ($pesan !== "") { ?>
                <div class="alert">
                    <?php echo htmlspecialchars($pesan); ?>
                </div>
            <?php } ?>

            <form method="POST">

                <div class="field">
                    <label>Kelas</label>
                    <select name="kelas_id" required>
                        <option value="">Pilih Kelas</option>

                        <?php
                        mysqli_data_seek($kelas, 0);
                        while ($k = mysqli_fetch_assoc($kelas)) {
                        ?>
                            <option value="<?php echo (int)$k['id']; ?>"
                                <?php echo ((int)($_POST['kelas_id'] ?? 0) === (int)$k['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($k['nama']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="field">
                    <label>Hari</label>
                    <select name="hari" required>
                        <option value="">Pilih Hari</option>
                        <?php
                        $daftar_hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                        foreach ($daftar_hari as $h) {
                        ?>
                            <option value="<?php echo $h; ?>"
                                <?php echo (($_POST['hari'] ?? '') === $h) ? 'selected' : ''; ?>>
                                <?php echo $h; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="field">
                    <label>Jam Mulai</label>
                    <input type="time" name="jam_mulai"
                        value="<?php echo htmlspecialchars($_POST['jam_mulai'] ?? ''); ?>"
                        required>
                </div>

                <div class="field">
                    <label>Jam Selesai</label>
                    <input type="time" name="jam_selesai"
                        value="<?php echo htmlspecialchars($_POST['jam_selesai'] ?? ''); ?>"
                        required>
                </div>

                <div class="field">
                    <label>Ruang</label>
                    <input type="text" name="ruang"
                        placeholder="Contoh: Ruang A"
                        value="<?php echo htmlspecialchars($_POST['ruang'] ?? ''); ?>"
                        required>
                </div>

                <div class="inline">
                    <button type="submit">Simpan Jadwal</button>
                    <a href="jadwal.php">Batal</a>
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
