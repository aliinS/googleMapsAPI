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
    <div>
        <p>Kliki kaardil, et lisada uus nööp või lisa manuaalselt allpool.</p>
    </div>
    <div id="map"></div>
    <div id="">
        <h2>Lisa uus marker:</h2>
        <div id="createMarker"></div>
    </div>
    <div id="markerList">
        <h2>Marker List</h2>
        <ul id="markersUl"></ul>
    </div>

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

                        // Add the marker to the list
                        const markerList = document.getElementById('markersUl');
                        const listItem = document.createElement('li');
                        listItem.textContent = marker.name;
                        markerList.appendChild(listItem);
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
                    saveMarkerToBackend(markerName, markerDescription, event.latLng)
                        .then(savedMarker => {
                            console.log('Uus nööbike salvestatud:', savedMarker);
                            // Add the new marker to the list
                            const markerList = document.getElementById('markersUl');
                            const listItem = document.createElement('li');
                            listItem.textContent = savedMarker.name;
                            markerList.appendChild(listItem);
                        })
                        .catch(error => {
                            console.error('Miskit juhtus, nööbikest ei salvestatud:', error);
                        });
                }
            });
        }

        // Function to save marker information to the backend
        async function saveMarkerToBackend(name, description, position) {
            const response = await fetch('/markers', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    name: name,
                    description: description,
                    latitude: position.lat(),
                    longitude: position.lng(),
                }),
            });

            if (!response.ok) {
                throw new Error(`Failed to save marker: ${response.statusText}`);
            }

            return response.json();
        }
    </script>





    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ config('app.google_api_key') }}&callback=initMap&libraries=places"
        defer>
    </script>
</body>

</html>
