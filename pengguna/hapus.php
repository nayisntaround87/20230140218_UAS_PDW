<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../config.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM users WHERE id = $id");
header("Location: index.php");
exit();
