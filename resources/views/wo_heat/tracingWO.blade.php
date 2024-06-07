@extends('layout')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.20.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0"></script>
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
                            <h5 class="card-title text-center">Trace By Customer</h5>

                            <!-- No. WO Search Textbox -->
                            <div class="form-group d-flex justify-content-between">
                                <div class="p-2 flex-fill">
                                    <label for="searchWO">Nama Customer</label>
                                    <input type="text" class="form-control" id="searchWO"
                                        placeholder="Cari Nama Customer....">
                                </div>
                                <div class="p-2 flex-fill">
                                    <label for="searchStatusWO">Status WO</label>
                                    <input type="text" class="form-control" id="searchStatusWO"
                                        placeholder="Cari Status WO....">
                                </div>
                                <div class="p-2 flex-fill">
                                    <label for="searchStatusDO">Status DO</label>
                                    <input type="text" class="form-control" id="searchStatusDO"
                                        placeholder="Cari Status DO....">
                                </div>
                            </div>


                            <br>

                            <div id="tableContainer"></div> <!-- Kontainer untuk tabel -->

                            <hr>

                            <!-- Detail Status Proses -->
                            <h5 class="card-title text-center">Detail Status Proses</h5>

                            <!-- Table 2 (Detail Status Proses) -->
                            <div class="table-responsive">
                                <table class="table" id="table2">
                                    <thead>
                                        <tr>
                                            <th scope="col"></th>
                                            <th scope="col">HEATING</th>
                                            <th scope="col">TEMPER 1</th>
                                            <th scope="col">TEMPER 2</th>
                                            <th scope="col">TEMPER 3</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Batch</td>
                                            <td id="batchHeating" class="clickable-cell" data-type="heating"></td>
                                            <td id="batchTemper1" class="clickable-cell" data-type="temper1"></td>
                                            <td id="batchTemper2" class="clickable-cell" data-type="temper2"></td>
                                            <td id="batchTemper3" class="clickable-cell" data-type="temper3"></td>
                                        </tr>
                                        <tr>
                                            <td>Mesin</td>
                                            <td id="mesinHeating"></td>
                                            <td id="mesinTemper1"></td>
                                            <td id="mesinTemper2"></td>
                                            <td id="mesinTemper3"></td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal</td>
                                            <td id="tanggalHeating"></td>
                                            <td id="tanggalTemper1"></td>
                                            <td id="tanggalTemper2"></td>
                                            <td id="tanggalTemper3"></td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                            <hr>

                            <!-- Detail Status DO -->
                            <h5 class="card-title text-center">Detail Status DO</h5>

                            <!-- Table 3 (Detail Status DO) -->
                            <div class="table-responsive">
                                <table class="table" id="table3">
                                    <thead>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>No. DO</td>
                                            <td id="detailNoDO"></td>
                                        </tr>
                                        <tr>
                                            <td>Tgl. ST</td>
                                            <td id="detailTglST"></td>
                                        </tr>
                                        <tr>
                                            <td>Pengirim</td>
                                            <td id="detailSupir"></td>
                                        </tr>
                                        <tr>
                                            <td>Tgl. Terima</td>
                                            <td id="detailTglTerima"></td>
                                        </tr>
                                        <tr>
                                            <td>Penerima</td>
                                            <td id="detailPenerima"></td>
                                        </tr>
                                    </tbody>
                                </table>
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
                            {{-- <div class="form-group row">
                                <label for="searchMesin" class="col-sm-2 col-form-label">Mesin</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="searchMesin" placeholder="Search..."
                                        readonly>
                                </div>
                            </div> --}}
                            <br>
                            <!-- Tabel -->
                            <div class="table-responsive">
                                <table class="table" id="detailProsesTable">
                                    <thead>
                                        <tr>
                                            <th scope="col">No.</th>
                                            <th scope="col">WO</th>
                                            <th scope="col">Nama Customer</th>
                                            <th scope="col">Mesin</th>
                                            <th scope="col">PCS</th>
                                            <th scope="col">Tonase (QTY)</th>
                                            <th scope="col">Tgl. WO</th>
                                            <th scope="col">Status WO</th>
                                            <th scope="col">Status DO</th>
                                            <th scope="col">Proses</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div id="detailWO"></div>
                                            </td>
                                            <td>
                                                <div id="detailCustomer"></div>
                                            </td>
                                            <td>
                                                <div id="detailMesin"></div>
                                            </td>
                                            <td>
                                                <div id="detailPCS"></div>
                                            </td>
                                            <td>
                                                <div id="detailTonase"></div>
                                            </td>
                                            <td>
                                                <div id="detailTglWO"></div>
                                            </td>
                                            <td>
                                                <div id="detailStatusWO"></div>
                                            </td>
                                            <td>
                                                <div id="detailStatusDO"></div>
                                            </td>
                                            <td>
                                                <div id="detailProses"></div>
                                            </td>
                                        </tr>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <script>
            var globalData = {};

            $(document).ready(function() {
                $('#searchWO').on('keyup', function() {
                    var searchWO = $(this).val();

                    $.ajax({
                        url: '{{ route('searchWO') }}',
                        type: 'GET',
                        data: {
                            'searchWO': searchWO
                        },
                        success: function(data) {
                            globalData = data;
                            populateTables(data);
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                    });
                });

                $('#searchStatusWO').on('keyup', function() {
                    var searchStatusWO = $(this).val();

                    $.ajax({
                        url: '{{ route('searchWO') }}',
                        type: 'GET',
                        data: {
                            'searchStatusWO': searchStatusWO
                        },
                        success: function(data) {
                            globalData = data;
                            populateTables(data);
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                    });
                });

                $('#searchStatusDO').on('keyup', function() {
                    var searchStatusDO = $(this).val();

                    $.ajax({
                        url: '{{ route('searchWO') }}',
                        type: 'GET',
                        data: {
                            'searchStatusDO': searchStatusDO
                        },
                        success: function(data) {
                            globalData = data;
                            populateTables(data);
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                    });
                });

                function populateTables() {
                    var searchWO = $('#searchWO').val();
                    var searchStatusWO = $('#searchStatusWO').val();
                    var searchStatusDO = $('#searchStatusDO').val();

                    $.ajax({
                        url: '{{ route('searchWO') }}',
                        type: 'GET',
                        data: {
                            'searchWO': searchWO,
                            'searchStatusWO': searchStatusWO,
                            'searchStatusDO': searchStatusDO
                        },
                        success: function(data) {
                            globalData = data;
                            populateTable1(data);
                            if (data.length > 0) {
                                populateTable2(data[0]);
                                populateTable3(data[0]);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                    });
                }

                $('#searchWO, #searchStatusWO, #searchStatusDO').on('keyup', function() {
                    populateTables();
                });



                function populateTable1(data) {
                    $('#tableContainer').empty();

                    let tableHtml = `
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No. WO</th>
                                        <th>Customer</th>
                                        <th>Tgl. WO</th>
                                        <th>Status WO</th>
                                        <th>Status DO</th>
                                        <th>Proses</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                </tbody>
                            </table>
                        </div>
                    `;

                    $('#tableContainer').append(tableHtml);

                    let $tableBody = $('#tableBody');

                    $.each(data, function(customer, workOrders) {
                        workOrders.forEach(wo => {
                            let rowHtml = `
                                    <tr data-no-wo="${wo.no_wo}">
                                        <td class="no-wo clickable-cell">${wo.no_wo}</td>
                                        <td>${wo.cust}</td>
                                        <td>${wo.tgl_wo}</td>
                                        <td>${wo.status_wo}</td>
                                        <td>${wo.status_do}</td>
                                        <td>${wo.proses}</td>
                                    </tr>
                                `;
                            $tableBody.append(rowHtml);

                        });
                    });

                    $('#tableBody').on('click', '.clickable-cell', function() {
                        let no_wo = $(this).text();
                        let selectedWO = null;

                        $.each(globalData, function(customer, workOrders) {
                            workOrders.forEach(wo => {
                                if (wo.no_wo === no_wo) {
                                    selectedWO = wo;
                                    return false;
                                }
                            });
                            if (selectedWO) {
                                return false;
                            }
                        });

                        console.log('Selected WO:', selectedWO); // tambahkan log di sini

                        if (selectedWO) {
                            populateTable2(selectedWO);
                            populateTable3(selectedWO);
                        }
                    });

                }

                function populateTable2(data) {
                    // Membersihkan konten sebelumnya
                    $('#batchHeating').text('');
                    $('#mesinHeating').text('');
                    $('#tanggalHeating').text('');
                    $('#batchTemper1').text('');
                    $('#mesinTemper1').text('');
                    $('#tanggalTemper1').text('');
                    $('#batchTemper2').text('');
                    $('#mesinTemper2').text('');
                    $('#tanggalTemper2').text('');
                    $('#batchTemper3').text('');
                    $('#mesinTemper3').text('');
                    $('#tanggalTemper3').text('');

                    // Menambahkan detail proses baru
                    $('#batchHeating').text(data.batch || '');
                    $('#mesinHeating').text(data.mesin_heating || '');
                    $('#tanggalHeating').text(data.tgl_heating || '');
                    $('#batchTemper1').text(data.batch_temper1 || '');
                    $('#mesinTemper1').text(data.mesin_temper1 || '');
                    $('#tanggalTemper1').text(data.tgl_temper1 || '');
                    $('#batchTemper2').text(data.batch_temper2 || '');
                    $('#mesinTemper2').text(data.mesin_temper2 || '');
                    $('#tanggalTemper2').text(data.tgl_temper2 || '');
                    $('#batchTemper3').text(data.batch_temper3 || '');
                    $('#mesinTemper3').text(data.mesin_temper3 || '');
                    $('#tanggalTemper3').text(data.tgl_temper3 || '');
                }

                function populateTable3(data) {
                    // Membersihkan konten sebelumnya
                    $('#detailNoDO').text('');
                    $('#detailTglST').text('');
                    $('#detailSupir').text('');
                    $('#detailPenerima').text('');
                    $('#detailTglTerima').text('');

                    // Menambahkan detail proses baru
                    $('#detailNoDO').text(data.no_do || '');
                    $('#detailTglST').text(data.tgl_st || '');
                    $('#detailSupir').text(data.supir || '');
                    $('#detailPenerima').text(data.penerima || '');
                    $('#detailTglTerima').text(data.tgl_terima || '');
                }

                $('#table2').on('click', '.clickable-cell', function() {
                    let batch = $(this).text().trim();
                    let type = $(this).data('type'); // Mendapatkan jenis mesin dari atribut data-type

                    console.log('Clicked batch:', batch); // Log batch yang dipilih
                    console.log('Type:', type); // Log jenis mesin yang dipilih

                    $.ajax({
                        url: '{{ route('getBatchData') }}', // Ganti dengan URL dari script backend Anda
                        type: 'GET', // Anda dapat menggunakan metode POST atau GET sesuai kebutuhan Anda
                        dataType: 'json', // Menentukan tipe data yang diharapkan dari respons server
                        data: {
                            batch: batch // Mengirimkan data batch ke server
                        },
                        success: function(response) {
                            console.log('Data received:',
                                response); // Log data yang diterima dari server

                            // Memanggil fungsi untuk menampilkan detail proses dengan data yang diterima
                            populateDetailProses(batch, response, type);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error); // Log pesan error jika terjadi
                        }
                    });
                });

                function populateDetailProses(batch, workOrders, type) {
                    // Reset detail proses
                    $('#detailProsesTable tbody').empty();
                    $('#selectedBatch').text(batch);

                    // Tampilkan data untuk setiap work order yang terkait dengan batch yang dipilih
                    workOrders.forEach((wo, index) => {
                        let rowHtml = `
            <tr>
                <td>${index + 1}</td>
                <td>${wo.no_wo}</td>
                <td>${wo.cust}</td>
                <td>${wo.mesin_heating || wo.mesin_temper1 || wo.mesin_temper2 || wo.mesin_temper3}</td>
                <td>${wo.pcs || ''}</td>
                <td>${wo.kg || ''}</td>
                <td>${wo.tgl_wo || ''}</td>
                <td>${wo.status_wo || ''}</td>
                <td>${wo.status_do || ''}</td>
                <td>${wo.proses || ''}</td>
            </tr>
        `;

                        // Tambahkan baris baru ke dalam tabel
                        $('#detailProsesTable tbody').append(rowHtml);
                    });

                    // Memperbarui detail mesin berdasarkan jenis mesin yang dipilih
                    switch (type) {
                        case 'heating':
                            $('#mesinHeating').text(workOrders[0].mesin_heating);
                            $('#tanggalHeating').text(workOrders[0].tgl_wo);
                            break;
                        case 'temper1':
                            $('#mesinTemper1').text(workOrders[0].mesin_temper1);
                            $('#tanggalTemper1').text(workOrders[0].tgl_wo);
                            break;
                        case 'temper2':
                            $('#mesinTemper2').text(workOrders[0].mesin_temper2);
                            $('#tanggalTemper2').text(workOrders[0].tgl_wo);
                            break;
                        case 'temper3':
                            $('#mesinTemper3').text(workOrders[0].mesin_temper3);
                            $('#tanggalTemper3').text(workOrders[0].tgl_wo);
                            break;
                    }
                }



            });
        </script>

        <style>
            #detailProcessTable td {
                padding: 8px;
                border: 1px solid #ccc;
                text-align: center;
                vertical-align: middle;
            }

            #table3 td div {
                border-bottom: 1px solid #dee2e6;
                padding: 8px 0;
            }

            #table3 td div:last-child {
                border-bottom: none;
            }

            .table {
                border-collapse: collapse;
                width: 100%;
            }

            .table th,
            .table td {
                border: 1px solid #dddddd;
                text-align: left;
                padding: 8px;
            }

            .table th {
                background-color: #f2f2f2;
            }
        </style>
    </main>
@endsection
