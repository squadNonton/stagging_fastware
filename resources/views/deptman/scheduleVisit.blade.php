@extends('layout')

@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Menu Jadwal Kunjungan</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboardHandling') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Menu Jadwal Kunjungan</a></li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Jadwal Kunjungan</h5>
                        <div id='calendar'></div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Modal -->
        <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="eventModalLabel">Detail Jadwal Kunjungan</h5>
                    </div>
                    <div class="modal-body">
                        <div id="eventDetails"></div>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('scheduleVisit') }}" class="btn btn-secondary" data-dismiss="modal">Close</a>
                    </div>

                </div>
            </div>
        </div>



    </main><!-- End #main -->
@endsection
@section('scripts')
    <script src="{{ asset('assets/js/viewcalendar.js') }}"></script>
    <script>
        var scheduleVisits = {!! json_encode($scheduleVisits) !!};
    </script>
@endsection
