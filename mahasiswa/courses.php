<?php
require_once '../config.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../login.php");
    exit();
}

$pageTitle = 'Cari Praktikum';
$activePage = 'courses';

require_once 'templates/header_mahasiswa.php';

$keyword = $_GET['search'] ?? '';
$escaped = mysqli_real_escape_string($conn, $keyword);

$query = "SELECT * FROM praktikum";
if (!empty($escaped)) {
    $query .= " WHERE nama_praktikum LIKE '%$escaped%' OR deskripsi LIKE '%$escaped%'";
}

$result = mysqli_query($conn, $query);
?>

<div class="bg-white p-6 rounded-xl shadow-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Cari & Daftar Praktikum</h2>

    <form method="GET" class="mb-4">
        <input type="text" name="search" value="<?= htmlspecialchars($keyword) ?>" placeholder="Cari praktikum..."
            class="border rounded-md px-4 py-2 w-full md:w-1/3">
    </form>

    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 text-left">Nama Praktikum</th>
                <th class="px-4 py-2 text-left">Deskripsi</th>
                <th class="px-4 py-2 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) === 0): ?>
                <tr><td colspan="3" class="px-4 py-2 text-gray-500 italic">Tidak ditemukan.</td></tr>
            <?php else: ?>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr class="border-b">
                        <td class="px-4 py-2"><?= htmlspecialchars($row['nama_praktikum']) ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($row['deskripsi']) ?></td>
                        <td class="px-4 py-2">
                            <a href="daftar.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:underline">Daftar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once 'templates/footer_mahasiswa.php'; ?>
