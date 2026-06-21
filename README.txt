SmartLearn - Web LMS Pembelajaran HTML Interaktif

Cara menjalankan di XAMPP:
1. Extract folder smartlearn ke folder htdocs.
   Contoh: C:/xampp/htdocs/smartlearn
2. Jalankan Apache dan MySQL di XAMPP.
3. Buka phpMyAdmin.
4. Import file database/html_learn_rpl.sql.
5. Buka browser:
   http://localhost/smartlearn/

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
2. Copy semua isi folder smartlearn.
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
2. Copy semua isi folder smartlearn.
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
2. Copy semua isi folder smartlearn.
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
2. Copy semua isi folder smartlearn.
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
2. Copy semua isi folder smartlearn.
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
2. Copy semua isi folder smartlearn.
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
2. Copy semua isi folder smartlearn.
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
2. Copy semua isi folder smartlearn.
3. Paste ke D:/xampp/htdocs/lms_rpl.
4. Pilih Replace/Overwrite jika diminta.
5. Buka phpMyAdmin.
6. Pilih database html_learn_rpl.
7. Import file database/update_tahap_9_kelola_game.sql.
8. Refresh browser dengan Ctrl + F5.


Update Tahap 10:
- Ditambahkan popup notifikasi animasi bergaya gooey/toast.
- Toast muncul otomatis saat:
  1. Login berhasil
  2. Login gagal
  3. Data berhasil ditambah
  4. Data berhasil diedit
  5. Data berhasil dihapus
  6. Setting berhasil diperbarui
  7. Skor game tersimpan
  8. Materi ditandai selesai
- File baru:
  assets/js/gooey-toast.js
- CSS toast ditambahkan di assets/css/style.css.
- Tidak ada perubahan database pada tahap 10.

Cara update dari Tahap 9 ke Tahap 10:
1. Extract ZIP tahap 10.
2. Copy semua isi folder smartlearn.
3. Paste ke D:/xampp/htdocs/lms_rpl.
4. Pilih Replace/Overwrite jika diminta.
5. Tidak perlu import database baru.
6. Refresh browser dengan Ctrl + F5.


Update Tahap 11:
- Ditambahkan fitur Import Soal Word untuk guru.
- Guru dapat mengupload dokumen .docx berisi soal pilihan ganda.
- Sistem membaca format:
  1. Pertanyaan
  A. Opsi A
  B. Opsi B
  C. Opsi C
  D. Opsi D
  Jawaban: A
- Sistem menampilkan preview soal sebelum dimasukkan ke database.
- Soal valid dapat langsung diimport menjadi kuis sesuai materi yang dipilih.
- Soal yang formatnya belum lengkap akan diberi tanda Perlu Dicek.
- Tidak ada perubahan database baru pada tahap 11.
- Catatan: membutuhkan ekstensi PHP ZipArchive aktif di XAMPP.

Cara update dari Tahap 10 ke Tahap 11:
1. Extract ZIP tahap 11.
2. Copy semua isi folder smartlearn.
3. Paste ke D:/xampp/htdocs/lms_rpl.
4. Pilih Replace/Overwrite jika diminta.
5. Tidak perlu import database baru.
6. Refresh browser dengan Ctrl + F5.
7. Login sebagai guru dan buka menu Import Soal Word.


Update Tahap 12:
- Memperbaiki bug Import Soal Word.
- Sebelumnya, jika Word membaca opsi dalam satu paragraf seperti:
  Apa fungsi HTML?A. ...B. ...C. ...D. ...Jawaban: C
  maka opsi tidak terbaca.
- Parser sekarang otomatis memisahkan:
  1. Nomor soal
  2. Opsi A, B, C, D
  3. Jawaban/Kunci/Answer/ANS
- Tidak ada perubahan database baru pada tahap 12.

