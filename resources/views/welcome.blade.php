<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Maps API</title>
    <style>
        #map {
            width: 100%;
            height: 500px;
        }
    </style>
</head>

<body>

    <h1>Google Maps API</h1>
    <div id="map"></div>

    <script>
        function initMap() {
            const map = new google.maps.Map(document.getElementById('map'), {
                center: {
                    lat: 54.525963,
                    lng: 15.255119
                },
                zoom: 3,
            });

            // Fetch existing markers from the Laravel backend
            fetch('/markers')
                .then(response => response.json())
                .then(data => {
                    // Add existing markers to the map
                    data.forEach(marker => {
                        const markerPosition = {
                            lat: parseFloat(marker.latitude),
                            lng: parseFloat(marker.longitude)
                        };

                        const markerInfoWindow = new google.maps.InfoWindow({
                            content: `<strong>${marker.name}</strong><br>${marker.description}`
                        });

                        const markerObject = new google.maps.Marker({
                            position: markerPosition,
                            map: map,
                            title: marker.name,
                        });

                        // Attach a click event to show the marker's info window
                        markerObject.addListener('click', () => {
                            markerInfoWindow.open(map, markerObject);
                        });
                    });
                })
                .catch(error => console.error('Error fetching markers:', error));

            // Event listener for map click
            map.addListener('click', (event) => {
                // Prompt user for marker information (name, description)
                const markerName = prompt('Enter marker name:');
                const markerDescription = prompt('Enter marker description:');

                // Create a new marker
                const newMarker = new google.maps.Marker({
                    position: event.latLng,
                    map: map,
                    title: markerName,
                });

                // Create an info window for the new marker
                const infoWindow = new google.maps.InfoWindow({
                    content: `<strong>${markerName}</strong><br>${markerDescription}`,
                });

                // Attach a click event to show the info window
                newMarker.addListener('click', () => {
                    infoWindow.open(map, newMarker);
                });

                // Optionally, you can save the new marker information to your backend here
                saveMarkerToBackend(markerName, markerDescription, event.latLng);
            });
        }

        // Function to save marker information to the backend (you need to implement this)
        function saveMarkerToBackend(name, description, position) {
            // Implement the logic to save the marker data to your Laravel backend
            // You may use Ajax, fetch, or another method to send the data to your server
            console.log('Saving marker to backend:', name, description, position);
        }
    </script>





    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ config('app.google_api_key') }}&callback=initMap&libraries=places"
        defer></script>
</body>

</html>
