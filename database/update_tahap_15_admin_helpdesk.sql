USE html_learn_rpl;

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
