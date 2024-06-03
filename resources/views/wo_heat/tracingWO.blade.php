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
                                <label for="searchWO">Nama Customer</label>
                                <input type="text" class="form-control" id="searchWO"
                                    placeholder="Cari Nama Customer....">
                            </div>

                            <!-- Table 1 (Trace No.WO) -->
                            <div class="table-responsive">
                                <table class="table" id="table1">
                                    <thead>
                                        <tr>
                                            <th scope="col">Detail</th>
                                            <th scope="col">Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>No. WO</td>
                                            <td id="noWO"></td>
                                        </tr>
                                        <tr>
                                            <td>Tgl. WO</td>
                                            <td id="tglWO"></td>
                                        </tr>
                                        <tr>
                                            <td>Status WO</td>
                                            <td id="statusWO"></td>
                                        </tr>
                                        <tr>
                                            <td>Status DO</td>
                                            <td id="statusDO"></td>
                                        </tr>
                                        <tr>
                                            <td>Proses</td>
                                            <td id="proses"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

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
                                            <td id="batchHeating" class="clickable-cell"></td>
                                            <td id="batchTemper1" class="clickable-cell"></td>
                                            <td id="batchTemper2" class="clickable-cell"></td>
                                            <td id="batchTemper3" class="clickable-cell"></td>
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
                                        <tr>
                                            <th scope="col">Detail</th>
                                            <th scope="col">Value</th>
                                        </tr>
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
                            <div class="form-group row">
                                <label for="searchMesin" class="col-sm-2 col-form-label">Mesin</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="searchMesin" placeholder="Search..."
                                        readonly>
                                </div>
                            </div>

                            <!-- Tabel -->
                            <div class="table-responsive">
                                <table class="table" id="detailProsesTable">
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
                                            <td id="detailWO">
                                                <div id="detailTglWO"></div>
                                                <div id="detailStatusWO"></div>
                                                <div id="detailStatusDO"></div>
                                                <div id="detailProses"></div>
                                            </td>
                                            <td id="detailCustomer"></td>
                                            <td id="detailPCS"></td>
                                            <td id="detailTonase"></td>
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
                            populateTables(data);
                            populateDetailProses(data);
                            populateDetailStatusDO(data);
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                    });
                });

                $('#table2').on('click', '.clickable-cell', function() {
                    var clickedCell = $(this);
                    var row = clickedCell.closest('tr'); // Dapatkan baris terdekat dari sel yang diklik

                    // Ambil nilai dari kolom "Mesin" yang sesuai dengan kolom "Batch" yang diklik
                    var mesin = row.find('#mesin' + clickedCell.attr('id').substr(5)).text();

                    $('#searchMesin').val(mesin);
                    $('#searchMesin').trigger('keyup');
                });


                function populateTables(data) {
                    populateTable1(data);
                    populateTable2(data);
                    populateTable3(data);
                }

                function populateTable1(data) {
                    let no_wo = [],
                        cust = [],
                        tgl_wo = [],
                        status_wo = [],
                        status_do = [],
                        proses = [];
                    $.each(data, function(index, value) {
                        no_wo.push(value.no_wo);
                        cust.push(value.cust);
                        tgl_wo.push(value.tgl_wo);
                        status_wo.push(value.status_wo);
                        status_do.push(value.status_do);
                        proses.push(value.proses);
                    });
                    $('#noWO').text(no_wo.join(', '));
                    $('#customer').text(cust.join(', '));
                    $('#tglWO').text(tgl_wo.join(', '));
                    $('#statusWO').text(status_wo.join(', '));
                    $('#statusDO').text(status_do.join(', '));
                    $('#proses').text(proses.join(', '));
                }

                function populateTable2(data) {
                    let batch = [],
                        mesin = [],
                        tgl_heating = [],
                        batch_temper1 = [],
                        mesin_temper1 = [],
                        tgl_temper1 = [];
                    let batch_temper2 = [],
                        mesin_temper2 = [],
                        tgl_temper2 = [],
                        batch_temper3 = [],
                        mesin_temper3 = [],
                        tgl_temper3 = [];
                    $.each(data, function(index, value) {
                        batch.push(value.batch);
                        mesin.push(value.mesin);
                        tgl_heating.push(value.tgl_heating);
                        batch_temper1.push(value.batch_temper1);
                        mesin_temper1.push(value.mesin_temper1);
                        tgl_temper1.push(value.tgl_temper1);
                        batch_temper2.push(value.batch_temper2);
                        mesin_temper2.push(value.mesin_temper2);
                        tgl_temper2.push(value.tgl_temper2);
                        batch_temper3.push(value.batch_temper3);
                        mesin_temper3.push(value.mesin_temper3);
                        tgl_temper3.push(value.tgl_temper3);
                    });
                    $('#batchHeating').text(batch.join(', '));
                    $('#mesinHeating').text(mesin.join(', '));
                    $('#tanggalHeating').text(tgl_heating.join(', '));
                    $('#batchTemper1').text(batch_temper1.join(', '));
                    $('#mesinTemper1').text(mesin_temper1.join(', '));
                    $('#tanggalTemper1').text(tgl_temper1.join(', '));
                    $('#batchTemper2').text(batch_temper2.join(', '));
                    $('#mesinTemper2').text(mesin_temper2.join(', '));
                    $('#tanggalTemper2').text(tgl_temper2.join(', '));
                    $('#batchTemper3').text(batch_temper3.join(', '));
                    $('#mesinTemper3').text(mesin_temper3.join(', '));
                    $('#tanggalTemper3').text(tgl_temper3.join(', '));
                }

                function populateTable3(data) {
                    let no_do = [],
                        tgl_st = [],
                        supir = [],
                        penerima = [],
                        tgl_terima = [];
                    $.each(data, function(index, value) {
                        no_do.push(value.no_do);
                        tgl_st.push(value.tgl_st);
                        supir.push(value.supir);
                        penerima.push(value.penerima);
                        tgl_terima.push(value.tgl_terima);
                    });
                    $('#detailNoDO').html(no_do.map(no => `<div>${no}</div>`).join(''));
                    $('#detailTglST').html(tgl_st.map(tgl => `<div>${tgl}</div>`).join(''));
                    $('#detailSupir').html(supir.map(sup => `<div>${sup}</div>`).join(''));
                    $('#detailPenerima').html(penerima.map(pen => `<div>${pen}</div>`).join(''));
                    $('#detailTglTerima').html(tgl_terima.map(tgl => `<div>${tgl}</div>`).join(''));
                }

                function populateDetailProses(data) {
                    let wo = [],
                        nama_customer = [],
                        pcs = [],
                        tonase = [],
                        tgl_wo = [],
                        status_wo = [],
                        status_do = [],
                        proses = [],
                        mesin = [];
                    $.each(data, function(index, value) {
                        wo.push(index);
                        nama_customer.push(value.cust);
                        pcs.push(value.pcs);
                        tonase.push(value.kg);
                        tgl_wo.push(value.tgl_wo);
                        status_wo.push(value.status_wo);
                        status_do.push(value.status_do);
                        proses.push(value.proses);
                        mesin.push(value.mesin);
                    });
                    if (wo.length > 0) {
                        $('#detailTglWO').text('Tgl.WO: ' + tgl_wo.join(', '));
                        $('#detailStatusWO').text('Status WO: ' + status_wo.join(', '));
                        $('#detailStatusDO').text('Status DO: ' + status_do.join(', '));
                        $('#detailProses').text('Proses: ' + proses.join(', '));
                        $('#detailCustomer').text(nama_customer.join(', '));
                        $('#detailPCS').text(pcs.join(', '));
                        $('#detailTonase').text(tonase.join(', '));
                    } else {
                        $('#detailTglWO').text('Tgl.WO:');
                        $('#detailStatusWO').text('Status WO:');
                        $('#detailStatusDO').text('Status DO:');
                        $('#detailProses').text('Proses:');
                        $('#detailCustomer').text('');
                        $('#detailPCS').text('');
                        $('#detailTonase').text('');
                        $('#searchMesin').val(mesin.join(', '));
                    }
                }

                function populateDetailStatusDO(data) {
                    let no_do = [],
                        tgl_st = [],
                        supir = [],
                        penerima = [],
                        tgl_terima = [];
                    $.each(data, function(index, value) {
                        no_do.push(value.no_do);
                        tgl_st.push(value.tgl_st);
                        supir.push(value.supir);
                        penerima.push(value.penerima);
                        tgl_terima.push(value.tgl_terima);
                    });
                    if (no_do.length > 0) {
                        $('#detailNoDO').html(no_do.map(no => `<div>${no}</div>`).join(''));
                        $('#detailTglST').html(tgl_st.map(tgl => `<div>${tgl}</div>`).join(''));
                        $('#detailSupir').html(supir.map(sup => `<div>${sup}</div>`).join(''));
                        $('#detailPenerima').html(penerima.map(pen => `<div>${pen}</div>`).join(''));
                        $('#detailTglTerima').html(tgl_terima.map(tgl => `<div>${tgl}</div>`).join(''));
                    } else {
                        $('#detailNoDO').text('');
                        $('#detailTglST').text('');
                        $('#detailSupir').text('');
                        $('#detailPenerima').text('');
                        $('#detailTglTerima').text('');
                    }
                }
            });
        </script>

        <style>
            #table3 td div {
                border-bottom: 1px solid #dee2e6;
                padding: 8px 0;
            }

            #table3 td div:last-child {
                border-bottom: none;
            }
        </style>

    </main>
    < !-- End #main -->
    @endsection
