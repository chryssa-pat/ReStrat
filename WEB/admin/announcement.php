<?php include('../main/session_check.php'); ?> <!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Announcement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="create_announcement.css">
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
                    <li><a href="#" class="nav-link link-body-emphasis">Map</a></li>
                    <li><a href="#" class="nav-link link-body-emphasis">Announcements</a></li>
                    <li><a href="warehouse_main.php" class="nav-link link-body-emphasis">Warehouse</a></li>
                    <li><a href="#" class="nav-link link-body-emphasis">Create Account</a></li>
                    <hr>
                </ul>
                <div class="dropdown">
                    <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Account</button>
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
                          <li class="nav-item"><a href="#" class="nav-link active link-body-emphasis">Announcements</a></li>
                          <li class="nav-item"><a href="warehouse_main.php" class="nav-link active link-body-emphasis">Warehouse</a></li>
                          <li class="nav-item"><a href="#" class="nav-link active link-body-emphasis">Create Account</a></li>
                          <hr>
                      </ul>
                      <div class="dropdown">
                          <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Account</button>
                          <ul class="dropdown-menu">
                              <li><a class="dropdown-item" href="settings.html">Settings</a></li>
                              <li><a classdropdown-item" id="logoutButton" href="#">Logout</a></li>
                          </ul>
                      </div>
                  </div>
                </nav>
                <br>
                <div id="Frame">
                    <h2 style="text-align: center;">Create Announcement</h2>
                    <form id="Form" action="create_announcement.php" method="POST">
                        <label for="category">Select Category:</label>
                        <select id="category">
                            <!-- Categories will be populated dynamically -->
                        </select>

                        <label for="item">Select Item:</label>
                        <select id="item" name="item_name">
                            <!-- Items will be populated dynamically based on the selected category -->
                        </select>

                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" class="form-control" min="1" required>
                        
                        <button type="button" id="addItemButton" class="btn btn-secondary">Add Item</button>
                    </form>
                </div>
                <div>
                    <div id="checkoutFrame" class="announcement-frame">
                        <!-- This area will be used to display added items -->
                    </div>
                    <div>
                        <button id="createAnnouncementButton" class="btn btn-primary">Create Announcement</button>
                    </div>
                    <!-- Custom Alert -->
                        <div id="customAlert" class="alert alert-success alert-dismissible fade" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999;">
                        <span id="alertMessage"></span>
                        <button type="button" class="btn-close" aria-label="Close" onclick="closeAlert()"></button>
                        </div>

                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
    var data;
    var items = [];

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
        // If there are no search terms, return all of the data
        if ($.trim(params.term) === '') {
            return data;
        }

        // Do not display the item if there is no 'text' property
        if (typeof data.text === 'undefined') {
            return null;
        }

        // params.term should be the term that is used for searching
        // data.text is the text that is displayed for the data object
        if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
            var modifiedData = $.extend({}, data, true);
            return modifiedData;
        }

        // Return null if the term should not be displayed
        return null;
    }

    $('#addItemButton').click(function() {
        var itemId = $('#item').val();
        var itemName = $('#item option:selected').text();
        var quantity = $('#quantity').val();

        console.log('Selected item:', itemName, '(ID:', itemId, ')');
        console.log('Entered quantity:', quantity);

        if (!itemId || itemName === 'Choose an option') {
            alert('Please select an item.');
            return;
        }

        if (quantity <= 0) {
            alert('Please enter a valid quantity.');
            return;
        }

        items.push({
            id: itemId,  // Store as 'id' to match the structure you're using
            name: itemName,  // Store the name as well
            quantity: parseInt(quantity)
        });
        console.log('Items after adding:', JSON.stringify(items, null, 2));
        updateCheckoutFrame();

        // Clear the form
        $('#category').val('').trigger('change');
        $('#item').val('').trigger('change');
        $('#quantity').val('');
    });

    function updateCheckoutFrame() {
        console.log('Current items:', JSON.stringify(items, null, 2)); // Log the entire items array

        var html = '<h3>Added Items:</h3>';
        items.forEach(function(item, index) {
            var itemId = item.itemId || item.id; // Use itemId if available, otherwise use id
            var itemName = item.itemName || item.name || 'Unknown Item';
            
            if (itemName === 'Unknown Item') {
                // If the item name is not stored, try to fetch it from the select options
                var selectOption = $('#item option[value="' + itemId + '"]');
                if (selectOption.length) {
                    itemName = selectOption.text();
                }
            }

            html += '<div>Item ID: ' + itemId + ' (' + itemName + ') - Quantity: ' + item.quantity + 
                    ' <button class="btn btn-sm btn-danger remove-item" data-index="' + index + '">Remove</button></div>';
        });
        $('#checkoutFrame').html(html);
    }

    $(document).on('click', '.remove-item', function() {
        var index = $(this).data('index');
        items.splice(index, 1);
        updateCheckoutFrame();
    });

    function showAlert(message, type) {
    var alertDiv = $('#customAlert');
    var alertMessage = $('#alertMessage');
    
    alertMessage.text(message);
    alertDiv.removeClass('alert-success alert-danger').addClass('alert-' + type);
    alertDiv.addClass('show');
    
    setTimeout(function() {
        alertDiv.removeClass('show');
    }, 3000); // Auto-hide after 3 seconds
}

$('#createAnnouncementButton').click(function() {
    if (items.length === 0) {
        showAlert('Please add at least one item before creating an announcement.', 'danger');
        return;
    }

   

    $.ajax({
        url: 'create_announcement.php',
        type: 'POST',
        data: JSON.stringify({ items: items }),
        contentType: 'application/json',
        success: function(response) {
            try {
                var parsedResponse = JSON.parse(response);
                console.log('Received response:', parsedResponse);
                if (parsedResponse.success) {
                    showAlert(parsedResponse.message, 'success');
                    items = []; // Clear the items array
                    updateCheckoutFrame(); // Update the display
                } else {
                    showAlert(parsedResponse.error, 'danger');
                }
            } catch (e) {
                console.error('Parsing error:', e);
                showAlert('Error parsing server response.', 'danger');
            }
        },
        error: function(xhr, status, error) {
            console.error('Ajax error:', status, error);
            showAlert('An error occurred while submitting the form: ' + error, 'danger');
        }
    });
});


    });

    </script>
</body>
</html>