@extends('layouts.app')

@section('content')

<h2>Schedule Provider Visit</h2>

<form method="POST" action="/provider-visits">

@csrf

<div class="mb-3">
<label class="form-label">Facility</label>

<select name="facility_id" class="form-control" required>

<option value="">Select Facility</option>

@foreach($facilities as $facility)

<option value="{{ $facility->id }}">
{{ $facility->name }}
</option>

@endforeach

</select>

</div>


<div class="mb-3">
<label class="form-label">Visit Date</label>

<input
type="date"
name="visit_date"
class="form-control"
required>

</div>


<div class="mb-3">
<label class="form-label">Notes</label>

<textarea
name="notes"
class="form-control"
rows="3"></textarea>
</div>


<button class="btn btn-success">
Save Visit
</button>

<a href="/provider-visits" class="btn btn-secondary">
Cancel
</a>

</form>

@endsection
