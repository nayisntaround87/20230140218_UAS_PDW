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

$pageTitle = 'Penilaian Laporan';
$activePage = 'laporan';

// Ambil ID laporan dari URL
$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID laporan tidak ditemukan.");
}

// Ambil data laporan lengkap
$query = "SELECT 
    laporan.*, 
    users.nama AS nama_mahasiswa, 
    modul.judul_modul,
    praktikum.nama_praktikum
FROM laporan
JOIN users ON laporan.mahasiswa_id = users.id
JOIN modul ON laporan.modul_id = modul.id
JOIN praktikum ON modul.praktikum_id = praktikum.id
WHERE laporan.id = $id
LIMIT 1";

$data = mysqli_fetch_assoc(mysqli_query($conn, $query));
if (!$data) {
    die("Laporan tidak ditemukan.");
}

// Handle submit nilai
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nilai = intval($_POST['nilai']);
    $komentar = mysqli_real_escape_string($conn, $_POST['komentar']);

    $update = "UPDATE laporan SET nilai = '$nilai', komentar = '$komentar' WHERE id = $id";
    mysqli_query($conn, $update);

    header("Location: index.php");
    exit();
}

require_once '../asisten/templates/header.php';
?>

<div class="bg-white p-6 rounded-xl shadow-md max-w-2xl mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Nilai Laporan Mahasiswa</h2>

    <div class="mb-6">
        <p><strong>Nama Mahasiswa:</strong> <?= htmlspecialchars($data['nama_mahasiswa']) ?></p>
        <p><strong>Praktikum:</strong> <?= htmlspecialchars($data['nama_praktikum']) ?></p>
        <p><strong>Modul:</strong> <?= htmlspecialchars($data['judul_modul']) ?></p>
        <p><strong>Waktu Upload:</strong> <?= date('d M Y H:i', strtotime($data['tanggal_upload'])) ?></p>
        <p><strong>File:</strong>
            <?php if ($data['file_laporan']) : ?>
                <a href="file/<?= htmlspecialchars($data['file_laporan']) ?>" target="_blank" class="text-blue-600 hover:underline">
                    Download Laporan
                </a>
            <?php else : ?>
                <em class="text-gray-400">Tidak ada file</em>
            <?php endif; ?>
        </p>
    </div>

    <form method="POST">
        <div class="mb-4">
            <label class="block font-semibold text-gray-700 mb-2">Nilai (0-100)</label>
            <input type="number" name="nilai" min="0" max="100" value="<?= $data['nilai'] ?? '' ?>" required
                   class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
        </div>

        <div class="mb-4">
            <label class="block font-semibold text-gray-700 mb-2">Komentar (Opsional)</label>
            <textarea name="komentar" rows="4"
                      class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"><?= htmlspecialchars($data['komentar'] ?? '') ?></textarea>
        </div>

        <div class="flex justify-between">
            <a href="index.php" class="text-gray-500 hover:underline">← Kembali</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow">
                Simpan Nilai
            </button>
        </div>
    </form>
</div>

<?php require_once '../asisten/templates/footer.php'; ?>
