<?php
include "../../config/koneksi.php";
include "../../includes/auth.php";
hanya_role('guru');

/*
Format Word yang didukung:
1. Pertanyaan soal
A. Pilihan A
B. Pilihan B
C. Pilihan C
D. Pilihan D
Jawaban: A

Boleh juga menggunakan:
Kunci: A
Answer: A
ANS: A
*/

function xmlTextToPlain($xmlString) {
    $xmlString = str_replace(['w:', 'a:'], ['w_', 'a_'], $xmlString);
    $dom = new DOMDocument();
    $dom->preserveWhiteSpace = false;
    @$dom->loadXML($xmlString);

    $paragraphs = $dom->getElementsByTagName('w_p');
    $lines = [];

    foreach ($paragraphs as $p) {
        $texts = $p->getElementsByTagName('w_t');
        $line = '';

        foreach ($texts as $t) {
            $line .= $t->nodeValue;
        }

        $line = trim($line);
        if ($line !== '') {
            $lines[] = $line;
        }
    }

    return implode("\n", $lines);
}

function readDocxText($filePath) {
    if (!class_exists('ZipArchive')) {
        return [
            'success' => false,
            'message' => 'Ekstensi ZipArchive PHP belum aktif. Aktifkan extension=zip di php.ini XAMPP.'
        ];
    }

    $zip = new ZipArchive();

    if ($zip->open($filePath) !== true) {
        return [
            'success' => false,
            'message' => 'File Word tidak dapat dibuka. Pastikan file berformat .docx, bukan .doc atau PDF.'
        ];
    }

    $xml = $zip->getFromName('word/document.xml');
    $zip->close();

    if (!$xml) {
        return [
            'success' => false,
            'message' => 'Isi dokumen Word tidak ditemukan.'
        ];
    }

    return [
        'success' => true,
        'text' => xmlTextToPlain($xml)
    ];
}

function cleanLine($line) {
    $line = trim($line);
    $line = preg_replace('/\s+/', ' ', $line);
    return $line;
}

function normalizeQuestionText($text) {
    $text = str_replace(["\r\n", "\r"], "\n", $text);

    // Memisahkan nomor soal yang menempel dengan teks sebelumnya.
    // Contoh: "Jawaban: A2. Soal berikutnya" menjadi baris baru.
    $text = preg_replace('/([^\n])\s*(?=(?:No\.?\s*)?\d+[\.\)]\s*)/iu', "$1\n", $text);

    // Memisahkan opsi A-D yang menempel di belakang pertanyaan atau opsi sebelumnya.
    // Contoh: "Apa fungsi HTML?A. ..." atau "webB. ..." menjadi baris baru.
    $text = preg_replace('/([^\n])\s*(?=[A-D][\.\)\:]\s*)/iu', "$1\n", $text);

    // Memisahkan baris kunci jawaban yang menempel setelah opsi D.
    // Contoh: "serverJawaban: C" menjadi "server\nJawaban: C".
    $text = preg_replace('/([^\n])\s*(?=(?:Jawaban|Kunci|Answer|ANS)\s*[\:\-]?)/iu', "$1\n", $text);

    return $text;
}

function parseQuestionsFromText($text) {
    $text = normalizeQuestionText($text);
    $lines = array_values(array_filter(array_map('cleanLine', explode("\n", $text)), function($line) {
        return $line !== '';
    }));

    $questions = [];
    $current = null;

    foreach ($lines as $line) {
        if (preg_match('/^(?:No\.?\s*)?(\d+)[\.\)]\s*(.+)$/i', $line, $m)) {
            if ($current) {
                $questions[] = $current;
            }

            $current = [
                'pertanyaan' => trim($m[2]),
                'opsi_a' => '',
                'opsi_b' => '',
                'opsi_c' => '',
                'opsi_d' => '',
                'jawaban_benar' => '',
                'valid' => false,
                'error' => ''
            ];
            continue;
        }

        if (!$current) {
            continue;
        }

        if (preg_match('/^([A-D])[\.\)\:]\s*(.+)$/i', $line, $m)) {
            $key = 'opsi_' . strtolower(strtoupper($m[1]));
            $current[$key] = trim($m[2]);
            continue;
        }

        if (preg_match('/^(?:jawaban|kunci|answer|ans)\s*[\:\-]?\s*([A-D])\b/i', $line, $m)) {
            $current['jawaban_benar'] = strtoupper($m[1]);
            continue;
        }

        if ($current && $current['opsi_a'] == '') {
            $current['pertanyaan'] .= ' ' . $line;
        }
    }

    if ($current) {
        $questions[] = $current;
    }

    foreach ($questions as $i => $q) {
        $missing = [];

        if ($q['pertanyaan'] == '') $missing[] = 'pertanyaan';
        if ($q['opsi_a'] == '') $missing[] = 'opsi A';
        if ($q['opsi_b'] == '') $missing[] = 'opsi B';
        if ($q['opsi_c'] == '') $missing[] = 'opsi C';
        if ($q['opsi_d'] == '') $missing[] = 'opsi D';
        if ($q['jawaban_benar'] == '') $missing[] = 'jawaban benar';

        if (count($missing) == 0) {
            $questions[$i]['valid'] = true;
        } else {
            $questions[$i]['valid'] = false;
            $questions[$i]['error'] = 'Bagian kurang: ' . implode(', ', $missing);
        }
    }

    return $questions;
}

