<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('guru');

$pesan = "";

$setting_check = mysqli_query($koneksi, "SELECT * FROM game_settings WHERE id_setting=1");
if ($setting_check && mysqli_num_rows($setting_check) == 0) {
    mysqli_query($koneksi, "INSERT INTO game_settings (id_setting, max_questions, lives, score_per_correct) VALUES (1, 10, 3, 20)");
}

if (isset($_POST['update_setting'])) {
    $max_questions = intval($_POST['max_questions']);
    $lives = intval($_POST['lives']);
    $score_per_correct = intval($_POST['score_per_correct']);

    if ($max_questions < 1) $max_questions = 1;
    if ($lives < 1) $lives = 1;
    if ($score_per_correct < 1) $score_per_correct = 1;

    mysqli_query($koneksi, "UPDATE game_settings SET 
        max_questions=$max_questions,
        lives=$lives,
        score_per_correct=$score_per_correct
        WHERE id_setting=1
    ");

    header("Location: kelola_game.php?status=setting");
    exit;
}

if (isset($_POST['tambah'])) {
    $mode = mysqli_real_escape_string($koneksi, $_POST['mode_game']);
    $pertanyaan = mysqli_real_escape_string($koneksi, $_POST['pertanyaan']);
    $kode = mysqli_real_escape_string($koneksi, $_POST['kode']);
    $a = mysqli_real_escape_string($koneksi, $_POST['opsi_a']);
    $b = mysqli_real_escape_string($koneksi, $_POST['opsi_b']);
    $c = mysqli_real_escape_string($koneksi, $_POST['opsi_c']);
    $d = mysqli_real_escape_string($koneksi, $_POST['opsi_d']);
    $jawaban = mysqli_real_escape_string($koneksi, $_POST['jawaban_benar']);
    $pembahasan = mysqli_real_escape_string($koneksi, $_POST['pembahasan']);
    $status = mysqli_real_escape_string($koneksi, $_POST['status']);

    mysqli_query($koneksi, "INSERT INTO game_questions
        (mode_game, pertanyaan, kode, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar, pembahasan, status)
        VALUES
        ('$mode', '$pertanyaan', '$kode', '$a', '$b', '$c', '$d', '$jawaban', '$pembahasan', '$status')
    ");

    header("Location: kelola_game.php?status=ditambah");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    mysqli_query($koneksi, "DELETE FROM game_questions WHERE id_question=$id");
    header("Location: kelola_game.php?status=dihapus");
    exit;
}

if (isset($_GET['toggle'])) {
    $id = intval($_GET['toggle']);
    $row = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT status FROM game_questions WHERE id_question=$id"));
    if ($row) {
        $new_status = $row['status'] == 'aktif' ? 'nonaktif' : 'aktif';
        mysqli_query($koneksi, "UPDATE game_questions SET status='$new_status' WHERE id_question=$id");
    }
    header("Location: kelola_game.php?status=toggle");
    exit;
}

if (isset($_GET['status'])) {
    if ($_GET['status'] == 'ditambah') $pesan = "Pertanyaan game berhasil ditambahkan.";
    if ($_GET['status'] == 'diupdate') $pesan = "Pertanyaan game berhasil diperbarui.";
    if ($_GET['status'] == 'dihapus') $pesan = "Pertanyaan game berhasil dihapus.";
    if ($_GET['status'] == 'toggle') $pesan = "Status pertanyaan game berhasil diubah.";
    if ($_GET['status'] == 'setting') $pesan = "Setting game berhasil diperbarui.";
}

$setting = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM game_settings WHERE id_setting=1"));

$filter_mode = mysqli_real_escape_string($koneksi, $_GET['mode'] ?? '');
$keyword = mysqli_real_escape_string($koneksi, $_GET['keyword'] ?? '');

$where = "WHERE 1=1";
if ($filter_mode != "") {
    $where .= " AND mode_game='$filter_mode'";
}
if ($keyword != "") {
    $where .= " AND (pertanyaan LIKE '%$keyword%' OR pembahasan LIKE '%$keyword%')";
}

$questions = mysqli_query($koneksi, "SELECT * FROM game_questions $where ORDER BY mode_game ASC, id_question DESC");

$count_all = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM game_questions"))['total'];
$count_active = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM game_questions WHERE status='aktif'"))['total'];
$count_html = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM game_questions WHERE mode_game='HTML'"))['total'];
$count_css = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM game_questions WHERE mode_game='CSS'"))['total'];
$count_js = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM game_questions WHERE mode_game='JavaScript'"))['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Game</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Kelola Game Coding</h1>
                <p>Guru dapat mengatur soal, mode, pembahasan, status aktif, jumlah soal, nyawa, dan skor game.</p>
            </div>
        </div>

        <?php if ($pesan): ?>
            <div class="alert alert-success"><?= $pesan; ?></div>
        <?php endif; ?>

        <section class="stats-grid">
            <div class="stat-card"><span>Total Soal</span><h2><?= $count_all; ?></h2></div>
            <div class="stat-card"><span>Soal Aktif</span><h2><?= $count_active; ?></h2></div>
            <div class="stat-card"><span>HTML</span><h2><?= $count_html; ?></h2></div>
            <div class="stat-card"><span>CSS</span><h2><?= $count_css; ?></h2></div>
        </section>

        <section class="game-admin-grid">
            <div>
                <form method="POST" class="card" style="margin-bottom:18px;">
                    <h3>Setting Game</h3>
                    <p>Setting ini berlaku untuk semua siswa saat memainkan game.</p>
                    <br>

                    <div class="setting-mini-grid">
                        <div class="form-group">
                            <label>Jumlah Soal</label>
                            <input type="number" name="max_questions" class="form-control" value="<?= $setting['max_questions'] ?? 10; ?>" min="1" required>
                        </div>

                        <div class="form-group">
                            <label>Nyawa</label>
                            <input type="number" name="lives" class="form-control" value="<?= $setting['lives'] ?? 3; ?>" min="1" required>
                        </div>

                        <div class="form-group">
                            <label>Skor per Benar</label>
                            <input type="number" name="score_per_correct" class="form-control" value="<?= $setting['score_per_correct'] ?? 20; ?>" min="1" required>
                        </div>
                    </div>

                    <button type="submit" name="update_setting" class="btn btn-primary">Simpan Setting</button>
                </form>

                <form method="POST" class="card">
                    <h3>Tambah Pertanyaan Game</h3>
                    <p>Pertanyaan ini akan muncul pada game siswa sesuai mode yang dipilih.</p>
                    <br>

                    <div class="form-group">
                        <label>Mode Game</label>
                        <select name="mode_game" class="form-control" required>
                            <option value="HTML">HTML</option>
                            <option value="CSS">CSS</option>
                            <option value="JavaScript">JavaScript</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Pertanyaan</label>
                        <textarea name="pertanyaan" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="form-group">
                        <label>Kode Pendukung</label>
                        <textarea name="kode" class="form-control" rows="4" placeholder="Opsional. Contoh: h1 { color: blue; }"></textarea>
                    </div>

                    <div class="form-group"><label>Opsi A</label><input type="text" name="opsi_a" class="form-control" required></div>
                    <div class="form-group"><label>Opsi B</label><input type="text" name="opsi_b" class="form-control" required></div>
                    <div class="form-group"><label>Opsi C</label><input type="text" name="opsi_c" class="form-control" required></div>
                    <div class="form-group"><label>Opsi D</label><input type="text" name="opsi_d" class="form-control" required></div>

                    <div class="form-group">
                        <label>Jawaban Benar</label>
                        <select name="jawaban_benar" class="form-control" required>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Pembahasan</label>
                        <textarea name="pembahasan" class="form-control" rows="3" placeholder="Penjelasan setelah siswa menjawab."></textarea>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>

                    <button type="submit" name="tambah" class="btn btn-primary">Simpan Pertanyaan</button>
                </form>
            </div>

            <div>
                <div class="filter-card">
                    <form method="GET" class="filter-form">
                        <div class="form-group">
                            <label>Cari Pertanyaan</label>
                            <input type="text" name="keyword" class="form-control" value="<?= htmlspecialchars($keyword); ?>" placeholder="Ketik keyword soal...">
                        </div>

                        <div class="form-group">
                            <label>Mode</label>
                            <select name="mode" class="form-control">
                                <option value="">Semua Mode</option>
                                <option value="HTML" <?= $filter_mode == 'HTML' ? 'selected' : ''; ?>>HTML</option>
                                <option value="CSS" <?= $filter_mode == 'CSS' ? 'selected' : ''; ?>>CSS</option>
                                <option value="JavaScript" <?= $filter_mode == 'JavaScript' ? 'selected' : ''; ?>>JavaScript</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="kelola_game.php" class="btn btn-outline">Reset</a>
                    </form>
                </div>

                <div class="module-list">
                    <?php if (!$questions || mysqli_num_rows($questions) == 0): ?>
                        <div class="empty-state">Belum ada pertanyaan game.</div>
                    <?php endif; ?>

                    <?php if ($questions): ?>
                        <?php while($row = mysqli_fetch_assoc($questions)): ?>
                            <?php
                                $options = [
                                    'A' => $row['opsi_a'],
                                    'B' => $row['opsi_b'],
                                    'C' => $row['opsi_c'],
                                    'D' => $row['opsi_d']
                                ];
                            ?>
                            <div class="question-admin-card <?= $row['status'] == 'nonaktif' ? 'inactive' : ''; ?>">
                                <div style="display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap;">
                                    <div>
                                        <span class="badge badge-primary"><?= $row['mode_game']; ?></span>
                                        <span class="badge <?= $row['status'] == 'aktif' ? 'badge-success' : 'badge-warning'; ?>"><?= $row['status']; ?></span>
                                    </div>
                                    <div class="action-row">
                                        <a href="edit_game_question.php?id=<?= $row['id_question']; ?>" class="btn btn-outline btn-sm">Edit</a>
                                        <a href="?toggle=<?= $row['id_question']; ?>" class="btn btn-outline btn-sm"><?= $row['status'] == 'aktif' ? 'Nonaktifkan' : 'Aktifkan'; ?></a>
                                        <a href="?hapus=<?= $row['id_question']; ?>" onclick="return confirm('Hapus pertanyaan game ini?')" class="btn btn-danger btn-sm">Hapus</a>
                                    </div>
                                </div>

                                <h3 style="margin-top:14px;"><?= htmlspecialchars($row['pertanyaan']); ?></h3>

                                <?php if ($row['kode']): ?>
                                    <div class="question-code-box"><?= htmlspecialchars($row['kode']); ?></div>
                                <?php endif; ?>

                                <div class="question-options-mini">
                                    <?php foreach($options as $key => $value): ?>
                                        <span class="<?= $row['jawaban_benar'] == $key ? 'right' : ''; ?>">
                                            <?= $key; ?>. <?= htmlspecialchars($value); ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>

                                <?php if ($row['pembahasan']): ?>
                                    <p style="margin-top:12px;"><strong>Pembahasan:</strong> <?= htmlspecialchars($row['pembahasan']); ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>
</div>
</body>
</html>
