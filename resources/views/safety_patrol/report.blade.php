@extends('layout')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.20.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://code.highcharts.com/highcharts.js"></script>


    <main id="main" class="main">

        <section class="section">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div id="areaPatrolChart" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div id="picAreaChart" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div id="kategoriPatrolChart" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div id="safetyPatrolChart" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div id="lingkunganPatrolChart" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main><!-- End #main -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Area Patrol Chart
            Highcharts.chart('areaPatrolChart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Total Area Patrol'
                },
                xAxis: {
                    categories: @json($areaPatrolLabels),
                    title: {
                        text: 'Area Patrol'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Jumlah Area Patrol'
                    }
                },
                series: [{
                    name: 'Jumlah Area Patrol',
                    data: @json($areaPatrolCounts)
                }]
            });

            // PIC Area Chart
            Highcharts.chart('picAreaChart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Total PIC Area'
                },
                xAxis: {
                    categories: @json($picAreaLabels),
                    title: {
                        text: 'PIC Area'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Jumlah PIC'
                    }
                },
                series: [{
                    name: 'Jumlah PIC',
                    data: @json($picAreaCounts)
                }]
            });

            // Kategori Patrol Chart
            Highcharts.chart('kategoriPatrolChart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Kategori 5R/5S'
                },
                xAxis: {
                    categories: Object.keys(@json($kategoriCounts)),
                    title: {
                        text: 'Kategori 5R/5S'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Jumlah Nilai'
                    }
                },
                series: Object.keys(@json($kategoriCounts)).map((key, index) => ({
                    name: key,
                    data: Object.values(@json($kategoriCounts)[key])
                }))
            });

            // Safety Patrol Chart
            Highcharts.chart('safetyPatrolChart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Kategori Safety'
                },
                xAxis: {
                    categories: Object.keys(@json($safetyCounts)),
                    title: {
                        text: 'Kategori Safety'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Jumlah Nilai'
                    }
                },
                series: Object.keys(@json($safetyCounts)).map((key, index) => ({
                    name: key,
                    data: Object.values(@json($safetyCounts)[key])
                }))
            });

            // Lingkungan Patrol Chart
            Highcharts.chart('lingkunganPatrolChart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Kategori Lingkungan'
                },
                xAxis: {
                    categories: Object.keys(@json($lingkunganCounts)),
                    title: {
                        text: 'Kategori Lingkungan'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Jumlah Nilai'
                    }
                },
                series: Object.keys(@json($lingkunganCounts)).map((key, index) => ({
                    name: key,
                    data: Object.values(@json($lingkunganCounts)[key])
                }))
            });
        });
    </script>
@endsection
