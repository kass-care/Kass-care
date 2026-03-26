@extends('layouts.app')

@section('content')

<div class="container">

<h2>Care Log for Visit #{{ $visit->id }}</h2>

@if(session('success'))
<div class="alert alert-success">
{{ session('success') }}
</div>
@endif

<form method="POST" action="/visits/{{ $visit->id }}/carelog">

@csrf

<div class="mb-3">
<label class="form-label">Meal Eaten (%)</label>
<input type="number" name="meal_percent" class="form-control">
</div>

<div class="form-check mb-2">
<input type="checkbox" name="shower" value="1" class="form-check-input">
<label class="form-check-label">Shower Given</label>
</div>

<div class="form-check mb-2">
<input type="checkbox" name="bm" value="1" class="form-check-input">
<label class="form-check-label">BM (Bowel Movement)</label>
</div>

<div class="form-check mb-2">
<input type="checkbox" name="medication" value="1" class="form-check-input">
<label class="form-check-label">Medication Given</label>
</div>

<div class="mb-3">
<label class="form-label">Notes</label>
<textarea name="notes" class="form-control"></textarea>
</div>

<button type="submit" class="btn btn-primary">
Save Care Log
</button>

</form>

</div>

@endsection