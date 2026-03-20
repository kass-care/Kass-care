@extends('layouts.app')

@section('content')
<div style="max-width: 900px; margin: 40px auto; padding: 0 20px;">
    <h1 style="font-size: 48px; font-weight: 800; color: #111827; margin-bottom: 30px;">
        Caregiver Check-In
    </h1>

    <form method="POST" action="{{ route('caregiver.checkin.save', $visit->id) }}"
          style="background: #ffffff; border-radius: 18px; padding: 32px; box-shadow: 0 10px 25px rgba(0,0,0,0.08);">
        @csrf

        <div style="margin-bottom: 22px;">
            <label style="display: block; font-size: 16px; font-weight: 600; color: #374151; margin-bottom: 10px;">
                Visit ID
            </label>
            <input
                type="text"
                value="{{ $visit->id }}"
                readonly
                style="width: 100%; padding: 16px; font-size: 20px; border: 1px solid #d1d5db; border-radius: 12px; background: #f9fafb; color: #111827; box-sizing: border-box;"
            >
        </div>

        <div style="margin-bottom: 30px;">
            <label style="display: block; font-size: 16px; font-weight: 600; color: #374151; margin-bottom: 10px;">
                Check-In Time
            </label>
            <input
                type="text"
                name="check_in_time"
                value="{{ now()->format('m/d/Y, h:i A') }}"
                readonly
                style="width: 100%; padding: 16px; font-size: 20px; border: 1px solid #d1d5db; border-radius: 12px; background: #f9fafb; color: #111827; box-sizing: border-box;"
            >
        </div>

        <div style="display: flex; align-items: center; gap: 16px; margin-top: 10px;">
            <button
                type="submit"
                style="background: #16a34a; color: #ffffff; border: none; border-radius: 12px; padding: 14px 28px; font-size: 18px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 10px rgba(22,163,74,0.25);"
            >
                Save Check-In
            </button>

            <a
                href="{{ route('caregiver.dashboard') }}"
                style="display: inline-block; color: #374151; font-size: 18px; font-weight: 600; text-decoration: none;"
            >
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
