<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('guru');

$id_user = $_SESSION['user']['id_user'];
$role = $_SESSION['user']['role'];
$pesan_sukses = "";
$error = "";

function addNotifLocal($koneksi, $id_user, $judul, $pesan, $tipe='info', $link=null) {
    $judul = mysqli_real_escape_string($koneksi, $judul);
    $pesan = mysqli_real_escape_string($koneksi, $pesan);
    $tipe = mysqli_real_escape_string($koneksi, $tipe);
    $linkSql = $link ? "'" . mysqli_real_escape_string($koneksi, $link) . "'" : "NULL";
    mysqli_query($koneksi, "INSERT INTO notifikasi (id_user, judul, pesan, tipe, link) VALUES ($id_user, '$judul', '$pesan', '$tipe', $linkSql)");
}

if (isset($_POST['mark_read'])) {
    mysqli_query($koneksi, "UPDATE notifikasi SET sudah_dibaca=1 WHERE id_user=$id_user");
    header("Location: pesan.php?status=read");
    exit;
}

if (isset($_POST['kirim_chat'])) {
    $id_receiver = intval($_POST['id_receiver']);
    $isi = trim($_POST['pesan']);

    if ($id_receiver <= 0 || $isi == '') {
        $error = "Pilih penerima dan isi pesan terlebih dahulu.";
    } else {
        $receiver = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM users WHERE id_user=$id_receiver"));
        if (!$receiver) {
            $error = "Penerima tidak ditemukan.";
        } else {
            $isi_sql = mysqli_real_escape_string($koneksi, $isi);
            mysqli_query($koneksi, "INSERT INTO chat_messages (id_sender, id_receiver, pesan) VALUES ($id_user, $id_receiver, '$isi_sql')");

            addNotifLocal($koneksi, $id_receiver, "Pesan Baru", $_SESSION['user']['nama'] . " mengirim pesan baru.", "info", "../" . $receiver['role'] . "/pesan.php");

            header("Location: pesan.php?chat_with=$id_receiver&status=sent");
            exit;
        }
    }
}

if (isset($_GET['status'])) {
    if ($_GET['status'] == 'sent') $pesan_sukses = "Pesan berhasil dikirim.";
    if ($_GET['status'] == 'read') $pesan_sukses = "Semua notifikasi ditandai sudah dibaca.";
}

$chat_with = intval($_GET['chat_with'] ?? 0);

if ($role == 'admin') {
    $receivers = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user != $id_user ORDER BY role ASC, nama ASC");
} else {
    $receivers = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user != $id_user AND role IN ('guru','admin') ORDER BY role ASC, nama ASC");
}

$receiverData = null;
if ($chat_with > 0) {
    $receiverData = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM users WHERE id_user=$chat_with"));
    if ($receiverData) {
        mysqli_query($koneksi, "UPDATE chat_messages SET sudah_dibaca=1 WHERE id_sender=$chat_with AND id_receiver=$id_user");
    }
}

