<?php
session_start();
require_once('../main/session_check.php');
checkSessionAndRedirect();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Load Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="volunteer.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        #map { height: 350px; width: 100%; }
        .car-icon {
            color: #3388ff;
            font-size: 24px;
        }
        .leaflet-popup-content-wrapper {
            background-color: rgba(255, 255, 255, 0.8);
            border: 2px solid #3388ff;
        }
        .leaflet-popup-tip {
            background-color: #3388ff;
        }
        .leaflet-popup-content {
            margin: 5px 10px;
            color: #3388ff;
            font-weight: bold;
        }
        #productTableContainer {
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #dee2e6;
        }
        
        #productTable {
            margin-bottom: 0;
        }
        
        #productTable .table {
            margin-bottom: 0;
        }
        
        #productTable thead th {
            position: sticky;
            top: 0;
            background-color: #f8f9fa;
            z-index: 1;
        }

        .quantity-input {
            width: 60px;
            display: inline-block;
            margin-left: 10px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-3 d-none d-md-flex flex-column p-3 bg-body-tertiary" style="width: 280px; min-height:100vh;">
                <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
                    <span class="fs-4"><img src="../images/world.png" alt="logo" height="50"></span>
                </a>
                <hr>
                <ul class="nav nav-pills flex-column mb-auto">
                    <li>
                        <a href="volunteer.php" class="nav-link link-body-emphasis">
                            Map
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-link active link-body-emphasis">
                            Load Management
                        </a>
                    </li>
                    <li>
                        <a href="tasks.php" class="nav-link link-body-emphasis">
                            Tasks
                        </a>
                    </li>
                    <hr>
                </ul>
               
                <div class="dropdown">
                    <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Account
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><a class="dropdown-item" id="logoutButton" href="#">Logout</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-md-9 col-lg-9">
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
                            <hr>
                              <a href="volunteer.php" class="nav-link active link-body-emphasis">
                                  <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#table"></use></svg>
                                  Map
                              </a>
                          </li>
                          <li class="nav-item">
                            <a href="#" class="nav-link activelink-body-emphasis">
                                <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#speedometer2"></use></svg>
                                Load Managment
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="tasks.php" class="nav-link active link-body-emphasis">
                                <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#grid"></use></svg>
                                Tasks
                            </a>
                        </li>
                        

                
                      </ul>
                                        
                        <hr>
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

                <div class="row mt-5">
                    <div class="col-md-7">
                        <h3 class="mb-4">Load Management</h3>
                        <div id="map"></div>
                    </div>
                    <div class="col-md-5">
                        <div class="d-grid gap-2 mb-3">
                            <button id="loadButton" class="btn btn-primary">Load</button>
                            <button id="unloadButton" class="btn btn-secondary">Unload</button>
                        </div>
                        <div id="productTableContainer" class="mt-3">
                            <div id="productTable"></div>
                        </div>
                        <div class="mt-4">
                            <h4>Current Vehicle Load</h4>
                            <div id="vehicleLoadTable"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
         document.getElementById('logoutButton').addEventListener('click', function (e) {
                      e.preventDefault();
                      var confirmLogout = confirm('Are you sure you want to logout?');
                      if (confirmLogout) {
                          window.location.href = "../main/logout.php";
                      }
                  });
        let map, volunteerMarker, baseMarker, distanceLine, distancePopup;
        const loadButton = document.getElementById('loadButton');
        const unloadButton = document.getElementById('unloadButton');
        const productTable = document.getElementById('productTable');

        var carIcon = L.divIcon({
            className: 'car-icon',
            html: '<i class="fas fa-car"></i>',
            iconSize: [30, 30],
            iconAnchor: [15, 15]
        });

        function initMap() {
            map = L.map('map');
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            getBaseLocation();
            getVolunteerLocation();
        }

        function getVolunteerLocation() {
            if ("geolocation" in navigator) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var lat = position.coords.latitude;
                    var lng = position.coords.longitude;
                    
                    if (volunteerMarker) {
                        volunteerMarker.setLatLng([lat, lng]);
                    } else {
                        volunteerMarker = L.marker([lat, lng], {icon: carIcon}).addTo(map);
                        addPermanentLabel(volunteerMarker, "Volunteer Location<br>Lat: " + lat.toFixed(6) + "<br>Lng: " + lng.toFixed(6));
                    }

                    updateLocation(lat, lng);
                    updateDistanceLine();
                    fitMapToAllMarkers();
                });
            } else {
                console.log("Geolocation is not supported by this browser.");
            }
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
                            baseMarker = L.marker([lat, lng]).addTo(map);
                            addPermanentLabel(baseMarker, "Base Location<br>Lat: " + lat.toFixed(6) + "<br>Lng: " + lng.toFixed(6));
                        }

                        updateDistanceLine();
                        fitMapToAllMarkers();
                    } else {
                        console.error('Failed to get base location:', data.error);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function updateDistanceLine() {
            if (volunteerMarker && baseMarker) {
                if (distanceLine) {
                    map.removeLayer(distanceLine);
                }
                if (distancePopup) {
                    map.removeLayer(distancePopup);
                }

                var volunteerLatLng = volunteerMarker.getLatLng();
                var baseLatLng = baseMarker.getLatLng();

                distanceLine = L.polyline([volunteerLatLng, baseLatLng], {
                    color: '#3388ff',
                    dashArray: '5, 5',
                    weight: 2,
                    opacity: 0.6
                }).addTo(map);

                var distance = volunteerLatLng.distanceTo(baseLatLng);
                var distanceInKm = distance / 1000; // Convert to km
                var midPoint = L.latLng(
                    (volunteerLatLng.lat + baseLatLng.lat) / 2,
                    (volunteerLatLng.lng + baseLatLng.lng) / 2
                );

                distancePopup = L.popup({
                    closeButton: false,
                    autoClose: false,
                    closeOnClick: false,
                    className: 'distance-popup'
                })
                .setLatLng(midPoint)
                .setContent(distanceInKm.toFixed(2) + ' km')
                .openOn(map);

                // Enable/disable buttons based on distance
                if (distanceInKm <= 0.1) {
                    loadButton.disabled = false;
                    unloadButton.disabled = false;
                } else {
                    loadButton.disabled = true;
                    unloadButton.disabled = true;
                }

                console.log('Distance:', distanceInKm, 'km'); // For debugging
            }
        }

        function addPermanentLabel(marker, content) {
            marker.bindTooltip(content, {
                permanent: true,
                direction: 'top',
                offset: L.point(0, -30)
            }).openTooltip();
        }

        function updateLocation(lat, lng) {
            fetch('update_location.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'latitude=' + lat + '&longitude=' + lng
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Location updated successfully');
                } else {
                    console.error('Failed to update location:', data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function fitMapToAllMarkers() {
            var allMarkers = [volunteerMarker, baseMarker].filter(Boolean);
            if (allMarkers.length > 0) {
                var group = new L.featureGroup(allMarkers);
                map.fitBounds(group.getBounds().pad(0.1));
            } else {
                console.log("No markers to fit");
            }
        }

        function updateVehicleLoadTable() {
            fetch('get_vehicle_load.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        let html = '<table class="table table-striped table-hover">';
                        html += '<thead class="table-secondary"><tr><th>Item</th><th>Quantity</th></tr></thead>';
                        html += '<tbody>';
                        data.products.forEach((product) => {
                            html += `<tr>
                                        <td>${product.item}</td>
                                        <td>${product.quantity}</td>
                                    </tr>`;
                        });
                        html += '</tbody></table>';
                        document.getElementById('vehicleLoadTable').innerHTML = html;
                    } else {
                        document.getElementById('vehicleLoadTable').innerHTML = '<p class="text-danger">Failed to load vehicle contents.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('vehicleLoadTable').innerHTML = '<p class="text-danger">An error occurred while loading vehicle contents.</p>';
                });
        }

        function updateProductQuantity(inputElement) {
            const item = inputElement.dataset.item;
            const quantity = parseInt(inputElement.value);

            fetch('update_product_quantity.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `item=${encodeURIComponent(item)}&quantity=${encodeURIComponent(quantity)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    loadButton.click(); // Reload the products
                    updateVehicleLoadTable(); // Update the vehicle load table
                } else {
                    alert(`Σφάλμα: ${data.error}`);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Παρουσιάστηκε σφάλμα κατά την ενημέρωση της ποσότητας του προϊόντος.');
            });
        }

        function updateVehicleUnload(inputElement) {
            const item = inputElement.dataset.item;
            const quantity = parseInt(inputElement.value);

            fetch('update_vehicle_unload.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `item=${encodeURIComponent(item)}&quantity=${encodeURIComponent(quantity)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    unloadButton.click(); // Reload the vehicle contents
                    updateVehicleLoadTable(); // Update the vehicle load table
                } else {
                    alert(`Σφάλμα: ${data.error}`);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Παρουσιάστηκε σφάλμα κατά την εκφόρτωση του προϊόντος.');
            });
        }

        loadButton.addEventListener('click', function() {
            fetch('get_products.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        let html = '<table class="table table-striped table-hover">';
                        html += '<thead class="table-primary"><tr><th>Select</th><th>Item</th><th>Quantity</th><th>Load Quantity</th></tr></thead>';
                        html += '<tbody>';
                        data.products.forEach((product) => {
                            html += `<tr>
                                        <td><input type="checkbox" class="product-checkbox" data-item="${product.item}"></td>
                                        <td>${product.item}</td>
                                        <td>${product.available}</td>
                                        <td><input type="number" class="form-control quantity-input" 
                                            id="quantity-${product.item.replace(/\s+/g, '-')}" 
                                            data-item="${product.item}"
                                            min="1" max="${product.available}" disabled></td>
                                    </tr>`;
                        });
                        html += '</tbody></table>';
                        productTable.innerHTML = html;

                        // Add event listeners to checkboxes
                        document.querySelectorAll('.product-checkbox').forEach(checkbox => {
                            checkbox.addEventListener('change', function() {
                                const item = this.getAttribute('data-item');
                                const quantityInput = document.getElementById(`quantity-${item.replace(/\s+/g, '-')}`);
                                quantityInput.disabled = !this.checked;
                                if (this.checked) {
                                    quantityInput.value = 1;
                                    quantityInput.focus();
                                } else {
                                    quantityInput.value = '';
                                }
                            });
                        });

                        // Add event listeners to quantity inputs
                        document.querySelectorAll('.quantity-input').forEach(input => {
                            input.addEventListener('keypress', function(e) {
                                if (e.key === 'Enter') {
                                    e.preventDefault();
                                    updateProductQuantity(this);
                                }
                            });
                        });

                        updateVehicleLoadTable(); // Update the vehicle load table
                    } else {
                        productTable.innerHTML = '<p class="text-danger">Failed to load products.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    productTable.innerHTML = '<p class="text-danger">An error occurred while loading products.</p>';
                });
        });

        unloadButton.addEventListener('click', function() {
            fetch('get_vehicle_load.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        let html = '<table class="table table-striped table-hover">';
                        html += '<thead class="table-primary"><tr><th>Select</th><th>Item</th><th>Quantity</th><th>Unload Quantity</th></tr></thead>';
                        html += '<tbody>';
                        data.products.forEach((product) => {
                            html += `<tr>
                                        <td><input type="checkbox" class="product-checkbox" data-item="${product.item}"></td>
                                        <td>${product.item}</td>
                                        <td>${product.quantity}</td>
                                        <td><input type="number" class="form-control quantity-input" 
                                            id="unload-quantity-${product.item.replace(/\s+/g, '-')}" 
                                            data-item="${product.item}"
                                            min="1" max="${product.quantity}" disabled></td>
                                    </tr>`;
                        });
                        html += '</tbody></table>';
                        productTable.innerHTML = html;

                        // Add event listeners to checkboxes
                        document.querySelectorAll('.product-checkbox').forEach(checkbox => {
                            checkbox.addEventListener('change', function() {
                                const item = this.getAttribute('data-item');
                                const quantityInput = document.getElementById(`unload-quantity-${item.replace(/\s+/g, '-')}`);
                                quantityInput.disabled = !this.checked;
                                if (this.checked) {
                                    quantityInput.value = 1;
                                    quantityInput.focus();
                                } else {
                                    quantityInput.value = '';
                                }
                            });
                        });

                        // Add event listeners to quantity inputs
                        document.querySelectorAll('.quantity-input').forEach(input => {
                            input.addEventListener('keypress', function(e) {
                                if (e.key === 'Enter') {
                                    e.preventDefault();
                                    updateVehicleUnload(this);
                                }
                            });
                        });

                        updateVehicleLoadTable(); // Update the vehicle load table
                    } else {
                        productTable.innerHTML = '<p class="text-danger">Failed to load vehicle contents.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    productTable.innerHTML = '<p class="text-danger">An error occurred while loading vehicle contents.</p>';
                });
        });

        // Initialize the map and load the vehicle contents when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            initMap();
            updateVehicleLoadTable();
        });
    </script>
</body>
</html>

