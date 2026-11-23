@extends('layouts.app')

@section('title', 'Family access')

@section('content')
@php $temp = session('kc_temp_password'); @endphp
<div class="kc-page">
    <div class="kc-page-head">
        <div>
            <h1>Family access</h1>
            <p>Give relatives a safe, read-only view of their loved one's visits and care notes. They only ever see the one patient you link them to.</p>
        </div>
    </div>

    @if($temp)
        <div class="kc-callout" role="status">
            <p>Share these sign-in details with the family member. The password is shown only once, so copy it now.</p>
            <p style="color:#fff;margin-bottom:6px"><strong>Email:</strong> {{ $temp['email'] }}</p>
            <code>{{ $temp['password'] }}</code>
            <p style="margin:10px 0 0">They can change it after signing in, from their profile.</p>
        </div>
    @endif

    <div class="kc-grid kc-grid--2-1">
        <section class="kc-card">
            <div class="kc-card-head"><h2>People with access</h2></div>
            @if($family->isEmpty())
                <div class="kc-empty"><strong>No family members yet</strong>Families who can see updates stay calmer and call the office less. Invite the first one using the form.</div>
            @else
                <div class="kc-table-wrap">
                    <table class="kc-table">
                        <thead><tr><th>Name</th><th>Follows</th><th>Contact</th><th></th></tr></thead>
                        <tbody>
                        @foreach($family as $person)
                            <tr>
                                <td><strong>{{ $person->name }}</strong>@if($person->relationship)<p class="kc-list-sub">{{ $person->relationship }}</p>@endif</td>
                                <td>{{ $person->familyClient->name ?? 'Patient removed' }}</td>
                                <td>{{ $person->email }}@if($person->phone)<p class="kc-list-sub">{{ $person->phone }}</p>@endif</td>
                                <td style="text-align:right">
                                    <form method="POST" action="{{ route('kc.family.destroy', $person) }}" onsubmit="return confirm('Remove this person\'s access?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="kc-btn kc-btn--ghost kc-btn--sm" type="submit">Remove access</button>
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
            <div class="kc-card-head"><h2>Invite a family member</h2></div>
            <form method="POST" action="{{ route('kc.family.store') }}" class="kc-form">
                @csrf
                <div class="kc-field">
                    <label for="client_id">Patient they follow</label>
                    <select id="client_id" name="client_id" class="kc-input" required>
                        <option value="">Choose a patient</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" @selected(old('client_id') == $client->id)>{{ $client->name }}</option>
                        @endforeach
                    </select>
                    @error('client_id')<p class="kc-field-error">{{ $message }}</p>@enderror
                </div>
                <div class="kc-field">
                    <label for="name">Full name</label>
                    <input id="name" name="name" class="kc-input" value="{{ old('name') }}" required>
                    @error('name')<p class="kc-field-error">{{ $message }}</p>@enderror
                </div>
                <div class="kc-field">
                    <label for="relationship">Relationship</label>
                    <input id="relationship" name="relationship" class="kc-input" value="{{ old('relationship') }}" placeholder="Daughter, son, spouse…">
                </div>
                <div class="kc-field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" class="kc-input" value="{{ old('email') }}" required>
                    @error('email')<p class="kc-field-error">{{ $message }}</p>@enderror
                </div>
                <div class="kc-field">
                    <label for="phone">Phone (optional)</label>
                    <input id="phone" name="phone" class="kc-input" value="{{ old('phone') }}" inputmode="tel">
                </div>
                <button class="kc-btn kc-btn--primary kc-btn--block" type="submit">Create family access</button>
                <p class="kc-hint">You'll get a one-time password to share with them.</p>
            </form>
        </section>
    </div>
</div>
@endsection
