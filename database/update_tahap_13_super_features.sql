USE html_learn_rpl;

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

CREATE TABLE IF NOT EXISTS forum_topics (
    id_topic INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    role VARCHAR(20) NOT NULL,
    judul VARCHAR(200) NOT NULL,
    isi TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS forum_replies (
    id_reply INT AUTO_INCREMENT PRIMARY KEY,
    id_topic INT NOT NULL,
    id_user INT NOT NULL,
    role VARCHAR(20) NOT NULL,
    isi TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_topic) REFERENCES forum_topics(id_topic) ON DELETE CASCADE,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE
);

INSERT INTO tugas (id_materi, judul_tugas, deskripsi, batas_waktu)
SELECT 
    (SELECT id_materi FROM materi ORDER BY urutan ASC LIMIT 1),
    'Membuat Halaman Profil Sederhana',
    'Buat halaman HTML sederhana yang berisi nama, kelas, deskripsi diri, gambar, dan link sosial media. Gunakan struktur HTML yang rapi.',
    DATE_ADD(NOW(), INTERVAL 7 DAY)
WHERE NOT EXISTS (SELECT 1 FROM tugas WHERE judul_tugas='Membuat Halaman Profil Sederhana');

INSERT INTO tugas (id_materi, judul_tugas, deskripsi, batas_waktu)
SELECT 
    (SELECT id_materi FROM materi WHERE judul_materi LIKE '%CSS%' LIMIT 1),
    'Desain Kartu Produk dengan CSS',
    'Buat tampilan card produk menggunakan HTML dan CSS. Minimal berisi gambar, judul, deskripsi, harga, dan tombol.',
    DATE_ADD(NOW(), INTERVAL 10 DAY)
WHERE NOT EXISTS (SELECT 1 FROM tugas WHERE judul_tugas='Desain Kartu Produk dengan CSS');

INSERT INTO forum_topics (id_user, role, judul, isi)
SELECT 
    (SELECT id_user FROM users WHERE role='guru' LIMIT 1),
    'guru',
    'Selamat datang di forum diskusi',
    'Gunakan forum ini untuk bertanya tentang materi HTML, CSS, JavaScript, kuis, game coding, atau tugas.'
WHERE NOT EXISTS (SELECT 1 FROM forum_topics WHERE judul='Selamat datang di forum diskusi');
