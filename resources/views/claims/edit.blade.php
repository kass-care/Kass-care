@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-8">

<div class="flex items-center justify-between mb-6">

<div>
<h1 class="text-3xl font-bold text-gray-900">Edit Claim</h1>
<p class="text-sm text-gray-500 mt-1">Update claim payment, balance, or insurance response.</p>
</div>

<a href="{{ route('claims.index') }}"
class="inline-flex items-center rounded-xl bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-200">
Back to Claims
</a>

</div>

<form method="POST" action="{{ route('claims.update',$claim->id) }}" class="space-y-6">

@csrf
@method('PUT')

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

<div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

<h2 class="text-lg font-semibold text-gray-900 mb-4">Claim Info</h2>

<div class="space-y-4">

<div>
<label class="block text-sm font-medium text-gray-700 mb-1">Patient</label>
<input
type="text"
value="{{ $claim->client->name ?? '' }}"
disabled
class="w-full rounded-xl border border-gray-300 px-4 py-3 bg-gray-100">
</div>

<div>
<label class="block text-sm font-medium text-gray-700 mb-1">Payer</label>
<input
type="text"
name="payer_name"
value="{{ $claim->payer_name }}"
class="w-full rounded-xl border border-gray-300 px-4 py-3">
</div>

<div>
<label class="block text-sm font-medium text-gray-700 mb-1">Claim Number</label>
<input
type="text"
name="claim_number"
value="{{ $claim->claim_number }}"
class="w-full rounded-xl border border-gray-300 px-4 py-3">
</div>

<div>
<label class="block text-sm font-medium text-gray-700 mb-1">Status</label>

<select name="status"
class="w-full rounded-xl border border-gray-300 px-4 py-3">

<option value="submitted" {{ $claim->status=='submitted'?'selected':'' }}>Submitted</option>

<option value="partial" {{ $claim->status=='partial'?'selected':'' }}>Partial</option>

<option value="paid" {{ $claim->status=='paid'?'selected':'' }}>Paid</option>

<option value="denied" {{ $claim->status=='denied'?'selected':'' }}>Denied</option>

<option value="void" {{ $claim->status=='void'?'selected':'' }}>Void</option>

</select>

</div>

</div>
</div>

<div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

<h2 class="text-lg font-semibold text-gray-900 mb-4">Amounts</h2>

<div class="space-y-4">

<div>
<label class="block text-sm font-medium text-gray-700 mb-1">Billed</label>

<input
type="number"
step="0.01"
name="billed_amount"
value="{{ $claim->billed_amount }}"
class="w-full rounded-xl border border-gray-300 px-4 py-3">
</div>

<div>
<label class="block text-sm font-medium text-gray-700 mb-1">Paid</label>

<input
type="number"
step="0.01"
name="paid_amount"
value="{{ $claim->paid_amount }}"
class="w-full rounded-xl border border-gray-300 px-4 py-3">
</div>

<div>
<label class="block text-sm font-medium text-gray-700 mb-1">Adjustment</label>

<input
type="number"
step="0.01"
name="adjustment_amount"
value="{{ $claim->adjustment_amount }}"
class="w-full rounded-xl border border-gray-300 px-4 py-3">
</div>

<div>
<label class="block text-sm font-medium text-gray-700 mb-1">Balance</label>

<input
type="number"
step="0.01"
name="balance_amount"
value="{{ $claim->balance_amount }}"
class="w-full rounded-xl border border-gray-300 px-4 py-3 bg-gray-100">
</div>

</div>

</div>

</div>

<div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

<h2 class="text-lg font-semibold text-gray-900 mb-4">Notes</h2>

<textarea
name="notes"
rows="4"
class="w-full rounded-xl border border-gray-300 px-4 py-3">{{ $claim->notes }}</textarea>

</div>

<div class="flex items-center gap-3">

<button
type="submit"
class="inline-flex items-center rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-700">

Update Claim

</button>

<a
href="{{ route('claims.index') }}"
class="inline-flex items-center rounded-xl bg-gray-100 px-6 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-200">

Cancel

</a>

</div>

</form>

</div>
@endsection
