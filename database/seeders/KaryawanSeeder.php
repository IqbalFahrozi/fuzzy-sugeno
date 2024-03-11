<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Faker\Factory as Faker;

class KaryawanSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Looping untuk membuat 50 data fake karyawan
        for ($i = 0; $i < 50; $i++) {
            $namaKaryawan = $faker->name;
            $nilaiWawancara = rand(0, 100); // Contoh nilai wawancara acak
            $nilaiKemampuan = rand(0, 100); // Contoh nilai kemampuan acak
            $nilaiSoftSkill = rand(0, 100); // Contoh nilai soft skill acak
            $nilaiPsikologi = rand(0, 100); // Contoh nilai psikologi acak
            $nilaiKeterampilanBahasa = rand(0, 100); // Contoh nilai keterampilan bahasa acak
            $skorKeputusan = $this->logikaFuzzySugeno($nilaiWawancara, $nilaiKemampuan, $nilaiSoftSkill, $nilaiPsikologi, $nilaiKeterampilanBahasa);
            $status = $this->tentukanKeputusan($skorKeputusan);
            $createdAt = $faker->dateTimeBetween('-3 months', 'now');
            $updatedAt = $createdAt; // Setelah dibuat, updated_at sama dengan created_at

            $karyawanId = DB::table('karyawan')->insertGetId([
                'nama' => $namaKaryawan,
                'status' => $status,
                'skor_keputusan' => $skorKeputusan,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ]);

            // Tambahkan data fake nilai karyawan untuk setiap karyawan
            DB::table('nilai_karyawan')->insert([
                'karyawan_id' => $karyawanId,
                'nilai_wawancara' => $nilaiWawancara,
                'nilai_kemampuan' => $nilaiKemampuan,
                'nilai_soft_skill' => $nilaiSoftSkill,
                'nilai_psikologi' => $nilaiPsikologi,
                'nilai_keterampilan_bahasa' => $nilaiKeterampilanBahasa,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ]);
        }
    }

    private function logikaFuzzySugeno($nilaiWawancara, $nilaiKemampuan, $nilaiSoftSkill, $nilaiPsikologi, $nilaiKeterampilanBahasa)
    {
        $koefisienWawancara = 30;
        $koefisienKemampuan = 30;
        $koefisienSoftSkill = 20;
        $koefisienPsikologi = 10;
        $koefisienKeterampilanBahasa = 10;

        $totalBobot = $koefisienWawancara + $koefisienKemampuan + $koefisienSoftSkill + $koefisienPsikologi + $koefisienKeterampilanBahasa;

        $skorKeputusan = ($koefisienWawancara * $nilaiWawancara +
                          $koefisienKemampuan * $nilaiKemampuan +
                          $koefisienSoftSkill * $nilaiSoftSkill +
                          $koefisienPsikologi * $nilaiPsikologi +
                          $koefisienKeterampilanBahasa * $nilaiKeterampilanBahasa) / $totalBobot;

        $skorKeputusan = round($skorKeputusan, 2);

        return $skorKeputusan;
    }

    private function tentukanKeputusan($skorKeputusan)
    {
        if ($skorKeputusan < 40) {
            return 'Tidak Layak';
        } elseif ($skorKeputusan < 70) {
            return 'Kurang Layak';
        } elseif ($skorKeputusan < 85) {
            return 'Cukup Layak';
        } elseif ($skorKeputusan < 95) {
            return 'Layak';
        } else {
            return 'Sangat Layak';
        }
    }
}
