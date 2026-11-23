@extends('layouts.app')

@section('title', 'Staff credentials')

@section('content')
<div class="kc-page">
    <div class="kc-page-head">
        <div>
            <h1>Staff credentials</h1>
            <p>Keep licences, CPR, TB tests and background checks up to date. Anything expiring within 30 days shows on your home screen.</p>
        </div>
    </div>

    <div class="kc-tiles" style="grid-template-columns:repeat(3,minmax(0,1fr))">
        <a class="kc-tile {{ $counts['expired'] ? 'kc-tile--bad' : '' }}" href="{{ route('kc.credentials.index', ['show' => 'attention']) }}"><b>{{ $counts['expired'] }}</b><span>Expired</span></a>
        <a class="kc-tile {{ $counts['expiring'] ? 'kc-tile--warn' : '' }}" href="{{ route('kc.credentials.index', ['show' => 'attention']) }}"><b>{{ $counts['expiring'] }}</b><span>Expiring soon</span></a>
        <a class="kc-tile" href="{{ route('kc.credentials.index', ['show' => 'all']) }}"><b>{{ $counts['all'] }}</b><span>On file</span></a>
    </div>

    <div class="kc-grid kc-grid--2-1">
        <section class="kc-card">
            <div class="kc-card-head" style="padding-bottom:10px">
                <h2>{{ $filter === 'attention' ? 'Needs renewing' : 'All credentials' }}</h2>
            </div>
            <div class="kc-tabs" style="padding:0 20px">
                <a href="{{ route('kc.credentials.index', ['show' => 'attention']) }}" @if($filter === 'attention') aria-current="page" @endif>Needs renewing</a>
                <a href="{{ route('kc.credentials.index', ['show' => 'all']) }}" @if($filter !== 'attention') aria-current="page" @endif>All</a>
            </div>

            @if($credentials->isEmpty())
                <div class="kc-empty">
                    <strong>{{ $filter === 'attention' ? 'Nothing needs renewing' : 'No credentials on file yet' }}</strong>
                    {{ $filter === 'attention' ? 'Every credential with a date is valid for at least 30 more days.' : 'Add your team\'s licences and checks using the form.' }}
                </div>
            @else
                <div class="kc-table-wrap">
                    <table class="kc-table">
                        <thead><tr><th>Staff</th><th>Credential</th><th>Expires</th><th></th></tr></thead>
                        <tbody>
                        @foreach($credentials as $c)
                            @php
                                $label = ['expired' => 'Expired', 'expiring' => 'Expiring soon', 'valid' => 'Valid', 'no_expiry' => 'No expiry'][$c->state];
                            @endphp
                            <tr>
                                <td><strong>{{ $c->user->name ?? 'Removed user' }}</strong><p class="kc-list-sub">{{ ucfirst($c->user->role ?? '') }}</p></td>
                                <td>{{ $c->type }}@if($c->reference)<p class="kc-list-sub">#{{ $c->reference }}</p>@endif</td>
                                <td>
                                    <span class="kc-pill kc-pill--{{ $c->state }}">{{ $label }}</span>
                                    @if($c->expires_on)<p class="kc-list-sub">{{ $c->expires_on->format('M j, Y') }}</p>@endif
                                </td>
                                <td style="text-align:right">
                                    <form method="POST" action="{{ route('kc.credentials.destroy', $c) }}" onsubmit="return confirm('Remove this credential?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="kc-btn kc-btn--ghost kc-btn--sm" type="submit">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        <section class="kc-card">
            <div class="kc-card-head"><h2>Add a credential</h2></div>
            <form method="POST" action="{{ route('kc.credentials.store') }}" class="kc-form">
                @csrf
                <div class="kc-field">
                    <label for="user_id">Staff member</label>
                    <select id="user_id" name="user_id" class="kc-input" required>
                        <option value="">Choose a person</option>
                        @foreach($staff as $person)
                            <option value="{{ $person->id }}" @selected(old('user_id') == $person->id)>{{ $person->name }} ({{ $person->role }})</option>
                        @endforeach
                    </select>
                    @error('user_id')<p class="kc-field-error">{{ $message }}</p>@enderror
                </div>
                <div class="kc-field">
                    <label for="type">Type</label>
                    <select id="type" name="type" class="kc-input" required>
                        @foreach($types as $type)
                            <option value="{{ $type }}" @selected(old('type') === $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="kc-field">
                    <label for="reference">Number (optional)</label>
                    <input id="reference" name="reference" class="kc-input" value="{{ old('reference') }}">
                </div>
                <div class="kc-form-grid">
                    <div class="kc-field">
                        <label for="issued_on">Issued</label>
                        <input id="issued_on" type="date" name="issued_on" class="kc-input" value="{{ old('issued_on') }}">
                    </div>
                    <div class="kc-field">
                        <label for="expires_on">Expires</label>
                        <input id="expires_on" type="date" name="expires_on" class="kc-input" value="{{ old('expires_on') }}">
                        @error('expires_on')<p class="kc-field-error">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="kc-field">
                    <label for="notes">Notes (optional)</label>
                    <textarea id="notes" name="notes" class="kc-input">{{ old('notes') }}</textarea>
                </div>
                <button class="kc-btn kc-btn--primary kc-btn--block" type="submit">Save credential</button>
            </form>
        </section>
    </div>
</div>
@endsection
