<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cities</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">

<div class="container my-5">
    <h1 class="text-center text-dark mb-4">Cities</h1>

    <!-- Add/Edit City Form -->
    <div class="card shadow-sm mb-4" style="max-width: 900px; margin: 0 auto;">
        <div class="card-body">
            <form method="POST" class="add-form" id="city-form" action="{{ route('cities.store') }}">
                @csrf
                <h4 id="form-title" class="mb-3">Add New City</h4>
                <input type="hidden" name="id" id="city-id">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <select id="city-country" name="country_id" class="form-select" required>
                            <option disabled selected>Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->country_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select id="city-state" name="state_id" class="form-select" required>
                            <option disabled selected>Select State</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input name="city_name" id="city-name" class="form-control" placeholder="City Name" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-success w-100" id="form-button">Add City</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Cities Table -->
    <div class="card shadow-sm" style="max-width: 900px; margin: 0 auto;">
        <div class="card-body">
            <table id="cities-table" class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Sr. No.</th>
                        <th>City</th>
                        <th>State</th>
                        <th>Country</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cities as $index => $city)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $city->city_name }}</td>
                            <td>{{ $city->state->state_name }}</td>
                            <td>{{ $city->state->country->country_name }}</td>
                            <td class="text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    <button type="button" class="btn btn-warning" onclick="editCity('{{ $city->id }}', '{{ $city->city_name }}', '{{ $city->state_id }}', '{{ $city->state->country_id }}')">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <form method="POST" action="{{ route('cities.destroy', $city->id) }}">
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
        $('#cities-table').DataTable();
    });

    function editCity(id, name, stateId, countryId) {
    $('#city-id').val(id);
    $('#city-name').val(name);
    $('#form-title').text('Edit City');
    $('#form-button').text('Update City');

    // Disable selects during update mode
    $('#city-country').prop('disabled', true);
    $('#city-state').prop('disabled', true);

    // Set form action to PUT
    const updateUrl = `/cities/${id}`;
    $('#city-form').attr('action', updateUrl);

    if (!$('#city-form input[name="_method"]').length) {
        $('#city-form').append('<input type="hidden" name="_method" value="PUT">');
    } else {
        $('#city-form input[name="_method"]').val('PUT');
    }

    // Set country first
    $('#city-country').val(countryId);

    // Then fetch states and set the correct state
    fetch(`/states-by-country/${countryId}`)
        .then(response => response.json())
        .then(data => {
            const stateSelect = document.getElementById('city-state');
            stateSelect.innerHTML = '<option disabled>Select State</option>';
            data.forEach(state => {
                const option = document.createElement('option');
                option.value = state.id;
                option.text = state.state_name;
                stateSelect.add(option);
            });

            // Now set the state value
            $('#city-state').val(stateId);
        });
}


    // Populate states based on country
    document.getElementById('city-country').addEventListener('change', function () {
        const countryId = this.value;
        fetch(`/states-by-country/${countryId}`)
            .then(response => response.json())
            .then(data => {
                const stateSelect = document.getElementById('city-state');
                stateSelect.innerHTML = '<option disabled selected>Select State</option>';
                data.forEach(state => {
                    const option = document.createElement('option');
                    option.value = state.id;
                    option.text = state.state_name;
                    stateSelect.add(option);
                });
            });
    });
</script>

<!-- SweetAlert feedback -->
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}',
            confirmButtonColor: '#198754'
        });
    @endif

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            confirmButtonColor: '#dc3545'
        });
    @endif
</script>

</body>
</html>
