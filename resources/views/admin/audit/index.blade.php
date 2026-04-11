@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-6 py-8">

<h1 class="text-2xl font-bold mb-6">System Audit Logs</h1>

<div class="bg-white shadow rounded-xl overflow-hidden">

<table class="min-w-full text-sm">

<thead class="bg-gray-100">
<tr>
<th class="px-4 py-3 text-left">Time</th>
<th class="px-4 py-3 text-left">User</th>
<th class="px-4 py-3 text-left">Role</th>
<th class="px-4 py-3 text-left">Action</th>
<th class="px-4 py-3 text-left">Client</th>
<th class="px-4 py-3 text-left">IP</th>
</tr>
</thead>

<tbody>

@foreach($logs as $log)
<tr class="border-t">

<td class="px-4 py-2">
{{ $log->created_at }}
</td>

<td class="px-4 py-2">
{{ $log->user_name }}
</td>

<td class="px-4 py-2">
{{ $log->user_role }}
</td>

<td class="px-4 py-2">
{{ $log->action }}
</td>

<td class="px-4 py-2">
{{ $log->client_name }}
</td>

<td class="px-4 py-2">
{{ $log->ip_address }}
</td>

</tr>
@endforeach

</tbody>

</table>

</div>

<div class="mt-6">
{{ $logs->links() }}
</div>

</div>

@endsection
