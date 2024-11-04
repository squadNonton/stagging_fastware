@extends('layout')

@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Menu Form Pengajuan User</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboardHandling') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Menu Pengajuan FPB</li>

                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Tampilan Data Form Permintaan Barang/Jasa</h5>
                            <!-- Table with stripped rows -->
                            <div class="table-responsive" style="height: 100%; overflow-y: auto;">
                                <button id="kirimSemuaData" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i> Kirim Semua FPB
                                </button>
                                <table class="datatable table">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="50px">NO</th>
                                            <th class="text-center" width="50px">
                                                <input type="checkbox" id="selectAll" />
                                                <!-- Checkbox untuk memilih semua -->
                                            </th>
                                            <th class="text-center" width="100px">NO FPB</th>
                                            <th class="text-center" width="100px">PIC</th>
                                            <th class="text-center" width="100px">Kategori</th>
                                            <th class="text-center" width="100px">Catatan</th>
                                            <th class="text-center" width="100px">Tgl Pembaruan</th>
                                            <th class="text-center" width="100px">Status</th>
                                            <th class="text-center" width="100px">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $row)
                                            @if ($row->status_1 == 3)
                                                <!-- Tambahkan kondisi untuk menampilkan hanya jika status_1 == 2 -->
                                                <tr>
                                                    <td class="text-center py-3">{{ $loop->iteration }}</td>
                                                    <td class="text-center">
                                                        @if (!in_array($row->status_1, [5, 6, 7, 8, 9, 10]))
                                                            <input type="checkbox" class="selectRow"
                                                                data-no_fpb="{{ $row->no_fpb }}" />
                                                        @endif
                                                    </td>
                                                    <td class="text-center py-3">{{ $row->no_fpb }}</td>
                                                    <td class="text-center py-3">{{ $row->modified_at }}</td>
                                                    <td class="text-center py-3">{{ $row->kategori_po }}</td>
                                                    <td class="text-center py-3"><b>{{ $row->catatan }}</b></td>
                                                    <td class="text-center py-3"><b>{{ $row->trs_updated_at }}</b></td>
                                                    <td class="text-center py-4"
                                                        style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                        @if ($row->status_1 == 1)
                                                            <span class="badge bg-secondary align-items-center"
                                                                style="font-size: 18px;">Draf</span>
                                                        @elseif ($row->status_1 == 2)
                                                            <span class="badge bg-success align-items-center"
                                                                style="font-size: 18px;">Open</span>
                                                        @elseif ($row->status_1 == 3)
                                                            <span class="badge bg-success align-items-center"
                                                                style="font-size: 18px;">Open</span>
                                                        @elseif($row->status_1 == 4)
                                                            <span class="badge bg-success align-items-center"
                                                                style="font-size: 18px;">Open</span>
                                                        @elseif($row->status_1 == 5)
                                                            <span class="badge bg-success align-items-center"
                                                                style="font-size: 18px;">Open</span>
                                                        @elseif($row->status_1 == 6)
                                                            <span class="badge bg-success align-items-center"
                                                                style="font-size: 18px;">Open</span>
                                                        @elseif($row->status_1 == 7)
                                                            <span class="badge bg-success align-items-center"
                                                                style="font-size: 18px;">Open</span>
                                                        @elseif($row->status_1 == 8)
                                                            <span class="badge bg-success align-items-center"
                                                                style="font-size: 18px;">Open</span>
                                                        @elseif($row->status_1 == 9)
                                                            <span class="badge bg-info align-items-center"
                                                                style="font-size: 18px;">Finish</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center py-4">
                                                        <a href="{{ route('view.FormPo.3', ['id' => $row->id]) }}"
                                                            class="btn btn-primary btn-sm" title="View Form">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-success btn-sm btn-kirim"
                                                            data-no_fpb="{{ $row->no_fpb }}" title="Kirim">
                                                            <i class="fas fa-paper-plane"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('kirimSemuaData').addEventListener('click', function() {
                    // Ambil semua checkbox yang dipilih
                    const selectedCheckboxes = document.querySelectorAll('.selectRow:checked');

                    // Periksa apakah ada checkbox yang dipilih
                    if (selectedCheckboxes.length === 0) {
                        Swal.fire('Pilih data terlebih dahulu!', '', 'warning');
                        return;
                    }

                    // Ambil semua no_fpb dari checkbox yang dipilih
                    let noFpbArray = [];
                    selectedCheckboxes.forEach(checkbox => {
                        noFpbArray.push(checkbox.getAttribute('data-no_fpb').replace(/\//g, '-'));
                    });

                    // Tampilkan pesan konfirmasi
                    Swal.fire({
                        title: 'Apakah anda ingin mengirim semua data?',
                        text: "Data yang telah dikirim tidak dapat dirubah!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, kirim!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Lakukan AJAX POST request untuk setiap no_fpb yang dipilih
                            noFpbArray.forEach(no_fpb => {
                                $.ajax({
                                    url: "{{ route('kirim.fpb.user', ':no_fpb') }}"
                                        .replace(':no_fpb', no_fpb),
                                    type: 'POST',
                                    data: {
                                        _token: '{{ csrf_token() }}', // CSRF Token Laravel
                                    },
                                    success: function(response) {
                                        console.log("Response from server:",
                                            response);
                                    },
                                    error: function(xhr) {
                                        console.log("Error occurred:", xhr
                                            .responseText);
                                    }
                                });
                            });

                            // Tampilkan pesan sukses dan refresh halaman setelah semua request berhasil
                            Swal.fire('Terkirim!', 'Semua data berhasil dikirim.', 'success').then(
                                () => {
                                    location.reload();
                                });
                        }
                    });
                });

                document.querySelectorAll('.btn-kirim').forEach(button => {
                    button.addEventListener('click', function() {
                        // Mengambil no_fpb dari data attribute dan mengganti "/" dengan "-"
                        var no_fpb = this.getAttribute('data-no_fpb').replace(/\//g, '-');
                        console.log("FPB Number to send:", no_fpb); // Log FPB number yang diambil

                        Swal.fire({
                            title: 'Apakah anda ingin mengirim data?',
                            text: "Data yang telah dikirim tidak dapat dirubah!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ya, kirim!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Jika konfirmasi, lakukan AJAX POST request
                                $.ajax({
                                    url: "{{ route('kirim.fpb.user', ':no_fpb') }}"
                                        .replace(':no_fpb', no_fpb),
                                    type: 'POST',
                                    data: {
                                        _token: '{{ csrf_token() }}', // CSRF Token Laravel
                                    },
                                    success: function(response) {
                                        console.log("Response from server:",
                                            response); // Log response dari server

                                        Swal.fire(
                                            'Terkirim!',
                                            'Data berhasil dikirim.',
                                            'success'
                                        ).then(() => {
                                            location
                                                .reload(); // Refresh halaman setelah sukses
                                        });
                                    },
                                    error: function(xhr) {
                                        console.log("Error occurred:", xhr
                                            .responseText
                                        ); // Log error jika terjadi kesalahan

                                        Swal.fire(
                                            'Gagal!',
                                            'Terjadi kesalahan saat mengirim data.',
                                            'error'
                                        );
                                    }
                                });
                            }
                        });
                    });
                });
            });

            // Fungsi untuk memilih semua checkbox
            document.getElementById('selectAll').addEventListener('change', function() {
                document.querySelectorAll('.selectRow').forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        </script>

    </main><!-- End #main -->
@endsection
