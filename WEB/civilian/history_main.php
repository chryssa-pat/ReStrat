<?php
include('../main/session_check.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offers History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="./history_offers.css">
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
                        <a href="announcements_main.php" class="nav-link link-body-emphasis">
                            <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#speedometer2"></use></svg>
                            Announcements
                        </a>
                    </li>
                    <li>
                        <a href="history_main.php" class="nav-link active link-body-emphasis">
                            <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#grid"></use></svg>
                            Offers History
                        </a>
                    </li>
                    <li>
                        <a href="history_inquiry_main.php" class="nav-link active link-body-emphasis">
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
                                <a href="civilian_main.php" class="nav-link active link-body-emphasis">
                                    <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#table"></use></svg>
                                    Inquiries
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="announcements_main.php" class="nav-link active link-body-emphasis">
                                    <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#speedometer2"></use></svg>
                                    Announcements
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="history_main.php" class="nav-link active link-body-emphasis">
                                    <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#grid"></use></svg>
                                    Offers History
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="history_inquiry_main.php" class="nav-link active link-body-emphasis">
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

                <main>
                    <h1 class="center-title">Offers History</h1>
                    <table class="table table-striped table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>Offer ID</th>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Current Status</th>
                                <th>Last Updated</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="history-table-body">
                            <!-- Data will be loaded here dynamically -->
                        </tbody>
                    </table>
                </main>
            </div>
        </div>
    </div>

    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Offer Status History</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody id="statusModalBody">
                            <!-- Status history will be inserted here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            $.ajax({
                url: 'history.php',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log("Received data:", data); // Debug: Log received data
                    renderOffers(data);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching history data:', error);
                    console.log('Response:', xhr.responseText); // Debug: Log full response
                    $('#history-table-body').html('<tr><td colspan="6" class="text-center">Error loading data. Please try again later.</td></tr>');
                }
            });
        });

        function renderOffers(groupedOffers) {
            var tbody = $('#history-table-body');
            tbody.empty();
            
            groupedOffers.forEach(function(offer) {
                var latestStatus = offer.statuses[0]; // Assuming statuses are sorted desc
                var row = $('<tr>').append(
                    $('<td>').text(offer.id),
                    $('<td>').text(offer.item),
                    $('<td>').text(offer.quantity),
                    $('<td>').append($('<span>').addClass('status-badge ' + getStatusClass(latestStatus.status)).text(latestStatus.status)),
                    $('<td>').text(formatDate(latestStatus.date)),
                    $('<td>').append(
                        $('<button>').addClass('btn btn-outline-primary btn-sm view-history-btn').text('View History').data('offer', offer),
                        latestStatus.status.toLowerCase() === 'pending' ? 
                            $('<button>').addClass('btn btn-danger btn-sm cancel-btn ms-2').text('Cancel').data('offer-id', offer.id) :
                            ''
                    )
                );
                
                tbody.append(row);
            });
            
            // Add click event for view history buttons
            $('.view-history-btn').on('click', function() {
                var offer = $(this).data('offer');
                showStatusHistory(offer);
            });

            // Add click event for cancel buttons
            $('.cancel-btn').on('click', function(e) {
                e.stopPropagation();
                var offerId = $(this).data('offer-id');
                if (confirm('Are you sure you want to cancel this offer?')) {
                    cancelOffer(offerId, $(this));
                }
            });
        }

        function showStatusHistory(offer) {
            var modalBody = $('#statusModalBody');
            modalBody.empty();
            
            offer.statuses.forEach(function(status) {
                var statusRow = $('<tr>').append(
                    $('<td>').append($('<span>').addClass('status-badge ' + getStatusClass(status.status)).text(status.status)),
                    $('<td>').text(formatDate(status.date))
                );
                modalBody.append(statusRow);
            });

            $('#statusModalLabel').text('Offer Status History - ID: ' + offer.id);
            var modal = new bootstrap.Modal(document.getElementById('statusModal'));
            modal.show();
        }

        function cancelOffer(offerId, buttonElement) {
            $.ajax({
                url: 'cancel_offer.php',
                method: 'POST',
                data: { offer_id: offerId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Refresh the entire table
                        location.reload();
                    } else {
                        alert('Failed to cancel offer: ' + response.error);
                    }
                },
                error: function(error) {
                    console.error('Error cancelling offer:', error);
                    alert('An error occurred while cancelling the offer. Please try again later.');
                }
            });
        }

        function getStatusClass(status) {
            switch(status.toLowerCase()) {
                case 'pending': return 'bg-warning text-dark';
                case 'approved': return 'bg-success text-white';
                case 'finished': return 'bg-info text-white';
                case 'disapproved': return 'bg-danger text-white';
                case 'cancelled': return 'bg-secondary text-white';
                default: return 'bg-light text-dark';
            }
        }

        function formatDate(dateString) {
            var date = new Date(dateString);
            return date.toLocaleString();
        }
    </script>

</body>

</html>