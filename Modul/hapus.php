<?php
require_once '../config.php';
session_start();

// Cek akses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'] ?? null;
$praktikum_id = $_GET['praktikum_id'] ?? null;

if (!$id || !$praktikum_id) {
    die("Parameter tidak lengkap.");
}

// Ambil nama file
$result = mysqli_query($conn, "SELECT file_materi FROM modul WHERE id = $id");
if ($row = mysqli_fetch_assoc($result)) {
    $file = $row['file_materi'];
    if ($file && file_exists("file/$file")) {
        unlink("file/$file"); // hapus file PDF
    }
}

// Hapus modul dari database
mysqli_query($conn, "DELETE FROM modul WHERE id = $id");

// Redirect
header("Location: index.php?praktikum_id=$praktikum_id");
exit();
