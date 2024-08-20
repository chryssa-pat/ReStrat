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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
         integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
         crossorigin=""/>
   
</head>
<style>
.car-icon {
    background-color: #ffffff;
    border: 2px solid #000000;
    border-radius: 50%;
    text-align: center;
 }
   
.car-icon i {
    font-size: 20px;
    margin-top: 5px;
    color: #000000;
}

.custom-div-icon i.pending {
 color: #DB4437; /* Κόκκινο */
}

.custom-div-icon i.approved {
 color: #F4B400; /* Κίτρινο */
}

.inquiry-icon {
    background-color: #ffffff;
    border: 2px solid #000000;
    border-radius: 50%;
    text-align: center;
    width: 30px;
    height: 30px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.inquiry-icon.pending {
    border-color: #DB4437; /* Red */
}

.inquiry-icon.approved {
    border-color: #F4B400; /* Yellow */
}

.inquiry-icon i {
    font-size: 16px;
}

.inquiry-icon i.pending {
    color: #DB4437; /* Red */
}

.inquiry-icon i.approved {
    color: #F4B400; /* Yellow */
}

.offer-icon {
    background-color: #ffffff;
    border: 2px solid #000000;
    border-radius: 50%;
    text-align: center;
    width: 30px;
    height: 30px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.offer-icon.pending {
    border-color: #4285F4; /* Blue */
}

.offer-icon.approved {
    border-color: #0F9D58; /* Green */
}

.offer-icon i {
    font-size: 16px;
}

.offer-icon i.pending {
    color: #4285F4; /* Blue */
}

.offer-icon i.approved {
    color: #0F9D58; /* Green */
}

.custom-div-icon .marker-pin {
    width: 30px;
    height: 30px;
    border-radius: 50% 50% 50% 0;
    background: #c30b82;
    position: absolute;
    transform: rotate(-45deg);
    left: 50%;
    top: 50%;
    margin: -15px 0 0 -15px;
}

.custom-div-icon i {
    position: absolute;
    width: 22px;
    font-size: 22px;
    left: 0;
    right: 0;
    margin: 10px auto;
    text-align: center;
}

.custom-div-icon i.fa-gift {
    font-size: 16px;
    margin: 12px auto;
}
</style>

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
                    <li>
                        <a href="statistics_main.php" class="nav-link link-body-emphasis">
                            <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#speedometer2"></use></svg>
                            Statistics
                        </a>
                    </li>
                    <li>
                        <a href="update_products_main.php" class="nav-link link-body-emphasis">
                            <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#speedometer2"></use></svg>
                            Update Products from JSON
                        </a>
                    </li>
                    <li>
                        <a href="add_product_main.php" class="nav-link link-body-emphasis">
                            <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#speedometer2"></use></svg>
                            Manage Products
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
                            <li class="nav-item">
                                <a href="statistics_main.php" class="nav-link link-body-emphasis">
                                    <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#speedometer2"></use></svg>
                                    Statistics
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="update_products_main.php" class="nav-link link-body-emphasis">
                                    <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#speedometer2"></use></svg>
                                    Update Products from JSON
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="add_product_main.php" class="nav-link link-body-emphasis">
                                    <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#speedometer2"></use></svg>
                                    Manage Products 
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
        let vehicleMarkers = [];
        let inquiryMarkers = [];
        let offerMarkers = [];

        var carIcon = L.divIcon({
        className: 'car-icon',
        html: '<i class="fas fa-car"></i>',
        iconSize: [30, 30],
        iconAnchor: [15, 15]
        });

        var inquiryApprovedIcon = L.divIcon({
            className: 'custom-div-icon',
            html: "<div style='background-color:#4285F4;' class='marker-pin'></div><i class='fa fa-question' style='color:#4285F4;'></i>",
            iconSize: [30, 42],
            iconAnchor: [15, 42]
        });

        var inquiryPendingIcon = L.divIcon({
            className: 'custom-div-icon',
            html: "<div style='background-color:#DB4437;' class='marker-pin'></div><i class='fa fa-question' style='color:#DB4437;'></i>",
            iconSize: [30, 42],
            iconAnchor: [15, 42]
        });

        var offerApprovedIcon = L.divIcon({
            className: 'custom-div-icon',
            html: "<div style='background-color:#0F9D58;' class='marker-pin'></div><i class='fa fa-gift' style='color:#ffffff;'></i>",
            iconSize: [30, 42],
            iconAnchor: [15, 42]
        });

        var offerPendingIcon = L.divIcon({
            className: 'custom-div-icon',
            html: "<div style='background-color:#F4B400;' class='marker-pin'></div><i class='fa fa-gift' style='color:#ffffff;'></i>",
            iconSize: [30, 42],
            iconAnchor: [15, 42]
        });

        function initMap() {
            console.log("Initializing map...");
            map = L.map('map').setView([38.246639, 21.734573], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            console.log("Map initialized:", map); 

            getBaseLocation();
            getVehicleLocations();
            getInquiries();
            getOffers();

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
                            baseMarker.bindPopup("<strong>Base Location</strong><br>Lat: " + lat.toFixed(6) + "<br>Lng: " + lng.toFixed(6)).openPopup();
                            
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

        function getVehicleLocations() {
            console.log("Fetching vehicle locations...");
            fetch('get_vehicle_locations.php')
                .then(response => response.json())
                .then(data => {
                    console.log("Vehicle data received:", data);
                    if (data.success) {
                        data.vehicles.forEach(vehicle => {
                            var lat = parseFloat(vehicle.latitude_vehicle);
                            var lng = parseFloat(vehicle.longitude_vehicle);
                            var marker = L.marker([lat, lng], {icon: carIcon}).addTo(map);
                            marker.bindPopup("<strong>Volunteer Location</strong><br>Vehicle ID: " + vehicle.vehicle_id);
                            vehicleMarkers.push(marker);
                        });
                        fitMapToAllMarkers();
                    } else {
                        console.error('Failed to get vehicle locations:', data.error);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function getInquiries() {
            console.log("Fetching inquiries...");
            fetch('get_inquiries.php')
                .then(response => response.json())
                .then(data => {
                    console.log("Inquiries data received:", data);
                    if (data.success) {
                        data.inquiries.forEach(inquiry => {
                            var lat = parseFloat(inquiry.latitude);
                            var lng = parseFloat(inquiry.longitude);
                            var status = inquiry.status.toLowerCase();
                            var marker = L.marker([lat, lng], {
                                icon: L.divIcon({
                                    className: `inquiry-icon ${status}`,
                                    html: `<i class="fas fa-question ${status}"></i>`,
                                    iconSize: [30, 30],
                                    iconAnchor: [15, 15]
                                })
                            }).addTo(map);
                            marker.bindPopup(`<strong>Inquiry</strong><br>
                                Name: ${inquiry.full_name}<br>
                                Phone: ${inquiry.phone}<br>
                                Product: ${inquiry.product}<br>
                                Quantity: ${inquiry.quantity}<br>
                                Status: ${inquiry.status}<br>
                                Date: ${inquiry.registration_date}`);
                            inquiryMarkers.push(marker);
                        });
                        fitMapToAllMarkers();
                    } else {
                        console.error('Failed to get inquiries:', data.error);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function getOffers() {
            console.log("Fetching offers...");
            fetch('get_offers.php')
                .then(response => {
                    console.log("Raw response:", response);
                    return response.json();
                })
                .then(data => {
                    console.log("Offers data received:", data);
                    if (data.success) {
                        console.log("Number of offers:", data.offers.length);
                        data.offers.forEach(offer => {
                            console.log("Processing offer:", offer);
                            var lat = parseFloat(offer.latitude);
                            var lng = parseFloat(offer.longitude);
                            var status = offer.status.toLowerCase();
                            var icon = status === 'approved' ? offerApprovedIcon : offerPendingIcon;
                            
                            var marker = L.marker([lat, lng], {icon: icon}).addTo(map);
                            marker.bindPopup(`<strong>Offer</strong><br>
                                Name: ${offer.full_name}<br>
                                Phone: ${offer.phone}<br>
                                Product: ${offer.product}<br>
                                Quantity: ${offer.quantity}<br>
                                Status: ${status}<br>
                                Date: ${offer.registration_date}`);
                            offerMarkers.push(marker);
                        });
                        fitMapToAllMarkers();
                    } else {
                        console.error('Failed to get offers:', data.error);
                    }
                })
                .catch(error => console.error('Error:', error));
        }
        
        function fitMapToAllMarkers() {
            var allMarkers = [baseMarker, ...vehicleMarkers, ...inquiryMarkers, ...offerMarkers].filter(Boolean);
            if (allMarkers.length > 0) {
                var group = new L.featureGroup(allMarkers);
                map.fitBounds(group.getBounds().pad(0.1));
            }
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
            
        window.onload = function() {
            console.log("Window loaded, initializing map...");
            initMap();
            
        };
    </script>
</body>

</html>