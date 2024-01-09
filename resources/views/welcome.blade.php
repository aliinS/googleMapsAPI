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

    <h1>Google Maps API harjutus</h1>
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
                // Ask user if they want to save a new marker
                const wantToSaveMarker = confirm('Soovid salvestada uut nööpi?');

                if (wantToSaveMarker) {
                    // Prompt user for marker information (name, description)
                    const markerName = prompt('Sisesta nööbi nimi:');
                    const markerDescription = prompt('Sisesta nööbi kirjeldus:');

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

                    // Save the new marker information to your backend
                    saveMarkerToBackend(markerName, markerDescription, event.latLng);
                }
            });
        }

        // Function to save marker information to the backend
        function saveMarkerToBackend(name, description, position) {
            const latitude = position.lat();
            const longitude = position.lng();

            console.log('Marker data:', { name, description, latitude, longitude });
            

            fetch('/markers', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({
                        name: name,
                        description: description,
                        latitude: latitude,
                        longitude: longitude,
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Uus nööbike salvestatud:', data);
                })
                .catch(error => {
                    console.error('Miskit juhtus, nööbikest ei salvestatud:', error);
                });

            console.log('Saving marker to backend:', name, description, {
                latitude,
                longitude
            });
        }
    </script>





    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ config('app.google_api_key') }}&callback=initMap&libraries=places"
        defer></script>
</body>

</html>
