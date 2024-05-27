@extends('layout')

@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Form SS</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboardHandling') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Form SS</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Suggestion System / Section<span></span></h5>
                            <div>
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
                                <label for="yearDropdown">Pilih Employee:</label>
                                <select id='date-dropdown' style="width: 10%"></select>
                                <canvas id="myChart" style="height:24.5vh; width:100%"></canvas>
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
    </main><!-- End #main -->
@endsection
