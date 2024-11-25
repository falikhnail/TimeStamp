<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCutisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cutis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id');
            $table->string('nama_cuti');
            $table->string('tanggal');
            $table->string('jam_awal')->nullable();
            $table->string('jamAkhir')->nullable();
            $table->text('alasan_cuti');
            $table->string('foto_cuti')->nullable();
            $table->string('status_cuti');
            $table->string('catatan')->nullable();
            $table->timestamp('tanggal_approve')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cutis');
    }
}
