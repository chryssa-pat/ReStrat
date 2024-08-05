<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Announcement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <link rel="stylesheet" href="create_announcement.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar (for larger screens) -->
            <div class="col-md-3 col-lg-3 d-none d-md-flex flex-column p-3 bg-body-tertiary" style="width: 280px; min-height:100vh;">
                <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
                    <span class="fs-4"><img src="../images/world.png" alt="logo" height="50"></span>
                </a>
                <hr>
                <ul class="nav nav-pills flex-column mb-auto">
                    <li><a href="#" class="nav-link link-body-emphasis">Map</a></li>
                    <li><a href="#" class="nav-link link-body-emphasis">Announcements</a></li>
                    <li><a href="warehouse_main.php" class="nav-link link-body-emphasis">Warehouse</a></li>
                    <li><a href="createuser_admin_main.php" class="nav-link link-body-emphasis">Create Account</a></li>
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
                        <a class="navbar-brand" href="#"><img src="../images/world.png" alt="logo" height="50"></a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                    </div>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item"><a href="#" class="nav-link active link-body-emphasis">Map</a></li>
                            <li class="nav-item"><a href="#" class="nav-link link-body-emphasis">Announcements</a></li>
                            <li class="nav-item"><a href="warehouse_main.php" class="nav-link link-body-emphasis">Warehouse</a></li>
                            <li class="nav-item"><a href="createuser_admin_main.php" class="nav-link link-body-emphasis">Create Account</a></li>
                            <hr>
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

                <br>
                <div id="Frame">
                    <h2 class="text-center">Create Announcement</h2>
                    <form id="Form" action="create_announcement.php" method="POST">
                        <label for="category">Select Category:</label>
                        <select id="category" name="category">
                            <!-- Categories will be populated dynamically -->
                        </select>

                        <label for="item">Select Item:</label>
                        <select id="item" name="item_name">
                            <!-- Items will be populated dynamically based on the selected category -->
                        </select>

                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" class="form-control" min="1" required>
                        <button type="submit" id="createAnnouncementButton" class="btn btn-primary">Create Announcement</button>
                    </form>
                </div>
                <div id="checkoutFrame" class="announcement-frame">
                    <!-- This area can be used to display added items or other information -->
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function () {
            var data;

            // Fetch categories and items
            $.getJSON('fetch_data.php', function (fetchedData) {
                data = fetchedData;
                var categorySelect = $('#category');
                var itemSelect = $('#item');

                // Populate categories
                populateSelect(categorySelect, data.categories);

                // Update items when category changes
                categorySelect.change(function () {
                    var selectedCategory = $(this).val();
                    updateItems(selectedCategory);
                });

                // Add search functionality to category select
                addSearchToSelect(categorySelect, data.categories);

                // Add search functionality to item select
                addSearchToSelect(itemSelect, []);
            });

            function populateSelect(select, options) {
                select.empty().append($('<option></option>').attr('value', '').text('Choose an option'));
                $.each(options, function (index, option) {
                    if (typeof option === 'object') {
                        select.append($('<option></option>').attr('value', option.id).text(option.name));
                    } else {
                        select.append($('<option></option>').attr('value', option).text(option));
                    }
                });
            }

            function updateItems(selectedCategory) {
                var itemSelect = $('#item');
                if (selectedCategory in data.items) {
                    populateSelect(itemSelect, data.items[selectedCategory]);
                    addSearchToSelect(itemSelect, data.items[selectedCategory]);
                } else {
                    itemSelect.empty().append($('<option></option>').attr('value', '').text('Choose an item'));
                }
            }

            function addSearchToSelect(select, options) {
                select.select2({
                    data: options.map(option => {
                        return typeof option === 'object' ? 
                            { id: option.id, text: option.name } : 
                            { id: option, text: option };
                    }),
                    placeholder: 'Search...',
                    allowClear: true,
                    matcher: matchCustom
                });
            }

            function matchCustom(params, data) {
                if ($.trim(params.term) === '') {
                    return data;
                }
                if (typeof data.text === 'undefined') {
                    return null;
                }
                if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
                    var modifiedData = $.extend({}, data, true);
                    return modifiedData;
                }
                return null;
            }

            $('#Form').submit(function (e) {
                e.preventDefault();

                var itemName = $('#item').val();
                var quantity = $('#quantity').val();

                if (!itemName) {
                    alert('Please select an item.');
                    return;
                }

                if (quantity <= 0) {
                    alert('Please enter a valid quantity.');
                    return;
                }

                $.ajax({
                    url: 'create_announcement.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        var result = JSON.parse(response);
                        if (result.success) {
                            $('#checkoutFrame').html('<div class="alert alert-success">' + result.message + '</div>');
                        } else {
                            $('#checkoutFrame').html('<div class="alert alert-danger">' + result.error + '</div>');
                        }
                    },
                    error: function () {
                        $('#checkoutFrame').html('<div class="alert alert-danger">An error occurred while submitting the form.</div>');
                    }
                });
            });

            document.getElementById('logoutButton').addEventListener('click', function () {
                var confirmLogout = confirm('Are you sure you want to logout?');
                if (confirmLogout) {
                    window.location.href = "../main/main.html"; // Replace 'main.html' with the actual URL you want to redirect to
                }
            });
        });
    </script>
</body>
</html>
