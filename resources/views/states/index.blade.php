<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>States</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- jQuery & DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
</head>
<body class="bg-light">

<div class="container my-5">

    <!-- Main title -->
    <h1 class="text-center text-dark mb-4">States</h1>

    <!-- Add/Update State Form -->
    <div class="card shadow-sm mb-4" style="max-width: 800px; margin: 0 auto;">
        <div class="card-body">
            <form method="POST" class="add-form" id="state-form" action="{{ route('states.store') }}">
                @csrf
                <h4 id="form-title" class="mb-3">Add New State</h4>
                <input type="hidden" name="id" id="state-id">

                <!-- Use grid system to align form inputs in the same row -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <select name="country_id" id="country-select" class="form-control" required>
                            <option value="" selected disabled>Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->country_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input name="state_name" type="text" class="form-control" id="state-name" placeholder="State Name" required>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100" id="form-button">Add State</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- States Table -->
    <div class="card shadow-sm" style="max-width: 800px; margin: 0 auto;">
        <div class="card-body">
            <table id="states-table" class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Sr. No.</th>
                        <th>State Name</th>
                        <th>Country</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($states as $index => $state)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $state->state_name }}</td>
                            <td>{{ $state->country->country_name }}</td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <!-- Update Button with Bootstrap Icon -->
                                    <button type="button" class="btn btn-warning" onclick="editState('{{ $state->id }}', '{{ $state->state_name }}', '{{ $state->country_id }}')">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <!-- Delete Button with Bootstrap Icon -->
                                    <form method="POST" action="{{ route('states.destroy', $state->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        $('#states-table').DataTable();
    });

    function editState(id, name, countryId) {
        $('#state-id').val(id);
        $('#state-name').val(name);
        $('#country-select').val(countryId).prop('disabled', true);
        $('#form-title').text('Edit State');
        $('#form-button').text('Submit Changes');

        const updateUrl = `states/${id}`;
        $('#state-form').attr('action', updateUrl);

        if (!$('#state-form input[name="_method"]').length) {
            $('#state-form').append('<input type="hidden" name="_method" value="PUT">');
        } else {
            $('#state-form input[name="_method"]').val('PUT');
        }
    }
</script>

<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}',
            confirmButtonColor: '#198754' // Bootstrap green
        });
    @endif

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Error',
            html: '{!! implode("<br>", $errors->all()) !!}',
            confirmButtonColor: '#dc3545' // Bootstrap red
        });
    @endif
</script>
</body>
</html>
