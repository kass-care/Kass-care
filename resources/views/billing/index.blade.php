<!DOCTYPE html>
<html>
<head>
    <title>KASS-Care Billing</title>
</head>

<body>

<h1>KASS-Care Billing</h1>

<table border="1" cellpadding="10">

<tr>
<th>Client</th>
<th>Caregiver</th>
<th>Date</th>
<th>Hours</th>
<th>Rate</th>
<th>Amount</th>
</tr>

@foreach($invoices as $invoice)

<tr>
<td>{{ $invoice['client'] }}</td>
<td>{{ $invoice['caregiver'] }}</td>
<td>{{ $invoice['date'] }}</td>
<td>{{ $invoice['hours'] }}</td>
<td>${{ $invoice['rate'] }}</td>
<td>${{ $invoice['amount'] }}</td>
</tr>

@endforeach

</table>

</body>
</html>
