# 🚀 SmartLearn

**Web LMS Pembelajaran HTML, CSS, dan JavaScript Interaktif**

SmartLearn adalah platform *Learning Management System* (LMS) interaktif yang dirancang khusus untuk membantu siswa SMK belajar pemrograman web (HTML, CSS, JavaScript) dengan fitur *live coding*, kuis otomatis, hingga gamifikasi.

---

## 💻 Cara Menjalankan di XAMPP

Ikuti langkah-langkah berikut untuk menjalankan aplikasi di lingkungan lokal Anda:

1. **Extract** folder `smartlearn` ke dalam folder `htdocs` XAMPP Anda.
*(Contoh: `C:/xampp/htdocs/smartlearn` atau `D:/xampp/htdocs/platformbelajaruntuk_smk`)*
2. **Jalankan** modul **Apache** dan **MySQL** pada aplikasi XAMPP Control Panel.
3. Buka browser dan akses **phpMyAdmin** (`http://localhost/phpmyadmin`).
4. Buat database baru bernama `html_learn_rpl` dan **Import** file `database/html_learn_rpl.sql`.
5. Buka aplikasi melalui browser:
* URL: `http://localhost/smartlearn/` atau degan `http://localhost/platformbelajaruntuk_smk/`
* Atau: `http://localhost/platformbelajaruntuk_smk/login.php` (sesuaikan dengan nama folder Anda)



---

## 🔐 Akun Demo

Gunakan kredensial di bawah ini untuk menguji masing-masing *role* yang tersedia:

| Role | Email | Password |
| --- | --- | --- |
| 🎓 **Siswa** | `siswa@demo.com` | `123456` |
| 👨‍🏫 **Guru** | `guru@demo.com` | `123456` |
| ⚙️ **Admin** | `admin@demo.com` | `123456` |

> **⚠️ Catatan Keamanan Penting**
> Password pada versi awal aplikasi ini menggunakan enkripsi `MD5` agar mudah dipahami untuk kebutuhan proyek latihan/skripsi. Untuk versi produksi (daring), sangat disarankan untuk menggantinya menggunakan algoritma `password_hash()` dan `password_verify()`.

---

## ✨ Fitur Utama

Aplikasi ini dilengkapi dengan berbagai fitur yang disesuaikan dengan kebutuhan pembelajaran interaktif:

* **🌐 Umum:** Landing Page responsif, Login Multi-Role (Siswa, Guru, Admin), Mode Tema Dark/Light.
* **🎓 Dasbor Siswa:** Materi Web (HTML, CSS, JS), Live Coding Editor, Kuis Otomatis, Riwayat Belajar, Sertifikat Belajar, Game Coding Quest, Pengumpulan Tugas, Forum Diskusi, Kelompok, Absensi.
* **👨‍🏫 Dasbor Guru:** Kelola Materi & Kuis, Import Soal dari MS Word, Pantau Progress Siswa, Laporan Nilai (Export CSV/PDF), Instrumen Penilaian Produk, Kelola Game, Kelola Tugas & Kelompok, Jadwal Kelas.
* **⚙️ Dasbor Admin:** Kelola Pengguna, IT Help Desk (Reset Password Cepat), Pengaturan UI (Warna, Font, Tema), Monitoring Aktivitas Sistem.

---

## 📦 Riwayat Pembaruan (Changelog)

Berikut adalah catatan rilis pembaruan dari tahap ke tahap beserta cara memperbaruinya ke proyek lokal Anda:

### 🎯 Tahap 2: Peningkatan UI & Laporan

* Sidebar aktif otomatis sesuai halaman.
* Guru dapat mengedit materi dan kuis.
* Tampilan kelola materi, kuis, tabel, dan tombol aksi dirapikan.
* Laporan guru menjadi laporan *progress* siswa secara mendetail.
* Siswa dapat melihat kunci jawaban setelah kuis selesai.
**Cara Update:** Extract ZIP Tahap 2 ➔ Copy semua isi ➔ Paste ke folder `htdocs` (Replace/Overwrite) ➔ Refresh browser.

### 🎯 Tahap 3: Fitur Simpan Live Coding & Profil

