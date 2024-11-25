<?php

use App\Models\User;
use App\Events\NotifApproval;
use App\Http\Controllers\DinasLuar;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\authController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\AbsenController;
use App\Http\Controllers\PajakController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\KasbonController;
use App\Http\Controllers\LemburController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\jabatanController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\GolonganController;
use App\Http\Controllers\karyawanController;
use App\Http\Controllers\AutoShiftController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\RekapDataController;
use App\Http\Controllers\TunjanganController;
use App\Http\Controllers\StatusPtkpController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserControler;
use App\Exports\SakitExport;
use GuzzleHttp\Middleware;
use App\Exports\AbsenExport;
use Maatwebsite\Excel\Facades\Excel;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [authController::class, 'index'])->name('login')->middleware('guest');
Route::get('/login-admin', [authController::class, 'loginAdmin'])->name('loginAdmin')->middleware('guest');
Route::get('/get-started', [authController::class, 'getStarted'])->name('getStarted')->middleware('guest');
Route::get('/welcome', [authController::class, 'welcome'])->name('welcome')->middleware('guest');
Route::get('/presensi', [authController::class, 'presensi'])->middleware('guest');
Route::get('/presensi-pulang', [authController::class, 'presensiPulang'])->middleware('guest');
Route::post('/presensi/store', [authController::class, 'presensiStore'])->middleware('guest');
Route::post('/presensi-pulang/store', [authController::class, 'presensiPulangStore'])->middleware('guest');
Route::get('/ajaxGetNeural', [authController::class, 'ajaxGetNeural'])->middleware('guest');
Route::get('/register', [authController::class, 'register'])->middleware('guest');
Route::post('/register-proses', [authController::class, 'registerProses'])->middleware('guest');
Route::post('/login-proses', [authController::class, 'loginProses'])->middleware('guest');
Route::post('/login-proses-user', [authController::class, 'loginProsesUser'])->middleware('guest');
Route::get('/dashboard', [dashboardController::class, 'index'])->middleware('auth:web,karyawan');
Route::get('/logout', [authController::class, 'logout'])->middleware('auth:web,karyawan');
Route::get('/pegawai', [karyawanController::class, 'index'])->middleware('auth:web,karyawan');
Route::get('/euforia', [karyawanController::class, 'euforia'])->middleware('auth:web,karyawan');
Route::get('/pegawai/tambah-pegawai', [karyawanController::class, 'tambahKaryawan'])->middleware('admin');
Route::post('/pegawai/tambah-pegawai-proses', [karyawanController::class, 'tambahKaryawanProses'])->middleware('admin');
Route::post('/pegawai/face/ajaxPhoto', [karyawanController::class, 'ajaxPhoto'])->middleware('admin');
Route::post('/pegawai/face/ajaxDescrip', [karyawanController::class, 'ajaxDescrip'])->middleware('admin');
Route::post('/pegawai/import', [karyawanController::class, 'importUsers'])->middleware('admin');
Route::get('/pegawai/detail/{id}', [karyawanController::class, 'detail'])->middleware('admin');
Route::get('/pegawai/show/{id}', [karyawanController::class, 'show'])->middleware('auth:web,karyawan');
Route::get('/pegawai/face/{id}', [karyawanController::class, 'face'])->middleware('admin');
Route::put('/pegawai/proses-edit/{id}', [karyawanController::class, 'editKaryawanProses'])->middleware('admin');
Route::delete('/pegawai/delete/{id}', [karyawanController::class, 'deleteKaryawan'])->middleware('admin');
Route::get('/pegawai/edit-password/{id}', [karyawanController::class, 'editPassword'])->middleware('admin');
Route::put('/pegawai/edit-password-proses/{id}', [karyawanController::class, 'editPasswordProses'])->middleware('admin');
Route::resource('/shift', ShiftController::class)->middleware('admin');

Route::get('/pegawai/shift/{id}', [karyawanController::class, 'shift'])->middleware('admin');
Route::get('/pegawai/dinas-luar/{id}', [karyawanController::class, 'dinasLuar'])->middleware('admin');

Route::post('/pegawai/shift/proses-tambah-shift', [karyawanController::class, 'prosesTambahShift'])->middleware('admin');
Route::post('/pegawai/dinas-luar/proses-tambah-shift', [karyawanController::class, 'prosesTambahDinas'])->middleware('admin');

Route::delete('/pegawai/delete-shift/{id}', [karyawanController::class, 'deleteShift'])->middleware('admin');
Route::delete('/pegawai/delete-dinas/{id}', [karyawanController::class, 'deleteDinas'])->middleware('admin');

