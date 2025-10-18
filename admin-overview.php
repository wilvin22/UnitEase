<?php
include 'navbar.html';
include 'database.php';
include 'admin-dashboard.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="admin-overview.css">
    <title>Admin Homepage</title>

</head>
<body>
    <div id="main-content">
        <div id="main-content-header">Dashboard</div>
            <div class="card-container">
                <div class="top-card-container"> 
                <div class="cards status">Status</div>
                <div class="cards total-units">Total Units</div>
                <div class="cards total-tenants">Total Tenants</div>
                </div>
                
                <div class="bottom-card-container">
                <div class="cards table">Unit Management</div>
                <div class="cards announcements">Announcements</div>
                </div>
            </div>
    </div>    
</body>
</html>
