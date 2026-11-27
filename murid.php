
<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$sql = "SELECT * FROM murid ORDER BY id DESC";
$hasil = mysqli_query($conn, $sql);

if (!$hasil) {
    die("Gagal mengambil data murid: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Murid NgajiKu</title>
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

            <strong>Data Murid</strong>

            <div class="right">
                <div class="avatar">U</div>
            </div>
        </header>

        <main class="main">

            <h1>Data Murid</h1>

            <p class="lead">
                Kelola dan pantau seluruh data murid NgajiKu.
            </p>

            <div class="card">

                <div class="row" style="align-items:center; justify-content:space-between; flex-wrap:wrap;">
                    <div>
                        <h2>Daftar Murid</h2>
                        <p>Berikut daftar murid yang terdaftar.</p>
                    </div>

                    <a href="tambah_murid.php" class="btn">
                        + Tambah Murid
                    </a>
                </div>

                <div class="table-wrap">

                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Jenis Kelamin</th>
                                <th>Tanggal Lahir</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (mysqli_num_rows($hasil) > 0) { ?>

                                <?php while ($murid = mysqli_fetch_assoc($hasil)) { ?>

                                <tr>
                                    <td><?php echo $murid['id']; ?></td>

                                    <td>
                                        <strong>
                                            <?php echo htmlspecialchars($murid['nama']); ?>
                                        </strong>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($murid['jenis_kelamin']); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($murid['tanggal_lahir'] ?? ''); ?>
                                    </td>

                                    <td>
                                        <span class="badge">
                                            <?php echo htmlspecialchars($murid['status']); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <div class="inline">
                                            <a href="edit_murid.php?id=<?php echo $murid['id']; ?>">
                                                Edit
                                            </a>

                                            <form action="hapus_murid.php" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus murid ini?');">

                                                <input type="hidden" name="id"
                                                    value="<?php echo $murid['id']; ?>">

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
                                    <td colspan="6" class="empty">
                                        Belum ada data murid.
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
