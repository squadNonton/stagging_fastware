@extends('layout')
@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Production</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Form Permintaan Perbaikan</li>
                <li class="breadcrumb-item">Data FPP</li>
            </ol>
        </nav>

    </div><!-- End Page Title -->
    <section class="section">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data Form Permintaan Perbaikan</h5>
                    <div class="text-right mb-3">
                        <a class="btn btn-success float-right" href="{{ route('formperbaikans.create') }}">Buat Form</a>
                    </div>
                    <!-- Table with stripped rows -->
                    <div class="table-responsive">
                        <table id="" class="display" style="table-layout: fixed;">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nomor FPP</th>
                                    <th scope="col">Mesin</th>
                                    <th scope="col">Section</th>
                                    <th scope="col">Lokasi</th>
                                    <th scope="col">Kendala</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Tanggal Dibuat</th>
                                    <th scope="col">Last Update</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($formperbaikans as $formperbaikan)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $formperbaikan->id_fpp }}</td>
                                    <td>{{ $formperbaikan->mesin }}</td>
                                    <td>{{ $formperbaikan->section }}</td>
                                    <td>{{ $formperbaikan->lokasi }}</td>
                                    <td>{{ $formperbaikan->kendala }}</td>
                                    <td>
                                        <div style="background-color: {{ $formperbaikan->status_background_color }};
                                        border-radius: 5px; /* Rounded corners */
                                        padding: 5px 10px; /* Padding inside the div */
                                        color: white; /* Text color, adjust as needed */
                                        font-weight: bold; /* Bold text */
                                        text-align: center; /* Center-align text */
                                        text-transform: uppercase; /* Uppercase text */
                                    ">
                                            {{ $formperbaikan->ubahtext() }}
                                        </div>
                                    </td>

                                    <td>{{ $formperbaikan->created_at }}</td>
                                    <td>{{ $formperbaikan->updated_at }}</td>
                                    <td>
                                        <a class="btn btn-warning" href="{{ route('fpps.show', $formperbaikan->id) }}">
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
    </section>
</main><!-- End #main -->


@endsection
