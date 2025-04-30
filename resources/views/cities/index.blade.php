<!DOCTYPE html>
<html>
<head>
    <title>Cities</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- jQuery & DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <style>
        body, h1, h2, table {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fa;
            color: #333;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #2d3a45;
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .add-form {
            background-color: #fff;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            max-width: 700px;
            margin: 20px auto;
        }

        .form-row {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }

        .form-row select,
        .form-row input {
            flex: 1;
        }

        select, input[type="text"], input[name="city_name"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            padding: 10px 20px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .add-btn {
            background-color: #27ae60;
        }

        .add-btn:hover {
            background-color: #2ecc71;
        }

        .update-btn {
            background-color: #f1c40f;
        }

        .update-btn:hover {
            background-color: #f39c12;
        }

        .delete-btn {
            background-color: #e74c3c;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }

        .table-container {
            width: 90%;
            max-width: 1000px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
        }

        th {
            background-color: #2c3e50;
            color: white;
        }

        td {
            background-color: #f9f9f9;
            border-top: 1px solid #ddd;
            padding: 10px;
        }

        .form-container {
            display: inline-block;
        }

        .form-container form {
            margin: 0 5px;
        }

        @media (max-width: 600px) {
            form {
                width: 100%;
            }

            table {
                font-size: 14px;
            }

            th, td {
                padding: 10px;
            }

            button {
                width: 100%;
                padding: 12px;
            }
        }
    </style>
</head>
<body>

<h1>Cities</h1>

<!-- Add City Form -->
<form method="POST" class="add-form" id="city-form" action="{{ route('cities.store') }}">
    @csrf
    <h2 id="form-title">Add City</h2>
    <input type="hidden" name="id" id="city-id">
    <div class="form-row">
        <select id="city-country" name="country_id" required>
            <option disabled selected>Select Country</option>
            @foreach($countries as $country)
                <option value="{{ $country->id }}">{{ $country->country_name }}</option>
            @endforeach
        </select>

        <select id="city-state" name="state_id" required>
            <option disabled selected>Select State</option>
        </select>

        <input name="city_name" id="city-name" placeholder="City Name" required>
    </div>
    <button type="submit" class="add-btn" id="form-button">Add City</button>
</form>

<!-- Cities Table -->
<div class="table-container">
    <h2>All Cities</h2>
    <table id="cities-table" class="display">
        <thead>
            <tr>
                <th>City Name</th>
                <th>State</th>
                <th>Country</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cities as $city)
                <tr>
                    <td>{{ $city->city_name }}</td>
                    <td>{{ $city->state->state_name }}</td>
                    <td>{{ $city->state->country->country_name }}</td>
                    <td>
                        <div class="form-container">
                            <button type="button" class="update-btn"
                                onclick="editCity('{{ $city->id }}', '{{ $city->city_name }}', '{{ $city->state_id }}', '{{ $city->state->country_id }}')">
                                Update
                            </button>
                        </div>

                        <div class="form-container">
                            <form method="POST" action="{{ route('cities.destroy', $city->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-btn">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function () {
        $('#cities-table').DataTable();
    });
</script>

<script>
    function editCity(id, name, stateId, countryId) {
      
        $('#city-id').val(id);
        $('#city-name').val(name);
        $('#form-title').text('Edit City');
        $('#form-button').text('Update City');

      
        $('#city-country').val(countryId).change(); 
        
       
        setTimeout(() => {
            $('#city-state').val(stateId); 
        }, 500);

        
        $('#city-country').prop('disabled', true);
        $('#city-state').prop('disabled', true);

        
        const updateUrl = `/cities/${id}`;
        $('#city-form').attr('action', updateUrl);

       
        if (!$('#city-form input[name="_method"]').length) {
            $('#city-form').append('<input type="hidden" name="_method" value="PUT">');
        } else {
            $('#city-form input[name="_method"]').val('PUT');
        }
    }

   
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



</body>
</html>