* Siswa dapat menyimpan hasil latihan dari *Live Coding*.
* Riwayat belajar menampilkan hasil *coding* yang disimpan.
* Guru bisa melihat detail *progress* dan kode latihan per siswa.
* Penambahan halaman Profil untuk semua *role*.
**Cara Update:** Paste file Tahap 3 ➔ Import database `database/update_tahap_3.sql` ➔ Refresh.

### 🎯 Tahap 4: Manajemen Latihan & Keamanan Akun

* Halaman "Latihan Siswa" untuk guru memantau *live coding*.
* Fitur Export laporan ke CSV untuk guru.
* Admin dapat mengedit data pengguna & dicegah menghapus akun yang sedang aktif.
* Dashboard siswa kini menampilkan rekomendasi materi dan statistik latihan.
**Cara Update:** Paste file Tahap 4 ➔ Refresh browser (Tidak perlu import DB baru).

### 🎯 Tahap 5: Sertifikat & Instrumen Penilaian

* Fitur **Sertifikat Belajar** terbuka otomatis jika nilai rata-rata > 75 dan materi selesai.
* Kuis dengan skor > 75 otomatis menandai materi selesai.
* Fitur pencarian di halaman materi.
* Guru mendapatkan halaman Instrumen Penilaian Produk (Skala Likert 1-5) yang dapat dicetak.
**Cara Update:** Paste file Tahap 5 ➔ Refresh browser (Tidak perlu import DB baru).

### 🎯 Tahap 6: Ekspansi Materi CSS & JavaScript

* Penambahan materi pengenalan, selector, box model (CSS).
* Penambahan materi pengenalan, DOM, event (JavaScript).
* Label materi diubah menjadi "Materi Web".
* Editor *Live Coding* kini mendukung HTML, CSS, dan JS secara bersamaan.
**Cara Update:** Paste file Tahap 6 ➔ Import database `database/update_tahap_6_css_js.sql` ➔ Refresh.

### 🎯 Tahap 7: UI Playful Modern

* Desain dirombak total menggunakan gaya *playful modern* (neon lime, electric blue, dark panel).
* Landing page diperbarui dengan *section* bahasa pemrograman yang interaktif.
**Cara Update:** Paste file Tahap 7 ➔ Refresh browser dengan `Ctrl + F5` (Tidak perlu import DB baru).

### 🎯 Tahap 8 & 9: Game Coding Quest

* Siswa dapat bermain *Game Coding* (Mode HTML, CSS, JS, Campuran) lengkap dengan skor, nyawa, dan level.
* Guru mendapatkan menu "Laporan Game" dan dapat mengelola daftar pertanyaan game dari database.
* Guru dapat mengatur jumlah soal, nyawa, dan skor per jawaban.
**Cara Update:** Paste file Tahap 8 & 9 ➔ Import database `update_tahap_8_game.sql` & `update_tahap_9_kelola_game.sql` ➔ Refresh `Ctrl + F5`.

### 🎯 Tahap 10: Notifikasi Gooey Toast

* Pop-up notifikasi animasi (*gooey/toast*) untuk berbagai aksi seperti login, simpan data, dan skor game.
**Cara Update:** Paste file Tahap 10 ➔ Refresh `Ctrl + F5`.

### 🎯 Tahap 11 & 12: Import Soal MS Word

* Guru dapat mengunggah file `.docx` berisi soal pilihan ganda.
* Sistem otomatis memisahkan nomor, opsi (A-D), dan kunci jawaban, lengkap dengan *preview* sebelum disimpan.
*(Catatan: Ekstensi PHP ZipArchive wajib aktif di XAMPP)*
**Cara Update:** Paste file Tahap 12 ➔ Refresh `Ctrl + F5`.

### 🎯 Tahap 13: Super Features (Ranking, Tugas, Forum)

* **Ranking Siswa:** Tersedia untuk siswa dan guru.
* **Upload Tugas:** Guru dapat memberi tugas & *feedback*, siswa dapat unggah file.
* **Forum Diskusi:** Interaksi antara siswa dan guru.
* **Ekstra:** Mode Dark/Light, Grafik Progress, Export PDF.
**Cara Update:** Paste file Tahap 13 ➔ Import database `update_tahap_13_super_features.sql` ➔ Refresh `Ctrl + F5`.

