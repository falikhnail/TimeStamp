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
                        <a href="{{ url('/pegawai') }}" class="btn btn-danger btn-sm ms-2">Back</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        @if($karyawan->foto_karyawan == null)
                            <img class="profile-user-img img-fluid img-circle" src="{{ url('assets/img/foto_default.jpg') }}" alt="User profile picture">
                        @else
                            <img class="profile-user-img img-fluid img-circle" src="{{ url('storage/'.$karyawan->foto_karyawan) }}" alt="User profile picture">
                        @endif
                    </div>

                    <h3 class="profile-username text-center">{{ $karyawan->name }}</h3>

                    <p class="text-muted text-center">{{ $karyawan->Jabatan->nama_jabatan }}</p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                        <b>Email</b> <a class="float-end" style="color: black">{{ $karyawan->email }}</a>
                        </li>
                        <li class="list-group-item">
                        <b>Username</b> <a class="float-end" style="color: black">{{ $karyawan->username }}</a>
                        </li>
                        <li class="list-group-item">
                        <b>Telepon</b> <a class="float-end" style="color: black">{{ $karyawan->telepon }}</a>
                        </li>
                    </ul>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <div class="tab-content">
                        <div class="active tab-pane" id="settings">
                            <form method="post" action="{{ url('/pegawai/proses-edit/'.$karyawan->id) }}" enctype="multipart/form-data">
                                @method('put')
                                @csrf
                                <div class="form-row">
                                    <div class="col mb-4">
                                        <label for="name">Nama Pegawai</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" autofocus value="{{ old('name', $karyawan->name) }}">
                                        @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col mb-4">
                                        <label for="foto_karyawan" class="form-label">Foto Pegawai</label>
                                        <input class="form-control @error('foto_karyawan') is-invalid @enderror" type="file" id="foto_karyawan" name="foto_karyawan">
                                        @error('foto_karyawan')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <input type="hidden" name="foto_karyawan_lama" value="{{ $karyawan->foto_karyawan }}">
                                </div>
                                <div class="form-row">
                                    <div class="col mb-4">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $karyawan->email) }}">
                                        @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col mb-4">
                                        <label for="telepon">Nomor Telfon</label>
                                        <input type="text" class="form-control @error('telepon') is-invalid @enderror" id="telepon" name="telepon" value="{{ old('telepon', $karyawan->telepon) }}">
                                        @error('telepon')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col mb-4">
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username', $karyawan->username) }}">
                                        @error('username')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <input type="hidden" name="password" value="{{ $karyawan->password }}">
                                    <div class="col mb-4">
                                        <label for="lokasi_id">Lokasi Kantor</label>
                                        <select name="lokasi_id" id="lokasi_id" class="form-control @error('lokasi_id') is-invalid @enderror selectpicker" data-live-search="true">
                                            @foreach ($data_lokasi as $dl)
                                                @if(old('lokasi_id', $karyawan->lokasi_id) == $dl->id)
                                                <option value="{{ $dl->id }}" selected>{{ $dl->nama_lokasi }}</option>
                                                @else
                                                <option value="{{ $dl->id }}">{{ $dl->nama_lokasi }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('lokasi_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col mb-4">
                                        <label for="tgl_lahir">Tanggal Lahir</label>
                                        <input type="datetime" class="form-control @error('tgl_lahir') is-invalid @enderror" id="tgl_lahir" name="tgl_lahir" value="{{ old('tgl_lahir', $karyawan->tgl_lahir) }}">
                                        @error('tgl_lahir')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col mb-4">
                                        <?php $gender = array(
                                        [
                                            "gender" => "Laki-Laki"
                                        ],
                                        [
                                            "gender" => "Perempuan"
                                        ]);
                                        ?>
                                        <label for="gender">Gender</label>
                                        <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror selectpicker" data-live-search="true">
                                            @foreach ($gender as $g)
                                                @if(old('gender', $karyawan->gender) == $g["gender"])
                                                <option value="{{ $g["gender"] }}" selected>{{ $g["gender"] }}</option>
                                                @else
                                                <option value="{{ $g["gender"] }}">{{ $g["gender"] }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('gender')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col mb-4">
                                        <label for="tgl_join">Tanggal Masuk Perusahaan</label>
                                        <input type="datetime" class="form-control @error('tgl_join') is-invalid @enderror" id="tgl_join" name="tgl_join" value="{{ old('tgl_join', $karyawan->tgl_join) }}">
                                        @error('tgl_join')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col mb-4">
                                        <?php $sNikah = array(
                                        [
                                            "status" => "Menikah"
                                        ],
                                        [
                                            "status" => "Lajang"
                                        ]);
                                        ?>
                                        <label for="status_nikah">Status Pernikahan</label>
                                        <select name="status_nikah" id="status_nikah" class="form-control @error('status_nikah') is-invalid @enderror selectpicker" data-live-search="true">
                                            @foreach ($sNikah as $s)
                                                @if(old('status_nikah', $karyawan->status_nikah) == $s["status"])
                                                    <option value="{{ $s["status"] }}" selected>{{ $s["status"] }}</option>
                                                @else
                                                    <option value="{{ $s["status"] }}">{{ $s["status"] }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('status_nikah')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col mb-4">
                                        <label for="jabatan_id">Jabatan</label>
                                        <select name="jabatan_id" id="jabatan_id" class="form-control @error('jabatan_id') is-invalid @enderror selectpicker" data-live-search="true">
                                            @foreach ($data_jabatan as $dj)
                                                @if(old('jabatan_id', $karyawan->jabatan_id) == $dj->id)
                                                <option value="{{ $dj->id }}" selected>{{ $dj->nama_jabatan }}</option>
                                                @else
                                                <option value="{{ $dj->id }}">{{ $dj->nama_jabatan }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('jabatan_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col mb-4">
                                        <?php $is_admin = array(
                                        [
                                            "is_admin" => "admin"
                                        ],
                                        [
                                            "is_admin" => "user"
                                        ]);
                                        ?>
                                        <label for="is_admin">Level User</label>
                                        <select name="is_admin" id="is_admin" class="form-control @error('is_admin') is-invalid @enderror selectpicker" data-live-search="true">
                                            @foreach ($is_admin as $a)
                                                @if(old('is_admin', $karyawan->is_admin) == $a["is_admin"])
                                                <option value="{{ $a["is_admin"] }}" selected>{{ $a["is_admin"] }}</option>
                                                @else
                                                <option value="{{ $a["is_admin"] }}">{{ $a["is_admin"] }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('is_admin')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col mb-4">
                                        <label for="rekening">Rekening</label>
                                        <input type="number" class="form-control @error('rekening') is-invalid @enderror" id="rekening" name="rekening" value="{{ old('rekening', $karyawan->rekening) }}">
                                        @error('rekening')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col mb-4">
                                        <label for="alamat">Alamat</label>
                                        <textarea name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $karyawan->alamat) }}</textarea>
                                        @error('alamat')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col mb-4">
                                    <h3 style="color: blue">Cuti & Izin</h3>
                                </div>
                                <div class="form-row">
                                    <div class="col mb-4">
                                        <label for="izin_cuti">Cuti</label>
                                        <input type="number" class="form-control @error('izin_cuti') is-invalid @enderror" id="izin_cuti" name="izin_cuti" value="{{ old('izin_cuti', $karyawan->izin_cuti) }}">
                                        @error('izin_cuti')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col mb-4">
                                        <label for="cuti_sakit">Cuti Sakit</label>
                                        <input type="number" class="form-control @error('cuti_sakit') is-invalid @enderror" id="cuti_sakit" name="cuti_sakit" value="{{ old('cuti_sakit', $karyawan->cuti_sakit) }}">
                                        @error('cuti_sakit')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col mb-4">
                                        <label for="cuti_menikah">Cuti Menikah</label>
                                        <input type="number" class="form-control @error('cuti_menikah') is-invalid @enderror" id="cuti_menikah" name="cuti_menikah" value="{{ old('cuti_menikah', $karyawan->cuti_menikah) }}">
                                        @error('cuti_menikah')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col mb-4">
                                        <label for="cuti_melahirkan">Cuti Melahirkan</label>
                                        <input type="number" class="form-control @error('cuti_melahirkan') is-invalid @enderror" id="cuti_melahirkan" name="cuti_melahirkan" value="{{ old('cuti_melahirkan', $karyawan->cuti_melahirkan) }}">
                                        @error('cuti_melahirkan')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col mb-4">
                                        <label for="cuti_keguguran">Cuti Keguguran</label>
                                        <input type="number" class="form-control @error('cuti_keguguran') is-invalid @enderror" id="cuti_keguguran" name="cuti_keguguran" value="{{ old('cuti_keguguran', $karyawan->cuti_keguguran) }}">
                                        @error('cuti_keguguran')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col mb-4">
                                        <label for="cuti_istri_melahirkan">Cuti Istri Melahirkan/Keguguran</label>
                                        <input type="number" class="form-control @error('cuti_istri_melahirkan') is-invalid @enderror" id="cuti_istri_melahirkan" name="cuti_istri_melahirkan" value="{{ old('cuti_istri_melahirkan', $karyawan->cuti_istri_melahirkan) }}">
                                        @error('cuti_istri_melahirkan')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col mb-4">
                                        <label for="cuti_menikahkan_anak">Cuti Menikahkan Anak</label>
                                        <input type="number" class="form-control @error('cuti_menikahkan_anak') is-invalid @enderror" id="cuti_menikahkan_anak" name="cuti_menikahkan_anak" value="{{ old('cuti_menikahkan_anak', $karyawan->cuti_menikahkan_anak) }}">
                                        @error('cuti_menikahkan_anak')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col mb-4">
                                        <label for="cuti_khitanan_anak">Cuti Khitanan Anak</label>
                                        <input type="number" class="form-control @error('cuti_khitanan_anak') is-invalid @enderror" id="cuti_khitanan_anak" name="cuti_khitanan_anak" value="{{ old('cuti_khitanan_anak', $karyawan->cuti_khitanan_anak) }}">
                                        @error('cuti_khitanan_anak')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col mb-4">
                                        <label for="cuti_membabtiskan_anak">Cuti Membabtiskan Anak</label>
                                        <input type="number" class="form-control @error('cuti_membabtiskan_anak') is-invalid @enderror" id="cuti_membabtiskan_anak" name="cuti_membabtiskan_anak" value="{{ old('cuti_membabtiskan_anak', $karyawan->cuti_membabtiskan_anak) }}">
                                        @error('cuti_membabtiskan_anak')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col mb-4">
                                        <label for="cuti_keluarga_atap">Cuti Keluarga Atap</label>
                                        <input type="number" class="form-control @error('cuti_keluarga_atap') is-invalid @enderror" id="cuti_keluarga_atap" name="cuti_keluarga_atap" value="{{ old('cuti_keluarga_atap', $karyawan->cuti_keluarga_atap) }}">
                                        @error('cuti_keluarga_atap')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col mb-4">
                                        <label for="cuti_keluarga">Cuti Keluarga</label>
                                        <input type="number" class="form-control @error('cuti_keluarga') is-invalid @enderror" id="cuti_keluarga" name="cuti_keluarga" value="{{ old('cuti_keluarga', $karyawan->cuti_keluarga) }}">
                                        @error('cuti_keluarga')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col mb-4">
                                        <label for="cuti_ibadah_besar">Cuti Ibadah Besar</label>
                                        <input type="number" class="form-control @error('cuti_ibadah_besar') is-invalid @enderror" id="cuti_ibadah_besar" name="cuti_ibadah_besar" value="{{ old('cuti_ibadah_besar', $karyawan->cuti_ibadah_besar) }}">
                                        @error('cuti_ibadah_besar')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col mb-4">
                                    <h3 style="color: blue">Penjumlahan Gaji</h3>
                                </div>
                                <div class="form-row">
                                    <div class="col mb-4">
                                        <label for="gaji_pokok">Gaji Pokok</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control money @error('gaji_pokok') is-invalid @enderror" name="gaji_pokok" value="{{ old('gaji_pokok', $karyawan->gaji_pokok) }}">
                                            <div class="input-group-text">
                                                <span>/ Bulan</span>
                                            </div>
                                            @error('gaji_pokok')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col mb-4">
                                        <label for="makan_transport">Makan Dan Transport</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control money @error('makan_transport') is-invalid @enderror" name="makan_transport" value="{{ old('makan_transport', $karyawan->makan_transport) }}">
                                            <div class="input-group-text">
                                                <span>/ Bulan</span>
                                            </div>
                                            @error('makan_transport')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col mb-4">
                                        <label for="lembur">Lembur</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control money @error('lembur') is-invalid @enderror" name="lembur" value="{{ old('lembur', $karyawan->lembur) }}">
                                            <div class="input-group-text">
                                                <span>/ Jam</span>
                                            </div>
                                            @error('lembur')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col mb-4">
                                        <label for="kehadiran">100% Kehadiran</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control money @error('kehadiran') is-invalid @enderror" name="kehadiran" value="{{ old('kehadiran', $karyawan->kehadiran) }}">
                                            <div class="input-group-text">
                                                <span>/ Bulan</span>
                                            </div>
                                            @error('kehadiran')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col mb-4">
                                        <label for="thr">THR</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control money @error('thr') is-invalid @enderror" name="thr" value="{{ old('thr', $karyawan->thr) }}">
                                            <div class="input-group-text">
                                                <span>/ Bulan</span>
                                            </div>
                                            @error('thr')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col mb-4">
                                        <label for="bonus">Bonus</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control money @error('bonus') is-invalid @enderror" name="bonus" value="{{ old('bonus', $karyawan->bonus) }}">
                                            <div class="input-group-text">
                                                <span>/ Bulan</span>
                                            </div>
                                            @error('bonus')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col mb-4">
                                    <h3 style="color: blue">Pengurangan Gaji</h3>
                                </div>
                                <div class="form-row">
                                    <div class="col mb-4">
                                        <label for="izin">Izin</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control money @error('izin') is-invalid @enderror" name="izin" value="{{ old('izin', $karyawan->izin) }}">
                                            <div class="input-group-text">
                                                <span>/ hari</span>
                                            </div>
                                            @error('izin')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col mb-4">
                                        <label for="terlambat">Terlambat</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control money @error('terlambat') is-invalid @enderror" name="terlambat" value="{{ old('terlambat', $karyawan->terlambat) }}">
                                            <div class="input-group-text">
                                                <span>/ hari</span>
                                            </div>
                                            @error('terlambat')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col mb-4">
                                        <label for="mangkir">Mangkir</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control money @error('mangkir') is-invalid @enderror" name="mangkir" value="{{ old('mangkir', $karyawan->mangkir) }}">
                                            <div class="input-group-text">
                                                <span>/ hari</span>
                                            </div>
                                            @error('mangkir')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col mb-4">
                                        <label for="saldo_kasbon">Saldo Kasbon</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control money @error('saldo_kasbon') is-invalid @enderror" name="saldo_kasbon" value="{{ old('saldo_kasbon', $karyawan->saldo_kasbon) }}">
                                            <div class="input-group-text">
                                                <span>/ Tahun</span>
                                            </div>
                                            @error('saldo_kasbon')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('script')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
        <script>
            $(document).ready(function(){
                $('.money').mask('000,000,000,000,000', {
                    reverse: true
                });
            });
        </script>
    @endpush
@endsection
