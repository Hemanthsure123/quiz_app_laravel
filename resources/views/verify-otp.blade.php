<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP</title>
</head>
<body>
    <h1>Enter OTP</h1>
    @if(session('success')) <p style="color:green;">{{ session('success') }}</p> @endif
    @if(session('error')) <p style="color:red;">{{ session('error') }}</p> @endif
    <form method="POST" action="{{ route('verify-otp') }}">
        @csrf
        <label>OTP:</label><input type="number" name="otp" required><br>
        <button type="submit">Verify</button>
    </form>
</body>
</html>