$materi = mysqli_query($koneksi, "SELECT * FROM materi ORDER BY urutan ASC");
$previewQuestions = [];
$rawText = "";
$error = "";
$success = "";
$id_materi_selected = intval($_POST['id_materi'] ?? 0);

if (isset($_POST['preview'])) {
    $id_materi_selected = intval($_POST['id_materi'] ?? 0);

    if ($id_materi_selected <= 0) {
        $error = "Pilih materi terlebih dahulu.";
    } elseif (!isset($_FILES['file_word']) || $_FILES['file_word']['error'] != UPLOAD_ERR_OK) {
        $error = "Upload file Word terlebih dahulu.";
    } else {
        $fileName = $_FILES['file_word']['name'];
        $tmp = $_FILES['file_word']['tmp_name'];
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if ($ext !== 'docx') {
            $error = "File harus berformat .docx. Jika masih .doc, buka di Word lalu Save As menjadi .docx.";
        } else {
            $read = readDocxText($tmp);
            if (!$read['success']) {
                $error = $read['message'];
            } else {
                $rawText = $read['text'];
                $previewQuestions = parseQuestionsFromText($rawText);

                if (count($previewQuestions) == 0) {
                    $error = "Soal tidak terbaca. Pastikan format nomor, opsi A-D, dan jawaban sudah sesuai.";
                }
            }
        }
    }
}

