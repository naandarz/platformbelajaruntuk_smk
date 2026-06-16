<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('admin');

/* Pastikan kolom tema lanjutan tersedia. */
$requiredColumns = [
    "font_family" => "VARCHAR(120) DEFAULT 'Inter, Arial, sans-serif'",
    "font_size" => "INT DEFAULT 16",
    "text_color" => "VARCHAR(20) DEFAULT '#171827'",
    "heading_color" => "VARCHAR(20) DEFAULT '#171827'",
    "panel_color" => "VARCHAR(20) DEFAULT '#ffffff'",
    "theme_template" => "VARCHAR(50) DEFAULT 'default_theme'"
];

foreach ($requiredColumns as $col => $definition) {
    $cek = mysqli_query($koneksi, "SHOW COLUMNS FROM ui_settings LIKE '$col'");
    if ($cek && mysqli_num_rows($cek) == 0) {
        mysqli_query($koneksi, "ALTER TABLE ui_settings ADD COLUMN $col $definition");
    }
}

$pesan = "";
$error = "";

$themes = [
    'default_theme' => [
        'name' => 'Default Learning',
        'desc' => 'Tema terang bawaan yang bersih dan cocok untuk pembelajaran.',
        'primary' => '#4438f2',
        'accent' => '#d8ff3e',
        'sidebar' => '#151923',
        'panel' => '#ffffff',
        'text' => '#171827',
        'heading' => '#171827'
    ],
    'one_dark_pro' => [
        'name' => 'One Dark Pro',
        'desc' => 'Tema gelap modern terinspirasi Atom, nyaman untuk membaca kode.',
        'primary' => '#61afef',
        'accent' => '#98c379',
        'sidebar' => '#21252b',
        'panel' => '#282c34',
        'text' => '#abb2bf',
        'heading' => '#e5c07b'
    ],
    'dracula' => [
        'name' => 'Dracula Official',
        'desc' => 'Tema gelap kontras dengan nuansa ungu, pink, dan hijau.',
        'primary' => '#bd93f9',
        'accent' => '#50fa7b',
        'sidebar' => '#282a36',
        'panel' => '#343746',
        'text' => '#f8f8f2',
        'heading' => '#ff79c6'
    ],
    'tokyo_night' => [
        'name' => 'Tokyo Night',
        'desc' => 'Tema gelap estetis dengan neon malam kota Tokyo.',
        'primary' => '#7aa2f7',
        'accent' => '#9ece6a',
        'sidebar' => '#1a1b26',
        'panel' => '#24283b',
        'text' => '#c0caf5',
        'heading' => '#7dcfff'
    ],
    'nord' => [
        'name' => 'Nord',
        'desc' => 'Palet biru es yang bersih, tenang, dan nyaman.',
        'primary' => '#5e81ac',
        'accent' => '#a3be8c',
        'sidebar' => '#2e3440',
        'panel' => '#ffffff',
        'text' => '#2e3440',
        'heading' => '#3b4252'
    ],
    'night_owl' => [
        'name' => 'Night Owl',
        'desc' => 'Tema malam untuk sesi belajar/ngoding agar mata tidak cepat lelah.',
        'primary' => '#82aaff',
        'accent' => '#c3e88d',
        'sidebar' => '#011627',
        'panel' => '#0b2942',
        'text' => '#d6deeb',
        'heading' => '#addb67'
    ]
];

