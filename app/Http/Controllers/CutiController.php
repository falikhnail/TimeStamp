<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use App\Models\User;
use App\Models\MappingShift;
use Illuminate\Http\Request;
use App\Events\NotifApproval;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use App\Exports\CutiExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\IzinExport;
use App\Exports\SakitExport;


class CutiController extends Controller
{
    public function index()
    {
        $user_id = auth()->user()->id;
        $user = Karyawan::findOrFail(auth()->user()->id);

        $mulai = request()->input('mulai');
        $akhir = request()->input('akhir');

        $cuti = Cuti::where('karyawan_id', $user_id)
            ->when($mulai && $akhir, function ($query) use ($mulai, $akhir) {
                return $query->whereBetween('tanggal', [$mulai, $akhir]);
            })
            ->orderBy('id', 'desc')->paginate(10)->withQueryString();

        if (auth()->user()->is_admin == 'admin') {
            return view('cuti.index', [
                'title' => 'Tambah Permintaan Cuti Karyawan',
                'data_user' => $user,
                'data_cuti_user' => $cuti
            ]);
        } else {
            return view('cuti.indexuser', [
                'title' => 'Tambah Permintaan Cuti Karyawan',
                'data_user' => $user,
                'data_cuti_user' => $cuti
            ]);
        }
    }

    public function tambah(Request $request)
    {
        // dd($request);
        // Atur timezone
        date_default_timezone_set('Asia/Jakarta');

        // Set default values for dates if they are null
        if (!$request->filled('tanggal_mulai')) {
            $request['tanggal_mulai'] = $request['tanggal_akhir'];
        }

        if (!$request->filled('tanggal_akhir')) {
            $request['tanggal_akhir'] = $request['tanggal_mulai'];
        }

        // Set validation rules based on the category
        $rules = [
            'karyawan_id' => 'required',
            'alasan_cuti' => 'required',
            'foto_cuti' => 'nullable|image|file|max:10240',
        ];

        // if ($request->kategori === 'cuti') {
        //     $rules['jenis_cuti'] = 'required'; // Ensure this field is validated
        // } elseif ($request->kategori === 'izin') {
        //     $rules['jenis_izin'] = 'required'; // Ensure this field is validated
        //     $rules['jam_awal'] = 'required';
        //     $rules['jam_akhir'] = 'required';
        // }

        // Validasi data request
        $validatedData = $request->validate($rules);

        // Handle file upload
        if ($request->hasFile('foto_cuti')) {
            $validatedData['foto_cuti'] = $request->file('foto_cuti')->store('foto_cuti');
        }

        // Add category-specific field data
        if ($request->kategori === 'cuti') {
            $validatedData['nama_cuti'] = $request->input('jenis_cuti');
        } elseif ($request->kategori === 'izin' && $request->jenis_izin === 'Izin Meninggalkan Pekerjaan') {
            $validatedData['nama_cuti'] = $request->input('jenis_izin');
            $validatedData['jam_awal'] = $request->input('jam_awal');
            $validatedData['jam_akhir'] = $request->input('jam_akhir');
        } elseif ($request->kategori === 'izin') {
            $validatedData['nama_cuti'] = $request->input('jenis_izin');
        } else {
            $validatedData['nama_cuti'] = $request->kategori;
        }

        // Set tanggal_mulai dan tanggal_akhir langsung ke validated data
        $validatedData['tanggal'] = $request->input('tanggal_mulai');
        $validatedData['tanggal_akhir'] = $request->input('tanggal_akhir');
        $validatedData['status_cuti'] = 'Pending'; // Set default status

        // Simpan data ke tabel Cuti
        Cuti::create($validatedData);

        // Notifikasi untuk admin
        $users = User::where('is_admin', 'admin')->get();
        foreach ($users as $user) {
            $type = 'Approval';
            $notif = 'Pengajuan Cuti Dari ' . auth()->user()->name . ' Butuh Approval Anda';
            $url = url('/data-cuti?mulai=' . $request["tanggal_mulai"] . '&akhir=' . $request["tanggal_akhir"]);

            $user->messages = [
                'user_id'   =>  auth()->user()->id,
                'from'   =>  auth()->user()->name,
                'message'   =>  $notif,
                'action'   =>  '/data-cuti?mulai=' . $request["tanggal_mulai"] . '&akhir=' . $request["tanggal_akhir"]
            ];
            $user->notify(new \App\Notifications\UserNotification);

            NotifApproval::dispatch($type, $user->id, $notif, $url);
        }

        return redirect('/cuti')->with('success', 'Data Berhasil Ditambahkan');
    }


