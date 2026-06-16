USE html_learn_rpl;

-- ===== Tahap 20: Kelompok, Tugas Kelompok, dan Pengaturan Tema Lanjutan =====

CREATE TABLE IF NOT EXISTS kelompok (
    id_kelompok INT AUTO_INCREMENT PRIMARY KEY,
    nama_kelompok VARCHAR(150) NOT NULL,
    deskripsi TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS kelompok_anggota (
    id_anggota INT AUTO_INCREMENT PRIMARY KEY,
    id_kelompok INT NOT NULL,
    id_user INT NOT NULL,
    peran VARCHAR(50) DEFAULT 'anggota',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_kelompok_user (id_kelompok, id_user),
    FOREIGN KEY (id_kelompok) REFERENCES kelompok(id_kelompok) ON DELETE CASCADE,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE
);

ALTER TABLE tugas 
ADD COLUMN IF NOT EXISTS tipe_tugas VARCHAR(20) DEFAULT 'individu',
ADD COLUMN IF NOT EXISTS id_kelompok INT DEFAULT NULL;

ALTER TABLE pengumpulan_tugas 
ADD COLUMN IF NOT EXISTS id_kelompok INT DEFAULT NULL;

ALTER TABLE ui_settings 
ADD COLUMN IF NOT EXISTS font_family VARCHAR(120) DEFAULT 'Inter, Arial, sans-serif',
ADD COLUMN IF NOT EXISTS font_size INT DEFAULT 16,
ADD COLUMN IF NOT EXISTS text_color VARCHAR(20) DEFAULT '#171827',
ADD COLUMN IF NOT EXISTS heading_color VARCHAR(20) DEFAULT '#171827',
ADD COLUMN IF NOT EXISTS panel_color VARCHAR(20) DEFAULT '#ffffff',
ADD COLUMN IF NOT EXISTS theme_template VARCHAR(50) DEFAULT 'default_theme';

UPDATE ui_settings
SET 
    font_family = COALESCE(font_family, 'Inter, Arial, sans-serif'),
    font_size = COALESCE(font_size, 16),
    text_color = COALESCE(text_color, '#171827'),
    heading_color = COALESCE(heading_color, '#171827'),
    panel_color = COALESCE(panel_color, '#ffffff'),
    theme_template = COALESCE(theme_template, 'default_theme')
WHERE id_setting=1;

INSERT INTO kelompok (nama_kelompok, deskripsi)
SELECT 'Kelompok Web Kreatif', 'Kelompok contoh untuk pengerjaan tugas web secara berkolaborasi.'
WHERE NOT EXISTS (SELECT 1 FROM kelompok WHERE nama_kelompok='Kelompok Web Kreatif');

INSERT IGNORE INTO kelompok_anggota (id_kelompok, id_user, peran)
SELECT 
    (SELECT id_kelompok FROM kelompok WHERE nama_kelompok='Kelompok Web Kreatif' LIMIT 1),
    id_user,
    'anggota'
FROM users
WHERE role='siswa'
LIMIT 3;
