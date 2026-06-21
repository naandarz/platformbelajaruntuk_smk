<?php
// Tahap 22: memastikan tabel absensi dan jadwal tersedia agar halaman tidak error
// jika file SQL belum sempat diimport.
if (isset($koneksi)) {
    @mysqli_query($koneksi, "CREATE TABLE IF NOT EXISTS absensi (
        id_absensi INT AUTO_INCREMENT PRIMARY KEY,
        id_user INT NOT NULL,
        tanggal DATE NOT NULL,
        status VARCHAR(30) NOT NULL DEFAULT 'hadir',
        catatan TEXT DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_absensi_user_tanggal (id_user, tanggal)
    )");

    @mysqli_query($koneksi, "CREATE TABLE IF NOT EXISTS jadwal_kelas (
        id_jadwal INT AUTO_INCREMENT PRIMARY KEY,
        judul VARCHAR(200) NOT NULL,
        kategori VARCHAR(50) DEFAULT 'Pembelajaran',
        kelas VARCHAR(100) DEFAULT NULL,
        id_materi INT DEFAULT NULL,
        tanggal DATE NOT NULL,
        jam_mulai TIME DEFAULT NULL,
        jam_selesai TIME DEFAULT NULL,
        keterangan TEXT DEFAULT NULL,
        dibuat_oleh INT DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
}
?>