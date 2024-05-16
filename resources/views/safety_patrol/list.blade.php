@extends('layout')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.20.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">


    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Karyawan</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Engineering</li>
                    <li class="breadcrumb-item active">Data patrol</li>
                </ol>
            </nav>

        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">List Form Safety Patrol</h5>
                            <div class="text-right mb-3">
                                <a class="btn btn-success float-right" href="{{ route('patrols.buatFormSafety') }}">
                                    <i class="bi bi-plus"></i> Tambah Form Safety Patrol
                                </a>
                            </div>

                            <!-- Table with stripped rows -->
                            <div class="table-responsive">
                                <table id="" class="datatable table">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Tanggal Patrol</th>
                                            <th scope="col">Area Patrol</th>
                                            <th scope="col">PIC Area</th>
                                            <th scope="col">Petugas Patrol</th>
                                            <!-- Kategori 5S/5R -->
                                            <th scope="col">Kelengkapan Alat 5S/5R</th>
                                            <th scope="col">Kerapihan Area Kerja</th>
                                            <th scope="col">Kondisi Lingkungan Kerja</th>
                                            <th scope="col">Penempatan Alat/Barang</th>
                                            <th scope="col">Praktik 5S/5R</th>
                                            <th scope="col">Catatan Kategori 5R/5S</th>
                                            <!-- Safety -->
                                            <th scope="col">Checksheet APAR</th>
                                            <th scope="col">Penggunaan APD</th>
                                            <th scope="col">Potensi Bahaya</th>
                                            <th scope="col">Pemeliharaan APD</th>
                                            <th scope="col">Kelengkapan APD</th>
                                            <th scope="col">Catatan Safety</th>
                                            <!-- Lingkungan -->
                                            <th scope="col">Pengelolaan Jenis & Kriteria Limbah</th>
                                            <th scope="col">Kebersihan Lingkungan</th>
                                            <th scope="col">Penyimpanan Limbah</th>
                                            <th scope="col">Tempat Sampah</th>
                                            <th scope="col">Catatan Lingkungan</th>
                                            <th scope="col">Modifikasi Terakhir</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($patrols as $patrol)
                                            <tr>
                                                <td>{{ ++$i }}</td>
                                                <td>{{ $patrol->tanggal_patrol }}</td>
                                                <td>{{ $patrol->area_patrol }}</td>
                                                <td>{{ $patrol->pic_area }}</td>
                                                <td>{{ $patrol->petugas_patrol }}</td>
                                                <td>{{ $patrol->kategori_1 }}</td>
                                                <td>{{ $patrol->kategori_2 }}</td>
                                                <td>{{ $patrol->kategori_3 }}</td>
                                                <td>{{ $patrol->kategori_4 }}</td>
                                                <td>{{ $patrol->kategori_5 }}</td>
                                                <td>{{ $patrol->kategori_catatan }}</td>
                                                <td>{{ $patrol->safety_1 }}</td>
                                                <td>{{ $patrol->safety_2 }}</td>
                                                <td>{{ $patrol->safety_3 }}</td>
                                                <td>{{ $patrol->safety_4 }}</td>
                                                <td>{{ $patrol->safety_5 }}</td>
                                                <td>{{ $patrol->safety_catatan }}</td>
                                                <td>{{ $patrol->lingkungan_1 }}</td>
                                                <td>{{ $patrol->lingkungan_2 }}</td>
                                                <td>{{ $patrol->lingkungan_3 }}</td>
                                                <td>{{ $patrol->lingkungan_4 }}</td>
                                                <td>{{ $patrol->lingkungan_catatan }}</td>
                                                <td>{{ $patrol->updated_at }}</td>
                                                <td>
                                                    {{-- <a class="btn btn-primary"
                                                        href="{{ route('patrols.edit', $patrol->id) }}">
                                                        <i class="bi bi-pencil-fill"></i>
                                                    </a> --}}
                                                    <a class="btn btn-warning"
                                                        href="{{ route('patrols.detailPatrol', $patrol->id) }}">
                                                        <i class="bi bi-eye-fill"></i>
                                                    </a>
                                                    {{-- <button type="button" class="btn btn-danger"
                                                        onclick="deletepatrol({{ $patrol->id }})">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                    <form id="patrolForm_{{ $patrol->id }}"
                                                        action="{{ route('patrols.destroy', $patrol->id) }}" method="POST"
                                                        style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form> --}}
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

        <div class="modal fade" id="patrolModal" tabindex="-1" aria-labelledby="patrolModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="patrolModalLabel">Form Lihat Patrol</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="patrolFormModal">
                            <!-- Isi formulir modal -->
                            <div class="mb-3">
                                <label for="tanggal_patrol_modal" class="form-label">Tanggal Patrol</label>
                                <input type="date" class="form-control" id="tanggal_patrol_modal"
                                    name="tanggal_patrol_modal" readonly>
                            </div>
                            <!-- Isi bagian lain dari formulir sesuai kebutuhan -->
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="submitModalBtn">Submit</button>
                    </div>
                </div>
            </div>
        </div>




    </main><!-- End #main -->
@endsection
