HTML Learn RPL - Web LMS Pembelajaran HTML Interaktif

Cara menjalankan di XAMPP:
1. Extract folder html-learn-rpl ke folder htdocs.
   Contoh: C:/xampp/htdocs/html-learn-rpl
2. Jalankan Apache dan MySQL di XAMPP.
3. Buka phpMyAdmin.
4. Import file database/html_learn_rpl.sql.
5. Buka browser:
   http://localhost/html-learn-rpl/

Akun demo:
- Siswa: siswa@demo.com / 123456
- Guru : guru@demo.com / 123456
- Admin: admin@demo.com / 123456

Fitur yang sudah ada:
- Landing page
- Login multi role
- Dashboard siswa
- Materi HTML
- Detail materi
- Live coding HTML
- Kuis otomatis
- Riwayat belajar siswa
- Dashboard guru
- Kelola materi
- Kelola kuis
- Laporan nilai
- Dashboard admin
- Kelola pengguna

Catatan:
Password pada versi awal ini menggunakan MD5 agar mudah dipahami untuk proyek latihan.
Untuk versi produksi sebaiknya diganti menggunakan password_hash() dan password_verify().


Update Tahap 2:
- Sidebar aktif sesuai halaman yang sedang dibuka.
- Guru bisa edit materi.
- Guru bisa edit kuis.
- Halaman kelola materi dan kelola kuis dibuat lebih rapi.
- Laporan guru ditingkatkan menjadi laporan progress siswa.
- Kuis siswa menampilkan jawaban benar setelah selesai mengerjakan.
- Tampilan tabel dan tombol aksi diperbaiki.

Cara update ke project lokal:
1. Extract ZIP tahap 2.
2. Copy semua isi folder html-learn-rpl.
3. Paste ke D:/xampp/htdocs/lms_rpl.
4. Pilih Replace/Overwrite jika diminta.
5. Refresh browser.


Update Tahap 3:
- Siswa bisa menyimpan latihan coding dari halaman Live Coding.
- Riwayat siswa menampilkan hasil latihan coding yang sudah disimpan.
- Guru bisa melihat detail progress per siswa.
- Guru bisa melihat kode latihan siswa pada halaman detail siswa.
- Halaman profil ditambahkan untuk siswa, guru, dan admin.
- Sidebar ditambah menu Profil.
- File update database ditambahkan: database/update_tahap_3.sql

Cara update dari Tahap 2 ke Tahap 3:
1. Extract ZIP tahap 3.
2. Copy semua isi folder html-learn-rpl.
3. Paste ke D:/xampp/htdocs/lms_rpl.
4. Pilih Replace/Overwrite jika diminta.
5. Buka phpMyAdmin.
6. Pilih database html_learn_rpl.
7. Import file database/update_tahap_3.sql.
8. Refresh browser dan coba fitur Live Coding.


Update Tahap 4:
- Guru memiliki halaman baru Latihan Siswa untuk memantau semua hasil live coding.
- Laporan guru dapat diexport ke CSV.
- Admin dapat mengedit data pengguna.
- Admin tidak dapat menghapus akun yang sedang dipakai.
- Dashboard siswa menampilkan rekomendasi materi berikutnya.
- Dashboard siswa menampilkan jumlah dan latihan coding terakhir.
- Detail materi memiliki tombol materi sebelumnya dan berikutnya.
- Tampilan role pengguna diberi badge warna berbeda.

Cara update dari Tahap 3 ke Tahap 4:
1. Extract ZIP tahap 4.
2. Copy semua isi folder html-learn-rpl.
3. Paste ke D:/xampp/htdocs/lms_rpl.
4. Pilih Replace/Overwrite jika diminta.
5. Tidak perlu import database baru jika update_tahap_3.sql sudah pernah dijalankan.
6. Refresh browser.


Update Tahap 5:
- Siswa memiliki fitur Sertifikat Belajar.
- Sertifikat hanya terbuka jika seluruh materi selesai dan rata-rata nilai minimal 75.
- Kuis dengan skor minimal 75 otomatis menandai materi sebagai selesai.
- Halaman Materi HTML siswa memiliki fitur pencarian dan status nilai terbaik.
- Ditambahkan halaman Panduan untuk siswa, guru, dan admin.
- Guru memiliki halaman Instrumen Penilaian Produk yang dapat dicetak.
- Instrumen penilaian mengikuti model skala Likert 1 sampai 5.
- Sidebar diperbarui dengan menu Sertifikat, Panduan, dan Instrumen.
- Tidak ada perubahan database baru pada tahap 5.

