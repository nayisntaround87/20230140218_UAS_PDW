<?php
require_once '../config.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../login.php");
    exit();
}

$pageTitle = 'Kumpul Laporan';
$activePage = 'laporan';

require_once 'templates/header_mahasiswa.php';

$mahasiswa_id = $_SESSION['user_id'];

// Ambil semua praktikum yang diikuti mahasiswa
$praktikum_q = mysqli_query($conn, "
    SELECT p.* FROM praktikum p
    JOIN peserta ps ON ps.praktikum_id = p.id
    WHERE ps.mahasiswa_id = $mahasiswa_id
");

// Ambil semua laporan yang sudah dikumpulkan
$laporan_cek = mysqli_query($conn, "SELECT modul_id FROM laporan WHERE mahasiswa_id = $mahasiswa_id");
$laporan_dikirim = [];
while ($row = mysqli_fetch_assoc($laporan_cek)) {
    $laporan_dikirim[] = $row['modul_id'];
}
?>

<div class="bg-white p-6 rounded-xl shadow-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Kumpulkan Laporan Praktikum</h2>

    <?php if (mysqli_num_rows($praktikum_q) === 0): ?>
        <p class="text-gray-500 italic">Kamu belum mendaftar praktikum apa pun.</p>
    <?php endif; ?>

    <?php while ($praktikum = mysqli_fetch_assoc($praktikum_q)) : ?>
        <div class="mt-6 mb-2 border-b pb-2">
            <h3 class="text-lg font-semibold text-blue-600"><?= htmlspecialchars($praktikum['nama_praktikum']) ?></h3>
        </div>

        <?php
        $moduls = mysqli_query($conn, "SELECT * FROM modul WHERE praktikum_id = {$praktikum['id']}");
        if (mysqli_num_rows($moduls) === 0): ?>
            <p class="text-sm italic text-gray-400 mb-4">Belum ada modul tersedia.</p>
        <?php else: ?>
            <ul class="space-y-4 mb-6">
                <?php while ($modul = mysqli_fetch_assoc($moduls)) : ?>
                    <li>
                        <p class="font-medium"><?= htmlspecialchars($modul['judul_modul']) ?></p>
                        <?php if (in_array($modul['id'], $laporan_dikirim)): ?>
                            <p class="text-green-600 text-sm">✅ Sudah dikumpulkan</p>
                        <?php else: ?>
                            <form method="POST" enctype="multipart/form-data" class="mt-2 space-y-2">
                                <input type="hidden" name="modul_id" value="<?= $modul['id'] ?>">
                                <input type="file" name="file" required class="border rounded px-2 py-1">
                                <button type="submit" name="upload" class="bg-blue-600 text-white px-3 py-1 rounded-md text-sm">Upload</button>
                            </form>
                        <?php endif; ?>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php endif; ?>
    <?php endwhile; ?>
</div>

<?php
// Proses upload
if (isset($_POST['upload'])) {
    $modul_id = $_POST['modul_id'];
    $file = $_FILES['file'];
    if ($file['error'] === 0) {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $ext;
        move_uploaded_file($file['tmp_name'], "../laporan/file/$filename");
        mysqli_query($conn, "INSERT INTO laporan (mahasiswa_id, modul_id, file_laporan) VALUES ($mahasiswa_id, $modul_id, '$filename')");
        echo "<script>alert('Berhasil mengunggah laporan!'); location.href='laporankumpul.php';</script>";
    } else {
        echo "<script>alert('Gagal upload file.');</script>";
    }
}
?>

<?php require_once 'templates/footer_mahasiswa.php'; ?>
