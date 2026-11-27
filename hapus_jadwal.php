
<?php
session_start();
require_once "koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: jadwal.php");
    exit;
}

$id = (int)($_POST['id'] ?? 0);

$stmt = mysqli_prepare($conn, "DELETE FROM jadwal WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

header("Location: jadwal.php");
exit;
?>