Cara update dari Tahap 4 ke Tahap 5:
1. Extract ZIP tahap 5.
2. Copy semua isi folder html-learn-rpl.
3. Paste ke D:/xampp/htdocs/lms_rpl.
4. Pilih Replace/Overwrite jika diminta.
5. Tidak perlu import database baru.
6. Refresh browser.


Update Tahap 6:
- Ditambahkan materi CSS:
  1. Pengenalan CSS
  2. Selector dan Properti CSS
  3. Box Model CSS
- Ditambahkan materi JavaScript:
  1. Pengenalan JavaScript
  2. DOM dan Event JavaScript
- Ditambahkan kuis untuk setiap materi CSS dan JavaScript.
- Label beberapa halaman diperbarui dari Materi HTML menjadi Materi Web.
- Landing page diperbarui menjadi HTML, CSS, dan JavaScript.
- Live Coding tetap menggunakan satu editor dan preview, sehingga bisa menjalankan HTML, CSS, dan JavaScript sekaligus.

Cara update dari Tahap 5 ke Tahap 6:
1. Extract ZIP tahap 6.
2. Copy semua isi folder html-learn-rpl.
3. Paste ke D:/xampp/htdocs/lms_rpl.
4. Pilih Replace/Overwrite jika diminta.
5. Buka phpMyAdmin.
6. Pilih database html_learn_rpl.
7. Import file database/update_tahap_6_css_js.sql.
8. Refresh browser.


Update Tahap 7:
- UI diperbarui dengan gaya playful modern seperti referensi:
  neon lime, electric blue, dark panel, rounded card, dan decorative blob.
- Landing page dibuat lebih menarik dengan section HTML, CSS, dan JavaScript.
- Semua panel utama seperti card, dashboard, tabel, form, tombol, sidebar, dan editor mendapat style baru.
- Sidebar menggunakan warna dark dengan active menu hijau neon.
- Dashboard dan halaman materi memiliki card yang lebih hidup dan interaktif.
- Tidak ada perubahan database pada tahap 7.

Cara update dari Tahap 6 ke Tahap 7:
1. Extract ZIP tahap 7.
2. Copy semua isi folder html-learn-rpl.
3. Paste ke D:/xampp/htdocs/lms_rpl.
4. Pilih Replace/Overwrite jika diminta.
5. Tidak perlu import database baru.
6. Refresh browser dengan Ctrl + F5 agar CSS terbaru terbaca.


Update Tahap 8:
- Ditambahkan Game Coding Quest untuk siswa.
- Mode game: HTML, CSS, JavaScript, dan Campuran.
- Game memiliki skor, level, nyawa, feedback jawaban, dan riwayat skor.
- Skor game dapat disimpan ke database.
- Guru memiliki menu Laporan Game untuk melihat skor siswa.
- Dashboard siswa menampilkan skor game terbaik.
- File update database ditambahkan: database/update_tahap_8_game.sql

Cara update dari Tahap 7 ke Tahap 8:
1. Extract ZIP tahap 8.
2. Copy semua isi folder html-learn-rpl.
3. Paste ke D:/xampp/htdocs/lms_rpl.
4. Pilih Replace/Overwrite jika diminta.
5. Buka phpMyAdmin.
6. Pilih database html_learn_rpl.
7. Import file database/update_tahap_8_game.sql.
8. Refresh browser dengan Ctrl + F5.


Update Tahap 9:
- Guru dapat mengelola pertanyaan Game Coding.
- Guru dapat menambah, mengedit, menghapus, mengaktifkan, dan menonaktifkan pertanyaan game.
- Guru dapat mengatur setting game:
  1. Jumlah soal
  2. Jumlah nyawa
  3. Skor per jawaban benar
- Game siswa sekarang mengambil pertanyaan dari database, bukan dari soal hardcode JavaScript.
- Pertanyaan game mendukung mode HTML, CSS, JavaScript, dan Campuran.
- File update database ditambahkan: database/update_tahap_9_kelola_game.sql

Cara update dari Tahap 8 ke Tahap 9:
1. Extract ZIP tahap 9.
2. Copy semua isi folder html-learn-rpl.
3. Paste ke D:/xampp/htdocs/lms_rpl.
4. Pilih Replace/Overwrite jika diminta.
5. Buka phpMyAdmin.
6. Pilih database html_learn_rpl.
7. Import file database/update_tahap_9_kelola_game.sql.
8. Refresh browser dengan Ctrl + F5.
