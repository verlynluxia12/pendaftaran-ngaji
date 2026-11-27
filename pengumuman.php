
<?php
session_start();
require_once "koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$pesan = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $judul = trim($_POST['judul']);
    $isi = trim($_POST['isi']);

    if ($judul !== "" && $isi !== "") {
        $stmt = mysqli_prepare($conn, "
            INSERT INTO pengumuman (judul, isi)
            VALUES (?, ?)
        ");

        mysqli_stmt_bind_param($stmt, "ss", $judul, $isi);

        if (mysqli_stmt_execute($stmt)) {
            $pesan = "Pengumuman berhasil ditambahkan!";
        } else {
            $pesan = "Pengumuman gagal ditambahkan.";
        }

        mysqli_stmt_close($stmt);
    } else {
        $pesan = "Judul dan isi harus diisi.";
    }
}

$data = mysqli_query($conn, "
    SELECT * FROM pengumuman
    ORDER BY id DESC
");

if (!$data) {
    die("Gagal mengambil pengumuman: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengumuman NgajiKu</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sb-head">
            <div class="logo">N</div>
            <div class="brand">NgajiKu</div>
        </div>

        <nav class="sb-nav">
            <a href="dashboard.php">⌂ Dashboard</a>
            <a href="index.html">⌂ Beranda</a>
            <a href="murid.php">♧ Kelola Murid</a>
            <a href="kelas.php">▤ Kelola Kelas</a>
            <a href="jadwal.php">▦ Kelola Jadwal</a>
            <a href="absensi.php">✓ Kelola Absensi</a>
            <a href="pengumuman.php">♢ Pengumuman</a>
            <a href="tambah_kelas.php">＋ Tambah Kelas</a>
        </nav>

        <div class="sb-foot">
            <a href="logout.php">↪ Logout</a>
        </div>
    </aside>

    <div class="overlay" onclick="document.body.classList.remove('menu-open')"></div>

    <!-- Konten utama -->
    <div class="app">

        <header class="topbar">
            <button class="burger" onclick="document.body.classList.toggle('menu-open')">
                ☰
            </button>

            <strong>Pengumuman</strong>

            <div class="right">
                <div class="avatar">U</div>
            </div>
        </header>

        <main class="main">

            <h1>Pengumuman NgajiKu</h1>

            <p class="lead">
                Sampaikan informasi penting kepada murid dan orang tua.
            </p>

            <!-- Pesan -->
            <?php if ($pesan !== "") { ?>
                <div class="alert">
                    <?= htmlspecialchars($pesan) ?>
                </div>
            <?php } ?>

            <!-- Form tambah pengumuman -->
            <div class="card">

                <h2>Tambah Pengumuman</h2>

                <p>Buat pengumuman baru untuk warga NgajiKu.</p>

                <form method="POST">

                    <div class="field">
                        <label>Judul Pengumuman</label>
                        <input
                            type="text"
                            name="judul"
                            placeholder="Masukkan judul pengumuman"
                            required
                        >
                    </div>

                    <div class="field">
                        <label>Isi Pengumuman</label>
                        <textarea
                            name="isi"
                            rows="5"
                            placeholder="Tuliskan isi pengumuman..."
                            required
                        ></textarea>
                    </div>

                    <button type="submit">
                        Simpan Pengumuman
                    </button>

                </form>
            </div>

            <!-- Daftar pengumuman -->
            <h2 style="margin-top:30px;">Daftar Pengumuman</h2>

            <div class="card">

                <?php if (mysqli_num_rows($data) > 0) { ?>

                    <?php while ($row = mysqli_fetch_assoc($data)) { ?>

                        <div class="item" style="margin-bottom:15px;">

                            <h3>
                                <?= htmlspecialchars($row['judul']) ?>
                            </h3>

                            <p>
                                <?= nl2br(htmlspecialchars($row['isi'])) ?>
                            </p>

                        </div>

                    <?php } ?>

                <?php } else { ?>

                    <div class="empty">
                        Belum ada pengumuman.
                    </div>

                <?php } ?>

            </div>

            <p style="margin-top:20px;">
                <a href="dashboard.php">← Kembali ke Dashboard</a>
            </p>

        </main>
    </div>

</body>
</html>