Route::get('/pegawai/edit-shift/{id}', [karyawanController::class, 'editShift'])->middleware('admin');
Route::get('/pegawai/edit-dinas/{id}', [karyawanController::class, 'editDinas'])->middleware('admin');

Route::put('/pegawai/proses-edit-shift/{id}', [karyawanController::class, 'prosesEditShift'])->middleware('auth');
Route::put('/pegawai/proses-edit-dinas/{id}', [karyawanController::class, 'prosesEditDinas'])->middleware('auth');

Route::get('/absen', [AbsenController::class, 'index'])->middleware('auth:web,karyawan');
Route::get('/dinas-luar', [DinasLuar::class, 'index'])->middleware('auth:web,karyawan');

Route::get('/notifications', [NotificationController::class, 'index'])->middleware('auth:web,karyawan');
Route::get('/notifications/read', [NotificationController::class, 'read'])->middleware('auth:web,karyawan');
Route::get('/notifications/unread', [NotificationController::class, 'unread'])->middleware('auth:web,karyawan');
Route::get('/notifications/read-message/{id}', [NotificationController::class, 'readMessage'])->middleware('auth:web,karyawan');

Route::get('/menu', [dashboardController::class, 'menu'])->middleware('auth:web,karyawan');

Route::get('/my-location', [AbsenController::class, 'myLocation'])->middleware('auth:web,karyawan');

Route::put('/absen/masuk/{id}', [AbsenController::class, 'absenMasuk'])->middleware('auth:web,karyawan');
Route::put('/dinas-luar/masuk/{id}', [DinasLuar::class, 'absenMasukDinas'])->middleware('auth:web,karyawan');

Route::put('/absen/pulang/{id}', [AbsenController::class, 'absenPulang'])->middleware('auth:web,karyawan');
Route::put('/dinas-luar/pulang/{id}', [DinasLuar::class, 'absenPulangDinas'])->middleware('auth:web,karyawan');

Route::get('/data-absen', [AbsenController::class, 'dataAbsen'])->middleware('admin');
Route::get('/data-dinas-luar', [DinasLuar::class, 'dataAbsenDinas'])->middleware('admin');

Route::get('/data-absen/{id}/edit-masuk', [AbsenController::class, 'editMasuk'])->middleware('admin');
Route::get('/maps/{lat}/{long}/{userid}', [AbsenController::class, 'maps'])->middleware('auth:web,karyawan');
Route::put('/data-absen/{id}/proses-edit-masuk', [AbsenController::class, 'prosesEditMasuk'])->middleware('admin');
Route::get('/data-absen/{id}/edit-pulang', [AbsenController::class, 'editPulang'])->middleware('admin');
Route::put('/data-absen/{id}/proses-edit-pulang', [AbsenController::class, 'prosesEditPulang'])->middleware('admin');
Route::delete('/data-absen/{id}/delete', [AbsenController::class, 'deleteAdmin'])->middleware('admin');

Route::get('/my-absen', [AbsenController::class, 'myAbsen'])->middleware('auth');
Route::get('/my-absen/pengajuan/{id}', [AbsenController::class, 'pengajuan'])->middleware('auth:web,karyawan');
Route::post('/my-absen/pengajuan-proses/{id}', [AbsenController::class, 'pengajuanProses'])->middleware('auth:web,karyawan');
Route::get('/my-dinas-luar', [DinasLuar::class, 'myDinasLuar'])->middleware('auth:web,karyawan');
Route::get('/pengajuan-absensi', [AbsenController::class, 'pengajuanAbsensi'])->middleware('auth:web,karyawan');
Route::get('/pengajuan-absensi/edit/{id}', [AbsenController::class, 'editPengajuanAbsensi'])->middleware('auth:web,karyawan');
Route::post('/pengajuan-absensi/update/{id}', [AbsenController::class, 'updatePengajuanAbsensi'])->middleware('auth:web,karyawan');

Route::get('/lembur', [LemburController::class, 'index'])->middleware('auth:web,karyawan');
Route::post('/lembur/masuk', [LemburController::class, 'masuk'])->middleware('auth:web,karyawan');
Route::put('/lembur/pulang/{id}', [LemburController::class, 'pulang'])->middleware('auth:web,karyawan');
Route::get('/data-lembur', [LemburController::class, 'dataLembur'])->middleware('admin');
Route::post('/data-lembur/approval/{id}', [LemburController::class, 'approval'])->middleware('admin');
Route::get('/my-lembur', [LemburController::class, 'myLembur'])->middleware('auth:web,karyawan');

