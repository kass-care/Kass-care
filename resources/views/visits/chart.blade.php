@extends('layouts.app')

@section('content')

<div class="container">

<h2>Visit Charting</h2>

<h3>Client: {{ $visit->client->name }}</h3>

<form method="POST" action="/visits/end/{{ $visit->id }}">
@csrf

<div>

<label>Meal %</label>
<input type="text" name="meal">

</div>

<div>

<label>Shower</label>
<select name="shower">
<option value="Yes">Yes</option>
<option value="No">No</option>
</select>

</div>

<div>

<label>BM</label>
<select name="bm">
<option value="Yes">Yes</option>
<option value="No">No</option>
</select>

</div>

<div>

<label>Notes</label>
<textarea name="notes"></textarea>

</div>

<br>

<button type="submit">
End Visit
</button>

</form>

</div>

@endsection
