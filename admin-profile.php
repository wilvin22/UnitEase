<?php
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            padding: 0;
            background-color: #e4e4e6ff;
        }

        .dashboard {
            height: 100vh;
            width: min(300px, 40%);
            background-color: white;
        }

        .dashboard-item {
            padding: 15px;
            display: flex;
            align-items: center;
            font-size: 18px;
            color: #62929E;
            cursor: pointer;
        }

        .dashboard-item:hover {
            background-color: #f1f1f1ff;
        }

        a {
            text-decoration: none;
        }

        #menu-icon:hover {
            cursor: pointer;
        }

        @media (max-width:767px) {
            .dashboard {
                display: none;
                width: 100%;
            }
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div style="background-color: #393D3F;">
        <img onclick=Sidebar() src="images/hamburger-blue.png" alt="menu" id="menu-icon" style="width:30px; height:30px; margin:10px;">
    </div>

    <div class="dashboard">
        <a href="admin-overview.php">
            <div class="dashboard-item 1">
                <img src="images/overview-blue.png" alt="menu" id="menu-icon" style="width:30px; height:30px; margin:10px;">
                Overview
            </div>
        </a>

        <a href="admin-profile.php">
            <div class="dashboard-item 2">
                <img src="images/user-blue.png" alt="menu" id="menu-icon" style="width:30px; height:30px; margin:10px;">
                Profile
            </div>
        </a>

        <a href="admin-units.php">
            <div class="dashboard-item 3">
                <img src="images/logo-blue.png" alt="menu" id="menu-icon" style="width:30px; height:30px; margin:10px;">
                Manage Units
            </div>
        </a>

        <a href="admin-tenants.php">
            <div class="dashboard-item 4">
                <img src="images/user-blue.png" alt="menu" id="menu-icon" style="width:30px; height:30px; margin:10px;">
                Manage Tenants
            </div>
        </a>

        <a href="admin-requests.php">
            <div class="dashboard-item 5">
                <img src="images/request-blue.png" alt="menu" id="menu-icon" style="width:30px; height:30px; margin:10px;">
                View Requests
            </div>
        </a>

        <a href="admin-announcements.php">
            <div class="dashboard-item 6">
                <img src="images/announcement-blue.png" alt="menu" id="menu-icon" style="width:30px; height:30px; margin:10px;">
                Send an Announcement
            </div>
        </a>

        <a href="login.php">
            <div class="dashboard-item 7">
                <img src="images/logout-blue.png" alt="menu" id="menu-icon" style="width:30px; height:30px; margin:10px;">
                Log-out
            </div>
        </a>
    </div>
    <script>
        function Sidebar() {
            const dashboard = document.querySelector('.dashboard');
            if (dashboard.style.display === 'block') {
                dashboard.style.display = 'none';
            } else {
                dashboard.style.display = 'block';
            }
        }
    </script>
</body>
</html>