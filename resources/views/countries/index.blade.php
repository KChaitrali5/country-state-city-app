<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Countries</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
</head>
<body class="bg-light">

<div class="container my-5">

    <!-- Main title -->
    <h1 class="text-center text-dark mb-4">Countries</h1>

    <!-- Add Country Form -->
    <div class="card shadow-sm mb-4" style="max-width: 800px; margin: 0 auto;">
        <div class="card-body">
            <form method="POST" class="add-form" id="country-form" action="{{ route('countries.store') }}">
                @csrf
                <h4 id="form-title" class="mb-3">Add New Country</h4>
                <input type="hidden" name="id" id="country-id">
                <div class="row mb-3">
                    <div class="col-md-8">
                        <input name="country_name" type="text" class="form-control" id="country-name" placeholder="Country Name" required>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100" id="form-button">Add Country</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Countries Table -->
    <div class="card shadow-sm" style="max-width: 800px; margin: 0 auto;">
        <div class="card-body">
            <table id="countries-table" class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 150px;">Sr. No.</th>
                        <th>Country Name</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($countries as $index => $country)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $country->country_name }}</td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <!-- Update Button with Bootstrap Icon -->
                                    <button type="button" class="btn btn-warning" onclick="editCountry('{{ $country->id }}', '{{ $country->country_name }}')">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <!-- Delete Button with Bootstrap Icon -->
                                    <form method="POST" action="{{ route('countries.destroy', $country->id) }}">
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

<script>
    $(document).ready(function () {
        $('#countries-table').DataTable();
    });

    function editCountry(id, name) {
        $('#country-id').val(id);
        $('#country-name').val(name);
        $('#form-title').text('Edit Country');
        $('#form-button').text('Update Country');

        // Update the form's action to point to the update route dynamically
        const updateUrl = `countries/${id}`;
        $('#country-form').attr('action', updateUrl);

        // Laravel requires PUT method for updates — inject the method field dynamically
        if (!$('#country-form input[name="_method"]').length) {
            $('#country-form').append('<input type="hidden" name="_method" value="PUT">');
        } else {
            $('#country-form input[name="_method"]').val('PUT');
        }
    }
</script>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            title: 'Validation Error',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            confirmButtonColor: '#dc3545' // Bootstrap red
        });
    @endif
</script>

</body>
</html>
