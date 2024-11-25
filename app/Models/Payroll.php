<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function Karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function jumlahHadir($karyawan_id, $bulan, $tahun, $status)
    {
       return MappingShift::where('karyawan_id', $karyawan_id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status_absen', $status)->count();
    }

    public function jumlahTelat($karyawan_id, $bulan, $tahun)
    {
       $telat = MappingShift::where('karyawan_id', $karyawan_id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('telat', '>', 0)->count();
       $pulpat = MappingShift::where('karyawan_id', $karyawan_id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('pulang_cepat', '>', 0)->count();
       $jumlah = $telat + $pulpat;
       return $jumlah;
    }

}
