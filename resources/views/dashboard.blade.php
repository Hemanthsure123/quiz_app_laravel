<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome to the Quiz Exam: {{ $exam->title }}</h1>
    <p>Description: {{ $exam->description }}</p>
    <p>Duration: {{ $exam->duration }} minutes</p>
    <p id="timer"></p>  <!-- To show countdown -->

    <button id="startExam">Start Exam</button>

    <!-- Hidden videos for preview (optional, for testing) -->
    <video id="cameraPreview" autoplay muted style="width:200px; display:none;"></video>
    <video id="screenPreview" autoplay style="width:200px; display:none;"></video>

    <script>
        const durationMinutes = {{ $exam->duration }};  // From Blade
        const durationMs = durationMinutes * 60 * 1000;
        let cameraRecorder, screenRecorder;
        let cameraChunks = [], screenChunks = [];
        let countdownInterval;

        // Disable right-click, copy, paste
        function disableInteractions() {
            document.addEventListener('contextmenu', e => e.preventDefault());
            document.addEventListener('keydown', e => {
                if (e.ctrlKey && (e.key === 'c' || e.key === 'v' || e.key === 'x')) {
                    e.preventDefault();
                }
            });
            // Optional: Prevent leaving page
            window.addEventListener('beforeunload', e => {
                e.preventDefault();
                e.returnValue = '';
            });
        }

        // Start timer countdown
        function startTimer() {
            let timeLeft = durationMs / 1000;  // In seconds
            const timerEl = document.getElementById('timer');
            countdownInterval = setInterval(() => {
                const mins = Math.floor(timeLeft / 60);
                const secs = timeLeft % 60;
                timerEl.textContent = `Time left: ${mins}:${secs < 10 ? '0' : ''}${secs}`;
                timeLeft--;
                if (timeLeft < 0) {
                    clearInterval(countdownInterval);
                    stopRecording();
                }
            }, 1000);
        }

        // Start recording
        async function startRecording() {
            try {
                // Get camera stream
                const cameraStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
                document.getElementById('cameraPreview').srcObject = cameraStream;

                // Get screen stream (share screen)
                const screenStream = await navigator.mediaDevices.getDisplayMedia({ video: true, audio: true });  // Audio may need browser flags
                document.getElementById('screenPreview').srcObject = screenStream;

                // Camera recorder
                cameraRecorder = new MediaRecorder(cameraStream);
                cameraRecorder.ondataavailable = e => cameraChunks.push(e.data);
                cameraRecorder.start(1000);  // Chunk every 1s

                // Screen recorder
                screenRecorder = new MediaRecorder(screenStream);
                screenRecorder.ondataavailable = e => screenChunks.push(e.data);
                screenRecorder.start(1000);

                // Start timer and disable button
                startTimer();
                document.getElementById('startExam').disabled = true;

                // Stop screen sharing when window closes (optional)
                screenStream.getVideoTracks()[0].addEventListener('ended', () => stopRecording());
            } catch (err) {
                alert('Error starting recording: ' + err.message);
            }
        }

        // Stop recording and upload
        function stopRecording() {
            if (cameraRecorder && cameraRecorder.state !== 'inactive') {
                cameraRecorder.stop();
            }
            if (screenRecorder && screenRecorder.state !== 'inactive') {
                screenRecorder.stop();
            }

            // Wait for last data (use promise or timeout)
            setTimeout(uploadVideos, 2000);  // Give time for ondataavailable
        }

        // Upload videos
        async function uploadVideos() {
            const cameraBlob = new Blob(cameraChunks, { type: 'video/webm' });
            const screenBlob = new Blob(screenChunks, { type: 'video/webm' });

            const formData = new FormData();
            formData.append('camera_video', cameraBlob, 'camera.webm');
            formData.append('screen_video', screenBlob, 'screen.webm');

            try {
                const response = await fetch('{{ route('upload.proctor.videos') }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                });
                const data = await response.json();
                if (data.success) {
                    alert('Exam completed! Videos saved.');
                    // Optional: Redirect or show quiz results
                } else {
                    alert('Upload failed.');
                }
            } catch (err) {
                alert('Error uploading: ' + err.message);
            }
        }

        // Event listener for start button
        document.getElementById('startExam').addEventListener('click', () => {
            disableInteractions();
            startRecording();
        });
    </script>
</body>
</html>