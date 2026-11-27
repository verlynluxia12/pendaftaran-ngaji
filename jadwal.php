
<?php
session_start();
require_once "koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$query = mysqli_query($conn, "
    SELECT jadwal.*, kelas.nama AS nama_kelas
    FROM jadwal
    LEFT JOIN kelas ON jadwal.kelas_id = kelas.id
    ORDER BY jadwal.id ASC
");

if (!$query) {
    die("Gagal mengambil data jadwal: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Mengaji NgajiKu</title>
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

            <strong>Jadwal Mengaji</strong>

            <div class="right">
                <div class="avatar">U</div>
            </div>
        </header>

        <main class="main">

            <h1>Jadwal Mengaji</h1>

            <p class="lead">
                Lihat dan kelola jadwal kegiatan belajar mengaji.
            </p>

            <div class="card">

                <div class="row" style="align-items:center; justify-content:space-between; flex-wrap:wrap;">
                    <div>
                        <h2>Daftar Jadwal</h2>
                        <p>Jadwal kegiatan setiap kelas NgajiKu.</p>
                    </div>

                    <a href="tambah_jadwal.php" class="btn">
                        + Tambah Jadwal
                    </a>
                </div>

                <div class="table-wrap">

                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Kelas</th>
                                <th>Hari</th>
                                <th>Jam Mulai</th>
                                <th>Jam Selesai</th>
                                <th>Ruang</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php if (mysqli_num_rows($query) > 0) { ?>

                                <?php while ($data = mysqli_fetch_assoc($query)) { ?>

                                <tr>
                                    <td><?= htmlspecialchars($data['id']) ?></td>
                                    <td><strong><?= htmlspecialchars($data['nama_kelas'] ?? '-') ?></strong></td>
                                    <td><?= htmlspecialchars($data['hari']) ?></td>
                                    <td><?= htmlspecialchars($data['jam_mulai']) ?></td>
                                    <td><?= htmlspecialchars($data['jam_selesai']) ?></td>
                                    <td><?= htmlspecialchars($data['ruang']) ?></td>

                                    <td>
                                        <div class="inline">

                                            <a href="edit_jadwal.php?id=<?= (int)$data['id'] ?>">
                                                Edit
                                            </a>

                                            <form action="hapus_jadwal.php" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">

                                                <input type="hidden" name="id"
                                                    value="<?= (int)$data['id'] ?>">

                                                <button type="submit">
                                                    Hapus
                                                </button>

                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <?php } ?>

                            <?php } else { ?>

                                <tr>
                                    <td colspan="7" class="empty">
                                        Belum ada jadwal mengaji.
                                    </td>
                                </tr>

                            <?php } ?>

                        </tbody>
                    </table>

                </div>
            </div>

            <p style="margin-top:20px;">
                <a href="dashboard.php">← Kembali ke Dashboard</a>
            </p>

        </main>
    </div>

</body>
</html>