Cara update dari Tahap 11 ke Tahap 12:
1. Extract ZIP tahap 12.
2. Copy semua isi folder smartlearn.
3. Paste ke D:/xampp/htdocs/lms_rpl atau D:/xampp/htdocs/platformbelajaruntuk_smk.
4. Pilih Replace/Overwrite jika diminta.
5. Tidak perlu import database baru.
6. Refresh browser dengan Ctrl + F5.


Update Tahap 13:
- Ditambahkan fitur Ranking Siswa untuk siswa dan guru.
- Ditambahkan fitur Upload Tugas Siswa.
- Guru dapat membuat tugas, melihat pengumpulan, dan memberikan nilai/feedback.
- Ditambahkan fitur Forum Diskusi untuk siswa dan guru.
- Ditambahkan dashboard grafik progress/nilai.
- Ditambahkan tombol Export PDF menggunakan fitur print browser.
- Ditambahkan mode tema dark/light.
- File baru:
  assets/js/theme-toggle.js
  pages/siswa/ranking.php
  pages/siswa/tugas.php
  pages/siswa/forum.php
  pages/siswa/forum_detail.php
  pages/guru/ranking.php
  pages/guru/kelola_tugas.php
  pages/guru/forum.php
  pages/guru/forum_detail.php
  database/update_tahap_13_super_features.sql
- Folder upload:
  uploads/tugas/
- Wajib import database:
  database/update_tahap_13_super_features.sql

Cara update dari Tahap 12 ke Tahap 13:
1. Extract ZIP tahap 13.
2. Copy semua isi folder smartlearn.
3. Paste ke D:/xampp/htdocs/lms_rpl atau D:/xampp/htdocs/platformbelajaruntuk_smk.
4. Pilih Replace/Overwrite jika diminta.
5. Buka phpMyAdmin.
6. Pilih database html_learn_rpl.
7. Import file database/update_tahap_13_super_features.sql.
8. Refresh browser dengan Ctrl + F5.


Update Tahap 14:
- Memperbaiki bug sidebar/menu yang terlihat kosong atau terkena background saat halaman discroll ke bawah.
- Sidebar sekarang memiliki tinggi tetap 100vh dan scroll sendiri.
- Menu bagian bawah seperti Bantuan, Profil, Logout, dan tombol Tema tetap berada di dalam background sidebar.
- Tidak ada perubahan database baru pada tahap 14.

Cara update dari Tahap 13 ke Tahap 14:
1. Extract ZIP tahap 14.
2. Copy semua isi folder smartlearn.
3. Paste ke D:/xampp/htdocs/lms_rpl atau D:/xampp/htdocs/platformbelajaruntuk_smk.
4. Pilih Replace/Overwrite jika diminta.
5. Tidak perlu import database baru.
6. Refresh browser dengan Ctrl + F5.


Update Tahap 15:
- Ditambahkan fitur Admin IT Help Desk.
- Admin dapat mencari akun berdasarkan nama, email, kelas, dan role.
- Admin dapat reset password akun siswa, guru, atau admin.
- Admin dapat menggunakan password cepat seperti 123456, rpl12345, atau smkbisa123.
- Admin dapat mengedit cepat nama, kelas, dan role akun.
- Sistem mencatat riwayat tindakan help desk.
- Dashboard admin ditambahkan shortcut IT Help Desk.
- File baru:
  pages/admin/helpdesk.php
  database/update_tahap_15_admin_helpdesk.sql
- Wajib import database:
  database/update_tahap_15_admin_helpdesk.sql

Cara update dari Tahap 14 ke Tahap 15:
1. Extract ZIP tahap 15.
2. Copy semua isi folder smartlearn.
3. Paste ke D:/xampp/htdocs/lms_rpl atau D:/xampp/htdocs/platformbelajaruntuk_smk.
4. Pilih Replace/Overwrite jika diminta.
5. Buka phpMyAdmin.
6. Pilih database html_learn_rpl.
7. Import file database/update_tahap_15_admin_helpdesk.sql.
8. Refresh browser dengan Ctrl + F5.
9. Login sebagai admin dan buka menu IT Help Desk.


