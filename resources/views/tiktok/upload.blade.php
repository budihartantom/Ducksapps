<!DOCTYPE html>
<html>
<head>
    <title>Upload Video to TikTok</title>
</head>
<body>
    <h1>Upload Video to TikTok</h1>

    @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div>{{ session('error') }}</div>
    @endif

    <form action="{{ route('upload.video') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="video">Select Video:</label>
            <input type="file" name="video" id="video" required>
        </div>
        <button type="submit">Upload</button>
    </form>
</body>
</html>
