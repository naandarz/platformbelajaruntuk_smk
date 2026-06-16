USE html_learn_rpl;

CREATE TABLE IF NOT EXISTS admin_helpdesk_logs (
    id_log INT AUTO_INCREMENT PRIMARY KEY,
    id_admin INT NOT NULL,
    id_target_user INT NOT NULL,
    aksi VARCHAR(100) NOT NULL,
    keterangan TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_admin) REFERENCES users(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_target_user) REFERENCES users(id_user) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS tugas (
    id_tugas INT AUTO_INCREMENT PRIMARY KEY,
    id_materi INT DEFAULT NULL,
    judul_tugas VARCHAR(200) NOT NULL,
    deskripsi TEXT NOT NULL,
    batas_waktu DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_materi) REFERENCES materi(id_materi) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS pengumpulan_tugas (
    id_pengumpulan INT AUTO_INCREMENT PRIMARY KEY,
    id_tugas INT NOT NULL,
    id_user INT NOT NULL,
    file_tugas VARCHAR(255) DEFAULT NULL,
    catatan TEXT DEFAULT NULL,
    nilai INT DEFAULT NULL,
    feedback TEXT DEFAULT NULL,
    tanggal_upload TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    tanggal_nilai TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (id_tugas) REFERENCES tugas(id_tugas) ON DELETE CASCADE,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS notifikasi (
    id_notifikasi INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    judul VARCHAR(200) NOT NULL,
    pesan TEXT NOT NULL,
    tipe VARCHAR(50) DEFAULT 'info',
    link VARCHAR(255) DEFAULT NULL,
    sudah_dibaca TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS chat_messages (
    id_message INT AUTO_INCREMENT PRIMARY KEY,
    id_sender INT NOT NULL,
    id_receiver INT NOT NULL,
    pesan TEXT NOT NULL,
    sudah_dibaca TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_sender) REFERENCES users(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_receiver) REFERENCES users(id_user) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS ui_settings (
    id_setting INT AUTO_INCREMENT PRIMARY KEY,
    app_name VARCHAR(100) NOT NULL DEFAULT 'HTML Learn RPL',
    logo_icon VARCHAR(20) NOT NULL DEFAULT '</>',
    primary_color VARCHAR(20) NOT NULL DEFAULT '#4438f2',
    accent_color VARCHAR(20) NOT NULL DEFAULT '#d8ff3e',
    sidebar_color VARCHAR(20) NOT NULL DEFAULT '#151923',
    radius_size INT NOT NULL DEFAULT 30,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO ui_settings (id_setting, app_name, logo_icon, primary_color, accent_color, sidebar_color, radius_size)
SELECT 1, 'HTML Learn RPL', '</>', '#4438f2', '#d8ff3e', '#151923', 30
WHERE NOT EXISTS (SELECT 1 FROM ui_settings WHERE id_setting=1);

INSERT INTO tugas (id_materi, judul_tugas, deskripsi, batas_waktu)
SELECT 
    (SELECT id_materi FROM materi ORDER BY urutan ASC LIMIT 1),
    'Membuat Halaman Profil Sederhana',
    'Buat halaman HTML sederhana yang berisi nama, kelas, deskripsi diri, gambar, dan link sosial media. Gunakan struktur HTML yang rapi.',
    DATE_ADD(NOW(), INTERVAL 7 DAY)
WHERE NOT EXISTS (SELECT 1 FROM tugas WHERE judul_tugas='Membuat Halaman Profil Sederhana');
