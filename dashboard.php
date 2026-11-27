
<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ustadz') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard NgajiKu</title>
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

            <div>
                <strong>Dashboard</strong>
            </div>

            <div class="right">
                <div class="avatar">U</div>
            </div>
        </header>

        <main class="main">

            <!-- Foto Dashboard -->
                       <h1>Selamat Datang di NgajiKu!</h1>

            <p class="lead">
                Kelola kegiatan belajar mengaji dengan lebih mudah.
            </p>

            <div class="card">
                <h2>
                    Assalamu'alaikum,
                    <?php echo htmlspecialchars($_SESSION['nama']); ?>!
                </h2>

                <p>
                    Selamat datang di dashboard ustadz NgajiKu.
                    Semoga aktivitas mengajar hari ini berjalan lancar.
                </p>
            </div>

            <h2>Menu Utama</h2>

            <div class="stat-grid">

                <a href="murid.php" class="item">
                    <div class="sicon">♧</div>
                    <h3>Kelola Murid</h3>
                    <p>Lihat dan kelola data murid.</p>
                </a>

                <a href="kelas.php" class="item">
                    <div class="sicon">▤</div>
                    <h3>Kelola Kelas</h3>
                    <p>Atur informasi kelas mengaji.</p>
                </a>

                <a href="jadwal.php" class="item">
                    <div class="sicon">▦</div>
                    <h3>Kelola Jadwal</h3>
                    <p>Lihat jadwal kegiatan belajar.</p>
                </a>

                <a href="absensi.php" class="item">
                    <div class="sicon">✓</div>
                    <h3>Kelola Absensi</h3>
                    <p>Pantau kehadiran murid.</p>
                </a>

                <a href="pengumuman.php" class="item">
                    <div class="sicon">♢</div>
                    <h3>Pengumuman</h3>
                    <p>Buat dan lihat pengumuman.</p>
                </a>

                <a href="tambah_kelas.php" class="item">
                    <div class="sicon">＋</div>
                    <h3>Tambah Kelas</h3>
                    <p>Tambahkan kelas baru.</p>
                </a>

            </div>

        </main>
    </div>

</body>
</html>
