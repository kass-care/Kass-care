@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-10">

    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-black text-slate-900 tracking-tighter italic">
            Provider Schedule <span class="text-indigo-600">Calendar</span>
        </h1>

        <a href="{{ route('visits.create') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-bold text-xs uppercase">
            + Add Visit
        </a>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-100 overflow-hidden">

        <div id="calendar"></div>

    </div>
</div>


<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    var calendarEl = document.getElementById('calendar');

    var events = {!! $events !!};

    var calendar = new FullCalendar.Calendar(calendarEl, {

        initialView: 'dayGridMonth',

        height: 650,

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },

        events: events,

        eventClick: function(info) {

            alert("Visit: " + info.event.title);

        }

    });

    calendar.render();

});

</script>

@endsection
