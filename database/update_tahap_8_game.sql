USE html_learn_rpl;

CREATE TABLE IF NOT EXISTS game_scores (
    id_score INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    mode_game VARCHAR(50) NOT NULL,
    skor INT NOT NULL,
    level_tercapai INT DEFAULT 1,
    jawaban_benar INT DEFAULT 0,
    total_soal INT DEFAULT 0,
    tanggal_main TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE
);
