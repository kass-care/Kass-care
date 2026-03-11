<!DOCTYPE html>
<html>
<head>
    <title>Caregiver Login</title>
    <style>
        body{
            font-family:Arial;
            background:#f2f2f2;
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
        }

        .login-box{
            background:white;
            padding:40px;
            border-radius:8px;
            width:300px;
            box-shadow:0 0 10px rgba(0,0,0,0.1);
        }

        input{
            width:100%;
            padding:10px;
            margin-top:10px;
        }

        button{
            width:100%;
            padding:10px;
            margin-top:20px;
            background:#2563eb;
            color:white;
            border:none;
        }
    </style>
</head>

<body>

<div class="login-box">

<h2>KASS Caregiver Login</h2>

@if(session('error'))
<p style="color:red;">{{ session('error') }}</p>
@endif

<form method="POST" action="/caregiver-login">
@csrf

<label>Caregiver ID</label>
<input type="text" name="caregiver_id" required>

<label>PIN</label>
<input type="password" name="pin" required>

<button type="submit">Login</button>

</form>

</div>

</body>
</html>
