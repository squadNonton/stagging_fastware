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
                    <h5 class="card-title">Approve Dept. Head</h5>

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
                                <th scope="col" class="text-center">is Active</th>
                                <th scope="col" class="text-center">Actions</th>
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
                                            class="btn btn-sm text-center {{ $inquiry->to_approve == 'Waiting' ? 'btn-warning' : ($inquiry->to_approve == 'Approved' ? 'btn-success' : 'btn-danger') }}">
                                            {{ $inquiry->to_approve }}
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        <button
                                            class="btn btn-sm {{ $inquiry->to_validate == 'Waiting' ? 'btn-warning' : ($inquiry->to_validate == 'Validated' ? 'btn-success' : 'btn-danger') }}">
                                            {{ $inquiry->to_validate }}
                                        </button>
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
                                        @if ($inquiry->status == 0)
                                            <button type="button" class="btn btn-danger" title="Data tidak aktif">
                                                <i class="bi bi-exclamation-octagon"></i>
                                            </button>
                                        @endif
                                    </td>

                                    <td>
                                        @if (
                                            $inquiry->status != 3 &&
                                                $inquiry->status != 4 &&
                                                $inquiry->status != 5 &&
                                                $inquiry->status != 6 &&
                                                $inquiry->status != 7)
                                            <a class="btn btn-success mt-1" title="Edit">
                                                <i class="bi bi-check2-all"
                                                    onclick="openViewInquiryModal({{ $inquiry->id }})"></i>
                                            </a>
                                        @endif
                                        <a class="btn btn-warning mt-1" title="View Form">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- approval -->
                    <!-- approval -->
                    <div class="modal fade" id="viewInquiryModal" tabindex="-1" aria-labelledby="viewInquiryModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="viewInquiryModalLabel">Form Approved Inquiry</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="viewInquiryForm"
                                        action="{{ route('approvedInquiry', ['id' => $inquiry->id]) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" id="viewInquiryId" name="inquiry_id">
                                        <input type="hidden" id="action_type" name="action_type">
                                        <div class="mb-3">
                                            <label for="viewjenis_inquiry" class="form-label">Jenis Inquiry</label>
                                            <select class="form-select" id="viewjenis_inquiry" name="jenis_inquiry" required
                                                disabled>
                                                <option value="RO">RO</option>
                                                <option value="SPOR">SPOR</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="viewtype" class="form-label">Type</label>
                                            <input type="text" class="form-control" id="viewtype" name="type"
                                                required disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label for="viewsize" class="form-label">Size</label>
                                            <input type="text" class="form-control" id="viewsize" name="size"
                                                required disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label for="viewsupplier" class="form-label">Supplier</label>
                                            <input type="text" class="form-control" id="viewsupplier" name="supplier"
                                                required disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label for="viewqty" class="form-label">Qty</label>
                                            <input type="number" class="form-control" id="viewqty" name="qty"
                                                required disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label for="vieworder_from" class="form-label">Order From</label>
                                            <input type="text" class="form-control" id="vieworder_from"
                                                name="order_from" required disabled>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary"
                                                onclick="setActionType('approved')">Approved</button>
                                            <button type="submit" class="btn btn-danger"
                                                onclick="setActionType('not_approved')">Not Approved</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        {{-- excel --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

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

            function openViewInquiryModal(id) {
                console.log('Opening modal for inquiry ID: ' + id); // Debugging log
                $.ajax({
                    url: '{{ route('editInquiry', ['id' => ':id']) }}'.replace(':id', id),
                    type: 'GET',
                    success: function(response) {
                        console.log('Response:', response); // Debugging log

                        // Isi form modal dengan data yang diperoleh
                        $('#viewkode_inquiry').val(response.kode_inquiry);
                        $('#viewjenis_inquiry').val(response.jenis_inquiry);
                        $('#viewtype').val(response.type);
                        $('#viewsize').val(response.size);
                        $('#viewsupplier').val(response.supplier);
                        $('#viewqty').val(response.qty);
                        $('#vieworder_from').val(response.order_from);
                        $('#viewcreate_by').val(response.create_by);
                        $('#viewto_approve').val(response.to_approve);
                        $('#viewto_validate').val(response.to_validate);
                        $('#viewnote').val(response.note);
                        $('#viewInquiryId').val(response.id);

                        // Update form action URL dengan ID
                        $('#viewInquiryForm').attr('action', '{{ route('approvedInquiry', ['id' => ':id']) }}'
                            .replace(':id', response.id));

                        if (response.attach_file) {
                            // Display the attached file (image or other)
                            var fileExtension = response.attach_file.split('.').pop().toLowerCase();
                            var fileLink = '{{ asset('assets/files/') }}/' + response.attach_file;

                            if (['jpg', 'jpeg', 'png'].includes(fileExtension)) {
                                $('#viewImagePreview').attr('src', fileLink).attr('width', '150').attr('height',
                                    '150').show();
                                $('#viewFilePreview').hide();
                                $('#viewFileName').text('');
                                $('#viewImagePreview').click(function() {
                                    showImageInModal(fileLink);
                                });
                            } else {
                                $('#viewImagePreview').hide();
                                $('#viewFilePreview').attr('href', fileLink).attr('download', response.attach_file)
                                    .show();
                                $('#viewFileName').text(response.attach_file);
                            }
                        } else {
                            // Jika tidak ada file terlampir
                            $('#viewImagePreview').hide();
                            $('#viewFilePreview').hide();
                            $('#viewFileName').text('');
                        }

                        // Display modal
                        $('#viewInquiryModal').modal('show');
                    },
                    error: function(xhr) {
                        // Handle error
                        console.log(xhr.responseText);
                    }
                });
            }

            function setActionType(actionType) {
                document.getElementById('action_type').value = actionType;
            }
        </script>
    </main><!-- End #main -->
@endsection