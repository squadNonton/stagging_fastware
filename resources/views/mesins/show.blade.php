@extends('layout')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Dept.Head Maintenance</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Mesin & Approved FPP</li>
                    <li class="breadcrumb-item active">Lihat Data Mesin</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="container">
                <div class="row">
                    <div class="col-mt-4">
                        <div class="card">
                            <div class="accordion">
                                <div class="card-body">
                                    <h5 class="card-title">Form Lihat Mesin</h5>
                                    <!-- Form di sini -->
                                    <div class="collapse" id="updateProgress">
                                        <form id="mesinForm" action="{{ route('mesins.update', $mesin->id) }}"
                                            method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')

                                            <div class="mb-3">
                                                <label for="section" class="form-label">
                                                    Section<span style="color: red;">*</span>
                                                </label>
                                                <input type="text" class="form-control" id="section" name="section"
                                                    value="{{ $mesin->section }}" readonly>
                                            </div>

                                            <div class="mb-3">
                                                <label for="tipe" class="form-label">
                                                    Tipe<span style="color: red;">*</span>
                                                </label>
                                                <input type="text" class="form-control" id="tipe" name="tipe"
                                                    value="{{ $mesin->tipe }}" readonly>
                                            </div>

                                            <div class="mb-3">
                                                <label for="no_mesin" class="form-label">
                                                    Nomor Mesin<span style="color: red;">*</span>
                                                </label>
                                                <input type="text" class="form-control" id="no_mesin" name="no_mesin"
                                                    value="{{ $mesin->no_mesin }}" readonly>
                                            </div>

                                            <div class="mb-3">
                                                <label for="tanggal_dibuat" class="form-label">
                                                    Manufacturing Year<span style="color: red;">*</span>
                                                </label>
                                                <input type="number" class="form-control" id="tanggal_dibuat"
                                                    name="tanggal_dibuat" value="{{ $mesin->tanggal_dibuat }}"
                                                    min="1900" max="2099" onchange="hitungUmur()" readonly>
                                            </div>

                                            <div class="mb-3">
                                                <label for="umur" class="form-label">Umur<span
                                                        style="color: red;">*</span></label>
                                                <input type="text" class="form-control" id="umur" name="umur"
                                                    value="{{ $mesin->umur }}" readonly>
                                                <!-- Jika Anda ingin melakukan perhitungan Age secara otomatis, Anda perlu menambahkan JavaScript untuk menghitungnya. -->
                                            </div>

                                            <div class="mb-3">
                                                <label for="spesifikasi" class="form-label">
                                                    Spesifikasi<span style="color: red;">*</span>
                                                </label>
                                                <textarea class="form-control" id="spesifikasi" name="spesifikasi" readonly>{{ $mesin->spesifikasi }}</textarea>
                                            </div>

                                            <div class="mb-3">
                                                <label for="lokasi" class="form-label">
                                                    Lokasi Mesin<span style="color: red;">*</span>
                                                </label>
                                                <select class="form-select" id="lokasi" name="lokasi" disabled>
                                                    <option value="" disabled>Pilih Lokasi Mesin</option>
                                                    <option value="Deltamas"
                                                        {{ strtoupper($mesin->lokasi) === 'DELTAMAS' ? 'selected' : '' }}>
                                                        Deltamas</option>
                                                    <option value="DS8"
                                                        {{ strtoupper($mesin->lokasi) === 'DS8' ? 'selected' : '' }}>DS8
                                                    </option>
                                                    <option value="Surabaya"
                                                        {{ strtoupper($mesin->lokasi) === 'SURABAYA' ? 'selected' : '' }}>
                                                        Surabaya</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="foto" class="form-label">Foto</label>
                                                <div>
                                                    @if ($mesin->foto)
                                                        <!-- Jika ada foto, tampilkan gambar -->
                                                        <img id="fotoPreview" src="{{ asset('assets/' . $mesin->foto) }}"
                                                            alt="Preview Foto" style="max-width: 200px; cursor: pointer;">
                                                    @else
                                                        <!-- Jika tidak ada foto, tampilkan pesan -->
                                                        <img id="fotoPreview" src="" alt=""
                                                            style="max-width: 300px; max-height: 200px; display: none; cursor: pointer;">
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="sparepart" class="form-label">Sparepart</label>
                                                <div>
                                                    @if ($mesin->sparepart)
                                                        <!-- Jika ada sparepart, tampilkan gambar -->
                                                        <img id="sparepartPreview"
                                                            src="{{ asset('assets/' . $mesin->sparepart) }}"
                                                            alt="Preview Sparepart"
                                                            style="max-width: 200px; cursor: pointer;">
                                                    @else
                                                        <!-- Jika tidak ada sparepart, tampilkan pesan -->
                                                        <img id="sparepartPreview" src="" alt=""
                                                            style="max-width: 300px; max-height: 200px; display: none; cursor: pointer;">
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Modal untuk foto -->
                                            <div id="fotoModal" class="modal">
                                                <span class="close" onclick="closeFotoModal()">&times;</span>
                                                <div class="modal-content">
                                                    <img id="fotoModalImage" src="" alt="">
                                                </div>
                                            </div>

                                            <!-- Modal untuk sparepart -->
                                            <div id="sparepartModal" class="modal">
                                                <span class="close" onclick="closeSparepartModal()">&times;</span>
                                                <div class="modal-content">
                                                    <img id="sparepartModalImage" src="" alt="">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-mt-4">
                        <div class="card">
                            <div class="accordion">
                                <div class="card-body">
                                    <b>
                                        <h5 class="card-title">Tabel List Sparepart - Mesin {{ $mesin->no_mesin }}</h5>
                                    </b>
                                    <div class="collapse" id="updateProgress">
                                        <form id="importForm" enctype="multipart/form-data">
                                            @csrf
                                            <input type="file" id="file" name="file" class="form-control">
                                            <br>
                                            <button type="submit" class="btn btn-danger">Unggah Sparepart</button>
                                        </form>

                                        <div id="message"></div>
                                        <!-- Bagian HTML untuk menampilkan tabel sparepart -->
                                        <a class="btn btn-success float-end"
                                            href="{{ route('spareparts.export', ['nomor_mesin' => $mesin->no_mesin]) }}">Ekspor
                                            Sparepart</a>
                                        <br><br>
                                        <div class="table-responsive">
                                            <table class="datatables datatable" style="table-layout: responsive;">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama Sparepart</th>
                                                        <th>Deskripsi</th>
                                                        <th>Jumlah Stok</th>
                                                        <th>Tanggal Upload</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($spareparts as $index => $sparepart)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>{{ $sparepart->nama_sparepart }}</td>
                                                            <td>{{ $sparepart->deskripsi }}</td>
                                                            <td>{{ $sparepart->jumlah_stok }}</td>
                                                            <td>{{ $sparepart->updated_at }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mt-4">
                            <div class="card-body">
                                <div class="accordion">
                                    <b>
                                        <h5 class="card-title">Table Riwayat Kerusakan Mesin </h5>
                                    </b>
                                    <div class="collapse" id="updateProgress">
                                        <div class="text-end">
                                            <a href="{{ route('pdf.mesin', ['mesin' => $mesin]) }}" class="pdf-button">
                                                <i class="fas fa-file-pdf"></i> Ekspor Riwayat Mesin
                                            </a>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="datatables datatable" style="table-layout: responsive;">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nomor FPP</th>
                                                        <th>Mesin</th>
                                                        <th>Section</th>
                                                        <th>Lokasi</th>
                                                        <th>Kendala</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($formperbaikans as $formperbaikan)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <!-- Menggunakan $loop->iteration untuk menampilkan nomor baris -->
                                                            <td>{{ $formperbaikan->id_fpp }}</td>
                                                            <td>{{ $formperbaikan->mesin }}</td>
                                                            <td>{{ $formperbaikan->section }}</td>
                                                            <td>{{ $formperbaikan->lokasi }}</td>
                                                            <td>{{ $formperbaikan->kendala }}</td>
                                                            <td>
                                                                <div
                                                                    style="background-color: {{ $formperbaikan->status_background_color }};
                                        border-radius: 5px; /* Rounded corners */
                                        padding: 5px 10px; /* Padding inside the div */
                                        color: white; /* Text color, adjust as needed */
                                        font-weight: bold; /* Bold text */
                                        text-align: center; /* Center-align text */
                                        text-transform: uppercase; /* Uppercase text */
                                    ">
                                                                    {{ $formperbaikan->ubahtext() }}
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <a class="btn btn-warning"
                                                                    href="{{ route('fpps.show', $formperbaikan->id) }}">
                                                                    <i class="bi bi-eye-fill"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            </div>
        </section>

        <script>
            $(document).ready(function() {
                $('#table1').DataTable();
                $('#table2').DataTable();
            });
        </script>

        <script>
            function validateForm() {
                // Add form validation logic if needed
                return true;
            }

            $(document).ready(function() {
                $('#importForm').on('submit', function(e) {
                    e.preventDefault();

                    if (!validateForm()) {
                        return false;
                    }

                    var formData = new FormData(this);
                    var nomorMesin = "{{ $mesin->no_mesin }}";

                    $.ajax({
                        url: "{{ route('spareparts.import') }}?nomor_mesin=" + nomorMesin,
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function(xhr) {
                            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr(
                                'content'));
                        },
                        success: function(response) {
                            $('#message').html('<div class="alert alert-success">' + response
                                .message + '</div>');
                            setTimeout(function() {
                                location.reload(); // Refresh the page after 2 seconds
                            }, 1000); // 2000 milliseconds = 2 seconds
                        },
                        error: function(xhr) {
                            var error = xhr.responseJSON ? xhr.responseJSON.message :
                                'Terjadi kesalahan.';
                            $('#message').html('<div class="alert alert-danger">' + error +
                                '</div>');
                            setTimeout(function() {
                                location.reload(); // Refresh the page after 2 seconds
                            }, 1000); // 2000 milliseconds = 2 seconds
                        }
                    });
                });
            });
        </script>


        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    </main><!-- End #main -->


    <script>
        // Function to validate file upload
        function validateForm() {
            var fileInput = document.getElementById('file');
            // Check if file input is empty
            if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                // If empty, show SweetAlert2
                Swal.fire({
                    icon: 'error',
                    title: 'File Tidak Ada !!!',
                    text: 'Tolong unggah file sesuai dengan formatnya.'
                });
                return false; // Prevent form submission
            }
            return true; // Allow form submission
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var fotoPreview = document.getElementById('fotoPreview');
            var sparepartPreview = document.getElementById('sparepartPreview');

            fotoPreview.addEventListener('click', function() {
                toggleModal('fotoModal', 'fotoPreview', 'fotoModalImage');
            });

            sparepartPreview.addEventListener('click', function() {
                toggleModal('sparepartModal', 'sparepartPreview', 'sparepartModalImage');
            });

            function toggleModal(modalId, previewId, modalImageId) {
                var modal = document.getElementById(modalId);
                var modalImg = document.getElementById(modalImageId);
                var preview = document.getElementById(previewId);

                if (modal.style.display === "block") {
                    modal.style.display = "none";
                } else {
                    modal.style.display = "block";
                    modalImg.src = preview.src;
                }
            }

            function closeFotoModal() {
                var modal = document.getElementById("fotoModal");
                modal.style.display = "none";
            }

            function closeSparepartModal() {
                var modal = document.getElementById("sparepartModal");
                modal.style.display = "none";
            }

            var fotoModal = document.getElementById("fotoModal");
            var sparepartModal = document.getElementById("sparepartModal");

            // Menambahkan event listener untuk menutup modal saat tombol "X" diklik
            fotoModal.querySelector(".close").addEventListener('click', function() {
                closeFotoModal();
            });

            sparepartModal.querySelector(".close").addEventListener('click', function() {
                closeSparepartModal();
            });

            // Menambahkan event listener untuk menutup modal saat area di luar modal diklik
            window.addEventListener('click', function(event) {
                if (event.target == fotoModal || event.target == sparepartModal) {
                    closeFotoModal();
                    closeSparepartModal();
                }
            });
        });
    </script>

    <style>
        /* CSS untuk modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 600px;
            /* Ubah ukuran lebar */
            height: 400px;
            /* Ubah ukuran tinggi */
            overflow: auto;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            padding: 20px;
        }

        /* CSS untuk tombol close */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* CSS untuk gambar di dalam modal */
        .modal-content {
            width: 100%;
            height: auto;
        }
    </style>

    <style>
        .pdf-button {
            background-color: red;
            /* Warna latar belakang */
            border: none;
            color: white;
            padding: 10px 20px;
            /* Padding agar tombol terlihat lebih luas */
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            transition-duration: 0.4s;
            cursor: pointer;
            border-radius: 4px;
        }

        .pdf-button i {
            margin-right: 5px;
            /* Spasi antara ikon dan teks */
        }
    </style>