Update Tahap 16:
- Merapikan Kelola Tugas guru agar pengumpulan tidak terlalu panjang.
- Pengumpulan tugas sekarang dikelompokkan berdasarkan tugas dan bisa difilter:
  1. Belum Dinilai
  2. Sudah Dinilai
  3. Semua
  4. Berdasarkan Materi
- Menu IT Help Desk admin digabung ke Kelola Pengguna.
- Admin dapat tambah akun, edit cepat akun, reset password, hapus akun, dan melihat riwayat bantuan akun dari satu halaman.
- Ditambahkan fitur Pesan & Bantuan untuk siswa, guru, dan admin.
- Pesan & Bantuan berisi:
  1. Notifikasi
  2. Live chat
  3. Riwayat percakapan
- Notifikasi otomatis muncul saat:
  1. Admin membuat akun
  2. Admin mengubah akun
  3. Admin reset password
  4. Guru membuat tugas baru
  5. Siswa mengumpulkan tugas
  6. Guru memberi nilai tugas
- Admin dapat mengatur UI:
  1. Nama aplikasi
  2. Icon/logo singkat
  3. Warna utama
  4. Warna aksen
  5. Warna sidebar
  6. Radius panel
- UI dirapikan mengikuti prinsip konsistensi, feedback informatif, pencegahan error, kontrol pengguna, dan mengurangi beban informasi.
- File baru:
  includes/ui_settings.php
  assets/js/theme-toggle.js sudah tetap digunakan
  pages/siswa/pesan.php
  pages/guru/pesan.php
  pages/admin/pesan.php
  pages/admin/ui_settings.php
  database/update_tahap_16_ui_helpdesk_chat.sql
- Wajib import database:
  database/update_tahap_16_ui_helpdesk_chat.sql

Cara update dari Tahap 15 ke Tahap 16:
1. Extract ZIP tahap 16.
2. Copy semua isi folder smartlearn.
3. Paste ke D:/xampp/htdocs/lms_rpl atau D:/xampp/htdocs/platformbelajaruntuk_smk.
4. Pilih Replace/Overwrite jika diminta.
5. Buka phpMyAdmin.
6. Pilih database html_learn_rpl.
7. Import file database/update_tahap_16_ui_helpdesk_chat.sql.
8. Refresh browser dengan Ctrl + F5.
9. Login sebagai admin dan cek Kelola Pengguna, Pesan Bantuan, dan Pengaturan UI.


Update Tahap 17:
- Memperbaiki tampilan Pesan & Bantuan yang sebelumnya belum rapi.
- Pesan chat sekarang tampil sebagai bubble chat yang jelas.
- Chat dari diri sendiri berada di kanan dengan warna aksen.
- Chat dari pengguna lain berada di kiri.
- Area chat memiliki tinggi tetap dan scroll sendiri.
- Notifikasi dan riwayat chat dipisah dalam panel kiri.
- Live chat berada di panel kanan agar alur lebih jelas.
- Tidak ada perubahan database baru pada tahap 17.

Cara update dari Tahap 16 ke Tahap 17:
1. Extract ZIP tahap 17.
2. Copy semua isi folder smartlearn.
3. Paste ke D:/xampp/htdocs/lms_rpl atau D:/xampp/htdocs/platformbelajaruntuk_smk.
4. Pilih Replace/Overwrite jika diminta.
5. Tidak perlu import database baru.
6. Refresh browser dengan Ctrl + F5.


