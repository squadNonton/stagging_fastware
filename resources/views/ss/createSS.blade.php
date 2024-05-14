@extends('layout')

@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Form SS</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboardHandling') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Form SS</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Table View SS</h5>
                            <br>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#sumbangSaranModal"><i class="fas fa-plus"></i> Tambah</button>
                            <!-- Table with stripped rows -->
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th width="50px">NO</th>
                                        <th width="100px">Nama</th>
                                        <th width="100px">NPK</th>
                                        <th class="text-center" width="100px">Bagian</th>
                                        <th width="100px">Judul Ide</th>
                                        <th width="100px">Tanggal Pengajuan Ide</th>
                                        <th width="100px">Lokasi</th>
                                        <th width="100px">Tanggal Diterapkan</th>
                                        <th width="100px">Pembaruan Terakhir</th>
                                        <th width="100px">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $data)
                                        <tr>
                                            <th scope="row" class="text-center">{{ $loop->iteration }}</th>
                                            <td class="text-center py-3">{{ $data->users->name ?? '' }}</td>
                                            <td class="text-center py-3">{{ $data->users->npk ?? '' }}</td>
                                            <td class="text-center py-3">{{ $usersRoles[$data->id_user] ?? '' }}</td>
                                            <td class="text-center py-3">{{ $data->judul }}</td>
                                            <td class="text-center py-3">{{ $data->tgl_pengajuan_ide }}</td>
                                            <td class="text-center py-3">{{ $data->lokasi_ide }}</td>
                                            <td class="text-center py-3">{{ $data->tgl_diterapkan }}</td>
                                            <td class="text-center py-3">{{ $data->created_at }}</td>
                                            <td class="text-center">
                                                <button class="btn btn-primary btn-sm"
                                                    onclick="openEditModal({{ $data->id }})"
                                                    data-id="{{ $data->id }}" title="Edit">
                                                    <i class="fa-solid fa-edit fa-1x"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm"
                                                    onclick="confirmDelete('{{ $data->id }}')" title="Hapus">
                                                    <i class="fas fa-trash fa-1x"></i>
                                                </button>
                                                <button class="btn btn-success btn-sm"
                                                    onclick="showViewSumbangSaranModal({{ $data->id }})"
                                                    data-id="{{ $data->id }}" title="lihat">
                                                    <i class="fa-solid fa-eye fa-1x"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->
                        </div>
                    </div>
                </div>
            </div>
            {{-- Add SS --}}
            <div class="modal fade" id="sumbangSaranModal" tabindex="-1" aria-labelledby="sumbangSaranModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" style="max-width: 90%;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="sumbangSaranModalLabel">Tambah Sumbang Saran</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Form goes here -->
                            <form id="sumbangSaranForm">
                                @csrf
                                <div class="row mb-3">
                                    <label for="inputDate" class="col-sm-2 col-form-label">Tgl. pengajuan Ide <span
                                            style="color: red;">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="date" class="form-control" id="tgl_pengajuan_ide"
                                            name="tgl_pengajuan_ide" required>
                                        <input type="hidden" id="id_user" name="id_user" value="{{ Auth::user()->id }}">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-2 col-form-label">Lokasi Ide <span
                                            style="color: red;">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="lokasi_ide" name="lokasi_ide"
                                            required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputDate" class="col-sm-2 col-form-label">Tgl. Diterapkan<span
                                            style="color: red;">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="date" class="form-control" id="tgl_diterapkan"
                                            name="tgl_diterapkan">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-2 col-form-label">Judul Ide <span
                                            style="color: red;">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="judul" name="judul"
                                            required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputPassword" class="col-sm-2 col-form-label">Keadaan Sebelumnya
                                        (Permasalahan) <span style="color: red;">*</span></label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" style="height: 100px" id="keadaan_sebelumnya" name="keadaan_sebelumnya" required></textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputNumber" class="col-sm-2 col-form-label">File Upload</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="file" id="image" name="image">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputPassword" class="col-sm-2 col-form-label">Usulan Ide <span
                                            style="color: red;">*</span></label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" style="height: 100px" id="usulan_ide" name="usulan_ide" required></textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputNumber" class="col-sm-2 col-form-label">File Upload</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="file" id="image_2" name="image_2">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputPassword" class="col-sm-2 col-form-label">Keuntungan Dari Penerapan
                                        Ide</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" style="height: 100px" id="keuntungan_ide" name="keuntungan_ide"></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" onclick="submitForm()">Save</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Edit Form -->
            <!-- Modal Form Edit Sumbang Saran -->
            <div class="modal fade" id="editSumbangSaranModal" tabindex="-1"
                aria-labelledby="editSumbangSaranModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" style="max-width: 90%;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editSumbangSaranModalLabel">Edit Sumbang Saran</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Form Edit Sumbang Saran -->
                            <form id="editSumbangSaranForm" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-3">
                                    <label for="editTglPengajuan" class="col-sm-2 col-form-label">Tgl. pengajuan Ide <span
                                            style="color: red;">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="date" class="form-control" id="editTglPengajuan"
                                            name="tgl_pengajuan_ide" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="editLokasiIde" class="col-sm-2 col-form-label">Lokasi Ide <span
                                            style="color: red;">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="editLokasiIde" name="lokasi_ide"
                                            required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="editTglDiterapkan" class="col-sm-2 col-form-label">Tgl. Diterapkan<span
                                            style="color: red;">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="date" class="form-control" id="editTglDiterapkan"
                                            name="tgl_diterapkan">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="editJudulIde" class="col-sm-2 col-form-label">Judul Ide <span
                                            style="color: red;">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="editJudulIde" name="judul"
                                            required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="editKeadaanSebelumnya" class="col-sm-2 col-form-label">Keadaan Sebelumnya
                                        (Permasalahan) <span style="color: red;">*</span></label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" style="height: 100px" id="editKeadaanSebelumnya" name="keadaan_sebelumnya" required></textarea>
                                    </div>
                                </div>

                                <!-- Input File Upload 1 -->
                                <div class="row mb-3">
                                    <label for="editImage" class="col-sm-2 col-form-label">File Upload 1</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="file" id="editImage" name="edit_image"
                                            onchange="showFileName('editImage', 'editImageLabel', 'editImagePreview')">
                                        <label id="editImageLabel" class="form-label"></label>
                                        <input type="hidden" id="editImageUrl" name="edit_image_url">
                                        <img id="editImagePreview" class="image-popup" src="#" alt="Preview"
                                            style="max-width: 100px; display: none;">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="editUsulanIde" class="col-sm-2 col-form-label">Usulan Ide <span
                                            style="color: red;">*</span></label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" style="height: 100px" id="editUsulanIde" name="usulan_ide" required></textarea>
                                    </div>
                                </div>

                                <!-- Input File Upload 2 -->
                                <div class="row mb-3">
                                    <label for="editImage2" class="col-sm-2 col-form-label">File Upload 2</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="file" id="editImage2" name="edit_image_2"
                                            onchange="showFileName('editImage2', 'editImage2Label', 'editImage2Preview')">
                                        <label id="editImage2Label" class="form-label"></label>
                                        <input type="hidden" id="editImage2Url" name="edit_image_2_url">
                                        <img id="editImage2Preview" class="image-popup" src="#" alt="Preview"
                                            style="max-width: 100px; display: none;">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="editKeuntungan" class="col-sm-2 col-form-label">Keuntungan Dari Penerapan
                                        Ide</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" style="height: 100px" id="editKeuntungan" name="keuntungan_ide"></textarea>
                                    </div>
                                </div>
                                <input type="hidden" id="editSumbangSaranId" name="id">
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary"
                                        onclick="submitEditForm()">Save</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Readonly Modal Form Edit Sumbang Saran -->
            <div class="modal fade" id="viewSumbangSaranModal" tabindex="-1"
                aria-labelledby="viewSumbangSaranModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" style="max-width: 90%;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewSumbangSaranModalLabel">View Sumbang Saran</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Form View Sumbang Saran -->
                            <form id="viewSumbangSaranForm" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-3">
                                    <label for="viewTglPengajuan" class="col-sm-2 col-form-label">Tgl. pengajuan Ide <span
                                            style="color: red;">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="date" class="form-control" id="viewTglPengajuan"
                                            name="tgl_pengajuan_ide" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="viewLokasiIde" class="col-sm-2 col-form-label">Lokasi Ide <span
                                            style="color: red;">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="viewLokasiIde" name="lokasi_ide"
                                            readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="viewTglDiterapkan" class="col-sm-2 col-form-label">Tgl. Diterapkan<span
                                            style="color: red;">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="date" class="form-control" id="viewTglDiterapkan"
                                            name="tgl_diterapkan" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="viewJudulIde" class="col-sm-2 col-form-label">Judul Ide <span
                                            style="color: red;">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="viewJudulIde" name="judul"
                                            readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="viewKeadaanSebelumnya" class="col-sm-2 col-form-label">Keadaan Sebelumnya
                                        (Permasalahan) <span style="color: red;">*</span></label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" style="height: 100px" id="viewKeadaanSebelumnya" name="keadaan_sebelumnya" readonly></textarea>
                                    </div>
                                </div>

                                <!-- Input File Upload 1 -->
                                <div class="row mb-3">
                                    <label for="viewImage" class="col-sm-2 col-form-label">File Upload 1</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="file" id="viewImage" name="edit_image"
                                            readonly>
                                        <label id="viewImageLabel" class="form-label"></label>
                                        <input type="hidden" id="viewImageUrl" name="edit_image_url">
                                        <img id="viewImagePreview" class="image-popup" src="#" alt="Preview"
                                            style="max-width: 100px; display: none;">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="viewUsulanIde" class="col-sm-2 col-form-label">Usulan Ide <span
                                            style="color: red;">*</span></label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" style="height: 100px" id="viewUsulanIde" name="usulan_ide" readonly></textarea>
                                    </div>
                                </div>

                                <!-- Input File Upload 2 -->
                                <div class="row mb-3">
                                    <label for="viewImage2" class="col-sm-2 col-form-label">File Upload 2</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="file" id="viewImage2" name="edit_image_2"
                                            readonly>
                                        <label id="viewImage2Label" class="form-label"></label>
                                        <input type="hidden" id="viewImage2Url" name="edit_image_2_url">
                                        <img id="viewImage2Preview" class="image-popup" src="#" alt="Preview"
                                            style="max-width: 100px; display: none;">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="viewKeuntungan" class="col-sm-2 col-form-label">Keuntungan Dari Penerapan
                                        Ide</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" style="height: 100px" id="viewKeuntungan" name="keuntungan_ide" readonly></textarea>
                                    </div>
                                </div>
                                <input type="hidden" id="viewSumbangSaranId" name="id">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script>
            // ADD
            function submitForm() {
                // Ambil data dari formulir
                var formData = new FormData(document.getElementById('sumbangSaranForm'));

                // Kirim data menggunakan AJAX ke route simpanSS
                $.ajax({
                    url: '{{ route('simpanSS') }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Handle success
                        console.log(response);
                        // Tampilkan SweetAlert
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Data berhasil disimpan.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Tutup modal
                                $('#sumbangSaranModal').modal('hide');
                                // Bersihkan formulir
                                document.getElementById('sumbangSaranForm').reset();
                                // Redirect to showSS
                                window.location.href = '{{ route('showSS') }}';
                            }
                        });
                    }
                });
            }

            $(document).ready(function() {
                $('.image-popup').magnificPopup({
                    type: 'image',
                    closeOnContentClick: true,
                    gallery: {
                        enabled: true
                    },
                    zoom: {
                        enabled: true,
                        duration: 300,
                        easing: 'ease-in-out'
                    }
                });
            });

            function showFileName(inputId, labelId, previewId) {
                var input = document.getElementById(inputId);
                var label = document.getElementById(labelId);
                var preview = document.getElementById(previewId);

                if (input.files && input.files.length > 0) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(input.files[0]);

                    label.innerText = input.files[0].name;
                }
            }

            function openEditModal(id) {
                // Panggil endpoint untuk mendapatkan data sumbang saran berdasarkan ID
                $.ajax({
                    url: '/editSS/' + id,
                    type: 'GET',
                    success: function(response) {
                        // Isi form modal dengan data yang diperoleh
                        $('#editTglPengajuan').val(response.tgl_pengajuan_ide);
                        $('#editLokasiIde').val(response.lokasi_ide);
                        $('#editTglDiterapkan').val(response.tgl_diterapkan);
                        $('#editJudulIde').val(response.judul);
                        $('#editKeadaanSebelumnya').val(response.keadaan_sebelumnya);
                        $('#editUsulanIde').val(response.usulan_ide);
                        $('#editSumbangSaranId').val(response.id);
                        $('#editKeuntungan').val(response.keuntungan_ide);

                        // Atur label file dan input tersembunyi jika file ada
                        if (response.edit_image_url) {
                            $('#editImagePreview').attr('src', response.edit_image_url)
                                .show(); // Atur src gambar dan tampilkan
                        } else {
                            $('#editImagePreview').attr('src', '').hide(); // Kosongkan src gambar dan sembunyikan
                        }
                        if (response.edit_image_2_url) {
                            $('#editImage2Preview').attr('src', response.edit_image_2_url)
                                .show(); // Atur src gambar dan tampilkan
                        } else {
                            $('#editImage2Preview').attr('src', '').hide(); // Kosongkan src gambar dan sembunyikan
                        }

                        // Tampilkan modal
                        $('#editSumbangSaranModal').modal('show');
                    },
                    error: function(xhr) {
                        // Tangani kesalahan jika ada
                        console.log(xhr.responseText);
                    }
                });
            }

            function submitEditForm() {
                // Dapatkan data dari form
                var formData = new FormData($('#editSumbangSaranForm')[0]);

                // Kirim data form menggunakan AJAX
                $.ajax({
                    url: '/updateSS',
                    type: 'POST', // Gunakan metode POST untuk mensimulasikan PUT
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Tampilkan pesan sukses menggunakan SweetAlert
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Data berhasil diperbarui.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Tutup modal
                                $('#editSumbangSaranModal').modal('hide');
                                // Pilihan: reload halaman atau perbarui tabel untuk mencerminkan perubahan
                                window.location.href = '{{ route('showSS') }}';
                            }
                        });
                    },
                    error: function(xhr) {
                        // Tangani kesalahan jika ada
                        console.log(xhr.responseText);
                    }
                });
            }

            function confirmDelete(id) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Setelah dihapus, Anda tidak akan dapat memulihkan data ini!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/delete-ss/${id}`,
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            success: function(data) {
                                if (data.message === 'Data berhasil dihapus') {
                                    Swal.fire(
                                        'Dihapus!',
                                        'Data berhasil dihapus.',
                                        'success'
                                    ).then(() => {
                                        // Lakukan tindakan tambahan seperti menyegarkan halaman atau menghapus baris tabel
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire(
                                        'Gagal!',
                                        'Terjadi kesalahan saat menghapus data.',
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr) {
                                console.error('Error:', xhr.responseText);
                                Swal.fire(
                                    'Gagal!',
                                    'Terjadi kesalahan saat menghapus data.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            }

            function showViewSumbangSaranModal(id) {
                // Gunakan AJAX untuk mengambil data berdasarkan ID
                $.ajax({
                    url: '/getSumbangSaran/' + id, // Endpoint untuk mendapatkan data Sumbang Saran
                    type: 'GET',
                    success: function(data) {
                        // Isi form dengan data yang diambil
                        $('#viewTglPengajuan').val(data.tgl_pengajuan_ide);
                        $('#viewLokasiIde').val(data.lokasi_ide);
                        $('#viewTglDiterapkan').val(data.tgl_diterapkan);
                        $('#viewJudulIde').val(data.judul);
                        $('#viewKeadaanSebelumnya').val(data.keadaan_sebelumnya);
                        $('#viewUsulanIde').val(data.usulan_ide);
                        $('#viewKeuntungan').val(data.keuntungan_ide);
                        $('#viewImageUrl').val(data.image);
                        $('#viewImage2Url').val(data.image_2);
                        $('#viewSumbangSaranId').val(data.id);

                        // Tampilkan preview gambar
                        if (data.image) {
                            $('#viewImagePreview').attr('src', '/assets/image/' + data.image).show();
                        } else {
                            $('#viewImagePreview').hide();
                        }

                        if (data.image_2) {
                            $('#viewImage2Preview').attr('src', '/assets/image/' + data.image_2).show();
                        } else {
                            $('#viewImage2Preview').hide();
                        }

                        // Tampilkan modal
                        $('#viewSumbangSaranModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        // Tampilkan pesan kesalahan jika terjadi masalah
                        alert('Terjadi kesalahan saat mengambil data. Silakan coba lagi.');
                        console.log(xhr.responseText);
                    }
                });
            }
        </script>

    </main><!-- End #main -->
@endsection
