<!DOCTYPE html>
<html>

<head>
    <title>Admin Photo Upload</title>
</head>

<body>

    <h2>Upload Admin Photo</h2>

    <!-- Success Message -->
    @if (session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    <!-- Error Message -->
    @if (session('error'))
        <p style="color:red;">{{ session('error') }}</p>
    @endif

    <!-- Validation Errors -->
    @if ($errors->any())
        <p style="color:red;">{{ $errors->first() }}</p>
    @endif

    <!-- Upload Form -->
    <form action="{{ route('admin.update.photo', $admin->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="profile_photo" required>
        <button type="submit">Upload Photo</button>
    </form>

    <br>

    <!-- Show Image -->
    @if ($admin->profile_photo)
        <img src="{{ asset($admin->profile_photo) }}" width="150">
    @endif

</body>

</html>
