@extends('layout')

@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Konfirmasi SS by Dept Head</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboardHandling') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Konfirmasi SS</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Table View Konfirmasi</h5>
                            <!-- Table with stripped rows -->
                            <div class="table-responsive" style="height: 100%; overflow-y: auto;">
                                <table class="datatable table">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="30px">NO</th>
                                            <th class="text-center" width="100px">Nama</th>
                                            <th class="text-center" width="100px">NPK</th>
                                            <th class="text-center" width="100px">Bagian</th>
                                            <th class="text-center" width="100px">Judul Ide</th>
                                            <th class="text-center" width="90px">Tanggal Pengajuan Ide</th>
                                            <th class="text-center" width="70px">Lokasi</th>
                                            <th class="text-center" width="100px">Tanggal Diterapkan</th>
                                            <th class="text-center" width="100px">Pembaruan Terakhir</th>
                                            <th class="text-center" width="190px">Status</th>
                                            <th class="text-center" width="100px">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $data)
                                            <tr>
                                                <th scope="row" class="text-center">{{ $loop->iteration }}</th>
                                                <td class="text-center py-3">{{ $data->user->name ?? '' }}</td>
                                                <td class="text-center py-3">{{ $data->user->npk ?? '' }}</td>
                                                <td class="text-center py-3">{{ $usersRoles[$data->id_user] ?? '' }}</td>
                                                <td class="text-center py-3">{{ $data->judul }}</td>
                                                <td class="text-center py-3">{{ $data->tgl_pengajuan_ide }}</td>
                                                <td class="text-center py-3">{{ $data->lokasi_ide }}</td>
                                                <td class="text-center py-3">{{ $data->tgl_diterapkan }}</td>
                                                <td class="text-center py-3">{{ $data->created_at }}</td>
                                                <td class="text-center py-4"
                                                    style="max-width: 70%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
                                                    title="@if ($data->status == 1) Draf @elseif ($data->status == 2) Menunggu Approve Foreman @elseif($data->status == 3) Menunggu Approve Dept. Head @elseif($data->status == 4) Direksi @endif">
                                                    @if ($data->status == 1)
                                                        <span class="badge bg-secondary align-items-center"
                                                            style="font-size: 18px;">Draf</span>
                                                    @elseif ($data->status == 2)
                                                        <span class="badge bg-info align-items-center"
                                                            style="font-size: 18px;">Menunggu<br>Konfirmasi Foreman</span>
                                                    @elseif($data->status == 3)
                                                        <span class="badge bg-info align-items-center"
                                                            style="font-size: 18px;">Menunggu<br>Konfirmasi Dept.
                                                            Head</span>
                                                    @elseif($data->status == 4)
                                                        <span class="badge bg-info align-items-center"
                                                            style="font-size: 18px;">Menunggu<br>Konfirmasi Komite</span>
                                                    @elseif($data->status == 5)
                                                        <span class="badge bg-info align-items-center"
                                                            style="font-size: 18px;">SS sudah dinilai</span>
                                                    @elseif($data->status == 6)
                                                        <span class="badge bg-info align-items-center"
                                                            style="font-size: 18px;">SS sudah Verivikasi</span>
                                                    @elseif($data->status == 7)
                                                        <span class="badge bg-success align-items-center"
                                                            style="font-size: 18px;">SS Terbayar</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($data->status != 4 && $data->status != 5)
                                                        <button class="btn btn-primary btn-sm"
                                                            onclick="openFormPenilaian({{ $data->id }})"
                                                            data-id="{{ $data->id }}" title="Kirim">
                                                            <i class="fa-solid fa fa-check-square fa-1x"></i>
                                                        </button>
                                                    @endif
                                                    <button class="btn btn-success btn-sm"
                                                        onclick="viewFormSS({{ $data->id }})"
                                                        data-id="{{ $data->id }}" title="lihat">
                                                        <i class="fa-solid fa-eye fa-1x"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- End Table with stripped rows -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- Edit Form -->
            <!-- Modal Form Edit Sumbang Saran -->
            <div class="modal fade" id="editSumbangSaranModal" tabindex="-1" aria-labelledby="editSumbangSaranModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editSumbangSaranModalLabel">Form SS</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Form Edit Sumbang Saran -->
                            <form id="editSumbangSaranForm" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="editSumbangSaranId" name="id">
                                <input type="hidden" id="ss_id" name="ss_id">

                                <div class="mb-3">
                                    <label for="telah_direvisi" class="form-label">Telah Direvisi</label>
                                    <div class="form-check">
                                        <input type="hidden" name="telah_direvisi" value="0">
                                        <input type="checkbox" class="form-check-input" id="telah_direvisi"
                                            name="telah_direvisi" value="1">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="belum_diterapkan" class="form-label">Belum Diterapkan</label>
                                    <div class="form-check">
                                        <input type="hidden" name="belum_diterapkan" value="0">
                                        <input type="checkbox" class="form-check-input" id="belum_diterapkan"
                                            name="belum_diterapkan" value="1">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="sedang_diterapkan" class="form-label">Sedang Diterapkan</label>
                                    <div class="form-check">
                                        <input type="hidden" name="sedang_diterapkan" value="0">
                                        <input type="checkbox" class="form-check-input" id="sedang_diterapkan"
                                            name="sedang_diterapkan" value="1">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="sudah_diterapkan" class="form-label">Sudah Diterapkan</label>
                                    <div class="form-check">
                                        <input type="hidden" name="sudah_diterapkan" value="0">
                                        <input type="checkbox" class="form-check-input" id="sudah_diterapkan"
                                            name="sudah_diterapkan" value="1">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="tidak_bisa_diterapkan" class="form-label">Tidak Bisa Diterapkan</label>
                                    <div class="form-check">
                                        <input type="hidden" name="tidak_bisa_diterapkan" value="0">
                                        <input type="checkbox" class="form-check-input" id="tidak_bisa_diterapkan"
                                            name="tidak_bisa_diterapkan" value="1">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="keterangan" class="form-label">Keterangan SS</label>
                                    <textarea class="form-control" id="keterangan" name="keterangan" style="height: 100px; width: 100%;"></textarea>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary"
                                        onclick="submitFormPenilaian()">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="viewSumbangSaranModal" tabindex="-1"
                aria-labelledby="viewSumbangSaranModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" style="max-width: 90%;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewSumbangSaranModalLabel">Form View SS</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Form View Sumbang Saran -->
                            <form id="viewSumbangSaranForm" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-3">
                                    <label for="editLokasiIde" class="col-sm-2 col-form-label">Nama<span
                                            style="color: red;">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="viewname" name="nama"
                                            disabled>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="editLokasiIde" class="col-sm-2 col-form-label">Npk<span
                                            style="color: red;">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="viewnpk" name="npk"
                                            disabled>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="viewTglPengajuan" class="col-sm-2 col-form-label">Tgl. pengajuan Ide <span
                                            style="color: red;">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="date" class="form-control" id="viewTglPengajuan"
                                            name="tgl_pengajuan_ide" disabled>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="viewLokasiIde" class="col-sm-2 col-form-label">Lokasi Ide <span
                                            style="color: red;">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="viewLokasiIde" name="lokasi_ide"
                                            disabled>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="viewTglDiterapkan" class="col-sm-2 col-form-label">Tgl. Diterapkan<span
                                            style="color: red;">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="date" class="form-control" id="viewTglDiterapkan"
                                            name="tgl_diterapkan" disabled>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="viewJudulIde" class="col-sm-2 col-form-label">Judul Ide <span
                                            style="color: red;">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="viewJudulIde" name="judul"
                                            disabled>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="viewKeadaanSebelumnya" class="col-sm-2 col-form-label">Keadaan Sebelumnya
                                        (Permasalahan) <span style="color: red;">*</span></label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" style="height: 100px" id="viewKeadaanSebelumnya" name="keadaan_sebelumnya" disabled></textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="viewImage" class="col-sm-2 col-form-label">File Upload
                                        (Sebelumnya)</label>
                                    <div class="col-sm-10">
                                        <div id="view-image-preview" style="margin-top: 10px;"></div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="viewUsulanIde" class="col-sm-2 col-form-label">Usulan Ide <span
                                            style="color: red;">*</span></label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" style="height: 100px" id="viewUsulanIde" name="usulan_ide" disabled></textarea>
                                    </div>
                                </div>
                                <!-- Input File Upload 2 -->
                                <div class="row mb-3">
                                    <label for="viewImage2" class="col-sm-2 col-form-label">File Upload (Sesudah)</label>
                                    <div class="col-sm-10">
                                        <div id="view-image2-preview" style="margin-top: 10px;"></div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="viewKeuntungan" class="col-sm-2 col-form-label">Keuntungan Dari Penerapan
                                        Ide</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" style="height: 100px" id="viewKeuntungan" name="keuntungan_ide" disabled></textarea>
                                    </div>
                                </div>
                                <input type="hidden" id="viewSumbangSaranId" name="id">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Gambar -->
            <div class="modal fade" id="viewImageModal" tabindex="-1" aria-labelledby="viewImageModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewImageModalLabel">Gambar</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img id="viewModalImage" src="" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script>
            function openFormPenilaian(id) {
                // Panggil endpoint untuk mendapatkan data sumbang saran berdasarkan ID
                $.ajax({
                    url: '{{ route('getPenilaians', ['id' => ':id']) }}'.replace(':id', id),
                    type: 'GET',
                    success: function(response) {
                        var sumbangSaran = response.sumbangSaran; // Objek sumbangSaran dari respons
                        $('#editTglPengajuan').val(sumbangSaran.tgl_pengajuan_ide);
                        $('#editLokasiIde').val(sumbangSaran.lokasi_ide);
                        $('#editTglDiterapkan').val(sumbangSaran.tgl_diterapkan);
                        $('#editJudulIde').val(sumbangSaran.judul);
                        $('#editKeadaanSebelumnya').val(sumbangSaran.keadaan_sebelumnya);
                        $('#editUsulanIde').val(sumbangSaran.usulan_ide);
                        $('#editSumbangSaranId').val(sumbangSaran.id);
                        $('#editKeuntungan').val(sumbangSaran.keuntungan_ide);

                        // Set nilai ss_id ke input tersembunyi dalam modal
                        $('#ss_id').val(sumbangSaran.id);

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

            function submitFormPenilaian() {
                // Ambil elemen formulir
                var form = document.getElementById('editSumbangSaranForm');

                // Periksa field yang required
                var requiredFields = form.querySelectorAll('[required]');
                var valid = true;
                var firstInvalidField = null;
                requiredFields.forEach(function(field) {
                    if (!field.value.trim()) {
                        valid = false;
                        if (!firstInvalidField) {
                            firstInvalidField = field;
                        }
                    }
                });

                if (!valid) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Harap isi semua field yang wajib diisi.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        if (firstInvalidField) {
                            firstInvalidField.focus();
                        }
                    });
                    return;
                }

                // Buat objek formData
                var formData = new FormData(form);

                // Kirim permintaan AJAX
                $.ajax({
                    url: '{{ route('simpanPenilaian') }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Handle sukses
                        console.log(response);
                        // Tampilkan alert sukses
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Data berhasil disimpan.',
                            icon: 'success',
                            timer: 1000, // Waktu dalam milidetik sebelum alert otomatis tertutup
                            showConfirmButton: false
                        }).then(() => {
                            // Tutup modal
                            $('#sumbangSaranModal').modal('hide');
                            // Reset formulir
                            form.reset();
                            // Redirect ke showSS
                            window.location.href = '{{ route('showKonfirmasiDeptHead') }}';
                        });
                    }
                });
            }

            //viewmodal
            function viewFormSS(id) {
                $.ajax({
                    url: '{{ route('sechead.show', ':id') }}'.replace(':id', id),
                    type: 'GET',
                    success: function(response) {
                        console.log(response); // Tambahkan ini untuk debug
                        if (response) {
                            $('#viewname').val(response.user.name);
                            $('#viewnpk').val(response.user.npk);
                            $('#viewTglPengajuan').val(response.tgl_pengajuan_ide);
                            $('#viewLokasiIde').val(response.lokasi_ide);
                            $('#viewTglDiterapkan').val(response.tgl_diterapkan);
                            $('#viewJudulIde').val(response.judul);
                            $('#viewKeadaanSebelumnya').val(response.keadaan_sebelumnya);
                            $('#viewUsulanIde').val(response.usulan_ide);
                            $('#viewKeuntungan').val(response.keuntungan_ide);
                            $('#viewSumbangSaranId').val(response.id);

                            if (response.file_name && response.image) {
                                var fileExtension1 = response.file_name.split('.').pop().toLowerCase();
                                var fileLink1 = '{{ asset('assets/image/') }}/' + response.image;
                                if (['jpg', 'jpeg', 'png'].includes(fileExtension1)) {
                                    $('#view-image-preview').html('<img src="' + fileLink1 +
                                        '" class="img-fluid rounded clickable-view-image" style="max-width: 200px; height: auto;" data-bs-toggle="modal" data-bs-target="#viewImageModal" data-img-src="' +
                                        fileLink1 + '">');
                                } else {
                                    $('#view-image-preview').html('<a href="' + fileLink1 + '" download="' +
                                        response.file_name + '">' + response.file_name + '</a>');
                                }
                            } else {
                                $('#view-image-preview').html('');
                            }

                            if (response.file_name_2 && response.image_2) {
                                var fileExtension2 = response.file_name_2.split('.').pop().toLowerCase();
                                var fileLink2 = '{{ asset('assets/image/') }}/' + response.image_2;
                                if (['jpg', 'jpeg', 'png'].includes(fileExtension2)) {
                                    $('#view-image2-preview').html('<img src="' + fileLink2 +
                                        '" class="img-fluid rounded clickable-view-image" style="max-width: 200px; height: auto;" data-bs-toggle="modal" data-bs-target="#viewImageModal" data-img-src="' +
                                        fileLink2 + '">');
                                } else {
                                    $('#view-image2-preview').html('<a href="' + fileLink2 + '" download="' +
                                        response.file_name_2 + '">' + response.file_name_2 + '</a>');
                                }
                            } else {
                                $('#view-image2-preview').html('');
                            }

                            $('#viewSumbangSaranModal').modal('show');
                        } else {
                            console.error('No response data');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }

            // Event listener untuk gambar yang diklik
            $(document).on('click', '.clickable-view-image', function() {
                var imgSrc = $(this).data('img-src');
                $('#viewModalImage').attr('src', imgSrc);
                $('#viewImageModal').modal('show');
            });
        </script>

    </main><!-- End #main -->
@endsection
