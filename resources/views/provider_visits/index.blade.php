@extends('layouts.app')

@section('content')

<h2>Provider Facility Visits</h2>

<a href="/provider-visits/create" class="btn btn-primary mb-3">
Schedule New Visit
</a>

<table class="table table-bordered">

<thead>
<tr>
<th>Facility</th>
<th>Visit Date</th>
<th>Next Visit Due</th>
<th>Notes</th>
</tr>
</thead>

<tbody>

@foreach($visits as $visit)

<tr>

<td>
{{ $visit->facility->name ?? 'Facility' }}
</td>

<td>
{{ $visit->visit_date }}
</td>

<td>
{{ $visit->next_visit_due }}
</td>

<td>
{{ $visit->notes }}
</td>

</tr>

@endforeach

</tbody>

</table>

@endsection
