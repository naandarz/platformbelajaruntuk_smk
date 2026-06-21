USE html_learn_rpl;

-- ===== Tahap 21: SmartLearn Clean UI =====
-- Mengubah identitas aplikasi dan default tampilan menjadi clean/simple.

CREATE TABLE IF NOT EXISTS ui_settings (
    id_setting INT AUTO_INCREMENT PRIMARY KEY,
    app_name VARCHAR(100) NOT NULL DEFAULT 'SmartLearn',
    logo_icon VARCHAR(20) NOT NULL DEFAULT 'SL',
    primary_color VARCHAR(20) NOT NULL DEFAULT '#5b6ee1',
    accent_color VARCHAR(20) NOT NULL DEFAULT '#d8ff3e',
    sidebar_color VARCHAR(20) NOT NULL DEFAULT '#ffffff',
    radius_size INT NOT NULL DEFAULT 24,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

ALTER TABLE ui_settings 
ADD COLUMN IF NOT EXISTS font_family VARCHAR(120) DEFAULT 'Inter, Arial, sans-serif',
ADD COLUMN IF NOT EXISTS font_size INT DEFAULT 15,
ADD COLUMN IF NOT EXISTS text_color VARCHAR(20) DEFAULT '#1f2937',
ADD COLUMN IF NOT EXISTS heading_color VARCHAR(20) DEFAULT '#111827',
ADD COLUMN IF NOT EXISTS panel_color VARCHAR(20) DEFAULT '#ffffff',
ADD COLUMN IF NOT EXISTS theme_template VARCHAR(50) DEFAULT 'smartlearn_clean';

INSERT INTO ui_settings (id_setting, app_name, logo_icon, primary_color, accent_color, sidebar_color, radius_size, font_family, font_size, text_color, heading_color, panel_color, theme_template)
SELECT 1, 'SmartLearn', 'SL', '#5b6ee1', '#d8ff3e', '#ffffff', 24, 'Inter, Arial, sans-serif', 15, '#1f2937', '#111827', '#ffffff', 'smartlearn_clean'
WHERE NOT EXISTS (SELECT 1 FROM ui_settings WHERE id_setting=1);

UPDATE ui_settings
SET 
    app_name='SmartLearn',
    logo_icon='SL',
    primary_color='#5b6ee1',
    accent_color='#d8ff3e',
    sidebar_color='#ffffff',
    panel_color='#ffffff',
    text_color='#1f2937',
    heading_color='#111827',
    font_family='Inter, Arial, sans-serif',
    font_size=15,
    radius_size=24,
    theme_template='smartlearn_clean'
WHERE id_setting=1;
