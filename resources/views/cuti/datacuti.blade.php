@extends('templates.dashboard')
@section('isi')

{{-- @php
    dd($data_cuti)
@endphp --}}
    <div class="row">
        <div class="col-md-12 project-list">
            <div class="card">
                <div class="row">
                    <div class="col-md-6 mt-2 p-0 d-flex">
                        <h4>{{ $title }}</h4>
                    </div>
                    <div class="col-md-6 p-0">    
                        <a class="btn btn-primary btn-sm ms-2" href="{{ url('/data-cuti/tambah') }}">+ Tambah</a>
                        <a href="{{ url('/rekap-cuti/export') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}" class="btn btn-success">Export</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <form action="{{ url('/data-cuti') }}">
                            <div class="row">
                            <div class="col-2">
                                <input type="datetime" class="form-control" name="mulai" placeholder="Tanggal Mulai" id="mulai" value="{{ request('mulai') }}">
                            </div>
                            <div class="col-2">
                                <input type="datetime" class="form-control" name="akhir" placeholder="Tanggal Akhir" id="akhir" value="{{ request('akhir') }}">
                            </div>
                            <div class="col-2">
                                <select class="form-control" name="status">
                                    <option value="">Pilih Status</option>
                                    <option value="Diterima" {{ request('status') == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </div>
                            <div class="col-2">
                                <select class="form-control" name="nama_cuti">
                                    <option value="">Pilih Nama Cuti</option>
                                    <option value="Cuti Tahunan" {{ request('nama_cuti') == 'Cuti Tahunan' ? 'selected' : '' }}>Cuti Tahunan</option>
                                    <option value="Cuti Menikah" {{ request('nama_cuti') == 'Cuti Menikah' ? 'selected' : '' }}>Cuti Menikah</option>
                                    <option value="Cuti Melahirkan" {{ request('nama_cuti') == 'Cuti Melahirkan' ? 'selected' : '' }}>Cuti Melahirkan</option>
                                    <option value="Cuti Keguguran" {{ request('nama_cuti') == 'Cuti Keguguran' ? 'selected' : '' }}>Cuti Keguguran</option>
                                    <option value="Cuti Istri Melahirkan" {{ request('nama_cuti') == 'Cuti Istri Melahirkan' ? 'selected' : '' }}>Cuti Istri Melahirkan / Keguguran</option>
                                    <option value="Cuti Menikahkan Anak" {{ request('nama_cuti') == 'Cuti Menikahkan Anak' ? 'selected' : '' }}>Cuti Menikahkan Anak</option>
                                    <option value="Cuti Mengkhitankan Anak" {{ request('nama_cuti') == 'Cuti Mengkhitankan Anak' ? 'selected' : '' }}>Cuti Mengkhitankan Anak</option>
                                    <option value="Cuti Membaptiskan Anak" {{ request('nama_cuti') == 'Cuti Membaptiskan Anak' ? 'selected' : '' }}>Cuti Membaptiskan Anak</option>
                                    <option value="Cuti Keluarga Meninggal" {{ request('nama_cuti') == 'Cuti Keluarga' ? 'selected' : '' }}>Cuti Keluarga Meninggal</option>
                                    <option value="Cuti Keluarga Meninggal Atap" {{ request('nama_cuti') == 'Cuti Anggota Keluarga Dalam 1 Rumah Meninggal' ? 'selected' : '' }}>Cuti Anggota Keluarga Dalam 1 Rumah Meninggal</option>
                                    <option value="Cuti Ibadah Besar" {{ request('nama_cuti') == 'Cuti Ibadah Besar' ? 'selected' : '' }}>Cuti Ibadah Besar</option>
                                </select>
                            </div>
                            <div class="col-3">
                                <button type="submit" id="search"class="border-0 mt-3" style="background-color: transparent;"><i class="fas fa-search"></i></button>
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
                                    <th>Nama Cuti</th>
                                    <th>Tanggal</th>
                                    <th>Alasan Cuti</th>
                                    <th>Foto Cuti</th>
                                    <th>Status Cuti</th>
                                    <th>Catatan</th>
                                    @if (auth()->user()->is_admin == "admin_divisi")
                                    
                                    @else  
                                    <th>Actions</th>
                                    @endif
                                    <th>Tanggal Approve</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_cuti as $dc)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $dc->Karyawan->name }}</td>
                                    <td>{{ $dc->nama_cuti }}</td>
                                    <td>{{ $dc->tanggal}} sd {{ $dc->tanggal_akhir }}</td>
                                    <td>{{ $dc->alasan_cuti}}</td>
                                    <td>
                                        <img src="{{ url('storage/'.$dc->foto_cuti) }}" style="width: 70px" alt="">
                                    </td>
                                    <td>
                                        @if($dc->status_cuti == "Diterima")
                                            <span class="badge badge-success">{{ $dc->status_cuti }}</span>
                                        @elseif($dc->status_cuti == "Ditolak")
                                            <span class="badge badge-danger">{{ $dc->status_cuti }}</span>
                                        @else
                                            <span class="badge badge-warning">{{ $dc->status_cuti }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $dc->catatan}}</td>
                                    @if (auth()->user()->is_admin == "admin_divisi")
                                        
                                    @else
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
            
                                            {{-- @if($dc->status_cuti == "Diterima")
                                                <li>
                                                    <span class="badge badge-success">Sudah Approve</span>
                                                </li>
                                            @else
                                                
                                            @endif --}}
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
