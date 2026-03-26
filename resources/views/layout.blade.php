<!DOCTYPE html>
<html>
<head>
    <title>KASS Care</title>

    <style>
        body{
            font-family: Arial, sans-serif;
            margin:40px;
        }

        h1{
            margin-bottom:10px;
        }

        .nav{
            margin-bottom:30px;
        }

        .nav a{
            margin-right:15px;
            text-decoration:none;
            color:#0077cc;
            font-weight:bold;
        }

        .nav a:hover{
            text-decoration:underline;
        }

        table{
            border-collapse:collapse;
            width:100%;
        }

        table, th, td{
            border:1px solid #ccc;
        }

        th, td{
            padding:8px;
            text-align:left;
        }

        button{
            padding:5px 10px;
            background:#2d89ef;
            border:none;
            color:white;
            border-radius:4px;
        }

        button:hover{
            background:#1b5fad;
        }
    </style>

</head>

<body>

<h1>KASS Care</h1>

<div class="nav">

<a href="/dashboard">Dashboard</a>

<a href="/clients">Clients</a>

<a href="/facilities">Facilities</a>

<a href="/facility-schedule">Visit Schedule</a>

<a href="/facility-alerts">Alerts</a>

</div>

<hr>

@yield('content')

</body>
</html>
