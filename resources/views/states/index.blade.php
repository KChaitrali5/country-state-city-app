<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>States</title>
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
            max-width: 500px;
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

        select, input[type="text"] {
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
            margin-left: 10px;
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

<h1>States</h1>

<!-- Add/Update State Form -->
<form method="POST" class="add-form" id="state-form" action="{{ route('states.store') }}">
    @csrf
    <h2 id="form-title">Add New State</h2>
    <input type="hidden" name="id" id="state-id">

    <div class="form-row">
        <select name="country_id" id="country-select" required>
            <option value="" selected disabled>Select Country</option>
            @foreach($countries as $country)
                <option value="{{ $country->id }}">{{ $country->country_name }}</option>
            @endforeach
        </select>

        <input name="state_name" type="text" id="state-name" placeholder="State Name" required>
    </div>

    <button type="submit" class="add-btn" id="form-button">Add State</button>
</form>

<!-- States Table -->
<div class="table-container">
    <table id="states-table" class="display">
        <thead>
            <tr>
                <th>State Name</th>
                <th>Country</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($states as $state)
                <tr>
                    <td>{{ $state->state_name }}</td>
                    <td>{{ $state->country->country_name }}</td>
                    <td>
                        <div class="form-container">
                            @csrf
                            @method('PUT')
                            <button type="button" class="update-btn" onclick="editState('{{ $state->id }}', '{{ $state->state_name }}', '{{ $state->country_id }}')">Update</button>
                        </div>
                        <div class="form-container">
                        <form method="POST" action="{{ route('states.destroy', $state->id) }}">
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

        // Inject or update the PUT method
        if (!$('#state-form input[name="_method"]').length) {
            $('#state-form').append('<input type="hidden" name="_method" value="PUT">');
        } else {
            $('#state-form input[name="_method"]').val('PUT');
        }
    }
</script>

</body>
</html>