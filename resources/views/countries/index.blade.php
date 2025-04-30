<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Countries</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <style>
        /* Reset some default styles */
        body, h1, h2, table {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fa;
            color: #333;
            padding: 20px;
        }

        /* Main title */
        h1 {
            text-align: center;
            color: #2d3a45;
        }

        /* Add country form styling */
        h2 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .add-form {
            background-color: #fff;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 20px auto 20px;
        }

        input[type="text"] {
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
        /* add button styling */
        .add-btn {
            background-color: #27ae60; /* Yellow */
            margin-left: 10px;
        }

        .add-btn:hover {
            background-color: #2ecc71;
        }
        /* Update Button Styling (Yellow) */
        .update-btn {
            background-color: #f1c40f; /* Yellow */
        }

        .update-btn:hover {
            background-color: #f39c12;
        }

        /* Delete Button Styling (Red) */
        .delete-btn {
            background-color: #e74c3c; /* Red */
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }

        /* Table Styling */
        .table-container {
            width: 40%;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px; 
            border-radius: 5px;
        }


        /* table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        } */

        /* th, td {
            padding: 12px;
            text-align: left;
        } */

        th {
            background-color: #2c3e50;
            color: white;
            margin-top: 5px;
        }

        td {
            background-color: #f9f9f9;
            border-top: 1px solid #ddd;
        }

        /* Form container for actions */
        .form-container {
            display: inline-block;
        }

        .form-container form {
            margin: 0 5px;
        }

        /* Media query for smaller screens */
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

<h1>Countries</h1>

<!-- Add Country Form -->

<form method="POST" class="add-form" id="country-form" action="{{ route('countries.store') }}">
    @csrf
    <h2 id="form-title">Add New</h2>
    <input type="hidden" name="id" id="country-id">
    <input name="country_name" id="country-name" placeholder="Country Name" required>
    <button type="submit" class="add-btn" id="form-button">Add Country</button>
</form>


<!-- Countries Table -->

<div class="table-container">
    <table id="countries-table">
        <thead>
            <tr>
                <th>Country Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($countries as $country)
                <tr>
                    <td>{{ $country->country_name }}</td>
                    <td>
                        <div class="form-container">
                            <!-- <form method="POST" action="{{ route('countries.update', $country->id) }}"> -->
                                @csrf
                                @method('PUT')
                                <input name="country_name" type="hidden" value="{{ $country->country_name }}" required>
                                <button type="button" class="update-btn" onclick="editCountry('{{ $country->id }}', '{{ $country->country_name }}')">Update</button>
                            <!-- </form> -->
                        </div>
                        <div class="form-container">
                            <form method="POST" action="{{ route('countries.destroy', $country->id) }}">
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
        $('#countries-table').DataTable();
    });
</script>

<script>
    function editCountry(id, name) {
        $('#country-id').val(id);
        $('#country-name').val(name);
        $('#form-title').text('Edit Country');
        $('#form-button').text('Submit');

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


</body>
</html>