Update Tahap 18:
- Admin dapat melihat password sementara/terakhir diset untuk setiap user di menu Kelola Pengguna.
- Ditambahkan icon mata untuk menampilkan dan menyembunyikan password.
- Form tambah akun dan reset password memiliki tombol show/hide password.
- Ditambahkan tombol generate password sementara.
- Password baru yang dibuat/reset admin akan tersimpan pada kolom password_plain agar admin help desk bisa membantu siswa/guru yang lupa password.
- Riwayat Help Desk dibuat lebih ringkas, hanya menampilkan beberapa aktivitas terbaru.
- Ditambahkan halaman "Lihat Semua Riwayat Help Desk" dengan filter dan export PDF.
- Riwayat Belajar siswa dibuat lebih ringkas dengan tombol "Lihat Semua Riwayat".
- Beberapa UI diperhalus agar card, tabel, dan teks lebih nyaman dibaca.
- File baru:
  assets/js/password-toggle.js
  pages/admin/helpdesk_logs.php
  pages/siswa/riwayat_semua.php
  database/update_tahap_18_password_ui_fix.sql
- Wajib import database:
  database/update_tahap_18_password_ui_fix.sql

Catatan keamanan:
- Fitur melihat password dibuat untuk kebutuhan project lokal/skripsi dan peran admin sebagai IT Help Desk.
- Untuk aplikasi produksi nyata, password sebaiknya tidak disimpan dalam bentuk teks biasa.

Cara update dari Tahap 17 ke Tahap 18:
1. Extract ZIP tahap 18.
2. Copy semua isi folder smartlearn.
3. Paste ke D:/xampp/htdocs/lms_rpl atau D:/xampp/htdocs/platformbelajaruntuk_smk.
4. Pilih Replace/Overwrite jika diminta.
5. Buka phpMyAdmin.
6. Pilih database html_learn_rpl.
7. Import file database/update_tahap_18_password_ui_fix.sql.
8. Refresh browser dengan Ctrl + F5.
9. Login sebagai admin dan buka Kelola Pengguna.


Update Tahap 19:
- Memperbaiki UI Kelola Pengguna & Help Desk yang sempat tampil aneh karena password_plain belum aman.
- Halaman Kelola Pengguna ditulis ulang lebih rapi:
  1. Tambah akun
  2. Cari/filter akun
  3. Lihat password sementara
  4. Show/hide password dengan icon mata
  5. Reset password
  6. Edit cepat akun
  7. Riwayat Help Desk ringkas
- Kolom password_plain akan dibuat otomatis dari halaman Kelola Pengguna jika belum ada.
- Ditambahkan halaman Lihat Semua Riwayat Help Desk.
- Pesan & Bantuan siswa/guru/admin ditulis ulang agar bubble chat dan panel notifikasi tampil rapi.
- Kelola Tugas guru dipoles lagi dan filter default diubah ke Semua agar data pengumpulan tidak terlihat kosong setelah dinilai.
- CSS UI diperbaiki untuk card, chat, password viewer, dan monitoring tugas.
- File baru/diubah:
  assets/js/password-toggle.js
  pages/admin/kelola_pengguna.php
  pages/admin/helpdesk_logs.php
  pages/siswa/pesan.php
  pages/guru/pesan.php
  pages/admin/pesan.php
  pages/guru/kelola_tugas.php
  database/update_tahap_18_password_ui_fix.sql tetap digunakan.
- Database tambahan yang tetap perlu ada:
  database/update_tahap_18_password_ui_fix.sql

Cara update dari Tahap 18 ke Tahap 19:
1. Extract ZIP tahap 19.
2. Copy semua isi folder smartlearn.
3. Paste ke D:/xampp/htdocs/lms_rpl atau D:/xampp/htdocs/platformbelajaruntuk_smk.
4. Pilih Replace/Overwrite jika diminta.
5. Import database/update_tahap_18_password_ui_fix.sql jika belum pernah.
6. Refresh browser dengan Ctrl + F5.


Update Tahap 20:
- Memperbaiki UI siswa agar lebih konsisten dan nyaman.
- Halaman siswa seperti Pesan, Tugas, Riwayat, dan Kelompok memakai komponen card yang lebih rapi.
- Ditambahkan fitur Kelompok:
  1. Guru dapat membuat kelompok.
  2. Guru dapat menambah anggota kelompok.
  3. Guru dapat menghapus anggota/kelompok.
  4. Siswa dapat melihat Kelompok Saya.
