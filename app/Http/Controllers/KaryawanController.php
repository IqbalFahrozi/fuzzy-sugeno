<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index()
    {
        return view('input-karyawan');
    }

    public function terimaKaryawan(Request $request)
    {
        // Mendapatkan nilai input dari request
        $nilaiWawancara = $request->input('nilai_wawancara');
        $nilaiKemampuan = $request->input('nilai_kemampuan');
        $nilaiSoftSkill = $request->input('nilai_soft_skill');
        $nilaiPsikologi = $request->input('nilai_psikologi');
        $nilaiKeterampilanBahasa = $request->input('nilai_keterampilan_bahasa');

        // Menerapkan logika fuzzy metode Sugeno
        $keputusanPenerimaan = $this->logikaFuzzySugeno($nilaiWawancara, $nilaiKemampuan, $nilaiSoftSkill, $nilaiPsikologi, $nilaiKeterampilanBahasa);

        // Mengembalikan hasil keputusan
        return response()->json(['keputusan' => $keputusanPenerimaan]);
    }

    private function logikaFuzzySugeno($nilaiWawancara, $nilaiKemampuan, $nilaiSoftSkill, $nilaiPsikologi, $nilaiKeterampilanBahasa)
    {
        // Total bobot
        $totalBobot = 100;

        // Menyesuaikan nilai koefisien agar total menjadi 100
        $totalKoefisien = 0.3 + 0.3 + 0.2 + 0.1 + 0.1;
        $koefisienWawancara = (0.3 / $totalKoefisien) * $totalBobot;
        $koefisienKemampuan = (0.3 / $totalKoefisien) * $totalBobot;
        $koefisienSoftSkill = (0.2 / $totalKoefisien) * $totalBobot;
        $koefisienPsikologi = (0.1 / $totalKoefisien) * $totalBobot;
        $koefisienKeterampilanBahasa = (0.1 / $totalKoefisien) * $totalBobot;

        // Menerapkan aturan fuzzy
        $skorKeputusan = ($koefisienWawancara * $nilaiWawancara +
                          $koefisienKemampuan * $nilaiKemampuan +
                          $koefisienSoftSkill * $nilaiSoftSkill +
                          $koefisienPsikologi * $nilaiPsikologi +
                          $koefisienKeterampilanBahasa * $nilaiKeterampilanBahasa) / $totalBobot;

        // Menentukan keputusan penerimaan berdasarkan skor
        $keputusan = $this->tentukanKeputusan($skorKeputusan);

        // Mengembalikan keputusan
        return $keputusan;
    }

    private function tentukanKeputusan($skorKeputusan)
    {
        // Menentukan keputusan berdasarkan skor
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