Route::get('/rekap-data', [RekapDataController::class, 'index'])->middleware('admin');
Route::get('/rekap-data/export', [RekapDataController::class, 'export'])->middleware('admin');
Route::get('/rekap-data/get-data', [RekapDataController::class, 'getData'])->middleware('admin');
Route::get('/rekap-data/payroll/{id}', [RekapDataController::class, 'payroll'])->middleware('admin');
Route::post('/rekap-data/payroll/tambah', [RekapDataController::class, 'tambahPayroll'])->middleware('admin');

Route::get('/cuti', [CutiController::class, 'index'])->middleware('auth:web,karyawan');

Route::post('/cuti/tambah', [CutiController::class, 'tambah'])->middleware('auth:web,karyawan');
Route::delete('/cuti/delete/{id}', [CutiController::class, 'delete'])->middleware('auth:web,karyawan');
Route::get('/cuti/edit/{id}', [CutiController::class, 'edit'])->middleware('auth:web,karyawan');
Route::put('/cuti/proses-edit/{id}', [CutiController::class, 'editProses'])->middleware('auth:web,karyawan');
Route::get('/data-cuti', [CutiController::class, 'dataCuti'])->middleware('admin');
Route::get('/data-cuti/tambah', [CutiController::class, 'tambahAdmin'])->middleware('admin');
Route::post('/data-cuti/getuserid', [CutiController::class, 'getUserId'])->middleware('admin');
Route::post('/data-cuti/proses-tambah', [CutiController::class, 'tambahAdminProses'])->middleware('admin');
Route::delete('/data-cuti/delete/{id}', [CutiController::class, 'deleteAdmin'])->middleware('admin');
Route::get('/data-cuti/edit/{id}', [CutiController::class, 'editAdmin'])->middleware('admin');
Route::put('/data-cuti/edit-proses/{id}', [CutiController::class, 'editAdminProses'])->middleware('admin');
Route::get('/rekap-cuti/export', [CutiController::class, 'exportCuti']);
Route::get('/export-izin', [CutiController::class, 'exportIzin']);
Route::get('/data-izin', [CutiController::class, 'dataIzin'])->middleware('auth:web');
Route::get('/data-sakit', [CutiController::class, 'dataSakit'])->middleware('auth:web');
Route::get('/export-sakit', [CutiController::class, 'exportSakit']);
Route::get('/my-profile', [KaryawanController::class, 'myProfile'])->middleware('auth:web,karyawan');
Route::put('/my-profile/update/{id}', [KaryawanController::class, 'myProfileUpdate'])->middleware('auth:web,karyawan');
Route::get('/my-profile/edit-password', [KaryawanController::class, 'editPassMyProfile'])->middleware('auth:web,karyawan');
Route::put('/my-profile/edit-password-proses/{id}', [KaryawanController::class, 'editPassMyProfileProses'])->middleware('auth:web,karyawan');

Route::get('/lokasi-kantor', [LokasiController::class, 'index'])->middleware('admin');
Route::get('/lokasi-kantor/tambah', [LokasiController::class, 'tambahLokasi'])->middleware('admin');
Route::post('/lokasi-kantor/tambah-proses', [LokasiController::class, 'prosesTambahLokasi'])->middleware('admin');
Route::get('/lokasi-kantor/edit/{id}', [LokasiController::class, 'editLokasi'])->middleware('admin');
Route::put('/lokasi-kantor/update/{id}', [LokasiController::class, 'updateLokasi'])->middleware('admin');
Route::put('/lokasi-kantor/radius/{id}', [LokasiController::class, 'updateRadiusLokasi'])->middleware('admin');
Route::delete('/lokasi-kantor/delete/{id}', [LokasiController::class, 'deleteLokasi'])->middleware('admin');
Route::get('/lokasi-kantor/pending-location', [LokasiController::class, 'pendingLocation'])->middleware('admin');
Route::put('/lokasi-kantor/update-pending-location/{id}', [LokasiController::class, 'UpdatePendingLocation'])->middleware('admin');

Route::get('/request-location', [LokasiController::class, 'requestLocation'])->middleware('auth:web,karyawan');
Route::get('/request-location/tambah', [LokasiController::class, 'tambahRequestLocation'])->middleware('auth:web,karyawan');
Route::post('/request-location/tambah-proses', [LokasiController::class, 'prosesTambahRequestLocation'])->middleware('auth:web,karyawan');
Route::get('/request-location/edit/{id}', [LokasiController::class, 'editRequestLocation'])->middleware('auth:web,karyawan');
Route::put('/request-location/update/{id}', [LokasiController::class, 'updateRequestLocation'])->middleware('auth:web,karyawan');
Route::put('/request-location/radius/{id}', [LokasiController::class, 'updateRadiusRequestLocation'])->middleware('auth:web,karyawan');
Route::delete('/request-location/delete/{id}', [LokasiController::class, 'deleteRequestLocation'])->middleware('auth:web,karyawan');

