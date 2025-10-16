<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="navbar.css">
    <title>Homepage</title>
</head>
<body>
    <div class="navbar">
            <ul>
                <li class="nav-items">
                    <a href="index.php" id="unitease"><span style="color: #62929E">U</span>nitEase <img src="images/logo-blue.png" alt="logo blue" style="width: min(40px, 5vw);"></a>
                </li>
                <li class="nav-items">
                    <a href="contacts.php" id="contacts">Contacts</a>
                </li>
                <li class="nav-items">
                    <a href="aboutus.php" id="aboutus">About Us</a>
                </li>
            </ul>
    </div>
    <div id="main-content">
    <div id="content-header">
        <h1>Welcome to Admin's Homepage!</h1>
    </div>
    <a href="admin-units.php">
        <div class="features">Units</div>
    </a>
    <a href="admin-tenants.php">
    <div class="features">Tenants</div>
    </a>
    <a href="admin-requests.php">
    <div class="features">Tenant Requests</div>
    </a>
    <a href="admin-announcements.php">
    <div class="features">Send Announcements</div>
    </a>
    </div>
</body>
</html>