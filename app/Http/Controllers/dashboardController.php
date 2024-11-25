<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\MappingShift;
use Illuminate\Http\Request;
use App\Models\Cuti;
use App\Models\Karyawan;
use App\Models\Lembur;
use App\Models\ResetCuti;
use Illuminate\Support\Facades\Auth;

class dashboardController extends Controller
{
    public function index()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tgl_skrg = date("Y-m-d");
        $user = Auth::guard('web')->user();

        if($user && in_array($user->is_admin, ['admin', 'admin_divisi','finance']) ){
            return view('dashboard.index', [
                'title' => 'Dashboard',
                'jumlah_user' => Karyawan::count(),
                'jumlah_masuk' => MappingShift::where('tanggal', $tgl_skrg)->where('status_absen', 'Masuk')->count(),
                'jumlah_libur' => MappingShift::where('tanggal', $tgl_skrg)->where('status_absen', 'Libur')->count(),
                'jumlah_lepas' => MappingShift::where('tanggal', $tgl_skrg)->where('status_absen', 'Lepas')->count(),
                'jumlah_cuti' => MappingShift::where('tanggal', $tgl_skrg)
                ->whereIn('status_absen', [
                    'Cuti Tahunan', 
                    'Cuti Melahirkan Anak', 
                    'Cuti Menikah', 
                    'Cuti Keguguran',
                    'Cuti Istri Melahirkan', 
                    'Cuti Menikahkan Anak', 
                    'Cuti Mengkhitankan Anak', 
                    'Cuti Membabtiskan Anak', 
                    'Cuti Keluarga Meninggal Atap', 
                    'Cuti Keluarga Meninggal', 
                    'Cuti Ibadah Besar'
                ])
                ->count(),
                
                'jumlah_izin_masuk' => MappingShift::where('tanggal', $tgl_skrg)->where('status_absen', 'Izin Masuk')->count(),
                'jumlah_izin_sakit' => MappingShift::where('tanggal', $tgl_skrg)->where('status_absen', 'Izin Sakit')->count(),
                'jumlah_izin_telat' => MappingShift::where('tanggal', $tgl_skrg)->where('status_absen', 'Izin Telat')->count(),
                'jumlah_izin_pulang_cepat' => MappingShift::where('tanggal', $tgl_skrg)->where('status_absen', 'Izin Pulang Cepat')->count(),
                'jumlah_karyawan_lembur' => Lembur::where('tanggal', $tgl_skrg)->count(),
            ]);
        } else {
            $user_login = Auth()->user()->id;
            $tanggal = "";
            $tglskrg = date('Y-m-d');
            $tglkmrn = date('Y-m-d', strtotime('-1 days'));
            $mapping_shift = MappingShift::where('karyawan_id', $user_login)->where('tanggal', $tglkmrn)->get();
            if($mapping_shift->count() > 0) {
                foreach($mapping_shift as $mp) {
                    $jam_absen = $mp->jam_absen;
                    $jam_pulang = $mp->jam_pulang;
                }
            } else {
                $jam_absen = "-";
                $jam_pulang = "-";
            }
            if($jam_absen != null && $jam_pulang == null) {
                $tanggal = $tglkmrn;
            } else {
                $tanggal = $tglskrg;
            }
            return view('dashboard.indexUser', [
                'title' => 'Dashboard',
                'shift_karyawan' => MappingShift::where('karyawan_id', $user_login)->where('tanggal', $tanggal)->first()
            ]);
        }
    }

    public function menu()
    {
        return view('dashboard.menu', [
            'title' => 'All Menu',
        ]);
    }
}
