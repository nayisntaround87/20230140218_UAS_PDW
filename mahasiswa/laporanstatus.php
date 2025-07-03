<?php
require_once '../config.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../login.php");
    exit();
}

$pageTitle = 'Status Laporan';
$activePage = 'laporan';

require_once 'templates/header_mahasiswa.php';

$id = $_SESSION['user_id'];
$result = mysqli_query($conn, "
    SELECT l.*, m.judul_modul
    FROM laporan l
    JOIN modul m ON m.id = l.modul_id
    WHERE l.mahasiswa_id = $id
");
?>

<div class="bg-white p-6 rounded-xl shadow-md">
    <h2 class="text-2xl font-bold mb-4">Status Laporan Terkumpul</h2>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 text-left">Modul</th>
                <th class="px-4 py-2 text-left">File</th>
                <th class="px-4 py-2 text-left">Nilai</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr class="border-b">
                    <td class="px-4 py-2"><?= htmlspecialchars($row['judul_modul']) ?></td>
                    <td class="px-4 py-2">
                        <a href="../laporan/file/<?= $row['file_laporan'] ?>" class="text-blue-600 underline" target="_blank">Lihat</a>
                    </td>
                    <td class="px-4 py-2">
                        <?= $row['nilai'] ?? '<em class="text-gray-500">Belum Dinilai</em>' ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once 'templates/footer_mahasiswa.php'; ?>
