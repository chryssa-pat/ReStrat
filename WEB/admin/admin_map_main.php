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
#map {
  height: 90vh; 
  width: 100%;
  max-height: 800px; /* Maximum height */
}
.map_container {
  padding: 20px;
}

.custom-div-icon .marker-pin {
  width: 30px;
  height: 30px;
  border-radius: 50% 50% 50% 0;
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

.custom-div-icon .marker-pin::after {
  content: '';
  width: 24px;
  height: 24px;
  margin: 3px 0 0 3px;
  background: #fff;
  position: absolute;
  border-radius: 50%;
}

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

.marker-pin.pending {
 background-color: #DB4437; /* Κόκκινο */
}

.marker-pin.approved {
 background-color: #F4B400; /* Κίτρινο */
}

.marker-pin.completed {
 background-color: #0F9D58; /* Πράσινο */
}

.custom-div-icon i.pending {
 color: #DB4437; /* Κόκκινο */
}

.custom-div-icon i.approved {
 color: #F4B400; /* Κίτρινο */
}

.custom-div-icon i.completed {
 color: #0F9D58; /* Πράσινο */
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
                 
                 <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="pendingFilter">
                    <label class="form-check-label" for="pendingFilter">Show Pending Offers/Inquiries</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="approvedFilter">
                    <label class="form-check-label" for="approvedFilter">Show Approved Offers/Inquiries</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="taskFilter">
                    <label class="form-check-label" for="taskFilter">Show Vehicles with Tasks Only</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="noTaskFilter">
                    <label class="form-check-label" for="noTaskFilter">Show Vehicles without Tasks Only</label>
                </div>

                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="showLinesFilter">
                    <label class="form-check-label" for="showLinesFilter">Show Task Lines</label>
                </div>

                 <hr>
                 
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
                    </ul>

                    <div class="dropdown">
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

// Ορίζουμε τα εικονίδια για τις διαφορετικές πινέζες
        // Ορίζουμε τα εικονίδια για τις διαφορετικές πινέζες
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
            html: "<div style='background-color:#0F9D58;' class='marker-pin'></div><i class='fa fa-gift' style='color:#0F9D58;'></i>",
            iconSize: [30, 42],
            iconAnchor: [15, 42]
        });

        var offerPendingIcon = L.divIcon({
            className: 'custom-div-icon',
            html: "<div style='background-color:#F4B400;' class='marker-pin'></div><i class='fa fa-gift' style='color:#F4B400;'></i>",
            iconSize: [30, 42],
            iconAnchor: [15, 42]
        });
function initMap() {
    map = L.map('map').setView([38.246639, 21.734573], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    getBaseLocation();
    getVehicleLocations();
    getInquiries();
    getOffers(); // Load all markers by default
}

function getBaseLocation() {
    fetch('get_base_location.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                var lat = parseFloat(data.latitude);
                var lng = parseFloat(data.longitude);

                if (baseMarker) {
                    baseMarker.setLatLng([lat, lng]);
                } else {
                    baseMarker = L.marker([lat, lng], { draggable: true }).addTo(map);
                    baseMarker.bindPopup("<strong>Base Location</strong><br>Lat: " + lat.toFixed(6) + "<br>Lng: " + lng.toFixed(6)).openPopup();

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

let vehicleMarkersMap = {}; // To map vehicle IDs to their markers
let vehicleTasksMap = {};   // To keep track of all tasks for each vehicle
let inquiriesLoaded = false;
let offersLoaded = false;

function getVehicleLocations() {
    fetch('get_vehicle_locations.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                data.vehicles.forEach(vehicle => {
                    var lat = parseFloat(vehicle.latitude_vehicle);
                    var lng = parseFloat(vehicle.longtitude_vehicle);
                    var marker = L.marker([lat, lng], { icon: carIcon }).addTo(map);

                    // Pop-up content to display vehicle ID, load details, and task count
                    var popupContent = `<strong>Volunteer Location</strong><br>
                                        Vehicle ID: ${vehicle.vehicle_id}<br>
                                        Load: ${vehicle.load_details || 'No load'}<br>
                                        Tasks: ${vehicle.task_count || 0}`;

                    marker.bindPopup(popupContent);
                    vehicleMarkers.push(marker);

                    // Map the vehicle ID to its marker
                    vehicleMarkersMap[vehicle.vehicle_id] = marker;

                    // Initialize an array to store tasks (offers/inquiries) for the vehicle
                    vehicleTasksMap[vehicle.vehicle_id] = [];
                });
                fitMapToAllMarkers();
            } else {
                console.error("Error: No vehicles found.");
            }
        })
        .catch(error => console.error('Error:', error));
}

