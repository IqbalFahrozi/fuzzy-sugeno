<x-app-layout>
    <!-- =============== Navigation ================ -->
    <div class="container">
        <!-- ========================= Main ==================== -->
        <div class="main">
            <!-- ======================= Cards ================== -->
            <div class="cardBox">
                <div class="card">
                    <div>
                        <div id="skorTertinggi" class="numbers"></div>
                        <div class="cardName">Skor Tertinggi</div>
                    </div>

                    <div class="iconBx">
                        <ion-icon name="stats-chart-outline"></ion-icon>
                    </div>
                </div>

                <div class="card">
                    <div>
                        <div id="skorKemampuan" class="numbers"></div>
                        <div class="cardName">Nilai Tes Kemampuan</div>
                    </div>

                    <div class="iconBx">
                        <ion-icon name="flask-outline"></ion-icon>
                    </div>
                </div>

                <div class="card">
                    <div>
                        <div id="skorSkill" class="numbers"></div>
                        <div class="cardName">Nilai Soft Skill</div>
                    </div>

                    <div class="iconBx">
                        <ion-icon name="hand-left-outline"></ion-icon>
                    </div>
                </div>

                <div class="card">
                    <div>
                        <div id="skorPsikologi" class="numbers"></div>
                        <div class="cardName">Nilai Tes Psikologi</div>
                    </div>

                    <div class="iconBx">
                        <ion-icon name="happy-outline"></ion-icon>
                    </div>
                </div>
            </div>

            <!-- ================ Order Details List ================= -->
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
                                $karyawanData = DB::table('karyawan')
                                    ->join('nilai_karyawan', 'karyawan.id', '=', 'nilai_karyawan.karyawan_id')
                                    ->orderBy('karyawan.created_at', 'desc') // Mengurutkan berdasarkan created_at secara descending (terbaru ke terlama)
                                    ->get();
                                ?>

                                <?php foreach ($karyawanData as $karyawan): ?>
                                    <tr>
                                        <td><?= $karyawan->created_at ?></td>
                                        <td><?= $karyawan->nama ?></td>
                                        <td><?= $karyawan->nilai_wawancara ?></td>
                                        <td><?= $karyawan->nilai_kemampuan ?></td>
                                        <td><?= $karyawan->nilai_soft_skill ?></td>
                                        <td><?= $karyawan->nilai_psikologi ?></td>
                                        <td><?= $karyawan->nilai_keterampilan_bahasa ?></td>
                                        <td><?= $karyawan->skor_keputusan ?></td>
                                        <td><?= $karyawan->status ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ================= Chart ==================== -->
            <div class="charts">
                <canvas id="myChart" width="50px" height="20"></canvas>
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
        // Buat tabel menggunakan DataTables
        var table = $('#karyawanTable').DataTable({
            paging: true, // Mengaktifkan pagination
            order: [[0, 'desc']] // Mengatur pengurutan default berdasarkan kolom 0 (tanggal) secara descending
        });

        // Mendapatkan data yang sudah difilter dan diurutkan oleh DataTables
        var filteredData = table.rows({ search: 'applied', order: 'applied' }).data();

        // Variabel untuk menyimpan data skor keputusan di bulan ini
        var labels = [];
        var skorKeputusanBulanIni = [];

        // Iterasi melalui data yang sudah difilter dan diurutkan
        filteredData.each(function(row) {
            // Mendapatkan tanggal dari kolom pertama (indeks 0)
            var dateString = row[0]; // Di sini, anggaplah kolom pertama adalah tanggal
            var date = new Date(dateString);

            // Filter data berdasarkan bulan saat ini
            var thisMonth = new Date().getMonth() + 1; // Menggunakan indeks bulan dimulai dari 0
            if (date.getMonth() + 1 === thisMonth) {
                labels.push(row[1]); // Ambil nama karyawan untuk label
                skorKeputusanBulanIni.push(parseFloat(row[7])); // Ambil skor keputusan
            }
        });

        // Buat grafik menggunakan Chart.js
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Skor Keputusan',
                    data: skorKeputusanBulanIni,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });

        // $(document).ready(function() {
        //     // Mendapatkan tanggal saat ini
        //     var today = new Date();
        //     var thisMonth = today.getMonth() + 1; // Menggunakan indeks bulan dimulai dari 0

        //     // Ambil data skor keputusan dari kolom skor keputusan (indeks 7)
        //     var skorKeputusan = [];
        //     $('#karyawanTable tbody tr').each(function() {
        //         // Mendapatkan tanggal dari kolom pertama (indeks 0)
        //         var dateString = $(this).find('td:eq(0)').text();
        //         var date = new Date(dateString);

        //         // Filter data berdasarkan bulan saat ini
        //         if (date.getMonth() + 1 === thisMonth) { // Menggunakan indeks bulan dimulai dari 0
        //             skorKeputusan.push(parseFloat($(this).find('td:eq(1)').text())); // Ambil nilai skor keputusan
        //         }
        //     });

        //     // Temukan skor tertinggi
        //     var skorTertinggi = Math.max(...skorKeputusan);

        //     // Masukkan skor tertinggi ke dalam card
        //     $('#skorTertinggi').text(skorTertinggi);
        // });

        $(document).ready(function() {
    // Mendapatkan tanggal saat ini
    var today = new Date();
    var thisMonth = today.getMonth() + 1; // Menggunakan indeks bulan dimulai dari 0

    // Variabel untuk menyimpan skor tertinggi dan nama karyawan
    var maxSkor = -Infinity;
    var namaKaryawanMaxSkor = "";

    // Iterasi melalui baris tabel untuk mencari skor tertinggi
    $('#karyawanTable tbody tr').each(function() {
        // Mendapatkan tanggal dari kolom pertama (indeks 0)
        var dateString = $(this).find('td:eq(0)').text();
        var date = new Date(dateString);

        // Filter data berdasarkan bulan saat ini
        if (date.getMonth() + 1 === thisMonth) { // Menggunakan indeks bulan dimulai dari 0
            var skor = parseFloat($(this).find('td:eq(7)').text());
            if (skor > maxSkor) {
                maxSkor = skor;
                namaKaryawanMaxSkor = $(this).find('td:eq(1)').text(); // Ambil nama dari kolom kedua (indeks 1)
            }
        }
    });

    // Jika tidak ada data yang memenuhi kriteria, tampilkan pesan alternatif
    if (maxSkor === -Infinity) {
        $('#skorTertinggi').text("LOADING...");
    } else {
        // Tampilkan nilai skorTertinggi tertinggi
        $('#skorTertinggi').text(namaKaryawanMaxSkor + " (" + maxSkor + ")");
    }
});

