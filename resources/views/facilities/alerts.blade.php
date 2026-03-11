@extends('layout')

@section('content')

<h1>Facility Alerts</h1>

<h2 style="color:red;">Overdue Visits</h2>

@if($overdue->count() == 0)
<p>No overdue visits</p>
@endif

@foreach($overdue as $facility)

<p>
⚠ {{ $facility->name }} — visit was {{ $facility->next_visit }}
</p>

@endforeach


<h2 style="color:orange;">Due Today</h2>

@if($dueToday->count() == 0)
<p>No visits today</p>
@endif

@foreach($dueToday as $facility)

<p>
⏰ {{ $facility->name }} — visit today
</p>

@endforeach


<h2 style="color:green;">Due Soon</h2>

@if($dueSoon->count() == 0)
<p>No visits due soon</p>
@endif

@foreach($dueSoon as $facility)

<p>
📅 {{ $facility->name }} — visit on {{ $facility->next_visit }}
</p>

@endforeach

@endsection
