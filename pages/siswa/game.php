<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('siswa');

$id_user = $_SESSION['user']['id_user'];
$nama = $_SESSION['user']['nama'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_score'])) {
    $mode = mysqli_real_escape_string($koneksi, $_POST['mode_game']);
    $skor = intval($_POST['skor']);
    $level = intval($_POST['level_tercapai']);
    $benar = intval($_POST['jawaban_benar']);
    $total = intval($_POST['total_soal']);

    mysqli_query($koneksi, "INSERT INTO game_scores (id_user, mode_game, skor, level_tercapai, jawaban_benar, total_soal)
    VALUES ($id_user, '$mode', $skor, $level, $benar, $total)");

    header("Location: game.php?saved=1");
    exit;
}

$setting = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM game_settings WHERE id_setting=1"));
if (!$setting) {
    $setting = ['max_questions' => 10, 'lives' => 3, 'score_per_correct' => 20];
}

$questions_result = mysqli_query($koneksi, "SELECT * FROM game_questions WHERE status='aktif' ORDER BY RAND()");
$questionBank = [
    "HTML" => [],
    "CSS" => [],
    "JavaScript" => []
];

if ($questions_result) {
    while ($row = mysqli_fetch_assoc($questions_result)) {
        $questionBank[$row['mode_game']][] = [
            "id" => intval($row['id_question']),
            "q" => $row['pertanyaan'],
            "code" => $row['kode'] ?: "",
            "options" => [
                $row['opsi_a'],
                $row['opsi_b'],
                $row['opsi_c'],
                $row['opsi_d']
            ],
            "answer" => array_search($row['jawaban_benar'], ["A","B","C","D"]),
            "explain" => $row['pembahasan'] ?: "Pembahasan belum tersedia."
        ];
    }
}

$best = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT MAX(skor) AS skor FROM game_scores WHERE id_user=$id_user"))['skor'];
$history = mysqli_query($koneksi, "SELECT * FROM game_scores WHERE id_user=$id_user ORDER BY tanggal_main DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Game Coding</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
<?php include "../../includes/pwa_head.php"; ?>
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Game Coding Quest</h1>
                <p>Jawab tantangan HTML, CSS, dan JavaScript untuk mengumpulkan skor tertinggi.</p>
            </div>
            <div class="user-pill">
                <div class="avatar">🎮</div>
                Best: <?= $best ? $best : 0; ?>
            </div>
        </div>

        <?php if (isset($_GET['saved'])): ?>
            <div class="alert alert-success">Skor game berhasil disimpan ke riwayat.</div>
        <?php endif; ?>

        <div class="game-hero">
            <span class="badge badge-primary">Edu Game</span>
            <h2 style="margin:12px 0;">Code Quest Challenge</h2>
            <p>
                Pilih mode permainan, jawab soal sebelum nyawa habis, lalu simpan skor.
                Pertanyaan game dikelola oleh guru melalui menu Kelola Game.
            </p>
        </div>

        <section class="game-panel">
            <div class="game-card">
                <h2 id="gameTitle">HTML Quest</h2>

                <div class="game-question">
                    <p id="questionText">Klik Mulai Game untuk memulai tantangan.</p>
                    <pre id="questionCode" style="display:none;"></pre>
                </div>

                <div class="game-options" id="optionsBox"></div>
                <div class="game-feedback" id="feedbackText"></div>

                <div class="game-result" id="resultBox">
                    <h2>Game Selesai!</h2>
                    <p id="resultText"></p>

                    <form method="POST" id="saveScoreForm">
                        <input type="hidden" name="save_score" value="1">
                        <input type="hidden" name="mode_game" id="modeInput">
                        <input type="hidden" name="skor" id="scoreInput">
                        <input type="hidden" name="level_tercapai" id="levelInput">
                        <input type="hidden" name="jawaban_benar" id="correctInput">
                        <input type="hidden" name="total_soal" id="totalInput">
                        <button class="btn btn-primary" type="submit">Simpan Skor</button>
                    </form>
                </div>

                <div class="game-actions">
                    <button class="btn btn-primary" onclick="startGame()">Mulai Game</button>
                    <button class="btn btn-outline" onclick="resetGame()">Reset</button>
                </div>
            </div>

            <div>
                <div class="game-card" style="margin-bottom:18px;">
                    <h3>Status Game</h3>
                    <br>
                    <div class="game-status-grid">
                        <div class="game-status"><span>Skor</span><strong id="scoreText">0</strong></div>
                        <div class="game-status"><span>Level</span><strong id="levelText">1</strong></div>
                        <div class="game-status"><span>Nyawa</span><strong id="lifeText"><?= intval($setting['lives']); ?></strong></div>
                        <div class="game-status"><span>Benar</span><strong id="correctText">0</strong></div>
                    </div>
                </div>

                <div class="card">
                    <h3>Pilih Mode</h3>
                    <p>Mode menentukan jenis soal yang akan muncul.</p>

                    <div class="game-mode-list">
                        <div class="game-mode active" onclick="setMode('HTML', this)">
                            <strong>HTML Quest</strong>
                            <p>Tag, struktur, tabel, form, dan elemen dasar.</p>
                        </div>
                        <div class="game-mode" onclick="setMode('CSS', this)">
                            <strong>CSS Quest</strong>
                            <p>Selector, properti, warna, box model, dan style.</p>
                        </div>
                        <div class="game-mode" onclick="setMode('JavaScript', this)">
                            <strong>JavaScript Quest</strong>
                            <p>DOM, event, function, dan interaksi web.</p>
                        </div>
                        <div class="game-mode" onclick="setMode('Campuran', this)">
                            <strong>Campuran</strong>
                            <p>Gabungan HTML, CSS, dan JavaScript.</p>
                        </div>
                    </div>
                </div>

                <div class="card" style="margin-top:18px;">
                    <h3>Riwayat Skor</h3>
                    <br>
                    <?php if (mysqli_num_rows($history) == 0): ?>
                        <div class="empty-state">Belum ada skor tersimpan.</div>
                    <?php endif; ?>

                    <?php while($row = mysqli_fetch_assoc($history)): ?>
                        <p>🏆 <?= $row['mode_game']; ?> - Skor <?= $row['skor']; ?></p>
                        <small><?= $row['tanggal_main']; ?></small>
                        <br><br>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>
    </main>
</div>

<script>
const questionBank = <?= json_encode($questionBank, JSON_UNESCAPED_UNICODE); ?>;
const gameConfig = <?= json_encode([
    "maxQuestions" => intval($setting['max_questions']),
    "lives" => intval($setting['lives']),
    "scorePerCorrect" => intval($setting['score_per_correct'])
]); ?>;

let currentMode = "HTML";
let questions = [];
let currentIndex = 0;
let score = 0;
let lives = gameConfig.lives;
let correct = 0;
let isPlaying = false;

function setMode(mode, el) {
    currentMode = mode;
    document.querySelectorAll('.game-mode').forEach(item => item.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('gameTitle').innerText = mode + " Quest";
    resetGame();
}

function shuffleArray(arr) {
    return [...arr].sort(() => Math.random() - 0.5);
}

function getQuestions() {
    if (currentMode === "Campuran") {
        return shuffleArray([
            ...questionBank.HTML,
            ...questionBank.CSS,
            ...questionBank.JavaScript
        ]).slice(0, gameConfig.maxQuestions);
    }

    return shuffleArray(questionBank[currentMode] || []).slice(0, gameConfig.maxQuestions);
}

function startGame() {
    questions = getQuestions();

    if (questions.length === 0) {
        document.getElementById('questionText').innerText = "Belum ada pertanyaan aktif untuk mode ini. Silakan hubungi guru.";
        document.getElementById('optionsBox').innerHTML = "";
        document.getElementById('feedbackText').innerText = "";
        return;
    }

    currentIndex = 0;
    score = 0;
    lives = gameConfig.lives;
    correct = 0;
    isPlaying = true;

    document.getElementById('resultBox').classList.remove('show');
    updateStatus();
    showQuestion();
}

function resetGame() {
    currentIndex = 0;
    score = 0;
    lives = gameConfig.lives;
    correct = 0;
    isPlaying = false;
    updateStatus();

    document.getElementById('questionText').innerText = "Klik Mulai Game untuk memulai tantangan.";
    document.getElementById('questionCode').style.display = "none";
    document.getElementById('questionCode').innerText = "";
    document.getElementById('optionsBox').innerHTML = "";
    document.getElementById('feedbackText').innerText = "";
    document.getElementById('resultBox').classList.remove('show');
}

function updateStatus() {
    document.getElementById('scoreText').innerText = score;
    document.getElementById('levelText').innerText = isPlaying ? currentIndex + 1 : 1;
    document.getElementById('lifeText').innerText = lives;
    document.getElementById('correctText').innerText = correct;
}

function showQuestion() {
    if (!isPlaying) return;

    if (currentIndex >= questions.length || lives <= 0) {
        finishGame();
        return;
    }

    const q = questions[currentIndex];
    document.getElementById('questionText').innerText = q.q;

    const codeBox = document.getElementById('questionCode');
    if (q.code) {
        codeBox.style.display = "block";
        codeBox.innerText = q.code;
    } else {
        codeBox.style.display = "none";
        codeBox.innerText = "";
    }

    const optionsBox = document.getElementById('optionsBox');
    optionsBox.innerHTML = "";
    document.getElementById('feedbackText').innerText = "";

    q.options.forEach((option, index) => {
        const btn = document.createElement("button");
        btn.className = "game-option";
        btn.innerText = option;
        btn.onclick = () => answerQuestion(index, btn);
        optionsBox.appendChild(btn);
    });

    updateStatus();
}

function answerQuestion(selected, btn) {
    if (!isPlaying) return;

    const q = questions[currentIndex];
    const allOptions = document.querySelectorAll('.game-option');
    allOptions.forEach(item => item.disabled = true);

    if (selected === q.answer) {
        btn.classList.add('correct');
        score += gameConfig.scorePerCorrect;
        correct++;
        document.getElementById('feedbackText').innerText = "Benar! " + q.explain;
    } else {
        btn.classList.add('wrong');
        if (allOptions[q.answer]) {
            allOptions[q.answer].classList.add('correct');
        }
        lives--;
        document.getElementById('feedbackText').innerText = "Kurang tepat. " + q.explain;
    }

    currentIndex++;
    updateStatus();

    setTimeout(showQuestion, 1200);
}

function finishGame() {
    isPlaying = false;

    const levelReached = Math.min(currentIndex + 1, questions.length);
    document.getElementById('resultText').innerText =
        "Mode: " + currentMode +
        " | Skor: " + score +
        " | Jawaban benar: " + correct + " dari " + questions.length;

    document.getElementById('modeInput').value = currentMode;
    document.getElementById('scoreInput').value = score;
    document.getElementById('levelInput').value = levelReached;
    document.getElementById('correctInput').value = correct;
    document.getElementById('totalInput').value = questions.length;

    document.getElementById('resultBox').classList.add('show');
}
</script>
</body>
</html>
