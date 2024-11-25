@extends('templates.dashboard')
@section('isi')
   <div class="row">
        <div class="col-md-12 m project-list">
            <div class="card">
                <div class="row">
                    <div class="col-md-6 p-0 d-flex mt-2">
                        <h4>{{ $title }}</h4>
                    </div>
                    <div class="col-md-6 p-0">
                        <a href="{{ url('/rekap-data') }}" class="btn btn-danger btn-sm ms-2">Back</a>
                        <a href="{{ url('/rekap-data/export') }}{{ $_GET?'?'.$_SERVER['QUERY_STRING']: '' }}" class="btn btn-success">Export</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <form action="{{ url('/rekap-data/get-data') }}">
                            <div class="row">
                                <div class="col-3">
                                    <input type="datetime" class="form-control" name="mulai" placeholder="Tanggal Mulai" id="mulai" value="{{ request('mulai') }}">
                                </div>
                                <div class="col-3">
                                    <input type="datetime" class="form-control" name="akhir" placeholder="Tanggal Akhir" id="akhir" value="{{ request('akhir') }}">
                                </div>
                                <div class="col-3">
                                    <button type="submit" id="search"class="border-0 mt-3" style="background-color: transparent;"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table  id="mytable">
                            <thead>
                                <tr>
                                    <th>Nama Pegawai</th>
                                    <th>Total Cuti</th>
                                    <th>Total Sakit</th>
                                    <th>Total Izin Masuk</th>
                                    <th>Total Izin Telat</th>
                                    <th>Total Izin Pulang Cepat</th>
                                    <th>Total Hadir</th>
                                    <th>Total Alfa</th>
                                    <th>Total Libur</th>
                                    <th>Total Lepas</th>
                                    <th>Total Telat</th>
                                    <th>Total Izin Meninggalkan Pekerjaan</th>
                                    <th>Total Pulang Cepat</th>
                                    <th>Total Lembur</th>
                                    <th>Total Jam Kerja</th>
                                    <th>Persentase Kehadiran</th>
                                    @if (auth()->user()->is_admin == "admin_divisi")
                                        
                                    @else
                                    
                                    <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_user as $du)
                                    <tr>
                                        @php
                                            $cuti = $du->MappingShift
                                            ->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir])
                                            ->where('status_absen','Cuti Tahunan')
                                            ->count();  
                                            $cuti_sakit = $du->MappingShift->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir])->where('status_absen','Izin Sakit')->count();

                                            $izin_masuk = $du->MappingShift->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir])->where('status_absen', 'Izin Masuk')->count();

                                            $izin_telat = $du->MappingShift->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir])->where('status_absen', 'Izin Telat')->count();

                                            $izin_pulang_cepat = $du->MappingShift->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir])->where('status_absen', 'Izin Pulang Cepat')->count();

                                            $masuk = $du->MappingShift->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir])->where('status_absen', 'Masuk',)->count();

                                            $total_hadir = $masuk + $izin_telat + $izin_pulang_cepat;

                                            $libur = $du->MappingShift->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir])->where('status_absen', 'Libur')->count();

                                            $lepas = $du->MappingShift->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir])->where('status_absen', 'Lepas')->count();

                                            $izin_meninggalkan_pekerjaan = $du->Cuti->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir])
                                            ->where('status_cuti', 'Diterima')
                                            ->sum(function ($izin) {
                                                $jam_awal = \Carbon\Carbon::parse($izin->jam_awal);
                                                $jam_akhir = \Carbon\Carbon::parse($izin->jam_akhir);
                                                
                                                // Hitung selisih waktu dalam menit
                                                $total_menit = $jam_akhir->diffInMinutes($jam_awal);
                                                
                                                return $total_menit; // Mengembalikan total menit
                                            });

                                            // Konversi total menit ke dalam format jam dan menit
                                            $total_jam = floor($izin_meninggalkan_pekerjaan / 60); // Jam dari total menit
                                            $total_menit = $izin_meninggalkan_pekerjaan % 60;      

                                            $mulai = new \DateTime($tanggal_mulai);
                                            $akhir = new \DateTime($tanggal_akhir);
                                            $interval = $mulai->diff($akhir);
                                            $total_alfa = $interval->days + 1 - $masuk - $cuti - $izin_masuk - $libur - $lepas - $cuti_sakit;

                                        @endphp
                                        <td>
                                            {{ $du->name }}
                                        </td>

                                        <td>
                                            {{ $cuti }} x
                                        </td>
                                        <td>
                                            {{ $cuti_sakit }} x
                                        </td>
                                        <td>
                                            {{ $izin_masuk }} x
                                        </td>
                                        <td>
                                            @php
                                                $total_telat = $du->MappingShift->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir])->sum('telat');
                                                $jam   = floor($total_telat / (60 * 60));
                                                $menit = $total_telat - ( $jam * (60 * 60) );
                                                $menit2 = floor($menit / 60);
                                                $jumlah_telat = $du->MappingShift->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir])->where('telat', '>', 0)->count();
                                            @endphp
                                            <span >{{ $jumlah_telat }} x</span>
                                        </td>
                                        <td>
                                            {{ $izin_pulang_cepat }} x
                                        </td>
                                        
                                        <td>
                                            {{ $total_hadir }} x
                                        </td>
                                        <td>
                                            {{ $total_alfa }}
                                        </td>
                                        <td>
                                            {{ $libur }} x
                                        </td>
                                        <td>
                                            {{ $lepas }} x
                                        </td>
                                        
                                        <td>
                                            @php
                                                $total_telat = $du->MappingShift->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir])->sum('telat');
                                                $jam   = floor($total_telat / (60 * 60));
                                                $menit = $total_telat - ( $jam * (60 * 60) );
                                                $menit2 = floor($menit / 60);
                                                $jumlah_telat = $du->MappingShift->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir])->where('telat', '>', 0)->count();
                                            @endphp

                                            @if($jam <= 0 && $menit2 <= 0)
                                                <span class="badge badge-success">Tidak Pernah Telat</span>
                                            @else
                                                <span class="badge badge-danger">{{ $jam." Jam ".$menit2." Menit" }}</span>
                                                <br>
                                                {{-- <span class="badge badge-danger">{{ $jumlah_telat }} x</span> --}}
                                            @endif
                                        </td>
                                        <td>
                                            {{-- @php
                                                $jumlah_izin = $du->Cuti->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir])
                                                ->where('nama_cuti', 'Izin Meninggalkan Pekerjaan')
                                                ->count();
                                            @endphp --}}
                                            @if ($izin_meninggalkan_pekerjaan > 0)
                                            <span class="badge badge-danger">{{ $total_jam }} Jam {{ $total_menit }} Menit</span>
                                            {{-- <br>
                                            <span class="badge badge-danger">{{ $jumlah_izin }} x</span> --}}
                                            @else
                                            <span class="badge badge-success">Tidak Pernah Izin</span>
                                            <br>
                                            
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $total_pulang_cepat = $du->MappingShift->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir])->sum('pulang_cepat');
                                                $jam_cepat   = floor($total_pulang_cepat / (60 * 60));
                                                $menit_cepat = $total_pulang_cepat - ( $jam_cepat * (60 * 60) );
                                                $menit_cepat2 = floor($menit_cepat / 60);
                                                $jumlah_pulang_cepat = $du->MappingShift->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir])->where('pulang_cepat', '>', 0)->count();
                                            @endphp

                                            @if($jam_cepat <= 0 && $menit_cepat2 <= 0)
                                                <span class="badge badge-success">Tidak Pernah Pulang Cepat</span>
                                            @else
                                                <span class="badge badge-danger">{{ $jam_cepat." Jam ".$menit_cepat2." Menit" }}</span>
                                                {{-- <br>
                                                <span class="badge badge-danger">{{ $jumlah_pulang_cepat }} x</span> --}}
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $total_lembur = $du->Lembur->where('status', 'Approved')->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir])->sum('total_lembur');
                                                $jam_lembur   = floor($total_lembur / (60 * 60));
                                                $menit_lembur = $total_lembur - ( $jam_lembur * (60 * 60) );
                                                $menit_lembur2 = floor($menit_lembur / 60);
                                                $detik = $total_lembur % 60;
                                            @endphp

                                            <span class="badge badge-success">{{ $jam_lembur." Jam ".$menit_lembur2." Menit" }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $total_jam_kerja = $du->Lembur->where('status', 'Approved')->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir])->sum('total_lembur');
                                                $jam_lembur   = floor($total_lembur / (60 * 60));
                                                $menit_lembur = $total_lembur - ( $jam_lembur * (60 * 60) );
                                                $menit_lembur2 = floor($menit_lembur / 60);
                                                $detik = $total_lembur % 60;
                                            @endphp

                                            <span class="badge badge-success">{{ $jam_lembur." Jam ".$menit_lembur2." Menit" }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $timestamp_mulai = strtotime($tanggal_mulai);
                                                $timestamp_akhir = strtotime($tanggal_akhir);
                                                $selisih_timestamp = $timestamp_akhir - $timestamp_mulai;
                                                $jumlah_hari = (floor($selisih_timestamp / (60 * 60 * 24)))+1;
                                                $persentase_kehadiran = (($total_hadir + $libur) / $jumlah_hari) * 100;
                                            @endphp
                                            {{ $persentase_kehadiran }} %
                                        </td>

                                        @if (auth()->user()->is_admin == 'admin_divisi')
                                            
                                        @else
                                        
                                        <td>
                                            @php
                                                $pecah_tanggal = explode("-", $tanggal_mulai);
                                                $tahun_filter = $pecah_tanggal[0];
                                                $bulan_filter = $pecah_tanggal[1];
                                                $payroll = \App\Models\Payroll::where('karyawan_id', $du->id)->where('bulan', $bulan_filter)->where('tahun', $tahun_filter)->first();
                                            @endphp
                                            <ul class="action">
                                                @if ($payroll)
                                                    <li class="me-2">
                                                        <span class="badge badge-success">Terinput</span>
                                                    </li>
                                                @else
                                                    <li class="me-2">
                                                        <a href="{{ url('/rekap-data/payroll/'.$du->id) }}{{ $_GET?'?'.$_SERVER['QUERY_STRING']: '' }}" title="Input Gaji"><i style="color: orangered" class="fa fa-money"></i></a>
                                                    </li>
                                                @endif
                                                <li>
                                                    <a href="{{ url('/data-absen/export?user_id='.$du->id) }}{{ $_GET?'&'.$_SERVER['QUERY_STRING']: '' }}" title="Download Absen"><i style="color: blue" class="fa fa-print"></i></a>
                                                </li>
                                            </ul>
                                        </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end mr-4">
                        {{ $data_user->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
