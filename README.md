Berikut adalah pembaharuan dokumen **README.md** yang telah diintegrasikan secara menyeluruh dari **Tahap 1 hingga Tahap 20**. Semua fitur baru seperti sistem tema, forum diskusi, impor soal Word, *live chat*, manajemen kelompok, hingga panel *IT Help Desk* telah disusun secara terstruktur, interaktif, dan penuh dengan ikon visual agar menarik saat dipasang di GitHub.

---

# 🌐 HTML Learn RPL - Web LMS Pembelajaran Web Interaktif (v20.0 ✨)

**HTML Learn RPL** adalah platform *Learning Management System* (LMS) berbasis web interaktif yang dikembangkan secara khusus untuk siswa SMK jurusan Rekayasa Perangkat Lunak (RPL). Aplikasi ini memfasilitasi pembelajaran teknologi web dasar—**HTML5, CSS3, dan JavaScript**—dalam satu ekosistem yang seru, modern, dan penuh fitur kelas atas.

Aplikasi ini mengusung konsep visual **Playful Modern UI** dengan kombinasi warna *neon lime*, *electric blue*, *dark panel*, *rounded card*, serta *decorative blob* yang dinamis, didukung oleh mesin kustomisasi tema yang sangat fleksibel.

---

## 🚀 Fitur Utama Berdasarkan Peran

### 🧑‍🎓 Fitur Siswa

* 📊 **Dashboard Pintar:** Menampilkan kemajuan belajar, statistik latihan, rekomendasi materi berikutnya secara otomatis, serta skor game terbaik.
* 📚 **Materi Web Interaktif:** Modul HTML, CSS, dan JavaScript lengkap dengan pencarian materi, navigasi *next/prev*, dan status nilai terbaik.
* 💻 **Super Live Coding:** Editor kode terintegrasi untuk mengeksekusi HTML, CSS, dan JS sekaligus dengan fitur simpan hasil latihan.
* 📝 **Kuis & Penyelarasan Otomatis:** Kuis interaktif dengan umpan balik jawaban benar setelah selesai. Skor $\ge 75$ otomatis menandai materi selesai.
* 🏆 **Sistem Peringkat (Ranking):** Papan klaster nilai dan keaktifan siswa se-kelas.
* 👥 **Kelompok Saya:** Melihat daftar kelompok belajar yang dibentuk oleh guru.
* 📤 **Manajemen Tugas:** Mengunggah tugas individu maupun kelompok dengan pelacakan status nilai/umpan balik dari guru.
* 💬 **Forum Diskusi & Live Chat:** Wadah interaktif untuk berdiskusi per materi dan fitur pesan bantuan terenkapsulasi *bubble chat*.
* 🎮 **Coding Quest Game:** Game edukasi 4 mode (HTML, CSS, JS, Campuran) dengan sistem nyawa (*lives*), level, dan riwayat skor.
* 🎓 **E-Sertifikat:** Sertifikat digital otomatis terbuka dan dapat dicetak jika seluruh modul selesai dengan rata-rata nilai kuis minimal 75.

### 👨‍🏫 Fitur Guru

* 📖 **Kelola Pembelajaran:** Manajemen penuh (Tambah/Edit/Hapus) untuk materi web dan bank soal kuis.
* 📥 **Word Quiz Importer:** Fitur unggah dokumen `.docx` untuk impor soal pilihan ganda otomatis dilengkapi sistem *parsing preview* cerdas.
* 📂 **Kelola Tugas & Kelompok:** Membuat tugas (Individu/Kelompok), membentuk kelompok belajar, dan mengelompokkan tugas siswa berdasarkan status filter.
* 📝 **Evaluasi Fleksibel:** Memantau kode *live coding* siswa, riwayat skor game, grafik progress nilai, dan memberikan umpan balik tugas.
* 🖨️ **Instrumen Penilaian:** Fitur cetak instrumen penilaian produk berbasis skala Likert (1-5).

