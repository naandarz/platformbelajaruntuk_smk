USE html_learn_rpl;

CREATE TABLE IF NOT EXISTS game_scores (
    id_score INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    mode_game VARCHAR(50) NOT NULL,
    skor INT NOT NULL,
    level_tercapai INT DEFAULT 1,
    jawaban_benar INT DEFAULT 0,
    total_soal INT DEFAULT 0,
    tanggal_main TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS game_settings (
    id_setting INT AUTO_INCREMENT PRIMARY KEY,
    max_questions INT NOT NULL DEFAULT 10,
    lives INT NOT NULL DEFAULT 3,
    score_per_correct INT NOT NULL DEFAULT 20,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO game_settings (id_setting, max_questions, lives, score_per_correct)
SELECT 1, 10, 3, 20
WHERE NOT EXISTS (SELECT 1 FROM game_settings WHERE id_setting=1);

CREATE TABLE IF NOT EXISTS game_questions (
    id_question INT AUTO_INCREMENT PRIMARY KEY,
    mode_game ENUM('HTML','CSS','JavaScript') NOT NULL,
    pertanyaan TEXT NOT NULL,
    kode TEXT DEFAULT NULL,
    opsi_a VARCHAR(255) NOT NULL,
    opsi_b VARCHAR(255) NOT NULL,
    opsi_c VARCHAR(255) NOT NULL,
    opsi_d VARCHAR(255) NOT NULL,
    jawaban_benar ENUM('A','B','C','D') NOT NULL,
    pembahasan TEXT DEFAULT NULL,
    status ENUM('aktif','nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Seed pertanyaan HTML
INSERT INTO game_questions (mode_game, pertanyaan, kode, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar, pembahasan, status)
SELECT 'HTML', 'Tag HTML untuk membuat paragraf adalah...', '', '<p>', '<h1>', '<img>', '<table>', 'A', 'Tag <p> digunakan untuk membuat paragraf.', 'aktif'
WHERE NOT EXISTS (SELECT 1 FROM game_questions WHERE pertanyaan='Tag HTML untuk membuat paragraf adalah...');

INSERT INTO game_questions (mode_game, pertanyaan, kode, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar, pembahasan, status)
SELECT 'HTML', 'Bagian HTML yang tampil di browser berada di dalam tag...', '', '<head>', '<title>', '<body>', '<meta>', 'C', 'Konten yang tampil di halaman web berada di dalam tag <body>.', 'aktif'
WHERE NOT EXISTS (SELECT 1 FROM game_questions WHERE pertanyaan='Bagian HTML yang tampil di browser berada di dalam tag...');

INSERT INTO game_questions (mode_game, pertanyaan, kode, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar, pembahasan, status)
SELECT 'HTML', 'Tag untuk membuat link adalah...', '', '<a>', '<link>', '<href>', '<url>', 'A', 'Tag <a> digunakan untuk membuat hyperlink.', 'aktif'
WHERE NOT EXISTS (SELECT 1 FROM game_questions WHERE pertanyaan='Tag untuk membuat link adalah...');

INSERT INTO game_questions (mode_game, pertanyaan, kode, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar, pembahasan, status)
SELECT 'HTML', 'Output dari kode berikut adalah...', '<h1>Belajar Web</h1>', 'Teks kecil', 'Judul besar', 'Tabel', 'Gambar', 'B', 'Tag <h1> menampilkan judul utama dengan ukuran besar.', 'aktif'
WHERE NOT EXISTS (SELECT 1 FROM game_questions WHERE pertanyaan='Output dari kode berikut adalah...');

INSERT INTO game_questions (mode_game, pertanyaan, kode, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar, pembahasan, status)
SELECT 'HTML', 'Tag untuk membuat input pada form adalah...', '', '<form>', '<input>', '<label>', '<button>', 'B', 'Tag <input> digunakan untuk membuat kolom input.', 'aktif'
WHERE NOT EXISTS (SELECT 1 FROM game_questions WHERE pertanyaan='Tag untuk membuat input pada form adalah...');

-- Seed pertanyaan CSS
INSERT INTO game_questions (mode_game, pertanyaan, kode, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar, pembahasan, status)
SELECT 'CSS', 'CSS digunakan untuk...', '', 'Mengatur tampilan web', 'Menyimpan database', 'Membuat server', 'Menghapus file', 'A', 'CSS digunakan untuk mengatur tampilan halaman web.', 'aktif'
WHERE NOT EXISTS (SELECT 1 FROM game_questions WHERE pertanyaan='CSS digunakan untuk...');

INSERT INTO game_questions (mode_game, pertanyaan, kode, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar, pembahasan, status)
SELECT 'CSS', 'Selector class pada CSS ditulis menggunakan...', '', '#', '.', '/', '*', 'B', 'Selector class menggunakan tanda titik.', 'aktif'
WHERE NOT EXISTS (SELECT 1 FROM game_questions WHERE pertanyaan='Selector class pada CSS ditulis menggunakan...');

INSERT INTO game_questions (mode_game, pertanyaan, kode, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar, pembahasan, status)
SELECT 'CSS', 'Properti untuk mengubah warna teks adalah...', '', 'background-color', 'font-size', 'color', 'margin', 'C', 'Properti color digunakan untuk mengatur warna teks.', 'aktif'
WHERE NOT EXISTS (SELECT 1 FROM game_questions WHERE pertanyaan='Properti untuk mengubah warna teks adalah...');

INSERT INTO game_questions (mode_game, pertanyaan, kode, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar, pembahasan, status)
SELECT 'CSS', 'Padding pada box model adalah...', '', 'Jarak luar elemen', 'Garis tepi', 'Jarak antara isi dan border', 'Ukuran teks', 'C', 'Padding adalah jarak antara content dan border.', 'aktif'
WHERE NOT EXISTS (SELECT 1 FROM game_questions WHERE pertanyaan='Padding pada box model adalah...');

INSERT INTO game_questions (mode_game, pertanyaan, kode, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar, pembahasan, status)
SELECT 'CSS', 'Kode CSS berikut berfungsi untuk...', 'h1 { text-align: center; }', 'Mengubah warna h1', 'Membuat h1 rata tengah', 'Menghapus h1', 'Membuat h1 menjadi link', 'B', 'text-align: center membuat teks rata tengah.', 'aktif'
WHERE NOT EXISTS (SELECT 1 FROM game_questions WHERE pertanyaan='Kode CSS berikut berfungsi untuk...');

-- Seed pertanyaan JavaScript
INSERT INTO game_questions (mode_game, pertanyaan, kode, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar, pembahasan, status)
SELECT 'JavaScript', 'JavaScript digunakan untuk membuat web menjadi...', '', 'Interaktif', 'Lebih berat saja', 'Tidak bisa dibuka', 'Tanpa tampilan', 'A', 'JavaScript membuat halaman web bisa merespons aksi pengguna.', 'aktif'
WHERE NOT EXISTS (SELECT 1 FROM game_questions WHERE pertanyaan='JavaScript digunakan untuk membuat web menjadi...');

INSERT INTO game_questions (mode_game, pertanyaan, kode, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar, pembahasan, status)
SELECT 'JavaScript', 'Tag untuk menulis JavaScript dalam HTML adalah...', '', '<style>', '<script>', '<js>', '<code>', 'B', 'JavaScript ditulis di dalam tag <script>.', 'aktif'
WHERE NOT EXISTS (SELECT 1 FROM game_questions WHERE pertanyaan='Tag untuk menulis JavaScript dalam HTML adalah...');

INSERT INTO game_questions (mode_game, pertanyaan, kode, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar, pembahasan, status)
SELECT 'JavaScript', 'DOM adalah singkatan dari...', '', 'Document Object Model', 'Data Output Method', 'Digital Object Menu', 'Design Object Mode', 'A', 'DOM adalah Document Object Model.', 'aktif'
WHERE NOT EXISTS (SELECT 1 FROM game_questions WHERE pertanyaan='DOM adalah singkatan dari...');

INSERT INTO game_questions (mode_game, pertanyaan, kode, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar, pembahasan, status)
SELECT 'JavaScript', 'Event click terjadi saat...', '', 'Halaman ditutup', 'Elemen diklik', 'Database dibuat', 'File dihapus', 'B', 'Event click terjadi ketika elemen diklik.', 'aktif'
WHERE NOT EXISTS (SELECT 1 FROM game_questions WHERE pertanyaan='Event click terjadi saat...');

INSERT INTO game_questions (mode_game, pertanyaan, kode, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar, pembahasan, status)
SELECT 'JavaScript', 'Kode berikut digunakan untuk...', 'document.getElementById("judul").innerHTML = "Halo";', 'Mengubah isi elemen', 'Menghapus browser', 'Membuat database', 'Mengatur ukuran layar', 'A', 'innerHTML digunakan untuk mengubah isi elemen HTML.', 'aktif'
WHERE NOT EXISTS (SELECT 1 FROM game_questions WHERE pertanyaan='Kode berikut digunakan untuk...');
