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
    <title>Announcements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="announcements.css">
 
</head>

</head>
<body>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-3 d-none d-md-flex flex-column p-3 bg-body-tertiary" style="width: 280px; min-height:100vh;">
                <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
                    <svg class="bi pe-none me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
                    <span class="fs-4"><img src="../images/world.png" alt="logo" height="50"></span>
                </a>
                <hr>
                <ul class="nav nav-pills flex-column mb-auto">
                    <li>
                        <a href="civilian_main.php" class="nav-link link-body-emphasis">
                            <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#table"></use></svg>
                            Inquiries
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-link active link-body-emphasis">
                            <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#speedometer2"></use></svg>
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
                    <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Account
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="settings.html" class="dropdown-item">Settings</a></li>
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
                                <a href="civilian_main.php" class="nav-link link-body-emphasis>
                                    <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#table"></use></svg>
                                    Inquiries
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link active link-body-emphasis">
                                    <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#speedometer2"></use></svg>
                                    Announcements
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="history_main.php" class="nav-link link-body-emphasis>
                                    <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#grid"></use></svg>
                                    Offers History
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="history_inquiry.php" class="nav-link link-body-emphasis>
                                    <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#grid"></use></svg>
                                    Inquiries History
                                </a>
                            </li>
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

                <main id="announcements-container">
                    <h1 class="center-title">Announcements</h1>
                    <!-- Announcements will be injected here by the AJAX call -->
                </main>
            </div>
        </div>
    </div>

    <div class="modal fade" id="offerModal" tabindex="-1" aria-labelledby="offerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="offerModalLabel">Make an Offer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="offerForm">
                    <input type="hidden" id="announceId" name="announce_id">
                    <input type="hidden" id="productId" name="product_id">
                    <div class="mb-3">
                        <label for="offerQuantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="offerQuantity" name="offer_quantity" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Offer</button>
                </form>
            </div>
        </div>
    </div>
</div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>

        <script>
         $.ajax({
        url: 'announcements.php',
        method: 'GET',
        success: function(response) {
            $('#announcements-container').append(response);
        },
        error: function(error) {
            console.error('Error fetching data:', error);
        }
    });

    
    document.getElementById('logoutButton').addEventListener('click', function (e) {
            e.preventDefault();
            var confirmLogout = confirm('Are you sure you want to logout?');
            if (confirmLogout) {
                window.location.href = "../main/logout.php";
            }
        });
    // Handle announcement button click
    $(document).on('click', '.announcement-button', function() {
    var announceId = $(this).data('announce-id');
    var productId = $(this).data('product-id');
    $('#announceId').val(announceId);
    $('#productId').val(productId);
    $('#offerModal').modal('show');
});

$('#offerForm').on('submit', function(e) {
    e.preventDefault();
    var formData = $(this).serialize();
    console.log("Form data being sent:", formData); // Debug log

    $.ajax({
        url: 'offer.php',
        method: 'POST',
        data: formData,
        dataType: 'json', // Expect JSON response
        success: function(response) {
            console.log("Response received:", response); // Debug log
            if (response.success) {
                $('#offerModal').modal('hide');
                alert('Offer submitted successfully!');
                // Refresh the announcements list
                location.reload();
            } else {
                alert(response.error || 'An error occurred while submitting the offer.');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error submitting offer:', textStatus, errorThrown);
            console.log('Response Text:', jqXHR.responseText); // Log the full response
            alert('An error occurred while submitting the offer. Please check the console for details.');
        }
    });
});
    </script>
    
</body>
</html>
