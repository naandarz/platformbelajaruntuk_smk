<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('admin');

$id_admin = $_SESSION['user']['id_user'];
$pesan = "";
$error = "";

/* Membuat kolom password_plain otomatis agar halaman tidak error
   walaupun file SQL belum diimport. */
$cekKolom = mysqli_query($koneksi, "SHOW COLUMNS FROM users LIKE 'password_plain'");
if ($cekKolom && mysqli_num_rows($cekKolom) == 0) {
    mysqli_query($koneksi, "ALTER TABLE users ADD COLUMN password_plain VARCHAR(255) DEFAULT NULL");
    mysqli_query($koneksi, "UPDATE users SET password_plain='123456' WHERE email IN ('siswa@demo.com','guru@demo.com','admin@demo.com') AND (password_plain IS NULL OR password_plain='')");
}

function roleBadgeClass($role) {
    if ($role == 'siswa') return 'user-role-siswa';
    if ($role == 'guru') return 'user-role-guru';
    return 'user-role-admin';
}

function addNotif($koneksi, $id_user, $judul, $pesan, $tipe='info', $link=null) {
    $judul = mysqli_real_escape_string($koneksi, $judul);
    $pesan = mysqli_real_escape_string($koneksi, $pesan);
    $tipe = mysqli_real_escape_string($koneksi, $tipe);
    $linkSql = $link ? "'" . mysqli_real_escape_string($koneksi, $link) . "'" : "NULL";
    mysqli_query($koneksi, "INSERT INTO notifikasi (id_user, judul, pesan, tipe, link) VALUES ($id_user, '$judul', '$pesan', '$tipe', $linkSql)");
}