$ui = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM ui_settings WHERE id_setting=1"));
if (!$ui) {
    mysqli_query($koneksi, "INSERT INTO ui_settings (id_setting, app_name, logo_icon, primary_color, accent_color, sidebar_color, radius_size)
    VALUES (1, 'HTML Learn RPL', '</>', '#4438f2', '#d8ff3e', '#151923', 30)");
    $ui = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM ui_settings WHERE id_setting=1"));
}

if (isset($_POST['apply_template'])) {
    $template = $_POST['theme_template'] ?? 'default_theme';
    if (!isset($themes[$template])) $template = 'default_theme';

    $t = $themes[$template];
    mysqli_query($koneksi, "UPDATE ui_settings SET
        theme_template='$template',
        primary_color='{$t['primary']}',
        accent_color='{$t['accent']}',
        sidebar_color='{$t['sidebar']}',
        panel_color='{$t['panel']}',
        text_color='{$t['text']}',
        heading_color='{$t['heading']}'
        WHERE id_setting=1
    ");

    header("Location: ui_settings.php?status=template");
    exit;
}

if (isset($_POST['simpan_ui'])) {
    $app_name = mysqli_real_escape_string($koneksi, $_POST['app_name']);
    $logo_icon = mysqli_real_escape_string($koneksi, $_POST['logo_icon']);
    $primary_color = mysqli_real_escape_string($koneksi, $_POST['primary_color']);
    $accent_color = mysqli_real_escape_string($koneksi, $_POST['accent_color']);
    $sidebar_color = mysqli_real_escape_string($koneksi, $_POST['sidebar_color']);
    $panel_color = mysqli_real_escape_string($koneksi, $_POST['panel_color']);
    $text_color = mysqli_real_escape_string($koneksi, $_POST['text_color']);
    $heading_color = mysqli_real_escape_string($koneksi, $_POST['heading_color']);
    $font_family = mysqli_real_escape_string($koneksi, $_POST['font_family']);
    $font_size = intval($_POST['font_size']);
    $radius_size = intval($_POST['radius_size']);

    if ($font_size < 13) $font_size = 13;
    if ($font_size > 22) $font_size = 22;
    if ($radius_size < 8) $radius_size = 8;
    if ($radius_size > 40) $radius_size = 40;

    mysqli_query($koneksi, "UPDATE ui_settings SET
        app_name='$app_name',
        logo_icon='$logo_icon',
        primary_color='$primary_color',
        accent_color='$accent_color',
        sidebar_color='$sidebar_color',
        panel_color='$panel_color',
        text_color='$text_color',
        heading_color='$heading_color',
        font_family='$font_family',
        font_size=$font_size,
        radius_size=$radius_size,
        theme_template='custom'
        WHERE id_setting=1
    ");

    header("Location: ui_settings.php?status=success");
    exit;
}

if (isset($_POST['reset_ui'])) {
    mysqli_query($koneksi, "UPDATE ui_settings SET
        app_name='HTML Learn RPL',
        logo_icon='</>',
        primary_color='#4438f2',
        accent_color='#d8ff3e',
        sidebar_color='#151923',
        panel_color='#ffffff',
        text_color='#171827',
        heading_color='#171827',
        font_family='Inter, Arial, sans-serif',
        font_size=16,
        radius_size=30,
        theme_template='default_theme'
        WHERE id_setting=1
    ");
    header("Location: ui_settings.php?status=reset");
    exit;
}

if (isset($_GET['status'])) {
    if ($_GET['status'] == 'success') $pesan = "Pengaturan UI berhasil disimpan.";
    if ($_GET['status'] == 'reset') $pesan = "Pengaturan UI berhasil dikembalikan ke default.";
    if ($_GET['status'] == 'template') $pesan = "Template tema berhasil diterapkan.";
}

$ui = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM ui_settings WHERE id_setting=1"));
$currentTemplate = $ui['theme_template'] ?? 'default_theme';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengaturan UI</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Pengaturan UI</h1>
                <p>Admin dapat mengatur tema, font, ukuran teks, warna teks, dan warna elemen web.</p>
            </div>
        </div>

        <?php if ($pesan): ?><div class="alert alert-success"><?= $pesan; ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-danger"><?= $error; ?></div><?php endif; ?>

        <section class="content-grid">
            <div>
                <form method="POST" class="student-clean-card">
                    <span class="badge badge-primary">Theme Template</span>
                    <h3 style="margin-top:12px;">Pilih Template Tema Editor</h3>
                    <p class="small-muted">Tema terinspirasi tampilan editor kode agar platform terasa lebih modern.</p>
                    <br>

                    <div class="theme-template-grid">
                        <?php foreach($themes as $key => $theme): ?>
                            <label class="theme-template-card" style="background:<?= $theme['panel']; ?>; color:<?= $theme['text']; ?>;">
                                <input type="radio" name="theme_template" value="<?= $key; ?>" <?= $currentTemplate == $key ? 'checked' : ''; ?>>
                                <div class="theme-preview-dots">
                                    <span style="background:<?= $theme['primary']; ?>"></span>
                                    <span style="background:<?= $theme['accent']; ?>"></span>
                                    <span style="background:<?= $theme['sidebar']; ?>"></span>
                                </div>
                                <h4 style="color:<?= $theme['heading']; ?> !important;"><?= $theme['name']; ?></h4>
                                <p style="color:<?= $theme['text']; ?>;"><?= $theme['desc']; ?></p>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <br>
                    <button type="submit" name="apply_template" class="btn btn-primary">Terapkan Template</button>
                </form>

                <form method="POST" class="student-clean-card" style="margin-top:18px;">
                    <span class="badge badge-success">Custom UI</span>
                    <h3 style="margin-top:12px;">Edit Manual Tampilan</h3>
                    <br>

                    <div class="form-group">
                        <label>Nama Aplikasi</label>
                        <input type="text" name="app_name" class="form-control" value="<?= htmlspecialchars($ui['app_name']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Icon / Logo Singkat</label>
                        <input type="text" name="logo_icon" class="form-control" value="<?= htmlspecialchars($ui['logo_icon']); ?>" required>
                        <small class="input-help">Contoh: &lt;/&gt;, ⚡, 💻, JS, CSS.</small>
                    </div>

                    <div class="font-control-grid">
                        <div class="form-group">
                            <label>Jenis Font</label>
                            <select name="font_family" class="form-control">
                                <?php
                                $fonts = [
                                    'Inter, Arial, sans-serif' => 'Inter / Arial',
                                    'Poppins, Arial, sans-serif' => 'Poppins',
                                    'Segoe UI, Arial, sans-serif' => 'Segoe UI',
                                    'Verdana, Arial, sans-serif' => 'Verdana',
                                    'Georgia, serif' => 'Georgia',
                                    'Consolas, monospace' => 'Consolas / Code Font'
                                ];
                                foreach($fonts as $value => $label):
                                ?>
                                    <option value="<?= htmlspecialchars($value); ?>" <?= ($ui['font_family'] ?? '') == $value ? 'selected' : ''; ?>><?= $label; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Ukuran Font</label>
                            <input type="number" name="font_size" class="form-control" min="13" max="22" value="<?= intval($ui['font_size'] ?? 16); ?>">
                        </div>

                        <div class="form-group">
                            <label>Radius Panel</label>
                            <input type="number" name="radius_size" class="form-control" min="8" max="40" value="<?= intval($ui['radius_size']); ?>">
                        </div>
                    </div>

                    <div class="element-color-grid">
                        <div class="form-group"><label>Warna Utama</label><input type="color" name="primary_color" class="form-control" value="<?= htmlspecialchars($ui['primary_color']); ?>"></div>
                        <div class="form-group"><label>Warna Aksen</label><input type="color" name="accent_color" class="form-control" value="<?= htmlspecialchars($ui['accent_color']); ?>"></div>
                        <div class="form-group"><label>Warna Sidebar</label><input type="color" name="sidebar_color" class="form-control" value="<?= htmlspecialchars($ui['sidebar_color']); ?>"></div>
                        <div class="form-group"><label>Warna Panel</label><input type="color" name="panel_color" class="form-control" value="<?= htmlspecialchars($ui['panel_color'] ?? '#ffffff'); ?>"></div>
                        <div class="form-group"><label>Warna Teks</label><input type="color" name="text_color" class="form-control" value="<?= htmlspecialchars($ui['text_color'] ?? '#171827'); ?>"></div>
                        <div class="form-group"><label>Warna Judul</label><input type="color" name="heading_color" class="form-control" value="<?= htmlspecialchars($ui['heading_color'] ?? '#171827'); ?>"></div>
                    </div>

                    <div class="page-actions">
                        <button type="submit" name="simpan_ui" class="btn btn-primary">Simpan UI</button>
                        <button type="submit" name="reset_ui" onclick="return confirm('Kembalikan UI ke default?')" class="btn btn-outline">Reset Default</button>
                    </div>
                </form>
            </div>

            <div>
                <div class="student-clean-card">
                    <h3>Preview Komponen</h3>
                    <p>Contoh tampilan elemen web setelah pengaturan UI diterapkan.</p>
                    <br>

                    <div class="student-summary-list">
                        <div class="student-summary-item">
                            <div>
                                <strong>Card Panel</strong><br>
                                <span class="small-muted">Panel mengikuti warna dan radius pilihan admin.</span>
                            </div>
                            <span class="badge badge-primary">Preview</span>
                        </div>

                        <div class="student-summary-item">
                            <div>
                                <strong>Tombol Utama</strong><br>
                                <span class="small-muted">Warna utama digunakan pada tombol penting.</span>
                            </div>
                            <button class="btn btn-primary" type="button">Aksi</button>
                        </div>

                        <div class="student-summary-item">
                            <div>
                                <strong>Aksen</strong><br>
                                <span class="small-muted">Warna aksen untuk status aktif, badge, dan highlight.</span>
                            </div>
                            <span class="badge badge-success">Aktif</span>
                        </div>
                    </div>
                </div>

                <div class="student-clean-card" style="margin-top:18px;">
                    <h3>Tips Konsistensi UI</h3>
                    <p>
                        Gunakan kombinasi warna yang kontras, font yang mudah dibaca, dan ukuran teks yang tidak terlalu kecil
                        agar siswa, guru, dan admin nyaman menggunakan sistem.
                    </p>
                </div>
            </div>
        </section>
    </main>
</div>
</body>
</html>
