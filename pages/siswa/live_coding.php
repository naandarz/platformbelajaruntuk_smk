<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('siswa');

$id_user = $_SESSION['user']['id_user'];
$id = intval($_GET['id'] ?? 0);
$pesan = "";

$defaultCode = "<!DOCTYPE html>\n<html>\n<head>\n  <title>Latihan HTML</title>\n</head>\n<body>\n  <h1>Halo Dunia!</h1>\n  <p>Saya sedang belajar HTML.</p>\n</body>\n</html>";

$judul_materi = "Latihan Bebas";

if ($id > 0) {
    $q = mysqli_query($koneksi, "SELECT judul_materi, contoh_kode FROM materi WHERE id_materi=$id");
    $m = mysqli_fetch_assoc($q);
    if ($m) {
        $judul_materi = $m['judul_materi'];
        if ($m['contoh_kode']) {
            $defaultCode = $m['contoh_kode'];
        }
    }
}

if (isset($_POST['simpan_latihan'])) {
    $kode_html = mysqli_real_escape_string($koneksi, $_POST['kode_html']);
    $catatan = mysqli_real_escape_string($koneksi, $_POST['catatan']);

    if ($id > 0) {
        mysqli_query($koneksi, "INSERT INTO latihan_kode (id_user, id_materi, kode_html, catatan)
        VALUES ($id_user, $id, '$kode_html', '$catatan')");
        $pesan = "Latihan coding berhasil disimpan ke riwayat belajar.";
        $defaultCode = $_POST['kode_html'];
    } else {
        $pesan = "Pilih materi terlebih dahulu dari halaman Materi HTML agar latihan dapat disimpan.";
        $defaultCode = $_POST['kode_html'];
    }
}

$materi_list = mysqli_query($koneksi, "SELECT id_materi, judul_materi FROM materi ORDER BY urutan ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Live Coding Web</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
<?php include "../../includes/pwa_head.php"; ?>
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Live Coding Web</h1>
                <p>Materi aktif: <?= htmlspecialchars($judul_materi); ?>. Tulis kode HTML, CSS, dan JavaScript lalu lihat hasilnya secara langsung.</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-outline" onclick='resetEditor(<?= json_encode($defaultCode); ?>)'>Reset Kode</button>
            </div>
        </div>

        <?php if ($pesan): ?>
            <div class="alert <?= strpos($pesan, 'berhasil') !== false ? 'alert-success' : 'alert-warning'; ?>"><?= $pesan; ?></div>
        <?php endif; ?>

        <div class="card" style="margin-bottom:18px;">
            <h3>Pilih Materi Latihan</h3>
            <p>Jika ingin hasil coding web tersimpan di riwayat, buka live coding berdasarkan materi tertentu.</p>
            <br>
            <form method="GET" class="action-row">
                <select name="id" class="form-control" style="max-width:360px;">
                    <option value="0">Latihan Bebas</option>
                    <?php while($row = mysqli_fetch_assoc($materi_list)): ?>
                        <option value="<?= $row['id_materi']; ?>" <?= $id == $row['id_materi'] ? 'selected' : ''; ?>>
                            <?= $row['judul_materi']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <button class="btn btn-primary" type="submit">Gunakan Materi</button>
            </form>
        </div>

        <form method="POST">
            <section class="coding-layout">
                <div>
                    <h3 style="margin-bottom:12px;">Editor Kode</h3>
                    <textarea id="htmlEditor" name="kode_html" class="editor" oninput="updatePreview()"><?= htmlspecialchars($defaultCode); ?></textarea>
                </div>

                <div>
                    <h3 style="margin-bottom:12px;">Preview Hasil</h3>
                    <iframe id="previewFrame" class="preview"></iframe>
                </div>
            </section>

            <div class="card" style="margin-top:18px;">
                <h3>Simpan Latihan</h3>
                <p>Simpan hasil coding agar guru dapat melihat latihan yang sudah kamu kerjakan.</p>
                <br>
                <div class="form-group">
                    <label>Catatan Latihan</label>
                    <input type="text" name="catatan" class="form-control" placeholder="Contoh: Latihan membuat heading dan paragraf">
                </div>
                <button type="submit" name="simpan_latihan" class="btn btn-primary">Simpan Latihan Coding Web</button>
            </div>
        </form>
    </main>
</div>

<script src="../../assets/js/main.js"></script>
</body>
</html>
