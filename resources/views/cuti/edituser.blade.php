@extends('templates.app')
@section('container')
    <div class="card-section transfer-section">
        <div class="tf-container">
            <div class="tf-balance-box">
                <form class="tf-form p-4" method="post" action="{{ url('/cuti/tambah') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="group-input">
                        <label for="karyawan_id" style="z-index: 1000">Nama Pegawai</label>
                        <select class="form-control" id="karyawan_id" name="karyawan_id">
                            <option value="{{ $data_cuti_user->karyawan_id }}">{{ $data_cuti_user->karyawan->name }}</option>
                        </select>
                    </div>

                    <div class="group-input">
                        @php
                            $data_cuti = [
                                ['nama' => 'Cuti', 'detail' => 'Cuti (' . $data_cuti_user->izin_cuti. ')'],
                                ['nama' => 'Cuti Menikah', 'detail' => 'Cuti Menikah ('. $data_cuti_user->cuti_menikah .')'],
                                ['nama' => 'Cuti Melahirkan', 'detail' => 'Cuti Melahirkan('. $data_cuti_user->cuti_melahirkan .')'],
                                ['nama' => 'Cuti Keguguran', 'detail' => 'Cuti Keguguran('. $data_cuti_user->cuti_keguguran .')'],
                                ['nama' => 'Cuti Istri Melahirkan', 'detail' => 'Cuti Istri Melahirkan('. $data_cuti_user->cuti_istri_melahirkan .')'],
                                ['nama' => 'Cuti Menikahkan Anak', 'detail' => 'Cuti Menikahkan Anak('. $data_cuti_user->cuti_menikahkan_anak .')'],
                                ['nama' => 'Cuti Khitanan Anak', 'detail' => 'Cuti Khitanan Anak('. $data_cuti_user->cuti_khitanan_anak .')'],
                                ['nama' => 'Cuti Membabtiskan Anak', 'detail' => 'Cuti Membabtiskan Anak('. $data_cuti_user->cuti_membabtiskan_anak .')'],
                                ['nama' => 'Cuti Keluarga Atap', 'detail' => 'Cuti Keluarga Atap('. $data_cuti_user->cuti_keluarga_atap .')'],
                                ['nama' => 'Cuti Keluarga', 'detail' => 'Cuti Keluarga('. $data_cuti_user->cuti_keluarga .')'],
                            ];
                
                            $data_izin = [
                                ['nama' => 'Izin Masuk', 'detail' => 'Izin Masuk'],
                                ['nama' => 'Izin Telat', 'detail' => 'Izin Telat'],
                                ['nama' => 'Izin Pulang Cepat', 'detail' => 'Izin Pulang Cepat'],
                                ['nama' => 'Izin Meninggalkan Pekerjaan', 'detail' => 'Izin Meninggalkan Pekerjaan']
                            ];
                        @endphp
                
                        <label for="kategori" style="z-index: 1000">Kategori</label>
                        <select class="form-control @error('kategori') is-invalid @enderror" id="kategori" name="kategori" onchange="updateKategoriOptions()">
                            <option value="">Pilih Kategori</option>
                            <option value="cuti">Cuti</option>
                            <option value="izin">Izin</option>
                            <option value="Izin Sakit">Izin Sakit</option>
                        </select>
                        @error('kategori')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div id="cuti-options" class="group-input">
                        <label for="jenis_cuti" style="z-index: 1000">Jenis Cuti</label>
                        <select class="form-control @error('jenis_cuti') is-invalid @enderror" id="jenis_cuti" name="jenis_cuti">
                            <option value="">Pilih Jenis Cuti</option>
                            @foreach ($data_cuti as $dc)
                                <option value="{{ $dc["nama"] }}">{{ $dc["detail"] }}</option>
                            @endforeach
                        </select>
                        @error('jenis_cuti')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div id="izin-options" class="group-input">
                        <label for="jenis_izin" style="z-index: 1000">Jenis Izin</label>
                        <select class="form-control @error('jenis_izin') is-invalid @enderror" id="jenis_izin" name="jenis_izin" onchange="updateIzinOptions()">
                            <option value="">Pilih Jenis Izin</option>
                            @foreach ($data_izin as $di)
                                <option value="{{ $di["nama"] }}">{{ $di["detail"] }}</option>
                            @endforeach
                        </select>
                        @error('jenis_izin')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="group-input">
                        <label for="tanggal_mulai" id="label_tanggal_mulai">Tanggal Mulai</label>
                        <input type="datetime" class="@error('tanggal_mulai') is-invalid @enderror" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai') }}">
                        @error('tanggal_mulai')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="group-input" id="tanggal_akhir">
                        <label for="tanggal_akhir">Tanggal Akhir</label>
                        <input type="datetime-local" class="@error('tanggal_akhir') is-invalid @enderror" name="tanggal_akhir" id="tanggal_akhir" value="{{ old('tanggal_akhir') }}">
                        @error('tanggal_akhir')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <input type="hidden" name="tanggal">
                    
                    <div class="group-input" id="jam_awal">
                        <label for="jam_awal" class="float-left">Jam Izin</label>
                        <input type="text" class="form-control clockpicker @error('jam_awal') is-invalid @enderror" id="jam_awal" name="jam_awal" autofocus value="{{ old('jam_awal') }}">
                        @error('jam_awal')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="group-input" id="jam_akhir">
                        <label for="jam_akhir" class="float-left">Sampai Jam</label>
                        <input type="text" class="form-control clockpicker @error('jam_akhir') is-invalid @enderror" id="jam_akhir" name="jam_akhir" autofocus value="{{ old('jam_akhir') }}">
                        @error('jam_akhir')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="group-input">
                        <input type="file" name="foto_cuti" id="foto_cuti" class="form-control @error('foto_cuti') is-invalid @enderror">
                        @error('foto_cuti')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="group-input">
                        <label for="alasan_cuti">Alasan</label>
                        <input type="text" class="form-control @error('alasan_cuti') is-invalid @enderror" id="alasan_cuti" name="alasan_cuti" value="{{ old('alasan_cuti') }}">
                        @error('alasan_cuti')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <input type="hidden" name="status_cuti">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <div class="tf-spacing-20"></div>

    @push('script')
        <script>
            $('select').select2();

            $(document).ready(function(){
                $('.clockpicker').clockpicker({
                    donetext: 'Done'
                });

                $('body').on('keyup', '.clockpicker', function (event) {
                    var val = $(this).val();
                    val = val.replace(/[^0-9:]/g, '');
                    val = val.replace(/:+/g, ':');
                    $(this).val(val);
                });
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                updateKategoriOptions(); // Panggil fungsi saat halaman dimuat
                document.getElementById('kategori').addEventListener('change', updateKategoriOptions); // Event listener untuk kategori
                document.getElementById('jenis_izin').addEventListener('change', updateIzinOptions); // Event listener untuk jenis izin
            });

            function updateKategoriOptions() {
                var kategori = document.getElementById('kategori').value;
                var cutiOptions = document.getElementById('cuti-options');
                var izinOptions = document.getElementById('izin-options');
                var tanggalAkhir = document.getElementById('tanggal_akhir');
                var jamAwal = document.getElementById('jam_awal');
                var jamAkhir = document.getElementById('jam_akhir');
                const labelTanggal = document.getElementById('label_tanggal_mulai');

                if (kategori === 'cuti') {
                    cutiOptions.style.display = 'block';
                    izinOptions.style.display = 'none';
                    tanggalAkhir.style.display = 'block';
                    jamAwal.style.display = 'none';
                    jamAkhir.style.display = 'none';
                    labelTanggal.textContent = 'Tanggal Mulai';
                } else if (kategori === 'izin') {
                    cutiOptions.style.display = 'none';
                    izinOptions.style.display = 'block';
                    tanggalAkhir.style.display = 'block';
                    jamAwal.style.display = 'none';
                    jamAkhir.style.display = 'none';
                    labelTanggal.textContent = 'Tanggal Mulai';
                } else if (kategori === 'Izin Sakit'){
                    cutiOptions.style.display = 'none';
                    izinOptions.style.display = 'none';
                    tanggalAkhir.style.display = 'block';
                    jamAwal.style.display = 'none';
                    jamAkhir.style.display = 'none';
                    labelTanggal.textContent = 'Tanggal Mulai';
                } else {
                    cutiOptions.style.display = 'none';
                    izinOptions.style.display = 'none';
                    tanggalAkhir.style.display = 'none';
                    jamAwal.style.display = 'none';
                    jamAkhir.style.display = 'none';
                    labelTanggal.textContent = 'Tanggal Mulai';
                }
            }

            function updateIzinOptions() {
                var jenisIzin = document.getElementById('jenis_izin').value;
                var cutiOptions = document.getElementById('cuti-options');
                var izinOptions = document.getElementById('izin-options');
                var tanggalAkhir = document.getElementById('tanggal_akhir');
                var jamAwal = document.getElementById('jam_awal');
                var jamAkhir = document.getElementById('jam_akhir');
                const labelTanggal = document.getElementById('label_tanggal_mulai');

                if (jenisIzin === 'Izin Meninggalkan Pekerjaan') {
                    cutiOptions.style.display = 'none';
                    izinOptions.style.display = 'block';
                    tanggalAkhir.style.display = 'none';
                    jamAwal.style.display = 'block';
                    jamAkhir.style.display = 'block';
                    labelTanggal.textContent = 'Tanggal';
                } else {
                    // Jika jenis izin lain, kembali ke default
                    updateKategoriOptions(); // Memanggil kembali fungsi kategori untuk reset
                }
            }
        </script>
    @endpush
@endsection