$messages = null;
if ($chat_with > 0) {
    $messages = mysqli_query($koneksi, "
        SELECT chat_messages.*, sender.nama AS nama_sender, sender.role AS role_sender
        FROM chat_messages
        JOIN users sender ON chat_messages.id_sender = sender.id_user
        WHERE (id_sender=$id_user AND id_receiver=$chat_with)
           OR (id_sender=$chat_with AND id_receiver=$id_user)
        ORDER BY chat_messages.created_at ASC
    ");
}

$notif = mysqli_query($koneksi, "SELECT * FROM notifikasi WHERE id_user=$id_user ORDER BY created_at DESC LIMIT 6");
$unread = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM notifikasi WHERE id_user=$id_user AND sudah_dibaca=0"))['total'];

$threads = mysqli_query($koneksi, "
    SELECT u.id_user, u.nama, u.role,
        MAX(cm.created_at) AS last_time,
        SUM(CASE WHEN cm.id_receiver=$id_user AND cm.sudah_dibaca=0 THEN 1 ELSE 0 END) AS unread_count
    FROM users u
    JOIN chat_messages cm 
        ON (cm.id_sender=u.id_user AND cm.id_receiver=$id_user)
        OR (cm.id_receiver=u.id_user AND cm.id_sender=$id_user)
    WHERE u.id_user != $id_user
    GROUP BY u.id_user, u.nama, u.role
    ORDER BY last_time DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesan & Bantuan</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
<?php include "../../includes/pwa_head.php"; ?>
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Pesan & Bantuan</h1>
                <p>Notifikasi dan live chat dibuat rapi agar percakapan mudah dibaca.</p>
            </div>
            <div class="user-pill">
                <div class="avatar">💬</div>
                <?= $unread; ?> belum dibaca
            </div>
        </div>

        <?php if ($pesan_sukses): ?><div class="alert alert-success"><?= $pesan_sukses; ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-danger"><?= $error; ?></div><?php endif; ?>

        <section class="clean-chat-grid">
            <aside class="clean-chat-side">
                <div class="clean-chat-section">
                    <div style="display:flex; justify-content:space-between; gap:12px; align-items:flex-start;">
                        <div>
                            <h3>Notifikasi</h3>
                            <p class="small-muted">Info akun, tugas, nilai, dan pesan sistem.</p>
                        </div>
                        <form method="POST">
                            <button class="btn btn-outline btn-sm" name="mark_read" type="submit">Dibaca</button>
                        </form>
                    </div>

                    <?php if (!$notif || mysqli_num_rows($notif) == 0): ?>
                        <div class="empty-state">Belum ada notifikasi.</div>
                    <?php endif; ?>

                    <?php if ($notif): ?>
                        <?php while($n = mysqli_fetch_assoc($notif)): ?>
                            <div class="notification-clean <?= $n['sudah_dibaca'] ? '' : 'unread'; ?>">
                                <span class="badge <?= $n['sudah_dibaca'] ? 'badge-neutral' : 'badge-primary'; ?>">
                                    <?= $n['sudah_dibaca'] ? 'Dibaca' : 'Baru'; ?>
                                </span>
                                <h4 style="margin-top:8px;"><?= htmlspecialchars($n['judul']); ?></h4>
                                <p><?= nl2br(htmlspecialchars($n['pesan'])); ?></p>
                                <div class="message-time-clean"><?= $n['created_at']; ?></div>
                                <?php if ($n['link']): ?>
                                    <br><a href="<?= htmlspecialchars($n['link']); ?>" class="btn btn-outline btn-sm">Buka</a>
                                <?php endif; ?>
                            </div>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>

                <div class="clean-chat-section">
                    <h3>Riwayat Chat</h3>
                    <p class="small-muted">Percakapan terakhir dengan pengguna lain.</p>

                    <?php if (!$threads || mysqli_num_rows($threads) == 0): ?>
                        <div class="empty-state">Belum ada percakapan.</div>
                    <?php endif; ?>

                    <?php if ($threads): ?>
                        <?php while($t = mysqli_fetch_assoc($threads)): ?>
                            <a href="pesan.php?chat_with=<?= $t['id_user']; ?>" class="thread-clean <?= $chat_with == $t['id_user'] ? 'active' : ''; ?>">
                                <span class="badge badge-primary"><?= $t['role']; ?></span>
                                <h4 style="margin-top:8px;"><?= htmlspecialchars($t['nama']); ?></h4>
                                <div class="message-time-clean">Terakhir: <?= $t['last_time']; ?></div>
                                <?php if ($t['unread_count'] > 0): ?>
                                    <br><span class="badge badge-warning"><?= $t['unread_count']; ?> pesan baru</span>
                                <?php endif; ?>
                            </a>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
            </aside>

            <section class="clean-chat-main">
                <div class="chat-header-clean">
                    <h3>Live Chat</h3>
                    <p class="small-muted">
                        <?php if ($receiverData): ?>
                            Sedang chat dengan <strong><?= htmlspecialchars($receiverData['nama']); ?></strong>.
                        <?php else: ?>
                            Pilih penerima untuk memulai live chat.
                        <?php endif; ?>
                    </p>

                    <form method="GET" class="chat-picker-clean">
                        <div class="form-group" style="margin:0;">
                            <label>Pilih Penerima</label>
                            <select name="chat_with" class="form-control" required>
                                <option value="">Pilih pengguna</option>
                                <?php while($r = mysqli_fetch_assoc($receivers)): ?>
                                    <option value="<?= $r['id_user']; ?>" <?= $chat_with == $r['id_user'] ? 'selected' : ''; ?>>
                                        <?= $r['nama']; ?> - <?= $r['role']; ?> <?= $r['kelas'] ? '(' . $r['kelas'] . ')' : ''; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <button class="btn btn-primary" type="submit">Buka Chat</button>
                    </form>
                </div>

                <div class="chat-messages-clean" id="chatMessagesBox">
                    <?php if (!$receiverData): ?>
                        <div class="empty-state" style="margin:auto;">Pilih penerima chat terlebih dahulu.</div>
                    <?php endif; ?>

                    <?php if ($messages): ?>
                        <?php while($m = mysqli_fetch_assoc($messages)): ?>
                            <?php $isMe = intval($m['id_sender']) === intval($id_user); ?>
                            <div class="message-row-clean <?= $isMe ? 'me' : 'other'; ?>">
                                <div class="message-label-clean">
                                    <?= $isMe ? 'Saya' : htmlspecialchars($m['nama_sender']); ?>
                                    <?php if (!$isMe): ?>
                                        <span class="badge badge-neutral"><?= htmlspecialchars($m['role_sender']); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="message-bubble-clean">
                                    <?= nl2br(htmlspecialchars($m['pesan'])); ?>
                                </div>
                                <div class="message-time-clean"><?= $m['created_at']; ?></div>
                            </div>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>

                <?php if ($receiverData): ?>
                    <form method="POST" class="chat-compose-clean">
                        <input type="hidden" name="id_receiver" value="<?= $receiverData['id_user']; ?>">
                        <div class="form-group">
                            <label>Tulis Pesan</label>
                            <textarea name="pesan" class="form-control" rows="4" placeholder="Tulis pesan bantuan atau diskusi..." required></textarea>
                        </div>
                        <button type="submit" name="kirim_chat" class="btn btn-primary">Kirim Pesan</button>
                    </form>
                <?php endif; ?>
            </section>
        </section>
    </main>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const box = document.getElementById("chatMessagesBox");
    if (box) box.scrollTop = box.scrollHeight;
});
</script>
</body>
</html>
