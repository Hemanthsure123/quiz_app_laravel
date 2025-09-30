<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - All Exams</title>
</head>
<body>
    <h1>Admin Dashboard: All Created Exams</h1>
    
    @if(session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif
    
    <a href="{{ route('exams.create') }}">Create New Exam</a>
    
    @if($exams->isEmpty())
        <p>No exams created yet.</p>
    @else
        <table border="1" style="width:100%; border-collapse:collapse;">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Duration (mins)</th>
                    <th>Created At</th>
                    <th>Unique Link</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($exams as $exam)
                    <tr>
                        <td>{{ $exam->title }}</td>
                        <td>{{ $exam->description }}</td>
                        <td>{{ $exam->duration ?? 'N/A' }}</td>  <!-- Show N/A for old exams if null -->
                        <td>{{ $exam->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            {{ route('exam.register', ['uuid' => $exam->uuid]) }}
                        </td>
                        
                        <td>
                            <button onclick="copyLink('{{ route('exam.register', ['uuid' => $exam->uuid]) }}')">Copy Link</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    
    <script>
        function copyLink(link) {
            navigator.clipboard.writeText(link).then(() => {
                alert('Link copied to clipboard!');
            }).catch(err => {
                alert('Failed to copy: ' + err);
            });
        }
    </script>
</body>
</html>