function getInquiries() {
    fetch('get_inquiries.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Group and map inquiries
                const groupedInquiries = {};

                data.inquiries.forEach(inquiry => {
                    const key = `${inquiry.latitude},${inquiry.longitude}`;
                    if (!groupedInquiries[key]) {
                        groupedInquiries[key] = [];
                    }
                    groupedInquiries[key].push(inquiry);
                });

                for (const key in groupedInquiries) {
                    const [lat, lng] = key.split(',').map(parseFloat);
                    const inquiries = groupedInquiries[key];
                    const status = inquiries[0].status.toLowerCase();

                    const icon = status === 'approved' ? inquiryApprovedIcon : inquiryPendingIcon;

                    const marker = L.marker([lat, lng], { icon: icon }).addTo(map);

                    let popupContent = `<strong>Inquiries</strong><br>`;
                    inquiries.forEach(inquiry => {
                        popupContent += `
                            Name: ${inquiry.full_name}<br>
                            Phone: ${inquiry.phone}<br>
                            Product: ${inquiry.product}<br>
                            Quantity: ${inquiry.quantity}<br>
                            Status: ${inquiry.status}<br>
                            Date: ${inquiry.registration_date}<br>`;
                        if (inquiry.status.toLowerCase() === 'approved') {
                            popupContent += `Assigned Vehicle: ${inquiry.approved_vehicle_id}<br>`;

                            // Store this inquiry as a task for the assigned vehicle
                            if (vehicleTasksMap[inquiry.approved_vehicle_id]) {
                                vehicleTasksMap[inquiry.approved_vehicle_id].push({
                                    latLng: marker.getLatLng(),
                                    color: '#DB4437' // Red for inquiries
                                });
                            }
                        }
                        popupContent += `<hr>`;
                    });

                    marker.bindPopup(popupContent);
                    inquiryMarkers.push(marker);
                }

                // Mark inquiries as loaded and check if both inquiries and offers are loaded
                inquiriesLoaded = true;
                if (offersLoaded) {
                    drawLinesForAllTasks();
                }
            } else {
                console.error('Failed to get inquiries:', data.error);
            }
        })
        .catch(error => console.error('Error:', error));
}

