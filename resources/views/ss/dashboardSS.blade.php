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
                            <h5 class="card-title">Suggestion System / Section<span></span></h5>
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
                                        <label for="end_periode">Employee:</label>
                                        <select id="type" class="form-select form-select-sm"
                                                aria-label=".form-select-sm example">
                                                <option selected>--- Pilih Employee ---</option>
                                                <option value="kategori"></option>
                                                <option value="type_1"></option>
                                                <option value="type_2"></option>
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
                            <div class="row">
                                <div id="chartAllPeriode" style="height:21.5vh; width:100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <!-- Load jQuery library -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script type="text/javascript">
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
                                    text: 'Jumlah Sumbang Saran berdasarkan Section'
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
        </script>
    </main><!-- End #main -->
@endsection
