@extends('layout')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Data Tables</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item">Tables</li>
                <li class="breadcrumb-item active">Data</li>
            </ol>
        </nav>

    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">List Data Form FPP</h5>
                        <div class="table-responsive"> <!-- Add this div for better responsiveness -->
                            <table class="table table-striped table-bordered table-hover datatable">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Nomor FPP</th>
                                        <th scope="col">Mesin</th>
                                        <th scope="col">Section</th>
                                        <th scope="col">Lokasi</th>
                                        <th scope="col">Kendala</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Created Date</th>
                                        <th scope="col">Last Update</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($formperbaikans as $formperbaikan)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td> <!-- Use $loop->iteration for proper numbering -->
                                        <td>{{ $formperbaikan->id_fpp }}</td>
                                        <td>{{ $formperbaikan->mesin }}</td>
                                        <td>{{ $formperbaikan->section }}</td>
                                        <td>{{ $formperbaikan->lokasi }}</td>
                                        <td>{{ $formperbaikan->kendala }}</td>
                                        <td>
                                            <div style="background-color: {{ $formperbaikan->status_background_color }};
                                            border-radius: 5px; padding: 5px 10px; color: white; font-weight: bold; text-align: center; text-transform: uppercase;">
                                                {{ $formperbaikan->ubahtext() }}
                                            </div>
                                        </td>
                                        <td>{{ $formperbaikan->created_at }}</td>
                                        <td>{{ $formperbaikan->updated_at }}</td>
                                        <td>
                                            <a class="btn btn-warning" href="{{ route('sales.lihat', $formperbaikan->id) }}">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->
@endsection