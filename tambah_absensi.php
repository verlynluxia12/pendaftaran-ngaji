
<?php
session_start();
require_once "koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$pesan = "";

// Ambil anak yang terhubung dengan akun orang tua
$stmt = mysqli_prepare($conn, "
    SELECT id, nama
    FROM murid
    WHERE ortu_id = ?
    ORDER BY nama ASC
");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$murid = mysqli_stmt_get_result($stmt);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $murid_id = (int)($_POST['murid_id'] ?? 0);
    $status = $_POST['status'] ?? "";

    $status_valid = ["Hadir", "Izin", "Sakit"];

    // Pastikan murid benar-benar anak dari akun ini
    $cek = mysqli_prepare($conn, "
        SELECT id FROM murid
        WHERE id = ? AND ortu_id = ?
    ");
    mysqli_stmt_bind_param($cek, "ii", $murid_id, $user_id);
    mysqli_stmt_execute($cek);
    $hasil = mysqli_stmt_get_result($cek);

    if (mysqli_num_rows($hasil) === 0) {
        $pesan = "Anak tidak ditemukan pada akun ini.";
    } elseif (!in_array($status, $status_valid, true)) {
        $pesan = "Status absensi tidak valid.";
    } else {
        $tanggal = date("Y-m-d");
        $waktu = date("H:i:s");

        // Cegah absensi ganda pada tanggal yang sama
        $cek_absen = mysqli_prepare($conn, "
            SELECT id FROM absensi
            WHERE murid_id = ? AND tanggal = ?
        ");
        mysqli_stmt_bind_param($cek_absen, "is", $murid_id, $tanggal);
        mysqli_stmt_execute($cek_absen);
        $sudah_absen = mysqli_stmt_get_result($cek_absen);

        if (mysqli_num_rows($sudah_absen) > 0) {
            $pesan = "Anak ini sudah melakukan absensi hari ini.";
        } else {
            $simpan = mysqli_prepare($conn, "
                INSERT INTO absensi
                (murid_id, tanggal, waktu, status)
                VALUES (?, ?, ?, ?)
            ");
            mysqli_stmt_bind_param(
                $simpan, "isss",
                $murid_id, $tanggal, $waktu, $status
            );

            if (mysqli_stmt_execute($simpan)) {
                header("Location: absensi.php");
                exit;
            } else {
                $pesan = "Absensi gagal disimpan.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Absensi Anak</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h1>Absensi Anak</h1>

<p style="color:red;">
    <?= htmlspecialchars($pesan) ?>
</p>

<?php if (mysqli_num_rows($murid) > 0) { ?>

<form method="POST">

    <label>Pilih Nama Anak</label><br>
    <select name="murid_id" required>
        <option value="">-- Pilih Anak --</option>

        <?php while ($m = mysqli_fetch_assoc($murid)) { ?>
            <option value="<?= (int)$m['id'] ?>">
                <?= htmlspecialchars($m['nama']) ?>
            </option>
        <?php } ?>

    </select>

    <br><br>

    <label>Status Kehadiran</label><br>
    <select name="status" required>
        <option value="">-- Pilih Status --</option>
        <option value="Hadir">Hadir</option>
        <option value="Izin">Izin</option>
        <option value="Sakit">Sakit</option>
    </select>

    <br><br>

    <button type="submit">Kirim Absensi</button>

</form>

<?php } else { ?>

<p>Belum ada anak yang terhubung dengan akun ini.</p>

<?php } ?>

<br>
<a href="absensi.php">Kembali ke Absensi</a>

</body>
</html>
