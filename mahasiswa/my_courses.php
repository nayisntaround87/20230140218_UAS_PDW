<?php
require_once '../config.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../login.php");
    exit();
}

$pageTitle = 'Praktikum Saya';
$activePage = 'my_courses';

require_once 'templates/header_mahasiswa.php';

$mahasiswa_id = $_SESSION['user_id'];
$result = mysqli_query($conn, "
    SELECT p.* FROM praktikum p
    JOIN peserta ps ON ps.praktikum_id = p.id
    WHERE ps.mahasiswa_id = $mahasiswa_id
");
?>

<div class="bg-white p-6 rounded-xl shadow-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Praktikum yang Kamu Ikuti</h2>

    <?php if (mysqli_num_rows($result) === 0): ?>
        <p class="text-gray-500 italic">Kamu belum mendaftar praktikum.</p>
    <?php else: ?>
        <ul class="space-y-3">
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <li class="flex justify-between items-center border-b pb-2">
                    <div>
                        <p class="font-semibold text-gray-800"><?= htmlspecialchars($row['nama_praktikum']) ?></p>
                        <p class="text-sm text-gray-500"><?= htmlspecialchars($row['deskripsi']) ?></p>
                    </div>
                    <a href="modul.php?id=<?= $row['id'] ?>" class="bg-blue-600 text-white px-3 py-1 rounded-md text-sm">Lihat Modul</a>
                    <a href="laporankumpul.php?praktikum_id=<?= $row['id'] ?>" class="bg-yellow-500 text-white px-3 py-1 rounded-md text-sm">Kumpulkan Laporan</a>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php endif; ?>
</div>

<?php require_once 'templates/footer_mahasiswa.php'; ?>