### 🛠️ Fitur Admin (IT Help Desk & Super UI)

* 👥 **Manajemen Pengguna Terpusat:** Kontrol penuh akun siswa, guru, dan admin dari satu halaman eksekutif.
* 🔑 **IT Help Desk Panel:** Pencarian akun instan, fitur *reset password* kilat, dan tombol *generate* password sementara.
* 👁️ **Password Viewer:** Fitur intip kata sandi lewat ikon mata (*show/hide* `password_plain`) untuk membantu pengguna yang lupa sandi.
* 🎨 **Theme Engine Customizer:** Pengaturan nama aplikasi, logo, jenis/ukuran font, radius panel, hingga kustomisasi palet warna UI global.

---

## 🎨 Pilihan Tema Dashboard (Engine Build v20)

Admin dapat mengubah total estetika visual seluruh pengguna ke dalam beberapa *template* populer:

* 🌟 **Default Learning** (Playful Neon Cyan/Lime)
* 🦅 **One Dark Pro** (Professional Developer Style)
* 🧛 **Dracula Official** (High-Contrast Vampire Palette)
* 🌌 **Tokyo Night** (Neon Cyberpunk Vibe)
* ❄️ **Nord** (Clean Arctic Elegant Blue)
* 🦉 **Night Owl** (Sleek Deep Blue Accent)

---

## 💻 Spesifikasi Teknologi

* **Bahasa Pemrograman:** PHP Native (Struktur Prosedural untuk Pembelajaran) & JavaScript (ES6+)
* **Database:** MySQL / MariaDB
* **Desain UI:** CSS3 Custom Variables (Sistem Tema Dinamis) & Animasi Gooey
* **Library Eksternal:** `ZipArchive` PHP Extension (Wajib aktif untuk Fitur Impor Word)
* **Lingkungan Lokal:** XAMPP v3.3+ (Apache & MySQL)

---

## 📂 Peta Direktori Utama

```text
html-learn-rpl/
│
├── assets/
│   ├── css/
│   │   └── style.css            # Desain inti & komponen gooey toast
│   └── js/
│       ├── main.js
│       ├── gooey-toast.js      # Animasi pop-up notifikasi
│       ├── theme-toggle.js     # Pengatur mode gelap/terang & tema
│       └── password-toggle.js  # Utilitas show/hide password
│
├── config/
│   └── koneksi.php
│
├── database/                   # Berkas skema SQL & migrasi bertahap
│   ├── html_learn_rpl.sql
│   ├── update_tahap_3.sql
│   ├── update_tahap_6_css_js.sql
│   ├── update_tahap_8_game.sql
│   ├── update_tahap_9_kelola_game.sql
│   ├── update_tahap_13_super_features.sql
│   ├── update_tahap_15_admin_helpdesk.sql
│   ├── update_tahap_16_ui_helpdesk_chat.sql
│   ├── update_tahap_18_password_ui_fix.sql
│   └── update_tahap_20_groups_theme.sql
│
├── includes/
│   ├── auth.php
│   ├── header.php
│   ├── footer.php
│   └── ui_settings.php         # Engine penyimpan preferensi gaya dari Admin
│
├── pages/
│   ├── siswa/                  # (Materi, Live Coding, Kuis, Game, Tugas, Forum, Ranking, Kelompok)
│   ├── guru/                   # (Kelola Materi, Kuis, Kelompok, Tugas, Impor Word, Instrumen)
│   └── admin/                  # (Kelola Pengguna/Helpdesk, Pesan, UI Settings, Logs)
│
├── uploads/
│   └── tugas/                  # Tempat penyimpanan berkas unggahan siswa
└── index.php

```

---

## ⚙️ Panduan Instalasi Pertama Kali (Fresh Install)

