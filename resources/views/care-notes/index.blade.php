<!DOCTYPE html>
<html>
<head>
    <title>KASS-Care Care Notes</title>
</head>

<body>

<h1>KASS-Care Care Notes</h1>

<table border="1" cellpadding="10">
<tr>
<th>ID</th>
<th>Client</th>
<th>Note</th>
<th>Date</th>
</tr>

@foreach($notes as $note)

<tr>
<td>{{ $note->id }}</td>
<td>{{ $note->client_id }}</td>
<td>{{ $note->note }}</td>
<td>{{ $note->created_at }}</td>
</tr>

@endforeach

</table>

</body>
</html>
