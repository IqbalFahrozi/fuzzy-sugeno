<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Nilai Tes Karyawan</title>
</head>
<body>
    <h2>Input Nilai Tes Karyawan</h2>
    <form method="POST" action="{{ route('terima-karyawan') }}">
        @csrf
        <label for="nilai_wawancara">Nilai Tes Wawancara:</label>
        <input type="number" id="nilai_wawancara" name="nilai_wawancara" min="0" max="100" required><br><br>

        <label for="nilai_kemampuan">Nilai Tes Kemampuan:</label>
        <input type="number" id="nilai_kemampuan" name="nilai_kemampuan" min="0" max="100" required><br><br>

        <label for="nilai_soft_skill">Nilai Soft Skill:</label>
        <input type="number" id="nilai_soft_skill" name="nilai_soft_skill" min="0" max="100" required><br><br>

        <label for="nilai_psikologi">Nilai Tes Psikologi:</label>
        <input type="number" id="nilai_psikologi" name="nilai_psikologi" min="0" max="100" required><br><br>

        <label for="nilai_keterampilan_bahasa">Nilai Keterampilan Bahasa:</label>
        <input type="number" id="nilai_keterampilan_bahasa" name="nilai_keterampilan_bahasa" min="0" max="100" required><br><br>

        <button type="submit">Submit</button>
    </form>
</body>
</html>