    public function delete($id)
    {
        $delete = Cuti::find($id);
        // Storage::delete($delete->foto_cuti);
        $delete->delete();
        return back()->with('success', 'Data Berhasil di Delete');
    }

    public function edit($id)
    {
        if (auth()->user()->is_admin == 'admin') {
            return view('cuti.edit', [
                'title' => 'Edit Permintaan Cuti',
                'data_cuti_user' => Cuti::findOrFail($id)
            ]);
        } else {
            return view('cuti.edituser', [
                'title' => 'Edit Permintaan Cuti',
                'data_cuti_user' => Cuti::findOrFail($id)
            ]);
        }
    }

    public function editProses(Request $request, $id)
    {
        $validatedData = $request->validate([
            'karyawan_id' => 'required',
            'nama_cuti' => 'required',
            'tanggal' => 'required',
            'alasan_cuti' => 'required',
            'foto_cuti' => 'image|file|max:10240',
        ]);

        if ($request->file('foto_cuti')) {
            // if ($request->foto_cuti_lama) {
            //     Storage::delete($request->foto_cuti_lama);
            // }
            $validatedData['foto_cuti'] = $request->file('foto_cuti')->store('foto_cuti');
        }

        Cuti::where('id', $id)->update($validatedData);
        $request->session()->flash('success', 'Data Berhasil di Update');
        return redirect('/cuti');
    }

