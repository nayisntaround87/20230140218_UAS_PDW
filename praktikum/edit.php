<?php
require_once '../config.php';
session_start();

// Validasi akses asisten
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header("Location: ../login.php");
    exit();
}

// Ambil ID dan data praktikum
$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID tidak valid.");
}

$query = mysqli_query($conn, "SELECT * FROM praktikum WHERE id = $id");
$data = mysqli_fetch_assoc($query);
if (!$data) {
    die("Data tidak ditemukan.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];

    $update = mysqli_query($conn, "UPDATE praktikum SET nama_praktikum='$nama', deskripsi='$deskripsi' WHERE id=$id");

    if ($update) {
        header("Location: index.php");
        exit();
    } else {
        $error = "Gagal menyimpan perubahan.";
    }
}

$pageTitle = 'Edit Praktikum';
$activePage = 'praktikum';
require_once '../asisten/templates/header.php';
?>

<div class="bg-white p-8 rounded-xl shadow-md max-w-xl mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Mata Praktikum</h2>

    <?php if (isset($error)): ?>
        <p class="text-red-500 mb-4"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" class="space-y-6">
        <div>
            <label for="nama" class="block text-sm font-medium text-gray-700">Nama Praktikum</label>
            <input type="text" id="nama" name="nama" required value="<?= htmlspecialchars($data['nama_praktikum']) ?>"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" rows="4" required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"><?= htmlspecialchars($data['deskripsi']) ?></textarea>
        </div>

        <div class="flex justify-between">
            <a href="index.php" class="inline-block bg-gray-300 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-400">
                ← Kembali
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<?php require_once '../asisten/templates/footer.php'; ?>
