@extends('layout')

<script src="https://code.highcharts.com/highcharts.js"></script>
@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <nav>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section dashboard">
            <div class="row">
                <h3 style="display: flex;
            justify-content: center;">Dashboard Repair Maintenance</h3>
                <div class="col-xxl-3 col-md-6">
                    <div class="card info-card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">Open <span></span></h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    @if ($openCount > 0)
                                        <i class="bi bi-envelope-open-fill"></i>
                                    @else
                                        <i class="bi bi-envelope-open-fill"></i>
                                    @endif
                                </div>
                                <div class="ps-3">
                                    <h2>{{ $openCount }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- End Sales Card Today -->

                <!-- Sales Card This Month -->
                <div class="col-xxl-3 col-md-6">
                    <div class="card info-card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">On Progress <span></span></h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    @if ($onProgressCount > 0)
                                        <i class="bi bi-hourglass-split "></i>
                                    @else
                                        <i class="bi bi-hourglass-split "></i>
                                    @endif
                                </div>
                                <div class="ps-3">
                                    <h2>{{ $onProgressCount }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- End Sales Card This Month -->

                <!-- Revenue Card Today -->
                <div class="col-xxl-3 col-md-6">
                    <div class="card info-card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">Finish <span></span></h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    @if ($finishCount > 0)
                                        <i class="bi bi-check2-all"></i>
                                    @else
                                        <i class="bi bi-check2-all"></i>
                                    @endif
                                </div>
                                <div class="ps-3">
                                    <h2>{{ $finishCount }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- End Revenue Card Today -->

                <!-- Revenue Card This Month -->
                <div class="col-xxl-3 col-md-6">
                    <div class="card info-card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">Closed <span></span></h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    @if ($closedCount > 0)
                                        <i class="bi bi-x-circle-fill"></i>
                                    @else
                                        <i class="bi bi-x-circle-fill"></i>
                                    @endif
                                </div>
                                <div class="ps-3">
                                    <h2>{{ $closedCount }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- End Revenue Card This Month -->
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div id="chartCutting" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div id="chartMachining" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div id="chartHeatTreatment" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div id="chartMachiningCustom" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div id="summaryHighcharts" style="width: 100%; height: 400px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Linestop (Dalam Menit)</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="yearDropdown">Pilih Tahun:</label>
                                    <select id="date-dropdown2" class="form-control" onchange="updateChart2()">
                                        @foreach ($years2 as $year)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="sectionDropdown">Pilih Section:</label>
                                    <select id="section-dropdown" class="form-control" onchange="updateChart2()">
                                        <option value="All" selected>All</option> <!-- Default option -->
                                        @foreach ($sections as $section)
                                            <option value="{{ $section }}">{{ $section }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div id="repairMaintenance" style="width: 100%; height: auto;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card" style="height: 560px;">
                        <div class="card-body">
                            <h5 class="card-title">Linestop (Dalam Menit)</h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="start_month2">Bulan Mulai:</label>
                                        <input type="date" id="start_month2" name="start_month2" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="end_month2">Bulan Akhir:</label>
                                        <input type="date" id="end_month2" name="end_month2" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="chart-container"
                                style="position: relative; height: calc(100% - 10px); width: 100%;">
                                <canvas id="periodeRepair" style="height: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div id="highcharts-container" class="card">
                        <div class="card-body">
                            <h5 class="card-title">Detail Linestop / Mesin (Dalam Menit)</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="sectionDropdown">Pilih Section:</label>
                                    <select id="section-dropdown2" class="form-control"
                                        onchange="updateChartPeriodeMesin()">
                                        <option value="All" selected>All</option>
                                        <!-- Default option with "selected" attribute -->
                                        @foreach ($sections as $section)
                                            <option value="{{ $section }}">{{ $section }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="start_mesin">Bulan Mulai:</label>
                                    <input type="date" id="start_mesin" name="start_mesin" class="form-control"
                                        onchange="updateChartPeriodeMesin()">
                                </div>
                                <div class="col-md-3">
                                    <label for="end_mesin">Bulan Akhir:</label>
                                    <input type="date" id="end_mesin" name="end_mesin" class="form-control"
                                        onchange="updateChartPeriodeMesin()">
                                </div>
                            </div>
                            <div id="periodeRepairMesin" style="width: 100%; height: 400px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-sm-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Linestop Alat Bantu (Dalam Menit)</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="yearDropdown">Pilih Tahun:</label>
                                    <select id="date-dropdown-alat" class="form-control" onchange="updateChartAlat()">
                                        @foreach ($years2 as $year)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="sectionDropdown">Pilih Section:</label>
                                    <select id="section-dropdown-alat" class="form-control" onchange="updateChartAlat()">
                                        <option value="All" selected>All</option> <!-- Default option -->
                                        @foreach ($sections as $section)
                                            <option value="{{ $section }}">{{ $section }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div id="repairAlatBantu" style="width: 100%; height: auto;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="card" style="height: 560px;">
                        <div class="card-body">
                            <h5 class="card-title">Linestop Alat Bantu (Dalam Menit)</h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="start_month_alat">Bulan Mulai:</label>
                                        <input type="date" id="start_month_alat" name="start_month_alat"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="end_month_alat">Bulan Akhir:</label>
                                        <input type="date" id="end_month_alat" name="end_month_alat"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="chart-container"
                                style="position: relative; height: calc(100% - 10px); width: 100%;">
                                <canvas id="periodeRepairAlat" style="height: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div id="highcharts-container" class="card">
                        <div class="card-body">
                            <h5 class="card-title">Detail Linestop / Alat Bantu (Dalam Menit)</h5>
                            <div class="row">
                                <!-- Tambahkan dropdown dan input date untuk filter -->
                                <div class="col-md-3">
                                    <label for="sectionDropdown">Pilih Section:</label>
                                    <select id="section-dropdown-periode-alat" class="form-control"
                                        onchange="updateChartPeriodeAlat()">
                                        <option value="All" selected>All</option>
                                        @foreach ($sections as $section)
                                            <option value="{{ $section }}">{{ $section }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="start_alat">Bulan Mulai:</label>
                                    <input type="date" id="start_alat" name="start_alat" class="form-control"
                                        onchange="updateChartPeriodeAlat()">
                                </div>
                                <div class="col-md-3">
                                    <label for="end_alat">Bulan Akhir:</label>
                                    <input type="date" id="end_alat" name="end_alat" class="form-control"
                                        onchange="updateChartPeriodeAlat()">
                                </div>

                                <!-- Elemen untuk menampilkan grafik -->
                                <div id="periodeAlat" style="width: 100%; height: 400px;"></div>
                            </div>
                        </div>
                    </div>


                </div>

        </section>

        <hr>

        <section class="section dashboard">
            <div class="row">
                <h3 style="display: flex; justify-content: center;">Dashboard Handling Claim dan Complain</h3>
                <!-- Left side columns -->
                <div class="col-lg-12">
                    <div class="row">

                        <!-- Sales Card -->
                        <div class="col-xxl-3 col-md-6">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">Open</h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-envelope-open-fill"></i>
                                        </div>
                                        <div class="ps-3">
                                            @php
                                                $openHandlingsCount = \App\Models\Handling::where('status', 0)->count();
                                            @endphp
                                            <h6>{{ $openHandlingsCount }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- End Sales Card -->

                        <!-- Revenue Card -->
                        <div class="col-xxl-3 col-md-6">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">On Progress</h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-hourglass-split "></i>
                                        </div>
                                        <div class="ps-3">
                                            @php
                                                $openHandlingsCount = \App\Models\Handling::where('status', 1)->count();
                                            @endphp
                                            <h6>{{ $openHandlingsCount }}</h6>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div><!-- End Revenue Card -->

                        <!-- Customers Card -->
                        <div class="col-xxl-3 col-md-6">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">Finish</h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-check2-all"></i>
                                        </div>
                                        <div class="ps-3">
                                            @php
                                                $openHandlingsCount = \App\Models\Handling::where('status', 2)->count();
                                            @endphp
                                            <h6>{{ $openHandlingsCount }}</h6>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div><!-- End Customers Card -->
                        <!-- Customers Card -->
                        <div class="col-xxl-3 col-md-6">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">Close</h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-x-circle-fill"></i>
                                        </div>
                                        <div class="ps-3">
                                            @php
                                                $openHandlingsCount = \App\Models\Handling::where('status', 3)->count();
                                            @endphp
                                            <h6>{{ $openHandlingsCount }}</h6>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div><!-- End Customers Card -->

                        <!-- Reports -->
                        <div class="col-sm-9">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Chart Bar Klaim dan Komplain <span></span></h5>
                                    <div>
                                        <label for="yearDropdown">Pilih Tahun:</label>
                                        <select id='date-dropdown' style="width: 10%"></select>
                                        <canvas id="myChart" style="height:24.5vh; width:100%"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Chart Bar Periode</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="start_periode">Bulan Mulai:</label>
                                            <input type="date" id="start_periode" name="start_periode"
                                                class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="end_periode">Bulan Akhir:</label>
                                            <input type="date" id="end_periode" name="end_periode"
                                                class="form-control">
                                        </div>
                                        <div id="chartAllPeriode" style="height:21.5vh; width:100%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Chart Pie Tipe Material<span></span></h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="start_month">Bulan Mulai:</label>
                                            <input type="date" id="start_month" name="start_month"
                                                class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="end_month">Bulan Akhir:</label>
                                            <input type="date" id="end_month" name="end_month" class="form-control">
                                        </div>
                                        {{-- filternya --}}
                                        <div class="col-lg-6" style="margin-top: 1%">
                                            <label for="jenis">Jenis:</label>
                                            <select id="jenis" class="form-select form-select-sm"
                                                aria-label=".form-select-sm example" onchange="FilterPieChartTipe()">
                                                <option selected>--- Pilih Jenis ---</option>
                                                <option value="frekuensi">Frekuensi Jenis</option>
                                                <option value="qty">QTY</option>
                                                <option value="pcs">PCS</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-6" style="margin-top: 1%">
                                            <label for="tipe">Kategori:</label>
                                            <select id="type" class="form-select form-select-sm"
                                                aria-label=".form-select-sm example" onchange="FilterPieChartTipe()">
                                                <option selected>--- Pilih Kategori ---</option>
                                                <option value="kategori">All Kategori</option>
                                                <option value="type_1">Komplain</option>
                                                <option value="type_2">Klaim</option>
                                            </select>
                                        </div>
                                        <div id="ChartPieTypeMaterial"
                                            style="width: 100%; height: 300px; margin-top: 5%;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Chart Pie Proses<span></span></h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="start_month3">Bulan Mulai:</label>
                                            <input type="date" id="start_month3" name="start_month3"
                                                class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="end_month3">Bulan Akhir:</label>
                                            <input type="date" id="end_month3" name="end_month3"
                                                class="form-control">
                                        </div>
                                        {{-- filternya --}}
                                        <div class="col-lg-6" style="margin-top: 1%">
                                            <label for="jenis2">Jenis:</label>
                                            <select id="jenis2" class="form-select form-select-sm"
                                                aria-label=".form-select-sm example" onchange="updatePieChart();">
                                                <option selected>--- Pilih Jenis ---</option>
                                                <option value="frekuensi2">Frekuensi Jenis</option>
                                                <option value="qty">QTY</option>
                                                <option value="pcs">PCS</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-6" style="margin-top: 1%">
                                            <label for="tipe2">Kategori:</label>
                                            <select id="tipe2" class="form-select form-select-sm"
                                                aria-label=".form-select-sm example" onchange="updatePieChart();">
                                                <option selected>--- Pilih Kategori ---</option>
                                                <option value="kategori_2">All Kategori</option>
                                                <option value="type_1">Komplain</option>
                                                <option value="type_2">Klaim</option>
                                            </select>
                                        </div>
                                        <div id="ChartPieProses" style="width: 100%; height: 300px; margin-top: 5%;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- End Left side columns -->

            </div>
        </section>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script>
            //dropdownYear
            let dateDropdown = document.getElementById('date-dropdown');

            let currentYear = new Date().getFullYear();
            let earliestYear = 2020;
            while (currentYear >= earliestYear) {
                let dateOption = document.createElement('option');
                dateOption.text = currentYear;
                dateOption.value = currentYear;
                dateDropdown.add(dateOption);
                currentYear -= 1;
            }

            document.addEventListener('DOMContentLoaded', function() {
                // Mendapatkan elemen input tanggal
                var startDateInput = document.getElementById('start_periode');
                var endDateInput = document.getElementById('end_periode');

                // Menambahkan event listener untuk memperbarui chart saat nilai input tanggal berubah
                startDateInput.addEventListener('change', updateChart);
                endDateInput.addEventListener('change', updateChart);

                function updateChart() {
                    var startDate = startDateInput.value;
                    var endDate = endDateInput.value;

                    // Mengirim permintaan AJAX untuk mendapatkan data dari controller
                    $.ajax({
                        url: '{{ route('getChartData') }}',
                        method: 'GET',
                        data: {
                            start_date: startDate,
                            end_date: endDate
                        },
                        success: function(response) {
                            var responseData = response;
                            renderChart(responseData);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error saat memuat data:', xhr.statusText);
                        }
                    });
                }

                function renderChart(data) {
                    // Mengatur data yang diperlukan untuk chart
                    Highcharts.chart('chartAllPeriode', {
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: 'Status Handling'
                        },
                        xAxis: {
                            categories: ['Open', 'Close']
                        },
                        yAxis: {
                            title: {
                                text: 'Jumlah'
                            }
                        },
                        plotOptions: {
                            series: {
                                borderRadius: 5,
                                colorByPoint: true
                            }
                        },
                        series: [{
                            name: 'Data',
                            data: [{
                                name: 'Open',
                                y: data.Open,
                                color: '#0000FF'
                            }, {
                                name: 'Close',
                                y: data.Close,
                                color: '#FF0000'
                            }]
                        }]
                    });
                }
            });

            //Chart Handling /year
            // Fungsi untuk mengonversi angka bulan menjadi nama bulan dalam bahasa Inggris
            function getMonthName(monthNumber) {
                const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
                    "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                ];
                return monthNames[monthNumber];
            }

            // Event listener untuk menangkap perubahan pada dropdown tahun
            dateDropdown.addEventListener('change', function() {
                var selectedYear = this.value; // Mendapatkan tahun yang dipilih dari dropdown
                // Buat permintaan AJAX ke server dengan tahun yang dipilih
                $.ajax({
                    url: '{{ route('getDataByYear') }}',
                    type: 'GET',
                    data: {
                        year: selectedYear
                    }, // Mengirim tahun yang dipilih ke server
                    success: function(response) {
                        // Update chart dengan data baru dari respons server
                        updateChart(response);
                    },
                    error: function(xhr, status, error) {
                        console.error(error); // Tampilkan pesan error jika terjadi kesalahan
                    }
                });
            });

            // Fungsi untuk memperbarui chart dengan data baru
            function updateChart(data) {
                // Memetakan data baru ke status1 dan status2
                var status1 = [];
                var status2 = [];
                for (var i = 0; i < 12; i++) {
                    var found = data.find(function(item) {
                        return parseInt(item.month) === i + 1; // Ubah indeks bulan menjadi dimulai dari 1
                    });
                    if (found) {
                        status1.push(found.total_status_2_0);
                        status2.push(found.total_status_3);
                    } else {
                        status1.push(0);
                        status2.push(0);
                    }
                }

                // Perbarui data chart
                myChart.data.datasets[0].data = status1; // Update data untuk status1 (Open)
                myChart.data.datasets[1].data = status2; // Update data untuk status2 (Close)
                myChart.update(); // Perbarui chart
            }
            // Mendapatkan data dari controller Laravel
            var chartData = {!! $data !!};

            // Inisialisasi array untuk bulan-bulan
            // Inisialisasi array untuk bulan-bulan (dimulai dari Januari)
            var months = [];
            for (var i = 1; i < 12; i++) {
                months.push(getMonthName(i));
            }

            // Memetakan total status 1 (status_2=0) dari data
            var status1 = [];
            for (var i = 1; i <= 12; i++) {
                var found = chartData.find(function(item) {
                    return parseInt(item.month) === i;
                });
                if (found) {
                    status1.push(found.total_status_2_0);
                } else {
                    status1.push(0);
                }
            }

            // Memetakan total status 2 (status=3) dari data
            var status2 = [];
            for (var i = 1; i <= 12; i++) {
                var found = chartData.find(function(item) {
                    return parseInt(item.month) === i;
                });
                if (found) {
                    status2.push(found.total_status_3);
                } else {
                    status2.push(0);
                }
            }

            var ctx = document.getElementById('myChart').getContext('2d');


            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Open',
                        data: status1,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)', // Warna biru untuk 'Open'
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2
                    }, {
                        label: 'Close',
                        data: status2,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)', // Warna merah untuk 'Close'
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 2
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)' // Warna grid sumbu y
                            },
                            ticks: {
                                color: 'rgba(0, 0, 0, 0.7)' // Warna label sumbu y
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)' // Warna grid sumbu x
                            },
                            ticks: {
                                color: 'rgba(0, 0, 0, 0.7)' // Warna label sumbu x
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: 'rgba(0, 0, 0, 0.7)' // Warna label legenda
                            }
                        }
                    },
                    animation: {
                        duration: 1500, // Durasi animasi
                        easing: 'easeInOutQuart' // Efek animasi
                    }
                }
            });
            //end

            // Data cutting chart
            var cuttingData = {!! $chartCutting !!};

            // Inisialisasi array untuk bulan-bulan
            var months = [];
            for (var i = 1; i <= 12; i++) {
                months.push(getMonthName(i));
            }

            // Memetakan total status 1 (status_2=0) dari data
            var status1 = [];
            for (var i = 1; i <= 12; i++) {
                var found = cuttingData.find(function(item) {
                    return parseInt(item.month) === i;
                });
                if (found) {
                    status1.push(found.total_status_2_0);
                } else {
                    status1.push(0);
                }
            }

            // Memetakan total status 2 (status=3) dari data
            var status2 = [];
            for (var i = 1; i <= 12; i++) {
                var found = cuttingData.find(function(item) {
                    return parseInt(item.month) === i;
                });
                if (found) {
                    status2.push(found.total_status_3);
                } else {
                    status2.push(0);
                }
            }

            Highcharts.chart('chartCutting', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Mesin Cutting'
                },
                xAxis: {
                    categories: months,
                    crosshair: true,
                    accessibility: {
                        description: 'Bulan'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Jumlah Repair'
                    }
                },
                credits: { // Configuration to disable credits
                    enabled: false
                },
                tooltip: {
                    headerFormat: '<span style="font-size:12px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Status (Open)',
                    data: status1,
                    color: 'rgba(0, 150, 0, 0.5)' // Warna hijau yang lebih gelap untuk 'Status (Open)'
                }, {
                    name: 'Status (Closed)',
                    data: status2,
                    color: 'rgba(0, 0, 0, 0.7)' // Warna hitam yang lebih terang untuk 'Status (Closed)'
                }]

            });


            // Data Heat Treatment chart
            var heattreatmentData = {!! $chartHeatTreatment !!};

            // Memetakan total status 1 (status_2=0) dari data
            var status1 = [];
            for (var i = 1; i <= 12; i++) {
                var found = heattreatmentData.find(function(item) {
                    return parseInt(item.month) === i;
                });
                if (found) {
                    status1.push(found.total_status_2_0);
                } else {
                    status1.push(0);
                }
            }

            // Memetakan total status 2 (status=3) dari data
            var status2 = [];
            for (var i = 1; i <= 12; i++) {
                var found = heattreatmentData.find(function(item) {
                    return parseInt(item.month) === i;
                });
                if (found) {
                    status2.push(found.total_status_3);
                } else {
                    status2.push(0);
                }
            }

            Highcharts.chart('chartHeatTreatment', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Mesin Heat Treatment'
                },
                xAxis: {
                    categories: months,
                    crosshair: true,
                    accessibility: {
                        description: 'Bulan'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Jumlah Repair'
                    }
                },
                credits: { // Configuration to disable credits
                    enabled: false
                },
                tooltip: {
                    headerFormat: '<span style="font-size:12px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Status (Open)',
                    data: status1,
                    color: 'rgba(0, 150, 0, 0.5)' // Warna hijau yang lebih gelap untuk 'Status (Open)'
                }, {
                    name: 'Status (Closed)',
                    data: status2,
                    color: 'rgba(0, 0, 0, 0.7)' // Warna hitam yang lebih terang untuk 'Status (Closed)'
                }]

            });

            // Data Machining Chart
            var machiningData = {!! $chartMachining !!};

            // Memetakan total status 1 (status_2=0) dari data
            var status1 = [];
            for (var i = 1; i <= 12; i++) {
                var found = machiningData.find(function(item) {
                    return parseInt(item.month) === i;
                });
                if (found) {
                    status1.push(found.total_status_2_0);
                } else {
                    status1.push(0);
                }
            }

            // Memetakan total status 2 (status=3) dari data
            var status2 = [];
            for (var i = 1; i <= 12; i++) {
                var found = machiningData.find(function(item) {
                    return parseInt(item.month) === i;
                });
                if (found) {
                    status2.push(found.total_status_3);
                } else {
                    status2.push(0);
                }
            }

            Highcharts.chart('chartMachining', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Mesin Machining'
                },
                xAxis: {
                    categories: months,
                    crosshair: true,
                    accessibility: {
                        description: 'Bulan'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Jumlah Repair'
                    }
                },
                credits: { // Configuration to disable credits
                    enabled: false
                },
                tooltip: {
                    headerFormat: '<span style="font-size:12px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                credits: { // Configuration to disable credits
                    enabled: false
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Status (Open)',
                    data: status1,
                    color: 'rgba(0, 150, 0, 0.5)' // Warna hijau yang lebih gelap untuk 'Status (Open)'
                }, {
                    name: 'Status (Closed)',
                    data: status2,
                    color: 'rgba(0, 0, 0, 0.7)' // Warna hitam yang lebih terang untuk 'Status (Closed)'
                }]

            });

            // Data CT Bubut Chart
            var machiningcustomData = {!! $chartMachiningCustom !!};

            // Memetakan total status 1 (status_2=0) dari data
            var status1 = [];
            for (var i = 1; i <= 12; i++) {
                var found = machiningcustomData.find(function(item) {
                    return parseInt(item.month) === i;
                });
                if (found) {
                    status1.push(found.total_status_2_0);
                } else {
                    status1.push(0);
                }
            }

            // Memetakan total status 2 (status=3) dari data
            var status2 = [];
            for (var i = 1; i <= 12; i++) {
                var found = machiningcustomData.find(function(item) {
                    return parseInt(item.month) === i;
                });
                if (found) {
                    status2.push(found.total_status_3);
                } else {
                    status2.push(0);
                }
            }

            Highcharts.chart('chartMachiningCustom', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Mesin Maching Custom'
                },
                xAxis: {
                    categories: months,
                    crosshair: true,
                    accessibility: {
                        description: 'Bulan'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Jumlah Repair'
                    }
                },
                credits: { // Configuration to disable credits
                    enabled: false
                },
                tooltip: {
                    headerFormat: '<span style="font-size:12px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Status (Open)',
                    data: status1,
                    color: 'rgba(0, 150, 0, 0.5)' // Warna hijau yang lebih gelap untuk 'Status (Open)'
                }, {
                    name: 'Status (Closed)',
                    data: status2,
                    color: 'rgba(0, 0, 0, 0.7)' // Warna hitam yang lebih terang untuk 'Status (Closed)'
                }]

            });

            var summaryData = {!! json_encode($summaryData) !!};

            // Initialize array for months
            var months = [];
            for (var i = 1; i <= 12; i++) {
                months.push(getMonthName(i));
            }

            // Function to get month name from its number
            function getMonthName(monthNumber) {
                var d = new Date();
                d.setMonth(monthNumber - 1);
                return d.toLocaleString('en-us', {
                    month: 'long'
                });
            }

            // Get all unique sections
            var sections = ['CUTTING', 'HEAT TREATMENT', 'MACHINING', 'MACHINING CUSTOM'];

            // Define colors for each section
            var sectionColors = {
                'CUTTING': {
                    'open': '#FF6666', // Warna merah muda untuk Cutting (Open)
                    'closed': '#CC0000' // Warna merah tua untuk Cutting (Closed)
                },
                'HEAT TREATMENT': {
                    'open': '#66FF66', // Warna hijau muda untuk Heat Treatment (Open)
                    'closed': '#009900' // Warna hijau tua untuk Heat Treatment (Closed)
                },
                'MACHINING': {
                    'open': '#0652DD', // Warna biru muda untuk Machining (Open)
                    'closed': '#1B1464' // Warna biru tua untuk Machining (Closed)
                },
                'MACHINING CUSTOM': {
                    'open': '#ADD8E6', // Warna biru muda untuk Machining Custom (Open)
                    'closed': '#4682B4' // Warna biru tua untuk Machining Custom (Closed)
                }
            };

            // Create data series for Highcharts
            var seriesData = [];
            sections.forEach(function(section) {
                var openArray = [];
                var closedArray = [];
                months.forEach(function(month) {
                    // Check if data exists for this section and month
                    var sectionData = summaryData.find(data => data.section.toUpperCase() === section &&
                        getMonthName(data.month) === month);
                    if (sectionData) {
                        openArray.push(parseInt(sectionData.total_status_2_0));
                        closedArray.push(parseInt(sectionData.total_status_3));
                    } else {
                        openArray.push(0);
                        closedArray.push(0);
                    }
                });

                seriesData.push({
                    name: section + ' (Open)',
                    data: openArray,
                    color: sectionColors[section.toUpperCase()][
                        'open'
                    ] // Mengatur warna label berdasarkan bagian (Open)
                }, {
                    name: section + ' (Closed)',
                    data: closedArray,
                    color: sectionColors[section.toUpperCase()][
                        'closed'
                    ] // Mengatur warna label berdasarkan bagian (Closed)
                });
            });

            // Create chart using Highcharts
            Highcharts.chart('summaryHighcharts', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Summary Repair Maintenance'
                },
                xAxis: {
                    categories: months,
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Total Repair Maintenance'
                    }
                },
                credits: { // Configuration to disable credits
                    enabled: false
                },
                tooltip: {
                    headerFormat: '<span style="font-size: 12px">{point.key}</span><br/>',
                    pointFormat: '<span style="color:{series.color};font-weight:bold;">{series.name}: </span>' +
                        '<span style="font-weight:bold;">{point.y}</span><br/>',
                    footerFormat: '',
                    shared: true,
                    useHTML: true,
                    style: {
                        width: '200px'
                    }
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: seriesData
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var pieData = {!! json_encode($formattedData) !!};

                Highcharts.chart('ChartPieTypeMaterial', {
                    chart: {
                        type: 'pie'
                    },
                    credits: {
                        enabled: false
                    },
                    title: {
                        text: 'Total Tipe Material'
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.y}</b>'
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                            }
                        }
                    },
                    series: [{
                        name: 'Total Type Materials',
                        colorByPoint: true,
                        data: pieData
                    }]
                });

                document.getElementById('type').addEventListener('change', FilterPieChartTipe);

                function FilterPieChartTipe() {
                    var jenis = document.getElementById('jenis').value;
                    var typeSelected = document.getElementById('type').value;
                    var kategori = document.getElementById('type').options[document.getElementById('type')
                        .selectedIndex].text;

                    console.log('Memilih kategori:', kategori);
                    var filterType;

                    if (jenis === 'frekuensi') {
                        if (typeSelected === 'kategori') {
                            filterType = 'kategori';
                        } else if (typeSelected === 'type_1') {
                            filterType = 'total_komplain';
                        } else if (typeSelected === 'type_2') {
                            filterType = 'total_klaim';
                        }
                    } else if (jenis === 'qty') {
                        if (typeSelected === 'kategori') {
                            filterType = 'qty_all';
                        } else if (typeSelected === 'type_1') {
                            filterType = 'qty_komplain';
                        } else if (typeSelected === 'type_2') {
                            filterType = 'qty_klaim';
                        }
                    } else if (jenis === 'pcs') {
                        if (typeSelected === 'kategori') {
                            filterType = 'pcs_all';
                        } else if (typeSelected === 'type_1') {
                            filterType = 'pcs_komplain';
                        } else if (typeSelected === 'type_2') {
                            filterType = 'pcs_klaim';
                        }
                    }

                    var startMonth = document.getElementById('start_month').value;
                    var endMonth = document.getElementById('end_month').value;

                    $.ajax({
                        url: '{{ route('FilterPieChartTipe') }}',
                        method: 'GET',
                        data: {
                            jenis: jenis,
                            type: typeSelected,
                            kategori: kategori,
                            start_month: startMonth,
                            end_month: endMonth
                        },
                        success: function(response) {
                            var data = response;
                            renderChart(data, filterType, jenis, kategori);
                            console.log("kategori masuk1: ", data);
                        }
                    });
                }

                function renderChart(data, filterType, jenis, kategori) {
                    var chartData = [];
                    console.log('Nilai kategori:', kategori);
                    for (var i = 0; i < data.length; i++) {
                        if (data[i][filterType] > 0) {
                            chartData.push({
                                name: data[i].type_name,
                                y: parseInt(data[i][filterType]),
                                qty: data[i].total_qty,
                                pcs: data[i].total_pcs,
                                total_klaim: data[i].total_klaim,
                                total_komplain: data[i].total_komplain,
                                qty_komplain: data[i].qty_komplain,
                                qty_klaim: data[i].qty_klaim,
                                pcs_komplain: data[i].pcs_komplain,
                                pcs_klaim: data[i].pcs_klaim,
                                qty_all: data[i].qty_all,
                                pcs_all: data[i].pcs_all
                            });
                        }
                    }

                    Highcharts.chart('ChartPieTypeMaterial', {
                        chart: {
                            type: 'pie'
                        },
                        title: {
                            text: 'Pie Chart Berdasarkan Tipe'
                        },
                        tooltip: {
                            formatter: function() {
                                var tooltip = '<b>' + this.point.name + '</b>: ';

                                if (jenis === 'qty') {
                                    if (kategori === 'All Kategori') {
                                        tooltip += this.point.qty_all + ' kg';
                                    } else if (kategori === 'Komplain') {
                                        tooltip += this.point.qty_komplain + ' kg';
                                    } else if (kategori === 'Klaim') {
                                        tooltip += this.point.qty_klaim + ' kg';
                                    }
                                } else if (jenis === 'pcs') {
                                    if (kategori === 'All Kategori') {
                                        tooltip += this.point.pcs_all + ' pcs';
                                    } else if (kategori === 'Komplain') {
                                        tooltip += this.point.pcs_komplain + ' pcs';
                                    } else if (kategori === 'Klaim') {
                                        tooltip += this.point.pcs_klaim + ' pcs';
                                    }
                                } else if (jenis === 'frekuensi') {
                                    if (kategori === 'All Kategori') {
                                        tooltip += '<br>' + 'Total Klaim: ' + this.point.total_klaim +
                                            '<br>' + ' Total Komplain: ' + this.point.total_komplain +
                                            '<br>' + 'Total Qty: ' + this.point.qty_all + '<br>' +
                                            'Total Pcs: ' + this.point.pcs_all;
                                    } else if (kategori === 'Komplain') {
                                        tooltip += 'Jumlah Komplain: ' + this.point.total_komplain +
                                            '<br>' + ' Total Qty: ' + this.point.qty_all;
                                    } else if (kategori === 'Klaim') {
                                        tooltip += 'Jumlah Klaim: ' + this.point.total_klaim + '<br>' +
                                            ' Total Pcs: ' + this.point.pcs_all;
                                    }
                                }
                                tooltip += ' (' + this.point.percentage.toFixed(1) + '%)';
                                return tooltip;
                            }
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: false
                                },
                                showInLegend: true
                            }
                        },
                        series: [{
                            name: 'Total Data',
                            data: chartData
                        }]
                    });
                }
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('tipe2').addEventListener('change', updatePieChart);

                function updatePieChart() {
                    var jenis2 = document.getElementById('jenis2').value;
                    var typeSelected2 = document.getElementById('tipe2').value;
                    var kategori_2 = document.getElementById('tipe2').options[document.getElementById('tipe2')
                        .selectedIndex].text;

                    var filterType2;

                    if (jenis2 === 'frekuensi2') {
                        if (typeSelected2 === 'kategori_2') {
                            filterType2 = 'kategori_2';
                        } else if (typeSelected2 === 'type_1') {
                            filterType2 = 'total_komplain2';
                        } else if (typeSelected2 === 'type_2') {
                            filterType2 = 'total_klaim2';
                        }
                    } else if (jenis2 === 'qty') {
                        if (typeSelected2 === 'kategori_2') {
                            filterType2 = 'qty_all';
                        } else if (typeSelected2 === 'type_1') {
                            filterType2 = 'qty_komplain';
                        } else if (typeSelected2 === 'type_2') {
                            filterType2 = 'qty_klaim';
                        }
                    } else if (jenis2 === 'pcs') {
                        if (typeSelected2 === 'kategori_2') {
                            filterType2 = 'pcs_all';
                        } else if (typeSelected2 === 'type_1') {
                            filterType2 = 'pcs_komplain';
                        } else if (typeSelected2 === 'type_2') {
                            filterType2 = 'pcs_klaim';
                        }
                    }

                    var startMonth3 = document.getElementById('start_month3').value;
                    var endMonth3 = document.getElementById('end_month3').value;

                    $.ajax({
                        url: '{{ route('FilterPieChartProses') }}',
                        method: 'GET',
                        data: {
                            jenis2: jenis2,
                            tipe2: typeSelected2,
                            kategori_2: kategori_2,
                            start_month3: startMonth3,
                            end_month3: endMonth3
                        },
                        success: function(response) {
                            var data = response;
                            renderPieChart(data, filterType2, jenis2, kategori_2);
                            console.log("kategori masuk1: ", data);
                        }
                    });
                }

                function renderPieChart(data, filterType2, jenis2, kategori_2) {
                    var chartData = [];

                    // Memproses data yang diterima untuk grafik
                    for (var i = 0; i < data.length; i++) {
                        // Memeriksa apakah nilai filterType2 dari data saat ini tidak nol dan sesuai dengan filter yang dipilih
                        if (data[i][filterType2] > 0) {
                            // Menambahkan data ke chartData
                            chartData.push({
                                name: data[i].type_name,
                                y: parseInt(data[i][filterType2]),
                                qty: data[i].total_qty,
                                pcs: data[i].total_pcs,
                                total_klaim2: data[i].total_klaim2,
                                total_komplain2: data[i].total_komplain2,
                                qty_komplain: data[i].qty_komplain,
                                qty_klaim: data[i].qty_klaim,
                                pcs_komplain: data[i].pcs_komplain,
                                pcs_klaim: data[i].pcs_klaim,
                                qty_all: data[i].qty_all,
                                pcs_all: data[i].pcs_all
                            });
                            console.log("kategori masuk: ", data);
                        }
                    }

                    // Membuat grafik pie menggunakan Highcharts
                    Highcharts.chart('ChartPieProses', {
                        chart: {
                            type: 'pie'
                        },
                        credits: {
                            enabled: false
                        },
                        title: {
                            text: 'Total Proses'
                        },
                        tooltip: {
                            formatter: function() {
                                var tooltip = '<b>' + this.point.name + '</b>: ';
                                if (jenis2 === 'qty') {
                                    if (kategori_2 === 'All Kategori') {
                                        tooltip += this.point.qty_all + ' kg';
                                    } else if (kategori_2 === 'Komplain') {
                                        tooltip += this.point.qty_komplain + ' kg';
                                    } else if (kategori_2 === 'Klaim') {
                                        tooltip += this.point.qty_klaim + ' kg';
                                    }
                                } else if (jenis2 === 'pcs') {
                                    if (kategori_2 === 'All Kategori') {
                                        tooltip += this.point.pcs_all + ' pcs';
                                    } else if (kategori_2 === 'Komplain') {
                                        tooltip += this.point.pcs_komplain + ' pcs';
                                    } else if (kategori_2 === 'Klaim') {
                                        tooltip += this.point.pcs_klaim + ' pcs';
                                    }
                                } else if (jenis2 === 'frekuensi2') {
                                    if (kategori_2 === 'All Kategori') {
                                        tooltip += '<br>' + 'Total Klaim: ' + this.point.total_klaim2 +
                                            '<br>' + ' Total Komplain: ' + this.point.total_komplain2 +
                                            '<br>' + 'Total Qty: ' + this.point.qty_all + '<br>' +
                                            'Total Pcs: ' + this.point.pcs_all;
                                    } else if (kategori_2 === 'Komplain') {
                                        tooltip += 'Jumlah Komplain: ' + this.point.total_komplain2 +
                                            '<br>' + ' Total Qty: ' + this.point.qty_all;
                                    } else if (kategori_2 === 'Klaim') {
                                        tooltip += 'Jumlah Klaim: ' + this.point.total_klaim2 + '<br>' +
                                            ' Total Pcs: ' + this.point.pcs_all;
                                    }
                                }

                                tooltip += ' (' + this.point.percentage.toFixed(1) + '%)';
                                return tooltip;
                            }
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: false
                                },
                                showInLegend: true
                            }
                        },
                        series: [{
                            name: 'Total Data',
                            data: chartData
                        }]
                    });
                }
            });

            document.addEventListener('DOMContentLoaded', function() {
                var chartData = {!! json_encode($pieProses) !!};
                console.log(chartData); // Pastikan untuk memeriksa data yang tercetak di konsol

                Highcharts.chart('ChartPieProses', {
                    chart: {
                        type: 'pie'
                    },
                    credits: {
                        enabled: false
                    },
                    title: {
                        text: 'Total Proses'
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                            }
                        }
                    },
                    series: [{
                        name: 'Process',
                        colorByPoint: true,
                        data: chartData
                    }]
                });
            });
        </script>
        <script>
            // Inisialisasi chart dengan data default
            var repairMaintenanceChart;

            // Inisialisasi dropdown tahun saat halaman dimuat
            document.addEventListener('DOMContentLoaded', function() {
                let dateDropdown = document.getElementById('date-dropdown2');
                let currentYear = new Date().getFullYear();
                let earliestYear = 2020; // Tahun awal yang diinginkan
                while (currentYear >= earliestYear) {
                    let dateOption = document.createElement('option');
                    dateOption.text = currentYear;
                    dateOption.value = currentYear;
                    dateDropdown.add(dateOption);
                    currentYear -= 1;
                }
                // Panggil updateChart2() untuk memuat data awal
                updateChart2();
            });

            // Event handler untuk perubahan pada dropdown tahun
            document.getElementById('date-dropdown2').addEventListener('change', function() {
                updateChart2();
            });

            function updateChart2() {
                var selectedYear = document.getElementById('date-dropdown2').value;
                var selectedSection = document.getElementById('section-dropdown').value;

                // Perform AJAX request to get new data based on selected year and section
                $.ajax({
                    url: '/getRepairMaintenance', // Replace with appropriate endpoint URL
                    method: 'GET',
                    data: {
                        year: selectedYear,
                        section: selectedSection
                    },
                    success: function(response) {
                        var labels = response.labels;
                        var data2 = response.data2;

                        var color; // Variabel untuk menyimpan warna yang sesuai

                        // Tentukan warna berdasarkan bagian yang dipilih
                        switch (selectedSection) {
                            case 'CUTTING':
                                color = '#e74c3c';
                                break;
                            case 'MACHINING CUSTOM':
                                color = '#3498db';
                                break;
                            case 'MACHINING':
                                color = 'blue';
                                break;
                            case 'HEAT TREATMENT':
                                color = '#27ae60';
                                break;
                            default:
                                color = 'darkgrey'; // Warna default jika tidak ada yang cocok
                        }

                        if (!repairMaintenanceChart) {
                            repairMaintenanceChart = Highcharts.chart('repairMaintenance', {
                                chart: {
                                    type: 'column'
                                },
                                title: {
                                    text: 'Linestop (Dalam Menit)'
                                },
                                xAxis: {
                                    categories: labels
                                },
                                yAxis: {
                                    min: 0,
                                    title: {
                                        text: 'Waktu (menit)'
                                    }
                                },
                                credits: {
                                    enabled: false
                                },
                                series: [{
                                    name: 'Line Stop (Dalam Menit)',
                                    data: data2,
                                    color: color // Gunakan warna yang telah ditentukan
                                }]
                            });
                        } else {
                            repairMaintenanceChart.xAxis[0].setCategories(labels, false);
                            repairMaintenanceChart.series[0].setData(data2, true);
                            repairMaintenanceChart.series[0].update({
                                color: color
                            }); // Update warna
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        // Handle error here
                    }
                });

                // Call function to update the period of work completion chart
                updatePeriodeWaktuPengerjaan();
            }

            /// Inisialisasi chart periode waktu pengerjaan dengan data default
            var ctxPeriode = document.getElementById('periodeRepair').getContext('2d');
            var periodeRepair = new Chart(ctxPeriode, {
                type: 'bar',
                data: {
                    labels: ['Line Stop (Dalam Menit)'], // Label waktu pengerjaan saja
                    datasets: [{
                        label: 'Line Stop (Dalam menit)', // Label dataset
                        data: [
                            {!! json_encode($periodeWaktuPengerjaan) !!}
                        ], // Data waktu pengerjaan akan diisi setelah permintaan AJAX berhasil
                        backgroundColor: 'red', // Merah terang dengan opacity 0.6
                        borderColor: 'rgba(255, 99, 132, 0.6)', // Merah terang tanpa opacity
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Fungsi untuk memperbarui chart periode waktu pengerjaan
            function updatePeriodeWaktuPengerjaan() {
                var selectedSection = document.getElementById('section-dropdown').value;
                var selectedYear = document.getElementById('date-dropdown2').value;
                var startMonth = document.getElementById('start_month2').value;
                var endMonth = document.getElementById('end_month2').value;

                // Lakukan AJAX request untuk mendapatkan data periode waktu pengerjaan berdasarkan section dan tanggal yang dipilih
                $.ajax({
                    url: '/getPeriodeWaktuPengerjaan',
                    method: 'GET',
                    data: {
                        year: selectedYear,
                        section: selectedSection,
                        start_month2: startMonth,
                        end_month2: endMonth
                    },
                    success: function(response) {
                        // Perbarui data chart dengan data baru
                        periodeRepair.data.datasets[0].data = [response.total_minute];

                        // Tentukan warna berdasarkan bagian yang dipilih
                        var color;
                        switch (selectedSection) {
                            case 'CUTTING':
                                color = '#e74c3c';
                                break;
                            case 'MACHINING CUSTOM':
                                color = '#3498db';
                                break;
                            case 'MACHINING':
                                color = 'blue';
                                break;
                            case 'HEAT TREATMENT':
                                color = '#27ae60';
                                break;
                            default:
                                color = 'darkgrey'; // Warna default jika tidak ada yang cocok
                        }
                        // Update warna dataset
                        periodeRepair.data.datasets[0].backgroundColor = color;
                        periodeRepair.data.datasets[0].borderColor = color;

                        periodeRepair.update();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        // Handle error here
                    }
                });
            }


            // Event handler untuk perubahan pada dropdown section
            document.getElementById('section-dropdown').addEventListener('change', function() {
                updateChart2();
                updatePeriodeWaktuPengerjaan(); // Panggil fungsi untuk memperbarui periode waktu pengerjaan
            });

            // Event handler untuk perubahan pada dropdown section
            document.getElementById('date-dropdown2').addEventListener('change', function() {
                updateChart2();
                updatePeriodeWaktuPengerjaan(); // Panggil fungsi untuk memperbarui periode waktu pengerjaan
            });


            document.getElementById('start_month2').addEventListener('change', function() {
                updateChart2();
                updatePeriodeWaktuPengerjaan(); // Panggil fungsi untuk memperbarui periode waktu pengerjaan
            });

            document.getElementById('end_month2').addEventListener('change', function() {
                updateChart2();
                updatePeriodeWaktuPengerjaan(); // Panggil fungsi untuk memperbarui periode waktu pengerjaan
            });
        </script>

        <!-- Repair Maintenance Alat Bantu -->
        <script>
            // Inisialisasi chart dengan data default
            var repairAlatBantu;

            // Inisialisasi dropdown tahun saat halaman dimuat
            document.addEventListener('DOMContentLoaded', function() {
                let dateDropdown = document.getElementById('date-dropdown-alat');
                let currentYear = new Date().getFullYear();
                let earliestYear = 2020; // Tahun awal yang diinginkan
                while (currentYear >= earliestYear) {
                    let dateOption = document.createElement('option');
                    dateOption.text = currentYear;
                    dateOption.value = currentYear;
                    dateDropdown.add(dateOption);
                    currentYear -= 1;
                }
                // Panggil updateChart2() untuk memuat data awal
                updateChartAlat();
            });

            // Event handler untuk perubahan pada dropdown tahun
            document.getElementById('date-dropdown-alat').addEventListener('change', function() {
                updateChartAlat();
            });

            function updateChartAlat() {
                var selectedYear = document.getElementById('date-dropdown-alat').value;
                var selectedSection = document.getElementById('section-dropdown-alat').value;

                // Perform AJAX request to get new data based on selected year and section
                $.ajax({
                    url: '/getRepairAlatBantu', // Replace with appropriate endpoint URL
                    method: 'GET',
                    data: {
                        year: selectedYear,
                        section: selectedSection
                    },
                    success: function(response) {
                        var labels = response.labels;
                        var data2 = response.data2;

                        var color; // Variabel untuk menyimpan warna yang sesuai

                        // Tentukan warna berdasarkan bagian yang dipilih
                        switch (selectedSection) {
                            case 'CUTTING':
                                color = '#e74c3c';
                                break;
                            case 'MACHINING CUSTOM':
                                color = '#3498db';
                                break;
                            case 'MACHINING':
                                color = 'blue';
                                break;
                            case 'HEAT TREATMENT':
                                color = '#27ae60';
                                break;
                            default:
                                color = 'darkgrey'; // Warna default jika tidak ada yang cocok
                        }

                        if (!repairAlatBantu) {
                            repairAlatBantu = Highcharts.chart('repairAlatBantu', {
                                chart: {
                                    type: 'column'
                                },
                                title: {
                                    text: 'Linestop (Dalam Menit)'
                                },
                                xAxis: {
                                    categories: labels
                                },
                                yAxis: {
                                    min: 0,
                                    title: {
                                        text: 'Waktu (menit)'
                                    }
                                },
                                credits: {
                                    enabled: false
                                },
                                series: [{
                                    name: 'Line Stop (Dalam Menit)',
                                    data: data2,
                                    color: color // Gunakan warna yang telah ditentukan
                                }]
                            });
                        } else {
                            repairAlatBantu.xAxis[0].setCategories(labels, false);
                            repairAlatBantu.series[0].setData(data2, true);
                            repairAlatBantu.series[0].update({
                                color: color
                            }); // Update warna
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        // Handle error here
                    }
                });

                // Call function to update the period of work completion chart
                updatePeriodeWaktuAlat();


            }

            /// Inisialisasi chart periode waktu pengerjaan dengan data default
            var ctxPeriode = document.getElementById('periodeRepairAlat').getContext('2d');
            var periodeWaktuAlat = new Chart(ctxPeriode, {
                type: 'bar',
                data: {
                    labels: ['Line Stop (Dalam Menit)'], // Label waktu pengerjaan saja
                    datasets: [{
                        label: 'Line Stop (Dalam menit)', // Label dataset
                        data: [
                            {!! json_encode($periodeWaktuAlat) !!}
                        ], // Data waktu pengerjaan akan diisi setelah permintaan AJAX berhasil
                        backgroundColor: 'red', // Merah terang dengan opacity 0.6
                        borderColor: 'rgba(255, 99, 132, 0.6)', // Merah terang tanpa opacity
                        borderWidth: 1
                    }]
                },

                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Fungsi untuk memperbarui chart periode waktu pengerjaan
            function updatePeriodeWaktuAlat() {
                var selectedSection = document.getElementById('section-dropdown-alat').value;
                var selectedYear = document.getElementById('date-dropdown-alat').value;
                var startMonth = document.getElementById('start_month_alat').value;
                var endMonth = document.getElementById('end_month_alat').value;

                // Lakukan AJAX request untuk mendapatkan data periode waktu pengerjaan berdasarkan section dan tanggal yang dipilih
                $.ajax({
                    url: '/getPeriodeWaktuAlat',
                    method: 'GET',
                    data: {
                        year: selectedYear,
                        section: selectedSection,
                        start_month_alat: startMonth,
                        end_month_alat: endMonth
                    },
                    success: function(response) {
                        // Perbarui data chart dengan data baru
                        periodeWaktuAlat.data.datasets[0].data = [response.total_minute];

                        // Tentukan warna berdasarkan bagian yang dipilih
                        var color;
                        switch (selectedSection) {
                            case 'CUTTING':
                                color = '#e74c3c';
                                break;
                            case 'MACHINING CUSTOM':
                                color = '#3498db';
                                break;
                            case 'MACHINING':
                                color = 'blue';
                                break;
                            case 'HEAT TREATMENT':
                                color = '#27ae60';
                                break;
                            default:
                                color = 'darkgrey'; // Warna default jika tidak ada yang cocok
                        }
                        // Update warna dataset
                        periodeWaktuAlat.data.datasets[0].backgroundColor = color;
                        periodeWaktuAlat.data.datasets[0].borderColor = color;

                        periodeWaktuAlat.update();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        // Handle error here
                    }
                });
            }


            // Event handler untuk perubahan pada dropdown section
            document.getElementById('section-dropdown-alat').addEventListener('change', function() {
                updateChartAlat();
                updatePeriodeWaktuAlat();
            });

            // Event handler untuk perubahan pada dropdown section
            document.getElementById('date-dropdown-alat').addEventListener('change', function() {
                updateChartAlat();
                updatePeriodeWaktuAlat();
            });


            document.getElementById('start_month_alat').addEventListener('change', function() {
                updateChartAlat();
                updatePeriodeWaktuAlat();
            });

            document.getElementById('end_month_alat').addEventListener('change', function() {
                updateChartAlat();
                updatePeriodeWaktuAlat();
            });
        </script>

        <script>
            function updateChartPeriodeAlat() {
                var section_alat = document.getElementById('section-dropdown-periode-alat').value;
                var startDate_alat = document.getElementById('start_alat').value;
                var endDate_alat = document.getElementById('end_alat').value;

                $.ajax({
                    url: '/getPeriodeAlat',
                    type: 'GET',
                    data: {
                        section_alat: section_alat,
                        start_alat: startDate_alat,
                        end_alat: endDate_alat
                    },
                    success: function(response) {
                        drawChartPeriodeAlat(response);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }

            // Fungsi untuk menggambar grafik linestop alat bantu menggunakan data yang diterima
            function drawChartPeriodeAlat(data) {
                var categories_alat = []; // Array untuk menyimpan kategori (nomor mesin)
                var seriesData_alat = []; // Array untuk menyimpan data series

                // Tentukan warna yang sesuai untuk setiap section
                var colors = {
                    'cutting': '#e74c3c',
                    'machining custom': '#3498db',
                    'machining': 'blue',
                    'heat treatment': '#27ae60'
                };

                // Membuat data series berdasarkan section dengan warna yang sesuai
                data.forEach(item => {
                    // Menambahkan kategori (nomor mesin) dan data series
                    categories_alat.push(item.mesin);
                    var color = colors[item.section.toLowerCase()] ||
                        'gray'; // Gunakan warna sesuai dengan section, jika tidak ada gunakan warna default
                    seriesData_alat.push({
                        name: item.mesin + ' (' + item.section + ')',
                        y: parseFloat(item.total_minutes_alat),
                        color: color
                    });
                });

                // Menggambar grafik menggunakan Highcharts JS
                Highcharts.chart('periodeAlat', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Detail Linestop / Alat Bantu (Dalam Menit)'
                    },
                    xAxis: {
                        categories: categories_alat
                    },
                    yAxis: {
                        title: {
                            text: 'Total Menit'
                        }
                    },
                    series: [{
                        name: 'Line Stop (Dalam menit)',
                        data: seriesData_alat // Menggunakan data yang sudah disesuaikan warnanya
                    }],
                    credits: { // Configuration to disable credits
                        enabled: false
                    },
                    plotOptions: {
                        column: {
                            colorByPoint: true // Mengatur agar warna sesuai dengan point (data) pada sumbu-x
                        }
                    }
                });
            }

            // Memanggil fungsi updateChartPeriodeAlat() untuk menginisialisasi grafik linestop alat bantu
            updateChartPeriodeAlat();
        </script>



        <script>
            // Fungsi untuk memuat data dari server dan menggambar grafik
            function updateChartPeriodeMesin() {
                var section = document.getElementById('section-dropdown2').value;
                var startDate = document.getElementById('start_mesin').value;
                var endDate = document.getElementById('end_mesin').value;

                // AJAX request untuk mendapatkan data dari server
                $.ajax({
                    url: '/getPeriodeMesin',
                    type: 'GET',
                    data: {
                        section: section,
                        start_mesin: startDate,
                        end_mesin: endDate
                    },
                    success: function(response) {
                        drawChart(response);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }

            // Fungsi untuk menggambar grafik menggunakan Highcharts JS
            function drawChart(data) {
                console.log(data);
                var categories = []; // Array untuk menyimpan kategori (nomor mesin)
                var seriesData = []; // Array untuk menyimpan data series

                // Tentukan warna yang sesuai untuk setiap section
                var colors = {
                    'CUTTING': '#e74c3c',
                    'MACHINING CUSTOM': '#3498db',
                    'MACHINING': 'blue',
                    'HEAT TREATMENT': '#27ae60'
                };

                // Membuat data series berdasarkan section dengan warna yang sesuai
                data.forEach(item => {
                    // Menambahkan kategori (nomor mesin) dan data series
                    categories.push(item.no_mesin);
                    var color = colors[item
                        .section]; // Gunakan warna sesuai dengan section, jika tidak ada gunakan warna default
                    seriesData.push({
                        name: item.no_mesin + ' (' + item.section + ')',
                        y: parseFloat(item.total_minutes),
                        color: color
                    });
                });

                // Menggambar grafik menggunakan Highcharts JS
                Highcharts.chart('periodeRepairMesin', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Detail Linestop / Mesin (Dalam Menit)'
                    },
                    xAxis: {
                        categories: categories
                    },
                    yAxis: {
                        title: {
                            text: 'Total Menit'
                        }
                    },
                    series: [{
                        name: 'Line Stop (Dalam menit)',
                        data: seriesData // Menggunakan data yang sudah disesuaikan warnanya
                    }],
                    plotOptions: {
                        column: {
                            colorByPoint: true // Mengatur agar warna sesuai dengan point (data) pada sumbu-x
                        }
                    }
                });
            }

            // Memanggil fungsi updateChartPeriodeMesin() untuk menginisialisasi grafik
            updateChartPeriodeMesin();
        </script>
    </main><!-- End #main -->
@endsection
