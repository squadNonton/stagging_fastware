@extends('layout')

@section('content')
    <main id="main" class="main">
        <style>
            /* Profil pengguna */
            .user-profile {
                display: flex;
                align-items: center;
                margin-bottom: 15px;
                width: 100%;
            }

            .profile-icon {
                font-size: 50px;
                color: #555;
                margin-right: 15px;
            }

            .user-details {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
            }

            .user-name,
            .user-job {
                font-weight: bold;
                font-size: 14px;
                margin: 2px 0;
            }

            /* Container for the radar charts */
            #radarChartContainer {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
                gap: 20px;
                /* Tambahkan gap untuk spasi antar card */
                margin-top: 20px;
            }

            /* Each card containing a radar chart */
            .chart-card {
                background-color: #fff;
                border: 1px solid #000000;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                padding: 15px;
                width: calc(33.33% - 20px);
                /* Kalkulasi lebar agar 3 card muat dalam 1 baris */
                box-sizing: border-box;
            }

            /* Card title */
            .card-title {
                font-size: 18px;
                font-weight: bold;
                margin-bottom: 10px;
                text-align: center;
            }

            /* Adjustments for the canvas to ensure it fits within the card */
            .chart-card canvas {
                width: 100% !important;
                height: auto !important;
                display: block;
                margin: 0 auto;
            }

            /* Responsive adjustments for smaller screens */
            @media (max-width: 992px) {
                .chart-card {
                    width: calc(50% - 20px);
                    /* Setengah lebar jika ruang terbatas */
                }
            }

            @media (max-width: 768px) {
                .chart-card {
                    width: 100%;
                    /* Full width untuk layar kecil */
                    max-width: 100%;
                }
            }

            /* tabel */
            .card {
                border: 1px solid #ccc;
                border-radius: 5px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                background-color: #f9f9f9;
            }

            .card-header {
                background-color: #007bff;
                color: white;
                padding: 10px;
                font-weight: bold;
                text-align: center;
                border-bottom: 2px solid #ccc;
                border-radius: 5px 5px 0 0;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th {
                background-color: #f1f1f1;
                font-weight: bold;
                text-align: center;
                border: 1px solid #ccc;
            }

            th[rowspan="2"] {
                vertical-align: middle;
            }

            td,
            th {
                padding: 10px;
                border: 1px solid #ccc;
                white-space: nowrap;
            }

            tr:nth-child(even) {
                background-color: #f9f9f9;
            }

            tr:hover {
                background-color: #f1f1f1;
            }

            input[type="text"] {
                width: 100%;
                padding: 5px;
                border: 1px solid #ccc;
                border-radius: 3px;
                box-sizing: border-box;
            }

            @media (max-width: 768px) {

                th,
                td {
                    font-size: 12px;
                    padding: 5px;
                }
            }

            th,
            td {
                text-align: center;
                vertical-align: middle;
            }

            th[rowspan="2"] {
                vertical-align: middle;
            }

            /* Optional: Center the text inside input fields as well */
            input[type="text"] {
                text-align: center;
            }
        </style>
        <div class="pagetitle">
            <h1>Profile</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('forumSS') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Data Diri</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section profile">
            <div class="row">
                <div class="col-xl-4">

                    <div class="card">
                        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                            @if ($user->file_name)
                                <img src="{{ asset('assets/data_diri/' . $user->file_name) }}" alt="Profile"
                                    class="rounded-circle" style="width: 150px; height: 150px; cursor: pointer;"
                                    data-bs-toggle="modal" data-bs-target="#imageModal">
                            @else
                                <img src="{{ asset('assets/img/user.png') }}" alt="Default Profile" class="rounded-circle"
                                    style="width: 50px; height: 50px; cursor: pointer;" data-bs-toggle="modal"
                                    data-bs-target="#imageModal">
                            @endif


                            <h2>{{ Auth::user()->name }}</h2>
                            <h3>
                                @foreach ($jobPositions as $position)
                                    <div>{{ $position->job_position }}</div>
                                @endforeach
                            </h3>
                        </div>
                    </div>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body">
                                <img src="" id="modalImage" style="width: 100%;">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8">

                    <div class="card">
                        <div class="card-body pt-3">
                            <!-- Bordered Tabs -->
                            <ul class="nav nav-tabs nav-tabs-bordered">

                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab"
                                        data-bs-target="#profile-overview">Data Diri</button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Data
                                        Diri</button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#profile-change-password">Ubah Password</button>
                                </li>

                            </ul>
                            <div class="tab-content pt-2">
                                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                                    <h5 class="card-title">Data Diri</h5>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label ">Nama Lengkap</div>
                                        <div class="col-lg-9 col-md-8">{{ $user->name }}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label ">Section</div>
                                        <div class="col-lg-9 col-md-8">{{ $user->section }}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Posisi</div>
                                        <div class="col-lg-9 col-md-8">
                                            @foreach ($jobPositions as $position)
                                                <div>{{ $position->job_position }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">NPK</div>
                                        <div class="col-lg-9 col-md-8">{{ $user->npk }}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">No. Telp</div>
                                        <div class="col-lg-9 col-md-8">{{ $user->telp }}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Email</div>
                                        <div class="col-lg-9 col-md-8">{{ $user->email }}</div>
                                    </div>
                                </div>
                                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
                                    <!-- Profile Edit Form -->
                                    <form id="ubahDataDiriForm" action="{{ route('ubahDataDiri') }}" method="post"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row mb-3">
                                            <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Nama
                                                lengkap</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="name" type="text" class="form-control" id="name"
                                                    value="{{ $user->name }}" disabled>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="about" class="col-md-4 col-lg-3 col-form-label">Section</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="name" type="text" class="form-control" id="name"
                                                    value="{{ $user->section }}" disabled>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="job_positions"
                                                class="col-md-4 col-lg-3 col-form-label">Posisi</label>
                                            <div class="col-md-8 col-lg-9">
                                                @foreach ($jobPositions as $position)
                                                    <input name="job_position" type="text" class="form-control mb-2"
                                                        value="{{ $position->job_position }}" disabled>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="company" class="col-md-4 col-lg-3 col-form-label">NPK</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="npk" type="text" class="form-control" id="company"
                                                    value="{{ $user->npk }}" disabled>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="Job" class="col-md-4 col-lg-3 col-form-label">No.
                                                Telp</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="telp" type="text" class="form-control" id="telp"
                                                    value="{{ $user->telp }}">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="Country" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="email" type="text" class="form-control" id="email"
                                                    value="{{ $user->email }}">
                                            </div>
                                        </div>

                                        @php
                                            // Mendapatkan role_id dari pengguna yang sedang login
                                            $roleId = auth()->user()->role_id;
                                        @endphp

                                        @if (in_array($roleId, [1, 14, 15]))
                                            <div class="row mb-3">
                                                <label for="file" class="col-md-4 col-lg-3 col-form-label">Upload
                                                    Image</label>
                                                <div class="col-md-8 col-lg-9">
                                                    <input type="file" name="file" class="form-control"
                                                        id="file"
                                                        accept="image/png, image/jpeg, image/jpg, image/gif">
                                                </div>
                                            </div>
                                        @endif

                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary"
                                                onclick="submitDataDiri()">Save</button>
                                        </div>
                                    </form><!-- End Profile Edit Form -->
                                </div>
                                <div class="tab-pane fade pt-3" id="profile-change-password">
                                    <!-- Change Password Form -->
                                    <form action="{{ route('ubahPassword') }}" method="post">
                                        @csrf
                                        <div class="row mb-3">
                                            <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Password
                                                Anda Saat Ini</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="currentPassword" type="password" class="form-control"
                                                    id="currentPassword" required>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">Password
                                                Baru</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="newPassword" type="password" class="form-control"
                                                    id="newPassword" required>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="newPassword_confirmation"
                                                class="col-md-4 col-lg-3 col-form-label">Konfirmasi Password Baru</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="newPassword_confirmation" type="password"
                                                    class="form-control" id="newPassword_confirmation" required>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary"
                                                onclick="return validatePasswordChange();">Ubah Password</button>
                                        </div>
                                    </form><!-- End Change Password Form -->

                                </div>
                            </div><!-- End Bordered Tabs -->
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card mt-3">
                        <div class="card-header">
                            <div style="justify-content: space-between; align-items: center;">

                                <h4><b>Histori Development</b></h4>
                            </div>

                            <div
                                style="display: flex; justify-content: space-between; align-items: center; gap: 50px; margin-top: 2%;">

                                <!-- Input field for search -->
                                <input type="text" id="searchInput" placeholder="Cari Data Disemua Kolom..."
                                    style="padding: 5px; width: 250px;">
                            </div>

                        </div>

                        <div style="overflow-x: auto; white-space: nowrap;">
                            <table border="1" cellpadding="5" cellspacing="0" class="table table-bordered mt-3">
                                <thead>
                                    <tr>
                                        <th>Nama Employee</th>
                                        <th>Nama Program</th>
                                        <th>Kategori Competency</th>
                                        <th>Periode Actual</th>
                                        <th>File Download</th>
                                    </tr>
                                </thead>
                                <tbody id="peopleDevTabel">
                                    <!-- Initially populated from server-side -->
                                    @if ($dataTcPeopleDevelopment->isEmpty())
                                        <tr>
                                            <td colspan="6" style="text-align: center;">Tidak ada data tersedia</td>
                                        </tr>
                                    @else
                                        @foreach ($dataTcPeopleDevelopment as $data)
                                            <tr>
                                                <td>{{ $data->user->name }}</td>
                                                <td>{{ $data->program_training_plan }}</td>
                                                <td>{{ $data->kategori_competency }}</td>
                                                <td>{{ $data->due_date_plan }}</td>
                                                <td>
                                                    <button onclick="downloadPdf({{ $data->id }})"
                                                        style="cursor: pointer; background-color: transparent; border: none; color: blue; text-decoration: underline;">
                                                        <i class="bi bi-download"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            function validatePasswordChange() {
                var currentPassword = document.getElementById('currentPassword').value;
                var newPassword = document.getElementById('newPassword').value;
                var confirmPassword = document.getElementById('newPassword_confirmation').value;
                var currentPasswordStored = "{{ Auth::user()->pass }}"; // Password awal yang disimpan di database

                if (!currentPassword || !newPassword || !confirmPassword) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Mohon isi semua field dengan benar!'
                    });
                    return false;
                }

                if (currentPassword !== currentPasswordStored) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Password anda saat ini tidak sesuai!'
                    });
                    return false;
                }

                if (newPassword.length < 8) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Password baru harus memiliki minimal 8 karakter!'
                    });
                    return false;
                }

                if (newPassword === currentPassword) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Password baru harus berbeda dengan password Anda saat ini!'
                    });
                    return false;
                }

                if (newPassword !== confirmPassword) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Konfirmasi password baru tidak sesuai dengan password baru!'
                    });
                    return false;
                }

                // Jika semua validasi berhasil
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses!',
                    text: 'Password berhasil diubah.',
                    showConfirmButton: false // Hilangkan tombol OK
                });
                return true;
            }


            function submitDataDiri() {
                // Submit form menggunakan JavaScript
                document.getElementById('ubahDataDiriForm').submit();

                // Tampilkan SweetAlert setelah form disubmit
                Swal.fire({
                    title: 'Success!',
                    text: 'Data berhasil disimpan.',
                    icon: 'success',
                    showConfirmButton: false
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                var images = document.querySelectorAll('img[data-bs-toggle="modal"]');
                var modalImage = document.getElementById('modalImage');

                images.forEach(function(img) {
                    img.addEventListener('click', function() {
                        modalImage.src = this.src;
                    });
                });

                var imageModal = new bootstrap.Modal(document.getElementById('imageModal'));

                images.forEach(function(img) {
                    img.addEventListener('click', function() {
                        modalImage.src = this.src;
                        imageModal.show();
                    });
                });
            });

            function downloadPdf(id) {
                var downloadPdfUrl = "{{ route('download.pdf', ['id' => ':id']) }}";
                var url = downloadPdfUrl.replace(':id', id);
                window.location.href = url; // Redirect to the download URL
            }

            // Tambahkan event listener untuk fitur pencarian
            document.getElementById('searchInput').addEventListener('input', function() {
                var searchTerm = this.value.toLowerCase();
                var tableRows = document.querySelectorAll('#peopleDevTabel tr');

                tableRows.forEach(function(row) {
                    var rowText = row.textContent.toLowerCase();
                    if (rowText.indexOf(searchTerm) > -1) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        </script>
    </main><!-- End #main -->
@endsection
