<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KaryawanController extends Controller
{
    public function index()
    {
        return view('input-karyawan');
    }

    public function terimaKaryawan(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nilai_wawancara' => 'required|numeric|min:0|max:100',
            'nilai_kemampuan' => 'required|numeric|min:0|max:100',
            'nilai_soft_skill' => 'required|numeric|min:0|max:100',
            'nilai_psikologi' => 'required|numeric|min:0|max:100',
            'nilai_keterampilan_bahasa' => 'required|numeric|min:0|max:100',
        ]);

        // Jika validasi gagal, kembalikan pesan kesalahan
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Mendapatkan nilai input dari request
        $nilaiWawancara = $request->input('nilai_wawancara');
        $nilaiKemampuan = $request->input('nilai_kemampuan');
        $nilaiSoftSkill = $request->input('nilai_soft_skill');
        $nilaiPsikologi = $request->input('nilai_psikologi');
        $nilaiKeterampilanBahasa = $request->input('nilai_keterampilan_bahasa');

        // Menerapkan logika fuzzy metode Sugeno
        $skorKeputusan = $this->logikaFuzzySugeno($nilaiWawancara, $nilaiKemampuan, $nilaiSoftSkill, $nilaiPsikologi, $nilaiKeterampilanBahasa);

        // Tentukan label keputusan berdasarkan skor
        $labelKeputusan = $this->tentukanKeputusan($skorKeputusan);

        // Format hasil keputusan
        $hasilKeputusan = [
            'keputusan' => $labelKeputusan . ' dengan skor ' . $skorKeputusan
        ];

        // Mengembalikan hasil keputusan dalam format JSON
        return response()->json($hasilKeputusan);
    }


    private function logikaFuzzySugeno($nilaiWawancara, $nilaiKemampuan, $nilaiSoftSkill, $nilaiPsikologi, $nilaiKeterampilanBahasa)
    {
        // Koefisien relatif dari masing-masing variabel input
        $koefisienWawancara = 0.3;
        $koefisienKemampuan = 0.3;
        $koefisienSoftSkill = 0.2;
        $koefisienPsikologi = 0.1;
        $koefisienKeterampilanBahasa = 0.1;

        // Menghitung nilai bobot dari masing-masing input
        $totalBobot = $koefisienWawancara + $koefisienKemampuan + $koefisienSoftSkill + $koefisienPsikologi + $koefisienKeterampilanBahasa;

        // Menghitung skor keputusan menggunakan metode Sugeno
        $skorKeputusan = ($koefisienWawancara * $nilaiWawancara +
                          $koefisienKemampuan * $nilaiKemampuan +
                          $koefisienSoftSkill * $nilaiSoftSkill +
                          $koefisienPsikologi * $nilaiPsikologi +
                          $koefisienKeterampilanBahasa * $nilaiKeterampilanBahasa) / $totalBobot;

        // Membulatkan nilai skor keputusan menjadi 2 angka di belakang koma
        $skorKeputusan = round($skorKeputusan, 2);

        // Mengembalikan skor keputusan
        return $skorKeputusan;
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
