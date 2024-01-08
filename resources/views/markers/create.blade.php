<!DOCTYPE html>
<html>
<head>
    <title>Create Marker</title>
</head>
<body>
    <h1>Create Marker</h1>

    <form method="POST" action="{{ route('markers.store') }}">
        @csrf
        <label for="name">Name:</label>
        <input type="text" name="name" required><br>

        <label for="latitude">Latitude:</label>
        <input type="text" name="latitude" required><br>

        <label for="longitude">Longitude:</label>
        <input type="text" name="longitude" required><br>

        <label for="description">Description:</label>
        <textarea name="description"></textarea><br>

        <button type="submit">Create</button>
    </form>

    <a href="{{ route('markers.index') }}">Back to Index</a>
</body>
</html>
