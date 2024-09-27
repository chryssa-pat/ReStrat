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
    <title>Volunteer Tasks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="volunteer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .action-buttons {
            white-space: nowrap;
        }
        .action-buttons button {
            margin: 0 2px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
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
                        <a href="load_management.php" class="nav-link link-body-emphasis">
                            Load Management
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-link active link-body-emphasis">
                            Tasks
                        </a>
                    </li>
                    <hr>
                </ul>
                <hr>
                <button class="btn btn-danger" id="logoutButton">Logout</button> 
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
                            <hr>
                              <a href="volunteer.php" class="nav-link active link-body-emphasis">
                                  <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#table"></use></svg>
                                  Map
                              </a>
                          </li>
                          <li class="nav-item">
                            <a href="load_management.php" class="nav-link activelink-body-emphasis">
                                <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#speedometer2"></use></svg>
                                Load Managment
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link active link-body-emphasis">
                                <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#grid"></use></svg>
                                Tasks
                            </a>
                        </li>
                
                      </ul>
                                        
                        <hr>
                        <button class="btn btn-danger" id="logoutButton2">Logout</button> 
                    </div>
                </nav>

                <div class="container mt-5">
                    <h2>My Tasks</h2>
                    <div class="table-responsive">
                        <table class="table table-striped" id="tasksTable">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Full Name</th>
                                    <th>Phone</th>
                                    <th>Item</th>
                                    <th>Quantity</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Task details will be added here dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to logout?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmLogout">Logout</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
          document.getElementById('logoutButton').addEventListener('click', function (e) {
            e.preventDefault();
            $('#logoutModal').modal('show'); 
        });

        document.getElementById('logoutButton2').addEventListener('click', function (e) {
            e.preventDefault();
            $('#logoutModal').modal('show'); 
        });

        // Confirm logout action
        document.getElementById('confirmLogout').addEventListener('click', function () {
            window.location.href = "../main/logout.php"; 
        });
        let userLocation = null;

        function getUserLocation() {
            return new Promise((resolve, reject) => {
                if ("geolocation" in navigator) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        userLocation = {
                            latitude: position.coords.latitude,
                            longitude: position.coords.longitude
                        };
                        resolve(userLocation);
                    }, function(error) {
                        console.error("Error getting user location:", error);
                        reject(error);
                    });
                } else {
                    console.error("Geolocation is not supported by this browser.");
                    reject(new Error("Geolocation not supported"));
                }
            });
        }

        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371; // Radius of the Earth in km
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
                      Math.sin(dLon/2) * Math.sin(dLon/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            const distance = R * c; // Distance in km
            return distance;
        }

        function loadTasks() {
            getUserLocation().then(() => {
                fetch('get_volunteer_tasks.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const tableBody = document.querySelector('#tasksTable tbody');
                            tableBody.innerHTML = '';
                            
                            if (data.tasks.length === 0) {
                                tableBody.innerHTML = '<tr><td colspan="9">No tasks found</td></tr>';
                                return;
                            }
                            
                            data.tasks.forEach(task => {
                                const distance = userLocation ? calculateDistance(userLocation.latitude, userLocation.longitude, task.latitude, task.longitude) : Infinity;
                                const isWithinRange = distance <= 0.05;
                                
                                const row = `
                                    <tr>
                                        <td>${task.type}</td>
                                        <td>${task.full_name}</td>
                                        <td>${task.phone}</td>
                                        <td>${task.item}</td>
                                        <td>${task.quantity}</td>
                                        <td>${new Date(task.task_date).toLocaleString()}</td>
                                        <td class="action-buttons">
                                            <button class="btn btn-success btn-sm" onclick="completeTask(${task.details_id}, '${task.type}')" ${isWithinRange ? '' : 'disabled'}>Complete</button>
                                            <button class="btn btn-danger btn-sm" onclick="cancelTask(${task.details_id}, '${task.type}')">Cancel</button>
                                        </td>
                                    </tr>
                                `;
                                tableBody.innerHTML += row;
                            });
                        } else {
                            console.error('Error:', data.error);
                            const tableBody = document.querySelector('#tasksTable tbody');
                            tableBody.innerHTML = `<tr><td colspan="9">Error loading tasks: ${data.error}</td></tr>`;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        const tableBody = document.querySelector('#tasksTable tbody');
                        tableBody.innerHTML = `<tr><td colspan="9">Error loading tasks: ${error.message}</td></tr>`;
                    });
                }).catch(error => {
                console.error('Error getting user location:', error);
                // Εδώ μπορείτε να προσθέσετε κώδικα για να ενημερώσετε τον χρήστη ότι δεν μπορέσατε να πάρετε την τοποθεσία του
            });
        }

        function completeTask(detailsId, type) {
            const formData = new FormData();
            formData.append('detailsId', detailsId);
            formData.append('type', type);

            fetch('complete_task.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Task completed successfully');
                    loadTasks(); // Επαναφόρτωση των εργασιών
                } else {
                    alert('Error completing task: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error completing task');
            });
        }

        function cancelTask(detailsId, type) {
            console.log(`Cancel task: ${detailsId} (${type})`);
            
            const formData = new FormData();
            formData.append('detailsId', detailsId);
            formData.append('type', type);

            fetch('cancel_task.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Task cancelled successfully');
                    loadTasks(); // Επαναφόρτωση των εργασιών
                } else {
                    alert('Error cancelling task: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error cancelling task');
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadTasks();
            // Ανανέωση της τοποθεσίας του χρήστη και των εργασιών κάθε λίγα λεπτά
            setInterval(loadTasks, 5 * 60 * 1000); // Κάθε 5 λεπτά
        });

    </script>
</body>
</html>