<?php
require_once '../config.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID modul tidak valid.");
}

$query = mysqli_query($conn, "SELECT * FROM modul WHERE id = $id");
$data = mysqli_fetch_assoc($query);
if (!$data) {
    die("Data tidak ditemukan.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $praktikum_id = $data['praktikum_id'];

    // Cek apakah upload file dilakukan
    $file = $data['file_materi'];
    if (!empty($_FILES['file']['name'])) {
        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['file']['tmp_name'], "file/$filename");
        $file = $filename;
    }

    $update = mysqli_query($conn, "UPDATE modul SET judul_modul='$judul', file_materi='$file' WHERE id=$id");

    if ($update) {
        header("Location: index.php?praktikum_id=$praktikum_id");
        exit();
    } else {
        $error = "Gagal memperbarui modul.";
    }
}

$pageTitle = 'Edit Modul';
$activePage = 'modul';
require_once '../asisten/templates/header.php';
?>

<div class="bg-white p-8 rounded-xl shadow-md max-w-xl mx-auto">
    <h2 class="text-2xl font-bold mb-6">Edit Modul</h2>

    <?php if (isset($error)): ?>
        <p class="text-red-500 mb-4"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="space-y-6">
        <div>
            <label for="judul" class="block text-sm font-medium text-gray-700">Judul Modul</label>
            <input type="text" name="judul" id="judul" value="<?= htmlspecialchars($data['judul_modul']) ?>" required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">File Materi</label>
            <?php if ($data['file_materi']): ?>
                <p class="text-sm text-blue-600 mb-2">File: <?= htmlspecialchars($data['file_materi']) ?></p>
            <?php endif; ?>
            <input type="file" name="file"
                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-blue-600 file:text-white hover:file:bg-blue-700">
        </div>

        <div class="flex justify-between">
            <a href="index.php?praktikum_id=<?= $data['praktikum_id'] ?>" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-400">← Kembali</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Simpan</button>
        </div>
    </form>
</div>

<?php require_once '../asisten/templates/footer.php'; ?>