1. **Siapkan EnvXAMPP:** Pastikan Apache dan MySQL menyala. Pastikan ekstensi `ZipArchive` di `php.ini` Anda telah aktif.
2. **Ekstrak Berkas:** Tempatkan folder hasil ekstrak ke direktori target:
> `C:/xampp/htdocs/html-learn-rpl`  *(atau nama folder `lms_rpl`)*


3. **Konfigurasi Database:**
* Masuk ke `http://localhost/phpmyadmin/`
* Buat basis data bernama `html_learn_rpl`
* Impor berkas dasar: `database/html_learn_rpl.sql`


4. **Jalankan Aplikasi:** Akses tautan URL melalui peramban: `http://localhost/html-learn-rpl/`

---

## 🔄 Prosedur Pembaruan (Kumulatif Tahap 2 s.d 20)

Jika Anda melakukan pembaruan secara bertahap dari repositori ZIP, ikuti langkah-langkah standarisasi struktur berikut:

1. Ekstrak ZIP pembaruan tahap terbaru Anda.
2. Salin dan tempel (*overwrite/replace*) seluruh isi berkas ke dalam target folder instalasi htdocs Anda (`html-learn-rpl` atau `lms_rpl`).
3. **Eksekusi SQL Berurutan (Penting):** Jika pembaruan menyertakan berkas basis data baru, masuk ke phpMyAdmin, pilih database `html_learn_rpl` dan lakukan impor berkas SQL berikut secara berkini:
* 🛑 *Tahap 3:* `database/update_tahap_3.sql`
* 🛑 *Tahap 6:* `database/update_tahap_6_css_js.sql`
* 🛑 *Tahap 8:* `database/update_tahap_8_game.sql`
* 🛑 *Tahap 9:* `database/update_tahap_9_kelola_game.sql`
* 🛑 *Tahap 13:* `database/update_tahap_13_super_features.sql`
* 🛑 *Tahap 15:* `database/update_tahap_15_admin_helpdesk.sql`
* 🛑 *Tahap 16:* `database/update_tahap_16_ui_helpdesk_chat.sql`
* 🛑 *Tahap 18/19:* `database/update_tahap_18_password_ui_fix.sql`
* 🛑 *Tahap 20:* `database/update_tahap_20_groups_theme.sql`


4. Lakukan pembersihan cache peramban dengan menekan kombinasi tombol **Ctrl + F5** agar CSS gaya gooey, penataan sidebar baru, dan script tema terbaca sempurna.

---

## 🔑 Kredensial Akun Pengujian (Demo)

| Peran Pengguna | Email Login | Kata Sandi |
| --- | --- | --- |
| 🧑‍🎓 **Siswa** | `siswa@demo.com` | `123456` |
| 👨‍🏫 **Guru** | `guru@demo.com` | `123456` |
| 🛠️ **Admin** | `admin@demo.com` | `123456` |

---

## 🔒 Catatan Keamanan Aplikasi

> [!WARNING]
> Berkas bawaan pada proyek latihan lokal/skripsi ini menggunakan enkripsi **MD5** dan menyediakan visualisasi kolom `password_plain` pada tabel pengguna guna mendukung fungsionalitas panel darurat *IT Help Desk*.
> Jika platform ini akan diunggah atau diimplementasikan pada lingkungan **Produksi/Hosting Publik**, sangat direkomendasikan untuk mengubah mekanisme penyimpanan kata sandi menggunakan fungsi bawaan PHP standar industri: `password_hash()` dan `password_verify()`.

---

## 📤 Push Proyek ke GitHub

Gunakan rentetan perintah *command-line* berikut untuk mengunggah pembaruan arsitektur aplikasi ini ke repositori Anda sendiri:

```bash
git init
git add .
git commit -m "🚀 Upgrade Major v20: Implementasi Sistem Tema, Manajemen Grup, dan IT Help Desk"
git branch -M main
git remote add origin https://github.com/USERNAME_ANDA/html-learn-rpl.git
git push -u origin main

```
