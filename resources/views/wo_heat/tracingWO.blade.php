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
                    <li class="breadcrumb-item active">Tracing WO</li>
                </ol>
            </nav>

        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-center">Trace No. WO</h5>

                            <!-- No. WO Search Textbox -->
                            <div class="form-group">
                                <label for="searchWO">No. WO</label>
                                <input type="text" class="form-control" id="searchWO" placeholder="Search...">
                            </div>

                            <!-- Table 1 (Trace No.WO) -->
                            <div class="table-responsive">
                                <div class="row">
                                    <div class="col">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Customer</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Data will be populated here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Tgl. WO</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Data will be populated here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Status WO</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Data will be populated here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Status DO</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Data will be populated here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Proses</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Data will be populated here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Detail Status Proses -->
                            <h5 class="card-title text-center">Detail Status Proses</h5>

                            <!-- Table 2 (Detail Status Proses) -->
                            <div class="table-responsive">
                                <div class="row">
                                    <div class="col">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Batch</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Data will be populated here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Mesin</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Data will be populated here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Tanggal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Data will be populated here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Detail Status DO -->
                            <h5 class="card-title text-center">Detail Status DO</h5>

                            <!-- Table 3 (Detail Status DO) -->
                            <div class="table-responsive">
                                <div class="row">
                                    <div class="col">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">No. DO</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Data will be populated here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Tgl. ST</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Data will be populated here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Pengirim</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Data will be populated here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Tgl. Terima</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Data will be populated here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <table class="table">
                                            <thead>
                                                <tr>
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
                </div>
            </div>
        </section>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-center">Detail Proses</h5>

                            <!-- Mesin (Search Textbox Readonly) -->
                            <div class="form-group row">
                                <label for="searchMesin" class="col-sm-2 col-form-label">Mesin</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="searchMesin" placeholder="Search..."
                                        readonly>
                                </div>
                            </div>

                            <!-- Tabel -->
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">WO</th>
                                            <th scope="col">Nama Customer</th>
                                            <th scope="col">PCS</th>
                                            <th scope="col">Tonase (QTY)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="border-right: 1px solid #dee2e6;">
                                                <ul style="list-style: none; padding-left: 0;">
                                                    <li style="border-bottom: 1px solid #dee2e6;">Tgl.WO:</li>
                                                    <li style="border-bottom: 1px solid #dee2e6;">Status WO:</li>
                                                    <li style="border-bottom: 1px solid #dee2e6;">Status DO:</li>
                                                    <li>Proses:</li>
                                                </ul>
                                            </td>
                                            <td style="border-right: 1px solid #dee2e6;"></td>
                                            <td style="border-right: 1px solid #dee2e6;"></td>
                                            <td></td>
                                        </tr>
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