if (isset($_POST['import'])) {
    $id_materi_selected = intval($_POST['id_materi'] ?? 0);
    $json = $_POST['questions_json'] ?? '';
    $questions = json_decode($json, true);

    if ($id_materi_selected <= 0) {
        $error = "Pilih materi terlebih dahulu.";
    } elseif (!is_array($questions) || count($questions) == 0) {
        $error = "Data soal kosong. Lakukan preview terlebih dahulu.";
    } else {
        $inserted = 0;
        $skipped = 0;

        foreach ($questions as $q) {
            if (empty($q['valid'])) {
                $skipped++;
                continue;
            }

            $pertanyaan = mysqli_real_escape_string($koneksi, $q['pertanyaan']);
            $a = mysqli_real_escape_string($koneksi, $q['opsi_a']);
            $b = mysqli_real_escape_string($koneksi, $q['opsi_b']);
            $c = mysqli_real_escape_string($koneksi, $q['opsi_c']);
            $d = mysqli_real_escape_string($koneksi, $q['opsi_d']);
            $jawaban = mysqli_real_escape_string($koneksi, strtoupper($q['jawaban_benar']));

            mysqli_query($koneksi, "INSERT INTO kuis 
                (id_materi, pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar)
                VALUES
                ($id_materi_selected, '$pertanyaan', '$a', '$b', '$c', '$d', '$jawaban')
            ");

            if (mysqli_affected_rows($koneksi) > 0) {
                $inserted++;
            }
        }

        header("Location: kelola_kuis.php?status=import&jumlah=$inserted&skip=$skipped");
        exit;
    }
}

$totalValid = 0;
$totalInvalid = 0;
foreach ($previewQuestions as $q) {
    if ($q['valid']) $totalValid++;
    else $totalInvalid++;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Import Soal Word</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="dashboard-layout">
    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <div>
                <h1>Import Soal dari Word</h1>
                <p>Upload dokumen soal pilihan ganda .docx, lalu sistem akan memisahkan nomor, opsi A-D, dan jawaban menjadi kuis.</p>
            </div>
            <a href="kelola_kuis.php" class="btn btn-outline">Kembali ke Kuis</a>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error; ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success; ?></div>
        <?php endif; ?>

        <section class="import-step-grid" style="margin-bottom:18px;">
            <div class="import-step">
                <span class="badge badge-primary">1</span>
                <h3 style="margin-top:10px;">Siapkan Word</h3>
                <p>Gunakan format nomor soal, opsi A-D, dan baris jawaban.</p>
            </div>
            <div class="import-step">
                <span class="badge badge-primary">2</span>
                <h3 style="margin-top:10px;">Preview Soal</h3>
                <p>Sistem membaca soal dari file .docx dan menampilkan hasilnya.</p>
            </div>
            <div class="import-step">
                <span class="badge badge-primary">3</span>
                <h3 style="margin-top:10px;">Import Kuis</h3>
                <p>Soal valid akan dimasukkan ke tabel kuis sesuai materi.</p>
            </div>
        </section>

        <section class="content-grid">
            <div>
                <form method="POST" enctype="multipart/form-data" class="card">
                    <h3>Upload Dokumen Word</h3>
                    <p>Pilih materi tujuan, lalu upload file soal pilihan ganda dalam format .docx.</p>
                    <br>

                    <div class="form-group">
                        <label>Materi Tujuan</label>
                        <select name="id_materi" class="form-control" required>
                            <option value="">Pilih Materi</option>
                            <?php
                            mysqli_data_seek($materi, 0);
                            while($m = mysqli_fetch_assoc($materi)):
                            ?>
                                <option value="<?= $m['id_materi']; ?>" <?= $id_materi_selected == $m['id_materi'] ? 'selected' : ''; ?>>
                                    <?= $m['urutan']; ?>. <?= $m['judul_materi']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>File Word (.docx)</label>
                        <input type="file" name="file_word" class="form-control" accept=".docx" required>
                        <small class="form-help">File .doc lama harus diubah dulu menjadi .docx.</small>
                    </div>

                    <button type="submit" name="preview" class="btn btn-primary">Preview Soal</button>
                </form>

                <div class="card" style="margin-top:18px;">
                    <h3>Format Word yang Disarankan</h3>
                    <p>Gunakan format seperti ini agar soal terbaca otomatis. Jika Word menggabungkan opsi dalam satu paragraf, sistem tetap akan mencoba memisahkannya.</p>
                    <br>
                    <div class="import-format-box">1. Apa fungsi CSS?
A. Membuat struktur halaman
B. Mengatur tampilan halaman web
C. Menyimpan database
D. Menjalankan server
Jawaban: B

2. Tag HTML untuk paragraf adalah?
A. &lt;p&gt;
B. &lt;h1&gt;
C. &lt;img&gt;
D. &lt;table&gt;
Jawaban: A</div>
                </div>
            </div>

            <div>
                <div class="card">
                    <h3>Hasil Preview</h3>
                    <p>
                        Valid: <strong><?= $totalValid; ?></strong> |
                        Perlu Dicek: <strong><?= $totalInvalid; ?></strong>
                    </p>

                    <?php if (count($previewQuestions) == 0): ?>
                        <br><div class="empty-state">Belum ada preview soal.</div>
                    <?php endif; ?>

                    <?php if (count($previewQuestions) > 0): ?>
                        <br>
                        <form method="POST">
                            <input type="hidden" name="id_materi" value="<?= $id_materi_selected; ?>">
                            <input type="hidden" name="questions_json" value='<?= htmlspecialchars(json_encode($previewQuestions), ENT_QUOTES, "UTF-8"); ?>'>

                            <?php foreach($previewQuestions as $i => $q): ?>
                                <div class="import-result-card <?= $q['valid'] ? '' : 'invalid'; ?>">
                                    <div style="display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap;">
                                        <span class="badge <?= $q['valid'] ? 'badge-success' : 'badge-warning'; ?>">
                                            <?= $q['valid'] ? 'Valid' : 'Perlu Dicek'; ?>
                                        </span>
                                        <strong>Soal <?= $i + 1; ?></strong>
                                    </div>

                                    <h3 style="margin-top:12px;"><?= htmlspecialchars($q['pertanyaan']); ?></h3>

                                    <?php if (!$q['valid']): ?>
                                        <div class="alert alert-warning"><?= htmlspecialchars($q['error']); ?></div>
                                    <?php endif; ?>

                                    <div class="import-options">
                                        <span class="<?= $q['jawaban_benar'] == 'A' ? 'right' : ''; ?>">A. <?= htmlspecialchars($q['opsi_a']); ?></span>
                                        <span class="<?= $q['jawaban_benar'] == 'B' ? 'right' : ''; ?>">B. <?= htmlspecialchars($q['opsi_b']); ?></span>
                                        <span class="<?= $q['jawaban_benar'] == 'C' ? 'right' : ''; ?>">C. <?= htmlspecialchars($q['opsi_c']); ?></span>
                                        <span class="<?= $q['jawaban_benar'] == 'D' ? 'right' : ''; ?>">D. <?= htmlspecialchars($q['opsi_d']); ?></span>
                                    </div>

                                    <p style="margin-top:12px;"><strong>Jawaban:</strong> <?= htmlspecialchars($q['jawaban_benar'] ?: '-'); ?></p>
                                </div>
                            <?php endforeach; ?>

                            <div class="page-actions">
                                <button type="submit" name="import" class="btn btn-primary" <?= $totalValid == 0 ? 'disabled' : ''; ?>>
                                    Import <?= $totalValid; ?> Soal Valid
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>
</div>
</body>
</html>