function getOffers() {
    fetch('get_offers.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const groupedOffers = {};

                data.offers.forEach(offer => {
                    const key = `${offer.latitude},${offer.longitude}`;
                    if (!groupedOffers[key]) {
                        groupedOffers[key] = [];
                    }
                    groupedOffers[key].push(offer);
                });

                for (const key in groupedOffers) {
                    const [lat, lng] = key.split(',').map(parseFloat);
                    const offers = groupedOffers[key];
                    const status = offers[0].status.toLowerCase();
                    const icon = status === 'approved' ? offerApprovedIcon : offerPendingIcon;

                    const marker = L.marker([lat, lng], { icon: icon }).addTo(map);

                    let popupContent = `<strong>Offers</strong><br>`;
                    offers.forEach(offer => {
                        popupContent += `
                            Name: ${offer.full_name}<br>
                            Phone: ${offer.phone}<br>
                            Product: ${offer.product}<br>
                            Quantity: ${offer.quantity}<br>
                            Status: ${offer.status}<br>
                            Date: ${offer.registration_date}<br>`;
                        if (offer.status.toLowerCase() === 'approved') {
                            popupContent += `Assigned Vehicle: ${offer.approved_vehicle_id}<br>`;

                            // Store this offer as a task for the assigned vehicle
                            if (vehicleTasksMap[offer.approved_vehicle_id]) {
                                vehicleTasksMap[offer.approved_vehicle_id].push({
                                    latLng: marker.getLatLng(),
                                    color: '#0F9D58' // Green for offers
                                });
                            }
                        }
                        popupContent += `<hr>`;
                    });

                    marker.bindPopup(popupContent);
                    offerMarkers.push(marker);
                }

                // Mark offers as loaded and check if both inquiries and offers are loaded
                offersLoaded = true;
                if (inquiriesLoaded) {
                    drawLinesForAllTasks();
                }
            } else {
                console.error('Failed to get offers:', data.error);
            }
        })
        .catch(error => console.error('Error:', error));
}

window.onload = function () {
    initMap();
};

function fitMapToAllMarkers() {
    var allMarkers = [baseMarker, ...vehicleMarkers, ...inquiryMarkers, ...offerMarkers].filter(Boolean);
    if (allMarkers.length > 0) {
        var group = new L.featureGroup(allMarkers);
        map.fitBounds(group.getBounds().pad(0.1));
    }
}

