USE html_learn_rpl;

-- Catatan:
-- Kolom password_plain ditambahkan untuk kebutuhan admin help desk pada project lokal/penelitian.
-- Dalam aplikasi produksi nyata, password sebaiknya tidak disimpan dalam bentuk teks biasa.

ALTER TABLE users 
ADD COLUMN IF NOT EXISTS password_plain VARCHAR(255) DEFAULT NULL;

UPDATE users 
SET password_plain='123456' 
WHERE email IN ('siswa@demo.com','guru@demo.com','admin@demo.com')
AND (password_plain IS NULL OR password_plain='');

CREATE TABLE IF NOT EXISTS admin_helpdesk_logs (
    id_log INT AUTO_INCREMENT PRIMARY KEY,
    id_admin INT NOT NULL,
    id_target_user INT NOT NULL,
    aksi VARCHAR(100) NOT NULL,
    keterangan TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_admin) REFERENCES users(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_target_user) REFERENCES users(id_user) ON DELETE CASCADE
);