Route::get('/reset-cuti', [KaryawanController::class, 'resetCuti'])->middleware('admin');
Route::put('/reset-cuti/{id}', [KaryawanController::class, 'resetCutiProses'])->middleware('admin');

Route::get('/jabatan', [jabatanController::class, 'index'])->middleware('admin');
Route::get('/jabatan/create', [jabatanController::class, 'create'])->middleware('admin');
Route::post('/jabatan/insert', [jabatanController::class, 'insert'])->middleware('admin');
Route::get('/jabatan/edit/{id}', [jabatanController::class, 'edit'])->middleware('admin');
Route::put('/jabatan/update/{id}', [jabatanController::class, 'update'])->middleware('admin');
Route::delete('/jabatan/delete/{id}', [jabatanController::class, 'delete'])->middleware('admin');

Route::get('/golongan', [GolonganController::class, 'index'])->middleware('admin');
Route::get('/golongan/tambah', [GolonganController::class, 'tambah'])->middleware('admin');
Route::post('/golongan/tambah-proses', [GolonganController::class, 'tambahProses'])->middleware('admin');
Route::get('/golongan/edit/{id}', [GolonganController::class, 'edit'])->middleware('admin');
Route::put('/golongan/update/{id}', [GolonganController::class, 'update'])->middleware('admin');
Route::delete('/golongan/delete/{id}', [GolonganController::class, 'delete'])->middleware('admin');

Route::get('/dokumen', [DokumenController::class, 'index'])->middleware('admin');
Route::get('/dokumen/tambah', [DokumenController::class, 'tambah'])->middleware('admin');
Route::post('/dokumen/tambah-proses', [DokumenController::class, 'tambahProses'])->middleware('admin');
Route::get('/dokumen/edit/{id}', [DokumenController::class, 'edit'])->middleware('admin');
Route::put('/dokumen/edit-proses/{id}', [DokumenController::class, 'editProses'])->middleware('admin');
Route::delete('/dokumen/delete/{id}', [DokumenController::class, 'delete'])->middleware('admin');
Route::get('/my-dokumen', [DokumenController::class, 'myDokumen'])->middleware('auth:web,karyawan');
Route::get('/my-dokumen/tambah', [DokumenController::class, 'myDokumenTambah'])->middleware('auth:web,karyawan');
Route::post('/my-dokumen/tambah-proses', [DokumenController::class, 'myDokumenTambahProses'])->middleware('auth:web,karyawan');
Route::get('/my-dokumen/edit/{id}', [DokumenController::class, 'myDokumenEdit'])->middleware('auth:web,karyawan');
Route::put('/my-dokumen/edit-proses/{id}', [DokumenController::class, 'myDokumenEditProses'])->middleware('auth:web,karyawan');
Route::delete('/my-dokumen/delete/{id}', [DokumenController::class, 'myDokumenDelete'])->middleware('auth:web,karyawan');

Route::get('/auto-shift', [AutoShiftController::class, 'index'])->middleware('admin');
Route::get('/auto-shift/tambah', [AutoShiftController::class, 'tambah'])->middleware('admin');
Route::post('/auto-shift/store', [AutoShiftController::class, 'store'])->middleware('admin');
Route::get('/auto-shift/{id}/edit', [AutoShiftController::class, 'edit'])->middleware('admin');
Route::put('/auto-shift/update/{id}', [AutoShiftController::class, 'update'])->middleware('admin');
Route::delete('/auto-shift/delete/{id}', [AutoShiftController::class, 'delete'])->middleware('admin');

Route::get('/file', [FileController::class, 'index'])->middleware('admin');
Route::get('/file/upload', [FileController::class, 'upload'])->middleware('admin');
Route::post('/file/upload-proses', [FileController::class, 'uploadProses'])->middleware('admin');
Route::get('/file/edit/{id}', [FileController::class, 'edit'])->middleware('admin');
Route::put('/file/update/{id}', [FileController::class, 'update'])->middleware('admin');
Route::delete('/file/delete/{id}', [FileController::class, 'delete'])->middleware('admin');

Route::get('/my-file', [FileController::class, 'myFile'])->middleware('auth:web,karyawan');
Route::get('/my-file/upload', [FileController::class, 'myFileUpload'])->middleware('auth:web,karyawan');
Route::post('/my-file/upload-proses', [FileController::class, 'myFileUploadProses'])->middleware('auth:web,karyawan');
Route::get('/my-file/edit/{id}', [FileController::class, 'myFileEdit'])->middleware('auth:web,karyawan');
Route::put('/my-file/update/{id}', [FileController::class, 'myFileUpdate'])->middleware('auth:web,karyawan');
Route::delete('/my-file/delete/{id}', [FileController::class, 'myFileDelete'])->middleware('auth:web,karyawan');

