CREATE DATABASE IF NOT EXISTS html_learn_rpl;
USE html_learn_rpl;

DROP TABLE IF EXISTS progres_belajar;
DROP TABLE IF EXISTS nilai;
DROP TABLE IF EXISTS kuis;
DROP TABLE IF EXISTS materi;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('siswa','guru','admin') NOT NULL,
    kelas VARCHAR(50) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE materi (
    id_materi INT AUTO_INCREMENT PRIMARY KEY,
    judul_materi VARCHAR(150) NOT NULL,
    deskripsi TEXT,
    isi_materi TEXT NOT NULL,
    contoh_kode TEXT,
    urutan INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE kuis (
    id_kuis INT AUTO_INCREMENT PRIMARY KEY,
    id_materi INT NOT NULL,
    pertanyaan TEXT NOT NULL,
    opsi_a VARCHAR(255) NOT NULL,
    opsi_b VARCHAR(255) NOT NULL,
    opsi_c VARCHAR(255) NOT NULL,
    opsi_d VARCHAR(255) NOT NULL,
    jawaban_benar ENUM('A','B','C','D') NOT NULL,
    FOREIGN KEY (id_materi) REFERENCES materi(id_materi) ON DELETE CASCADE
);

CREATE TABLE nilai (
    id_nilai INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_materi INT NOT NULL,
    skor INT NOT NULL,
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_materi) REFERENCES materi(id_materi) ON DELETE CASCADE
);

CREATE TABLE progres_belajar (
    id_progres INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_materi INT NOT NULL,
    status ENUM('belum','selesai') DEFAULT 'belum',
    tanggal_selesai TIMESTAMP NULL,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_materi) REFERENCES materi(id_materi) ON DELETE CASCADE,
    UNIQUE KEY unique_progress (id_user, id_materi)
);

INSERT INTO users (nama, email, password, role, kelas) VALUES
('Siswa Demo', 'siswa@demo.com', MD5('123456'), 'siswa', 'X RPL 1'),
('Guru Demo', 'guru@demo.com', MD5('123456'), 'guru', NULL),
('Admin Demo', 'admin@demo.com', MD5('123456'), 'admin', NULL);

INSERT INTO materi (judul_materi, deskripsi, isi_materi, contoh_kode, urutan) VALUES
('Pengenalan HTML', 
 'Mengenal pengertian HTML dan fungsi dasarnya dalam pembuatan halaman web.',
 'HTML adalah singkatan dari HyperText Markup Language. HTML digunakan untuk membuat struktur halaman web. Dalam pembelajaran web, HTML menjadi dasar utama sebelum siswa mempelajari CSS dan JavaScript.',
 '<!DOCTYPE html>\n<html>\n<head>\n  <title>Belajar HTML</title>\n</head>\n<body>\n  <h1>Hello HTML</h1>\n  <p>Ini adalah paragraf pertama saya.</p>\n</body>\n</html>', 
 1),

('Struktur Dasar HTML', 
 'Mempelajari bagian utama dokumen HTML seperti html, head, title, dan body.',
 'Setiap dokumen HTML memiliki struktur dasar. Tag html menjadi pembungkus utama, tag head berisi informasi halaman, sedangkan tag body berisi konten yang ditampilkan di browser.',
 '<!DOCTYPE html>\n<html>\n<head>\n  <title>Judul Halaman</title>\n</head>\n<body>\n  <h1>Selamat Datang</h1>\n</body>\n</html>', 
 2),

('Heading dan Paragraf', 
 'Mempelajari penggunaan tag heading dan paragraf pada HTML.',
 'Heading digunakan untuk membuat judul atau subjudul. HTML memiliki heading dari h1 sampai h6. Paragraf dibuat menggunakan tag p.',
 '<h1>Judul Utama</h1>\n<h2>Sub Judul</h2>\n<p>Ini adalah isi paragraf.</p>', 
 3),

('Hyperlink dan Gambar', 
 'Mempelajari cara membuat link dan menampilkan gambar.',
 'Hyperlink dibuat menggunakan tag a, sedangkan gambar ditampilkan menggunakan tag img. Keduanya sering digunakan dalam pembuatan halaman web.',
 '<a href=\"https://www.google.com\">Buka Google</a>\n<img src=\"gambar.jpg\" alt=\"Contoh Gambar\">', 
 4),

('Tabel HTML', 
 'Mempelajari cara membuat tabel menggunakan tag table, tr, th, dan td.',
 'Tabel digunakan untuk menampilkan data dalam bentuk baris dan kolom. Tag table digunakan sebagai pembungkus, tr untuk baris, th untuk judul kolom, dan td untuk isi data.',
 '<table border=\"1\">\n  <tr><th>Nama</th><th>Kelas</th></tr>\n  <tr><td>Ani</td><td>X RPL</td></tr>\n</table>', 
 5);

INSERT INTO kuis (id_materi, pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar) VALUES
(1, 'HTML merupakan singkatan dari...', 'HyperText Markup Language', 'Home Tool Markup Language', 'Hyperlink Text Main Language', 'High Text Machine Language', 'A'),
(1, 'Tag yang digunakan untuk membuat paragraf adalah...', '<h1>', '<p>', '<img>', '<table>', 'B'),
(2, 'Bagian HTML yang ditampilkan pada browser berada di dalam tag...', '<head>', '<title>', '<body>', '<meta>', 'C'),
(3, 'Tag heading terbesar dalam HTML adalah...', '<h6>', '<head>', '<h1>', '<p>', 'C'),
(5, 'Tag untuk membuat baris pada tabel adalah...', '<td>', '<tr>', '<th>', '<table>', 'B');
