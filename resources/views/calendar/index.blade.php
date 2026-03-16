@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-7xl mx-auto px-6">

        <div class="mb-8">
            <h1 class="text-4xl font-black tracking-tight text-indigo-700">
                Visit Calendar
            </h1>
            <p class="text-slate-500 mt-2 text-sm">
                Manage scheduling, caregiver assignments, visit completion, and documentation.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="rounded-3xl bg-gradient-to-br from-indigo-600 to-indigo-800 p-6 text-white shadow-xl">
                <p class="text-xs uppercase tracking-[0.25em] text-indigo-100">Visits Today</p>
                <h2 class="mt-4 text-4xl font-black">{{ $todayVisits ?? 0 }}</h2>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200">
                <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Completed Today</p>
                <h2 class="mt-4 text-3xl font-black text-emerald-600">{{ $completedToday ?? 0 }}</h2>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200">
                <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Missing Care Logs</p>
                <h2 class="mt-4 text-3xl font-black text-amber-500">{{ $missingCareLogs ?? 0 }}</h2>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200">
                <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Missed Visits</p>
                <h2 class="mt-4 text-3xl font-black text-red-600">{{ $missedToday ?? 0 }}</h2>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-700 shadow-sm">
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        @if(($missingCareLogs ?? 0) > 0 || ($missedToday ?? 0) > 0)
            <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                @if(($missingCareLogs ?? 0) > 0)
                    <div class="rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 text-amber-800 shadow-sm">
                        <p class="font-bold uppercase tracking-wider text-xs mb-1">Documentation Alert</p>
                        <p>{{ $missingCareLogs }} completed visit(s) still need care logs.</p>
                    </div>
                @endif

                @if(($missedToday ?? 0) > 0)
                    <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-800 shadow-sm">
                        <p class="font-bold uppercase tracking-wider text-xs mb-1">Missed Visit Alert</p>
                        <p>{{ $missedToday }} visit(s) were missed today.</p>
                    </div>
                @endif
            </div>
        @endif

        <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/40 border border-slate-200 p-8">
            <div id="calendar"></div>
        </div>
    </div>
</div>