if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password_plain = mysqli_real_escape_string($koneksi, $_POST['password']);
    $password = md5($_POST['password']);
    $role = mysqli_real_escape_string($koneksi, $_POST['role']);
    $kelas = mysqli_real_escape_string($koneksi, $_POST['kelas']);

    $cek = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT id_user FROM users WHERE email='$email' LIMIT 1"));
    if ($cek) {
        $error = "Email sudah digunakan oleh akun lain.";
    } else {
        mysqli_query($koneksi, "INSERT INTO users (nama, email, password, password_plain, role, kelas)
        VALUES ('$nama', '$email', '$password', '$password_plain', '$role', '$kelas')");
        $new_id = mysqli_insert_id($koneksi);

        $ket = mysqli_real_escape_string($koneksi, "Admin membuat akun baru $email.");
        mysqli_query($koneksi, "INSERT INTO admin_helpdesk_logs (id_admin, id_target_user, aksi, keterangan)
        VALUES ($id_admin, $new_id, 'Tambah Akun', '$ket')");

        addNotif($koneksi, $new_id, "Akun Dibuat", "Akun kamu sudah dibuat oleh admin. Silakan login menggunakan email dan password yang diberikan.", "success", "../$role/profil.php");

        header("Location: kelola_pengguna.php?status=ditambah");
        exit;
    }
}

if (isset($_POST['update_account'])) {
    $id_user = intval($_POST['id_user']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $kelas = mysqli_real_escape_string($koneksi, $_POST['kelas']);
    $role = mysqli_real_escape_string($koneksi, $_POST['role']);

    $target = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM users WHERE id_user=$id_user"));
    if (!$target) {
        $error = "Akun target tidak ditemukan.";
    } else {
        mysqli_query($koneksi, "UPDATE users SET nama='$nama', kelas='$kelas', role='$role' WHERE id_user=$id_user");

        if ($id_user == $id_admin) {
            $q = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user=$id_user");
            $_SESSION['user'] = mysqli_fetch_assoc($q);
        }

        $ket = mysqli_real_escape_string($koneksi, "Admin memperbarui data akun " . $target['email']);
        mysqli_query($koneksi, "INSERT INTO admin_helpdesk_logs (id_admin, id_target_user, aksi, keterangan)
        VALUES ($id_admin, $id_user, 'Update Data Akun', '$ket')");

        addNotif($koneksi, $id_user, "Data Akun Diperbarui", "Admin telah memperbarui data akun kamu. Cek kembali profil kamu.", "info", "../$role/profil.php");

        header("Location: kelola_pengguna.php?status=diupdate");
        exit;
    }
}

if (isset($_POST['reset_password'])) {
    $id_user = intval($_POST['id_user']);
    $password_baru = trim($_POST['password_baru'] ?? '');
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan'] ?? '');

    if ($password_baru == "") {
        $error = "Password baru tidak boleh kosong.";
    } elseif (strlen($password_baru) < 6) {
        $error = "Password baru minimal 6 karakter.";
    } else {
        $target = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM users WHERE id_user=$id_user"));

        if (!$target) {
            $error = "Akun target tidak ditemukan.";
        } else {
            $password_plain = mysqli_real_escape_string($koneksi, $password_baru);
            $password_hash = md5($password_baru);
            mysqli_query($koneksi, "UPDATE users SET password='$password_hash', password_plain='$password_plain' WHERE id_user=$id_user");

            $ket = $keterangan ?: "Reset password akun " . $target['nama'] . " oleh admin.";
            $ket = mysqli_real_escape_string($koneksi, $ket);
            mysqli_query($koneksi, "INSERT INTO admin_helpdesk_logs (id_admin, id_target_user, aksi, keterangan)
            VALUES ($id_admin, $id_user, 'Reset Password', '$ket')");

            addNotif($koneksi, $id_user, "Password Direset Admin", "Password akun kamu telah direset oleh admin. Segera login dan ubah password melalui menu Profil.", "warning", "../" . $target['role'] . "/profil.php");

            header("Location: kelola_pengguna.php?status=reset&user=" . urlencode($target['nama']));
            exit;
        }
    }
}

if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);

    if ($id == $id_admin) {
        header("Location: kelola_pengguna.php?status=gagalhapus");
        exit;
    }

    mysqli_query($koneksi, "DELETE FROM users WHERE id_user=$id");
    header("Location: kelola_pengguna.php?status=dihapus");
    exit;
}

if (isset($_GET['status'])) {
    if ($_GET['status'] == 'ditambah') $pesan = "Akun berhasil ditambahkan.";
    if ($_GET['status'] == 'diupdate') $pesan = "Data akun berhasil diperbarui.";
    if ($_GET['status'] == 'dihapus') $pesan = "Akun berhasil dihapus.";
    if ($_GET['status'] == 'gagalhapus') $error = "Akun yang sedang digunakan tidak boleh dihapus.";
    if ($_GET['status'] == 'reset') {
        $nama = htmlspecialchars($_GET['user'] ?? 'pengguna');
        $pesan = "Password akun $nama berhasil direset.";
    }
}

$keyword = mysqli_real_escape_string($koneksi, $_GET['keyword'] ?? '');
$filter_role = mysqli_real_escape_string($koneksi, $_GET['role'] ?? '');

$where = "WHERE 1=1";
if ($keyword != "") {
    $where .= " AND (nama LIKE '%$keyword%' OR email LIKE '%$keyword%' OR kelas LIKE '%$keyword%')";
}
if ($filter_role != "") {
    $where .= " AND role='$filter_role'";
}

$users = mysqli_query($koneksi, "SELECT * FROM users $where ORDER BY role ASC, nama ASC");

$total_users = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM users"))['total'];
$total_siswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM users WHERE role='siswa'"))['total'];
$total_guru = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM users WHERE role='guru'"))['total'];
$total_admin = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM users WHERE role='admin'"))['total'];

$logs = mysqli_query($koneksi, "
    SELECT 
        admin_helpdesk_logs.*,
        admin_user.nama AS nama_admin,
        target_user.nama AS nama_target,
        target_user.email AS email_target
    FROM admin_helpdesk_logs
    JOIN users admin_user ON admin_helpdesk_logs.id_admin = admin_user.id_user
    JOIN users target_user ON admin_helpdesk_logs.id_target_user = target_user.id_user
    ORDER BY admin_helpdesk_logs.created_at DESC
    LIMIT 4
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Pengguna & Help Desk</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
<?php include "../../includes/pwa_head.php"; ?>
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Kelola Pengguna & IT Help Desk</h1>
                <p>Admin dapat menambah akun, mengubah data, melihat password sementara, dan reset password dari satu halaman.</p>
            </div>
            <a href="pesan.php" class="btn btn-outline">Pesan Bantuan</a>
        </div>

        <?php if ($pesan): ?><div class="alert alert-success"><?= $pesan; ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-danger"><?= $error; ?></div><?php endif; ?>

        <section class="stats-grid">
            <div class="stat-card"><span>Total Akun</span><h2><?= $total_users; ?></h2></div>
            <div class="stat-card"><span>Siswa</span><h2><?= $total_siswa; ?></h2></div>
            <div class="stat-card"><span>Guru</span><h2><?= $total_guru; ?></h2></div>
            <div class="stat-card"><span>Admin</span><h2><?= $total_admin; ?></h2></div>
        </section>

        <section class="clean-page-grid">
            <div class="clean-stack">
                <form method="POST" class="clean-card">
                    <span class="badge badge-primary">Tambah Akun</span>
                    <h3 style="margin-top:12px;">Buat Pengguna Baru</h3>
                    <p class="small-muted">Gunakan password sementara, lalu minta pengguna menggantinya lewat menu Profil.</p>
                    <br>

                    <div class="form-group"><label>Nama</label><input type="text" name="nama" class="form-control" required></div>
                    <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" required></div>

                    <div class="form-group">
                        <label>Password Awal</label>
                        <div class="password-viewer-clean">
                            <input type="password" name="password" id="password_baru_akun" class="form-control" value="123456" required>
                            <button type="button" class="eye-btn-clean" onclick="togglePasswordField('password_baru_akun', this)">👁</button>
                        </div>
                        <div class="quick-actions-clean">
                            <button type="button" onclick="document.getElementById('password_baru_akun').value='123456'">123456</button>
                            <button type="button" onclick="document.getElementById('password_baru_akun').value='rpl12345'">rpl12345</button>
                            <button type="button" onclick="generateTempPassword('password_baru_akun')">Generate</button>
                        </div>
                    </div>

                    <div class="form-row-clean">
                        <div class="form-group">
                            <label>Role</label>
                            <select name="role" class="form-control" required>
                                <option value="siswa">Siswa</option>
                                <option value="guru">Guru</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="form-group" style="grid-column:span 2;"><label>Kelas</label><input type="text" name="kelas" class="form-control" placeholder="Contoh: X RPL 1"></div>
                    </div>

                    <button type="submit" name="tambah" class="btn btn-primary">Simpan Akun</button>
                </form>

                <div class="clean-card">
                    <div style="display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap;">
                        <div>
                            <h3>Riwayat Help Desk</h3>
                            <p class="small-muted">Aktivitas terbaru ditampilkan ringkas.</p>
                        </div>
                        <a href="helpdesk_logs.php" class="btn btn-outline btn-sm">Lihat Semua</a>
                    </div>
                    <br>

                    <?php if (!$logs || mysqli_num_rows($logs) == 0): ?>
                        <div class="empty-state">Belum ada riwayat bantuan akun.</div>
                    <?php endif; ?>

                    <?php if ($logs): ?>
                        <div class="history-box-clean">
                            <?php while($log = mysqli_fetch_assoc($logs)): ?>
                                <div class="history-item-clean">
                                    <span class="badge badge-primary"><?= htmlspecialchars($log['aksi']); ?></span>
                                    <h4 style="margin-top:8px;"><?= htmlspecialchars($log['nama_target']); ?></h4>
                                    <p>
                                        <?= htmlspecialchars($log['email_target']); ?><br>
                                        Admin: <?= htmlspecialchars($log['nama_admin']); ?><br>
                                        <?= $log['created_at']; ?>
                                    </p>
                                    <?php if ($log['keterangan']): ?>
                                        <small><?= htmlspecialchars($log['keterangan']); ?></small>
                                    <?php endif; ?>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div>
                <div class="filter-card">
                    <form method="GET" class="filter-form">
                        <div class="form-group">
                            <label>Cari Akun</label>
                            <input type="text" name="keyword" class="form-control" value="<?= htmlspecialchars($keyword); ?>" placeholder="Cari nama, email, atau kelas...">
                        </div>

                        <div class="form-group">
                            <label>Role</label>
                            <select name="role" class="form-control">
                                <option value="">Semua Role</option>
                                <option value="siswa" <?= $filter_role == 'siswa' ? 'selected' : ''; ?>>Siswa</option>
                                <option value="guru" <?= $filter_role == 'guru' ? 'selected' : ''; ?>>Guru</option>
                                <option value="admin" <?= $filter_role == 'admin' ? 'selected' : ''; ?>>Admin</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Cari</button>
                        <a href="kelola_pengguna.php" class="btn btn-outline">Reset</a>
                    </form>
                </div>

                <?php if (!$users || mysqli_num_rows($users) == 0): ?>
                    <div class="empty-state">Akun tidak ditemukan.</div>
                <?php endif; ?>

                <?php if ($users): ?>
                    <?php while($row = mysqli_fetch_assoc($users)): ?>
                        <?php
                            $plain = $row['password_plain'] ?? '';
                            $passwordDisplay = $plain != '' ? $plain : 'Belum tersimpan';
                        ?>
                        <div class="user-help-card">
                            <div class="user-help-head">
                                <div>
                                    <span class="badge <?= roleBadgeClass($row['role']); ?>"><?= htmlspecialchars($row['role']); ?></span>
                                    <h3 style="margin-top:12px;"><?= htmlspecialchars($row['nama']); ?></h3>
                                    <div class="account-info-list">
                                        <div><strong>Email:</strong> <?= htmlspecialchars($row['email']); ?></div>
                                        <div><strong>Kelas:</strong> <?= htmlspecialchars($row['kelas'] ?: '-'); ?></div>
                                    </div>
                                </div>
                                <div class="action-row">
                                    <a href="edit_pengguna.php?id=<?= $row['id_user']; ?>" class="btn btn-outline btn-sm">Edit Lengkap</a>
                                    <?php if ($row['id_user'] != $id_admin): ?>
                                        <a href="?hapus=<?= $row['id_user']; ?>" onclick="return confirm('Hapus akun ini?')" class="btn btn-danger btn-sm">Hapus</a>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="password-box-clean">
                                <label>Password Akun</label>
                                <div class="password-viewer-clean">
                                    <input type="password" id="viewpass_<?= $row['id_user']; ?>" class="form-control" value="<?= htmlspecialchars($passwordDisplay); ?>" readonly>
                                    <button type="button" class="eye-btn-clean" onclick="togglePasswordField('viewpass_<?= $row['id_user']; ?>', this)">👁</button>
                                </div>
                                <div class="password-note">
                                    Password yang tampil adalah password sementara/terakhir diset admin. Jika belum tersimpan, reset password sekali.
                                </div>
                            </div>

                            <details class="help-details">
                                <summary>Edit Cepat Data Akun</summary>
                                <div class="help-details-body">
                                    <form method="POST">
                                        <input type="hidden" name="id_user" value="<?= $row['id_user']; ?>">
                                        <div class="form-row-clean">
                                            <div class="form-group"><label>Nama</label><input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($row['nama']); ?>" required></div>
                                            <div class="form-group"><label>Kelas</label><input type="text" name="kelas" class="form-control" value="<?= htmlspecialchars($row['kelas']); ?>"></div>
                                            <div class="form-group">
                                                <label>Role</label>
                                                <select name="role" class="form-control" required>
                                                    <option value="siswa" <?= $row['role'] == 'siswa' ? 'selected' : ''; ?>>Siswa</option>
                                                    <option value="guru" <?= $row['role'] == 'guru' ? 'selected' : ''; ?>>Guru</option>
                                                    <option value="admin" <?= $row['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                                </select>
                                            </div>
                                        </div>
                                        <button type="submit" name="update_account" class="btn btn-primary">Simpan Data</button>
                                    </form>
                                </div>
                            </details>

                            <details class="help-details">
                                <summary>Reset Password / Buat Password Baru</summary>
                                <div class="help-details-body">
                                    <form method="POST">
                                        <input type="hidden" name="id_user" value="<?= $row['id_user']; ?>">
                                        <div class="form-group">
                                            <label>Password Baru</label>
                                            <div class="password-viewer-clean">
                                                <input type="password" name="password_baru" id="password_<?= $row['id_user']; ?>" class="form-control" placeholder="Minimal 6 karakter" required>
                                                <button type="button" class="eye-btn-clean" onclick="togglePasswordField('password_<?= $row['id_user']; ?>', this)">👁</button>
                                            </div>
                                            <div class="quick-actions-clean">
                                                <button type="button" onclick="document.getElementById('password_<?= $row['id_user']; ?>').value='123456'">123456</button>
                                                <button type="button" onclick="document.getElementById('password_<?= $row['id_user']; ?>').value='rpl12345'">rpl12345</button>
                                                <button type="button" onclick="document.getElementById('password_<?= $row['id_user']; ?>').value='smkbisa123'">smkbisa123</button>
                                                <button type="button" onclick="generateTempPassword('password_<?= $row['id_user']; ?>')">Generate</button>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Keterangan Bantuan</label>
                                            <textarea name="keterangan" class="form-control" rows="3" placeholder="Contoh: Reset password karena siswa lupa password login."></textarea>
                                        </div>
                                        <button type="submit" name="reset_password" onclick="return confirm('Reset password akun ini?')" class="btn btn-danger">Reset Password</button>
                                    </form>
                                </div>
                            </details>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>
</div>
<script src="../../assets/js/password-toggle.js"></script>
</body>
</html>
