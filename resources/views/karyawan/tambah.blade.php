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
        <div class="col-sm-12">
            <div class="card">
                <form class="p-4" method="post" action="{{ url('/pegawai/tambah-pegawai-proses') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="col mb-4">
                            <label for="name">Nama Pegawai</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" autofocus value="{{ old('name') }}">
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
                    </div>
                    <div class="form-row">
                        <div class="col mb-4">
                            <label for="email">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                            @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col mb-4">
                            <label for="telepon">Nomor Telfon</label>
                            <input type="number" class="form-control @error('telepon') is-invalid @enderror" id="telepon" name="telepon" value="{{ old('telepon') }}">
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
                            <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}">
                            @error('username')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col mb-4">
                            <label for="password">Password</label>
                            <input type="password" au class="form-control @error('password') is-invalid @enderror" id="password" name="password" value="{{ old('password') }}">
                            @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col mb-4">
                            <label for="lokasi_id">Lokasi Kantor</label>
                            <select name="lokasi_id" id="lokasi_id" class="form-control @error('lokasi_id') is-invalid @enderror selectpicker" data-live-search="true">
                                <option value="">Pilih Lokasi Kantor</option>
                                @foreach ($data_lokasi as $dl)
                                    @if(old('lokasi_id') == $dl->id)
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
                        <div class="col mb-4">
                            <label for="tgl_lahir">Tanggal Lahir</label>
                            <input type="datetime" class="form-control @error('tgl_lahir') is-invalid @enderror" id="tgl_lahir" name="tgl_lahir" value="{{ old('tgl_lahir') }}">
                            @error('tgl_lahir')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
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
                                <option value="">Pilih Gender</option>
                                @foreach ($gender as $g)
                                    @if(old('gender') == $g["gender"])
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
                        <div class="col mb-4">
                            <label for="tgl_join">Tanggal Masuk Perusahaan</label>
                            <input type="datetime" class="form-control @error('tgl_join') is-invalid @enderror" id="tgl_join" name="tgl_join" value="{{ old('tgl_join') }}">
                            @error('tgl_join')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
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
                                <option value="">Pilih Status Pernikahan</option>
                                @foreach ($sNikah as $s)
                                    @if(old('status_nikah') == $s["status"])
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
                        <div class="col mb-4">
                            <label for="jabatan_id">Jabatan</label>
                            <select name="jabatan_id" id="jabatan_id" class="form-control @error('jabatan_id') is-invalid @enderror selectpicker" data-live-search="true">
                                <option value="">Pilih Jabatan</option>
                                @foreach ($data_jabatan as $dj)
                                    @if(old('jabatan_id') == $dj->id)
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
                    </div>
                        <div class="col mb-4">
                            <label for="rekening">Rekening</label>
                            <input type="number" class="form-control @error('rekening') is-invalid @enderror" id="rekening" name="rekening" value="{{ old('rekening') }}">
                            @error('rekening')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col mb-4">
                            <label for="alamat">Alamat</label>
                            <textarea name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat') }}</textarea>
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
                            <input type="number" class="form-control @error('izin_cuti') is-invalid @enderror" id="izin_cuti" name="izin_cuti" value="{{ old('izin_cuti') }}">
                            @error('izin_cuti')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col mb-4">
                            <label for="cuti_sakit">Cuti Sakit</label>
                            <input type="number" class="form-control @error('cuti_sakit') is-invalid @enderror" id="cuti_sakit" name="cuti_sakit" value="{{ old('cuti_sakit') }}">
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
                            <input type="number" class="form-control @error('cuti_menikah') is-invalid @enderror" id="cuti_menikah" name="cuti_menikah" value="{{ old('cuti_menikah') }}">
                            @error('cuti_menikah')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col mb-4">
                            <label for="cuti_melahirkan">Cuti Melahirkan</label>
                            <input type="number" class="form-control @error('cuti_melahirkan') is-invalid @enderror" id="cuti_melahirkan" name="cuti_melahirkan" value="{{ old('cuti_melahirkan') }}">
                            @error('cuti_melahirkan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col mb-4">
                            <label for="cuti_keguguran">Cuti Kegururan</label>
                            <input type="number" class="form-control @error('cuti_keguguran') is-invalid @enderror" id="cuti_keguguran" name="cuti_keguguran" value="{{ old('cuti_keguguran') }}">
                            @error('cuti_keguguran')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col mb-4">
                            <label for="cuti_istri_melahirkan">Cuti Istri Melahirkan</label>
                            <input type="number" class="form-control @error('cuti_istri_melahirkan') is-invalid @enderror" id="cuti_istri_melahirkan" name="cuti_istri_melahirkan" value="{{ old('cuti_istri_melahirkan') }}">
                            @error('cuti_istri_melahirkan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col mb-4">
                            <label for="cuti_menikahkan_anak">Cuti Menikahkan Anak</label>
                            <input type="number" class="form-control @error('cuti_menikahkan_anak') is-invalid @enderror" id="cuti_menikahkan_anak" name="cuti_menikahkan_anak" value="{{ old('cuti_menikahkan_anak') }}">
                            @error('cuti_menikahkan_anak')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col mb-4">
                            <label for="cuti_khitanan_anak">Cuti Khitanan Anak</label>
                            <input type="number" class="form-control @error('cuti_khitanan_anak') is-invalid @enderror" id="cuti_khitanan_anak" name="cuti_khitanan_anak" value="{{ old('cuti_khitanan_anak') }}">
                            @error('cuti_khitanan_anak')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col mb-4">
                            <label for="cuti_membabtiskan_anak">Cuti Mempabtiskan Anak</label>
                            <input type="number" class="form-control @error('cuti_membabtiskan_anak') is-invalid @enderror" id="cuti_membabtiskan_anak" name="cuti_membabtiskan_anak" value="{{ old('cuti_membabtiskan_anak') }}">
                            @error('cuti_membabtiskan_anak')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col mb-4">
                            <label for="cuti_keluarga_atap">Cuti Suami/Istri/Anak/Orang Tua Meninggal</label>
                            <input type="number" class="form-control @error('cuti_keluarga_atap') is-invalid @enderror" id="cuti_keluarga_atap" name="cuti_keluarga_atap" value="{{ old('cuti_keluarga_atap') }}">
                            @error('cuti_keluarga_atap')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col mb-4">
                            <label for="cuti_keluarga">Cuti Anggota Keluarga Dalam Meninggal</label>
                            <input type="number" class="form-control @error('cuti_keluarga') is-invalid @enderror" id="cuti_keluarga" name="cuti_keluarga" value="{{ old('cuti_keluarga') }}">
                            @error('cuti_keluarga')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col mb-4">
                            <label for="cuti_ibadah_besar">Cuti Ibadah Besar</label>
                            <input type="number" class="form-control @error('cuti_ibadah_besar') is-invalid @enderror" id="cuti_ibadah_besar" name="cuti_ibadah_besar" value="{{ old('cuti_ibadah_besar') }}">
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
                                <input type="text" class="form-control money @error('gaji_pokok') is-invalid @enderror" name="gaji_pokok" value="{{ old('gaji_pokok') }}">
                                <div class="input-group-append">
                                <div class="input-group-text">
                                    <span>/ Bulan</span>
                                </div>
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
                                <input type="text" class="form-control money @error('makan_transport') is-invalid @enderror" name="makan_transport" value="{{ old('makan_transport') }}">
                                <div class="input-group-append">
                                <div class="input-group-text">
                                    <span>/ Bulan</span>
                                </div>
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
                                <input type="text" class="form-control money @error('lembur') is-invalid @enderror" name="lembur" value="{{ old('lembur') }}">
                                <div class="input-group-append">
                                <div class="input-group-text">
                                    <span>/ Jam</span>
                                </div>
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
                                <input type="text" class="form-control money @error('kehadiran') is-invalid @enderror" name="kehadiran" value="{{ old('kehadiran') }}">
                                <div class="input-group-append">
                                <div class="input-group-text">
                                    <span>/ Bulan</span>
                                </div>
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
                                <input type="text" class="form-control money @error('thr') is-invalid @enderror" name="thr" value="{{ old('thr') }}">
                                <div class="input-group-append">
                                <div class="input-group-text">
                                    <span>/ Bulan</span>
                                </div>
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
                                <input type="text" class="form-control money @error('bonus') is-invalid @enderror" name="bonus" value="{{ old('bonus') }}">
                                <div class="input-group-append">
                                <div class="input-group-text">
                                    <span>/ Bulan</span>
                                </div>
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
                                <input type="text" class="form-control money @error('izin') is-invalid @enderror" name="izin" value="{{ old('izin') }}">
                                <div class="input-group-append">
                                <div class="input-group-text">
                                    <span>/ hari</span>
                                </div>
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
                                <input type="text" class="form-control money @error('terlambat') is-invalid @enderror" name="terlambat" value="{{ old('terlambat') }}">
                                <div class="input-group-append">
                                <div class="input-group-text">
                                    <span>/ hari</span>
                                </div>
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
                                <input type="text" class="form-control money @error('mangkir') is-invalid @enderror" name="mangkir" value="{{ old('mangkir') }}">
                                <div class="input-group-append">
                                <div class="input-group-text">
                                    <span>/ hari</span>
                                </div>
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
                                <input type="text" class="form-control money @error('saldo_kasbon') is-invalid @enderror" name="saldo_kasbon" value="{{ old('saldo_kasbon') }}">
                                <div class="input-group-append">
                                <div class="input-group-text">
                                    <span>/ Tahun</span>
                                </div>
                                </div>
                                @error('saldo_kasbon')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary float-right">Submit</button>
                </form>
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