<div id="visitModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-3xl rounded-[2rem] shadow-2xl border border-slate-200 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-black text-slate-900">Visit Details</h2>
                <p class="text-sm text-slate-500 mt-1">Kass Care scheduling panel</p>
            </div>
            <button type="button" onclick="closeVisitModal()" class="text-slate-400 hover:text-slate-700 text-2xl font-bold">
                &times;
            </button>
        </div>

        <div class="px-8 py-6 space-y-6">
            <input type="hidden" id="modalVisitId">

            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-slate-400 mb-1">Visit</p>
                <p id="modalVisitTitle" class="text-lg font-bold text-slate-900">—</p>
            </div>

            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-slate-400 mb-1">Date & Time</p>
                <p id="modalVisitDate" class="text-base text-slate-700">—</p>
            </div>

            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-slate-400 mb-1">Status</p>
                <span id="modalVisitStatus" class="inline-flex rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wider bg-blue-100 text-blue-700">
                    Scheduled
                </span>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-[0.2em] text-slate-400 mb-2">
                    Assign Caregiver
                </label>
                <select id="caregiverSelect" class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Select caregiver</option>
                    @foreach($caregivers as $caregiver)
                        <option value="{{ $caregiver->id }}">{{ $caregiver->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400 mb-1">Check In Time</p>
                    <p id="modalCheckInTime" class="text-sm text-slate-700">Not checked in</p>
                </div>

                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400 mb-1">Check Out Time</p>
                    <p id="modalCheckOutTime" class="text-sm text-slate-700">Not checked out</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400 mb-1">Check In GPS</p>
                    <p id="modalCheckInGps" class="text-sm text-slate-700">Not captured</p>
                </div>

                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400 mb-1">Check Out GPS</p>
                    <p id="modalCheckOutGps" class="text-sm text-slate-700">Not captured</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-3">
                <button type="button" id="checkInBtn" class="rounded-2xl bg-sky-600 px-5 py-3 text-sm font-bold uppercase tracking-wider text-white shadow-lg hover:bg-sky-700 transition">
                    Check In
                </button>

                <button type="button" id="checkOutBtn" class="rounded-2xl bg-slate-700 px-5 py-3 text-sm font-bold uppercase tracking-wider text-white shadow-lg hover:bg-slate-800 transition">
                    Check Out
                </button>

                <button type="button" id="assignBtn" class="rounded-2xl bg-indigo-600 px-5 py-3 text-sm font-bold uppercase tracking-wider text-white shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition">
                    Assign
                </button>

                <button type="button" id="completeBtn" class="rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-bold uppercase tracking-wider text-white shadow-lg hover:bg-emerald-700 transition">
                    Complete
                </button>

                <button type="button" id="missedBtn" class="rounded-2xl bg-red-600 px-5 py-3 text-sm font-bold uppercase tracking-wider text-white shadow-lg hover:bg-red-700 transition">
                    Missed
                </button>

                <a id="careLogBtn" href="#" class="rounded-2xl bg-sky-700 px-5 py-3 text-sm font-bold uppercase tracking-wider text-white shadow-lg hover:bg-sky-800 transition text-center">
                    Care Log
                </a>
            </div>
        </div>

        <div class="px-8 py-5 bg-slate-50 border-t border-slate-200 flex justify-end">
            <button type="button" onclick="closeVisitModal()" class="rounded-2xl bg-slate-800 px-5 py-3 text-sm font-bold uppercase tracking-wider text-white hover:bg-slate-900 transition">
                Close
            </button>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<script>
function closeVisitModal() {
    const modal = document.getElementById('visitModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function applyStatusBadge(status) {
    const statusEl = document.getElementById('modalVisitStatus');
    statusEl.textContent = status;
    statusEl.className = 'inline-flex rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wider';

    const s = String(status).toLowerCase();

    if (s === 'completed') {
        statusEl.classList.add('bg-emerald-100', 'text-emerald-700');
    } else if (s === 'missed') {
        statusEl.classList.add('bg-red-100', 'text-red-700');
    } else if (s === 'assigned') {
        statusEl.classList.add('bg-violet-100', 'text-violet-700');
    } else {
        statusEl.classList.add('bg-blue-100', 'text-blue-700');
    }
}

function formatGps(lat, lng) {
    if (!lat || !lng) return 'Not captured';
    return lat + ', ' + lng;
}

function openVisitModal(eventData) {
    document.getElementById('modalVisitId').value = eventData.id;
    document.getElementById('modalVisitTitle').textContent = eventData.title;
    document.getElementById('modalVisitDate').textContent = eventData.dateText;
    document.getElementById('caregiverSelect').value = eventData.caregiverId || '';
    document.getElementById('careLogBtn').href = '/care-logs/create?visit_id=' + eventData.id;

    document.getElementById('modalCheckInTime').textContent = eventData.checkInTime || 'Not checked in';
    document.getElementById('modalCheckOutTime').textContent = eventData.checkOutTime || 'Not checked out';
    document.getElementById('modalCheckInGps').textContent = formatGps(eventData.checkInLat, eventData.checkInLng);
    document.getElementById('modalCheckOutGps').textContent = formatGps(eventData.checkOutLat, eventData.checkOutLng);

    applyStatusBadge(eventData.status || 'Scheduled');

    const modal = document.getElementById('visitModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function getCurrentLocation(callback) {
    if (!navigator.geolocation) {
        callback(null, null);
        return;
    }

    navigator.geolocation.getCurrentPosition(
        function(position) {
            callback(position.coords.latitude, position.coords.longitude);
        },
        function() {
            callback(null, null);
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        }
    );
}

document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    const calendar = new FullCalendar.Calendar(calendarEl, {
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
        height: 'auto',
        eventDisplay: 'block',

        events: function(fetchInfo, successCallback, failureCallback) {
            fetch('/calendar/events')
                .then(response => response.json())
                .then(data => {
                    const events = data.map(event => ({
                        id: event.id,
                        title: event.title,
                        start: event.start,
                        color: event.color,
                        extendedProps: {
                            status: event.status,
                            caregiver_id: event.caregiver_id,
                            check_in_time: event.check_in_time,
                            check_out_time: event.check_out_time,
                            check_in_latitude: event.check_in_latitude,
                            check_in_longitude: event.check_in_longitude,
                            check_out_latitude: event.check_out_latitude,
                            check_out_longitude: event.check_out_longitude
                        }
                    }));

                    successCallback(events);
                })
                .catch(error => failureCallback(error));
        },

        eventDidMount: function(info) {
            info.el.style.cursor = 'pointer';
            info.el.title = 'Click to open visit';
        },

        eventClick: function(info) {
            info.jsEvent.preventDefault();

            openVisitModal({
                id: info.event.id,
                title: info.event.title,
                dateText: info.event.start ? info.event.start.toLocaleString() : 'No date available',
                status: info.event.extendedProps.status || 'Scheduled',
                caregiverId: info.event.extendedProps.caregiver_id || '',
                checkInTime: info.event.extendedProps.check_in_time || '',
                checkOutTime: info.event.extendedProps.check_out_time || '',
                checkInLat: info.event.extendedProps.check_in_latitude || '',
                checkInLng: info.event.extendedProps.check_in_longitude || '',
                checkOutLat: info.event.extendedProps.check_out_latitude || '',
                checkOutLng: info.event.extendedProps.check_out_longitude || ''
            });
        }
    });

    calendar.render();

    const checkInBtn = document.getElementById('checkInBtn');
    const checkOutBtn = document.getElementById('checkOutBtn');
    const assignBtn = document.getElementById('assignBtn');
    const completeBtn = document.getElementById('completeBtn');
    const missedBtn = document.getElementById('missedBtn');

    if (checkInBtn) {
        checkInBtn.addEventListener('click', function () {
            const visitId = document.getElementById('modalVisitId').value;

            getCurrentLocation(function(latitude, longitude) {
                fetch('/calendar/check-in', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        visit_id: visitId,
                        latitude: latitude,
                        longitude: longitude
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Unable to check in.');
                    }
                })
                .catch(() => {
                    alert('Something went wrong during check-in.');
                });
            });
        });
    }

    if (checkOutBtn) {
        checkOutBtn.addEventListener('click', function () {
            const visitId = document.getElementById('modalVisitId').value;

            getCurrentLocation(function(latitude, longitude) {
                fetch('/calendar/check-out', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        visit_id: visitId,
                        latitude: latitude,
                        longitude: longitude
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Unable to check out.');
                    }
                })
                .catch(() => {
                    alert('Something went wrong during check-out.');
                });
            });
        });
    }

    if (assignBtn) {
        assignBtn.addEventListener('click', function () {
            const visitId = document.getElementById('modalVisitId').value;
            const caregiverId = document.getElementById('caregiverSelect').value;

            fetch('/calendar/assign-caregiver', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    visit_id: visitId,
                    caregiver_id: caregiverId
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Unable to assign caregiver.');
                }
            })
            .catch(() => {
                alert('Something went wrong while assigning caregiver.');
            });
        });
    }

    if (completeBtn) {
        completeBtn.addEventListener('click', function () {
            const visitId = document.getElementById('modalVisitId').value;

            fetch('/calendar/update-visit-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    visit_id: visitId,
                    status: 'completed'
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Unable to update visit.');
                }
            })
            .catch(() => {
                alert('Something went wrong while updating visit status.');
            });
        });
    }

    if (missedBtn) {
        missedBtn.addEventListener('click', function () {
            const visitId = document.getElementById('modalVisitId').value;

            fetch('/calendar/update-visit-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    visit_id: visitId,
                    status: 'missed'
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Unable to update visit.');
                }
            })
            .catch(() => {
                alert('Something went wrong while updating visit status.');
            });
        });
    }
});
</script>
@endsection
