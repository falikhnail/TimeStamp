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
                        <a href="{{ url('/data-cuti') }}" class="btn btn-danger btn-sm ms-2">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <form method="post" action="{{ url('/data-cuti/proses-tambah') }}" enctype="multipart/form-data" class="p-4">
                    @csrf
                    <div class="form-row">
                        <div class="col mb-4">
                            <label for="user_id_ajax">Nama Pegawai</label>
                            <select id="user_id_ajax" name="karyawan_id" class="form-control selectpicker">
                                <option value="">Pilih Pegawai</option>
                                @foreach ($data_user as $du)
                                    <option value="{{ $du->id }}">{{ $du->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col mb-4">
                            <label for="kategori">Kategori</label>
                            <select name="kategori" id="kategori" class="form-control">
                                <option value="">Pilih Kategori</option>
                                <option value="cuti">Cuti</option>
                                <option value="izin">Izin</option>
                            </select>
                        </div>
                        <div class="col mb-4" id="nama_cuti_container" style="display: none;">
                            <label for="nama_cuti_ajax">Nama Cuti</label>
                            <select name="nama_cuti" id="nama_cuti_ajax" class="form-control">
                                <option value="">Pilih Cuti</option>
                            </select>
                        </div>
                        <div class="col mb-4" id="nama_izin_container" style="display: none;">
                            <label for="nama_izin_ajax">Nama Izin</label>
                            <select name="nama_izin" id="nama_izin_ajax" class="form-control">
                                <option value="">Pilih Izin</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col mb-4">
                            <label for="tanggal_mulai">Tanggal Mulai</label>
                            <input type="datetime" class="form-control @error('tanggal_mulai') is-invalid @enderror" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai') }}">
                            @error('tanggal_mulai')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col mb-4">
                            <label for="tanggal_akhir">Tanggal Akhir</label>
                            <input type="datetime" class="form-control @error('tanggal_akhir') is-invalid @enderror" name="tanggal_akhir" id="tanggal_akhir" value="{{ old('tanggal_akhir') }}">
                            @error('tanggal_akhir')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <input type="hidden" name="tanggal">
                    </div>
                    <div class="form-row">
                        <div class="col mb-4">
                            <label for="foto_cuti">Unggah Foto atau Ambil dari Kamera</label>
                            
                            <!-- Opsi untuk unggah file -->
                            <input type="radio" id="upload_option" name="file_option" value="upload" checked>
                            <label for="upload_option">Unggah File</label>
                        
                            <!-- Opsi untuk menggunakan kamera -->
                            <input type="radio" id="camera_option" name="file_option" value="camera">
                            <label for="camera_option">Buka Kamera</label>
                        
                            <!-- Input untuk unggah file -->
                            <input type="file" name="foto_cuti" id="foto_cuti" class="form-control @error('foto_cuti') is-invalid @enderror">
                            <img id="upload_preview" style="display: none; margin-top: 10px;" width="320" height="240" alt="Preview Gambar Unggahan"/>
                        
                            <!-- Input untuk kamera -->
                            <div id="camera_input" style="display: none;">
                                <video id="camera_stream" width="320" height="240" autoplay></video>
                                <button type="button" id="capture_button">Ambil Foto</button>
                                <canvas id="camera_canvas" style="display: none;"></canvas>
                                <input type="hidden" name="captured_image" id="captured_image">
                            </div>
                            <img id="camera_preview" style="display: none; margin-top: 10px;" width="320" height="240" alt="Preview Foto Kamera"/>
                        
                            @error('foto_cuti')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col mb-4">
                            <label for="alasan_cuti">Alasan Cuti</label>
                            <input type="text" class="form-control @error('alasan_cuti') is-invalid @enderror" id="alasan_cuti" name="alasan_cuti" value="{{ old('alasan_cuti') }}">
                            @error('alasan_cuti')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <input type="hidden" name="status_cuti">
                    </div>
                    <button type="submit" class="btn btn-primary">Kirim</button>
                </form>
            </div>
        </div>
    </div>

    @push('script')
        <script>
            $(document).ready(function(){
                $('#nama_cuti_ajax').select2();
                $('#nama_izin_ajax').select2();
                
                // CSRF Token setup for AJAX requests
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // Handle user selection change
                $('#user_id_ajax').on('change', function(){
                    updateCutiIzin();
                });

                // Handle category change
                $('#kategori').on('change', function(){
                    updateCutiIzin();
                });

                function updateCutiIzin() {
                    let user_id = $('#user_id_ajax').val();
                    let kategori = $('#kategori').val();

                    if (user_id && kategori) {
                        $.ajax({
                            type: 'POST',
                            url: "{{ url('/data-cuti/getuserid') }}",
                            data: {id: user_id, kategori: kategori},
                            success: function(response){
                                $('#nama_cuti_ajax').select2('destroy');
                                $('#nama_izin_ajax').select2('destroy');

                                if (kategori == 'cuti') {
                                    $('#nama_cuti_ajax').html(response.options);
                                    $('#nama_cuti_container').show();
                                    $('#nama_izin_container').hide();
                                } else if (kategori == 'izin') {
                                    $('#nama_izin_ajax').html(response.options);
                                    $('#nama_izin_container').show();
                                    $('#nama_cuti_container').hide();
                                }
                                
                                $('#nama_cuti_ajax').select2();
                                $('#nama_izin_ajax').select2();
                            },
                            error: function(data){
                                console.log('error:', data);
                            }
                        });
                    } else {
                        $('#nama_cuti_container').hide();
                        $('#nama_izin_container').hide();
                    }
                }
            });
        </script>
    @endpush
    <script>
        const uploadOption = document.getElementById('upload_option');
        const cameraOption = document.getElementById('camera_option');
        const fotoCutiInput = document.getElementById('foto_cuti');
        const cameraInput = document.getElementById('camera_input');
        const video = document.getElementById('camera_stream');
        const canvas = document.getElementById('camera_canvas');
        const captureButton = document.getElementById('capture_button');
        const capturedImage = document.getElementById('captured_image');
        const uploadPreview = document.getElementById('upload_preview');
        const cameraPreview = document.getElementById('camera_preview');
    
        // Switch between file upload and camera input
        uploadOption.addEventListener('change', function() {
            fotoCutiInput.style.display = 'block';
            cameraInput.style.display = 'none';
            uploadPreview.style.display = 'block';
            cameraPreview.style.display = 'none';
        });
    
        cameraOption.addEventListener('change', function() {
            fotoCutiInput.style.display = 'none';
            cameraInput.style.display = 'block';
            uploadPreview.style.display = 'none';
            cameraPreview.style.display = 'block';
            startCamera();
        });
    
        // Start the camera stream
        function startCamera() {
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
                    video.srcObject = stream;
                    video.play();
                });
            }
        }
    
        // Capture image from camera
        captureButton.addEventListener('click', function() {
            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
    
            // Convert to base64 and set to hidden input
            const imageData = canvas.toDataURL('image/png');
            capturedImage.value = imageData;
    
            // Show the captured image in preview
            cameraPreview.src = imageData;
            cameraPreview.style.display = 'block';
            
            alert('Foto berhasil diambil!');
        });
    
        // Preview image when file is uploaded
        fotoCutiInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    uploadPreview.src = e.target.result;
                    uploadPreview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