Route::get('/tunjangan', [TunjanganController::class, 'index'])->middleware('admin');
Route::get('/tunjangan/tambah', [TunjanganController::class, 'tambah'])->middleware('admin');
Route::post('/tunjangan/tambah-proses', [TunjanganController::class, 'tambahProses'])->middleware('admin');
Route::get('/tunjangan/{id}/edit', [TunjanganController::class, 'edit'])->middleware('admin');
Route::put('/tunjangan/{id}/update', [TunjanganController::class, 'update'])->middleware('admin');
Route::delete('/tunjangan/{id}/delete', [TunjanganController::class, 'delete'])->middleware('admin');

Route::get('/payroll', [PayrollController::class, 'index'])->middleware('auth:web,karyawan');
Route::get('/payroll/tambah', [PayrollController::class, 'tambah'])->middleware('admin');
Route::post('/payroll/tambah-proses', [PayrollController::class, 'tambahProses'])->middleware('admin');
Route::get('/payroll/{id}/edit', [PayrollController::class, 'edit'])->middleware('admin');
Route::get('/payroll/{id}/download', [PayrollController::class, 'download'])->middleware('auth:web,karyawan');
Route::put('/payroll/{id}/update', [PayrollController::class, 'update'])->middleware('admin');
Route::delete('/payroll/{id}/delete', [PayrollController::class, 'delete'])->middleware('admin');

Route::get('/kasbon', [KasbonController::class, 'index'])->middleware('auth:web,karyawan');
Route::get('/kasbon/tambah', [KasbonController::class, 'tambah'])->middleware('auth:web,karyawan');
Route::post('/kasbon/tambah-proses', [KasbonController::class, 'tambahProses'])->middleware('auth:web,karyawan');
Route::get('/kasbon/edit/{id}', [KasbonController::class, 'edit'])->middleware('auth:web,karyawan');
Route::put('/kasbon/update/{id}', [KasbonController::class, 'update'])->middleware('auth:web,karyawan');
Route::delete('/kasbon/delete/{id}', [KasbonController::class, 'delete'])->middleware('auth:web,karyawan');

Route::get('/status-ptkp', [StatusPtkpController::class, 'index'])->middleware('admin');
Route::get('/status-ptkp/tambah', [StatusPtkpController::class, 'tambah'])->middleware('admin');
Route::post('/status-ptkp/tambah-proses', [StatusPtkpController::class, 'tambahProses'])->middleware('admin');
Route::get('/status-ptkp/{id}/edit', [StatusPtkpController::class, 'edit'])->middleware('admin');
Route::put('/status-ptkp/{id}/update', [StatusPtkpController::class, 'update'])->middleware('admin');
Route::delete('/status-ptkp/{id}/delete', [StatusPtkpController::class, 'delete'])->middleware('admin');

Route::get('/pajak-pph21', [PajakController::class, 'index'])->middleware('admin');
Route::get('/pajak-pph21/tambah', [PajakController::class, 'tambah'])->middleware('admin');
Route::post('/pajak-pph21/tambah-proses', [PajakController::class, 'tambahProses'])->middleware('admin');
Route::get('/pajak-pph21/{id}/edit', [PajakController::class, 'edit'])->middleware('admin');
Route::put('/pajak-pph21/{id}/update', [PajakController::class, 'update'])->middleware('admin');
Route::delete('/pajak-pph21/{id}/delete', [PajakController::class, 'delete'])->middleware('admin');

Route::get('/data-absen/export', [AbsenController::class, 'exportDataAbsen'])->middleware('admin');

Route::get('/index-user',[UserControler::class,'index'])->middleware('admin')->name('index-user');
Route::get('/add-user',[UserControler::class,'tambah'])->middleware('admin');
Route::post('/add-user/proses',[UserControler::class,'store'])->middleware('admin');
Route::get('/user/edit/{id}',[UserControler::class,'edit'])->middleware('admin');
Route::put('/user/edit/proses/{id}',[UserControler::class,'update'])->middleware('admin');
Route::delete('/user/delete/{id}', [UserControler::class, 'destroy'])->middleware('admin');

Route::get('/reset', function () {
    Artisan::call('optimize');
    Artisan::call('config:cache');
    Artisan::call('route:clear');
    Artisan::call('migrate:fresh --seed');
    Artisan::call('storage:link');
});
