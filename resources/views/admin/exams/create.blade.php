<!DOCTYPE html>
<html>
<head>
    <title>Create Exam</title>
</head>
<body>
    <h1>Create New Exam</h1>
    @if(session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif
    <form method="POST" action="{{ route('exams.store') }}">
        @csrf
        <label>Title:</label><input type="text" name="title" required><br>
        <label>Description:</label><textarea name="description" required></textarea><br>
        <button type="submit">Create Exam and Generate Link</button>
    </form>
</body>
</html>