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
            // Fetch data from controller using AJAX
            fetch('/get-pic-area')
                .then(response => response.json())
                .then(data => {
                    // Extract data
                    const labels = data.labels;
                    const totalEntries = data.total_entries;
                    const areaPatrolData = data.area_patrol_data;

                    // Create chart
                    Highcharts.chart('picAreaChart', {
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: 'Total PIC Area'
                        },
                        xAxis: {
                            categories: labels,
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
                        tooltip: {
                            formatter: function() {
                                var tooltip = '<b>' + this.x + '</b><br/>'; // PIC area
                                tooltip += 'Total Form: ' + this.y +
                                    '<br/><br/>'; // Jumlah form secara keseluruhan

                                // Informasi form berdasarkan area patrol
                                tooltip += '<b>Detail Area Patrol:</b><br/>';
                                for (var area in areaPatrolData[this.x]) {
                                    tooltip += area + ': ' + areaPatrolData[this.x][area] + '<br/>';
                                }

                                return tooltip;
                            }
                        },
                        series: [{
                            name: 'Jumlah Form',
                            data: totalEntries
                        }]
                    });
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fetch data from controller using AJAX
            fetch('/get-area-patrol')
                .then(response => response.json())
                .then(data => {
                    // Extract data
                    const labels = data.labels;
                    const areaPatrols = data.area_patrols;

                    // Define colors for each data point
                    const colors = ['#7cb5ec', '#434348', '#90ed7d', '#f7a35c', '#8085e9'];

                    // Create chart
                    Highcharts.chart('areaPatrolChart', {
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: 'Total Area Patrol'
                        },
                        xAxis: {
                            categories: labels,
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
                            data: areaPatrols.map((value, index) => ({
                                y: value,
                                color: colors[index % colors
                                    .length] // Set color based on index
                            }))
                        }]
                    });
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fetch data from controller using AJAX
            fetch('/get-kategori-patrol')
                .then(response => response.json())
                .then(data => {
                    // Extract data
                    const kategoriCounts = data.kategori_counts;

                    // Initialize arrays to hold labels and counts
                    const labels = [];
                    const counts = [];

                    // Loop through kategoriCounts object
                    for (const label in kategoriCounts) {
                        if (kategoriCounts.hasOwnProperty(label)) {
                            labels.push(label); // Push label to labels array
                            counts.push(Object.values(kategoriCounts[label])); // Push counts for current label
                        }
                    }

                    // Create chart
                    Highcharts.chart('kategoriPatrolChart', {
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: 'Total Kategori Patrol'
                        },
                        xAxis: {
                            categories: labels,
                            title: {
                                text: 'Kategori'
                            }
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: 'Jumlah Kategori Patrol'
                            }
                        },
                        series: [{
                            name: '1',
                            data: counts.map(item => item[0]) // First value in counts array
                        }, {
                            name: '2',
                            data: counts.map(item => item[1]) // Second value in counts array
                        }, {
                            name: '3',
                            data: counts.map(item => item[2]) // Third value in counts array
                        }, {
                            name: '4',
                            data: counts.map(item => item[3]) // Fourth value in counts array
                        }, {
                            name: '5',
                            data: counts.map(item => item[4]) // Fifth value in counts array
                        }]
                    });
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fetch data from controller using AJAX
            fetch('/get-safety-patrol')
                .then(response => response.json())
                .then(data => {
                    // Extract data
                    const safetyCounts = data.safety_counts;

                    // Initialize arrays to hold labels and counts
                    const labels = [];
                    const counts = [];

                    // Loop through safetyCounts object
                    for (const label in safetyCounts) {
                        if (safetyCounts.hasOwnProperty(label)) {
                            labels.push(label); // Push label to labels array
                            counts.push(Object.values(safetyCounts[label])); // Push counts for current label
                        }
                    }

                    // Create chart
                    Highcharts.chart('safetyPatrolChart', {
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: 'Total Safety Patrol'
                        },
                        xAxis: {
                            categories: labels,
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: 'Jumlah Safety Patrol'
                            }
                        },
                        series: [{
                            name: '1',
                            data: counts.map(item => item[0]) // First value in counts array
                        }, {
                            name: '2',
                            data: counts.map(item => item[1]) // Second value in counts array
                        }, {
                            name: '3',
                            data: counts.map(item => item[2]) // Third value in counts array
                        }, {
                            name: '4',
                            data: counts.map(item => item[3]) // Fourth value in counts array
                        }, {
                            name: '5',
                            data: counts.map(item => item[4]) // Fifth value in counts array
                        }]
                    });
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fetch data from controller using AJAX
            fetch('/get-lingkungan-patrol')
                .then(response => response.json())
                .then(data => {
                    // Extract data
                    const lingkunganCounts = data.lingkungan_counts;

                    // Initialize arrays to hold labels and counts
                    const labels = [];
                    const counts = [];

                    // Loop through safetyCounts object
                    for (const label in lingkunganCounts) {
                        if (lingkunganCounts.hasOwnProperty(label)) {
                            labels.push(label); // Push label to labels array
                            counts.push(Object.values(lingkunganCounts[
                                label])); // Push counts for current label
                        }
                    }

                    // Create chart
                    Highcharts.chart('lingkunganPatrolChart', {
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: 'Total Lingkungan Patrol'
                        },
                        xAxis: {
                            categories: labels,
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: 'Jumlah Lingkungan Patrol'
                            }
                        },
                        series: [{
                            name: '1',
                            data: counts.map(item => item[0]) // First value in counts array
                        }, {
                            name: '2',
                            data: counts.map(item => item[1]) // Second value in counts array
                        }, {
                            name: '3',
                            data: counts.map(item => item[2]) // Third value in counts array
                        }, {
                            name: '4',
                            data: counts.map(item => item[3]) // Fourth value in counts array
                        }, {
                            name: '5',
                            data: counts.map(item => item[4]) // Fifth value in counts array
                        }]
                    });
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        });
    </script>
@endsection
