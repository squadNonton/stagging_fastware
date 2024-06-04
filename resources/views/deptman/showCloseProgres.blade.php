@extends('layout')

@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Halaman Riwayat Klaim dan Komplain</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboardHandling') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('showHistoryCLaimComplain') }}">Menu Riwayat Klaim dan
                            Komplain</a></li>
                    <li class="breadcrumb-item active">Halaman Riwayat Klaim dan Komplain</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Form Konfirmasi</h5>
                        <form action="{{ route('updateConfirm', $handlings->id) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="modified_by" class="col-sm-2 col-form-label">User : <span
                                                    style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-lg-6">
                                            <input type="text" class="form-control" id="modified_by" name="modified_by"
                                                maxlength="6" style="width: 100%; max-width: 100%;"
                                                placeholder="{{ $handlings->user->name}}" disabled>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="no_wo" class="col-sm-2 col-form-label">No. WO:<span
                                                    style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-lg-6">
                                            <input type="text" class="form-control" id="no_wo" name="no_wo"
                                                maxlength="6" style="width: 100%;" value="{{ $handlings->no_wo }}" required
                                                disabled>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="customer_code" class="col-sm-5 col-form-label">Kode Pelanggan:<span
                                                    style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-lg-6">
                                            <select name="customer_id" id="customer_id_code" class="select2"
                                                style="width: 100%" onchange="updateCustomerInfo()" disabled>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}"
                                                        @if ($customer->id == $handlings->customer_id) selected @endif
                                                        data-name_customer="{{ $customer->name_customer }}"
                                                        data-area="{{ $customer->area }}">{{ $customer->customer_code }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="customer_name" class="col-sm-5 col-form-label">Nama Pelanggan:<span
                                                    style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-lg-6">
                                            <select name="customer_name" id="customer_id_name" class="select2"
                                                style="width: 100%" disabled>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->name_customer }}">
                                                        {{ $customer->name_customer }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="area" class="col-sm-5 col-form-label">Area Pelanggan:<span
                                                    style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-lg-6">
                                            <select name="area" id="customer_id_area" class="select2" style="width: 100%"
                                                disabled>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->area }}">{{ $customer->area }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="area" class="col-sm-5 col-form-label">Tipe Bahan:<span
                                                    style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-lg-6">
                                            <select name="type_id" id="type_id" class="" style="width: 100%"
                                                disabled>
                                                @foreach ($type_materials as $typeMaterial)
                                                    <option value="{{ $typeMaterial->id }}"
                                                        @if ($typeMaterial->id == $handlings->type_id) selected @endif>
                                                        {{ $typeMaterial->type_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="t" class="form-label">T:</label>
                                            <input type="text" class="form-control input-sm" id="thickness"
                                                name="thickness" placeholder="Thickness" style="max-width: 80%;"
                                                value="{{ $handlings->thickness }}" disabled>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="w" class="form-label">W:</label>
                                            <input type="text" class="form-control input-sm" id="weight"
                                                name="weight" placeholder="Weight" style="max-width: 80%;"
                                                value="{{ $handlings->weight }}" disabled>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="w" class="form-label">L:</label>
                                            <input type="text" class="form-control input-sm" id="lenght"
                                                name="lenght" placeholder="Lenght" style="max-width: 80%;"
                                                value="{{ $handlings->lenght }}" disabled>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="w" class="form-label">OD:</label>
                                            <input type="text" class="form-control input-sm" id="outer_diameter"
                                                name="outer_diameter" placeholder="Outer Diameter" style="max-width: 80%"
                                                value="{{ $handlings->outer_diameter }}" disabled>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="w" class="form-label">ID:</label>
                                            <input type="text" class="form-control input-sm" id="inner_diameter"
                                                name="inner_diameter" placeholder="Inner Diameter" style="max-width: 80%"
                                                value="{{ $handlings->inner_diameter }}" disabled>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="qty" class="form-label">QTY (Kg):</label>
                                            <input type="text" class="form-control input-sm" id="qty"
                                                name="qty" style="max-width: 80%;" value="{{ $handlings->qty }}"
                                                required disabled>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="pcs" class="form-label">Unit (Pcs):</label>
                                            <input type="text" class="form-control input-sm" id="pcs"
                                                name="pcs" style="max-width: 80%" value="{{ $handlings->pcs }}"
                                                required disabled>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="category" class="col-sm-5 col-form-label">Kategori (NG):<span
                                                    style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-lg-6">
                                            <select name="category" class="form-control" id="category"
                                                style="width: 100%" required disabled>
                                                <option value="" class="text-center">-------- Silahkan Pilih
                                                    Kategori --------</option>
                                                <option value="CT - Ukuran Minus"
                                                    {{ $handlings->category == 'CT - Ukuran Minus' ? 'selected' : '' }}>CT
                                                    - Ukuran Minus</option>
                                                <option value="CT - Potongan Miring"
                                                    {{ $handlings->category == 'CT - Potongan Miring' ? 'selected' : '' }}>
                                                    CT - Potongan Miring</option>
                                                <option value="CT - NG Dimensi"
                                                    {{ $handlings->category == 'CT - NG Dimensi' ? 'selected' : '' }}>CT -
                                                    NG Dimensi</option>
                                                <option value="MCH - Dimensi NG"
                                                    {{ $handlings->category == 'MCH - Dimensi NG' ? 'selected' : '' }}>MCH
                                                    - Dimensi NG</option>
                                                <option value="MCH - Ada Step"
                                                    {{ $handlings->category == 'MCH - Ada Step' ? 'selected' : '' }}>MCH -
                                                    Ada Step</option>
                                                <option value="MCH - NG Paralelism"
                                                    {{ $handlings->category == 'MCH - NG Paralelism' ? 'selected' : '' }}>
                                                    MCH - NG Paralelism</option>
                                                <option value="MCH - NG Siku"
                                                    {{ $handlings->category == 'MCH - NG Siku' ? 'selected' : '' }}>MCH -
                                                    NG Siku</option>
                                                <option value="HT - NG Siku"
                                                    {{ $handlings->category == 'HT - NG Siku' ? 'selected' : '' }}>HT - NG
                                                    Siku</option>
                                                <option value="HT - Retak/Patah"
                                                    {{ $handlings->category == 'HT - Retak/Patah' ? 'selected' : '' }}>HT -
                                                    Retak/Patah</option>
                                                <option value="HT - Bending"
                                                    {{ $handlings->category == 'HT - Bending' ? 'selected' : '' }}>HT -
                                                    Bending</option>
                                                <option value="HT - Kekerasan Minus"
                                                    {{ $handlings->category == 'HT - Kekerasan Minus' ? 'selected' : '' }}>
                                                    HT - Kekerasan Minus</option>
                                                <option value="HT - Kekerasan Lebih"
                                                    {{ $handlings->category == 'HT - Kekerasan Lebih' ? 'selected' : '' }}>
                                                    HT - Kekerasan Lebih</option>
                                                <option value="HT - Scratch/Gores"
                                                    {{ $handlings->category == 'HT - Scratch/Gores' ? 'selected' : '' }}>HT
                                                    - Scratch/Gores</option>
                                                <option value="HT - Aus"
                                                    {{ $handlings->category == 'HT - Aus' ? 'selected' : '' }}>HT - Aus
                                                </option>
                                                <option value="HT - Appearance"
                                                    {{ $handlings->category == 'HT - Appearance' ? 'selected' : '' }}>HT -
                                                    Appearance</option>
                                                <option value="MKT - Jumlah Tidak Sesuai"
                                                    {{ $handlings->category == 'MKT - Jumlah Tidak Sesuai' ? 'selected' : '' }}>
                                                    MKT - Jumlah Tidak Sesuai</option>
                                                <option value="MKT - Dimensi Tidak Sesuai"
                                                    {{ $handlings->category == 'MKT - Dimensi Tidak Sesuai' ? 'selected' : '' }}>
                                                    MKT - Dimensi Tidak Sesuai</option>
                                                <option value="MKT - Type Material Tidak Sesuai"
                                                    {{ $handlings->category == 'MKT - Type Material Tidak Sesuai' ? 'selected' : '' }}>
                                                    MKT - Type Material Tidak Sesuai</option>
                                                <option value="MTRL - Pin Hole"
                                                    {{ $handlings->category == 'MTRL - Pin Hole' ? 'selected' : '' }}>MTRL
                                                    - Pin Hole</option>
                                                <option value="MTRL - Inklusi"
                                                    {{ $handlings->category == 'MTRL - Inklusi' ? 'selected' : '' }}>MTRL -
                                                    Inklusi</option>
                                                <option value="MTRL - Sulit Machining"
                                                    {{ $handlings->category == 'MTRL - Sulit Machining' ? 'selected' : '' }}>
                                                    MTRL - Sulit Machining</option>
                                                <option value="MTRL - Karat"
                                                    {{ $handlings->category == 'MTRL - Karat' ? 'selected' : '' }}>MTRL -
                                                    Karat</option>
                                                <option value="Others"
                                                    {{ $handlings->category == 'Others' ? 'selected' : '' }}>Others
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="hasil_tindak_lanjut" class="col-sm-5 col-form-label">Keterangan:
                                                (Jika ada)</label>
                                        </div>
                                        <div class="col-lg-6">
                                            <textarea class="form-control" rows="5" id="results" name="results" style="width: 100%" disabled required>{{ $handlings->results }}</textarea>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="process_type" class="col-sm-5 col-form-label">Jenis Proses:<span
                                                    style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-lg-6">
                                            <select name="process_type" class="form-control" id="process_type"
                                                style="width: 25%" required disabled>
                                                <option value="">------------------- Jenis Proses -----------------
                                                </option>
                                                <option value="Heat Treatment"
                                                    {{ $handlings->process_type == 'Heat Treatment' ? 'selected' : '' }}>
                                                    Heat
                                                    treatment</option>
                                                <option value="Cutting"
                                                    {{ $handlings->process_type == 'Cutting' ? 'selected' : '' }}>
                                                    Cutting
                                                </option>
                                                <option value="Machining"
                                                    {{ $handlings->process_type == 'Machining' ? 'selected' : '' }}>
                                                    Machining
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="type_1" class="col-sm-5 col-form-label">Jenis:<span
                                                    style="color: red;">*</span></label>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="type_1"
                                                    name="type_1" value="Komplain" disabled
                                                    @if ($handlings->type_1 == 'Komplain') checked @endif>
                                                <label class="form-check-label" for="check2">Komplain</label>
                                            </div>
                                            <div class="form-check mr-2">
                                                <input type="checkbox" class="form-check-input" id="type_2"
                                                    name="type_2" value="Klaim" disabled
                                                    @if ($handlings->type_2 == 'Klaim') checked @endif>
                                                <label class="form-check-label" for="check1">Klaim</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="image_upload" class="col-sm-5 col-form-label">Unggah Gambar: (Jika ada)</span></label>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="row mt-3">
                                                <div class="col-lg-12">
                                                    <div id="existingImages" class="row">
                                                        @php $count = 0; @endphp
                                                        @foreach (json_decode($handlings->image) as $image)
                                                            @if ($count < 4)
                                                                <div class="col-lg-6 mb-2 d-flex justify-content-start">
                                                                    <img src="{{ asset('assets/image/' . $image) }}"
                                                                        class="img-fluid rounded mx-1" alt="image"
                                                                        style="max-width: 100%; object-fit: cover;"
                                                                        onclick="showModal('{{ asset('assets/image/' . $image) }}')">
                                                                </div>
                                                                @php $count++; @endphp
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div id="imageModal" class="modal"
                    style="display: none; position: fixed; z-index: 1; padding-top: 50px; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.9);">
                    <div
                        style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 80%; max-width: 700px; background-color: #fefefe; border-radius: 5px;">
                        <span class="close"
                            style="position: absolute; top: 10px; right: 10px; color: #000; font-size: 30px; font-weight: bold; cursor: pointer;"
                            onclick="closeModal()">&times;</span>
                        <img class="modal-content" id="modalImage"
                            style="display: block; margin: auto; width: 50%; max-width: 70%;">
                    </div>
                </div>
            </div>
            <div class="card mb-2">
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Riwayat Progres
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <table id="" class="table table-striped table-bordered table-hover datatable">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">NO</th>
                                            <th style="text-align: center;">Hasil dan Tindak Lanjut</th>
                                            <th style="text-align: center;">Jadwal Kunjungan</th>
                                            <th style="text-align: center;">PIC</th>
                                            <th style="text-align: center;">Tenggat waktu</th>
                                            <th style="text-align: center;">Jenis 1</th>
                                            <th style="text-align: center;">Jenis 2</th>
                                            <th style="text-align: center;">Unggahan (File)</th>
                                            <th style="text-align: center;">Pembaruan Terakhir</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $row)
                                            <tr>
                                                <td class="text-center py-3">{{ $loop->iteration }}</td>
                                                <td class="text-center py-3">{{ $row->results }}</td>
                                                <td class="text-center py-3">{{ $row->schedule }}</td>
                                                <td class="text-center py-3">{{ $row->pic }}</td>
                                                <td class="text-center py-3">{{ $row->due_date }}</td>
                                                <td class="text-center py-3">
                                                    @if ($row->history_type == 1)
                                                        Komplain
                                                    @endif
                                                </td>
                                                <td class="text-center py-3">
                                                    @if ($row->history_type == 1)
                                                        Klaim
                                                    @endif
                                                </td>
                                                <td class="text-center pt-3">
                                                    @if (in_array(pathinfo($row->file, PATHINFO_EXTENSION), ['pdf']))
                                                        <a href="{{ asset('assets/image/' . $row->file) }}"
                                                            download="{{ $row->file_name }}">
                                                            <i class="fas fa-file-pdf fs-4"></i>
                                                        </a>
                                                    @elseif(in_array(pathinfo($row->file, PATHINFO_EXTENSION), ['xlsx', 'xls']))
                                                        <a href="{{ asset('assets/image/' . $row->file) }}"
                                                            download="{{ $row->file_name }}">
                                                            <i class="fas fa-file-excel fs-4"></i>
                                                        </a>
                                                    @elseif(in_array(pathinfo($row->file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']))
                                                        <a href="{{ asset('assets/image/' . $row->file) }}"
                                                            download="{{ $row->file_name }}">
                                                            <img src="{{ asset('assets/image/' . $row->file) }}"
                                                                class="img-fluid rounded"
                                                                style="max-width: 100%; height: auto;">
                                                        </a>
                                                    @else
                                                        <p>File tidak didukung</p>
                                                    @endif
                                                </td>
                                                <td class="text-center py-3">{{ $row->created_at }}</td>
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
        <script>
             function showModal(imageSrc) {
                var modal = document.getElementById("imageModal");
                var modalImg = document.getElementById("modalImage");
                modal.style.display = "block";
                modalImg.src = imageSrc;
            }

            // Fungsi untuk menutup modal saat tombol "x" di klik
            function closeModal() {
                var modal = document.getElementById("imageModal");
                modal.style.display = "none";
            }

            function viewImage(event) {
                var imageContainer = document.getElementById('imagePreviewContainer');
                imageContainer.innerHTML = ''; // Kosongkan kontainer gambar sebelum menambahkan gambar baru

                var files = event.target.files; // Ambil file-file yang diunggah

                // Iterasi melalui setiap file
                for (var i = 0; i < files.length; i++) {
                    var file = files[i];
                    var reader = new FileReader(); // Buat pembaca file

                    reader.onload = function(e) {
                        // Buat elemen gambar baru
                        var imgElement = document.createElement('img');
                        imgElement.src = e.target.result;

                        // Tentukan ukuran gambar berdasarkan jumlah file yang diunggah
                        if (files.length === 1 || files.length === 2) {
                            imgElement.style.width = '50%'; // Set lebar gambar menjadi 50% untuk 1 atau 2 gambar
                        } else {
                            imgElement.style.width = '33.33%'; // Set lebar gambar menjadi 33.33% untuk lebih dari 2 gambar
                        }

                        // Tambahkan gambar ke dalam kontainer
                        imageContainer.appendChild(imgElement);

                        // Tambahkan fungsi untuk menampilkan modal saat gambar di klik
                        imgElement.onclick = function() {
                            showModal(this.src);
                        };
                    };

                    reader.readAsDataURL(file); // Baca file sebagai URL data
                }

                // Setelah semua gambar ditambahkan, kita akan mengatur ulang lebar kontainer gambar
                setTimeout(function() {
                    var images = imageContainer.querySelectorAll('img');
                    var containerWidth = (files.length > 2) ? '100%' : '50%'; // Tentukan lebar kontainer
                    imageContainer.style.width = containerWidth;
                }, 100); // Beri sedikit waktu agar gambar ditampilkan sebelum menyesuaikan lebar kontainer
            }
        </script>
    </main><!-- End #main -->
@endsection
