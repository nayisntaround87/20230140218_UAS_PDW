<?php
session_start();

// Hapus semua variabel session
$_SESSION = array();

// Hancurkan session
session_destroy();

// Redirect ke halaman login (pastikan path ini sesuai struktur folder kamu)
header("Location: /SistemPengumpulanTugas/login.php");
exit();
?>
