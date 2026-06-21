<?php
$ui = [
    'app_name' => 'SmartLearn',
    'logo_icon' => 'SL',
    'primary_color' => '#5b6ee1',
    'accent_color' => '#d8ff3e',
    'sidebar_color' => '#ffffff',
    'radius_size' => 24,
    'font_family' => 'Inter, Arial, sans-serif',
    'font_size' => 15,
    'text_color' => '#1f2937',
    'heading_color' => '#111827',
    'panel_color' => '#ffffff',
    'theme_template' => 'smartlearn_clean'
];

if (isset($koneksi)) {
    $ui_query = @mysqli_query($koneksi, "SELECT * FROM ui_settings WHERE id_setting=1");
    if ($ui_query && mysqli_num_rows($ui_query) > 0) {
        $db_ui = mysqli_fetch_assoc($ui_query);
        foreach ($db_ui as $key => $value) {
            if ($value !== null && $value !== '') {
                $ui[$key] = $value;
            }
        }
    }
}

$ui_app_name = $ui['app_name'] ?? 'SmartLearn';
$ui_logo_icon = $ui['logo_icon'] ?? 'SL';
$ui_primary = $ui['primary_color'] ?? '#5b6ee1';
$ui_accent = $ui['accent_color'] ?? '#d8ff3e';
$ui_sidebar = $ui['sidebar_color'] ?? '#ffffff';
$ui_radius = intval($ui['radius_size'] ?? 24);
$ui_font_family = $ui['font_family'] ?? 'Inter, Arial, sans-serif';
$ui_font_size = intval($ui['font_size'] ?? 15);
$ui_text_color = $ui['text_color'] ?? '#1f2937';
$ui_heading_color = $ui['heading_color'] ?? '#111827';
$ui_panel_color = $ui['panel_color'] ?? '#ffffff';
$ui_theme_template = $ui['theme_template'] ?? 'smartlearn_clean';

$theme_palettes = [
    'smartlearn_clean' => [
        'bg' => '#f5f6fb',
        'panel' => '#ffffff',
        'text' => '#1f2937',
        'heading' => '#111827',
        'sidebar_bg' => '#ffffff',
        'sidebar_text' => '#6b7280',
        'border' => 'rgba(17,24,39,.08)'
    ],
    'default_theme' => [
        'bg' => '#f5f6fb',
        'panel' => '#ffffff',
        'text' => '#1f2937',
        'heading' => '#111827',
        'sidebar_bg' => '#ffffff',
        'sidebar_text' => '#6b7280',
        'border' => 'rgba(17,24,39,.08)'
    ],
    'one_dark_pro' => [
        'bg' => '#21252b',
        'panel' => '#282c34',
        'text' => '#abb2bf',
        'heading' => '#e5c07b',
        'sidebar_bg' => '#21252b',
        'sidebar_text' => '#abb2bf',
        'border' => 'rgba(255,255,255,.10)'
    ],
    'dracula' => [
        'bg' => '#282a36',
        'panel' => '#343746',
        'text' => '#f8f8f2',
        'heading' => '#ff79c6',
        'sidebar_bg' => '#282a36',
        'sidebar_text' => '#f8f8f2',
        'border' => 'rgba(255,255,255,.10)'
    ],
    'tokyo_night' => [
        'bg' => '#1a1b26',
        'panel' => '#24283b',
        'text' => '#c0caf5',
        'heading' => '#7aa2f7',
        'sidebar_bg' => '#1a1b26',
        'sidebar_text' => '#c0caf5',
        'border' => 'rgba(255,255,255,.10)'
    ],
    'nord' => [
        'bg' => '#eceff4',
        'panel' => '#ffffff',
        'text' => '#2e3440',
        'heading' => '#3b4252',
        'sidebar_bg' => '#ffffff',
        'sidebar_text' => '#4c566a',
        'border' => 'rgba(46,52,64,.12)'
    ],
    'night_owl' => [
        'bg' => '#011627',
        'panel' => '#0b2942',
        'text' => '#d6deeb',
        'heading' => '#82aaff',
        'sidebar_bg' => '#011627',
        'sidebar_text' => '#d6deeb',
        'border' => 'rgba(255,255,255,.10)'
    ]
];

$palette = $theme_palettes[$ui_theme_template] ?? $theme_palettes['smartlearn_clean'];
?>
<style>
:root {
    --primary: <?= htmlspecialchars($ui_primary); ?>;
    --secondary: <?= htmlspecialchars($ui_primary); ?>;
    --accent-lime: <?= htmlspecialchars($ui_accent); ?>;
    --accent-lime-2: <?= htmlspecialchars($ui_accent); ?>;
    --accent-blue: <?= htmlspecialchars($ui_primary); ?>;
    --accent-dark: <?= htmlspecialchars($palette['sidebar_bg']); ?>;
    --text: <?= htmlspecialchars($palette['text']); ?>;
    --heading-color: <?= htmlspecialchars($palette['heading']); ?>;
    --panel: <?= htmlspecialchars($palette['panel']); ?>;
    --cream: <?= htmlspecialchars($palette['bg']); ?>;
    --smartlearn-bg: <?= htmlspecialchars($palette['bg']); ?>;
    --smartlearn-panel: <?= htmlspecialchars($palette['panel']); ?>;
    --smartlearn-sidebar: <?= htmlspecialchars($palette['sidebar_bg']); ?>;
    --smartlearn-sidebar-text: <?= htmlspecialchars($palette['sidebar_text']); ?>;
    --smartlearn-border: <?= htmlspecialchars($palette['border']); ?>;
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
    background: var(--smartlearn-sidebar) !important;
    color: var(--smartlearn-sidebar-text) !important;
    border-right: 1px solid var(--smartlearn-border) !important;
    box-shadow: none !important;
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
.filter-card,
.student-clean-card,
.group-card {
    background: var(--panel);
}
.btn,
.badge {
    border-radius: 999px;
}
</style>
