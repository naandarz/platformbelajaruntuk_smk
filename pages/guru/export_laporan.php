<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('guru');

$total_materi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM materi"))['total'];

$laporan = mysqli_query($koneksi, "
    SELECT 
        users.nama, 
        users.kelas,
        COUNT(DISTINCT progres_belajar.id_materi) AS materi_selesai,
        COALESCE(ROUND(AVG(nilai.skor)), 0) AS rata_nilai,
        MAX(nilai.tanggal) AS aktivitas_terakhir
    FROM users
    LEFT JOIN progres_belajar ON users.id_user = progres_belajar.id_user AND progres_belajar.status='selesai'
    LEFT JOIN nilai ON users.id_user = nilai.id_user
    WHERE users.role='siswa'
    GROUP BY users.id_user, users.nama, users.kelas
    ORDER BY users.nama ASC
");

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=laporan_progress_siswa.csv');

$output = fopen('php://output', 'w');
fputcsv($output, ['Nama Siswa', 'Kelas', 'Materi Selesai', 'Total Materi', 'Progress (%)', 'Rata-rata Nilai', 'Aktivitas Terakhir']);

while ($row = mysqli_fetch_assoc($laporan)) {
    $progress = $total_materi > 0 ? round(($row['materi_selesai'] / $total_materi) * 100) : 0;
    fputcsv($output, [
        $row['nama'],
        $row['kelas'],
        $row['materi_selesai'],
        $total_materi,
        $progress,
        $row['rata_nilai'],
        $row['aktivitas_terakhir'] ?: '-'
    ]);
}

fclose($output);
exit;
?>
