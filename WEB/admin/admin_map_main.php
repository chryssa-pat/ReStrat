<?php include('../main/session_check.php'); ?> 
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin's Map</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin_map.css">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
         integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
         crossorigin=""/>
   
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar (for larger screens) -->
            <div class="col-md-3 col-lg-3 d-none d-md-flex flex-column p-3 bg-body-tertiary" style="width: 280px; min-height:100vh;">
                <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
                    <svg class="bi pe-none me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
                    <span class="fs-4"><img src="../images/world.png" alt="logo" height="50"></span>
                </a>
                <hr>
                <ul class="nav nav-pills flex-column mb-auto">
                    <li>
                        <a href="#" class="nav-link link-body-emphasis">
                            <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#table"></use></svg>
                            Map
                        </a>
                    </li>
                    <li>
                        <a href="announcement.php" class="nav-link link-body-emphasis">
                            <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#speedometer2"></use></svg>
                            Announcements
                        </a>
                    </li>
                    <li>
                        <a href="warehouse_main.php" class="nav-link link-body-emphasis">
                            <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#speedometer2"></use></svg>
                            Warehouse
                        </a>
                    </li>
                    <li>
                        <a href="createuser_admin_main.php" class="nav-link link-body-emphasis">
                            <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#grid"></use></svg>
                            Create Account
                        </a>
                    </li>
                    <hr>
                </ul>
                <div class="dropdown">
                    <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Account
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="settings.html" class="dropdown-item">Settings</a></li>
                        <li><a class="dropdown-item" id="logoutButton" href="#">Logout</a></li>
                    </ul>
                </div>
            </div>

            <!-- Content area (for all screens) -->
            <div class="col-md-9 col-lg-9">
                <!-- Navbar (for smaller screens) -->
                <nav class="navbar navbar-expand-lg navbar-light bg-light d-md-none">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="#"><img src="../images/world.png" alt="logo" height="50"> </a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                    </div>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a href="#" class="nav-link active link-body-emphasis">
                                    <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#table"></use></svg>
                                    Map
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="announcement.php" class="nav-link active link-body-emphasis">
                                    <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#speedometer2"></use></svg>
                                    Announcements
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a href="warehouse_main.php" class="nav-link active link-body-emphasis">
                                    <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#grid"></use></svg>
                                    Warehouse
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="createuser_admin_main.php" class="nav-link active link-body-emphasis">
                                    <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#grid"></use></svg>
                                    Create Account
                                </a>
                            </li>
                            <hr>
                        </ul>

                        <div class="dropdown ">
                            <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Account
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="settings.html">Settings</a></li>
                                <li><a class="dropdown-item" id="logoutButton" href="#">Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </nav>

                <!-- Add map container here -->
                <div class="map_container mt-3">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    
    <!-- Add Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>

    <!-- Add map initialization script -->
    <script>
        let map;
        let baseMarker;
        let isDragging = false;

        function initMap() {
            console.log("Initializing map...");
            map = L.map('map').setView([38.246639, 21.734573], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            getBaseLocation();

            // Add click event to the map
            map.on('click', onMapClick);
        }

        function getBaseLocation() {
            console.log("Fetching base location...");
            fetch('get_base_location.php')
                .then(response => response.json())
                .then(data => {
                    console.log("Data received:", data);
                    if (data.success) {
                        var lat = parseFloat(data.latitude);
                        var lng = parseFloat(data.longitude);
                        
                        console.log("Base location:", lat, lng);
                        
                        if (baseMarker) {
                            baseMarker.setLatLng([lat, lng]);
                        } else {
                            baseMarker = L.marker([lat, lng], {draggable: true}).addTo(map);
                            baseMarker.bindPopup("Base Location<br>Lat: " + lat.toFixed(6) + "<br>Lng: " + lng.toFixed(6)).openPopup();
                            
                            // Add drag events to the marker
                            baseMarker.on('dragstart', onDragStart);
                            baseMarker.on('dragend', onDragEnd);
                        }

                        map.setView([lat, lng], 13);
                    } else {
                        console.error('Failed to get base location:', data.error);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function onMapClick(e) {
            if (!baseMarker) {
                baseMarker = L.marker(e.latlng, {draggable: true}).addTo(map);
                baseMarker.on('dragstart', onDragStart);
                baseMarker.on('dragend', onDragEnd);
            } else {
                baseMarker.setLatLng(e.latlng);
            }
            updateMarkerPopup();
            confirmLocationChange();
        }

        function onDragStart() {
            isDragging = true;
        }

        function onDragEnd(e) {
            isDragging = false;
            updateMarkerPopup();
            confirmLocationChange();
        }

        function updateMarkerPopup() {
            let lat = baseMarker.getLatLng().lat.toFixed(6);
            let lng = baseMarker.getLatLng().lng.toFixed(6);
            baseMarker.bindPopup("Base Location<br>Lat: " + lat + "<br>Lng: " + lng).openPopup();
        }

        function confirmLocationChange() {
            if (confirm("Do you want to save this new base location?")) {
                saveBaseLocation();
            } else {
                getBaseLocation(); // Reset to the original location
            }
        }

        function saveBaseLocation() {
            let lat = baseMarker.getLatLng().lat;
            let lng = baseMarker.getLatLng().lng;
            
            fetch('save_base_location.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({latitude: lat, longitude: lng}),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Base location updated successfully!");
                } else {
                    alert("Failed to update base location. Please try again.");
                    getBaseLocation(); // Reset to the original location
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                alert("An error occurred. Please try again.");
                getBaseLocation(); // Reset to the original location
            });
        }

        window.onload = initMap;
    </script>
</body>

</html>