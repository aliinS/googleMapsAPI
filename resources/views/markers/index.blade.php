<!DOCTYPE html>
<html>
<head>
    <title>Markers - Index</title>
</head>
<body>
    <h1>Markers - Index</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($markers as $marker)
                <tr>
                    <td>{{ $marker->id }}</td>
                    <td>{{ $marker->name }}</td>
                    <td>{{ $marker->latitude }}</td>
                    <td>{{ $marker->longitude }}</td>
                    <td>{{ $marker->description }}</td>
                    <td>
                        <a href="{{ route('markers.edit', $marker) }}">Edit</a>
                        <form method="POST" action="{{ route('markers.destroy', $marker) }}" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('markers.create') }}">Create Marker</a>
</body>
</html>
