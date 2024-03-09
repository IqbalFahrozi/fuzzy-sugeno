<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNilaiKaryawanTable extends Migration
{
    public function up()
    {
        Schema::create('nilai_karyawan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('karyawan_id');
            $table->foreign('karyawan_id')->references('id')->on('karyawan')->onDelete('cascade');
            $table->float('nilai_wawancara');
            $table->float('nilai_kemampuan');
            $table->float('nilai_soft_skill');
            $table->float('nilai_psikologi');
            $table->float('nilai_keterampilan_bahasa');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nilai_karyawan');
    }
}