@endsection

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Dept.Head Maintenance</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Mesin & Approved FPP</li>
                    <li class="breadcrumb-item active">Lihat Data Mesin</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="container">
                <div class="row">
                    <div class="col-mt-4">
                        <div class="card">
                            <div class="accordion">
                                <div class="card-body">
                                    <h5 class="card-title">Form Lihat Mesin</h5>
                                    <!-- Form di sini -->
                                    <div class="collapse" id="updateProgress">
                                        <form id="mesinForm" action="{{ route('mesins.update', $mesin->id) }}"
                                            method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')

                                            <div class="mb-3">
                                                <label for="section" class="form-label">
                                                    Section<span style="color: red;">*</span>
                                                </label>
                                                <input type="text" class="form-control" id="section" name="section"
                                                    value="{{ $mesin->section }}" readonly>
                                            </div>

                                            <div class="mb-3">
                                                <label for="tipe" class="form-label">
                                                    Tipe<span style="color: red;">*</span>
                                                </label>
                                                <input type="text" class="form-control" id="tipe" name="tipe"
                                                    value="{{ $mesin->tipe }}" readonly>
                                            </div>

                                            <div class="mb-3">
                                                <label for="no_mesin" class="form-label">
                                                    Nomor Mesin<span style="color: red;">*</span>
                                                </label>
                                                <input type="text" class="form-control" id="no_mesin" name="no_mesin"
                                                    value="{{ $mesin->no_mesin }}" readonly>
                                            </div>

                                            <div class="mb-3">
                                                <label for="tanggal_dibuat" class="form-label">
                                                    Manufacturing Year<span style="color: red;">*</span>
                                                </label>
                                                <input type="number" class="form-control" id="tanggal_dibuat"
                                                    name="tanggal_dibuat" value="{{ $mesin->tanggal_dibuat }}"
                                                    min="1900" max="2099" onchange="hitungUmur()" readonly>
                                            </div>

                                            <div class="mb-3">
                                                <label for="umur" class="form-label">Umur<span
                                                        style="color: red;">*</span></label>
                                                <input type="text" class="form-control" id="umur" name="umur"
                                                    value="{{ $mesin->umur }}" readonly>
                                                <!-- Jika Anda ingin melakukan perhitungan Age secara otomatis, Anda perlu menambahkan JavaScript untuk menghitungnya. -->
                                            </div>

                                            <div class="mb-3">
                                                <label for="spesifikasi" class="form-label">
                                                    Spesifikasi<span style="color: red;">*</span>
                                                </label>
                                                <textarea class="form-control" id="spesifikasi" name="spesifikasi" readonly>{{ $mesin->spesifikasi }}</textarea>
                                            </div>

                                            <div class="mb-3">
                                                <label for="lokasi" class="form-label">
                                                    Lokasi Mesin<span style="color: red;">*</span>
                                                </label>
                                                <select class="form-select" id="lokasi" name="lokasi" disabled>
                                                    <option value="" disabled>Pilih Lokasi Mesin</option>
                                                    <option value="Deltamas"
                                                        {{ strtoupper($mesin->lokasi) === 'DELTAMAS' ? 'selected' : '' }}>
                                                        Deltamas</option>
                                                    <option value="DS8"
                                                        {{ strtoupper($mesin->lokasi) === 'DS8' ? 'selected' : '' }}>DS8
                                                    </option>
                                                    <option value="Surabaya"
                                                        {{ strtoupper($mesin->lokasi) === 'SURABAYA' ? 'selected' : '' }}>
                                                        Surabaya</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="foto" class="form-label">Foto</label>
                                                <div>
                                                    @if ($mesin->foto)
                                                        <!-- Jika ada foto, tampilkan gambar -->
                                                        <img id="fotoPreview" src="{{ asset('assets/' . $mesin->foto) }}"
                                                            alt="Preview Foto" style="max-width: 200px; cursor: pointer;">
                                                    @else
                                                        <!-- Jika tidak ada foto, tampilkan pesan -->
                                                        <img id="fotoPreview" src="" alt=""
                                                            style="max-width: 300px; max-height: 200px; display: none; cursor: pointer;">
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="sparepart" class="form-label">Sparepart</label>
                                                <div>
                                                    @if ($mesin->sparepart)
                                                        <!-- Jika ada sparepart, tampilkan gambar -->
                                                        <img id="sparepartPreview"
                                                            src="{{ asset('assets/' . $mesin->sparepart) }}"
                                                            alt="Preview Sparepart"
                                                            style="max-width: 200px; cursor: pointer;">
                                                    @else
                                                        <!-- Jika tidak ada sparepart, tampilkan pesan -->
                                                        <img id="sparepartPreview" src="" alt=""
                                                            style="max-width: 300px; max-height: 200px; display: none; cursor: pointer;">
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Modal untuk foto -->
                                            <div id="fotoModal" class="modal">
                                                <span class="close" onclick="closeFotoModal()">&times;</span>
                                                <div class="modal-content">
                                                    <img id="fotoModalImage" src="" alt="">
                                                </div>
                                            </div>

                                            <!-- Modal untuk sparepart -->
                                            <div id="sparepartModal" class="modal">
                                                <span class="close" onclick="closeSparepartModal()">&times;</span>
                                                <div class="modal-content">
                                                    <img id="sparepartModalImage" src="" alt="">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-mt-4">
                        <div class="card">
                            <div class="accordion">
                                <div class="card-body">
                                    <b>
                                        <h5 class="card-title">Tabel List Sparepart - Mesin {{ $mesin->no_mesin }}</h5>
                                    </b>
                                    <div class="collapse" id="updateProgress">
                                        <form id="importForm" enctype="multipart/form-data">
                                            @csrf
                                            <input type="file" id="file" name="file" class="form-control">
                                            <br>
                                            <button type="submit" class="btn btn-danger">Unggah Sparepart</button>
                                        </form>

                                        <div id="message"></div>
                                        <!-- Bagian HTML untuk menampilkan tabel sparepart -->
                                        <a class="btn btn-success float-end"
                                            href="{{ route('spareparts.export', ['nomor_mesin' => $mesin->no_mesin]) }}">Ekspor
                                            Sparepart</a>
                                        <br><br>
                                        <div class="table-responsive">
                                            <table class="datatables datatable" style="table-layout: responsive;">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama Sparepart</th>
                                                        <th>Deskripsi</th>
                                                        <th>Jumlah Stok</th>
                                                        <th>Tanggal Upload</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($spareparts as $index => $sparepart)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>{{ $sparepart->nama_sparepart }}</td>
                                                            <td>{{ $sparepart->deskripsi }}</td>
                                                            <td>{{ $sparepart->jumlah_stok }}</td>
                                                            <td>{{ $sparepart->updated_at }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mt-4">
                            <div class="card-body">
                                <div class="accordion">
                                    <b>
                                        <h5 class="card-title">Table Riwayat Kerusakan Mesin </h5>
                                    </b>
                                    <div class="collapse" id="updateProgress">
                                        <div class="text-end">
                                            <a href="{{ route('pdf.mesin', ['mesin' => $mesin]) }}" class="pdf-button">
                                                <i class="fas fa-file-pdf"></i> Ekspor Riwayat Mesin
                                            </a>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="datatables datatable" style="table-layout: responsive;">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nomor FPP</th>
                                                        <th>Mesin</th>
                                                        <th>Section</th>
                                                        <th>Lokasi</th>
                                                        <th>Kendala</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($formperbaikans as $formperbaikan)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <!-- Menggunakan $loop->iteration untuk menampilkan nomor baris -->
                                                            <td>{{ $formperbaikan->id_fpp }}</td>
                                                            <td>{{ $formperbaikan->mesin }}</td>
                                                            <td>{{ $formperbaikan->section }}</td>
                                                            <td>{{ $formperbaikan->lokasi }}</td>
                                                            <td>{{ $formperbaikan->kendala }}</td>
                                                            <td>
                                                                <div
                                                                    style="background-color: {{ $formperbaikan->status_background_color }};
                                        border-radius: 5px; /* Rounded corners */
                                        padding: 5px 10px; /* Padding inside the div */
                                        color: white; /* Text color, adjust as needed */
                                        font-weight: bold; /* Bold text */
                                        text-align: center; /* Center-align text */
                                        text-transform: uppercase; /* Uppercase text */
                                    ">
                                                                    {{ $formperbaikan->ubahtext() }}
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <a class="btn btn-warning"
                                                                    href="{{ route('fpps.show', $formperbaikan->id) }}">
                                                                    <i class="bi bi-eye-fill"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            </div>
        </section>

        <script>
            $(document).ready(function() {
                $('#table1').DataTable();
                $('#table2').DataTable();
            });
        </script>

        <script>
            function validateForm() {
                // Add form validation logic if needed
                return true;
            }

            $(document).ready(function() {
                $('#importForm').on('submit', function(e) {
                    e.preventDefault();

                    if (!validateForm()) {
                        return false;
                    }

                    var formData = new FormData(this);
                    var nomorMesin = "{{ $mesin->no_mesin }}";

                    $.ajax({
                        url: "{{ route('spareparts.import') }}?nomor_mesin=" + nomorMesin,
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function(xhr) {
                            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr(
                                'content'));
                        },
                        success: function(response) {
                            $('#message').html('<div class="alert alert-success">' + response
                                .message + '</div>');
                            setTimeout(function() {
                                location.reload(); // Refresh the page after 2 seconds
                            }, 1000); // 2000 milliseconds = 2 seconds
                        },
                        error: function(xhr) {
                            var error = xhr.responseJSON ? xhr.responseJSON.message :
                                'Terjadi kesalahan.';
                            $('#message').html('<div class="alert alert-danger">' + error +
                                '</div>');
                            setTimeout(function() {
                                location.reload(); // Refresh the page after 2 seconds
                            }, 1000); // 2000 milliseconds = 2 seconds
                        }
                    });
                });
            });
        </script>


        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    </main><!-- End #main -->


    <script>
        // Function to validate file upload
        function validateForm() {
            var fileInput = document.getElementById('file');
            // Check if file input is empty
            if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                // If empty, show SweetAlert2
                Swal.fire({
                    icon: 'error',
                    title: 'File Tidak Ada !!!',
                    text: 'Tolong unggah file sesuai dengan formatnya.'
                });
                return false; // Prevent form submission
            }
            return true; // Allow form submission
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var fotoPreview = document.getElementById('fotoPreview');
            var sparepartPreview = document.getElementById('sparepartPreview');

            fotoPreview.addEventListener('click', function() {
                toggleModal('fotoModal', 'fotoPreview', 'fotoModalImage');
            });

            sparepartPreview.addEventListener('click', function() {
                toggleModal('sparepartModal', 'sparepartPreview', 'sparepartModalImage');
            });

            function toggleModal(modalId, previewId, modalImageId) {
                var modal = document.getElementById(modalId);
                var modalImg = document.getElementById(modalImageId);
                var preview = document.getElementById(previewId);

                if (modal.style.display === "block") {
                    modal.style.display = "none";
                } else {
                    modal.style.display = "block";
                    modalImg.src = preview.src;
                }
            }

            function closeFotoModal() {
                var modal = document.getElementById("fotoModal");
                modal.style.display = "none";
            }

            function closeSparepartModal() {
                var modal = document.getElementById("sparepartModal");
                modal.style.display = "none";
            }

            var fotoModal = document.getElementById("fotoModal");
            var sparepartModal = document.getElementById("sparepartModal");

            // Menambahkan event listener untuk menutup modal saat tombol "X" diklik
            fotoModal.querySelector(".close").addEventListener('click', function() {
                closeFotoModal();
            });

            sparepartModal.querySelector(".close").addEventListener('click', function() {
                closeSparepartModal();
            });

            // Menambahkan event listener untuk menutup modal saat area di luar modal diklik
            window.addEventListener('click', function(event) {
                if (event.target == fotoModal || event.target == sparepartModal) {
                    closeFotoModal();
                    closeSparepartModal();
                }
            });
        });
    </script>

    <style>
        /* CSS untuk modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 600px;
            /* Ubah ukuran lebar */
            height: 400px;
            /* Ubah ukuran tinggi */
            overflow: auto;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            padding: 20px;
        }

        /* CSS untuk tombol close */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* CSS untuk gambar di dalam modal */
        .modal-content {
            width: 100%;
            height: auto;
        }
    </style>

    <style>
        .pdf-button {
            background-color: red;
            /* Warna latar belakang */
            border: none;
            color: white;
            padding: 10px 20px;
            /* Padding agar tombol terlihat lebih luas */
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            transition-duration: 0.4s;
            cursor: pointer;
            border-radius: 4px;
        }

        .pdf-button i {
            margin-right: 5px;
            /* Spasi antara ikon dan teks */
        }
    </style>
@endsection
