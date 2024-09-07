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
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="civilian.css">
</head>

<body>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar (for larger screens) -->
            <div class="col-md-3 col-lg-3 d-none d-md-flex flex-column p-3 bg-body-tertiary" style="width: 280px; min-height:100vh;">
                <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
                    <svg class="bi pe-none me-2" width="40" height="32">
                        <use xlink:href="#bootstrap"></use>
                    </svg>
                    <span class="fs-4"><img src="../images/world.png" alt="logo" height="50"></span>
                </a>

                <hr>

                <ul class="nav nav-pills flex-column mb-auto">
                    <li>
                        <a href="#" class="nav-link active link-body-emphasis">
                            <svg class="bi pe-none me-2" width="16" height="16">
                                <use xlink:href="#table"></use>
                            </svg>
                            Inquiries
                        </a>
                    </li>
                    <li>
                        <a href="announcements_main.php" class="nav-link link-body-emphasis">
                            <svg class="bi pe-none me-2" width="16" height="16">
                                <use xlink:href="#speedometer2"></use>
                            </svg>
                            Announcements
                        </a>
                    </li>
                    <li>
                        <a href="history_main.php" class="nav-link link-body-emphasis">
                            <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#grid"></use></svg>
                            Offers History
                        </a>
                    </li>
                    <li>
                        <a href="history_inquiry_main.php" class="nav-link link-body-emphasis">
                            <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#grid"></use></svg>
                            Inquiries History
                        </a>
                    </li>
                    <hr>
                </ul>
                <div class="dropdown">
                    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        Account
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
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
                            <li class="nav-item">
                                <a href="#" class="nav-link active link-body-emphasis">
                                    <svg class="bi pe-none me-2" width="16" height="16">
                                        <use xlink:href="#table"></use>
                                    </svg>
                                    Inquiries
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="announcements_main.php" class="nav-link link-body-emphasis">
                                    <svg class="bi pe-none me-2" width="16" height="16">
                                        <use xlink:href="#speedometer2"></use>
                                    </svg>
                                    Announcements
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="history_main.php" class="nav-link link-body-emphasis">
                                    <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#grid"></use></svg>
                                    Offers History
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="history_inquiry.php" class="nav-link link-body-emphasis">
                                    <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#grid"></use></svg>
                                    Inquiries History
                                </a>
                            </li>
                        </ul>

                        <div class="dropdown">
                            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                Account
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><a class="dropdown-item" href="settings.html">Settings</a></li>
                                <li><a class="dropdown-item" id="logoutButton" href="#">Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </nav>

                <div id="orderFrame">
                    <h2 style="text-align: center;">Inquiry Form</h2>

                    <form id="orderForm" method="post" action="submit_inquiry.php">
                        <label for="category">Select Category:</label>
                        <select id="category">
                            <!-- Categories will be populated dynamically -->
                        </select>

                        <label for="item">Select Item:</label>
                        <select id="item" name="item">
                            <!-- Items will be populated dynamically based on the selected category -->
                        </select>

                        <label for="quantity">People:</label>
                        <input type="number" id="quantity" name="quantity" min="1" required>

                        <label for="address">Address:</label>
                        <div id="addressDisplay">
                            <!-- Address will be displayed here -->
                        </div>

                        <button class="form_button" type="submit">Submit</button>
                    </form>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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

                    // Fetch addresses and display them
                    $.getJSON('fetch_addresses.php', function (addresses) {
                        var addressDisplay = $('#addressDisplay');
                        if (addresses.length > 0) {
                            var addressHtml = '<ul>';
                            $.each(addresses, function (index, address) {
                                addressHtml += 'Latitude: ' + address.latitude + ', Longitude: ' + address.longitude + '</li>';
                            });
                            addressHtml += '</ul>';
                            addressDisplay.html(addressHtml);
                        } else {
                            addressDisplay.html('<p>No addresses available</p>');
                        }
                    });

                    // Handle form submission
                    $('#orderForm').submit(function (e) {
                        e.preventDefault();

                        var formData = {
                            category: $('#category').val(),
                            item: $('#item').val(),
                            quantity: $('#quantity').val(),
                            address: $('#address').val()
                        };

                        $.ajax({
                            type: 'POST',
                            url: 'submit_inquiry.php',
                            data: formData,
                            dataType: 'json',
                            encode: true
                        })
                        .done(function (data) {
                            if (data.success) {
                                alert('Inquiry submitted successfully!');
                                $('#orderForm')[0].reset();
                            } else {
                                alert('Error: ' + data.message);
                            }
                        })
                        .fail(function () {
                            alert('An error occurred. Please try again.');
                        });
                    });

                    document.getElementById('logoutButton').addEventListener('click', function (e) {
                        e.preventDefault();
                        console.log('Logout button clicked'); // Debugging line
                        var confirmLogout = confirm('Are you sure you want to logout?');
                        if (confirmLogout) {
                            window.location.href = "../main/logout.php";
                        }
                    });
                });
                </script>

            </div>
        </div>
    </div>
</body>

</html>
</html>