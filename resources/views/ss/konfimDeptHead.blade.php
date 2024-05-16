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
                                                <td class="text-center py-3">{{ $data->users->name ?? '' }}</td>
                                                <td class="text-center py-3">{{ $data->users->npk ?? '' }}</td>
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
                                                            style="font-size: 18px;">Menunggur<br>Konfirmasi Direksi</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($data->status != 4)
                                                        <button class="btn btn-primary btn-sm"
                                                            onclick="openFormPenilaian({{ $data->id }})"
                                                            data-id="{{ $data->id }}" title="Kirim">
                                                            <i class="fa-solid fa fa-check-square fa-1x"></i>
                                                        </button>
                                                    @endif
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
                <div class="modal-dialog modal-dialog-centered" style="max-width: 90%;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editSumbangSaranModalLabel">Form Edit SS</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Form Edit Sumbang Saran -->
                            <form id="editSumbangSaranForm" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label for="editTglPengajuan">Tgl. pengajuan Ide <span
                                                        style="color: red;">*</span>
                                                </label>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="date" class="form-control" id="editTglPengajuan"
                                                    name="tgl_pengajuan_ide" style="width: 100%" readonly>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label for="editLokasiIde">Lokasi Ide <span style="color: red;">*</span>
                                                </label>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" id="editLokasiIde"
                                                    name="lokasi_ide" style="width: 100%;" readonly>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label for="editTglDiterapkan">Tgl. Diterapkan<span
                                                        style="color: red;">*</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="date" class="form-control" id="editTglDiterapkan"
                                                    style="width: 100%" name="tgl_diterapkan" readonly>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label for="editJudulIde">Judul Ide <span
                                                        style="color: red;">*</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" id="editJudulIde"
                                                    name="judul" style="width: 100%" readonly>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label for="editKeadaanSebelumnya">Keadaan
                                                    Sebelumnya
                                                    (Permasalahan) <span style="color: red;">*</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <textarea class="form-control" style="height: 100px" id="editKeadaanSebelumnya" name="keadaan_sebelumnya"
                                                    style="width: 100%" readonly></textarea>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label for="editImage">File Upload 1</label>
                                            </div>
                                            <div class="col-lg-6">
                                                <label id="editImageLabel" class="form-label"></label>
                                                <input type="hidden" id="editImageUrl" name="edit_image_url">
                                                <img id="editImagePreview" class="image-popup" src="#"
                                                    alt="Preview" style="width: 100% display: none;">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label for="editUsulanIde">Usulan Ide <span
                                                        style="color: red;">*</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <textarea class="form-control" style="height: 100px" id="editUsulanIde" name="usulan_ide" style="width: 100%"
                                                    readonly></textarea>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label for="editImage2">File Upload 2</label>
                                            </div>
                                            <div class="col-lg-6">
                                                <label id="editImage2Label" class="form-label"></label>
                                                <input type="hidden" id="editImage2Url" name="edit_image_2_url">
                                                <img id="editImage2Preview" class="image-popup" src="#"
                                                    alt="Preview" style="width: 50%">
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label for="editKeuntungan">Keuntungan Dari Penerapan
                                                    Ide</label>
                                            </div>
                                            <div class="col-lg-6">
                                                <textarea class="form-control" style="height: 100px" id="editKeuntungan" name="keuntungan_ide" style="width: 100%"
                                                    readonly></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="telah_direvisi">Telah Direvisi</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-1">
                                                <input type="hidden" name="telah_direvisi" value="0">
                                                <input type="checkbox" id="telah_direvisi" name="telah_direvisi"
                                                    value="1">
                                            </div>
                                            <div class="col-lg-2">
                                                <label for="keterangan">Keterangan SS</label>
                                            </div>
                                            <div class="col-lg-5">
                                                <textarea class="form-control" style="height: 100px" id="keterangan" name="keterangan" style="width: 100%"></textarea>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="belum_diterapkan">Belum Diterapkan</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <input type="hidden" name="belum_diterapkan" value="0">
                                                <input type="checkbox" id="belum_diterapkan" name="belum_diterapkan"
                                                    value="1">
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="sedang_diterapkan">Sedang Diterapkan</label>

                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <input type="hidden" name="sedang_diterapkan" value="0">
                                                <input type="checkbox" id="sedang_diterapkan" name="sedang_diterapkan"
                                                    value="1">
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="sudah_diterapkan">Sudah Diterapkan</label>

                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <input type="hidden" name="sudah_diterapkan" value="0">
                                                <input type="checkbox" id="sudah_diterapkan" name="sudah_diterapkan"
                                                    value="1">
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="tidak_bisa_diterapkan">Tidak Bisa Diterapkan</label>

                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <input type="hidden" name="tidak_bisa_diterapkan" value="0">
                                                <input type="checkbox" id="tidak_bisa_diterapkan"
                                                    name="tidak_bisa_diterapkan" value="1">
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <!-- Ide Field -->
                                            <div class="col-lg-2">
                                                <label for="ide">Ide<span style="color: red;">*</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" id="ide" name="ide"
                                                    required style="width: 100%">
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <!-- Persiapan Field -->
                                            <div class="col-lg-2">
                                                <label for="persiapan">Persiapan<span style="color: red;">*</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" id="persiapan"
                                                    name="persiapan" required style="width: 100%">
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <!-- Penghematan Biaya Field -->
                                            <div class="col-lg-2">
                                                <label for="penghematan_biaya">Penghematan Biaya<span
                                                        style="color: red;">*</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" id="penghematan_biaya"
                                                    name="penghematan_biaya" required style="width: 100%">
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <!-- Kualitas Field -->
                                            <div class="col-lg-2">
                                                <label for="kualitas">Kualitas<span style="color: red;">*</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" id="kualitas"
                                                    name="kualitas" required style="width: 100%">
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <!-- Delivery Field -->
                                            <div class="col-lg-2">
                                                <label for="delivery">Delivery<span style="color: red;">*</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" id="delivery"
                                                    name="delivery" required style="width: 100%">
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <!-- Safety Field -->
                                            <div class="col-lg-2">
                                                <label for="safety">Safety<span style="color: red;">*</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" id="safety" name="safety"
                                                    required style="width: 100%">
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <!-- Biaya Penerapan Field -->
                                            <div class="col-lg-2">
                                                <label for="biaya_penerapan">Biaya Penerapan<span
                                                        style="color: red;">*</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" id="biaya_penerapan"
                                                    name="biaya_penerapan" required style="width: 100%">
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <!-- Usaha Field -->
                                            <div class="col-lg-2">
                                                <label for="usaha">Usaha<span style="color: red;">*</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" id="usaha" name="usaha"
                                                    required style="width: 100%">
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <!-- Pencapaian Target Field -->
                                            <div class="col-lg-2">
                                                <label for="pencapaian_target">Pencapaian Target<span
                                                        style="color: red;">*</span></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" id="pencapaian_target"
                                                    name="pencapaian_target" required style="width: 100%">
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <!-- Catatan Penilaian Field -->
                                            <div class="col-lg-2">
                                                <label for="catatan_penilaian">Catatan Penilaian</label>
                                            </div>
                                            <div class="col-lg-6">
                                                <textarea class="form-control" style="height: 100px" id="catatan_penilaian" name="catatan_penilaian"
                                                    style="width: 100%"></textarea>
                                            </div>
                                        </div>
                                        <br>
                                    </div>
                                </div>
                                <input type="hidden" id="editSumbangSaranId" name="id">
                                <input type="hidden" id="ss_id" name="ss_id">
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
            <!-- Readonly Modal Form View Sumbang Saran -->
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

            function showFileName2(inputId, labelId, previewId) {
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
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Tutup modal
                                $('#sumbangSaranModal').modal('hide');
                                // Reset formulir
                                form.reset();
                                // Redirect ke showSS
                                window.location.href = '{{ route('showKonfirmasiDeptHead') }}';
                            }
                        });
                    }
                });
            }

            function showViewSumbangSaranModal(id) {
                // Gunakan AJAX untuk mengambil data berdasarkan ID
                $.ajax({
                    url: '{{ route('getSumbangSaran', ['id' => ':id']) }}'.replace(':id',
                        id), // Endpoint untuk mendapatkan data Sumbang Saran
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
