@extends('layout')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.20.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css">


    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Karyawan</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">WO Heat Treatment</li>
                    <li class="breadcrumb-item active">Import WO</li>
                </ol>
            </nav>

        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-center">Import WO Heat Treatment</h5>

                            <button class="btn btn-danger" style="display: inline-block; margin-left: 10px;">
                                <i class="bi bi-upload"></i></i> Import Data
                            </button>


                            <!-- Table -->
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Customer</th>
                                            <th scope="col">Tgl. WO</th>
                                            <th scope="col">Status WO</th>
                                            <th scope="col">Status DO</th>
                                            <th scope="col">Proses</th>
                                            <th scope="col">Batch</th>
                                            <th scope="col">Mesin</th>
                                            <th scope="col">Tanggal</th>
                                            <th scope="col">No. DO</th>
                                            <th scope="col">Tgl. ST</th>
                                            <th scope="col">Pengirim</th>
                                            <th scope="col">Tgl. Terima</th>
                                            <th scope="col">Penerima</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data will be populated here -->
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>







    </main><!-- End #main -->
@endsection
