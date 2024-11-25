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
                        <a href="{{ url('/pegawai/tambah-pegawai') }}" class="btn btn-primary btn-sm ms-2">+ Tambah</a>
                        <button class="btn btn-success btn-sm" type="button" data-bs-toggle="modal" data-original-title="test" data-bs-target="#exampleModal"><i class="fa fa-table mr-2"></i> Import</button>
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Import Users</h5>
                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ url('/pegawai/import') }}" method="POST" enctype="multipart/form-data">
                                        <div class="modal-body">
                                            @csrf
                                            <div class="form-group">
                                                <label for="file_excel">File Excel</label>
                                                <input type="file" name="file_excel" id="file_excel" class="form-control @error('file_excel') is-invalid @enderror">
                                                @error('file_excel')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Close</button>
                                            <button class="btn btn-secondary" type="submit">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header pb-0">
                    <form action="{{ url('/pegawai') }}" method="GET">
                        <div class="row mb-2">
                            <div class="col-2">
                                <input type="text" placeholder="Search...." class="form-control" value="{{ request('search') }}" name="search">
                            </div>
                            <div class="col-4">
                                @if (auth()->user()->is_admin == 'admin')
                                    <select name="divisi" class="form-control">
                                        <option value="">Select Divisi</option>
                                        <option value="Teknologi Informasi" {{ request('divisi') == 'Teknologi Informasi' ? 'selected' : '' }}>Teknologi Informasi</option>
                                        <option value="Keuangan dan Akuntansi" {{ request('divisi') == 'Keuangan dan Akuntansi' ? 'selected' : '' }}>Keuangan dan Akuntansi</option>
                                        <option value="Administrasi & Umum" {{ request('divisi') == 'Administrasi & Umum' ? 'selected' : '' }}>Administrasi & Umum</option>
                                        <option value="Humas & Pemasaran" {{ request('divisi') == 'Humas & Pemasaran' ? 'selected' : '' }}>Humas & Pemasaran</option>
                                        <option value="Sekretariat" {{ request('divisi') == 'Sekretariat' ? 'selected' : '' }}>Sekretariat</option>
                                        <option value="Direktur" {{ request('divisi') == 'Direktur' ? 'selected' : '' }}>Direktur</option>
                                    </select>
                                @else
                                    <select name="divisi" class="form-control" disabled>
                                        <option value="">Select Divisi</option>
                                        <option value="Teknologi Informasi" {{ request('divisi') == 'Teknologi Informasi' ? 'selected' : '' }}>Teknologi Informasi</option>
                                        <option value="Keuangan dan Akuntansi" {{ request('divisi') == 'Keuangan dan Akuntansi' ? 'selected' : '' }}>Keuangan dan Akuntansi</option>
                                        <option value="Administrasi & Umum" {{ request('divisi') == 'Administrasi & Umum' ? 'selected' : '' }}>Administrasi & Umum</option>
                                        <option value="Humas & Pemasaran" {{ request('divisi') == 'Humas & Pemasaran' ? 'selected' : '' }}>Humas & Pemasaran</option>
                                        <option value="Sekretariat" {{ request('divisi') == 'Sekretariat' ? 'selected' : '' }}>Sekretariat</option>
                                        <option value="Direktur" {{ request('divisi') == 'Direktur' ? 'selected' : '' }}>Direktur</option>
                                    </select>
                                @endif
                            </div>
                            <div class="col">
                                <button type="submit" id="search" class="border-0 mt-3" style="background-color: transparent;"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display" id="mytable">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>Divisi</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_user as $du)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $du->name }}</td>
                                        <td>{{ $du->username }}</td>
                                        <td>{{ $du->Jabatan->nama_jabatan ?? '-' }}</td>
                                        <td>
                                            <ul class="action">
                                                @if (auth()->user()->is_admin == 'admin')
                                                    <li class="edit me-2"><a href="{{ url('/pegawai/detail/'.$du->id) }}" title="Edit Pegawai"><i class="icon-pencil-alt"></i></a></li>
                                                    <li class="me-2"><a href="{{ url('/pegawai/edit-password/'.$du->id) }}" title="Ganti Password"><i class="fa fa-solid fa-key" style="color: rgb(11, 18, 222)"></i></a></li>
                                                    <li class="me-2"> <a href="{{ url('/pegawai/shift/'.$du->id) }}" title="Input Shift Pegawai"><i style="color:coral" class="fa fa-solid fa-clock"></i></a></li>
                                                    <li class="me-2"> <a href="{{ url('/pegawai/dinas-luar/'.$du->id) }}" title="Input Dinas Luar Pegawai"><i style="color:rgb(43, 198, 203)" class="fa fa-solid fa-route"></i></a></li>
                                                    @if ($du->foto_face_recognition == null || $du->foto_face_recognition == "")
                                                        <li><a href="{{ url('/pegawai/face/'.$du->id) }}" title="Face Recognition"><i style="color: black" class="fa fa-solid fa-camera"></i></a></li>
                                                    @endif
                                                    <li class="delete">
                                                        <form action="{{ url('/pegawai/delete/'.$du->id) }}" method="post">
                                                            @method('delete')
                                                            @csrf
                                                            <button title="Delete Pegawai" class="border-0" style="background-color: transparent;" onClick="return confirm('Are You Sure')"><i class="icon-trash"></i></button>
                                                        </form>
                                                    </li>
                                                @elseif (auth()->user()->is_admin == 'admin_divisi')
                                                    <li class="me-2"><a href="{{ url('/pegawai/edit-password/'.$du->id) }}" title="Ganti Password"><i class="fa fa-solid fa-key" style="color: rgb(11, 18, 222)"></i></a></li>
                                                    <li class="me-2"> <a href="{{ url('/pegawai/shift/'.$du->id) }}" title="Input Shift Pegawai"><i style="color:coral" class="fa fa-solid fa-clock"></i></a></li>
                                                    <li class="me-2"> <a href="{{ url('/pegawai/dinas-luar/'.$du->id) }}" title="Input Dinas Luar Pegawai"><i style="color:rgb(43, 198, 203)" class="fa fa-solid fa-route"></i></a></li>
                                                @endif
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end me-4 mt-4">
                        {{ $data_user->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
