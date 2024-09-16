<?php include('../main/session_check.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        #categoryContainer {
            max-height: 300px; 
            overflow-y: auto; 
            border: 1px solid #ccc; 
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px; 
        }

        .content-area {
            margin-top: 20px; 
        }

    </style>
</head>

<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar (for larger screens) -->
        <div class="navigation col-md-3 col-lg-3 d-none d-md-flex flex-column p-3 bg-body-tertiary" style="width: 280px; min-height:100vh;">
            <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
                <svg class="bi pe-none me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
                <span class="fs-4"><img src="../images/world.png" alt="logo" height="50"></span>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li><a href="admin_map_main.php" class="nav-link link-body-emphasis">Map</a></li>
                <li><a href="announcement.php" class="nav-link link-body-emphasis">Announcements</a></li>
                <li><a href="#" class="nav-link active link-body-emphasis">Warehouse</a></li>
                <li><a href="createuser_admin_main.php" class="nav-link link-body-emphasis">Create Account</a></li>
                <li><a href="statistics_main.php" class="nav-link link-body-emphasis">Statistics</a></li>
                <li><a href="update_products_main.php" class="nav-link link-body-emphasis">Update Products from JSON</a></li>
                <li><a href="add_product_main.php" class="nav-link link-body-emphasis">Manage Products</a></li>
                <hr>
            </ul>
            <button class="btn btn-danger" id="logoutButton">Logout</button> 
        </div>
        <!-- Content area (for all screens) -->
        <div class="col-md-9 col-lg-9">
            <!-- Navbar (for smaller screens) -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light d-md-none">
                <div class="container-fluid">
                  <a class="navbar-brand" href="#"><img src="../images/world.png" alt="logo" height="50"></a>
                  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
              </button>
                </div>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                  <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                      <li class="nav-item"><a href="admin_map_main.php" class="nav-link link-body-emphasis">Map</a></li>
                      <li class="nav-item"><a href="announcement.php" class="nav-link link-body-emphasis">Announcements</a></li>
                      <li class="nav-item"><a href="#" class="nav-link active link-body-emphasis">Warehouse</a></li>
                      <li class="nav-item"><a href="createuser_admin_main.php" class="nav-link link-body-emphasis">Create Account</a></li>
                      <li class="nav-item"><a href="statistics_main.php" class="nav-link link-body-emphasis">Statistics</a></li>
                      <li class="nav-item"><a href="update_products_main.php" class="nav-link link-body-emphasis">Update Products from JSON</a></li>
                      <li class="nav-item"><a href="add_product_main.php" class="nav-link link-body-emphasis">Manage Products</a></li>
                      <hr>
                  </ul>
                  <button class="btn btn-danger" id="logoutButton2">Logout</button> 
              </div>
            </nav>

            <div class="content-area">
                <h2 class="text-center">Warehouse</h2>

                <div id="message" class="alert" style="display:none;"></div>

                <div id="categoryContainer" class="form-group mt-4">
                <label><h4>Select Categories:</h4></label>
                    <!-- The categories will be populated here  -->
                </div>
                <table id="productTable" class="table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Available</th>
                            <th>Details</th>
                            <th>Load (Vehicle ID, Quantity)</th>
                        </tr>
                    </thead>
                    <tbody id="productTableBody">
                        <!-- Products will be displayed here -->
                    </tbody>
                </table>
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


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
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
        
        document.addEventListener('DOMContentLoaded', function() {
            // Fetch and populate categories
            fetch('get_categories.php')
                .then(response => response.json())
                .then(data => {
                    var categoryContainer = document.getElementById('categoryContainer');
                    data.forEach(category => {
                        var checkbox = document.createElement('div');
                        checkbox.className = 'form-check';
                        checkbox.innerHTML = `
                            <input class="form-check-input category-checkbox" type="checkbox" value="${category.category_id}" id="category${category.category_id}" name="category[]">
                            <label class="form-check-label" for="category${category.category_id}">
                                ${category.category_name}
                            </label>
                        `;
                        categoryContainer.appendChild(checkbox);
                    });
                    // Add event listener after creating checkboxes
                    addCheckboxListeners();
                })
                .catch(error => console.error('Error fetching categories:', error));
        });

        function addCheckboxListeners() {
            var checkboxes = document.querySelectorAll('.category-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', fetchProducts);
            });
        }

        function fetchProducts() {
            var selectedCategories = Array.from(document.querySelectorAll('.category-checkbox:checked'))
                .map(cb => cb.value);
            
            if (selectedCategories.length === 0) {
                document.getElementById('productTableBody').innerHTML = '';
                return;
            }

            fetch('get_products.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ category_ids: selectedCategories })
            })
            .then(response => response.json())
            .then(data => {
                displayProducts(data);
            })
            .catch(error => console.error('Error:', error));
        }

        function displayProducts(products) {
            var productTableBody = document.getElementById('productTableBody');
            productTableBody.innerHTML = '';
            products.forEach(product => {
                var row = productTableBody.insertRow();
                row.innerHTML = `
                    <td>${product.item}</td>
                    <td>${product.available}</td>
                    <td>${product.details}</td>
                    <td>${product.vehicle_id || 'N/A'}, ${product.quantity || 0}</td>

                `;
            });
        }
        
    </script>
</body>

</html>
