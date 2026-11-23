@props([
    'client' => null,
    'visit' => null,
    'sticky' => true,
])
{{--
    Patient identity banner (photo, name, room, MRN, age, allergies).
    Stays at the top of the screen while staff scroll, so they always
    know which patient they are charting on.
--}}
@if($client)
    @php
        $name = $client->name
            ?? trim(($client->first_name ?? '') . ' ' . ($client->last_name ?? ''))
            ?: 'Patient';
        $photo = $client->photo ?? null;
        $dobRaw = $client->date_of_birth ?? $client->dob ?? null;
        $dob = null;
        $age = $client->age ?? null;
        try {
            if ($dobRaw) {
                $dobDate = \Carbon\Carbon::parse($dobRaw);
                $dob = $dobDate->format('M j, Y');
                $age = $dobDate->age;
            }
        } catch (\Throwable $e) {}
        $allergies = trim((string) ($client->allergies ?? ''));
        $noAllergies = $allergies === '' || in_array(strtolower($allergies), ['none', 'nka', 'nkda', 'no known allergies', 'n/a', 'na'], true);
        $initials = collect(explode(' ', $name))->filter()->take(2)->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))->implode('');
    @endphp

    <div class="kc-pid {{ $sticky ? 'kc-pid--sticky' : '' }}" role="region" aria-label="Patient: {{ $name }}">
        @if($photo)
            <img class="kc-pid-photo" src="{{ asset('storage/' . $photo) }}" alt="Photo of {{ $name }}">
        @else
            <span class="kc-pid-photo kc-pid-photo--empty" aria-hidden="true">{{ $initials ?: 'P' }}</span>
        @endif

        <div class="kc-pid-main">
            <div class="kc-pid-name">{{ $name }}</div>
            <div class="kc-pid-facts">
                @if(!empty($client->room))<span><b>Room</b> {{ $client->room }}</span>@endif
                @if($age)<span><b>Age</b> {{ $age }}</span>@endif
                @if($dob)<span><b>DOB</b> {{ $dob }}</span>@endif
                @if(!empty($client->gender))<span>{{ ucfirst($client->gender) }}</span>@endif
                @if(!empty($client->mrn))<span><b>MRN</b> {{ $client->mrn }}</span>@endif
            </div>
        </div>

        <div class="kc-pid-allergy {{ $noAllergies ? 'is-none' : '' }}">
            @if($noAllergies)
                <span>{{ $allergies === '' ? 'Allergies not recorded' : 'No known allergies' }}</span>
            @else
                <x-kc-icon name="alert" />
                <span><b>Allergies:</b> {{ \Illuminate\Support\Str::limit($allergies, 80) }}</span>
            @endif
        </div>
    </div>
@endif
