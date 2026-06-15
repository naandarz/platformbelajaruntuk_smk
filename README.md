Berikut adalah versi README yang sudah diperbarui. Saya telah menambahkan berbagai ikon emoji, merapikan struktur menggunakan tabel untuk bagian yang relevan (seperti akun demo), dan mengoptimalkan formatnya agar lebih profesional, modern, dan mudah dibaca di GitHub.

---

# 🚀 Platform Belajar Web untuk SMK

**Platform Belajar untuk SMK** adalah aplikasi pembelajaran berbasis web interaktif yang dirancang khusus untuk membantu siswa SMK jurusan Rekayasa Perangkat Lunak (RPL) dalam menguasai dasar-dasar pemrograman web, berfokus pada materi **HTML, CSS, dan JavaScript**.

Aplikasi ini tidak hanya sekadar e-learning biasa, tetapi dilengkapi dengan fitur *live coding*, *game edukasi*, hingga manajemen kelas yang lengkap untuk guru dan admin! 🌟

---

## 🎨 Tampilan dan Konsep

Platform ini mengusung konsep **UI/UX yang modern, playful, dan interaktif** untuk menjaga semangat belajar siswa. Menggunakan skema warna yang identik dengan dunia teknologi: kombinasi **Biru**, **Ungu**, **Hijau Neon**, yang dibalut dengan tema **Dark Panel**.

---

## ✨ Fitur Utama

### 🎓 Fitur Siswa

* 🔐 Login ke portal siswa.
* 📊 Melihat *dashboard progress* belajar secara *real-time*.
* 📚 Membaca materi interaktif (HTML, CSS, JavaScript).
* 💻 Mencoba kode langsung melalui fitur **Live Coding**.
* 💾 Menyimpan hasil latihan coding.
* 📝 Mengerjakan kuis evaluasi.
* 📈 Memantau riwayat nilai dan latihan.
* 🎮 Bermain **Game Edukasi: Coding Quest**.
* 🎓 Mencetak **Sertifikat** setelah menyelesaikan semua pembelajaran.

### 👨‍🏫 Fitur Guru

* 🔐 Login ke portal guru.
* 📖 Mengelola materi pembelajaran (Tambah/Edit/Hapus).
* 📋 Mengelola bank soal kuis.
* 📊 Memantau laporan nilai seluruh siswa.
* review Mengecek langsung hasil *live coding* siswa.
* 🏆 Melihat laporan skor *Game Coding Quest*.
* ⚙️ Mengatur soal dan konfigurasi *game*.
* 🖨️ Mencetak instrumen penilaian produk untuk keperluan akademik.

### 🛠️ Fitur Admin

* 🔐 Login ke portal admin.
* 👥 Mengelola keseluruhan data pengguna.
* ➕ Menambah akun untuk Siswa, Guru, dan Admin.
* ✏️ Mengedit atau 🗑️ menghapus data pengguna.
* 📈 Memantau ringkasan data sistem di *dashboard*.

---

## 💻 Teknologi yang Digunakan

Aplikasi ini dibangun menggunakan *tech stack* dasar web yang solid:

* 🐘 **PHP Native**
* 🐬 **MySQL**
* 🌐 **HTML5**
* 🎨 **CSS3**
* ⚡ **JavaScript**
* ⚙️ **XAMPP** & **phpMyAdmin**

---

## 📂 Struktur Folder

```text
platformbelajaruntuk_smk/
│
├── assets/
│   ├── css/
│   │   └── style.css
│   └── js/
│       └── main.js
│
├── config/
│   └── koneksi.php
│
├── database/
│   ├── html_learn_rpl.sql
│   ├── update_tahap_3.sql
│   ├── update_tahap_6_css_js.sql
│   ├── update_tahap_8_game.sql
│   └── update_tahap_9_kelola_game.sql
│
├── includes/
│   ├── auth.php
│   ├── header.php
│   └── footer.php
│
├── pages/
│   ├── siswa/
│   ├── guru/
│   └── admin/
│
├── index.php
├── login.php
├── logout.php
└── README.md

```

---

## ⚙️ Cara Instalasi di Localhost

### 1. Siapkan XAMPP

Pastikan aplikasi **XAMPP** sudah terinstal. Aktifkan *service* berikut pada XAMPP Control Panel:

* ✅ **Apache**
* ✅ **MySQL**

### 2. Pindahkan Project ke `htdocs`

Copy folder project ke dalam direktori `htdocs` XAMPP Anda:

> `D:\xampp\htdocs\platformbelajaruntuk_smk`

*(Catatan: Jika folder Anda bernama `lms_rpl`, URL localhost nantinya akan menyesuaikan nama folder tersebut).*

### 3. Konfigurasi Database (phpMyAdmin)

1. Buka browser dan akses: `http://localhost/phpmyadmin`
2. Buat database baru dengan nama: `html_learn_rpl`
3. Import file database utama: `database/html_learn_rpl.sql`

### 4. Import Database Update (Penting!)

Agar fitur terbaru (Live coding, CSS/JS, Game) berfungsi, import file update berikut secara berurutan:

1. `database/update_tahap_3.sql`
2. `database/update_tahap_6_css_js.sql`
3. `database/update_tahap_8_game.sql`
4. `database/update_tahap_9_kelola_game.sql`

