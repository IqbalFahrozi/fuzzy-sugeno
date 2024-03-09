<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Karyawan;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawanData = Karyawan::join('nilai_karyawan', 'karyawan.id', '=', 'nilai_karyawan.karyawan_id')->get();
        $skorTertinggi = Karyawan::max('skor_keputusan');

        return view('input-karyawan', compact('karyawanData', 'skorTertinggi'));
    }

    public function terimaKaryawan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nilai_wawancara' => 'required|numeric|min:0|max:100',
            'nilai_kemampuan' => 'required|numeric|min:0|max:100',
            'nilai_soft_skill' => 'required|numeric|min:0|max:100',
            'nilai_psikologi' => 'required|numeric|min:0|max:100',
            'nilai_keterampilan_bahasa' => 'required|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $nilaiWawancara = $request->input('nilai_wawancara');
        $nilaiKemampuan = $request->input('nilai_kemampuan');
        $nilaiSoftSkill = $request->input('nilai_soft_skill');
        $nilaiPsikologi = $request->input('nilai_psikologi');
        $nilaiKeterampilanBahasa = $request->input('nilai_keterampilan_bahasa');

        $skorKeputusan = $this->logikaFuzzySugeno($nilaiWawancara, $nilaiKemampuan, $nilaiSoftSkill, $nilaiPsikologi, $nilaiKeterampilanBahasa);

        $labelKeputusan = $this->tentukanKeputusan($skorKeputusan);

        $hasilKeputusan = [
            'keputusan' => $labelKeputusan . ' dengan skor ' . $skorKeputusan
        ];

        return response()->json($hasilKeputusan);
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
