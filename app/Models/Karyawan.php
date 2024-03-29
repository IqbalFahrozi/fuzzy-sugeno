<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawan';

    protected $fillable = [
        'nama',
        'status',
        'skor_keputusan',
    ];

    // Definisikan relasi dengan nilai karyawan
    public function nilaiKaryawan()
    {
        return $this->hasMany(NilaiKaryawan::class, 'karyawan_id');
    }
}


