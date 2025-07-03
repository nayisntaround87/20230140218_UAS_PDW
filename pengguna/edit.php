<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../config.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id = $id"));

if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    mysqli_query($conn, "UPDATE users SET nama='$nama', email='$email', role='$role' WHERE id=$id");
    header("Location: index.php");
    exit();
}

$pageTitle = 'Edit Pengguna';
$activePage = 'pengguna';
require_once '../asisten/templates/header.php';
?>

<div class="bg-white p-8 rounded-xl shadow-md max-w-xl mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Pengguna</h2>

    <form method="POST" class="space-y-6">
        <div>
            <label class="block text-sm font-medium text-gray-700">Nama</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($user['nama']) ?>" required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Role</label>
            <select name="role" required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="mahasiswa" <?= $user['role'] === 'mahasiswa' ? 'selected' : '' ?>>Mahasiswa</option>
                <option value="asisten" <?= $user['role'] === 'asisten' ? 'selected' : '' ?>>Asisten</option>
            </select>
        </div>

        <div class="flex justify-between">
            <a href="index.php" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-400">← Kembali</a>
            <button type="submit" name="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<?php require_once '../asisten/templates/footer.php'; ?>
