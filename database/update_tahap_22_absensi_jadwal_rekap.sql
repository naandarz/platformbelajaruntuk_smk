USE html_learn_rpl;

-- ===== Tahap 22: Absensi, Jadwal Kelas, dan Rekap Aktivitas Belajar =====

CREATE TABLE IF NOT EXISTS absensi (
    id_absensi INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    tanggal DATE NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'hadir',
    catatan TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_absensi_user_tanggal (id_user, tanggal),
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS jadwal_kelas (
    id_jadwal INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(200) NOT NULL,
    kategori VARCHAR(50) DEFAULT 'Pembelajaran',
    kelas VARCHAR(100) DEFAULT NULL,
    id_materi INT DEFAULT NULL,
    tanggal DATE NOT NULL,
    jam_mulai TIME DEFAULT NULL,
    jam_selesai TIME DEFAULT NULL,
    keterangan TEXT DEFAULT NULL,
    dibuat_oleh INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_materi) REFERENCES materi(id_materi) ON DELETE SET NULL,
    FOREIGN KEY (dibuat_oleh) REFERENCES users(id_user) ON DELETE SET NULL
);

INSERT INTO jadwal_kelas (judul, kategori, kelas, id_materi, tanggal, jam_mulai, jam_selesai, keterangan, dibuat_oleh)
SELECT 
    'Belajar Struktur Dasar HTML',
    'Pembelajaran',
    'X RPL 1',
    (SELECT id_materi FROM materi ORDER BY urutan ASC LIMIT 1),
    DATE_ADD(CURDATE(), INTERVAL 1 DAY),
    '08:00:00',
    '09:30:00',
    'Jadwal contoh untuk pembelajaran awal SmartLearn.',
    (SELECT id_user FROM users WHERE role IN ('guru','admin') ORDER BY role='guru' DESC LIMIT 1)
WHERE NOT EXISTS (SELECT 1 FROM jadwal_kelas WHERE judul='Belajar Struktur Dasar HTML');

INSERT INTO jadwal_kelas (judul, kategori, kelas, id_materi, tanggal, jam_mulai, jam_selesai, keterangan, dibuat_oleh)
SELECT 
    'Latihan Live Coding CSS',
    'Praktik',
    'X RPL 1',
    (SELECT id_materi FROM materi WHERE judul_materi LIKE '%CSS%' LIMIT 1),
    DATE_ADD(CURDATE(), INTERVAL 3 DAY),
    '10:00:00',
    '11:30:00',
    'Siswa berlatih membuat styling dasar menggunakan CSS.',
    (SELECT id_user FROM users WHERE role IN ('guru','admin') ORDER BY role='guru' DESC LIMIT 1)
WHERE NOT EXISTS (SELECT 1 FROM jadwal_kelas WHERE judul='Latihan Live Coding CSS');
