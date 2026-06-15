# Platform Belajar untuk SMK

**Platform Belajar untuk SMK** adalah aplikasi pembelajaran berbasis web yang dikembangkan untuk membantu siswa SMK jurusan RPL dalam mempelajari dasar-dasar pembuatan web, khususnya materi **HTML, CSS, dan JavaScript**.

Aplikasi ini dilengkapi dengan fitur materi interaktif, live coding, kuis, riwayat belajar, sertifikat, game edukasi, serta dashboard guru dan admin untuk mengelola proses pembelajaran.

---

## Tampilan dan Konsep

Platform ini menggunakan konsep tampilan modern, playful, dan interaktif agar proses belajar menjadi lebih menarik. Warna utama yang digunakan adalah kombinasi **biru**, **ungu**, **hijau neon**, dan **dark panel** sehingga cocok untuk media pembelajaran teknologi.

---

## Fitur Utama

### Fitur Siswa

* Login sebagai siswa.
* Melihat dashboard progress belajar.
* Membaca materi HTML, CSS, dan JavaScript.
* Mencoba kode melalui fitur Live Coding.
* Menyimpan hasil latihan coding.
* Mengerjakan kuis interaktif.
* Melihat riwayat nilai dan latihan coding.
* Bermain Game Coding Quest.
* Mencetak sertifikat setelah menyelesaikan pembelajaran.

### Fitur Guru

* Login sebagai guru.
* Mengelola materi pembelajaran.
* Mengelola soal kuis.
* Melihat laporan nilai siswa.
* Melihat hasil latihan coding siswa.
* Melihat laporan skor game siswa.
* Mengelola soal dan setting game.
* Mencetak instrumen penilaian produk.

### Fitur Admin

* Login sebagai admin.
* Mengelola data pengguna.
* Menambah akun siswa, guru, dan admin.
* Mengedit data pengguna.
* Menghapus data pengguna.
* Melihat ringkasan data sistem.

---

## Teknologi yang Digunakan

* **PHP Native**
* **MySQL**
* **HTML**
* **CSS**
* **JavaScript**
* **XAMPP**
* **phpMyAdmin**

---

## Struktur Folder

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

## Cara Instalasi di Localhost

### 1. Siapkan XAMPP

Pastikan aplikasi **XAMPP** sudah terinstal di komputer.

Aktifkan service berikut:

```text
Apache
MySQL
```

---

### 2. Pindahkan Project ke htdocs

Copy folder project ke dalam folder:

```text
D:\xampp\htdocs\
```

Contoh nama folder:

```text
D:\xampp\htdocs\platformbelajaruntuk_smk
```

Jika folder masih bernama `lms_rpl`, maka URL localhost mengikuti nama folder tersebut.

---

### 3. Buka phpMyAdmin

Buka browser, lalu akses:

```text
http://localhost/phpmyadmin
```

---

### 4. Import Database Utama

Buat atau import database utama dari file:

```text
database/html_learn_rpl.sql
```

Database yang digunakan:

```text
html_learn_rpl
```

---

### 5. Import Database Update

Setelah database utama berhasil diimport, lanjutkan import file update berikut secara berurutan:

```text
database/update_tahap_3.sql
database/update_tahap_6_css_js.sql
database/update_tahap_8_game.sql
database/update_tahap_9_kelola_game.sql
```

File update tersebut berisi tambahan tabel dan data untuk fitur live coding, materi CSS dan JavaScript, game coding, serta kelola game oleh guru.

---

### 6. Atur Koneksi Database

Buka file:

```text
config/koneksi.php
```

Pastikan konfigurasi database seperti berikut:

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

---

### 7. Jalankan Project

Buka browser, lalu akses:

```text
http://localhost/platformbelajaruntuk_smk/
```

Jika folder kamu masih bernama `lms_rpl`, gunakan:

```text
http://localhost/lms_rpl/
```

---

## Akun Demo

Gunakan akun berikut untuk mencoba sistem.

### Akun Siswa

```text
Email    : siswa@demo.com
Password : 123456
```

### Akun Guru

```text
Email    : guru@demo.com
Password : 123456
```

### Akun Admin

```text
Email    : admin@demo.com
Password : 123456
```

---

## Cara Menggunakan sebagai Siswa

