USE html_learn_rpl;

-- =========================
-- Tambahan Materi CSS
-- =========================
INSERT INTO materi (judul_materi, deskripsi, isi_materi, contoh_kode, urutan)
SELECT 
'Pengenalan CSS',
'Mengenal fungsi CSS untuk mempercantik tampilan halaman web.',
'CSS adalah singkatan dari Cascading Style Sheets. CSS digunakan untuk mengatur tampilan halaman web, seperti warna, ukuran teks, jarak antar elemen, background, border, dan layout. Jika HTML berfungsi sebagai struktur halaman, maka CSS berfungsi sebagai desain atau tampilan halaman tersebut. Dengan CSS, halaman web menjadi lebih menarik, rapi, dan nyaman dilihat oleh pengguna.',
'<!DOCTYPE html>
<html>
<head>
  <title>Pengenalan CSS</title>
  <style>
    body {
      background-color: #eef2ff;
      font-family: Arial, sans-serif;
    }

    h1 {
      color: #4f46e5;
      text-align: center;
    }

    p {
      color: #374151;
      font-size: 18px;
    }
  </style>
</head>
<body>
  <h1>Belajar CSS</h1>
  <p>CSS digunakan untuk mempercantik tampilan halaman web.</p>
</body>
</html>',
6
WHERE NOT EXISTS (SELECT 1 FROM materi WHERE judul_materi='Pengenalan CSS');

INSERT INTO materi (judul_materi, deskripsi, isi_materi, contoh_kode, urutan)
SELECT 
'Selector dan Properti CSS',
'Mempelajari cara memilih elemen HTML dan memberikan style menggunakan CSS.',
'Selector CSS digunakan untuk memilih elemen HTML yang ingin diberi gaya. Contoh selector adalah nama tag, class, dan id. Properti CSS adalah aturan tampilan yang diberikan pada elemen, seperti color, background-color, font-size, margin, padding, dan border. Class ditulis dengan tanda titik, sedangkan id ditulis dengan tanda pagar.',
'<!DOCTYPE html>
<html>
<head>
  <title>Selector CSS</title>
  <style>
    h1 {
      color: blue;
    }

    .teks-merah {
      color: red;
    }

    #kotak {
      background-color: yellow;
      padding: 15px;
      border-radius: 10px;
    }
  </style>
</head>
<body>
  <h1>Contoh Selector CSS</h1>
  <p class="teks-merah">Ini menggunakan selector class.</p>
  <div id="kotak">Ini menggunakan selector id.</div>
</body>
</html>',
7
WHERE NOT EXISTS (SELECT 1 FROM materi WHERE judul_materi='Selector dan Properti CSS');

INSERT INTO materi (judul_materi, deskripsi, isi_materi, contoh_kode, urutan)
SELECT 
'Box Model CSS',
'Mempelajari konsep margin, border, padding, dan content pada CSS.',
'Box Model adalah konsep dasar CSS yang menjelaskan bahwa setiap elemen HTML dianggap sebagai sebuah kotak. Kotak tersebut terdiri dari content, padding, border, dan margin. Content adalah isi elemen, padding adalah jarak antara isi dan border, border adalah garis tepi, sedangkan margin adalah jarak elemen dengan elemen lain.',
'<!DOCTYPE html>
<html>
<head>
  <title>Box Model CSS</title>
  <style>
    .box {
      width: 250px;
      background-color: #c7d2fe;
      padding: 20px;
      border: 3px solid #4f46e5;
      margin: 30px;
      border-radius: 12px;
    }
  </style>
</head>
<body>
  <div class="box">
    Ini adalah contoh Box Model CSS.
  </div>
</body>
</html>',
8
WHERE NOT EXISTS (SELECT 1 FROM materi WHERE judul_materi='Box Model CSS');

-- =========================
-- Tambahan Materi JavaScript
-- =========================
INSERT INTO materi (judul_materi, deskripsi, isi_materi, contoh_kode, urutan)
SELECT 
'Pengenalan JavaScript',
'Mengenal fungsi JavaScript untuk membuat halaman web menjadi interaktif.',
'JavaScript adalah bahasa pemrograman yang digunakan untuk membuat halaman web menjadi lebih interaktif. Dengan JavaScript, halaman web dapat merespons aksi pengguna, seperti klik tombol, input form, perubahan teks, validasi data, dan manipulasi tampilan secara langsung. Jika HTML adalah struktur dan CSS adalah tampilan, maka JavaScript adalah perilaku atau interaksi pada halaman web.',
'<!DOCTYPE html>
<html>
<head>
  <title>Pengenalan JavaScript</title>
</head>
<body>
  <h1 id="judul">Belajar JavaScript</h1>
  <button onclick="ubahTeks()">Klik Saya</button>

  <script>
    function ubahTeks() {
      document.getElementById("judul").innerHTML = "Teks berhasil diubah!";
    }
  </script>
</body>
</html>',
9
WHERE NOT EXISTS (SELECT 1 FROM materi WHERE judul_materi='Pengenalan JavaScript');

INSERT INTO materi (judul_materi, deskripsi, isi_materi, contoh_kode, urutan)
SELECT 
'DOM dan Event JavaScript',
'Mempelajari cara JavaScript mengubah elemen HTML melalui DOM dan event.',
'DOM atau Document Object Model adalah representasi struktur halaman HTML yang dapat diakses dan diubah menggunakan JavaScript. Event adalah aksi yang terjadi pada halaman web, misalnya tombol diklik, mouse diarahkan, atau input diketik. Dengan DOM dan event, siswa dapat membuat halaman web yang lebih dinamis dan interaktif.',
'<!DOCTYPE html>
<html>
<head>
  <title>DOM dan Event</title>
  <style>
    .aktif {
      background-color: #4f46e5;
      color: white;
      padding: 15px;
      border-radius: 10px;
    }
  </style>
