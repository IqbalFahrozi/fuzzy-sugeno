<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Karyawan;
use App\Models\NilaiKaryawan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class KaryawanController extends Controller
{
    public function index()
    {
        // Mengambil data karyawan beserta relasinya
        $karyawanData = Karyawan::with('nilaiKaryawan')->get();

        // Menghitung skor tertinggi
        $skorTertinggi = Karyawan::max('skor_keputusan');

        return view('pages.nilai-karyawan.index', compact('karyawanData', 'skorTertinggi'));
    }

    public function terimaKaryawan(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'nilai_wawancara' => 'required|numeric|min:0|max:100',
            'nilai_kemampuan' => 'required|numeric|min:0|max:100',
            'nilai_soft_skill' => 'required|numeric|min:0|max:100',
            'nilai_psikologi' => 'required|numeric|min:0|max:100',
            'nilai_keterampilan_bahasa' => 'required|numeric|min:0|max:100',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Calculate skor keputusan using fuzzy logic
            $skorKeputusan = $this->logikaFuzzySugeno(
                $request->nilai_wawancara,
                $request->nilai_kemampuan,
                $request->nilai_soft_skill,
                $request->nilai_psikologi,
                $request->nilai_keterampilan_bahasa
            );

            // Determine the status based on the skor keputusan
            $status = $this->tentukanKeputusan($skorKeputusan);

            // Create a new Karyawan instance
            $karyawan = Karyawan::create([
                'nama' => $request->nama,
                'status' => $status,
                'skor_keputusan' => $skorKeputusan,
                'created_at' => Carbon::now(), // Waktu saat ini
                'updated_at' => Carbon::now(), // Waktu saat ini
            ]);

            // Associate the Karyawan instance with the corresponding NilaiKaryawan instance
            $karyawan->nilaiKaryawan()->create([
                'nilai_wawancara' => $request->nilai_wawancara,
                'nilai_kemampuan' => $request->nilai_kemampuan,
                'nilai_soft_skill' => $request->nilai_soft_skill,
                'nilai_psikologi' => $request->nilai_psikologi,
                'nilai_keterampilan_bahasa' => $request->nilai_keterampilan_bahasa,
                'created_at' => Carbon::now(), // Waktu saat ini
                'updated_at' => Carbon::now(), // Waktu saat ini
            ]);

            // Redirect back with success message
            return redirect()->back()->with('success', 'Data karyawan berhasil disimpan.');
        } catch (\Exception $e) {
            // Handle the exception and redirect back with error message
            return redirect()->back()->with('error', 'Gagal menyimpan data karyawan. Silakan coba lagi.');
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
