<?php
require_once '../config.php';
session_start();

// 1. Cek akses asisten
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header("Location: ../login.php");
    exit();
}

// 2. Validasi ID
if (!isset($_GET['id'])) {
    die("ID tidak ditemukan.");
}

$id = (int) $_GET['id']; // casting ke integer agar aman

// 3. Jalankan query hapus
$query = "DELETE FROM praktikum WHERE id = $id";
if (mysqli_query($conn, $query)) {
    header("Location: index.php");
    exit();
} else {
    die("Gagal menghapus data: " . mysqli_error($conn));
}
