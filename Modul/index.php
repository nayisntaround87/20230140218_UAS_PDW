<?php
require_once '../config.php';
session_start();

// Cek akses asisten
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header("Location: ../login.php");
    exit();
}

$pageTitle = 'Manajemen Modul';
$activePage = 'modul';

require_once '../asisten/templates/header.php';

// Cek apakah praktikum_id disediakan
$praktikum_id = $_GET['praktikum_id'] ?? null;

if (!$praktikum_id):
    // Belum pilih praktikum: tampilkan daftar praktikum
    $result = mysqli_query($conn, "SELECT * FROM praktikum ORDER BY nama_praktikum ASC");
    ?>
    <div class="bg-white p-6 rounded-xl shadow-md">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Pilih Praktikum</h2>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <ul class="space-y-3">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <li class="flex justify-between items-center bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($row['nama_praktikum']) ?></h3>
                            <p class="text-sm text-gray-600"><?= htmlspecialchars($row['deskripsi']) ?></p>
                        </div>
                        <a href="index.php?praktikum_id=<?= $row['id'] ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Lihat Modul
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p class="text-gray-500 italic">Belum ada data praktikum.</p>
        <?php endif; ?>
    </div>

<?php
else:
    // Sudah pilih praktikum: tampilkan modul-modulnya
    $praktikum = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM praktikum WHERE id = $praktikum_id"));
    $modul_query = mysqli_query($conn, "SELECT * FROM modul WHERE praktikum_id = $praktikum_id ORDER BY created_at DESC");
    ?>
    <div class="bg-white p-6 rounded-xl shadow-md">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Modul: <?= htmlspecialchars($praktikum['nama_praktikum']); ?></h2>
            <a href="tambah.php?praktikum_id=<?= $praktikum_id ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow">
                + Tambah Modul
            </a>
        </div>

        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Judul Modul</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">File Materi</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                <?php if (mysqli_num_rows($modul_query) > 0): ?>
                    <?php while ($modul = mysqli_fetch_assoc($modul_query)): ?>
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-800 font-medium"><?= htmlspecialchars($modul['judul_modul']) ?></td>
                            <td class="px-6 py-4 text-sm text-blue-600">
                                <?php if ($modul['file_materi']): ?>
                                    <a href="file/<?= htmlspecialchars($modul['file_materi']) ?>" target="_blank">Download</a>
                                <?php else: ?>
                                    <em class="text-gray-400">Tidak ada file</em>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="edit.php?id=<?= $modul['id'] ?>" class="text-blue-600 hover:underline mr-3">Edit</a>
                                <a href="hapus.php?id=<?= $modul['id'] ?>&praktikum_id=<?= $praktikum_id ?>" onclick="return confirm('Hapus modul ini?')" class="text-red-600 hover:underline">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-gray-500 italic">Belum ada modul untuk praktikum ini.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once '../asisten/templates/footer.php'; ?>
