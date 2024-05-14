@extends('layout')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<main id="main" class="main">

    <div class="pagetitle">
        <h1>Dept.Head Maintenance</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Tabel Preventif</li>
                <li class="breadcrumb-item active">Tambah Jadwal Preventif</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Buat Jadwal Preventif</h5>

                            <form id="preventiveForm" method="POST" action="{{ route('preventives.store') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3">
                                    <label for="mesin" class="form-label">
                                        Pilih Mesin<span style="color: red;">*</span>
                                    </label>
                                    <select class="form-control" id="mesin" name="mesin">
                                        <option value="">Pilih Mesin</option>
                                        @foreach($mesins as $mesin)
                                        <option value="{{ $mesin->no_mesin }}" data-tipe="{{$mesin->tipe}}">
                                            {{ $mesin->section }} | {{ $mesin->no_mesin }} | {{ $mesin->tipe }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="tipe" class="form-label">
                                        Tipe<span style="color: red;">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="tipe" name="tipe" readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="jadwal_rencana" class="form-label">
                                        Schedule Plan<span style="color: red;">*</span>
                                    </label>
                                    <input type="date" class="form-control" id="jadwal_rencana" name="jadwal_rencana">
                                </div>

                                <!-- Input issue -->
                                <div id="input-container">
                                    <!-- Input awal issue -->
                                    <label for="issues[]" class="form-label">
                                        Issue<span style="color: red;">*</span>
                                    </label>
                                    @foreach ($issues as $key => $issue)
                                    <div class="mb-3">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="issue[]" value="{{ $issue }}">
                                            <button type="button" class="btn btn-danger delete-input" onclick="removeInput(this)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <!-- Tombol Tambah Input -->
                                <div class="mb-3">
                                    <button type="button" class="btn btn-primary" onclick="addInput()">
                                        <i class="bi bi-plus"></i> Tambah Input
                                    </button>
                                </div>

                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <a href="{{route('dashboardPreventive') }}" class="btn btn-danger">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main><!-- End #main -->
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ambil elemen-elemen yang diperlukan
        var MesinSelect = document.getElementById('mesin');
        var tipeInput = document.getElementById('tipe');
        // Tambahkan event listener untuk perubahan pada pilihan nama_mesin
        MesinSelect.addEventListener('change', function() {
            // Ambil opsi yang dipilih
            var selectedOption = MesinSelect.options[MesinSelect.selectedIndex];

            // Set nilai type, no_mesin, dan mfg_date sesuai data yang dipilih
            tipeInput.value = selectedOption.getAttribute('data-tipe');
        });
    });
</script>

<script>
    function addInput() {
        const inputContainer = document.getElementById('input-container');
        const newInput = document.createElement('div');
        newInput.classList.add('mb-3');
        newInput.innerHTML = `
        <div class="input-group">
            <input type="text" class="form-control" name="issue[]">
            <button type="button" class="btn btn-danger" onclick="removeInput(this)">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    `;
        inputContainer.appendChild(newInput);
    }

    function removeInput(button) {
        const inputGroup = button.closest('.input-group'); // Temukan elemen input-group terdekat
        inputGroup.parentNode.remove(); // Hapus elemen input-group dari parent node
    }

    function resetForm() {
        document.getElementById('IssueForm').reset();
    }

    function validateForm() {
        const issues = document.getElementsByName('issue[]');
        const issueValues = Array.from(issues).map(issue => issue.value.trim());

        // Check if any issue field is empty
        const isEmpty = issueValues.some(value => value === '');
        if (isEmpty) {
            Swal.fire({
                icon: 'error',
                title: 'Peringatan!!!',
                text: 'Mohon isi semua input sebelum mengirimkan formulir.',
            });
            return false; // Prevent form submission
        }

        // Check for duplicate issue values
        const isDuplicate = issueValues.some((value, index) => issueValues.indexOf(value) !== index);
        if (isDuplicate) {
            Swal.fire({
                icon: 'error',
                title: 'Peringatan!!!',
                text: 'Terdapat isian issue yang sama. Silakan periksa kembali!',
            });
            return false; // Prevent form submission
        }

        // Check if there are no inputs added
        const inputCount = issues.length;
        if (inputCount === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Peringatan!!!',
                text: 'Mohon tambahkan setidaknya satu input sebelum mengirimkan formulir.',
            });
            return false; // Prevent form submission
        }

        return true; // Allow form submission
    }

    document.getElementById('IssueForm').addEventListener('submit', function(event) {
        if (!validateForm()) {
            event.preventDefault(); // Prevent form submission if validation fails
        } else {
            // Jika validasi berhasil, tambahkan isian issue ke dalam textarea di form kedua
            const issues = document.getElementsByName('issue[]');
            const issueValues = Array.from(issues).map(issue => issue.value.trim());
            const combinedIssues = issueValues.join('\n');
            document.getElementById('issue').value = combinedIssues;
        }
    });
</script>
