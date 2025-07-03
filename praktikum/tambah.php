<?php
require_once '../config.php';
session_start();

// Cek hak akses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['submit'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    $query = "INSERT INTO praktikum (nama_praktikum, deskripsi) VALUES ('$nama', '$deskripsi')";
    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
        exit();
    } else {
        $error = "Gagal menyimpan: " . mysqli_error($conn);
    }
}

$pageTitle = 'Tambah Praktikum';
$activePage = 'praktikum';
require_once '../asisten/templates/header.php';
?>

<div class="bg-white p-8 rounded-xl shadow-md max-w-xl mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Tambah Mata Praktikum</h2>

    <?php if (isset($error)) : ?>
        <p class="text-red-600 mb-4"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" class="space-y-6">
        <div>
            <label class="block text-sm font-medium text-gray-700">Nama Praktikum</label>
            <input type="text" name="nama" required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea name="deskripsi" rows="4" required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
        </div>

        <div class="flex justify-between">
            <a href="index.php" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-400">← Kembali</a>
            <button type="submit" name="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                Simpan
            </button>
        </div>
    </form>
</div>

<?php require_once '../asisten/templates/footer.php'; ?>
