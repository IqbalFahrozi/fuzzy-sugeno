<x-app-layout>
    <style>
        form {
            max-width: 400px;
            margin: 0 auto;
        }
        h2 {
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }
        button[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            width: 100%;
        }
        button[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>

    <h2>Input Nilai Tes Karyawan</h2>
    <form method="POST" action="{{ route('terima-karyawan') }}">
        @csrf
        <label for="nilai_wawancara">Nilai Tes Wawancara:</label>
        <input type="number" id="nilai_wawancara" name="nilai_wawancara" min="0" max="100" required><br>

        <label for="nilai_kemampuan">Nilai Tes Kemampuan:</label>
        <input type="number" id="nilai_kemampuan" name="nilai_kemampuan" min="0" max="100" required><br>

        <label for="nilai_soft_skill">Nilai Soft Skill:</label>
        <input type="number" id="nilai_soft_skill" name="nilai_soft_skill" min="0" max="100" required><br>

        <label for="nilai_psikologi">Nilai Tes Psikologi:</label>
        <input type="number" id="nilai_psikologi" name="nilai_psikologi" min="0" max="100" required><br>

        <label for="nilai_keterampilan_bahasa">Nilai Keterampilan Bahasa:</label>
        <input type="number" id="nilai_keterampilan_bahasa" name="nilai_keterampilan_bahasa" min="0" max="100" required><br>

        <button type="submit">Submit</button>
    </form>

    <script>
        document.getElementById('formKaryawan').addEventListener('submit', function(event) {
            var nilaiWawancara = document.getElementById('nilai_wawancara').value;
            var nilaiKemampuan = document.getElementById('nilai_kemampuan').value;
            var nilaiSoftSkill = document.getElementById('nilai_soft_skill').value;
            var nilaiPsikologi = document.getElementById('nilai_psikologi').value;
            var nilaiKeterampilanBahasa = document.getElementById('nilai_keterampilan_bahasa').value;

            // Validasi setiap nilai input
            if (nilaiWawancara < 0 || nilaiWawancara > 100 ||
                nilaiKemampuan < 0 || nilaiKemampuan > 100 ||
                nilaiSoftSkill < 0 || nilaiSoftSkill > 100 ||
                nilaiPsikologi < 0 || nilaiPsikologi > 100 ||
                nilaiKeterampilanBahasa < 0 || nilaiKeterampilanBahasa > 100) {
                alert('Nilai harus berada di antara 0 dan 100.');
                event.preventDefault(); // Mencegah pengiriman formulir jika validasi gagal
            }
        });
    </script>
</x-app-layout>
