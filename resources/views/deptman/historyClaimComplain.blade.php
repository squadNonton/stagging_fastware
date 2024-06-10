@extends('layout')

@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Menu Riwayat Klaim dan Komplain</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboardHandling') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Menu Riwayat Klaim dan Komplain</a></li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        {{-- test push --}}
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Tampilan Data Riwayat Klaim dan Komplain</h5>
                            <div class="table-responsive" style="height: 100%; overflow-y: auto;">
                                <table class="datatable table">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="50px">NO</th>
                                            <th class="text-center" width="100px">NO WO</th>
                                            <th class="text-center" width="100px">PIC</th>
                                            <th class="text-center" width="100px">Kode Pelanggan</th>
                                            <th class="text-center" width="150px">Nama Pelanggan</th>
                                            <th class="text-center" width="100px">Area Pelanggan</th>
                                            <th class="text-center" width="100px">Tipe Bahan</th>
                                            <th class="text-center" width="30px">T</th>
                                            <th class="text-center" width="30px">W</th>
                                            <th class="text-center" width="30px">OD</th>
                                            <th class="text-center" width="30px">ID</th>
                                            <th class="text-center" width="30px">L</th>
                                            <th class="text-center" width="95px">Jumlah(/Kg)</th>
                                            <th class="text-center" width="98px">Jumlah(/Pcs)</th>
                                            <th class="text-center" width="105px">Kategori (NG)</th>
                                            <th class="text-center" width="100px">Tipe proses</th>
                                            <th class="text-center" width="95px">Jenis 1</th>
                                            <th class="text-center" width="95px">Jenis 2</th>
                                            <th class="text-center" width="100px">Pembaruan Terakhir</th>
                                            <th class="text-center" width="100px">Status</th>
                                            <th class="text-center" width="100px">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data2 as $row)
                                            <tr id="row_{{ $row->id }}">
                                                <td class="text-center py-3">{{ $loop->iteration }}</td>
                                                <td class="text-center py-3">{{ $row->no_wo }}</td>
                                                <td class="text-center py-3">{{ $row->user->name }}</td>
                                                <td class="text-center py-3">{{ $row->customers->customer_code }}</td>
                                                <td class="text-center py-3">{{ $row->customers->name_customer }}</td>
                                                <td class="text-center py-3">{{ $row->customers->area }}</td>
                                                <td class="text-center py-3">{{ $row->type_materials->type_name }}</td>
                                                <td class="text-center py-3">{{ $row->thickness }}</td>
                                                <td class="text-center py-3">{{ $row->weight }}</td>
                                                <td class="text-center py-3">{{ $row->outer_diameter }}</td>
                                                <td class="text-center py-3">{{ $row->inner_diameter }}</td>
                                                <td class="text-center py-3">{{ $row->length }}</td>
                                                <td class="text-center py-3">{{ $row->qty }}</td>
                                                <td class="text-center py-3">{{ $row->pcs }}</td>
                                                <td class="text-center py-3">{{ $row->category }}</td>
                                                <td class="text-center py-3">{{ $row->process_type }}</td>
                                                <td class="text-center py-3">{{ $row->type_1 }}</td>
                                                <td class="text-center py-3">{{ $row->type_2 }}</td>
                                                <td class="text-center py-3">{{ $row->created_at }}</td>
                                                <td class="text-center py-3">
                                                    @if ($row->status == 3)
                                                        <span class="badge bg-success align-items-center"
                                                            style="font-size: 18px;">Close</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('showCloseProgres', ['id' => $row->id]) }}"
                                                        class="btn btn-sm btn-warning" title="Riwayat Klaim dan Komplain">
                                                        <i class="fa fa-eye fa-1x" aria-hidden="true"></i>
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