### 🎯 Tahap 14 & 15: IT Help Desk & Sidebar Fix

* Perbaikan *bug* *scroll* pada *sidebar*.
* Penambahan peran Admin sebagai **IT Help Desk** (Cari akun, Reset password cepat, Edit role).
**Cara Update:** Paste file Tahap 15 ➔ Import database `update_tahap_15_admin_helpdesk.sql` ➔ Refresh `Ctrl + F5`.

### 🎯 Tahap 16 & 17: Pesan Bantuan, UI Setting, Live Chat

* Manajemen tugas dikelompokkan dengan filter status.
* Menu IT Help Desk digabung ke Kelola Pengguna.
* Fitur **Pesan & Bantuan** (Notifikasi otomatis & Live Chat bergaya *bubble*).
* Admin dapat mengubah warna tema, logo, dan radius aplikasi dari dasbor.
**Cara Update:** Paste file Tahap 17 ➔ Import database `update_tahap_16_ui_helpdesk_chat.sql` ➔ Refresh `Ctrl + F5`.

### 🎯 Tahap 18 & 19: Manajemen Password & UI Polish

* Admin dapat melihat *password* sementara (menggunakan ikon mata) untuk membantu pengguna yang lupa sandi.
* Perbaikan UI Kelola Pengguna dan *bubble chat*.
**Cara Update:** Paste file Tahap 19 ➔ Import database `update_tahap_18_password_ui_fix.sql` ➔ Refresh `Ctrl + F5`.

### 🎯 Tahap 20: Manajemen Kelompok & Template UI

* Guru dapat membuat kelompok dan memberikan tugas kelompok/individu.
* Tambahan 6 template UI bagi Admin (Default, One Dark Pro, Dracula, dll).
**Cara Update:** Paste file Tahap 20 ➔ Import database `update_tahap_20_groups_theme.sql` ➔ Refresh `Ctrl + F5`.

### 🎯 Tahap 21: SmartLearn Clean UI

* Nama aplikasi resmi menjadi **SmartLearn**.
* Desain dirombak menjadi gaya *SaaS dashboard* yang *clean* dan minimalis.
**Cara Update:** Paste file Tahap 21 ➔ Import database `update_tahap_21_smartlearn_clean_ui.sql` ➔ Refresh `Ctrl + F5`.

### 🎯 Tahap 22: Absensi, Jadwal, & Rekap Aktivitas

* **Absensi:** Siswa klik hadir harian, guru/admin mendapat rekap data.
* **Jadwal Kelas:** Pembuatan jadwal pembelajaran/tugas tertarget per kelas.
* **Rekap Aktivitas:** Dasbor khusus memantau seluruh aktivitas harian siswa.
**Cara Update:** Paste file Tahap 22 ➔ Import database `update_tahap_22_absensi_jadwal_rekap.sql` ➔ Refresh `Ctrl + F5`.

### 🎯 Tahap 23: Mobile Optimization & PWA

* UI 100% responsif untuk Android & iOS (Hamburger menu, *horizontal scroll* tabel, *touch-friendly*).
* Dukungan *Progressive Web App* (PWA) untuk instalasi aplikasi ke layar utama *smartphone*.
**Cara Update:** Paste file Tahap 23 ➔ (Opsional) Import `update_tahap_23_mobile_pwa.sql` ➔ Refresh `Ctrl + F5`.

### 🎯 Tahap 24: Stitch Syntactic Intelligence UI

* Penyesuaian UI ke gaya modern dengan referensi *Stitch Syntactic Intelligence*.
* Penggunaan ikon Material Symbols, warna sekunder *cyan*, dan tata letak dasbor yang lebih informatif (Card statistik, panel aktivitas, grafik dinamis).
**Cara Update:** Paste file Tahap 24 ➔ Import `update_tahap_24_stitch_ui.sql` ➔ Refresh browser dengan `Ctrl + F5`.
