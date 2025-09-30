<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <h1>Enter Your Details</h1>
    <form method="POST" action="{{ route('register') }}">
        @csrf  <!-- Security token -->
        <label>Full Name:</label><input type="text" name="full_name" required><br>
        <label>Email:</label><input type="email" name="email" required><br>
        <label>City:</label><input type="text" name="city" required><br>
        <label>Contact Number:</label><input type="text" name="contact_number" required><br>
        <button type="submit">Submit</button>
    </form>
</body>
</html>