@extends('layouts.app')

@section('content')
<div style="max-width: 900px; margin: 40px auto; padding: 0 20px;">
    <h1 style="font-size: 42px; font-weight: 800; color: #111827; margin-bottom: 24px;">
        Create Visit
    </h1>

    @if ($errors->any())
        <div style="background: #fee2e2; color: #991b1b; padding: 16px; border-radius: 12px; margin-bottom: 20px;">
            <strong>Please fix these errors:</strong>
            <ul style="margin-top: 10px; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('visits.store') }}"
          style="background: #ffffff; border-radius: 18px; padding: 32px; box-shadow: 0 10px 25px rgba(0,0,0,0.08);">
        @csrf

        <div style="margin-bottom: 22px;">
            <label style="display:block; font-size:16px; font-weight:600; margin-bottom:10px;">
                Client
            </label>
            <select name="client_id" required
                    style="width:100%; padding:16px; font-size:18px; border:1px solid #d1d5db; border-radius:12px; background:#fff; box-sizing:border-box;">
                <option value="">Select client</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                        {{ $client->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 22px;">
            <label style="display:block; font-size:16px; font-weight:600; margin-bottom:10px;">
                Caregiver
            </label>
            <select name="caregiver_id" required
                    style="width:100%; padding:16px; font-size:18px; border:1px solid #d1d5db; border-radius:12px; background:#fff; box-sizing:border-box;">
                <option value="">Select caregiver</option>
                @foreach($caregivers as $caregiver)
                    <option value="{{ $caregiver->id }}" {{ old('caregiver_id') == $caregiver->id ? 'selected' : '' }}>
                        {{ $caregiver->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 22px;">
            <label style="display:block; font-size:16px; font-weight:600; margin-bottom:10px;">
                Activity
            </label>
            <input
                type="text"
                name="activity"
                value="{{ old('activity') }}"
                placeholder="Enter visit activity"
                required
                style="width:100%; padding:16px; font-size:18px; border:1px solid #d1d5db; border-radius:12px; box-sizing:border-box;"
            >
        </div>

        <div style="margin-bottom: 22px;">
            <label style="display:block; font-size:16px; font-weight:600; margin-bottom:10px;">
                Visit Date
            </label>
            <input
                type="date"
                name="visit_date"
                value="{{ old('visit_date') }}"
                required
                style="width:100%; padding:16px; font-size:18px; border:1px solid #d1d5db; border-radius:12px; box-sizing:border-box;"
            >
        </div>

        <div style="margin-bottom: 30px;">
            <label style="display:block; font-size:16px; font-weight:600; margin-bottom:10px;">
                Status
            </label>
            <select name="status" required
                    style="width:100%; padding:16px; font-size:18px; border:1px solid #d1d5db; border-radius:12px; background:#fff; box-sizing:border-box;">
                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="missed" {{ old('status') == 'missed' ? 'selected' : '' }}>Missed</option>
            </select>
        </div>

        <div style="display:flex; align-items:center; gap:16px;">
            <button
                type="submit"
                style="background:#2563eb; color:#fff; border:none; border-radius:12px; padding:14px 28px; font-size:18px; font-weight:700; cursor:pointer;">
                Save Visit
            </button>

            <a href="{{ route('visits.index') }}"
               style="color:#374151; font-size:18px; font-weight:600; text-decoration:none;">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
