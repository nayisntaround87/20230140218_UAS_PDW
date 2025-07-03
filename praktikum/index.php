<?php
require_once '../config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session & Akses Asisten
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header("Location: ../login.php");
    exit();
}

// Title & active nav
$pageTitle = 'Kelola Mata Praktikum';
$activePage = 'praktikum';

// Ambil data dari DB
$query = mysqli_query($conn, "SELECT * FROM praktikum");

// Panggil header
require_once '../asisten/templates/header.php';
?>

<div class="bg-white p-6 rounded-xl shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Data Mata Praktikum</h2>
        <a href="tambah.php" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium shadow">
            + Tambah Praktikum
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">No</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Nama Praktikum</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Deskripsi</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                <?php
                if (mysqli_num_rows($query) > 0) {
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($query)) {
                        echo "<tr>
                            <td class='px-6 py-4 text-sm text-gray-700'>{$no}</td>
                            <td class='px-6 py-4 text-sm text-gray-900 font-medium'>" . htmlspecialchars($row['nama_praktikum']) . "</td>
                            <td class='px-6 py-4 text-sm text-gray-700'>" . htmlspecialchars($row['deskripsi']) . "</td>
                            <td class='px-6 py-4 text-sm'>
                                <a href='edit.php?id={$row['id']}' class='text-blue-600 hover:underline mr-3'>Edit</a>
                                <a href='hapus.php?id={$row['id']}' onclick=\"return confirm('Yakin ingin menghapus?')\" class='text-red-600 hover:underline'>Hapus</a>
                            </td>
                        </tr>";
                        $no++;
                    }
                } else {
                    echo "<tr><td colspan='4' class='px-6 py-4 text-gray-500 italic'>Belum ada data praktikum.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../asisten/templates/footer.php'; ?>
