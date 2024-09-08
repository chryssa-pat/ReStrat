<?php include('../main/session_check.php'); ?> 
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        .scrollable-categories {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            padding: 0.5rem;
        }
        .scrollable-categories .form-check {
            margin-bottom: 0.5rem;
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
                <li><a href="warehouse_main.php" class="nav-link link-body-emphasis">Warehouse</a></li>
                <li><a href="createuser_admin_main.php" class="nav-link link-body-emphasis">Create Account</a></li>
                <li><a href="statistics_main.php" class="nav-link link-body-emphasis">Statistics</a></li>
                <li><a href="update_products_main.php" class="nav-link link-body-emphasis">Update Products from JSON</a></li>
                <li><a href="add_product_main.php" class="nav-link active link-body-emphasis">Manage Products</a></li>
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
                      <li class="nav-item"><a href="warehouse_main.php" class="nav-link link-body-emphasis">Warehouse</a></li>
                      <li class="nav-item"><a href="createuser_admin_main.php" class="nav-link link-body-emphasis">Create Account</a></li>
                      <li class="nav-item"><a href="statistics_main.php" class="nav-link link-body-emphasis">Statistics</a></li>
                      <li class="nav-item"><a href="update_products_main.php" class="nav-link link-body-emphasis">Update Products from JSON</a></li>
                      <li class="nav-item"><a href="add_product_main.php" class="nav-link active link-body-emphasis">Manage Products</a></li>
                      <hr>
                  </ul>
                  <button class="btn btn-danger" id="logoutButton2">Logout</button> 
              </div>
            </nav>
            
            <!-- Spacer to add space between the menu and the form -->
            <div class="my-5"></div>
            
            <!-- Form Area -->
            <div class="mx-auto" style="max-width: 800px;">
                <h2 align="center">Delete Products</h2>
                <br>
                <p>You can delete one or more products at once by selecting their categories.</p>
                <br>
                <!-- Category selection -->
                <div class="mb-3">
                    <label for="categories" class="form-label">Select Categories:</label>
                    <div id="categories" class="scrollable-categories">
                        <!-- Categories will be loaded here dynamically -->
                    </div>
                </div>
                <br>
                <!-- Product table -->
                <div id="productTable">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product ID</th>
                                <th>Item</th>
                                <th>Details</th>
                                <th>Available Quantity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="productList">
                            <!-- Products will be loaded here dynamically -->
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
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous">
    </script>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

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
        $(document).ready(function() {
            // Fetch and populate categories
            $.getJSON('get_categories.php', function(data) {
                var categoryContainer = $('#categories');
                data.forEach(function(category) {
                    var checkbox = $('<div class="form-check">')
                        .append($('<input class="form-check-input category-checkbox" type="checkbox">')
                            .attr('id', 'category' + category.category_id)
                            .attr('value', category.category_id))
                        .append($('<label class="form-check-label">')
                            .attr('for', 'category' + category.category_id)
                            .text(category.category_name));
                    categoryContainer.append(checkbox);
                });
                // Add event listener after creating checkboxes
                $('.category-checkbox').on('change', fetchProducts);
            });

            function fetchProducts() {
                var selectedCategories = $('.category-checkbox:checked').map(function() {
                    return this.value;
                }).get();
                
                if (selectedCategories.length === 0) {
                    $('#productList').html('');
                    return;
                }

                $.ajax({
                    url: 'get_products.php',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({ category_ids: selectedCategories }),
                    success: function(response) {
                        displayProducts(response);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching products:", error);
                        $('#productList').html('<tr><td colspan="5">Error loading products. Please try again.</td></tr>');
                    }
                });
            }

            function displayProducts(products) {
                var productList = '';
                $.each(products, function(index, product) {
                    productList += '<tr>' +
                        '<td>' + product.product_id + '</td>' +
                        '<td>' + product.item + '</td>' +
                        '<td>' + (product.details || '') + '</td>' +
                        '<td>' + product.available + '</td>' +
                        '<td><button class="btn btn-danger delete-product" data-product-id="' + product.product_id + '">Delete</button></td>' +
                        '</tr>';
                });
                $('#productList').html(productList);
            }

            // Delete product
            $(document).on('click', '.delete-product', function() {
                var productId = $(this).data('product-id');
                if (confirm('Are you sure you want to delete this product?')) {
                    $.ajax({
                        url: 'delete_product.php',
                        type: 'POST',
                        data: { product_id: productId },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                alert(response.message);
                                fetchProducts(); // Refresh the product list
                            } else {
                                alert("Error: " + response.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Error deleting product:", error);
                            alert("Error deleting product. Please try again.");
                        }
                    });
                }
            });
        });
    </script>

</body>

</html>
