
<!DOCTYPE html>
<html>

<head>

<title>KASS-Care Dashboard</title>

<style>

body{
margin:0;
font-family:Arial;
background:#f4f6f9;
}

/* TOP BAR */

.topbar{
height:70px;
background:#0f172a;
color:white;
display:flex;
align-items:center;
padding-left:30px;
font-size:22px;
font-weight:bold;
}

/* LAYOUT */

.layout{
display:flex;
}

/* SIDEBAR */

.sidebar{
width:220px;
background:#111827;
min-height:100vh;
color:white;
}

.sidebar h2{
padding:20px;
margin:0;
font-size:18px;
border-bottom:1px solid #1f2937;
}

.sidebar a{
display:block;
padding:14px 20px;
text-decoration:none;
color:#d1d5db;
}

.sidebar a:hover{
background:#1f2937;
}

/* CONTENT */

.content{
flex:1;
padding:30px;
}

/* CARDS */

.cards{
display:flex;
gap:20px;
flex-wrap:wrap;
}

.card{
background:white;
padding:25px;
border-radius:10px;
width:220px;
box-shadow:0 4px 10px rgba(0,0,0,0.1);
}

.number{
font-size:32px;
font-weight:bold;
}

.title{
color:#666;
}

/* SECTION */

.section{
margin-top:40px;
}

table{
width:100%;
background:white;
border-collapse:collapse;
box-shadow:0 4px 10px rgba(0,0,0,0.05);
}

th,td{
padding:12px;
border-bottom:1px solid #eee;
text-align:left;
}

</style>

</head>

<body>

<div class="topbar">
KASS-Care Admin Dashboard
</div>

<div class="layout">

<div class="sidebar">

<h2>Navigation</h2>

<a href="/dashboard">Dashboard</a>
<a href="/clients">Clients</a>
<a href="/caregivers">Caregivers</a>
<a href="/visits">Visits</a>
<a href="/carelogs">Care Logs</a>

</div>

<div class="content">

<div class="cards">

<div class="card">
<div class="number">{{ $clients }}</div>
<div class="title">Clients</div>
</div>

<div class="card">
<div class="number">{{ $caregivers }}</div>
<div class="title">Caregivers</div>
</div>

<div class="card">
<div class="number">{{ $visitsToday }}</div>
<div class="title">Visits Today</div>
</div>

<div class="card">
<div class="number">{{ $careLogsToday }}</div>
<div class="title">Care Logs Today</div>
</div>

</div>

<div class="section">

<h2>Recent Care Logs</h2>

<table>

<tr>
<th>ID</th>
<th>Client</th>
<th>Meals %</th>
<th>BM</th>
<th>Shower</th>
<th>Medication</th>
</tr>

@foreach(\App\Models\CareLog::latest()->take(5)->get() as $log)

<tr>

<td>{{ $log->id }}</td>
<td>{{ $log->client_id }}</td>
<td>{{ $log->meals_percent }}</td>
<td>{{ $log->bm }}</td>
<td>{{ $log->shower }}</td>
<td>{{ $log->meds_given }}</td>

</tr>

@endforeach

</table>

</div>

</div>

</div>

</body>
</html>