1. Login menggunakan akun siswa.
2. Masuk ke dashboard siswa.
3. Pilih menu **Materi Web**.
4. Baca materi HTML, CSS, atau JavaScript.
5. Klik **Live Coding** untuk mencoba kode secara langsung.
6. Simpan hasil latihan coding.
7. Kerjakan kuis pada setiap materi.
8. Mainkan **Game Coding** untuk latihan tambahan.
9. Lihat hasil belajar di menu **Riwayat Belajar**.
10. Cetak sertifikat jika semua materi sudah selesai dan nilai mencukupi.

---

## Cara Menggunakan sebagai Guru

1. Login menggunakan akun guru.
2. Masuk ke dashboard guru.
3. Buka menu **Kelola Materi** untuk menambah, mengedit, atau menghapus materi.
4. Buka menu **Kelola Kuis** untuk mengatur soal kuis.
5. Buka menu **Latihan Siswa** untuk melihat hasil live coding siswa.
6. Buka menu **Kelola Game** untuk mengatur soal dan setting game.
7. Buka menu **Laporan Game** untuk melihat skor game siswa.
8. Buka menu **Laporan Nilai** untuk melihat progress dan nilai siswa.
9. Gunakan menu **Instrumen** untuk mencetak instrumen penilaian produk.

---

## Cara Menggunakan sebagai Admin

1. Login menggunakan akun admin.
2. Masuk ke dashboard admin.
3. Buka menu **Kelola Pengguna**.
4. Tambahkan akun siswa, guru, atau admin.
5. Edit data pengguna jika diperlukan.
6. Hapus akun yang tidak digunakan.
7. Gunakan menu profil untuk mengubah data akun admin.

---

## Fitur Game Coding Quest

Game Coding Quest adalah fitur permainan edukasi yang digunakan untuk membantu siswa mengulang materi dengan cara yang lebih menyenangkan.

Mode game yang tersedia:

```text
HTML
CSS
JavaScript
Campuran
```

Guru dapat mengatur:

```text
Jumlah soal
Jumlah nyawa
Skor per jawaban benar
Soal game
Jawaban benar
Pembahasan
Status aktif/nonaktif soal
```

Skor game siswa akan tersimpan dan dapat dilihat oleh guru melalui menu **Laporan Game**.

---

## Catatan Penggunaan

Aplikasi ini menggunakan PHP dan MySQL, sehingga tidak dapat dijalankan langsung menggunakan GitHub Pages. GitHub digunakan untuk menyimpan source code, sedangkan untuk menjalankan aplikasi secara online dibutuhkan hosting yang mendukung:

```text
PHP
MySQL
phpMyAdmin
```

Contoh hosting yang dapat digunakan:

```text
InfinityFree
Hostinger
Niagahoster
Rumahweb
VPS
```

---

## Upload ke GitHub

Jika ingin mengupload project ke GitHub, jalankan perintah berikut di terminal VS Code:

```bash
git init
git add .
git commit -m "Upload project platform belajar untuk SMK"
git branch -M main
git remote add origin https://github.com/username/platformbelajaruntuk_smk.git
git push -u origin main
```

Ganti `username` dengan username GitHub kamu.

Jika remote sudah pernah dibuat dan ingin mengganti URL repository, gunakan:

```bash
git remote set-url origin https://github.com/username/platformbelajaruntuk_smk.git
git push -u origin main
```

---

## Status Pengembangan

Project ini masih dapat dikembangkan lebih lanjut, misalnya dengan menambahkan:

* Export laporan ke PDF.
* Notifikasi siswa.
* Forum diskusi.
* Upload tugas.
* Level game yang lebih banyak.
* Sistem ranking kelas.
* Tampilan mobile yang lebih optimal.
* Deploy online ke hosting.

---

## Pengembang

Project ini dikembangkan sebagai media pembelajaran interaktif untuk siswa SMK jurusan RPL.

```text
Nama Project : Platform Belajar untuk SMK
Jenis        : LMS / Multimedia Pembelajaran Interaktif
Materi       : HTML, CSS, JavaScript
Platform     : Web
```

---

## Lisensi

Project ini dibuat untuk kebutuhan pembelajaran dan pengembangan media edukasi.
