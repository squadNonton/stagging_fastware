@extends('layout')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Super Admin</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Dashboard Admin</li>
                    <li class="breadcrumb-item">Ubah Data Pengguna</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Ubah Data Pengguna</h5>

                                <form id="userForm" action="{{ route('users.update', $user->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label for="role_id" class="form-label">Role Pengguna<span
                                                style="color: red;">*</span></label>
                                        <select class="form-control" id="role_id" name="role_id">
                                            <option value="">Pilih Role</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}"
                                                    {{ $user->role_id == $role->id ? 'selected' : '' }}>{{ $role->role }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nama Pengguna<span
                                                style="color: red;">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ $user->name }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Section<span
                                                style="color: red;">*</span></label>
                                        <input type="text" class="form-control" id="section" name="section"
                                            value="{{ $user->section }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="npk" class="form-label">NPK<span
                                                style="color: red;">*</span></label>
                                        <input type="text" class="form-control" id="npk" name="npk"
                                            value="{{ $user->npk }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username<span
                                                style="color: red;">*</span></label>
                                        <input type="text" class="form-control" id="username" name="username"
                                            value="{{ $user->username }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="pass" class="form-label">Password<span
                                                style="color: red;">*</span></label>
                                        <input type="text" class="form-control" id="pass" name="pass"
                                            value="{{ $user->pass }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email<span
                                                style="color: red;">*</span></label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ $user->email }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="telp" class="form-label">Nomor Telepon<span
                                                style="color: red;">*</span></label>
                                        <input type="number" class="form-control" id="telp" name="telp"
                                            value="{{ $user->telp }}">
                                    </div>

                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <a href="{{ route('users.index') }}" class="btn btn-danger">Cancel</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <script>
            function resetForm() {
                document.getElementById('userForm').reset();
            }
        </script>
        <script>
            function handleFormSubmission() {
                // Mendapatkan nilai input dari formulir
                var role_id = document.getElementById('role_id').value.trim();
                var name = document.getElementById('name').value.trim();
                var username = document.getElementById('username').value.trim();
                var pass = document.getElementById('pass').value.trim();
                var email = document.getElementById('email').value.trim();
                var telp = document.getElementById('telp').value.trim();

                // Melakukan validasi input
                if (role_id === '' || name === '' || username === '' || pass === '' || email === '' || telp === '') {
                    // Menampilkan SweetAlert jika ada isian yang kosong
                    Swal.fire({
                        title: 'Peringatan!',
                        text: 'Harap lengkapi semua isian yang diperlukan.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return false; // Menghentikan pengiriman formulir karena ada isian yang kosong
                }

                // Jika semua isian sudah terisi, lanjutkan pengiriman formulir
                document.getElementById('userForm').submit();
            }

            // Event listener untuk submit form
            document.getElementById('userForm').addEventListener('submit', function(event) {
                event.preventDefault(); // Mencegah pengiriman formulir secara default

                // Memanggil fungsi untuk menangani pengiriman formulir dan menampilkan SweetAlert
                handleFormSubmission();
            });
        </script>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



    </main><!-- End #main -->
@endsection
