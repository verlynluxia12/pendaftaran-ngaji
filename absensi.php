
<?php
session_start();
require_once "koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$query = mysqli_query($conn, "
    SELECT absensi.*, murid.nama AS nama_murid
    FROM absensi
    LEFT JOIN murid ON absensi.murid_id = murid.id
    ORDER BY absensi.tanggal DESC, absensi.waktu DESC
");

if (!$query) {
    die("Gagal mengambil data absensi: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Absensi NgajiKu</title>
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

            <strong>Data Absensi</strong>

            <div class="right">
                <div class="avatar">U</div>
            </div>
        </header>

        <main class="main">

            <h1>Data Absensi Mengaji</h1>

            <p class="lead">
                Pantau kehadiran murid dalam kegiatan belajar mengaji.
            </p>

            <div class="card">

                <div class="row" style="align-items:center; justify-content:space-between; flex-wrap:wrap;">
                    <div>
                        <h2>Daftar Absensi</h2>
                        <p>Riwayat kehadiran seluruh murid.</p>
                    </div>

                    <a href="tambah_absensi.php" class="btn">
                        + Tambah Absensi
                    </a>
                </div>

                <div class="table-wrap">

                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Murid</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php if (mysqli_num_rows($query) > 0) { ?>

                                <?php while ($data = mysqli_fetch_assoc($query)) { ?>

                                <tr>
                                    <td><?= htmlspecialchars($data['id']) ?></td>

                                    <td>
                                        <strong><?= htmlspecialchars($data['nama_murid'] ?? '-') ?></strong>
                                    </td>

                                    <td><?= htmlspecialchars($data['tanggal']) ?></td>

                                    <td><?= htmlspecialchars($data['waktu']) ?></td>

                                    <td>
                                        <?php
                                        $status = $data['status'];
                                        $badgeClass = '';

                                        if ($status === 'Hadir') {
                                            $badgeClass = 'b-hadir';
                                        } elseif ($status === 'Izin') {
                                            $badgeClass = 'b-izin';
                                        } elseif ($status === 'Sakit') {
                                            $badgeClass = 'b-tidak';
                                        }
                                        ?>

                                        <span class="badge <?= $badgeClass ?>">
                                            <?= htmlspecialchars($status) ?>
                                        </span>
                                    </td>
                                </tr>

                                <?php } ?>

                            <?php } else { ?>

                                <tr>
                                    <td colspan="5" class="empty">
                                        Belum ada data absensi.
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
