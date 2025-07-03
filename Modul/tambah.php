<?php
require_once '../config.php';
session_start();

// Cek akses asisten
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header("Location: ../login.php");
    exit();
}

// Pastikan praktikum_id ada
$praktikum_id = $_GET['praktikum_id'] ?? null;
if (!$praktikum_id) {
    die("ID praktikum tidak valid.");
}

$pageTitle = 'Tambah Modul';
$activePage = 'modul';

// Handle submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    
    // Proses file upload
    $filename = null;
    if ($_FILES['materi']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['materi']['tmp_name'];
        $original_name = basename($_FILES['materi']['name']);
        $ext = pathinfo($original_name, PATHINFO_EXTENSION);
        $allowed = ['pdf', 'docx'];

        if (in_array(strtolower($ext), $allowed)) {
            $filename = uniqid() . '.' . $ext;
            move_uploaded_file($tmp_name, "file/$filename");
        } else {
            echo "<script>alert('Hanya file PDF atau DOCX yang diperbolehkan.');</script>";
        }
    }

    // Simpan ke DB
    $stmt = $conn->prepare("INSERT INTO modul (praktikum_id, judul_modul, file_materi) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $praktikum_id, $judul, $filename);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php?praktikum_id=$praktikum_id");
    exit();
}

// Ambil nama praktikum
$praktikum = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM praktikum WHERE id = $praktikum_id"));

require_once '../asisten/templates/header.php';
?>

<div class="bg-white p-6 rounded-xl shadow-md max-w-xl mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Tambah Modul untuk: <?= htmlspecialchars($praktikum['nama_praktikum']); ?></h2>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Judul Modul</label>
            <input type="text" name="judul" required class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Upload Materi (PDF/DOCX)</label>
            <input type="file" name="materi" accept=".pdf,.docx" class="w-full text-sm">
        </div>

        <div class="flex justify-between">
            <a href="index.php?praktikum_id=<?= $praktikum_id ?>" class="text-gray-500 hover:underline">← Kembali</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow">Simpan</button>
        </div>
    </form>
</div>

<?php require_once '../asisten/templates/footer.php'; ?>
