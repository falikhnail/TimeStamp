@extends('templates.dashboard')
@section('isi')

<div class="row">
    <div class="col-md-12 project-list">
        <div class="card">
            <div class="row">
                <div class="col-md-6 mt-2 p-0 d-flex">
                    <h4>{{ $title }}</h4>
                </div>
                <div class="col-md-6 p-0">    
                    <a class="btn btn-primary btn-sm ms-2" href="{{ url('/data-cuti/tambah') }}">+ Tambah</a>
                    <a class="btn btn-success btn-sm" 
                    href="{{ url('/export-izin') }}?status={{ request('status') }}&nama_izin={{ request('nama_izin') }}&mulai={{ request('mulai') }}&akhir={{ request('akhir') }}">
                    Export Izin
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <form action="{{ url('/data-izin') }}">
                    <div class="row">
                        <div class="col-2">
                            <input type="datetime" class="form-control" name="mulai" placeholder="Tanggal Mulai" id="mulai" value="{{ request('mulai') }}">
                        </div>
                        <div class="col-2">
                            <input type="datetime" class="form-control" name="akhir" placeholder="Tanggal Akhir" id="akhir" value="{{ request('akhir') }}">
                        </div>
                        <div class="col-2">
                            <select name="status" class="form-control">
                                <option value="">Pilih Status</option>
                                <option value="Diterima" {{ request('status') == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                                <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                        </div>
                        <div class="col-2">
                            <select name="nama_izin" class="form-control">
                                <option value="">Pilih Nama Izin</option>
                                <option value="Izin Telat" {{ request('nama_izin') == 'Izin Telat' ? 'selected' : '' }}>Izin Telat</option>
                                <option value="Izin Pulang Cepat" {{ request('nama_izin') == 'Izin Pulang Cepat' ? 'selected' : '' }}>Izin Pulang Cepat</option>
                                <option value="Izin Masuk" {{ request('nama_izin') == 'Izin Masuk' ? 'selected' : '' }}>Izin Masuk</option>
                                <option value="Izin Meninggalkan Pekerjaan" {{ request('nama_izin') == 'Izin Meninggalkan Pekerjaan' ? 'selected' : '' }}>Izin Meninggalkan Pekerjaan</option>
                            </select>
                        </div>
                        <div class="col-2">
                            <button type="submit" id="search" class="border-0 mt-3" style="background-color: transparent;">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="mytable">
                       
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Pegawai</th>
                                <th>Nama Izin</th>
                                <th>Tanggal</th>
                                <th>Alasan Izin</th>
                                <th>Foto Izin</th>
                                <th>Jam Mulai</th>
                                <th>Jam Akhir</th>
                                {{-- <th>Total jam</th> --}}
                                <th>Status Izin</th>
                                <th>Catatan</th>
                                @if (auth()->user()->is_admin != "admin_divisi")
                                    <th>Actions</th>
                                @endif
                                <th>Tanggal Approve</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data_cuti as $dc)
                            {{-- @php
                                $jam_awal = $dc->jam_awal; // Misal: "15:00"
                                $jam_akhir = $dc->jam_akhir; // Misal: "17:00"

                                // Konversi string waktu menjadi objek DateTime
                                $start = new DateTime($jam_awal);
                                $end = new DateTime($jam_akhir);

                                // Hitung selisih waktu
                                $interval = $start->diff($end);

                                // Dapatkan durasi dalam jam
                                $total_jam = $interval->h + ($interval->i / 60); // Menghitung total jam (jam + menit dalam jam)
                            @endphp --}}
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $dc->Karyawan->name }}</td>
                                <td>{{ $dc->nama_cuti }}</td>
                                <td>{{ $dc->tanggal }}</td>
                                <td>{{ $dc->alasan_cuti }}</td>
                                <td>
                                    <img src="{{ url('storage/'.$dc->foto_cuti) }}" style="width: 70px" alt="">
                                </td>
                                <td>{{ $dc->jam_awal ? $dc->jam_awal : 'N/A' }}</td>
                                <td>{{ $dc->jam_akhir ? $dc->jam_akhir : 'N/A' }}</td>
                                {{-- <td>{{ $total_jam }} jam</td> --}}
                                <td>
                                    @if($dc->status_cuti == "Diterima")
                                        <span class="badge badge-success">{{ $dc->status_cuti }}</span>
                                    @elseif($dc->status_cuti == "Ditolak")
                                        <span class="badge badge-danger">{{ $dc->status_cuti }}</span>
                                    @else
                                        <span class="badge badge-warning">{{ $dc->status_cuti }}</span>
                                    @endif
                                </td>
                                <td>{{ $dc->catatan }}</td>
                                @if (auth()->user()->is_admin != "admin_divisi")
                                <td>
                                    <ul class="action">
                                        @if($dc->status_cuti == "Diterima")
                                            <li class="me-2">
                                                <span class="badge badge-success">Sudah Approve</span>
                                            </li>
                                        @else
                                            <li>
                                                <a href="{{ url('/data-cuti/edit/'.$dc->id) }}"><i style="color: blue" class="fas fa-edit"></i></a>
                                            </li>
                                            <li class="delete">
                                                <form action="{{ url('/data-cuti/delete/'.$dc->id) }}" method="post" class="d-inline">
                                                    @method('delete')
                                                    @csrf
                                                    <button class="border-0" style="background-color: transparent" onClick="return confirm('Are You Sure')"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </li>
                                        @endif
                                    </ul>
                                </td>
                                <td>
                                    @if ($dc->status_cuti == 'Diterima')
                                        {{ $dc->tanggal_approve }}
                                    @else
                                        <span class="badge badge-warning">{{ $dc->status_cuti }}</span>
                                    @endif
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end mr-4">
                    {{ $data_cuti->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    $(document).ready(function() {
        $('#mulai').change(function(){
            var mulai = $(this).val();
            $('#akhir').val(mulai);
        });
    });
</script>
@endpush

@endsection
