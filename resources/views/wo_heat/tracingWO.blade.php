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
                            <h5 class="card-title text-center">Trace By Customer</h5>

                            <!-- No. WO Search Textbox -->
                            <div class="form-group">
                                <label for="searchWO">Nama Customer</label>
                                <input type="text" class="form-control" id="searchWO"
                                    placeholder="Cari Nama Customer....">
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
                                    <input type="text" class="form-control" name="searchMesin" placeholder="Search..."
                                        readonly>
                                </div>
                            </div>
                            <br>
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
                                            <td>
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

                function populateTables(data) {
                    populateTable1(data);
                    if (data.length > 0) {
                        populateTable2(data[0]); // Initialize with the first record if available
                        populateTable3(data[0]); // Initialize with the first record if available
                    }
                }

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

                        if (selectedWO) {
                            populateTable2(selectedWO);
                            populateTable3(selectedWO);
                        }
                    });
                }

                function populateTable2(data) {
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
                    $('#detailNoDO').text(data.no_do || '');
                    $('#detailTglST').text(data.tgl_st || '');
                    $('#detailSupir').text(data.supir || '');
                    $('#detailPenerima').text(data.penerima || '');
                    $('#detailTglTerima').text(data.tgl_terima || '');
                }

                // Event handler for clickable cells in the batch columns
                $('#table2').on('click', '.clickable-cell', function() {
                    let batch = $(this).text().trim();
                    let selectedWO = null;

                    console.log('Clicked batch:', batch); // Log the clicked batch

                    // Find the corresponding work order in the global data
                    $.each(globalData, function(customer, workOrders) {
                        workOrders.forEach(wo => {
                            if (wo.batch === batch || wo.batch_temper1 === batch || wo
                                .batch_temper2 === batch || wo.batch_temper3 === batch) {
                                selectedWO = wo;
                                return false; // Exit loop
                            }
                        });
                        if (selectedWO) {
                            return false; // Exit loop
                        }
                    });

                    if (selectedWO) {
                        console.log('Selected work order:', selectedWO); // Log the selected work order
                        populateDetailProses(selectedWO, batch);
                    } else {
                        console.log('No matching work order found for batch:', batch);
                    }
                });

                function populateDetailProses(data, batch) {
                    console.log('Populating details with data:',
                        data); // Log the data being used to populate the details

                    // Determine the machine type based on the batch
                    let mesin = null;
                    if (data.batch === batch) {
                        mesin = data.mesin_heating;
                    } else if (data.batch_temper1 === batch) {
                        mesin = data.mesin_temper1;
                    } else if (data.batch_temper2 === batch) {
                        mesin = data.mesin_temper2;
                    } else if (data.batch_temper3 === batch) {
                        mesin = data.mesin_temper3;
                    }

                    console.log('Mesin value determined:', mesin); // Log the determined machine value

                    if (mesin !== null) {
                        $('input[name="searchMesin"]').val(mesin); // Use attribute selector for input fields
                    } else {
                        console.log('No matching mesin found for batch:', batch);
                    }

                    $('#detailTglWO').text(data.tgl_wo);
                    $('#detailStatusWO').text(data.status_wo);
                    $('#detailStatusDO').text(data.status_do);
                    $('#detailProses').text(data.proses);
                    $('#detailCustomer').text(data.cust);
                    $('#detailPCS').text(data.pcs);
                    $('#detailTonase').text(data.kg);
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
