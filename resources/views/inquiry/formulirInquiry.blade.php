@extends('layout')

@section('content')
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
        }

        .form-section {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }

        .form-section .form-group {
            flex: 1 1 15%;
            /* Adjust this value to control the width of each item */
            margin-right: 2px;
            margin-bottom: 15px;
        }

        .form-section label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        .add-column-button {
            margin-top: 15px;
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Halaman Inquiry</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active"><a href="{{ route('createinquiry') }}">Menu Inquiry Sales</a></li>
                    <li class="breadcrumb-item active">Formulir Inquiry Sales</li>
                </ol>
            </nav>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Tampilan Formulir Inquiry Sales</h5>
                    <div class="form-section">
                        <div class="form-group">
                            <label>Kode Inquiry:</label>
                            <div class="form-value">{{ $inquiry->kode_inquiry }}</div>
                        </div>
                        <div class="form-group">
                            <label>Supplier:</label>
                            <div class="form-value">{{ $inquiry->supplier }}</div>
                        </div>
                        <div class="form-group">
                            <label>Order From:</label>
                            <div class="form-value">{{ $inquiry->order_from }}</div>
                        </div>
                        <div class="form-group">
                            <label>Create By:</label>
                            <div class="form-value">{{ $inquiry->create_by }}</div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th><input type="checkbox" onclick="toggle(this);"></th>
                                    <th>No</th>
                                    <th>Nama Material</th>
                                    <th>Jenis</th>
                                    <th>Thickness</th>
                                    <th>Weight</th>
                                    <th>Length</th>
                                    <th>Pcs</th>
                                    <th>Qty</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                @foreach ($materials as $index => $material)
                                    <tr>
                                        <td>
                                            @if (empty($material['nama_material']) &&
                                                    empty($material['thickness']) &&
                                                    empty($material['weight']) &&
                                                    empty($material['length']) &&
                                                    empty($material['pcs']) &&
                                                    empty($material['qty']))
                                                <input type="checkbox" name="record">
                                            @endif
                                        </td>
                                        <td>{{ $index + 1 }}</td>
                                        <td><input type="text" name="nama_material"
                                                value="{{ $material['nama_material'] }}" required></td>
                                        <td>
                                            <select name="jenis" class="jenis-dropdown">
                                                <option value="Flat" {{ $material['jenis'] == 'Flat' ? 'selected' : '' }}>
                                                    Flat</option>
                                                <option value="Round"
                                                    {{ $material['jenis'] == 'Round' ? 'selected' : '' }}>Round</option>
                                            </select>
                                        </td>
                                        <td><input type="text" name="thickness" value="{{ $material['thickness'] }}">
                                        </td>
                                        <td><input type="text" name="weight" value="{{ $material['weight'] }}"></td>
                                        <td><input type="text" name="length" value="{{ $material['length'] }}"></td>
                                        <td><input type="text" name="pcs" value="{{ $material['pcs'] }}" required>
                                        </td>
                                        <td><input type="text" name="qty" value="{{ $material['qty'] }}" required>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <a href="#" class="btn btn-success add-row-button" onclick="addRow()">Tambah Baris</a>
                    <a href="#" class="btn btn-danger delete-row-button" onclick="deleteRow()">Hapus Baris</a>
                    <button class="btn btn-primary" onclick="saveTable()">Save</button>
                </div>
            </div>
        </section>
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <!-- excel -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
        <script>
            function addRow() {
                var tableBody = document.getElementById('table-body');
                var rowCount = tableBody.rows.length;
                var newRow = tableBody.insertRow(rowCount);

                var cell1 = newRow.insertCell(0);
                var cell2 = newRow.insertCell(1);
                var cell3 = newRow.insertCell(2);
                var cell4 = newRow.insertCell(3);
                var cell5 = newRow.insertCell(4);
                var cell6 = newRow.insertCell(5);
                var cell7 = newRow.insertCell(6);
                var cell8 = newRow.insertCell(7);

                cell1.innerHTML = '<input type="checkbox" name="record">';
                cell2.innerHTML = rowCount + 1;
                cell3.innerHTML = '<input type="text" name="nama_material">';
                cell4.innerHTML = '<input type="text" name="thickness">';
                cell5.innerHTML = '<input type="text" name="weight">';
                cell6.innerHTML = '<input type="text" name="length">';
                cell7.innerHTML = '<input type="text" name="pcs">';
                cell8.innerHTML = '<input type="text" name="qty">';
            }

            function toggle(source) {
                checkboxes = document.getElementsByName('record');
                for (var i = 0, n = checkboxes.length; i < n; i++) {
                    checkboxes[i].checked = source.checked;
                }
            }

            function deleteRow() {
                var tableBody = document.getElementById('table-body');
                var rows = tableBody.querySelectorAll('tr');
                rows.forEach(function(row) {
                    var checkbox = row.querySelector('input[name="record"]');
                    if (checkbox && checkbox.checked) {
                        tableBody.removeChild(row);
                    }
                });
            }

            function saveTable() {
                var tableBody = document.getElementById('table-body');
                var rows = tableBody.querySelectorAll('tr');
                var data = {
                    id_inquiry: '{{ $inquiry->id }}', // Ensure id_inquiry from PHP to JavaScript
                    kode_inquiry: '{{ $inquiry->kode_inquiry }}',
                    supplier: '{{ $inquiry->supplier }}',
                    order_from: '{{ $inquiry->order_from }}',
                    create_by: '{{ $inquiry->create_by }}',
                    materials: []
                };

                rows.forEach(function(row) {
                    var rowData = {
                        nama_material: row.querySelector('input[name="nama_material"]').value,
                        jenis: row.querySelector('select[name="jenis"]').value,
                        thickness: row.querySelector('input[name="thickness"]').value,
                        weight: row.querySelector('input[name="weight"]').value,
                        length: row.querySelector('input[name="length"]').value,
                        pcs: row.querySelector('input[name="pcs"]').value,
                        qty: row.querySelector('input[name="qty"]').value
                    };
                    data.materials.push(rowData);
                });

                $.ajax({
                    url: '{{ route('inquiry.previewSS') }}',
                    method: 'POST',
                    data: JSON.stringify(data),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert('Inquiry updated successfully');
                        window.location.href = '{{ route('showFormSS', $inquiry->id) }}';
                    },
                    error: function(error) {
                        console.error(error);
                        alert('An error occurred');
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                var dropdowns = document.querySelectorAll('.jenis-dropdown');

                dropdowns.forEach(function(dropdown) {
                    dropdown.addEventListener('change', function() {
                        var row = dropdown.closest('tr');
                        var thicknessInput = row.querySelector('input[name="weight"]');

                        if (dropdown.value === 'Round') {
                            thicknessInput.disabled = true;
                            thicknessInput.value = ''; // Clear the value if you want
                        } else {
                            thicknessInput.disabled = false;
                        }
                    });

                    // Trigger change event to set initial state
                    dropdown.dispatchEvent(new Event('change'));
                });
            });
        </script>
    </main><!-- End #main -->
@endsection
