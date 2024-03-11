<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiKaryawan extends Model
{
    use HasFactory;

    protected $table = 'nilai_karyawan'; // Menentukan nama tabel yang harus digunakan

    protected $fillable = [
        'karyawan_id',
        'nilai_wawancara',
        'nilai_kemampuan',
        'nilai_soft_skill',
        'nilai_psikologi',
        'nilai_keterampilan_bahasa',
    ];

    // Definisikan relasi dengan karyawan
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }
}
