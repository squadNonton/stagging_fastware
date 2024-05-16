@extends('layout')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.20.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">


    <main id="main" class="main">

        <div class="pagetitle">
            <h1>PIC</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">PIC</li>
                    <li class="breadcrumb-item active">List Safety Patrol</li>
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
                            {{-- <div class="text-end">
                                <a class="btn btn-success float-right" href="{{ route('patrol.export') }}">
                                    <i class="bi bi-filetype-xlsx"></i> Export Data
                                    Safety Patrol</a>
                            </div> --}}
                            <div class="text-end">
                                <button class="btn btn-success float-right" onclick="downloadExcel()">
                                    <i class="bi bi-filetype-xlsx"></i> Export Data Safety Patrol
                                </button>
                            </div>
                            <br>
                            <!-- Table with stripped rows -->
                            <div class="table-responsive">
                                <table id="tableToExport" class="datatable table table-bordered">
                                    <thead>
                                        <tr>
                                            <th scope="col" rowspan="2">No</th>
                                            <th scope="col" rowspan="2">Tgl</th>
                                            <th scope="col" rowspan="2">Area</th>
                                            <th scope="col" rowspan="2">PIC</th>
                                            <th scope="col" rowspan="2">Petugas Patrol</th>
                                            <!-- Kelompok Kategori 5S/5R -->
                                            <th scope="colgroup" colspan="6">Kategori 5S/5R</th>
                                            <!-- Kelompok Safety -->
                                            <th scope="colgroup" colspan="6">Safety</th>
                                            <!-- Kelompok Lingkungan -->
                                            <th scope="colgroup" colspan="5">Lingkungan</th>
                                            <th scope="col" rowspan="2">Modifikasi Terakhir</th>
                                            <th scope="col" rowspan="2">Aksi</th>
                                        </tr>
                                        <tr>
                                            <!-- Kategori 5S/5R -->
                                            <th scope="col">Kelengkapan Alat</th>
                                            <th scope="col">Kerapihan Area Kerja</th>
                                            <th scope="col">Kondisi Lingkungan Kerja</th>
                                            <th scope="col">Penempatan Alat/Barang</th>
                                            <th scope="col">Praktik 5S/5R</th>
                                            <th scope="col">Catatan Kategori 5S/5R</th>
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
                                                    <a class="btn btn-warning"
                                                        href="{{ route('patrols.detailPatrol', $patrol->id) }}">
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

        <style>
            .table-bordered th,
            .table-bordered td {
                border: 1px solid #dee2e6;
            }

            .table thead th {
                vertical-align: bottom;
                border-bottom: 2px solid #dee2e6;
            }

            .table tbody+tbody {
                border-top: 2px solid #dee2e6;
            }

            .table-bordered {
                border: 1px solid #dee2e6;
            }

            .datatable th,
            .datatable td {
                text-align: center;
                vertical-align: middle;
            }

            .datatable th[colspan] {
                text-align: center;
            }
        </style>
        <script>
            document.getElementById("exportButton").addEventListener("click", function() {
                downloadExcel();
            });

            function downloadExcel() {
                // Mengambil tabel berdasarkan ID
                var table = document.getElementById("tableToExport");
                if (!table) {
                    console.error("Tabel dengan ID 'tableToExport' tidak ditemukan.");
                    return;
                }
                // Hapus kolom "Aksi" dari tabel
                var rows = table.rows;
                for (var i = 0; i < rows.length; i++) {
                    rows[i].deleteCell(-1); // Menghapus sel terakhir dari setiap baris
                }
                // Membuat objek untuk WorkSheet baru
                var worksheet = XLSX.utils.table_to_sheet(table);
                // Membuat objek WorkBook baru
                var workbook = XLSX.utils.book_new();
                // Menambahkan WorkSheet ke dalam WorkBook
                XLSX.utils.book_append_sheet(workbook, worksheet, "Sheet1");
                // Mengkonversi WorkBook ke dalam format Excel
                var excelBuffer = XLSX.write(workbook, {
                    bookType: 'xlsx',
                    type: 'array'
                });
                // Mengkonversi buffer ke dalam blob Excel
                var blob = new Blob([excelBuffer], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8'
                });
                // Menghasilkan URL untuk file Excel
                var excelFileURL = URL.createObjectURL(blob);
                // Membuat link untuk download
                var a = document.createElement("a");
                a.href = excelFileURL;
                a.download = "Safety-Patrol.xlsx";
                // Menambahkan link ke dalam dokumen
                document.body.appendChild(a);
                // Mengklik link untuk mengunduh file
                a.click();
                // Membersihkan link setelah di klik
                document.body.removeChild(a);
            }
        </script>


    </main><!-- End #main -->
@endsection
