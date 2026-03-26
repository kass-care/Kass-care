<h1>{{ $caregiver->name }} Timeline</h1>

<h2>Visits</h2>

<table border="1">
<tr>
<th>Date</th>
<th>Start</th>
<th>End</th>
</tr>

@foreach($visits as $visit)

<tr>
<td>{{ $visit->visit_date }}</td>
<td>{{ $visit->start_time }}</td>
<td>{{ $visit->end_time }}</td>
</tr>

@endforeach

</table>


<h2>Billing</h2>

<table border="1">

<tr>
<th>Date</th>
<th>Hours</th>
<th>Amount</th>
</tr>

@foreach($billing as $bill)

<tr>
<td>{{ $bill['date'] }}</td>
<td>{{ $bill['hours'] }}</td>
<td>${{ $bill['amount'] }}</td>
</tr>

@endforeach

</table>