- Ditambahkan kategori tugas individu dan tugas kelompok.
- Guru dapat memilih tipe tugas:
  1. Individu
  2. Kelompok
- Jika tugas kelompok dipilih, guru dapat memilih target kelompok.
- Siswa dapat melihat label tugas individu/kelompok di halaman Tugas.
- Pengaturan UI admin diperluas:
  1. Jenis font
  2. Ukuran font
  3. Warna teks
  4. Warna judul
  5. Warna panel
  6. Warna utama
  7. Warna aksen
  8. Warna sidebar
  9. Radius panel
- Ditambahkan template tema:
  1. Default Learning
  2. One Dark Pro
  3. Dracula Official
  4. Tokyo Night
  5. Nord
  6. Night Owl
- File baru:
  pages/guru/kelola_kelompok.php
  pages/siswa/kelompok.php
  database/update_tahap_20_groups_theme.sql
- Wajib import database:
  database/update_tahap_20_groups_theme.sql

Cara update dari Tahap 19 ke Tahap 20:
1. Extract ZIP tahap 20.
2. Copy semua isi folder smartlearn.
3. Paste ke D:/xampp/htdocs/lms_rpl atau D:/xampp/htdocs/platformbelajaruntuk_smk.
4. Pilih Replace/Overwrite jika diminta.
5. Buka phpMyAdmin.
6. Pilih database html_learn_rpl.
7. Import file database/update_tahap_20_groups_theme.sql.
8. Refresh browser dengan Ctrl + F5.
9. Cek menu Guru: Kelola Kelompok dan Kelola Tugas.
10. Cek menu Siswa: Kelompok Saya dan Tugas.
11. Cek menu Admin: Pengaturan UI.


Update Tahap 21:
- Nama web diubah menjadi SmartLearn.
- Tampilan UI diubah menjadi clean dan simple seperti dashboard SaaS modern.
- Sidebar dibuat lebih minimal, terang, rapi, dan nyaman dibaca.
- Card, tombol, form, tabel, chat, dan panel dibuat lebih soft dengan border tipis dan shadow ringan.
- Warna default diubah menjadi:
  Primary: #5b6ee1
  Accent: #d8ff3e
  Sidebar: #ffffff
  Background: #f5f6fb
- Pengaturan UI admin ditambah template baru:
  SmartLearn Clean
- Template editor lain tetap tersedia:
  One Dark Pro
  Dracula Official
  Tokyo Night
  Nord
  Night Owl
- File baru:
  database/update_tahap_21_smartlearn_clean_ui.sql
- Wajib import database:
  database/update_tahap_21_smartlearn_clean_ui.sql

Cara update dari Tahap 20 ke Tahap 21:
1. Extract ZIP tahap 21.
2. Copy semua isi folder smartlearn.
3. Paste ke D:/xampp/htdocs/lms_rpl atau D:/xampp/htdocs/platformbelajaruntuk_smk.
4. Pilih Replace/Overwrite jika diminta.
5. Buka phpMyAdmin.
6. Pilih database html_learn_rpl.
7. Import file database/update_tahap_21_smartlearn_clean_ui.sql.
8. Refresh browser dengan Ctrl + F5.
9. Login sebagai admin dan buka Pengaturan UI.
10. Pilih template SmartLearn Clean jika belum aktif.


Update Tahap 22:
- Ditambahkan fitur Absensi Siswa.
  1. Siswa dapat klik hadir setiap hari.
  2. Siswa dapat menulis catatan absensi.
  3. Siswa dapat melihat riwayat absensi.
  4. Guru dan admin dapat melihat rekap absensi berdasarkan tanggal, kelas, dan status.
- Ditambahkan fitur Jadwal Kelas.
  1. Guru dan admin dapat membuat jadwal pembelajaran, praktik, kuis, tugas, atau diskusi.
  2. Jadwal dapat ditargetkan untuk semua kelas atau kelas tertentu.
  3. Jadwal dapat dikaitkan dengan materi.
  4. Siswa dapat melihat jadwal sesuai kelasnya.
  5. Saat jadwal dibuat, siswa mendapat notifikasi.
