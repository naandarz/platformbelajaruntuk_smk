<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('siswa');

$id_user = $_SESSION['user']['id_user'];
$role = $_SESSION['user']['role'];
$id_topic = intval($_GET['id'] ?? 0);

$topic = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT forum_topics.*, users.nama, users.kelas
    FROM forum_topics
    JOIN users ON forum_topics.id_user = users.id_user
    WHERE forum_topics.id_topic=$id_topic
"));

if (!$topic) {
    die("Topik forum tidak ditemukan.");
}

if (isset($_POST['balas'])) {
    $isi = mysqli_real_escape_string($koneksi, $_POST['isi']);
    mysqli_query($koneksi, "INSERT INTO forum_replies (id_topic, id_user, role, isi)
    VALUES ($id_topic, $id_user, '$role', '$isi')");

    header("Location: forum_detail.php?id=$id_topic&status=reply");
    exit;
}

$replies = mysqli_query($koneksi, "
    SELECT forum_replies.*, users.nama, users.kelas
    FROM forum_replies
    JOIN users ON forum_replies.id_user = users.id_user
    WHERE forum_replies.id_topic=$id_topic
    ORDER BY forum_replies.created_at ASC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Forum</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Detail Diskusi</h1>
                <p>Baca dan balas diskusi pada topik ini.</p>
            </div>
            <a href="forum.php" class="btn btn-outline">Kembali Forum</a>
        </div>

        <?php if (isset($_GET['status'])): ?>
            <div class="alert alert-success">Balasan berhasil ditambahkan.</div>
        <?php endif; ?>

        <div class="forum-card">
            <span class="badge <?= $topic['role'] == 'guru' ? 'badge-success' : 'badge-primary'; ?>"><?= $topic['role']; ?></span>
            <h2 style="margin-top:12px;"><?= htmlspecialchars($topic['judul']); ?></h2>
            <div class="forum-meta">Oleh <?= htmlspecialchars($topic['nama']); ?> | <?= $topic['created_at']; ?></div>
            <br>
            <p><?= nl2br(htmlspecialchars($topic['isi'])); ?></p>
        </div>

        <section class="content-grid">
            <div>
                <div class="card">
                    <h3>Balasan Diskusi</h3>
                    <br>

                    <?php if (!$replies || mysqli_num_rows($replies) == 0): ?>
                        <div class="empty-state">Belum ada balasan.</div>
                    <?php endif; ?>

                    <?php if ($replies): ?>
                        <?php while($row = mysqli_fetch_assoc($replies)): ?>
                            <div class="reply-box">
                                <strong><?= htmlspecialchars($row['nama']); ?></strong>
                                <span class="badge <?= $row['role'] == 'guru' ? 'badge-success' : 'badge-primary'; ?>"><?= $row['role']; ?></span>
                                <div class="forum-meta"><?= $row['created_at']; ?></div>
                                <p><?= nl2br(htmlspecialchars($row['isi'])); ?></p>
                            </div>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div>
                <form method="POST" class="card">
                    <h3>Tulis Balasan</h3>
                    <br>
                    <div class="form-group">
                        <label>Balasan</label>
                        <textarea name="isi" class="form-control" rows="6" required></textarea>
                    </div>
                    <button type="submit" name="balas" class="btn btn-primary">Kirim Balasan</button>
                </form>
            </div>
        </section>
    </main>
</div>
</body>
</html>
