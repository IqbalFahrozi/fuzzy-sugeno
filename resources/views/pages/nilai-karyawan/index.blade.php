<x-app-layout>
    <style>
        .cardHeader {
            background-color: #f0f0f0;
            padding: 20px;
            border-radius: 5px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            max-width: 400px;
            margin: 0 auto;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
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
            border-radius: 5px;
        }
        button[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>

    <!-- =============== Navigation ================ -->
    <div class="container">
        <!-- ========================= Main ==================== -->
        <div class="main">
            <!-- ================ Order Details List ================= -->
            @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

            <div class="details">
                <div class="recentOrders">
                    <div class="cardHeader">
                        <h2>Input Nilai Karyawan</h2>
                        <form id="formKaryawan" method="POST" action="{{ route('terima-karyawan') }}">
                            @csrf
                            <label for="nama">Nama Karyawan:</label>
                            <input type="text" id="nama" name="nama" required><br>

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
                    </div>
                </div>
            </div>
<!-- ==================== Karyawan Table ================= -->
<div class="details">
    <div class="recentOrders">
        <div class="cardHeader">
            <h2>Daftar Karyawan</h2>
            <table id="karyawanTable" class="display">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama</th>
                        <th>Nilai Wawancara</th>
                        <th>Nilai Kemampuan</th>
                        <th>Nilai Soft Skill</th>
                        <th>Nilai Psikologi</th>
                        <th>Nilai Keterampilan Bahasa</th>
                        <th>Skor Keputusan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Query to fetch data from the database
                    $karyawanData = DB::table('karyawan')
                        ->join('nilai_karyawan', 'karyawan.id', '=', 'nilai_karyawan.karyawan_id')
                        ->latest('karyawan.created_at') // Mengurutkan berdasarkan created_at secara descending (terbaru ke terlama)
                        ->get();

                    // Check if there are any rows returned
                    if ($karyawanData->isEmpty()) {
                        echo "<tr><td colspan='9'>Tidak ada data karyawan.</td></tr>";
                    } else {
                        // Loop through each row of data
                        foreach ($karyawanData as $karyawan) {
                            echo "<tr>";
                            echo "<td>" . $karyawan->created_at . "</td>";
                            echo "<td>" . $karyawan->nama . "</td>";
                            echo "<td>" . $karyawan->nilai_wawancara . "</td>";
                            echo "<td>" . $karyawan->nilai_kemampuan . "</td>";
                            echo "<td>" . $karyawan->nilai_soft_skill . "</td>";
                            echo "<td>" . $karyawan->nilai_psikologi . "</td>";
                            echo "<td>" . $karyawan->nilai_keterampilan_bahasa . "</td>";
                            echo "<td>" . $karyawan->skor_keputusan . "</td>";
                            echo "<td>" . $karyawan->status . "</td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- =========== Scripts =========  -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTables
        $('#karyawanTable').DataTable({
            "order": [[0, "desc"]] // Mengurutkan berdasarkan kolom pertama (created_at) secara descending
        });
    });

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
