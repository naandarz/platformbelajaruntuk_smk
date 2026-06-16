<?php
$ui = [
    'app_name' => 'HTML Learn RPL',
    'logo_icon' => '</>',
    'primary_color' => '#4438f2',
    'accent_color' => '#d8ff3e',
    'sidebar_color' => '#151923',
    'radius_size' => 30,
    'font_family' => 'Inter, Arial, sans-serif',
    'font_size' => 16,
    'text_color' => '#171827',
    'heading_color' => '#171827',
    'panel_color' => '#ffffff',
    'theme_template' => 'default_theme'
];

if (isset($koneksi)) {
    $ui_query = @mysqli_query($koneksi, "SELECT * FROM ui_settings WHERE id_setting=1");
    if ($ui_query && mysqli_num_rows($ui_query) > 0) {
        $db_ui = mysqli_fetch_assoc($ui_query);
        $ui = array_merge($ui, array_filter($db_ui, function($v) {
            return $v !== null && $v !== '';
        }));
    }
}

$ui_app_name = $ui['app_name'] ?? 'HTML Learn RPL';
$ui_logo_icon = $ui['logo_icon'] ?? '</>';
$ui_primary = $ui['primary_color'] ?? '#4438f2';
$ui_accent = $ui['accent_color'] ?? '#d8ff3e';
$ui_sidebar = $ui['sidebar_color'] ?? '#151923';
$ui_radius = intval($ui['radius_size'] ?? 30);
$ui_font_family = $ui['font_family'] ?? 'Inter, Arial, sans-serif';
$ui_font_size = intval($ui['font_size'] ?? 16);
$ui_text_color = $ui['text_color'] ?? '#171827';
$ui_heading_color = $ui['heading_color'] ?? '#171827';
$ui_panel_color = $ui['panel_color'] ?? '#ffffff';
$ui_theme_template = $ui['theme_template'] ?? 'default_theme';

$theme_palettes = [
    'default_theme' => [
        'bg' => '#f7f4ec',
        'panel' => $ui_panel_color,
        'text' => $ui_text_color,
        'heading' => $ui_heading_color
    ],
    'one_dark_pro' => [
        'bg' => '#21252b',
        'panel' => '#282c34',
        'text' => '#abb2bf',
        'heading' => '#e5c07b'
    ],
    'dracula' => [
        'bg' => '#282a36',
        'panel' => '#343746',
        'text' => '#f8f8f2',
        'heading' => '#ff79c6'
    ],
    'tokyo_night' => [
        'bg' => '#1a1b26',
        'panel' => '#24283b',
        'text' => '#c0caf5',
        'heading' => '#7aa2f7'
    ],
    'nord' => [
        'bg' => '#eceff4',
        'panel' => '#ffffff',
        'text' => '#2e3440',
        'heading' => '#3b4252'
    ],
    'night_owl' => [
        'bg' => '#011627',
        'panel' => '#0b2942',
        'text' => '#d6deeb',
        'heading' => '#82aaff'
    ]
];

$palette = $theme_palettes[$ui_theme_template] ?? $theme_palettes['default_theme'];
?>
<style>
:root {
    --primary: <?= htmlspecialchars($ui_primary); ?>;
    --secondary: <?= htmlspecialchars($ui_primary); ?>;
    --accent-lime: <?= htmlspecialchars($ui_accent); ?>;
    --accent-lime-2: <?= htmlspecialchars($ui_accent); ?>;
    --accent-blue: <?= htmlspecialchars($ui_primary); ?>;
    --accent-dark: <?= htmlspecialchars($ui_sidebar); ?>;
    --text: <?= htmlspecialchars($palette['text']); ?>;
    --heading-color: <?= htmlspecialchars($palette['heading']); ?>;
    --panel: <?= htmlspecialchars($palette['panel']); ?>;
    --cream: <?= htmlspecialchars($palette['bg']); ?>;
    --font-family-ui: <?= htmlspecialchars($ui_font_family); ?>;
    --font-size-ui: <?= $ui_font_size; ?>px;
    --radius-ui: <?= $ui_radius; ?>px;
}
body {
    font-family: var(--font-family-ui) !important;
    font-size: var(--font-size-ui) !important;
    color: var(--text) !important;
}
h1, h2, h3, h4, h5, h6 {
    color: var(--heading-color) !important;
}
.sidebar {
    background:
        radial-gradient(circle at 30% 18%, rgba(216,255,62,0.14), transparent 26%),
        linear-gradient(180deg, <?= htmlspecialchars($ui_sidebar); ?> 0%, #0f121a 100%) !important;
}
.card,
.stat-card,
.lesson-box,
.auth-card,
.guide-step,
.filter-card,
.table-wrapper,
.task-card,
.forum-card,
.account-card,
.clean-card,
.clean-chat-side,
.clean-chat-main,
.student-clean-card,
.group-card {
    border-radius: var(--radius-ui) !important;
}
.clean-card,
.card,
.task-card,
.forum-card,
.table-wrapper,
.filter-card {
    background: var(--panel);
}
.btn,
.badge {
    border-radius: 999px;
}
</style>
