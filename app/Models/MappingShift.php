<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MappingShift extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function Karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function Shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public static function dataAbsen()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tglskrg = date('Y-m-d');

        $user_id = request()->input('user_id');
        $mulai = request()->input('mulai');
        $akhir = request()->input('akhir');

        $data_absen = MappingShift::select('mapping_shifts.*', 'karyawans.name')
                                    ->rightJoin('karyawans', function($join) use ($tglskrg) {
                                        $join->on('karyawans.id', '=', 'mapping_shifts.karyawan_id')
                                            ->where('mapping_shifts.tanggal', '=', $tglskrg);
                                    })
                                    ->when(auth()->user()->is_admin == 'user', function ($query) {
                                        return $query->where('karyawans.id', auth()->user()->id);
                                    })
                                    ->when($user_id, function ($query) use ($user_id) {
                                        return $query->where('karyawans.id', $user_id);
                                    })
                                    ->when($mulai && $akhir, function ($query) use ($mulai, $akhir, $user_id) {
                                        return MappingShift::rightJoin('karyawans', function($join) use ($mulai, $akhir) {
                                                                $join->on('karyawans.id', '=', 'mapping_shifts.karyawan_id')
                                                                    ->whereBetween('tanggal', [$mulai, $akhir]);
                                                            })
                                                            ->when($user_id, function ($query) use ($user_id) {
                                                                return $query->where('karyawans.id', $user_id);
                                                            });
                                    })
                                    ->orderBy('karyawans.name', 'ASC')
                                    ->orderBy('mapping_shifts.tanggal', 'ASC');

        return $data_absen;
    }
}
