<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="navbar.css">
    <title>Admin Homepage</title>

</head>
<body>
    <div class="dashboard">
        <div class="dashboard-header" id="admin">
            <img src="images/user-blue.png" alt="user icon" style="width: 40px; height: 40px; margin-right: 12px;" class="icon">
            <h3>Admin</h3>
            Username: <?php echo $_SESSION['username'];?>
        </div>
        <br>
        <a href="admin-overview.php">
            <div class="dashboard-item" id="overview">
                <img src="images/overview-blue.png" alt="overview" style="width: 28px; height: 28px; margin-right: 4px;" class="icon">
                Overview
            </div>
        </a>
        <a href="admin-profile.php">
            <div class="dashboard-item" id="profile">
                <img src="images/user-blue.png" alt="user" style="width: 24px; height: 24px; margin-right: 8px;" class="icon">
                Profile
            </div>
        </a>
        <a href="admin-units.php">
            <div class="dashboard-item" id="manage-units">
                <img src="images/logo-blue.png" alt="manage units" style="width: 24px; height: 24px; margin-right: 8px;" class="icon">
                Manage Units
            </div>
        </a>
        <a href="admin-tenants.php">
            <div class="dashboard-item" id="manage-tenants">
                <img src="images/user-blue.png" alt="manage tenants" style="width: 24px; height: 24px; margin-right: 8px;" class="icon">
                Manage Tenants
            </div>
        </a>
        <a href="admin-requests.php">
            <div class="dashboard-item" id="view-requests">
                <img src="images/request-blue.png" alt="request" style="width: 24px; height: 24px; margin-right: 8px;" class="icon">
                View Requests
            </div>
        </a>
        <a href="admin-announcements.php">
            <div class="dashboard-item" id="send-announcements">
                <img src="images/announcement-blue.png" alt="announcements" style="width: 24px; height: 24px; margin-right: 8px;" class="icon">
                Send Announcements
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
</body>
</html>
<?php
    if(isset($_POST['logout-button'])) {
        session_destroy();
        header("Location: login.php");
        exit();
    }
?>