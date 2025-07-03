<?php
require_once '../config.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../login.php");
    exit();
}

$praktikum_id = $_GET['id'] ?? null;
$mahasiswa_id = $_SESSION['user_id'];

if (!$praktikum_id) {
    die("ID praktikum tidak valid.");
}

// Cek apakah sudah daftar
$cek = mysqli_query($conn, "SELECT * FROM peserta WHERE mahasiswa_id=$mahasiswa_id AND praktikum_id=$praktikum_id");

if (mysqli_num_rows($cek) === 0) {
    mysqli_query($conn, "INSERT INTO peserta (mahasiswa_id, praktikum_id) VALUES ($mahasiswa_id, $praktikum_id)");
}

header("Location: my_courses.php");
exit();