$(document).ready(function() {
    // Mendapatkan tanggal saat ini
    var today = new Date();
    var thisMonth = today.getMonth() + 1; // Menggunakan indeks bulan dimulai dari 0

    // Variabel untuk menyimpan nilai kemampuan tertinggi
    var maxSkor = -Infinity;
    var namaKaryawanMaxSkor = "";

    // Iterasi melalui baris tabel untuk mencari nilai kemampuan tertinggi
    $('#karyawanTable tbody tr').each(function() {
        // Mendapatkan tanggal dari kolom pertama (indeks 0)
        var dateString = $(this).find('td:eq(0)').text();
        var date = new Date(dateString);

        // Filter data berdasarkan bulan saat ini
        if (date.getMonth() + 1 === thisMonth) { // Menggunakan indeks bulan dimulai dari 0
            var skor = parseFloat($(this).find('td:eq(3)').text());
            if (skor > maxSkor) {
                maxSkor = skor;
                namaKaryawanMaxSkor = $(this).find('td:eq(1)').text(); // Ambil nama dari kolom kedua (indeks 1)
            }
        }
    });

    // Jika tidak ada data yang memenuhi kriteria, tampilkan pesan alternatif
    if (maxSkor === -Infinity) {
        $('#skorKemampuan').text("LOADING...");
    } else {
        // Tampilkan nilai kemampuan tertinggi
        $('#skorKemampuan').text(namaKaryawanMaxSkor + " (" + maxSkor + ")");
    }
});

$(document).ready(function() {
    // Mendapatkan tanggal saat ini
    var today = new Date();
    var thisMonth = today.getMonth() + 1; // Menggunakan indeks bulan dimulai dari 0

    // Variabel untuk menyimpan nilai kemampuan tertinggi
    var maxSkor = -Infinity;
    var namaKaryawanMaxSkor = "";

    // Iterasi melalui baris tabel untuk mencari nilai kemampuan tertinggi
    $('#karyawanTable tbody tr').each(function() {
        // Mendapatkan tanggal dari kolom pertama (indeks 0)
        var dateString = $(this).find('td:eq(0)').text();
        var date = new Date(dateString);

        // Filter data berdasarkan bulan saat ini
        if (date.getMonth() + 1 === thisMonth) { // Menggunakan indeks bulan dimulai dari 0
            var skor = parseFloat($(this).find('td:eq(4)').text());
            if (skor > maxSkor) {
                maxSkor = skor;
                namaKaryawanMaxSkor = $(this).find('td:eq(1)').text(); // Ambil nama dari kolom kedua (indeks 1)
            }
        }
    });

    // Jika tidak ada data yang memenuhi kriteria, tampilkan pesan alternatif
    if (maxSkor === -Infinity) {
        $('#skorSkill').text("LOADING...");
    } else {
        // Tampilkan nilai Skill tertinggi
        $('#skorSkill').text(namaKaryawanMaxSkor + " (" + maxSkor + ")");
    }
});

$(document).ready(function() {
    // Mendapatkan tanggal saat ini
    var today = new Date();
    var thisMonth = today.getMonth() + 1; // Menggunakan indeks bulan dimulai dari 0

    // Variabel untuk menyimpan nilai kemampuan tertinggi
    var maxSkor = -Infinity;
    var namaKaryawanMaxSkor = "";

    // Iterasi melalui baris tabel untuk mencari nilai kemampuan tertinggi
    $('#karyawanTable tbody tr').each(function() {
        // Mendapatkan tanggal dari kolom pertama (indeks 0)
        var dateString = $(this).find('td:eq(0)').text();
        var date = new Date(dateString);

        // Filter data berdasarkan bulan saat ini
        if (date.getMonth() + 1 === thisMonth) { // Menggunakan indeks bulan dimulai dari 0
            var skor = parseFloat($(this).find('td:eq(5)').text());
            if (skor > maxSkor) {
                maxSkor = skor;
                namaKaryawanMaxSkor = $(this).find('td:eq(1)').text(); // Ambil nama dari kolom kedua (indeks 1)
            }
        }
    });

    // Jika tidak ada data yang memenuhi kriteria, tampilkan pesan alternatif
    if (maxSkor === -Infinity) {
        $('#skorPsikologi').text("LOADING...");
    } else {
        // Tampilkan nilai Psikologi tertinggi
        $('#skorPsikologi').text(namaKaryawanMaxSkor + " (" + maxSkor + ")");
    }
});






    </script>
</x-app-layout>
