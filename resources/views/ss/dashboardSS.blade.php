@extends('layout')

@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Dashboard Suggestion System</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboardHandling') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Suggestion System</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Suggestion System / Departemen<span></span></h5>
                            <div class="container">
                                <div class="row align-items-center">
                                    <div class="col-lg-3">
                                        <label for="start_periode">Bulan Mulai:</label>
                                        <input type="date" id="start_periode" name="start_periode" class="form-control">
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="end_periode">Bulan Akhir:</label>
                                        <input type="date" id="end_periode" name="end_periode" class="form-control">
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="end_periode">Bagian:</label>
                                        <select id="type" class="form-select form-select-sm"
                                            aria-label=".form-select-sm example" onclick="handleSelectChange()">
                                            <option selected>--- Pilih Bagian ---</option>
                                            <option value="TotalSS">Total SS</option>
                                            <option value="Sales">Sales</option>
                                            <option value="HT">HT</option>
                                            <option value="SupplyChainProduction">Supply Chain & Production</option>
                                            <option value="FinnAccHrgaIT">Finn Acc Hrga IT</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10%">
                                    <div class="col-lg-12">
                                        <div id="chartBarSection" style="min-width: 310px; height: 500px; margin: 0 auto">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Suggestion System / Employee</h5>
                            <div class="container">
                                <div class="row align-items-center">
                                    <div class="col-lg-3">
                                        <label for="tgl_pengajuan">Pilih Bulan:</label>
                                        <input type="month" id="tgl_pengajuan" name="tgl_pengajuan" class="form-control">
                                    </div>

                                    <div class="col-lg-3">
                                        <label for="employeeType">Employee</label>
                                        <select id="employeeType" class="form-select form-select-sm"
                                            aria-label=".form-select-sm example">
                                            <option selected>--- Pilih Employee ---</option>
                                            <option value="AllEmployee">All Employee</option>
                                            <option value="User">{{ Auth::user()->name }}</option>
                                        </select>
                                    </div>

                                    <div class="row" style="margin-top: 10%">
                                        <div class="col-lg-12">
                                            <div id="chartBarAllEmployee"
                                                style="min-width: 310px; height: 500px; margin: 0 auto"></div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Suggestion System / All Employee</h5>
                            <div class="container">
                                <div class="row align-items-center">
                                    <div class="col-lg-3">
                                        <label for="tgl_pengajuan">Pilih Bulan:</label>
                                        <input type="month" id="tgl_pengajuan_2" name="tgl_pengajuan_2"
                                            class="form-control">
                                    </div>
                                    <div class="row" style="margin-top: 10%">
                                        <div class="col-lg-12">
                                            <div id="chartBarMountEmployee"
                                                style="min-width: 310px; height: 500px; margin: 0 auto"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <!-- Load jQuery library -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            // Token CSRF disimpan di dalam tag <script>
            window.csrfToken = "{{ csrf_token() }}";
        </script>
        <script type="text/javascript">
            //chart 1
            $(document).ready(function() {
                function fetchChartData(startPeriode, endPeriode) {
                    $.ajax({
                        url: '{{ route('chartSection') }}', // Sesuaikan dengan route Anda
                        method: 'GET',
                        data: {
                            start_periode: startPeriode,
                            end_periode: endPeriode
                        },
                        success: function(response) {
                            var categories = response.categories;
                            var series = response.series;

                            Highcharts.chart('chartBarSection', {
                                chart: {
                                    type: 'column'
                                },
                                title: {
                                    text: 'Jumlah Sumbang Saran berdasarkan Departemen'
                                },
                                xAxis: {
                                    categories: categories,
                                    crosshair: true
                                },
                                yAxis: {
                                    min: 0,
                                    title: {
                                        text: 'Jumlah Sumbang Saran'
                                    }
                                },
                                series: series
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error: ' + status + error);
                        }
                    });
                }

                // Event listeners untuk input tanggal
                $('#start_periode, #end_periode').on('change', function() {
                    var startPeriode = $('#start_periode').val();
                    var endPeriode = $('#end_periode').val();
                    fetchChartData(startPeriode, endPeriode);
                });

                // Ambil data dan tampilkan chart saat halaman dimuat
                fetchChartData();
            });


            const roleData = {
                'Sales': ['SC Sales', 'UR Sales'],
                'HT': ['Engineering', 'UR Maintenance'],
                'SupplyChainProduction': ['UR Productions', 'SC Productions'],
                'FinnAccHrgaIT': ['UR Finance', 'SC Finance', 'SC HRGA', 'UR HRGA'],
                'TotalSS': [] // Tambahkan opsi TotalSS dengan array kosong
            };

            function handleSelectChange() {
                const selectedValue = $('#type').val();
                if (roleData[selectedValue]) {
                    const roles = roleData[selectedValue];
                    fetchChartData(roles);
                } else if (selectedValue === 'TotalSS') {
                    fetchTotalSumbangSaranData(); // Panggil fungsi untuk mengambil total data sumbangsaran
                } else {
                    $('#chartBarSection').html('<p>No data available</p>');
                }
            }

            function fetchChartData(roles) {
                $.ajax({
                    url: '{{ route('chartEmployee') }}',
                    method: 'POST',
                    data: {
                        roles: roles
                    },
                    headers: {
                        'X-CSRF-TOKEN': window.csrfToken
                    },
                    success: function(response) {
                        renderChart(response.data);
                    },
                    error: function(error) {
                        console.error('Error fetching data', error);
                    }
                });
            }

            function fetchTotalSumbangSaranData() {
                $.ajax({
                    url: '{{ route('chartEmployee') }}',
                    method: 'POST',
                    data: {
                        roles: []
                    },
                    headers: {
                        'X-CSRF-TOKEN': window.csrfToken
                    },
                    success: function(response) {
                        renderTotalSumbangSaranChart(response.data);
                    },
                    error: function(error) {
                        console.error('Error fetching data', error);
                    }
                });
            }

            function renderTotalSumbangSaranChart(data) {
                Highcharts.chart('chartBarSection', {
                    chart: {
                        type: 'bar'
                    },
                    title: {
                        text: 'Total Sumbang Saran Data'
                    },
                    xAxis: {
                        type: 'category',
                        title: {
                            text: 'Bagian'
                        }
                    },
                    yAxis: {
                        title: {
                            text: 'Count'
                        }
                    },
                    series: [{
                        name: 'Total Sumbang Saran',
                        data: data
                    }]
                });
            }

            function renderChart(data) {
                Highcharts.chart('chartBarSection', {
                    chart: {
                        type: 'bar'
                    },
                    title: {
                        text: 'Chart Per Bagian'
                    },
                    xAxis: {
                        type: 'category',
                        title: {
                            text: 'Bagian'
                        }
                    },
                    yAxis: {
                        title: {
                            text: 'Count'
                        }
                    },
                    series: [{
                        name: 'Roles',
                        data: data
                    }]
                });
            }

            // chart2
            const employeeData = {
                'AllEmployee': 'All Employee',
                'User': 'User'
            };

            function handleEmployeeTypeChange() {
                const selectedValue = $('#employeeType').val();
                const selectedMonth = $('#tgl_pengajuan').val();
                fetchEmployeeChartData(selectedValue, selectedMonth);
            }

            function fetchEmployeeChartData(employeeType, selectedMonth) {
                $.ajax({
                    url: '{{ route('chartUser') }}',
                    method: 'POST',
                    data: {
                        employeeType: employeeType,
                        month: selectedMonth
                    },
                    headers: {
                        'X-CSRF-TOKEN': window.csrfToken
                    },
                    success: function(response) {
                        renderEmployeeChart(response.data);
                    },
                    error: function(error) {
                        console.error('Error fetching data', error);
                    }
                });
            }

            function renderEmployeeChart(data) {
                Highcharts.chart('chartBarAllEmployee', {
                    chart: {
                        type: 'bar'
                    },
                    title: {
                        text: 'Chart Per Employee'
                    },
                    xAxis: {
                        type: 'category',
                        title: {
                            text: 'Employee'
                        }
                    },
                    yAxis: {
                        title: {
                            text: 'Jumalah SS'
                        }
                    },
                    series: [{
                        name: 'Jumlah SS',
                        data: data
                    }]
                });
            }

            $(document).ready(function() {
                // $('#tgl_pengajuan').on('change', handleEmployeeTypeChange);
                $('#employeeType').on('change', handleEmployeeTypeChange);
            });

            $(document).ready(function() {
                $('#tgl_pengajuan_2').on('change', function() {
                    handleFilterChange();
                });

                function handleFilterChange() {
                    const selectedMonth = $('#tgl_pengajuan_2').val();
                    fetchChartData(selectedMonth);
                }

                function fetchChartData(selectedMonth) {
                    console.log('Selected Month:', selectedMonth); // Cek apakah bulan yang dipilih tercetak di konsol
                    $.ajax({
                        url: '{{ route('chartMountEmployee') }}',
                        method: 'POST',
                        data: {
                            month: selectedMonth
                        },
                        headers: {
                            'X-CSRF-TOKEN': window.csrfToken
                        },
                        success: function(response) {
                            renderChart(response.data);
                        },
                        error: function(error) {
                            console.error('Error fetching data', error);
                        }
                    });
                }

                function renderChart(chartData) {
                    Highcharts.chart('chartBarMountEmployee', {
                        chart: {
                            type: 'bar'
                        },
                        title: {
                            text: 'Chart Per Employee'
                        },
                        xAxis: {
                            type: 'category',
                            title: {
                                text: 'Employee'
                            }
                        },
                        yAxis: {
                            title: {
                                text: 'Submission Count'
                            }
                        },
                        series: [{
                            name: 'Submissions',
                            data: chartData
                        }]
                    });
                }

                // Panggil fungsi fetchChartData saat halaman dimuat untuk pertama kali
                fetchChartData($('#tgl_pengajuan_2').val());
            });
        </script>
    </main><!-- End #main -->
@endsection
