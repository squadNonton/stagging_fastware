@extends('layout')

@section('content')
    <main id="main" class="main">

        {{-- <div class="pagetitle">
            <h1>Halaman Tambah Data</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboardHandling') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">Menu Handling</a></li>
                    <li class="breadcrumb-item active">Halaman Tambah Data</li>
                </ol>
            </nav>
        </div> --}}
        <section class="section">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Inquiry Sales</h5>
                    <button class="btn btn-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#inquiryModal">Form
                        Inquiry</button>
                    <button class="btn btn-warning btn-sm mb-3" data-bs-toggle="modal"
                        data-bs-target="#inquiryModal">Report</button>

                    <!-- Table with stripped rows -->
                    <table class="table table-striped" id="inquiryTable">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Kode Inq.</th>
                                <th scope="col">Type Inq.</th>
                                <th scope="col">Type</th>
                                <th scope="col">Size</th>
                                <th scope="col">Supplier</th>
                                <th scope="col">Qty</th>
                                <th scope="col">Order From</th>
                                <th scope="col">Create By</th>
                                <th scope="col" class="text-center">To Approve</th>
                                <th scope="col" class="text-center">To Validate</th>
                                <th scope="col">Note</th>
                                <th scope="col">File</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inquiries as $inquiry)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $inquiry->kode_inquiry }}</td>
                                    <td>{{ $inquiry->jenis_inquiry }}</td>
                                    <td>{{ $inquiry->type }}</td>
                                    <td>{{ $inquiry->size }}</td>
                                    <td>{{ $inquiry->supplier }}</td>
                                    <td>{{ $inquiry->qty }}</td>
                                    <td>{{ $inquiry->order_from }}</td>
                                    <td>{{ $inquiry->create_by }}</td>
                                    <td class="text-center">
                                        <button
                                            class="btn btn-success btn-sm text-center">{{ $inquiry->to_approve }}</button>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-success btn-sm">{{ $inquiry->to_validate }}</button>
                                    </td>
                                    <td>{{ $inquiry->note }}</td>
                                    <td>
                                        @if ($inquiry->attach_file)
                                            <a href="{{ asset('storage/' . $inquiry->attach_file) }}" target="_blank">View
                                                File</a>
                                        @else
                                            No File
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-primary mt-1">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        <a class="btn btn-warning mt-1">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- End Table with stripped rows -->

                    <!-- Modal -->
                    <div class="modal fade" id="inquiryModal" tabindex="-1" aria-labelledby="inquiryModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="inquiryModalLabel">Form Inquiry</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('storeinquiry') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="jenis_inquiry" class="form-label">Jenis Inquiry</label>
                                            <select class="form-select" id="jenis_inquiry" name="jenis_inquiry" required>
                                                <option value="RO">RO</option>
                                                <option value="SPOR">SPOR</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="type" class="form-label">Type</label>
                                            <input type="text" class="form-control" id="type" name="type"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="size" class="form-label">Size</label>
                                            <input type="text" class="form-control" id="size" name="size"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="supplier" class="form-label">Supplier</label>
                                            <input type="text" class="form-control" id="supplier" name="supplier"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="qty" class="form-label">Qty</label>
                                            <input type="number" class="form-control" id="qty" name="qty"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="order_from" class="form-label">Order From</label>
                                            <input type="text" class="form-control" id="order_from" name="order_from"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="create_by" class="form-label">Create By</label>
                                            <input type="text" class="form-control" id="create_by" name="create_by"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="to_approve" class="form-label">To Approve</label>
                                            <input type="text" class="form-control" id="to_approve" name="to_approve"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="to_validate" class="form-label">To Validate</label>
                                            <input type="text" class="form-control" id="to_validate"
                                                name="to_validate" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="note" class="form-label">Note</label>
                                            <textarea class="form-control" id="note" name="note"></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="attach_file" class="form-label">Attach File</label>
                                            <input type="file" class="form-control" id="attach_file"
                                                name="attach_file">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal -->
                </div>
            </div>
        </section>

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <!-- SimpleDataTables JS -->
        <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const dataTable = new simpleDatatables.DataTable("#inquiryTable", {
                    searchable: true, // Aktifkan fitur pencarian
                    perPage: 10, // Jumlah entri data per halaman
                    perPageSelect: [5, 10, 20, 50], // Opsi jumlah entri data per halaman
                    dataProps: {
                        // Fungsi untuk menghasilkan format yang diinginkan
                        "Urutan": (value, data) => {
                            // Mendapatkan indeks baris data saat ini
                            const index = data.tableData.id;

                            // Mendapatkan nilai dari kolom "RO" atau "SPOR"
                            const spoOrRo = data[index][0].startsWith("RO") ? "RO" : "SPOR";

                            // Mendapatkan nilai dari kolom "Bulan"
                            const month = data[index][1];

                            // Mendapatkan nilai dari kolom "Tahun"
                            const year = data[index][2];

                            // Menghasilkan urutan sesuai format yang diinginkan
                            const order = (index + 1).toString().padStart(3, '0');
                            return `${spoOrRo}/${month}/${year}/${order}`;
                        }
                    }
                });
            });
        </script>

    </main><!-- End #main -->
@endsection
