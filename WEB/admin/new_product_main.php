<?php include('../main/session_check.php'); ?> 
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
                        <a href="admin_map_main.php" class="nav-link link-body-emphasis">
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
            <div class="col-md-9 col-lg-9 ">
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
                              <a href="admin_map_main.php" class="nav-link active link-body-emphasis">
                                  <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#table"></use></svg>
                                  Map
                              </a>
                          </li>
                          <li class="nav-item">
                            <a href="announcement.php" class="nav-link activelink-body-emphasis">
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

                
                <div class="card mt-4">
                    <div class="card-body text-center">
                        <h2 class="card-title mb-4">Add New Product</h2>
                        <p class="card-text">
                            Add a new product to the database.
                        </p>
                        <form id="addProductForm" action="add_product.php" method="POST">
                            <div class="mb-3">
                                <select class="form-select" id="categoryId" name="categoryId" required>
                                    <option value="">Select a category</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <input type="number" class="form-control" id="productId" name="productId" placeholder="Enter product ID" required>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" id="item" name="item" placeholder="Enter product name" required>
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control" id="description" name="description" placeholder="Enter product description" required></textarea>
                            </div>
                            <div class="mb-3">
                                <input type="number" class="form-control" id="available" name="available" placeholder="Enter available quantity" required>
                            </div>
                            <div id="productDetails">
                                <div class="mb-3 row">
                                    <div class="col">
                                        <input type="text" class="form-control" name="detailName[]" placeholder="Detail name">
                                    </div>
                                    <div class="col">
                                        <input type="text" class="form-control" name="detailValue[]" placeholder="Detail value">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg">Add Product</button>
                        </form>
                    </div>
                </div>
            </div>
                

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous">
    </script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
    

    $(document).ready(function() {
        var categorySelect = $('#categoryId');

        // Fetch categories
        $.getJSON('get_categories.php', function(data) {
            // Populate categories
            $.each(data, function(index, category) {
                categorySelect.append($('<option></option>').attr('value', category.category_id).text(category.category_name));
            });

            // Initialize Select2
            categorySelect.select2({
                placeholder: 'Search for a category...',
                allowClear: true
            });
        });

        // Handle form submission
        $('#addProductForm').on('submit', function(e) {
            e.preventDefault();
            
            var formData = new FormData(this);
            
            $.ajax({
                url: 'add_product.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        $('#addProductForm')[0].reset();
                        categorySelect.val('').trigger('change');
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('XHR:', xhr);
                    console.error('Status:', status);
                    console.error('Error:', error);
                    alert('An error occurred. Please check the console for more information.');
                }
            });
        });

        // Add new detail fields
        $('#addDetailField').click(function() {
            var newDetail = `
                <div class="mb-3 row">
                    <div class="col">
                        <input type="text" class="form-control" name="detailName[]" placeholder="Detail name">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" name="detailValue[]" placeholder="Detail value">
                    </div>
                </div>
            `;
            $('#productDetails').append(newDetail);
        });
    });


        
    </script>

</body>

</html>