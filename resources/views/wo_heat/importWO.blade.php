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
                    <li class="breadcrumb-item active">Import WO</li>
                </ol>
            </nav>

        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-center">Import WO Heat Treatment</h5>

                            <form action="{{ route('importWO') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <input type="file" name="excelFile" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-danger mt-3">
                                    <i class="bi bi-upload"></i> Import Data
                                </button>
                            </form>

                            @if (isset($data))
                                <!-- Table for imported data -->
                                <div class="table-responsive">
                                    <table class="datatables datatable" style="table-layout: responsive;">
                                        <thead>
                                            <tr>
                                                @foreach ($data[0] as $cell)
                                                    <th scope="col">{{ $cell['value'] }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $rowIndex => $row)
                                                @if ($rowIndex > 0)
                                                    <tr>
                                                        @foreach ($row as $cell)
                                                            <td colspan="{{ $cell['colspan'] }}"
                                                                rowspan="{{ $cell['rowspan'] }}">
                                                                {{ $cell['value'] }}
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                            <!-- Table for heat treatments -->
                            <div class="table-responsive">
                                <table class="datatables datatable" style="table-layout: responsive;">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">No.WO</th>
                                            <th scope="col">No.SO</th>
                                            <th scope="col">Tgl. WO</th>
                                            <th scope="col">Area</th>
                                            <th scope="col">Kode</th>
                                            <th scope="col">Cust</th>
                                            <th scope="col">Proses</th>
                                            <th scope="col">Pcs</th>
                                            <th scope="col">Kg</th>
                                            <th scope="col">Status WO</th>
                                            <th scope="col">No.DO</th>
                                            <th scope="col">Status DO</th>
                                            <th scope="col">Tgl.ST</th>
                                            <th scope="col">Supir</th>
                                            <th scope="col">Penerima</th>
                                            <th scope="col">Tgl.Terima</th>
                                            <th scope="col">Modifikasi Terakhir</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($heattreatments as $index => $data)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $data->no_wo }}</td>
                                                <td>{{ $data->no_so }}</td>
                                                <td>{{ $data->tgl_wo }}</td>
                                                <td>{{ $data->area }}</td>
                                                <td>{{ $data->kode }}</td>
                                                <td>{{ $data->cust }}</td>
                                                <td>{{ $data->proses }}</td>
                                                <td>{{ $data->pcs }}</td>
                                                <td>{{ $data->kg }}</td>
                                                <td>{{ $data->status_wo }}</td>
                                                <td>{{ $data->no_do }}</td>
                                                <td>{{ $data->status_do }}</td>
                                                <td>{{ $data->tgl_st }}</td>
                                                <td>{{ $data->supir }}</td>
                                                <td>{{ $data->penerima }}</td>
                                                <td>{{ $data->tgl_terima }}</td>
                                                <td>{{ $data->updated_at }}</td>
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
