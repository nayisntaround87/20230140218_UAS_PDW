<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../config.php';
session_start();

// Cek hak akses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header("Location: ../login.php");
    exit();
}

$pageTitle = 'Laporan Masuk';
$activePage = 'laporan';

// Query laporan gabung data user, modul, praktikum
$sql = "SELECT 
    laporan.*, 
    users.nama AS nama_mahasiswa,
    modul.judul_modul,
    praktikum.nama_praktikum
FROM laporan
JOIN users ON laporan.mahasiswa_id = users.id
JOIN modul ON laporan.modul_id = modul.id
JOIN praktikum ON modul.praktikum_id = praktikum.id
ORDER BY laporan.tanggal_upload DESC";

$result = mysqli_query($conn, $sql);

require_once '../asisten/templates/header.php';
?>

<div class="bg-white p-6 rounded-xl shadow-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Daftar Laporan Masuk</h2>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Mahasiswa</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Praktikum</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Modul</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Tanggal Upload</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Nilai</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-800"><?= htmlspecialchars($row['nama_mahasiswa']) ?></td>
                        <td class="px-6 py-4 text-sm text-gray-700"><?= htmlspecialchars($row['nama_praktikum']) ?></td>
                        <td class="px-6 py-4 text-sm text-gray-700"><?= htmlspecialchars($row['judul_modul']) ?></td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?= date('d M Y H:i', strtotime($row['tanggal_upload'])) ?></td>
                        <td class="px-6 py-4 text-sm font-semibold">
                            <?= $row['nilai'] !== null ? $row['nilai'] : '<span class="text-yellow-600 italic">Belum dinilai</span>' ?>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <a href="nilai.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:underline">Lihat / Nilai</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../asisten/templates/footer.php'; ?>
