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
                                    <th style="width: 50px;"><input type="checkbox" onclick="toggle(this);"></th>
                                    <th style="width: 30px;">No</th>
                                    <th style="width: 150px;">Nama Material</th>
                                    <th style="width: 50px;">Jenis</th>
                                    <th style="width: 50px;">Thickness</th>
                                    <th style="width: 50px;">Weight</th>
                                    <th style="width: 50px;">Inner Diameter</th>
                                    <th style="width: 50px;">Outer Diameter</th>
                                    <th style="width: 50px;">Length</th>
                                    <th style="width: 50px;">Pcs</th>
                                    <th style="width: 50px;">Qty</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                @foreach ($materials as $index => $material)
                                    <tr>
                                        <td>
                                            @if (empty($material['nama_material']) &&
                                                    empty($material['thickness']) &&
                                                    empty($material['weight']) &&
                                                    empty($material['inner_diameter']) &&
                                                    empty($material['outer_diameter']) &&
                                                    empty($material['length']) &&
                                                    empty($material['pcs']) &&
                                                    empty($material['qty']))
                                                <input type="checkbox" name="record">
                                            @endif
                                        </td>
                                        <td>{{ $index + 1 }}</td>
                                        <td><input type="text" name="nama_material"
                                                value="{{ $material['nama_material'] }}" size="20" required></td>
                                        <td>
                                            <select name="jenis" class="jenis-dropdown" style="width: 80px;">
                                                <option value="Flat" {{ $material['jenis'] == 'Flat' ? 'selected' : '' }}>
                                                    Flat</option>
                                                <option value="Round"
                                                    {{ $material['jenis'] == 'Round' ? 'selected' : '' }}>Round</option>
                                                <option value="Honed Tube"
                                                    {{ $material['jenis'] == 'Honed Tube' ? 'selected' : '' }}>Honed Tube
                                                </option>
                                            </select>
                                        </td>
                                        <td><input type="text" name="thickness" value="{{ $material['thickness'] }}"
                                                size="10"></td>
                                        <td><input type="text" name="weight" value="{{ $material['weight'] }}"
                                                size="5"></td>
                                        <td><input type="text" name="inner_diameter"
                                                value="{{ $material['inner_diameter'] }}" size="10"></td>
                                        <td><input type="text" name="outer_diameter"
                                                value="{{ $material['outer_diameter'] }}" size="10"></td>
                                        <td><input type="text" name="length" value="{{ $material['length'] }}"
                                                size="10"></td>
                                        <td><input type="text" name="pcs" value="{{ $material['pcs'] }}"
                                                size="10" required></td>
                                        <td><input type="text" name="qty" value="{{ $material['qty'] }}"
                                                size="10" required></td>
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
                        inner_diameter: row.querySelector('input[name="inner_diameter"]').value,
                        outer_diameter: row.querySelector('input[name="outer_diameter"]').value,
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
                function updateDropdownListeners() {
                    var dropdowns = document.querySelectorAll('.jenis-dropdown');

                    dropdowns.forEach(function(dropdown) {
                        dropdown.addEventListener('change', function() {
                            var row = dropdown.closest('tr');
                            var thicknessInput = row.querySelector('input[name="thickness"]');
                            var weightInput = row.querySelector('input[name="weight"]');
                            var innerDiameterInput = row.querySelector('input[name="inner_diameter"]');
                            var outerDiameterInput = row.querySelector('input[name="outer_diameter"]');

                            if (dropdown.value === 'Round') {
                                thicknessInput.disabled = true;
                                thicknessInput.value = ''; // Clear the value if you want
                                weightInput.disabled = true;
                                weightInput.value = ''; // Clear the value if you want
                                innerDiameterInput.disabled = true;
                                innerDiameterInput.value = ''; // Clear the value if you want
                                outerDiameterInput.disabled = false;
                            } else if (dropdown.value === 'Honed Tube') {
                                thicknessInput.disabled = true;
                                thicknessInput.value = ''; // Clear the value if you want
                                weightInput.disabled = true;
                                weightInput.value = ''; // Clear the value if you want
                                innerDiameterInput.disabled = false;
                                outerDiameterInput.disabled = false;
                            } else {
                                thicknessInput.disabled = false;
                                weightInput.disabled = false;
                                innerDiameterInput.disabled = true;
                                innerDiameterInput.value = ''; // Clear the value if you want
                                outerDiameterInput.disabled = true;
                                outerDiameterInput.value = ''; // Clear the value if you want
                            }
                        });

                        // Trigger change event to set initial state
                        dropdown.dispatchEvent(new Event('change'));
                    });
                }

                // Initial call to setup listeners on existing rows
                updateDropdownListeners();

                // Add row function updated to include the new columns
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
                    var cell9 = newRow.insertCell(8);
                    var cell10 = newRow.insertCell(9);
                    var cell11 = newRow.insertCell(10);

                    cell1.innerHTML = '<input type="checkbox" name="record">';
                    cell2.innerHTML = rowCount + 1;
                    cell3.innerHTML = '<input type="text" name="nama_material" size="20" required>';
                    cell4.innerHTML = `
        <select name="jenis" class="jenis-dropdown" style="width: 80px;">
            <option value="Flat">Flat</option>
            <option value="Round">Round</option>
            <option value="Honed Tube">Honed Tube</option>
        </select>
    `;
                    cell5.innerHTML = '<input type="text" name="thickness" size="10">';
                    cell6.innerHTML = '<input type="text" name="weight" size="5">';
                    cell7.innerHTML = '<input type="text" name="inner_diameter" size="10">';
                    cell8.innerHTML = '<input type="text" name="outer_diameter" size="10">';
                    cell9.innerHTML = '<input type="text" name="length" size="10">';
                    cell10.innerHTML = '<input type="text" name="pcs" size="10" required>';
                    cell11.innerHTML = '<input type="text" name="qty" size="10" required>';

                    // Re-attach listeners to include the new row
                    updateDropdownListeners();
                }


                // Make the addRow function available globally if needed
                window.addRow = addRow;
            });
        </script>
    </main><!-- End #main -->
@endsection
