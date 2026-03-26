<h1>Assign Caregiver</h1>

<h2>Client: {{ $client->name }}</h2>

<form method="POST" action="{{ route('assignments.store') }}">
    @csrf

    <input type="hidden" name="client_id" value="{{ $client->id }}">

    <label>Select Caregiver</label>

    <select name="caregiver_id">
        @foreach($caregivers as $caregiver)
            <option value="{{ $caregiver->id }}">
                {{ $caregiver->name }}
            </option>
        @endforeach
    </select>

    <br><br>

    <button type="submit">Assign Caregiver</button>

</form>

<br>

<a href="/clients/{{ $client->id }}">Back to Client</a>