### 5. Atur Koneksi Database

Buka file `config/koneksi.php` dan pastikan kodenya seperti ini:

```php
<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "html_learn_rpl";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>

```

### 6. Jalankan Aplikasi 🚀

Buka browser dan ketikkan:

> `http://localhost/platformbelajaruntuk_smk/`

---

## 🔑 Akun Demo

Gunakan kredensial di bawah ini untuk mencoba sistem langsung:

| Peran | Email | Password |
| --- | --- | --- |
| 🎓 **Siswa** | `siswa@demo.com` | `123456` |
| 👨‍🏫 **Guru** | `guru@demo.com` | `123456` |
| 🛠️ **Admin** | `admin@demo.com` | `123456` |

---

## 📖 Panduan Penggunaan

### 🧑‍🎓 Sebagai Siswa

1. **Login** menggunakan akun siswa.
2. Buka menu **Materi Web** di *dashboard*.
3. Pelajari materi HTML, CSS, atau JavaScript.
4. Klik **Live Coding** untuk mempraktikkan materi dan simpan kode Anda.
5. Kerjakan **Kuis** untuk menguji pemahaman.
6. Mainkan **Game Coding** untuk *review* materi yang asik.
7. Pantau perkembangan di **Riwayat Belajar**.
8. **Cetak Sertifikat** jika syarat nilai dan materi sudah terpenuhi! 🎓

### 👨‍🏫 Sebagai Guru

1. **Login** menggunakan akun guru.
2. **Kelola Materi**: Tambah, edit, atau hapus modul pembelajaran.
3. **Kelola Kuis**: Atur soal-soal evaluasi.
4. **Latihan Siswa**: Pantau hasil *live coding* yang disubmit siswa.
5. **Kelola & Laporan Game**: Atur konfigurasi *game* (soal, nyawa, skor) dan lihat skor tertinggi siswa.
6. **Laporan Nilai**: Rekapitulasi nilai dan *progress* kelas.
7. **Instrumen**: Cetak format penilaian produk.

### 🛠️ Sebagai Admin

1. **Login** menggunakan akun admin.
2. Buka menu **Kelola Pengguna**.
3. Tambah, edit, atau hapus data akun Siswa, Guru, maupun Admin lainnya.
4. Sesuaikan profil admin melalui pengaturan akun.

---

## 🎮 Fitur Khusus: Game Coding Quest

**Coding Quest** adalah mini-game edukasi bawaan untuk membantu siswa me-*review* materi dengan cara yang adiktif dan menyenangkan!

**Mode yang Tersedia:**

* HTML
* CSS
* JavaScript
* Campuran (Mix)

**Kontrol Guru atas Game:**
Guru dapat mengkustomisasi pengalaman bermain siswa dengan mengatur *Jumlah soal*, *Nyawa (lives)*, *Poin per jawaban benar*, *Soal & Pembahasan*, hingga mengaktifkan/menonaktifkan soal tertentu.

---

## ⚠️ Catatan Deployment (Hosting)

Aplikasi ini **tidak bisa** di-hosting menggunakan layanan statis seperti *GitHub Pages* karena membutuhkan server PHP dan MySQL. Untuk membuatnya *online*, gunakan layanan hosting seperti:

* ☁️ InfinityFree (Gratis)
* ☁️ Hostinger
* ☁️ Niagahoster
* ☁️ Rumahweb
* ☁️ VPS (DigitalOcean, AWS, dll)

---

## 🐙 Cara Upload ke GitHub

Buka terminal di dalam folder project (misal melalui VS Code) dan jalankan:

```bash
git init
git add .
git commit -m "🚀 Initial commit: Platform Belajar SMK"
git branch -M main
git remote add origin https://github.com/USERNAME/platformbelajaruntuk_smk.git
git push -u origin main

```

*(Jangan lupa ganti `USERNAME` dengan username GitHub Anda).*

**Jika ingin mengganti URL *remote* yang sudah ada:**

```bash
git remote set-url origin https://github.com/USERNAME/platformbelajaruntuk_smk.git
git push -u origin main

```

---

## 🌱 Status Pengembangan (To-Do List)

Project ini masih sangat terbuka untuk dikembangkan. Ide fitur mendatang:

* [ ] 📄 Export laporan kelas ke PDF/Excel.
* [ ] 🔔 Sistem notifikasi siswa.
* [ ] 💬 Forum diskusi antar siswa & guru.
* [ ] 📤 Fitur upload tugas (file).
* [ ] 🆙 Level *game* yang lebih kompleks.
* [ ] 🏆 *Leaderboard* / Sistem ranking kelas.
* [ ] 📱 Optimalisasi antarmuka Mobile (Responsif 100%).

---

## 🧑‍💻 Informasi Pengembang

Project ini didedikasikan sebagai media pembelajaran interaktif berbasis web.

* **Nama Project**: Platform Belajar untuk SMK
* **Kategori**: LMS (*Learning Management System*) / Multimedia Interaktif
* **Fokus Materi**: Pemrograman Web Dasar (HTML, CSS, JS)

## 📜 Lisensi

Aplikasi ini bersifat *Open-Source* untuk keperluan edukasi, pembelajaran, dan modifikasi pengembangan media pendidikan. Selamat berkarya! 🎉