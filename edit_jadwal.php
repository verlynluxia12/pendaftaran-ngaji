
<?php
session_start();
require_once "koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    die("ID jadwal tidak valid.");
}

$stmt = mysqli_prepare($conn, "SELECT * FROM jadwal WHERE id = ?");

if (!$stmt) {
    die("Gagal menyiapkan query: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$data) {
    die("Jadwal tidak ditemukan.");
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
        $update = mysqli_prepare($conn, "UPDATE jadwal SET kelas_id = ?, hari = ?, jam_mulai = ?, jam_selesai = ?, ruang = ? WHERE id = ?");

        if ($update) {
            mysqli_stmt_bind_param($update, "issssi", $kelas_id, $hari, $jam_mulai, $jam_selesai, $ruang, $id);

            if (mysqli_stmt_execute($update)) {
                mysqli_stmt_close($update);
                header("Location: jadwal.php");
                exit;
            } else {
                $pesan = "Gagal mengubah jadwal: " . mysqli_stmt_error($update);
            }

            mysqli_stmt_close($update);
        } else {
            $pesan = "Gagal menyiapkan query: " . mysqli_error($conn);
        }
    }

    $data['kelas_id'] = $kelas_id;
    $data['hari'] = $hari;
    $data['jam_mulai'] = $jam_mulai;
    $data['jam_selesai'] = $jam_selesai;
    $data['ruang'] = $ruang;
}

$kelas = mysqli_query($conn, "SELECT id, nama FROM kelas ORDER BY nama ASC");

if (!$kelas) {
    die("Gagal mengambil data kelas: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Jadwal - NgajiKu</title>
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
                <h1>Edit Jadwal</h1>
                <p>Perbarui informasi jadwal kegiatan mengaji.</p>
            </div>
        </div>

        <div class="card">
            <h2>Formulir Edit Jadwal</h2>

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

                        <?php while ($k = mysqli_fetch_assoc($kelas)) { ?>
                            <option value="<?php echo (int)$k['id']; ?>"
                                <?php echo ((int)$k['id'] === (int)$data['kelas_id']) ? 'selected' : ''; ?>>
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
                        $hari_list = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"];

                        foreach ($hari_list as $h) {
                        ?>
                            <option value="<?php echo $h; ?>"
                                <?php echo ($h === $data['hari']) ? 'selected' : ''; ?>>
                                <?php echo $h; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="field">
                    <label>Jam Mulai</label>
                    <input type="time" name="jam_mulai"
                        value="<?php echo htmlspecialchars($data['jam_mulai']); ?>"
                        required>
                </div>

                <div class="field">
                    <label>Jam Selesai</label>
                    <input type="time" name="jam_selesai"
                        value="<?php echo htmlspecialchars($data['jam_selesai']); ?>"
                        required>
                </div>

                <div class="field">
                    <label>Ruang</label>
                    <input type="text" name="ruang"
                        value="<?php echo htmlspecialchars($data['ruang']); ?>"
                        required>
                </div>

                <div class="inline">
                    <button type="submit">Simpan Perubahan</button>
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