    public function dataCuti()
    {
        // Ambil input dari request
        $mulai = request()->input('mulai');
        $akhir = request()->input('akhir');
        $status = request()->input('status');
        $namaCuti = request()->input('nama_cuti');

        // Ambil user yang sedang login
        $user = auth()->user();

        // Query dasar untuk mengambil data cuti
        $cuti = Cuti::whereIn('nama_cuti', [
            'Cuti Melahirkan',
            'Cuti Menikah',
            'Cuti Keguguran',
            'Cuti Keluarga Meninggal',
            'Cuti Keluarga Meninggal Atap',
            'Cuti Ibadah Besar',
            'Cuti Menikahkan Anak',
            'Cuti Istri Melahirkan/Keguguran',
            'Cuti Khitanan Anak',
            'Cuti Membabtiskan Anak',
            'Cuti Tahunan'
        ]);

        // Filter berdasarkan role admin_divisi
        if ($user->is_admin == 'admin_divisi') {
            // Jika admin divisi, hanya tampilkan data karyawan yang divisinya sama
            $cuti = $cuti->whereHas('karyawan', function ($query) use ($user) {
                $query->where('jabatan_id', $user->jabatan_id);
            });
        }

        // Filter berdasarkan input tanggal, status, dan nama cuti
        $cuti = $cuti->when($mulai && $akhir, function ($query) use ($mulai, $akhir) {
            return $query->whereBetween('tanggal', [$mulai, $akhir]);
        })
            ->when($status, function ($query) use ($status) {
                return $query->where('status_cuti', $status);
            })
            ->when($namaCuti, function ($query) use ($namaCuti) {
                return $query->where('nama_cuti', $namaCuti);
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Kembalikan view dengan data cuti
        return view('cuti.datacuti', [
            'title' => 'Data Cuti Karyawan',
            'data_cuti' => $cuti
        ]);
    }




    // public function dataCuti()
    // {
    //     $mulai = request()->input('mulai');
    //     $akhir = request()->input('akhir');

    //     $cuti = Cuti::when($mulai && $akhir, function ($query) use ($mulai, $akhir) {
    //                     return $query->whereBetween('tanggal', [$mulai, $akhir]);

    //                 })
    //                 ->orderBy('id', 'desc')->paginate(10)->withQueryString();

    //     return view('cuti.datacuti', [
    //         'title' => 'Data Cuti Karyawan',
    //         'data_cuti' => $cuti
    //     ]);
    // }

    public function dataSakit()
{
    $mulai = request()->input('mulai');
    $akhir = request()->input('akhir');
    $status = request()->input('status');

    // Dapatkan user yang sedang login
    $user = auth()->user();

    // Query dasar untuk mengambil data izin sakit
    $query = Cuti::whereIn('nama_cuti', [
        'Izin Sakit'
    ]);

    // Filter berdasarkan tanggal mulai dan tanggal akhir jika ada
    if ($mulai && $akhir) {
        $query->whereBetween('tanggal', [$mulai, $akhir]);
    }

    // Filter berdasarkan status jika ada
    if ($status) {
        $query->where('status_cuti', $status);
    }

    // Jika user adalah admin divisi, hanya ambil data karyawan yang divisinya sama
    if ($user->is_admin === 'admin_divisi') {
        $query->whereHas('karyawan', function ($query) use ($user) {
            $query->where('jabatan_id', $user->jabatan_id);
        });
    }

    // Dapatkan hasil dengan paginate
    $cuti = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

    // Kembalikan tampilan dengan data yang difilter
    return view('cuti.sakit', [
        'title' => 'Data Sakit Karyawan',
        'data_cuti' => $cuti
    ]);
}


    public function exportSakit(Request $request)
    {
        $mulai = $request->input('mulai');
        $akhir = $request->input('akhir');
        $status = $request->input('status');

        // Dapatkan user yang sedang login
        $user = auth()->user();

        return Excel::download(new SakitExport($mulai, $akhir,$status, $user), 'izin_sakit.xlsx');
    }



    public function tambahAdmin()
    {
        return view('cuti.tambahadmin', [
            'title' => 'Tambah Cuti Pegawai',
            'data_user' => Karyawan::select('id', 'name')->get()
        ]);
    }

    public function getUserId(Request $request)
    {
        $id = $request->input('id');
        $data_user = Karyawan::findOrFail($id);

        // Data cuti
        $data_cuti = [
            'cuti' => [
                'Cuti' => $data_user->izin_cuti,
                'Cuti Melahirkan' => $data_user->cuti_melahirkan,
                'Cuti Menikah' => $data_user->cuti_menikah,
                'Cuti Keguguran' => $data_user->cuti_keguguran,
                'Cuti Istri Melahirkan' => $data_user->cuti_istri_melahirkan,
                'Cuti Menikahkan Anak' => $data_user->cuti_menikahkan_anak,
                'Cuti Khitanan Anak' => $data_user->cuti_khitanan_anak,
                'Cuti Membabtiskan Anak' => $data_user->cuti_membabtiskan_anak,
                'Cuti Keluarga Atap' => $data_user->cuti_keluarga_atap,
                'Cuti Keluarga' => $data_user->cuti_keluarga,
                'Cuti Ibadah Besar' => $data_user->cuti_ibadah_besar,
            ],
            'izin' => [
                'Izin Sakit',
                'Izin Masuk',
                'Izin Telat',
                'Izin Pulang Cepat'
            ]
        ];

        $kategori = $request->input('kategori');
        $options = '';

        if ($kategori == 'cuti') {
            foreach ($data_cuti['cuti'] as $nama => $jumlah) {
                $options .= "<option value='{$nama}'> {$nama} ({$jumlah})</option>";
            }
        } elseif ($kategori == 'izin') {
            foreach ($data_cuti['izin'] as $izin) {
                $options .= "<option value='{$izin}'>{$izin}</option>";
            }
        }

        return response()->json(['options' => $options]);
    }


    // public function getUserId(Request $request)
    // {
    //     $id = $request["id"];
    //     $data_user = Karyawan::findOrfail($id);

    //     $izin_cuti = $data_user->izin_cuti;
    //     $cuti_sakit = $data_user->cuti_sakit;
    //     $cuti_melahirkan = $data_user->cuti_melahirkan;
    //     $cuti_menikah = $data_user->cuti_menikah;
    //     $cuti_keguguran = $data_user->cuti_keguguran;
    //     $cuti_istri_melahirkan = $data_user->cuti_istri_melahirkan;
    //     $cuti_menikahkan_anak = $data_user->cuti_menikahkan_anak;
    //     $cuti_khitanan_anak = $data_user->cuti_khitanan_anak;
    //     $cuti_membabtiskan_anak = $data_user->cuti_membabtiskan_anak;
    //     $cuti_keluarga_atap = $data_user->cuti_keluarga_atap;
    //     $cuti_keluarga = $data_user->cuti_keluarga;
    //     $cuti_ibadah_besar = $data_user->cuti_ibadah_besar;


    //     $data_cuti = array(
    //         [
    //             'nama' => 'Cuti',
    //             'nama_cuti' => 'Cuti ('.$izin_cuti.')'
    //         ],
    //         [
    //             'nama' => 'Cuti Sakit',
    //             'nama_cuti' => 'Cuti Sakit ('.$cuti_sakit.')'
    //         ],
    //         [
    //             'nama' => 'Cuti Melahirkan',
    //             'nama_cuti' => 'Cuti Melahirkan ('.$cuti_melahirkan.')'
    //         ],
    //         [
    //             'nama' => 'Cuti Menikah',
    //             'nama_cuti' => 'Cuti Menikah ('.$cuti_menikah.')'
    //         ],
    //         [
    //             'nama' => 'Cuti Kegururan',
    //             'nama_cuti' => 'Cuti Keguguran ('.$cuti_keguguran.')'
    //         ],
    //         [
    //             'nama' => 'Cuti Istri Melahirkan',
    //             'nama_cuti' => 'Cuti Istri Melahirkan ('.$cuti_istri_melahirkan.')'
    //         ],
    //         [
    //             'nama' => 'Cuti Menikahkan Anak',
    //             'nama_cuti' => 'Cuti Menikahkan Anak ('.$cuti_menikahkan_anak.')'
    //         ],
    //         [
    //             'nama' => 'Cuti Khitanan Anak',
    //             'nama_cuti' => 'Cuti Khitanan ('.$cuti_khitanan_anak.')'
    //         ],
    //         [
    //             'nama' => 'Cuti Membabtiskan Anak',
    //             'nama_cuti' => 'Cuti Membabtiskan Anak ('.$cuti_membabtiskan_anak.')'
    //         ],
    //         [
    //             'nama' => 'Cuti Keluarga Atap',
    //             'nama_cuti' => 'Cuti Keluarga Atap ('.$cuti_keluarga_atap.')'
    //         ],
    //         [
    //             'nama' => 'Cuti Keluarga ',
    //             'nama_cuti' => 'Cuti Keluarga  ('.$cuti_keluarga.')'
    //         ],
    //         [
    //             'nama' => 'Cuti Ibadah Besar',
    //             'nama_cuti' => 'Cuti Ibadah Besar ('.$cuti_ibadah_besar.')'
    //         ],
    //         [
    //             'nama' => 'Izin Masuk',
    //             'nama_cuti' => 'Izin Masuk '
    //         ],
    //         [
    //             'nama' => 'Izin Telat',
    //             'nama_cuti' => 'Izin Telat'
    //         ],
    //         [
    //             'nama' => 'Izin Pulang Cepat',
    //             'nama_cuti' => 'Izin Pulang Cepat'
    //         ]
    //     );

    //     echo "<option value='' selected>Pilih Cuti</option>";
    //     foreach($data_cuti as $dc){
    //         echo "
    //             <option value='$dc[nama]'>$dc[nama_cuti]</option>
    //         ";
    //     }
    // }

    public function tambahAdminProses(Request $request)
    {
        // dd($request);
        date_default_timezone_set('Asia/Jakarta');

        if ($request["tanggal_mulai"] == null) {
            $request["tanggal_mulai"] = $request["tanggal_akhir"];
        } else {
            $request["tanggal_mulai"] = $request["tanggal_mulai"];
        }

        if ($request["tanggal_akhir"] == null) {
            $request["tanggal_akhir"] = $request["tanggal_mulai"];
        } else {
            $request["tanggal_akhir"] = $request["tanggal_akhir"];
        }

        $begin = new \DateTime($request["tanggal_mulai"]);
        $end = new \DateTime($request["tanggal_akhir"]);
        $end = $end->modify('+1 day');

        $interval = new \DateInterval('P1D'); //referensi : https://en.wikipedia.org/wiki/ISO_8601#Durations
        $daterange = new \DatePeriod($begin, $interval, $end);

        foreach ($daterange as $date) {
            $request["tanggal"] = $date->format("Y-m-d");

            $request['status_cuti'] = "Pending";
            $validatedData = $request->validate([
                'karyawan_id' => 'required',
                'tanggal' => 'required',
                'alasan_cuti' => 'required',
                'foto_cuti' => 'image|file|max:10240',
                'status_cuti' => 'required',
                'tanggal_approve' => 'nullable'
            ]);

            if ($request->input('kategori') == 'cuti') {
                $validatedData['nama_cuti'] = $request->input('nama_cuti');
            } elseif ($request->input('kategori') == 'izin') {
                $validatedData['nama_cuti'] = $request->input('nama_izin');
            }

            if ($request->file('foto_cuti')) {
                $validatedData['foto_cuti'] = $request->file('foto_cuti')->store('foto_cuti');
            }

            Cuti::create($validatedData);
        }

        return redirect('/data-cuti')->with('success', 'Data Berhasil di Tambahkan');
    }

    public function deleteAdmin($id)
    {
        $delete = Cuti::find($id);
        // Storage::delete($delete->foto_cuti);
        $delete->delete();
        return redirect('/data-cuti')->with('success', 'Data Berhasil di Delete');
    }

    public function editAdmin($id)
    {
        return view('cuti.editadmin', [
            'title' => 'Edit Cuti Karyawan',
            'data_cuti_karyawan' => Cuti::findOrFail($id)
        ]);
    }

    public function editAdminProses(Request $request, $id)
    {
        date_default_timezone_set('Asia/Jakarta');

        $cuti = Cuti::find($id);
        $validated = $request->validate([
            'nama_cuti' => 'required',
            'tanggal' => 'required',
            'status_cuti' => 'required',
            'catatan' => 'nullable',
        ]);

        // Update status_cuti dan tanggal_approve jika status_cuti adalah 'Diterima' atau 'Ditolak'
        $cuti->status_cuti = $request['status_cuti'];
        if ($request['status_cuti'] == 'Diterima' || $request['status_cuti'] == 'Ditolak') {
            $cuti->tanggal_approve = now(); // Set tanggal_approve ke tanggal dan waktu saat ini
        }
        $cuti->update($validated);

        $user = Karyawan::find($cuti->karyawan_id);

        // Jika cuti diterima, lakukan pembaruan pada tabel MappingShift
        if ($request["status_cuti"] == "Diterima") {
            // Kurangi jumlah cuti pada karyawan sesuai jenis cuti
            if ($request["nama_cuti"] == "Cuti") {
                $user->update(['izin_cuti' => $user->izin_cuti - 1]);
            } elseif ($request["nama_cuti"] == "Cuti Menikah") {
                $user->update(['cuti_menikah' => $user->cuti_menikah - 1]);
            } elseif ($request["nama_cuti"] == "Cuti Melahirkan") {
                $user->update(['cuti_melahirkan' => $user->cuti_melahirkan - 1]);
            } elseif ($request["nama_cuti"] == "Cuti Istri Melahirkan") {
                $user->update(['cuti_istri_melahirkan' => $user->cuti_istri_melahirkan - 1]);
            } elseif ($request["nama_cuti"] == "Cuti Khitanan Anak") {
                $user->update(['cuti_khitanan_anak' => $user->cuti_khitanan_anak - 1]);
            } elseif ($request["nama_cuti"] == "Cuti Membabtiskan Anak") {
                $user->update(['cuti_membabtiskan_anak' => $user->cuti_membabtiskan_anak - 1]);
            } elseif ($request["nama_cuti"] == "Cuti Keluarga Meninggal Atap") {
                $user->update(['cuti_keluarga_atap' => $user->cuti_keluarga_atap - 1]);
            } elseif ($request["nama_cuti"] == "Cuti Keluarga Meninggal") {
                $user->update(['cuti_keluarga' => $user->cuti_keluarga - 1]);
            } elseif ($request["nama_cuti"] == "Cuti Ibadah Besar") {
                $user->update(['cuti_ibadah_besar' => $user->cuti_ibadah_besar - 1]);
            } elseif ($request["nama_cuti"] == "Cuti Menikahkan Anak") {
                $user->update(['cuti_menikahkan_anak' => $user->cuti_menikahkan_anak - 1]);
            }

            // Buat range tanggal dari tanggal mulai sampai tanggal akhir cuti
            $startDate = new \DateTime($cuti->tanggal);
            $endDate = new \DateTime($cuti->tanggal_akhir);
            $interval = new \DateInterval('P1D'); // Interval per hari
            $dateRange = new \DatePeriod($startDate, $interval, $endDate->modify('+1 day'));

            // Loop setiap tanggal dalam rentang dan update/create MappingShift
            foreach ($dateRange as $date) {
                $formattedDate = $date->format('Y-m-d');

                // Cek apakah sudah ada mapping shift untuk tanggal tersebut
                $mapping_shift = MappingShift::where('tanggal', $formattedDate)
                    ->where('karyawan_id', $cuti->karyawan_id)
                    ->first();

                if ($mapping_shift) {
                    // Jika sudah ada, update status_absen
                    if ($request["nama_cuti"] == "Izin Meninggalkan Pekerjaan") {
                        // Status absen tetap "Masuk" jika nama cuti adalah "Izin Meninggalkan Pekerjaan"
                        $mapping_shift->update(['status_absen' => "Masuk"]);
                    } else {
                        $mapping_shift->update(['status_absen' => $request["nama_cuti"]]);
                    }
                } else {
                    // Jika tidak ada, buat entry baru
                    MappingShift::create([
                        'karyawan_id' => $cuti->karyawan_id,
                        'tanggal' => $formattedDate,
                        'status_absen' => $request["nama_cuti"] === "Izin Meninggalkan Pekerjaan" ? "Masuk" : $request["nama_cuti"]
                    ]);
                }
            }

            $type = 'Approved';
            $notif = 'Cuti Anda Telah Diterima Oleh ' . auth()->user()->name;
            $url = url('/cuti?mulai=' . $cuti->tanggal . '&akhir=' . $cuti->tanggal_akhir);

            $user->messages = [
                'karyawan_id'   =>  auth()->user()->id,
                'from'   =>  auth()->user()->name,
                'message'   =>  $notif,
                'action'   =>  $url
            ];
            $user->notify(new \App\Notifications\UserNotification);

            NotifApproval::dispatch($type, $user->id, $notif, $url);
        } else if ($request["status_cuti"] == "Ditolak") {
            $type = 'Rejected';
            $notif = 'Cuti Anda Telah Ditolak Oleh ' . auth()->user()->name;
            $url = url('/cuti?mulai=' . $cuti->tanggal . '&akhir=' . $cuti->tanggal_akhir);

            $user->messages = [
                'karyawan_id'   =>  auth()->user()->id,
                'from'   =>  auth()->user()->name,
                'message'   =>  $notif,
                'action'   =>  $url
            ];
            $user->notify(new \App\Notifications\UserNotification);

            NotifApproval::dispatch($type, $user->id, $notif, $url);
        }

        $request->session()->flash('success', 'Data Berhasil di Update');
        return redirect('/data-cuti');
    }

    public function exportCuti(Request $request)
    {
        // Ambil filter dari request
        $mulai = $request->input('mulai');
        $akhir = $request->input('akhir');
        $status = $request->input('status');
        $namaCuti = $request->input('nama_cuti');

        // Ambil user yang sedang login
        $user = auth()->user();

        // Query dasar untuk mengambil data cuti
        $cutiQuery = Cuti::whereIn('nama_cuti', [
            'Cuti Melahirkan',
            'Cuti Menikah',
            'Cuti Keguguran',
            'Cuti Keluarga Meninggal',
            'Cuti Keluarga Meninggal Atap',
            'Cuti Ibadah Besar',
            'Cuti Menikahkan Anak',
            'Cuti Istri Melahirkan/Keguguran',
            'Cuti Khitanan Anak',
            'Cuti Membabtiskan Anak',
            'Cuti Tahunan'
        ]);

        // Filter berdasarkan role admin_divisi
        if ($user->is_admin == 'admin_divisi') {
            // Jika admin divisi, hanya tampilkan data karyawan yang divisinya sama
            $cutiQuery->whereHas('karyawan', function ($query) use ($user) {
                $query->where('jabatan_id', $user->jabatan_id);
            });
        }

        // Filter berdasarkan input tanggal, status, dan nama cuti
        $cutiQuery->when($mulai && $akhir, function ($query) use ($mulai, $akhir) {
            return $query->whereBetween('tanggal', [$mulai, $akhir]);
        })
            ->when($status, function ($query) use ($status) {
                return $query->where('status_cuti', $status);
            })
            ->when($namaCuti, function ($query) use ($namaCuti) {
                return $query->where('nama_cuti', $namaCuti);
            });

        // Ambil data cuti
        $dataCuti = $cutiQuery->get();

        // Panggil class export dengan data yang sudah difilter
        return Excel::download(new CutiExport($dataCuti), 'Data_Cuti.xlsx');
    }





    // public function editAdminProsess(Request $request, $id)
    // {
    //     date_default_timezone_set('Asia/Jakarta');

    //     // dd($id);
    //     $cuti = Cuti::find($id);
    //     $validated = $request->validate([
    //         'nama_cuti' => 'required',
    //         'tanggal' => 'required',
    //         'status_cuti' => 'required',
    //         'catatan' => 'nullable',
    //     ]);
    //     $cuti->update($validated);

    //     $user = Karyawan::find($cuti->karyawan_id);
    //     $mapping_shift = MappingShift::where('tanggal', $request['tanggal'])->where('karyawan_id', $cuti->karyawan_id)->first();

    //     if ($request["status_cuti"] == "Diterima") {
    //         if($request["nama_cuti"] == "Cuti") {
    //             $user->update([
    //                 'izin_cuti' => $user->izin_cuti - 1
    //             ]);

    //             if ($mapping_shift) {
    //                 $mapping_shift->update([
    //                     'status_absen' => $request["nama_cuti"]
    //                 ]);
    //             } else {
    //                 MappingShift::create([
    //                     'karyawan_id' => $cuti->karyawan_id,
    //                     'tanggal' => $cuti->tanggal,
    //                     'status_absen' => $request["nama_cuti"]
    //                 ]);
    //             }
    //         } else if($request["nama_cuti"] == "Cuti Sakit") {
    //             $user->update([
    //                 'izin_lainnya' => $user->cuti_sakit - 1
    //             ]);

    //             if ($mapping_shift) {
    //                 $mapping_shift->update([
    //                     'status_absen' => $request["nama_cuti"]
    //                 ]);
    //             } else {
    //                 MappingShift::create([
    //                     'karyawan_id' => $cuti->karyawan_id,
    //                     'tanggal' => $cuti->tanggal,
    //                     'status_absen' => $request["nama_cuti"]
    //                 ]);
    //             }
    //         } else if($request["nama_cuti"] == "Cuti Melahirkan") {
    //             $user->update([
    //                 'izin_lainnya' => $user->cuti_melahirkan - 1
    //             ]);

    //             if ($mapping_shift) {
    //                 $mapping_shift->update([
    //                     'status_absen' => $request["nama_cuti"]
    //                 ]);
    //             } else {
    //                 MappingShift::create([
    //                     'karyawan_id' => $cuti->karyawan_id,
    //                     'tanggal' => $cuti->tanggal,
    //                     'status_absen' => $request["nama_cuti"]
    //                 ]);
    //             }
    //         } else if($request["nama_cuti"] == "Cuti Keguguran") {
    //             $user->update([
    //                 'izin_lainnya' => $user->cuti_keguguran - 1
    //             ]);

    //             if ($mapping_shift) {
    //                 $mapping_shift->update([
    //                     'status_absen' => $request["nama_cuti"]
    //                 ]);
    //             } else {
    //                 MappingShift::create([
    //                     'karyawan_id' => $cuti->karyawan_id,
    //                     'tanggal' => $cuti->tanggal,
    //                     'status_absen' => $request["nama_cuti"]
    //                 ]);
    //             }
    //         } else if($request["nama_cuti"] == "Cuti Sakit") {
    //             $user->update([
    //                 'izin_lainnya' => $user->cuti_sakit - 1
    //             ]);

    //             if ($mapping_shift) {
    //                 $mapping_shift->update([
    //                     'status_absen' => $request["nama_cuti"]
    //                 ]);
    //             } else {
    //                 MappingShift::create([
    //                     'karyawan_id' => $cuti->karyawan_id,
    //                     'tanggal' => $cuti->tanggal,
    //                     'status_absen' => $request["nama_cuti"]
    //                 ]);
    //             }
    //         } else if($request["nama_cuti"] == "Cuti Sakit") {
    //             $user->update([
    //                 'izin_lainnya' => $user->cuti_sakit - 1
    //             ]);

    //             if ($mapping_shift) {
    //                 $mapping_shift->update([
    //                     'status_absen' => $request["nama_cuti"]
    //                 ]);
    //             } else {
    //                 MappingShift::create([
    //                     'karyawan_id' => $cuti->karyawan_id,
    //                     'tanggal' => $cuti->tanggal,
    //                     'status_absen' => $request["nama_cuti"]
    //                 ]);
    //             }
    //         } else if($request["nama_cuti"] == "Cuti Sakit") {
    //             $user->update([
    //                 'izin_lainnya' => $user->cuti_sakit - 1
    //             ]);

    //             if ($mapping_shift) {
    //                 $mapping_shift->update([
    //                     'status_absen' => $request["nama_cuti"]
    //                 ]);
    //             } else {
    //                 MappingShift::create([
    //                     'karyawan_id' => $cuti->karyawan_id,
    //                     'tanggal' => $cuti->tanggal,
    //                     'status_absen' => $request["nama_cuti"]
    //                 ]);
    //             }
    //         } else if($request["nama_cuti"] == "Cuti Sakit") {
    //             $user->update([
    //                 'izin_lainnya' => $user->cuti_sakit - 1
    //             ]);

    //             if ($mapping_shift) {
    //                 $mapping_shift->update([
    //                     'status_absen' => $request["nama_cuti"]
    //                 ]);
    //             } else {
    //                 MappingShift::create([
    //                     'karyawan_id' => $cuti->karyawan_id,
    //                     'tanggal' => $cuti->tanggal,
    //                     'status_absen' => $request["nama_cuti"]
    //                 ]);
    //             }
    //         } else if($request["nama_cuti"] == "Cuti Menikah") {
    //             if ($mapping_shift) {
    //                 $user->update([
    //                     'izin_telat' => $user->cuti_menikah - 1
    //                 ]);
    //                 $mapping_shift->update([
    //                     'jam_absen' => $mapping_shift->Shift->jam_masuk,
    //                     'telat' => 0,
    //                     'lat_absen' => $user->Lokasi->lat_kantor,
    //                     'long_absen' => $user->Lokasi->long_kantor,
    //                     'jarak_masuk' => 0,
    //                     'foto_jam_absen' => $cuti->foto_cuti,
    //                     'status_absen' => $request["nama_cuti"],
    //                 ]);
    //             } else {
    //                 $cuti->update(['status_cuti' => 'Pending']);
    //                 Alert::error('Failed', 'Anda Belum Absen Masuk Pada Tanggal Tersebut');
    //                 return redirect('/data-cuti');
    //             }
    //         } else {
    //             if ($mapping_shift) {
    //                 $user->update([
    //                     'izin_pulang_cepat' => $user->izin_pulang_cepat - 1
    //                 ]);

    //                 $mapping_shift->update([
    //                     'jam_pulang' => $mapping_shift->Shift->jam_keluar,
    //                     'lat_pulang' => $user->Lokasi->lat_kantor,
    //                     'long_pulang' => $user->Lokasi->long_kantor,
    //                     'pulang_cepat' => 0,
    //                     'jarak_pulang' => 0,
    //                     'foto_jam_pulang' => $cuti->foto_cuti,
    //                     'status_absen' => $request["nama_cuti"],
    //                 ]);
    //             } else {
    //                 $cuti->update(['status_cuti' => 'Pending']);
    //                 Alert::error('Failed', 'Anda Belum Absen Masuk Pada Tanggal Tersebut');
    //                 return redirect('/data-cuti');
    //             }
    //         }

    //         $user = Karyawan::find($cuti->karyawan_id);
    //         $type = 'Approved';
    //         $notif = 'Cuti Anda Telah Diterima Oleh ' . auth()->user()->name; 
    //         $url = url('/cuti?mulai='.$cuti->tanggal.'&akhir='.$cuti->tanggal); 

    //         $user->messages = [
    //             'karyawan_id'   =>  auth()->user()->id,
    //             'from'   =>  auth()->user()->name,
    //             'message'   =>  $notif,
    //             'action'   =>  '/cuti?mulai='.$cuti->tanggal.'&akhir='.$cuti->tanggal
    //         ];
    //         $user->notify(new \App\Notifications\UserNotification);

    //         NotifApproval::dispatch($type, $user->id, $notif, $url);
    //     } else if ($request["status_cuti"] == "Ditolak") {

    //         $user = Karyawan::find($cuti->karyawan_id);
    //         $type = 'Rejected';
    //         $notif = 'Cuti Anda Telah Ditolak Oleh ' . auth()->user()->name; 
    //         $url = url('/cuti?mulai='.$cuti->tanggal.'&akhir='.$cuti->tanggal); 

    //         $user->messages = [
    //             'karyawan_id'   =>  auth()->user()->id,
    //             'from'   =>  auth()->user()->name,
    //             'message'   =>  $notif,
    //             'action'   =>  '/cuti?mulai='.$cuti->tanggal.'&akhir='.$cuti->tanggal
    //         ];
    //         // $user->notify(new \App\Notifications\UserNotification);

    //         NotifApproval::dispatch($type, $user->id, $notif, $url);
    //     }

    //     $request->session()->flash('success', 'Data Berhasil di Update');
    //     return redirect('/data-cuti');
    // }


    public function dataIzin(Request $request)
    {
        // Ambil status, nama izin, tanggal mulai, dan tanggal akhir dari request
        $status = $request->input('status');
        $namaIzin = $request->input('nama_izin');
        $mulai = $request->input('mulai');
        $akhir = $request->input('akhir');

        // Dapatkan user yang sedang login
        $user = auth()->user();

        // Query dasar untuk mengambil data izin
        $query = Cuti::whereIn('nama_cuti', [
            'izin Telat',
            'izin Masuk',
            'izin Pulang Cepat',
            'izin Meninggalkan Pekerjaan',
        ])->orderBy('tanggal', 'desc');

        // Filter berdasarkan status jika ada
        if ($status) {
            $query->where('status_cuti', $status);
        }

        // Filter berdasarkan nama izin jika ada
        if ($namaIzin) {
            $query->where('nama_cuti', 'like', '%' . $namaIzin . '%');
        }

        // Filter berdasarkan tanggal mulai dan tanggal akhir jika ada
        if ($mulai && $akhir) {
            $query->whereBetween('tanggal', [$mulai, $akhir]);
        }

        // Jika user adalah admin divisi, hanya tampilkan data karyawan yang divisinya sama
        if ($user->is_admin === 'admin_divisi') {
            $query->whereHas('karyawan', function ($query) use ($user) {
                $query->where('jabatan_id', $user->jabatan_id);
            });
        }

        // Dapatkan hasil dengan paginate
        $data = $query->paginate(10);

        // Kembalikan tampilan dengan data yang difilter
        return view('cuti.izin', [
            'data_cuti' => $data,
            'title' => 'Data Izin'
        ]);
    }



    public function exportIzin(Request $request)
    {
        // Ambil status, nama izin, tanggal mulai, dan tanggal akhir dari request
        $status = $request->input('status');
        $namaIzin = $request->input('nama_izin');
        $mulai = $request->input('mulai');
        $akhir = $request->input('akhir');

        // Panggil class export dengan parameter filter
        return Excel::download(new IzinExport($status, $namaIzin, $mulai, $akhir), 'Data_Izin.xlsx');
    }
}