</head>
<body>
  <h1 id="judul">Contoh DOM dan Event</h1>
  <p id="pesan">Klik tombol untuk mengubah tampilan.</p>
  <button id="tombol">Ubah Tampilan</button>

  <script>
    const tombol = document.getElementById("tombol");
    const pesan = document.getElementById("pesan");

    tombol.addEventListener("click", function() {
      pesan.innerHTML = "Tampilan berhasil diubah menggunakan JavaScript!";
      pesan.classList.add("aktif");
    });
  </script>
</body>
</html>',
10
WHERE NOT EXISTS (SELECT 1 FROM materi WHERE judul_materi='DOM dan Event JavaScript');

-- =========================
-- Tambahan Kuis CSS dan JavaScript
-- =========================
SET @css1 = (SELECT id_materi FROM materi WHERE judul_materi='Pengenalan CSS' LIMIT 1);
SET @css2 = (SELECT id_materi FROM materi WHERE judul_materi='Selector dan Properti CSS' LIMIT 1);
SET @css3 = (SELECT id_materi FROM materi WHERE judul_materi='Box Model CSS' LIMIT 1);
SET @js1 = (SELECT id_materi FROM materi WHERE judul_materi='Pengenalan JavaScript' LIMIT 1);
SET @js2 = (SELECT id_materi FROM materi WHERE judul_materi='DOM dan Event JavaScript' LIMIT 1);

INSERT INTO kuis (id_materi, pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar)
SELECT @css1, 'CSS digunakan untuk...', 'Membuat struktur halaman web', 'Mengatur tampilan halaman web', 'Menyimpan database', 'Menghapus file komputer', 'B'
WHERE NOT EXISTS (SELECT 1 FROM kuis WHERE id_materi=@css1 AND pertanyaan='CSS digunakan untuk...');

INSERT INTO kuis (id_materi, pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar)
SELECT @css1, 'Kepanjangan CSS adalah...', 'Computer Style Sheet', 'Cascading Style Sheets', 'Creative System Style', 'Coding Style Script', 'B'
WHERE NOT EXISTS (SELECT 1 FROM kuis WHERE id_materi=@css1 AND pertanyaan='Kepanjangan CSS adalah...');

INSERT INTO kuis (id_materi, pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar)
SELECT @css2, 'Selector class pada CSS ditulis menggunakan tanda...', '#', '.', '/', '*', 'B'
WHERE NOT EXISTS (SELECT 1 FROM kuis WHERE id_materi=@css2 AND pertanyaan='Selector class pada CSS ditulis menggunakan tanda...');

INSERT INTO kuis (id_materi, pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar)
SELECT @css2, 'Properti CSS untuk mengubah warna teks adalah...', 'background', 'font', 'color', 'text-size', 'C'
WHERE NOT EXISTS (SELECT 1 FROM kuis WHERE id_materi=@css2 AND pertanyaan='Properti CSS untuk mengubah warna teks adalah...');

INSERT INTO kuis (id_materi, pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar)
SELECT @css3, 'Bagian box model yang menjadi jarak antara content dan border adalah...', 'margin', 'padding', 'outline', 'display', 'B'
WHERE NOT EXISTS (SELECT 1 FROM kuis WHERE id_materi=@css3 AND pertanyaan='Bagian box model yang menjadi jarak antara content dan border adalah...');

INSERT INTO kuis (id_materi, pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar)
SELECT @css3, 'Margin pada CSS berfungsi untuk...', 'Memberi jarak luar elemen', 'Mengubah warna teks', 'Membuat tombol', 'Menampilkan gambar', 'A'
WHERE NOT EXISTS (SELECT 1 FROM kuis WHERE id_materi=@css3 AND pertanyaan='Margin pada CSS berfungsi untuk...');

INSERT INTO kuis (id_materi, pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar)
SELECT @js1, 'JavaScript digunakan untuk membuat halaman web menjadi...', 'Statis saja', 'Interaktif', 'Tidak bisa dibuka', 'Hanya berisi teks', 'B'
WHERE NOT EXISTS (SELECT 1 FROM kuis WHERE id_materi=@js1 AND pertanyaan='JavaScript digunakan untuk membuat halaman web menjadi...');

INSERT INTO kuis (id_materi, pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar)
SELECT @js1, 'Perintah JavaScript biasanya ditulis di dalam tag...', '<style>', '<script>', '<body-only>', '<css>', 'B'
WHERE NOT EXISTS (SELECT 1 FROM kuis WHERE id_materi=@js1 AND pertanyaan='Perintah JavaScript biasanya ditulis di dalam tag...');

INSERT INTO kuis (id_materi, pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar)
SELECT @js2, 'DOM merupakan singkatan dari...', 'Data Object Method', 'Document Object Model', 'Digital Output Machine', 'Display Object Menu', 'B'
WHERE NOT EXISTS (SELECT 1 FROM kuis WHERE id_materi=@js2 AND pertanyaan='DOM merupakan singkatan dari...');

INSERT INTO kuis (id_materi, pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar)
SELECT @js2, 'Event click terjadi ketika pengguna...', 'Membuka database', 'Menekan tombol mouse pada elemen', 'Menghapus browser', 'Mengubah server', 'B'
WHERE NOT EXISTS (SELECT 1 FROM kuis WHERE id_materi=@js2 AND pertanyaan='Event click terjadi ketika pengguna...');
