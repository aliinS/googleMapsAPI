<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marker Management</title>
    <style>
        #map {
            width: 100%;
            height: 300px;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col md:flex-row h-dvh">
                        <div id="map" class="flex-1 sm:rounded-md h-[400px] md:h-auto md:w-2/3"></div>
                        <div id="marker-list" class="pl-4 overflow-y-auto md:w-1/3 lg:w-1/4 max-h-[860px]">
                            <div class="space-y-4 mb-4">
                                @php $limitedMarkers = array_slice($markers->toArray(), -10); @endphp
                                @foreach ($limitedMarkers as $marker)
                                    <div class="bg-white rounded-lg p-4 shadow-md">
                                        <div>
                                            <div class="font-semibold text-gray-600">{{ $marker['name'] }}</div>
                                            <small
                                                class="ml-2 text-sm text-gray-600">{{ $marker['description'] }}</small>
                                        </div>
                                        <div class="mt-2 flex justify-end space-x-2">
                                            <a href="{{ route('markers.edit', $marker['id']) }}"
                                                class="text-blue-600 hover:underline">Edit</a>
                                            <form method="POST" action="{{ route('markers.destroy', $marker['id']) }}">
                                                @csrf
                                                @method('delete')
                                                <button type="submit"
                                                    onclick="return confirm('Are you sure you want to delete this marker?')"
                                                    class="text-red-600 hover:underline">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const markersData = @json($markers);

        function initMap() {
            const mapOptions = {
                zoom: 3,
                center: new google.maps.LatLng(0, 0),
                mapId: "RADAR_MAP_KEY"
            };

            const map = new google.maps.Map(document.getElementById('map'), mapOptions);
            const bounds = new google.maps.LatLngBounds();

            markersData.forEach((data) => {
                const mapMarker = new google.maps.Marker({
                    position: new google.maps.LatLng(data.latitude, data.longitude),
                    map: map,
                    title: data.name
                });

                bounds.extend(mapMarker.getPosition());

                mapMarker.addListener('click', function() {
                    const infoWindow = new google.maps.InfoWindow({
                        content: `<strong>${data.name}</strong><br>${data.description}`
                    });

                    infoWindow.open(map, mapMarker);
                });
            });

            if (markersData.length > 0) {
                map.fitBounds(bounds);
            } else {
                map.setCenter(mapOptions.center);
            }

            google.maps.event.addListener(map, 'click', function(event) {
                const markerName = prompt("Enter map marker name:", "");
                if (markerName) {
                    const markerDescription = prompt("Enter marker description:", "");
                    if (markerDescription !== null) {
                        const clickMarker = new google.maps.Marker({
                            position: event.latLng,
                            map: map,
                            title: markerName
                        });

                        const markerData = {
                            name: markerName,
                            latitude: event.latLng.lat(),
                            longitude: event.latLng.lng(),
                            description: markerDescription,
                            _token: '{{ csrf_token() }}'
                        };

                        fetch('{{ route('markers.store') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    name: markerName,
                                    description: markerDescription,
                                    latitude: event.latLng.lat(),
                                    longitude: event.latLng.lng(),
                                })
                            })
                            .then(response => {
    if (!response.ok) {
        throw new Error('Network response was not ok');
    }
    return response.json();
})
                            .then(data => {
                                console.log('Marker saved:', data);
                                // Assuming you want to reload the page after saving the marker
                                location.reload();
                            })
                            .catch(error => {
                                console.error('Error saving marker:', error);
                            });
                    }
                }
            });
        }
    </script>
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{ config('services.map.key') }}&loading=async&callback=initMap">
    </script>

</body>

</html>
