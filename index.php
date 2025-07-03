<?php
session_start();

// Jika user sudah login
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'asisten') {
        header("Location: asisten/dashboard.php");
        exit();
    } elseif ($_SESSION['role'] === 'mahasiswa') {
        header("Location: mahasiswa/dashboard.php");
        exit();
    }
}

// Jika belum login, arahkan ke halaman login
header("Location: login.php");
exit();
