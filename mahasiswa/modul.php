<?php
require_once '../config.php';
session_start();

// Cek login mahasiswa
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../login.php");
    exit();
}

$pageTitle = 'Modul Praktikum';
$activePage = '';

require_once 'templates/header_mahasiswa.php';

$praktikum_id = $_GET['id'] ?? null;
if (!$praktikum_id) {
    echo "<p class='text-red-500'>ID praktikum tidak ditemukan.</p>";
    exit();
}

// Ambil data praktikum
$praktikum = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM praktikum WHERE id = $praktikum_id"));
if (!$praktikum) {
    echo "<p class='text-red-500'>Praktikum tidak ditemukan.</p>";
    exit();
}

// Ambil daftar modul
$moduls = mysqli_query($conn, "SELECT * FROM modul WHERE praktikum_id = $praktikum_id ORDER BY created_at DESC");
?>

<div class="bg-white p-6 rounded-xl shadow-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Modul untuk: <?= htmlspecialchars($praktikum['nama_praktikum']) ?></h2>

    <?php if (mysqli_num_rows($moduls) === 0): ?>
        <p class="text-gray-500 italic">Belum ada modul yang tersedia.</p>
    <?php else: ?>
        <ul class="space-y-4">
            <?php while ($modul = mysqli_fetch_assoc($moduls)) : ?>
                <li class="border-b pb-3">
                    <p class="font-semibold text-gray-800"><?= htmlspecialchars($modul['judul_modul']) ?></p>
                    <?php if ($modul['file_materi']) : ?>
                        <a href="../modul/file/<?= $modul['file_materi'] ?>" class="text-blue-600 hover:underline" target="_blank">Download Materi</a>
                    <?php else : ?>
                        <p class="text-gray-400 italic">Belum ada file materi.</p>
                    <?php endif; ?>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php endif; ?>
</div>

<?php require_once 'templates/footer_mahasiswa.php'; ?>
