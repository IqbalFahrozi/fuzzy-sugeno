<x-app-layout>
    <!-- =============== Navigation ================ -->
    <div class="container">
        <!-- ========================= Main ==================== -->
        <div class="main">
            <!-- ======================= Cards ================== -->
            <div class="cardBox">
                <div class="card">
                    <div>
                        <div class="numbers">1,504</div>
                        <div class="cardName">Skor Tertinggi</div>
                    </div>

                    <div class="iconBx">
                        <ion-icon name="eye-outline"></ion-icon>
                    </div>
                </div>

                <div class="card">
                    <div>
                        <div class="numbers">80</div>
                        <div class="cardName">Nilai Tes Kemampuan</div>
                    </div>

                    <div class="iconBx">
                        <ion-icon name="cart-outline"></ion-icon>
                    </div>
                </div>

                <div class="card">
                    <div>
                        <div class="numbers">284</div>
                        <div class="cardName">Nilai Soft Skill</div>
                    </div>

                    <div class="iconBx">
                        <ion-icon name="chatbubbles-outline"></ion-icon>
                    </div>
                </div>

                <div class="card">
                    <div>
                        <div class="numbers">$7,842</div>
                        <div class="cardName">Nilai Tes Psikologi</div>
                    </div>

                    <div class="iconBx">
                        <ion-icon name="cash-outline"></ion-icon>
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
                                ->get();
                            ?>

                            <?php foreach ($karyawanData as $karyawan): ?>
                                <tr>
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

                <!-- ================= New Customers ================ -->
                {{-- <div class="recentCustomers">
                    <div class="cardHeader">
                        <h2>Recent Customers</h2>
                    <table id="recentCustomersTable" class="display">
                        <tbody>
                            <?php
                            $karyawanData = DB::table('karyawan')
                                ->join('nilai_karyawan', 'karyawan.id', '=', 'nilai_karyawan.karyawan_id')
                                ->get();
                            ?>

                            <?php foreach ($karyawanData as $karyawan): ?>
                                <tr>
                                    <td><?= $karyawan->nama ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    </div>
                </div> --}}

            </div>
                    <!-- ================= Chart ==================== -->
        <div class="charts">
            <canvas id="myChart" width="50px" height="20"></canvas>
        </div>
        </div>

    </div>

    <!-- =========== Scripts =========  -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Tambahkan library DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <!-- Tambahkan library Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTables untuk kedua tabel
            $('#karyawanTable').DataTable({
                paging: true // Mengaktifkan pagination
            });
            $('#recentCustomersTable').DataTable({
                paging: true // Mengaktifkan pagination
            });

            // Ambil data dari tabel dan buat grafik
            var labels = [];
            var nilaiKemampuan = [];
            var nilaiSoftSkill = [];
            var nilaiPsikologi = [];
            var nilaiKeterampilanBahasa = [];

            $('#karyawanTable tbody tr').each(function() {
                labels.push($(this).find('td:eq(0)').text());
                nilaiKemampuan.push(parseFloat($(this).find('td:eq(2)').text()));
                nilaiSoftSkill.push(parseFloat($(this).find('td:eq(3)').text()));
                nilaiPsikologi.push(parseFloat($(this).find('td:eq(4)').text()));
                nilaiKeterampilanBahasa.push(parseFloat($(this).find('td:eq(5)').text()));
            });

            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Nilai Kemampuan',
                        data: nilaiKemampuan,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        tension: 0.5,
                        fill: true
                    }, {
                        label: 'Nilai Soft Skill',
                        data: nilaiSoftSkill,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        tension: 0.5,
                        fill: true
                    }, {
                        label: 'Nilai Psikologi',
                        data: nilaiPsikologi,
                        borderColor: 'rgba(255, 206, 86, 1)',
                        backgroundColor: 'rgba(255, 206, 86, 0.2)',
                        tension: 0.5,
                        fill: true
                    }, {
                        label: 'Nilai Keterampilan Bahasa',
                        data: nilaiKeterampilanBahasa,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.5,
                        fill: true
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
    </script>
</x-app-layout>