function onMapClick(e) {
    if (!baseMarker) {
        baseMarker = L.marker(e.latlng, { draggable: true }).addTo(map);
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
        body: JSON.stringify({ latitude: lat, longitude: lng }),
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

document.getElementById('pendingFilter').addEventListener('change', function () {
    applyFilters();
});

document.getElementById('approvedFilter').addEventListener('change', function () {
    applyFilters();
});

document.getElementById('taskFilter').addEventListener('change', function () {
    // Uncheck the other filter if this one is checked
    if (this.checked) {
        document.getElementById('noTaskFilter').checked = false;
    }
    applyFilters();
});

document.getElementById('noTaskFilter').addEventListener('change', function () {
    // Uncheck the "Show Vehicles with Tasks Only" filter if this one is checked
    if (this.checked) {
        document.getElementById('taskFilter').checked = false;
    }
    applyFilters();
});

let showLines = false; // Initialize the state of the line filter
let taskLines = [];    // Array to store references to the drawn lines

document.getElementById('showLinesFilter').addEventListener('change', function () {
    showLines = this.checked; // Update the state based on the checkbox
    applyFilters(); // Reapply filters when the checkbox is toggled
});

function drawDashedLine(startLatLng, endLatLng, color) {
    const dashedLine = L.polyline([startLatLng, endLatLng], {
        color: '#000000',
        dashArray: '5, 10', // Dashed line style
        weight: 2,          // Thickness of the line
    }).addTo(map);
    taskLines.push(dashedLine); // Store the line reference
}

function clearTaskLines() {
    taskLines.forEach(line => map.removeLayer(line)); // Remove each line from the map
    taskLines = []; // Clear the array
}

function drawLinesForAllTasks() {
    clearTaskLines(); // Ensure any existing lines are cleared

    if (showLines) { // Only draw lines if the filter is enabled
        for (const vehicleId in vehicleTasksMap) {
            const tasks = vehicleTasksMap[vehicleId];
            if (tasks.length > 0 && vehicleMarkersMap[vehicleId]) {
                const vehicleLatLng = vehicleMarkersMap[vehicleId].getLatLng();
                tasks.forEach(task => {
                    drawDashedLine(vehicleLatLng, task.latLng, task.color);
                });
            }
        }
    }
}

function applyFilters() {
    clearInquiryAndOfferMarkers(); // Clear markers for inquiries and offers
    clearTaskLines(); // Clear any existing task lines

    // Re-add the base marker after clearing markers
    if (baseMarker) {
        baseMarker.addTo(map);
    } else {
        getBaseLocation(); // If base marker doesn't exist, fetch and add it
    }

    let showPending = document.getElementById('pendingFilter').checked;
    let showApproved = document.getElementById('approvedFilter').checked;
    let showVehiclesWithTasks = document.getElementById('taskFilter').checked;
    let showVehiclesWithoutTasks = document.getElementById('noTaskFilter').checked;

    if (showVehiclesWithTasks) {
        // Hide inquiries and offers when showing only vehicles with tasks
        showOnlyVehiclesWithTasks();
    } else if (showVehiclesWithoutTasks) {
        // Hide inquiries and offers when showing only vehicles without tasks
        clearInquiryAndOfferMarkers(); // Ensure inquiry and offer markers are cleared
        showOnlyVehiclesWithoutTasks();
    } else {
        // Handle the inquiries and offers filtering
        if (showPending || showApproved) {
            if (showPending) {
                getPendingInquiries();
                getPendingOffers();
            }
            if (showApproved) {
                getApprovedInquiries();
                getApprovedOffers();
            }
            // Hide vehicles if "Show Pending Offers/Inquiries" is checked
            vehicleMarkers.forEach(marker => map.removeLayer(marker));
        } else {
            // If no inquiry/offer filters are checked, show all inquiries and offers
            getInquiries();
            getOffers();

            // Re-show the vehicles when no filters or other vehicle-related filters are active
            vehicleMarkers.forEach(marker => marker.addTo(map)); // Re-add all vehicle markers
        }
    }

    // Draw dashed lines only if the filter is enabled
    if (showLines) {
        drawLinesForAllTasks();
    }

    // Optionally, refit the map bounds to include all markers
    fitMapToAllMarkers();
}

function showOnlyVehiclesWithTasks() {
    vehicleMarkers.forEach(marker => {
        const vehicleId = Object.keys(vehicleMarkersMap).find(id => vehicleMarkersMap[id] === marker);
        if (vehicleTasksMap[vehicleId] && vehicleTasksMap[vehicleId].length > 0) {
            marker.addTo(map);
            drawTasksOnVehicleMarker(marker, vehicleId); // Draw tasks on the vehicle marker
        } else {
            map.removeLayer(marker);
        }
    });
}

function showOnlyVehiclesWithTasks() {
    vehicleMarkers.forEach(marker => {
        const vehicleId = Object.keys(vehicleMarkersMap).find(id => vehicleMarkersMap[id] === marker);
        if (vehicleTasksMap[vehicleId] && vehicleTasksMap[vehicleId].length > 0) {
            marker.addTo(map);
        } else {
            map.removeLayer(marker);
        }
    });
}

function showOnlyVehiclesWithoutTasks() {
    vehicleMarkers.forEach(marker => {
        const vehicleId = Object.keys(vehicleMarkersMap).find(id => vehicleMarkersMap[id] === marker);
        if (!vehicleTasksMap[vehicleId] || vehicleTasksMap[vehicleId].length === 0) {
            marker.addTo(map);
        } else {
            map.removeLayer(marker);
        }
    });
}



function clearInquiryAndOfferMarkers() {
    // Clear inquiry and offer markers only
    inquiryMarkers.forEach(marker => map.removeLayer(marker));
    offerMarkers.forEach(marker => map.removeLayer(marker));

    inquiryMarkers = [];
    offerMarkers = [];
}





function clearAllMarkers() {
    // Clear existing markers from the map, but don't remove the base marker
    vehicleMarkers.forEach(marker => map.removeLayer(marker));
    inquiryMarkers.forEach(marker => map.removeLayer(marker));
    offerMarkers.forEach(marker => map.removeLayer(marker));

    vehicleMarkers = [];
    inquiryMarkers = [];
    offerMarkers = [];
}


function getPendingInquiries() {
    fetch('get_inquiries.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                data.inquiries.forEach(inquiry => {
                    if (inquiry.status.toLowerCase() === 'pending') {
                        var lat = parseFloat(inquiry.latitude);
                        var lng = parseFloat(inquiry.longitude);
                        var marker = L.marker([lat, lng], {
                            icon: inquiryPendingIcon
                        }).addTo(map);
                        marker.bindPopup(`<strong>Inquiry</strong><br>
                            Name: ${inquiry.full_name}<br>
                            Phone: ${inquiry.phone}<br>
                            Product: ${inquiry.product}<br>
                            Quantity: ${inquiry.quantity}<br>
                            Status: ${inquiry.status}<br>
                            Date: ${inquiry.registration_date}`);
                        inquiryMarkers.push(marker);
                    }
                });
                fitMapToAllMarkers();
            } else {
                console.error('Failed to get inquiries:', data.error);
            }
        })
        .catch(error => console.error('Error:', error));
}