- Ditambahkan Dashboard Rekap Aktivitas Belajar.
  1. Guru dan admin dapat melihat rekap aktivitas siswa.
  2. Rekap berisi progress materi, nilai kuis, tugas terkumpul, live coding, absensi, dan skor game.
  3. Rekap dapat difilter berdasarkan kelas dan nama/email siswa.
  4. Disediakan grafik top progress materi.
- Dashboard siswa ditambahkan ringkasan jadwal terdekat dan status absensi hari ini.
- Dashboard guru/admin ditambahkan shortcut menuju absensi, jadwal, dan rekap aktivitas.
- File baru:
  includes/tahap22_bootstrap.php
  pages/siswa/absensi.php
  pages/siswa/jadwal.php
  pages/guru/absensi.php
  pages/guru/jadwal.php
  pages/guru/rekap_aktivitas.php
  pages/admin/absensi.php
  pages/admin/jadwal.php
  pages/admin/rekap_aktivitas.php
  database/update_tahap_22_absensi_jadwal_rekap.sql
- Wajib import database:
  database/update_tahap_22_absensi_jadwal_rekap.sql

Cara update dari Tahap 21 ke Tahap 22:
1. Extract ZIP tahap 22.
2. Copy semua isi folder smartlearn.
3. Paste ke D:/xampp/htdocs/platformbelajaruntuk_smk.
4. Pilih Replace/Overwrite jika diminta.
5. Buka phpMyAdmin.
6. Pilih database html_learn_rpl.
7. Import file database/update_tahap_22_absensi_jadwal_rekap.sql.
8. Refresh browser dengan Ctrl + F5.
9. Login sebagai siswa, guru, dan admin untuk menguji fitur.


Update Tahap 23:
- Ditambahkan optimasi mobile responsive untuk Android dan iOS.
- Sidebar berubah menjadi hamburger menu pada layar kecil.
- Sidebar mobile bisa dibuka/tutup dan tertutup otomatis setelah menu diklik.
- Layout dashboard, card, form, tabel, chat, tugas, absensi, jadwal, dan rekap dibuat lebih aman untuk layar HP.
- Tabel panjang diberi horizontal scroll agar tidak merusak layout.
- Tombol, input, select, textarea dibuat lebih touch-friendly.
- Topbar dibuat lebih ringkas untuk layar kecil.
- Ditambahkan dukungan PWA:
  1. manifest.webmanifest
  2. service-worker.js
  3. icon aplikasi SmartLearn ukuran 72 sampai 512 px
  4. meta tag mobile web app
  5. dukungan Add to Home Screen di Android/iOS
- File baru:
  includes/pwa_head.php
  assets/js/mobile-menu.js
  assets/icons/icon-72.png
  assets/icons/icon-96.png
  assets/icons/icon-128.png
  assets/icons/icon-144.png
  assets/icons/icon-152.png
  assets/icons/icon-192.png
  assets/icons/icon-384.png
  assets/icons/icon-512.png
  manifest.webmanifest
  service-worker.js
  database/update_tahap_23_mobile_pwa.sql
- Database:
  Tahap 23 tidak menambahkan tabel baru. Import update_tahap_23_mobile_pwa.sql bersifat opsional.

Cara update dari Tahap 22 ke Tahap 23:
1. Extract ZIP tahap 23.
2. Copy semua isi folder smartlearn.
3. Paste ke D:/xampp/htdocs/platformbelajaruntuk_smk.
4. Pilih Replace/Overwrite jika diminta.
5. Tidak wajib import database baru.
6. Jika ingin memastikan nama dan tema SmartLearn aktif, import database/update_tahap_23_mobile_pwa.sql.
7. Refresh browser dengan Ctrl + F5.
8. Uji tampilan di Inspect > Toggle Device Toolbar.
