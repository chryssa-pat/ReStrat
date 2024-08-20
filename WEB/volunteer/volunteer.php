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
    <title>Volunteer Map</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <link rel="stylesheet" href="volunteer.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="row">
            
            <div class=" col-md-3 col-lg-3  d-none d-md-flex flex-column p-3 bg-body-tertiary"style="width: 280px; min-height:100vh;">
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
                        <a href="load_management.html" class="nav-link link-body-emphasis">
                            <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#speedometer2"></use></svg>
                            Load Management
                        </a>
                    </li>
                    <li>
                        <a href="tasks.html" class="nav-link link-body-emphasis">
                            <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#grid"></use></svg>
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
                        <li><a href="settings.html" class="dropdown-item">Settings</a></li>
                        <li><a class="dropdown-item" id="logoutButton" href="#">Logout</a></li>
                    </ul>
                </div>
                <hr>
                <div id="filters">
                    <h5>Filters</h5>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="pendingFilter" checked>
                        <label class="form-check-label" for="pendingFilter">Show Pending Offers/Inquiries</label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="approvedFilter" checked>
                        <label class="form-check-label" for="approvedFilter">Show Approved Offers/Inquiries</label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="connectionLinesFilter">
                        <label class="form-check-label" for="connectionLinesFilter">Show Connection Lines</label>
                    </div>
                </div>
            </div>

            <div class="col-md-9 col-lg-9 ">
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
                            <a href="#" class="nav-link activelink-body-emphasis">
                                <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#speedometer2"></use></svg>
                                Load Managment
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="tasks.html" class="nav-link active link-body-emphasis">
                                <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#grid"></use></svg>
                                Tasks
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
              
                <div class="map_container">
                    <div id="map"></div>
                </div>

            </div>
        </div>
    </div>
        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin=""></script>
    <script src="./javascript/map.js" charset="utf-8"></script>


    <script>
          document.getElementById('logoutButton').addEventListener('click', function (e) {
                      e.preventDefault();
                      var confirmLogout = confirm('Are you sure you want to logout?');
                      if (confirmLogout) {
                          window.location.href = "../main/logout.php";
                      }
                  });
        var map;
        var carIcon = L.divIcon({
        className: 'car-icon',
        html: '<i class="fas fa-car"></i>',
        iconSize: [30, 30],
        iconAnchor: [15, 15]
        });
        var volunteerMarker;
        var baseMarker;
        var civilianMarkers = [];
        var filters = {
            pending: false,
            approved: false,
            connectionLines: false
        };
        var connectionLines = {};
    
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

            document.getElementById('pendingFilter').addEventListener('change', function() {
    filters.pending = this.checked;
            updateMarkers();
            clearAllConnectionLines();
            updateConnectionLines();
        });

        document.getElementById('approvedFilter').addEventListener('change', function() {
            filters.approved = this.checked;
            updateMarkers();
            clearAllConnectionLines();
            updateConnectionLines();
        });

        document.getElementById('connectionLinesFilter').addEventListener('change', function() {
            filters.connectionLines = this.checked;
            clearAllConnectionLines();
            updateConnectionLines();
        });
        
            // Αρχικοποιούμε τα checkboxes
            document.getElementById('pendingFilter').checked = filters.pending;
            document.getElementById('approvedFilter').checked = filters.approved;
            document.getElementById('connectionLinesFilter').checked = filters.connectionLines;

            getBaseLocation();
            getVolunteerLocation();
            getApprovedCivilians();
        }
    
        function getVolunteerLocation() {
            if ("geolocation" in navigator) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var lat = position.coords.latitude;
                    var lng = position.coords.longitude;
                    
                    if (volunteerMarker) {
                        volunteerMarker.setLatLng([lat, lng]);
                    } else {
                        volunteerMarker = L.marker([lat, lng], {icon: carIcon, draggable: true}).addTo(map);
                        addPermanentLabel(volunteerMarker, "Volunteer Location<br>Lat: " + lat.toFixed(6) + "<br>Lng: " + lng.toFixed(6));
                    }

                    updateLocation(lat, lng);

                    volunteerMarker.on('dragend', function(event) {
                        var position = volunteerMarker.getLatLng();
                        updateLocation(position.lat, position.lng);
                        updateLabel(volunteerMarker, "Volunteer Location<br>Lat: " + position.lat.toFixed(6) + "<br>Lng: " + position.lng.toFixed(6));
                    });

                    fitMapToAllMarkers();
                });
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
    
                        fitMapToAllMarkers();
                    } else {
                        console.error('Failed to get base location:', data.error);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function getApprovedCivilians() {
            fetch('get_approved_civilians.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Clear existing markers
                        civilianMarkers.forEach(markerInfo => markerInfo.marker.remove());
                        civilianMarkers = [];

                        data.coordinates.forEach((coord) => {
                            var icon;
                            if (coord.type === 'inquiry') {
                                icon = coord.status === 'approved' ? inquiryApprovedIcon : inquiryPendingIcon;
                            } else {
                                icon = coord.status === 'approved' ? offerApprovedIcon : offerPendingIcon;
                            }

                            var marker = L.marker([coord.latitude, coord.longitude], {icon: icon})
                                .bindPopup(() => createPopupContent(coord));
                            
                            civilianMarkers.push({marker: marker, type: coord.type, status: coord.status});
                        });
                        updateMarkers();
                        fitMapToAllMarkers();
                        updateConnectionLines();
                    } else {
                        console.error('Failed to get civilian coordinates:', data.error);
                    }
                })
                .catch(error => console.error('Error:', error));
        }
        function createPopupContent(coord) {
       let content = `<div id="popup-${coord.id}">
           <strong>${coord.type.charAt(0).toUpperCase() + coord.type.slice(1)}</strong><br>
           Name: ${coord.full_name || 'N/A'}<br>
           Phone: ${coord.phone || 'N/A'}<br>
           Registration Date: ${coord.registration_date || 'N/A'}<br>
           Product: ${coord.product || 'N/A'}<br>
           Quantity: ${coord.quantity || 'N/A'}<br>
           <span class="status">Status: ${coord.status}</span><br>`;
       
       if (coord.status === 'approved') {
           content += `Vehicle ID: <span class="vehicle-id">${coord.approved_vehicle_id || 'N/A'}</span><br>
                       Approved Date: <span class="approved-timestamp">${coord.approved_timestamp || 'N/A'}</span><br>`;
       }
       
       if (coord.status === 'pending') {
           content += `<button id="accept-btn-${coord.id}" onclick="handleAccept('${coord.type}', ${coord.id})">Accept</button>`;
       }
       
       content += `</div>`;
       return content;
   }

        function drawConnectionLine(id) {
            const markerInfo = civilianMarkers.find(m => m.id == id);
            if (markerInfo && volunteerMarker) {
                const line = L.polyline([volunteerMarker.getLatLng(), markerInfo.marker.getLatLng()], {
                    color: 'red',
                    weight: 2,
                    opacity: 0.5,
                    dashArray: '10, 10'
                }).addTo(map);
                connectionLines[id] = line;
            }
        }

        function handleAccept(type, id) {
            console.log(`handleAccept called with type: ${type}, id: ${id}`);
            const acceptBtn = document.getElementById(`accept-btn-${id}`);
            if (acceptBtn) {
                acceptBtn.disabled = true;
            }

            // Παίρνουμε το τρέχον timestamp
            const timestamp = new Date().toISOString().slice(0, 19).replace('T', ' ');

            fetch('accept_request.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `type=${type}&id=${id}&timestamp=${timestamp}`
            })
            .then(response => response.json())
            .then(data => {
                console.log('Response from server:', data);
                if (data.success) {
                    console.log('Request approved successfully');
                    updateUIAfterApproval(type, id, data.vehicle_id, data.approved_timestamp);
                    drawConnectionLine(id);
                } else {
                    console.error('Failed to approve request:', data.error);
                    alert(data.error); // Εμφανίζουμε το μήνυμα λάθους στον χρήστη
                    if (acceptBtn) {
                        acceptBtn.disabled = false;
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Σφάλμα κατά την επεξεργασία του αιτήματος');
                if (acceptBtn) {
                    acceptBtn.disabled = false;
                }
            });
        }

        function updateUIAfterApproval(type, id, vehicleId, timestamp) {
       console.log(`Updating UI after approval for ${type} with id ${id}`);
       const markerInfo = civilianMarkers.find(m => m.id == id);
       if (markerInfo) {
           markerInfo.status = 'approved';
           markerInfo.approved_vehicle_id = vehicleId; // Ensure this is set
           markerInfo.approved_timestamp = timestamp; // Ensure this is set
           updateMarkerIcon(markerInfo);

           // Update the popup content
           const newContent = createPopupContent({
               ...markerInfo,
               type: type,
               id: id,
               status: 'approved',
               approved_vehicle_id: vehicleId,
               approved_timestamp: timestamp
           });
           markerInfo.marker.setPopupContent(newContent);

           // If the popup is open, reopen it to refresh
           if (markerInfo.marker.isPopupOpen()) {
               markerInfo.marker.openPopup();
           }
       }

       updateMarkers();
       updateConnectionLines();
   }

        function updateMarkerIcon(markerInfo) {
            let icon;
            if (markerInfo.type === 'inquiry') {
                icon = markerInfo.status === 'approved' ? inquiryApprovedIcon : inquiryPendingIcon;
            } else {
                icon = markerInfo.status === 'approved' ? offerApprovedIcon : offerPendingIcon;
            }
            markerInfo.marker.setIcon(icon);
        }

        function handleFinish(type, id) {
            console.log(`Finish button clicked for ${type} with id ${id}`);
        }
    
        function addPermanentLabel(marker, content) {
            marker.bindTooltip(content, {
                permanent: true,
                direction: 'top',
                offset: L.point(0, -30)
            }).openTooltip();
        }
    
        function updateLabel(marker, content) {
            marker.setTooltipContent(content);
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
                    updateLabel(volunteerMarker, "Volunteer Location<br>Lat: " + lat.toFixed(6) + "<br>Lng: " + lng.toFixed(6));
                } else {
                    console.error('Failed to update location:', data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    
        function fitMapToAllMarkers() {
            var allMarkers = [volunteerMarker, baseMarker, ...civilianMarkers.map(m => m.marker)].filter(Boolean);
            if (allMarkers.length > 0) {
                var group = new L.featureGroup(allMarkers);
                map.fitBounds(group.getBounds().pad(0.1));
            } else {
                console.log("No markers to fit");
            }
        }

        function updateConnectionLines() {
            // Αφαίρεση όλων των υπαρχόντων γραμμών
            map.eachLayer(function (layer) {
                if (layer instanceof L.Polyline) {
                    map.removeLayer(layer);
                }
            });
            connectionLines = {};

            // Έλεγχος αν πρέπει να σχεδιαστούν οι γραμμές
            if (filters.approved && filters.connectionLines && volunteerMarker) {
                civilianMarkers.forEach(markerInfo => {
                    if (markerInfo.status === 'approved' && markerInfo.marker.getLatLng()) {
                        const line = L.polyline([volunteerMarker.getLatLng(), markerInfo.marker.getLatLng()], {
                            color: 'red',
                            weight: 2,
                            opacity: 0.5,
                            dashArray: '10, 10'
                        }).addTo(map);
                        connectionLines[markerInfo.id] = line;
                    }
                });
            }
        }

        function updateMarkers() {
            var allFiltersOff = !filters.pending && !filters.approved;
            
            civilianMarkers.forEach(markerInfo => {
                if (allFiltersOff) {
                    markerInfo.marker.remove();
                } else if ((markerInfo.status === 'pending' && filters.pending) ||
                            (markerInfo.status === 'approved' && filters.approved)) {
                    markerInfo.marker.addTo(map);
                } else {
                    markerInfo.marker.remove();
                }
            });
            
            // Πάντα εμφάνιζε το εικονίδιο του αυτοκινήτου και τη βάση
            if (volunteerMarker) volunteerMarker.addTo(map);
            if (baseMarker) baseMarker.addTo(map);
            
            clearAllConnectionLines();
            updateConnectionLines();
        }

        function clearAllConnectionLines() {
            map.eachLayer(function (layer) {
                if (layer instanceof L.Polyline) {
                    map.removeLayer(layer);
                }
            });
            connectionLines = {};
        }
    
        window.onload = initMap;
    </script>
</body>
</html>




