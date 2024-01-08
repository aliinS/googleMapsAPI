<!DOCTYPE html>
<html>
<head>
    <title>Edit Marker</title>
</head>
<body>
    <h1>Edit Marker</h1>

    <form method="POST" action="{{ route('markers.update', $marker) }}">
        @csrf
        @method('PUT')
        <label for="name">Name:</label>
        <input type="text" name="name" value="{{ $marker->name }}" required><br>

        <label for="latitude">Latitude:</label>
        <input type="text" name="latitude" value="{{ $marker->latitude }}" required><br>

        <label for="longitude">Longitude:</label>
        <input type="text" name="longitude" value="{{ $marker->longitude }}" required><br>

        <label for="description">Description:</label>
        <textarea name="description">{{ $marker->description }}</textarea><br>

        <button type="submit">Update</button>
    </form>

    <a href="{{ route('markers.index') }}">Back to Index</a>
</body>
</html>
