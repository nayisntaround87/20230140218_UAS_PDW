<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once '../config.php';
session_start();

// Cek akses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header("Location: ../login.php");
    exit();
}

$pageTitle = 'Kelola Pengguna';
$activePage = 'pengguna';

require_once '../asisten/templates/header.php';

// Ambil semua pengguna
$result = mysqli_query($conn, "SELECT * FROM users ORDER BY role ASC, created_at DESC");
?>

<div class="bg-white p-6 rounded-xl shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Akun Pengguna</h2>
        <a href="tambah.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow">+ Tambah Akun</a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">Nama</th>
                    <th class="px-4 py-2 text-left">Email</th>
                    <th class="px-4 py-2 text-left">Role</th>
                    <th class="px-4 py-2 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['nama']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['email']) ?></td>
                    <td class="px-4 py-2 capitalize"><?= $row['role'] ?></td>
                    <td class="px-4 py-2">
                        <a href="edit.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:underline mr-3">Edit</a>
                        <a href="hapus.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus?')" class="text-red-600 hover:underline">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../asisten/templates/footer.php'; ?>