function getPendingOffers() {
    fetch('get_offers.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                data.offers.forEach(offer => {
                    if (offer.status.toLowerCase() === 'pending') {
                        var lat = parseFloat(offer.latitude);
                        var lng = parseFloat(offer.longitude);
                        var marker = L.marker([lat, lng], { icon: offerPendingIcon }).addTo(map);
                        marker.bindPopup(`<strong>Offer</strong><br>
                            Name: ${offer.full_name}<br>
                            Phone: ${offer.phone}<br>
                            Product: ${offer.product}<br>
                            Quantity: ${offer.quantity}<br>
                            Status: ${offer.status}<br>
                            Date: ${offer.registration_date}`);
                        offerMarkers.push(marker);
                    }
                });
                fitMapToAllMarkers();
            } else {
                console.error('Failed to get offers:', data.error);
            }
        })
        .catch(error => console.error('Error:', error));
}

function getApprovedInquiries() {
    fetch('get_inquiries.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                data.inquiries.forEach(inquiry => {
                    if (inquiry.status.toLowerCase() === 'approved') {
                        var lat = parseFloat(inquiry.latitude);
                        var lng = parseFloat(inquiry.longitude);

                        // Use the same logic for choosing the icon as in the getPendingInquiries function
                        var status = inquiry.status.toLowerCase();
                        var marker = L.marker([lat, lng],
                            { icon: inquiryApprovedIcon }).addTo(map);

                        marker.bindPopup(`<strong>Inquiry</strong><br>
                            Name: ${inquiry.full_name}<br>
                            Phone: ${inquiry.phone}<br>
                            Product: ${inquiry.product}<br>
                            Quantity: ${inquiry.quantity}<br>
                            Status: ${inquiry.status}<br>
                            Date: ${inquiry.registration_date}`);
                        inquiryMarkers.push(marker);
                    }
                });
                fitMapToAllMarkers();
            } else {
                console.error('Failed to get inquiries:', data.error);
            }
        })
        .catch(error => console.error('Error:', error));
}

function getApprovedOffers() {
    fetch('get_offers.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                data.offers.forEach(offer => {
                    if (offer.status.toLowerCase() === 'approved') {
                        var lat = parseFloat(offer.latitude);
                        var lng = parseFloat(offer.longitude);

                        // Ensure the icon for approved offers is used
                        var marker = L.marker([lat, lng], {
                            icon: offerApprovedIcon
                        }).addTo(map);

                        marker.bindPopup(`<strong>Offer</strong><br>
                            Name: ${offer.full_name}<br>
                            Phone: ${offer.phone}<br>
                            Product: ${offer.product}<br>
                            Quantity: ${offer.quantity}<br>
                            Status: ${offer.status}<br>
                            Date: ${offer.registration_date}`);
                        offerMarkers.push(marker);
                    }
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

window.onload = function () {
    initMap();
};


    </script>
</body>

</html>