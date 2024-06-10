@extends('layout')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="sha512-qzQ9pyH1/Nkq4ysbr8yjBq44xDG/BaUkmUamJsIviGniGRC3plUSllPPe9wCJlY6k4t5IfMEO/A7R5Q2TDe2iQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <main id="main" class="main">
        <!-- Existing content... -->

        <!-- New Form Content -->
        <section class="section">
            <div class="row">
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Form Permintaan Perbaikan</h5>

                            <form id="FPPForm" action="{{ route('formperbaikans.update', $formperbaikan->id) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="pemohon" class="form-label">
                                        Pemohon<span style="color: red;">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="pemohon" name="pemohon"
                                        value="{{ $formperbaikan->pemohon }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="date" class="form-label">
                                        Tanggal<span style="color: red;">*</span>
                                    </label>
                                    <input type="date" class="form-control" id="tanggal" name="tanggal"
                                        value="{{ $formperbaikan->tanggal }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="section" class="form-label">
                                        Bagian<span style="color: red;">*</span>
                                    </label>
                                    <select class="form-select" id="section" name="section" disabled>
                                        <option value="{{ $formperbaikan->section }}" selected>{{ $formperbaikan->section }}
                                        </option>
                                    </select>
                                    <input type="hidden" name="section" value="{{ $formperbaikan->section }}">
                                </div>
                                <div class="mb-3">
                                    <label for="mesin" class="form-label">
                                        Mesin<span style="color: red;">*</span>
                                    </label>
                                    <select class="form-select" id="mesin" name="mesin" disabled>
                                        <option value="{{ $formperbaikan->mesin }}" selected>{{ $formperbaikan->mesin }}
                                        </option>
                                    </select>
                                    <input type="hidden" name="mesin" value="{{ $formperbaikan->mesin }}">
                                </div>
                                <div class="mb-3">
                                    <label for="lokasi" class="form-label">
                                        Lokasi Mesin<span style="color: red;">*</span>
                                    </label>
                                    <select class="form-select" id="lokasi" name="lokasi" disabled>
                                        <option value="{{ $formperbaikan->lokasi }}" selected>{{ $formperbaikan->lokasi }}
                                        </option>
                                    </select>
                                    <input type="hidden" name="lokasi" value="{{ $formperbaikan->lokasi }}">
                                </div>
                                <div class="mb-3">
                                    <label for="kendala" class="form-label">
                                        Kendala<span style="color: red;">*</span>
                                    </label>
                                    <textarea class="form-control" id="kendala" name="kendala" readonly>{{ $formperbaikan->kendala }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="gambar" class="form-label">Gambar</label>
                                    <div id="gambarPreviewContainer">
                                        @if ($formperbaikan->gambar)
                                            <img id="gambarPreview" src="{{ asset($formperbaikan->gambar) }}"
                                                alt="Preview Gambar" style="max-width: 200px; cursor: pointer;">
                                        @else
                                            <p>No image available</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Modal -->
                                <div id="imageModal" class="modal">
                                    <span class="close" onclick="closeImageModal()">&times;</span>
                                    <img class="modal-content" id="img01">
                                </div>

                            </form>

                        </div>
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Tabel Riwayat Progres</h5>
                            <!-- Tabel Riwayat Progres -->
                            <div class="table-responsive">
                                <table class="datatables datatable" style="table-layout: responsive;">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Tindak Lanjut</th>
                                            <th scope="col">Jadwal Pengecekan</th>
                                            <th scope="col">Penanggung Jawab</th>
                                            <th scope="col">Tanggal Jatuh Tempo</th>
                                            <th scope="col">Unggahan Data</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Catatan</th>
                                            <th scope="col">Modifikasi Terakhir</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($formperbaikan->tindaklanjuts as $tindaklanjut)
                                            <tr>
                                                <td>{{ ++$i }}</td>
                                                <td>{{ $tindaklanjut->tindak_lanjut }}</td>
                                                <td>{{ $tindaklanjut->schedule_pengecekan }}</td>
                                                <td>{{ $tindaklanjut->pic }}</td>
                                                <td>{{ $tindaklanjut->due_date }}</td>
                                                <td>
                                                    @if ($tindaklanjut->attachment_file)
                                                        @php
                                                            $fileName = basename($tindaklanjut->attachment_file);
                                                            $buttonClass = $tindaklanjut->getAttachmentButtonClass();
                                                            $buttonIcon = $tindaklanjut->getAttachmentButtonIcon();
                                                        @endphp
                                                        <a href="{{ route('download.attachment', $tindaklanjut) }}"
                                                            target="_blank" class="{{ $buttonClass }}">
                                                            <i class="{{ $buttonIcon }}"></i> {{ $fileName }}
                                                        </a>
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>

                                                <td>
                                                    <div
                                                        style="background-color: {{ $tindaklanjut->status_background_color }};
                                            border-radius: 5px; /* Rounded corners */
                                            padding: 5px 10px; /* Padding inside the div */
                                            color: white; /* Text color, adjust as needed */
                                            font-weight: bold; /* Bold text */
                                            text-align: center; /* Center-align text */
                                            text-transform: uppercase; /* Uppercase text */
                                            ">
                                                        {{ $tindaklanjut->ubahtext() }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div
                                                        style="background-color: {{ $tindaklanjut->note_background_color }};
                        border-radius: 5px; /* Rounded corners */
                        padding: 5px 10px; /* Padding inside the div */
                        color: black; /* Text color, adjust as needed */
                        font-weight: bold; /* Bold text */
                        text-align: center; /* Center-align text */
                        text-transform: uppercase; /* Uppercase text */
                        ">
                                                        {{ $tindaklanjut->note }}
                                                    </div>
                                                </td>
                                                <td>{{ $tindaklanjut->updated_at }}</td>
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
    </main>
@endsection
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var gambarPreview = document.getElementById('gambarPreview');
        var modal = document.getElementById("imageModal");
        var modalImg = document.getElementById("img01");

        function toggleImageModal() {
            if (modal.style.display === "block") {
                closeImageModal();
            } else {
                modal.style.display = "block";
                modalImg.src = gambarPreview.src;
            }
        }

        function closeImageModal() {
            modal.style.display = "none";
        }

        // Menambahkan event listener untuk menutup modal saat tombol "X" diklik
        var closeButton = document.querySelector(".close");
        closeButton.addEventListener('click', function() {
            closeImageModal();
        });

        // Menambahkan event listener untuk menutup modal saat gambar pratinjau diklik kembali
        gambarPreview.addEventListener('click', function() {
            toggleImageModal();
        });

        // Menambahkan event listener untuk menutup modal saat area di luar modal diklik
        window.addEventListener('click', function(event) {
            if (event.target == modal) {
                closeImageModal();
            }
        });
    });
</script>

<style>
    /* CSS untuk modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
        align-items: center;
        /* Mengatur penempatan vertikal ke tengah */
        justify-content: center;
        /* Mengatur penempatan horizontal ke tengah */
    }

    /* Konten dalam modal */
    .modal-content {
        max-width: 80%;
        max-height: 80%;
        background-color: white;
        padding: 20px;
        border-radius: 4px;
        position: relative;
    }

    /* Tombol close */
    .close {
        position: absolute;
        top: 10px;
        right: 10px;
        color: #aaa;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
    }

    /* CSS untuk gambar di dalam modal */
    .modal-content img {
        width: 100%;
        height: auto;
        display: block;
        margin: 0 auto;
    }
</style>
<script>
    $(document).ready(function() {
        $('.datatable').DataTable();
    });
</script>
