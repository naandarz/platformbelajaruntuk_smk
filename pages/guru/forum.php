<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('guru');

$id_user = $_SESSION['user']['id_user'];
$role = $_SESSION['user']['role'];
$pesan = "";

if (isset($_POST['buat_topik'])) {
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $isi = mysqli_real_escape_string($koneksi, $_POST['isi']);

    mysqli_query($koneksi, "INSERT INTO forum_topics (id_user, role, judul, isi)
    VALUES ($id_user, '$role', '$judul', '$isi')");

    header("Location: forum.php?status=ditambah");
    exit;
}

if (isset($_GET['status']) && $_GET['status'] == 'ditambah') {
    $pesan = "Topik diskusi berhasil dibuat.";
}

$keyword = mysqli_real_escape_string($koneksi, $_GET['keyword'] ?? '');
$where = "";
if ($keyword != "") {
    $where = "WHERE forum_topics.judul LIKE '%$keyword%' OR forum_topics.isi LIKE '%$keyword%'";
}

$topics = mysqli_query($koneksi, "
    SELECT forum_topics.*, users.nama, users.kelas,
        COUNT(forum_replies.id_reply) AS total_reply
    FROM forum_topics
    JOIN users ON forum_topics.id_user = users.id_user
    LEFT JOIN forum_replies ON forum_topics.id_topic = forum_replies.id_topic
    $where
    GROUP BY forum_topics.id_topic
    ORDER BY forum_topics.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Forum Diskusi</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
<?php include "../../includes/pwa_head.php"; ?>
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Forum Diskusi</h1>
                <p>Tempat bertanya dan berdiskusi tentang HTML, CSS, JavaScript, tugas, kuis, dan game coding.</p>
            </div>
        </div>

        <?php if ($pesan): ?>
            <div class="alert alert-success"><?= $pesan; ?></div>
        <?php endif; ?>

        <section class="content-grid">
            <div>
                <form method="POST" class="card">
                    <h3>Buat Topik Baru</h3>
                    <br>
                    <div class="form-group">
                        <label>Judul Topik</label>
                        <input type="text" name="judul" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Isi Diskusi</label>
                        <textarea name="isi" class="form-control" rows="5" required></textarea>
                    </div>
                    <button type="submit" name="buat_topik" class="btn btn-primary">Posting Topik</button>
                </form>
            </div>

            <div>
                <div class="search-bar">
                    <form method="GET">
                        <input type="text" name="keyword" class="form-control" value="<?= htmlspecialchars($keyword); ?>" placeholder="Cari topik diskusi...">
                        <button type="submit" class="btn btn-primary">Cari</button>
                        <a href="forum.php" class="btn btn-outline">Reset</a>
                    </form>
                </div>

                <?php if (!$topics || mysqli_num_rows($topics) == 0): ?>
                    <div class="empty-state">Belum ada topik diskusi.</div>
                <?php endif; ?>

                <?php if ($topics): ?>
                    <?php while($row = mysqli_fetch_assoc($topics)): ?>
                        <div class="forum-card">
                            <span class="badge <?= $row['role'] == 'guru' ? 'badge-success' : 'badge-primary'; ?>"><?= $row['role']; ?></span>
                            <h3 class="forum-topic-title"><?= htmlspecialchars($row['judul']); ?></h3>
                            <p><?= nl2br(htmlspecialchars(substr($row['isi'], 0, 180))); ?>...</p>
                            <div class="forum-meta">
                                Oleh <?= htmlspecialchars($row['nama']); ?> | <?= $row['created_at']; ?> | <?= $row['total_reply']; ?> balasan
                            </div>
                            <br>
                            <a href="forum_detail.php?id=<?= $row['id_topic']; ?>" class="btn btn-primary btn-sm">Buka Diskusi</a>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>
</div>
</body>
</html>
