<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unauthorized Access</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }
        .container {
            text-align: center;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #d9534f;
        }
        a {
            color: #0275d8;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Unauthorized Access</h1>
        <p>Sorry, you don't have permission to access this page.</p>
        <p>Please return to your <a href="<?php echo getHomePage(); ?>">dashboard</a>.</p>
    </div>
</body>
</html>
<?php
function getHomePage() {
    $profile = $_SESSION['user_profile'] ?? 'default';
    switch ($profile) {
        case 'civilian':
            return '../civilian/civilian_main.php';
        case 'volunteer':
            return '../volunteer/volunteer.php';
        case 'administrator':
            return '../admin/announcement.php';
        default:
            return '../main/login.html';
    }
}
?>
