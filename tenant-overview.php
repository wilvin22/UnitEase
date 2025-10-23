<?php
session_start();
include 'navbar.html';
include 'database.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="tenant.css">
    <link rel="stylesheet" href="navbar.css">
    <title>Tenant Homepage</title>

</head>
<body>
<div id="main-content">
    <div class="dashboard">
        <div class="dashboard-header" id="tenant">
            <img src="images/user-blue.png" alt="user icon" style="width: 40px; height: 40px; margin-right: 12px;" class="icon">
            <h3>Tenant</h3>
            Username: <?php echo $_SESSION['username'];?>
        </div>
        <br>
        <a href="tenant.php">
            <div class="dashboard-item" id="overview">
                <img src="images/overview-blue.png" alt="overview" style="width: 28px; height: 28px; margin-right: 4px;" class="icon">
                Overview
            </div>
        </a>
        <a href="tenant-profile.php">
            <div class="dashboard-item" id="your-profile">
                <img src="images/user-blue.png" alt="user" style="width: 24px; height: 24px; margin-right: 8px;" class="icon">
                Your Profile
            </div>
        </a>
        <a href="tenant-unit.php">
            <div class="dashboard-item" id="view-unit">
                <img src="images/logo-blue.png" alt="manage units" style="width: 24px; height: 24px; margin-right: 8px;" class="icon">
                View Unit
            </div>
        </a>
        <a href="tenant-admin.php">
            <div class="dashboard-item" id="view-admin">
                <img src="images/user-blue.png" alt="manage tenants" style="width: 24px; height: 24px; margin-right: 8px;" class="icon">
                View Admin
            </div>
        </a>
        <a href="tenant-requests.php">
            <div class="dashboard-item" id="send-requests">
                <img src="images/request-blue.png" alt="request" style="width: 24px; height: 24px; margin-right: 8px;" class="icon">
                Send Requests
            </div>
        </a>
        <a href="tenant-announcements.php">
            <div class="dashboard-item" id="view-announcements">
                <img src="images/announcement-blue.png" alt="announcements" style="width: 24px; height: 24px; margin-right: 8px;" class="icon">
                View Announcements
            </div>
        </a>
        <form action="login.php" method="post">
            <button type="submit" id="logout-button" name="logout-button">
                <div class="dashboard-item" id="logout">
                    <img src="images/logout-blue.png" alt="logout" style="width: 24px; height: 24px; margin-right: 8px;" class="icon">
                    Log-out
                </div>
            </button>
        </form>
    </div>
</div>    
</body>
</html>
<?php
    if(isset($_POST['logout-button'])) {
        session_destroy();
        header("Location: login.php");
        exit();
    }
?>