@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-indigo-50 py-10">
    <div class="max-w-7xl mx-auto px-6">

        <div class="bg-white rounded-2xl shadow-xl p-8">

            <h1 class="text-3xl font-bold text-indigo-700 mb-6">
                Visit Calendar
            </h1>

            <div id="calendar"></div>

        </div>

    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    let calendarEl = document.getElementById('calendar');

    let calendar = new FullCalendar.Calendar(calendarEl, {

        initialView: 'dayGridMonth',

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },

        buttonText: {
            today: 'Today',
            month: 'Month',
            week: 'Week',
            day: 'Day'
        },

        dateClick: function(info) {
            window.location.href = "/visits/create?date=" + info.dateStr;
        },

        events: '/calendar/events'

    });

    calendar.render();

});

</script>
@endsection
