USE html_learn_rpl;

CREATE TABLE IF NOT EXISTS latihan_kode (
    id_latihan INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_materi INT NOT NULL,
    kode_html TEXT NOT NULL,
    catatan VARCHAR(255) DEFAULT NULL,
    tanggal_simpan TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_materi) REFERENCES materi(id_materi) ON DELETE CASCADE
);
