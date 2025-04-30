<!DOCTYPE html>
<html>
<head>
    <title>Geo Data Manager</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<h1>Geo Data Manager</h1>

{{-- Add Country --}}
<h2>Add Country</h2>
<form method="POST" action="{{ route('countries.store') }}">
    @csrf
    <input name="country_name" placeholder="Country Name" required>
    <button type="submit">Add</button>
</form>

{{-- Add State --}}
<h2>Add State</h2>
<form method="POST" action="{{ route('states.store') }}">
    @csrf
    <select name="country_id" required>
        <option disabled selected>Select Country</option>
        @foreach($countries as $country)
            <option value="{{ $country->id }}">{{ $country->country_name }}</option>
        @endforeach
    </select>
    <input name="state_name" placeholder="State Name" required>
    <button type="submit">Add</button>
</form>

{{-- Add City --}}
<h2>Add City</h2>
<form method="POST" action="{{ route('cities.store') }}">
    @csrf
    <select id="city-country" required>
        <option disabled selected>Select Country</option>
        @foreach($countries as $country)
            <option value="{{ $country->id }}">{{ $country->country_name }}</option>
        @endforeach
    </select>

    <select name="state_id" id="city-state" required>
        <option disabled selected>Select State</option>
    </select>

    <input name="city_name" placeholder="City Name" required>
    <button type="submit">Add</button>
</form>

<hr>

<h2>Countries</h2>
@foreach($countries as $country)
    <form method="POST" action="{{ route('countries.update', $country->id) }}">
        @csrf @method('PUT')
        <input name="country_name" value="{{ $country->country_name }}">
        <button type="submit">Update</button>
    </form>
    <form method="POST" action="{{ route('countries.destroy', $country->id) }}">
        @csrf @method('DELETE')
        <button type="submit">Delete</button>
    </form>
@endforeach

<h2>States</h2>
@foreach($states as $state)
    <form method="POST" action="{{ route('states.update', $state->id) }}">
        @csrf @method('PUT')
        <input name="state_name" value="{{ $state->state_name }}">
        <select name="country_id">
            @foreach($countries as $country)
                <option value="{{ $country->id }}" {{ $state->country_id == $country->id ? 'selected' : '' }}>
                    {{ $country->country_name }}
                </option>
            @endforeach
        </select>
        <button type="submit">Update</button>
    </form>
    <form method="POST" action="{{ route('states.destroy', $state->id) }}">
        @csrf @method('DELETE')
        <button type="submit">Delete</button>
    </form>
@endforeach

<h2>Cities</h2>
@foreach($cities as $city)
    <form method="POST" action="{{ route('cities.update', $city->id) }}">
        @csrf @method('PUT')
        <input name="city_name" value="{{ $city->city_name }}">
        <select name="state_id">
            @foreach($states as $state)
                <option value="{{ $state->id }}" {{ $city->state_id == $state->id ? 'selected' : '' }}>
                    {{ $state->state_name }}
                </option>
            @endforeach
        </select>
        <button type="submit">Update</button>
    </form>
    <form method="POST" action="{{ route('cities.destroy', $city->id) }}">
        @csrf @method('DELETE')
        <button type="submit">Delete</button>
    </form>
@endforeach

<script>
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
