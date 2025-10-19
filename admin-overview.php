<?php
session_start();
include 'database.php';
?> 
<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        body {
            margin: 0;
            font-family: Inter-Regular, Arial, sans-serif;
            padding: 0;
            background-color: #e4e4e6ff;
            overflow-y: hidden;

        }

        .main-container {
            display: flex;
            height: 90vh;
            width: 100%;
        }

        .dashboard {
            height: 100vh;
            width: 300px;
            background-color: #fdfdff3f;
            display: none;
            position: absolute;
            backdrop-filter: blur(5px);
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            z-index: 10;
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

        @media (max-width:768px) {
            .dashboard {
                width: 100%;
            }
        }

        .main-content {
            background-color: #f7f7f8ff;
            height: 100%;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            overflow-y: scroll;
        }

        .cards {
            background-color: white;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 40px;
            margin: 10px;
            box-sizing: border-box;
            text-align: center;
            font-size: 18px;
            color: #333;
            display: flex;
            align-items: start;

        }

        .item-1,
        .item-2,
        .item-3 {
            width: max(400px, 30%);
            height: 200px;
            display: flex;
            justify-content: center;
            align-items: start;
            flex-direction: column;
        }

        .item-4 {
            width: 100%;
            height: auto;
            display: flex;
            flex-direction: column;
            overflow-y: scroll;
        }

        .right-sidebar {
            height: auto;
            width: 400px;
            background-color: #00fcb0ff;
            position: absolute;
            backdrop-filter: blur(5px);
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            z-index: 10;
            right: 0;

        }

        .right-sb-content input {
            height: 40px;
            width: 100%;
            font-size: 15px;
            border: 1px solid black;
        }

        #right-sidebar-close {
            display: flex;
            align-items: center;
            width: 30%;
        }

        #right-sidebar-close:hover {
            background-color: #333;
            cursor: pointer;
        }
        tr{
            height:30px;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="navbar.css">
    <title>Manage Units</title>
</head>

<body>
    <div style="background-color: #393D3F;">
        <img onclick=Sidebar() src="images/hamburger-blue.png" alt="menu" id="menu-icon" style="width:30px; height:30px; margin:10px;">
    </div>
    <div class="main-container">
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

            <a href="login.php">
                <div class="dashboard-item 7">
                    <img src="images/logout-blue.png" alt="menu" id="menu-icon" style="width:30px; height:30px; margin:10px;">
                    Log-out
                </div>
            </a>
        </div>

        <div class="right-sidebar">
            <div id="right-sidebar-close">
                <img src="images/close-blue.png" alt="close" id="close-icon" style="width:30px; height:30px; margin:10px;">
                Close
            </div>
            <br>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="right-sb-content"><input type="text" name="unit-name" id="unit-name" placeholder="Unit Name" required></div>
                <br>
                <div class="right-sb-content"><input type="text" name="unit-capacity" id="unit-capacity" placeholder="Unit Capacity" required></div>
                <br>
                <div class="right-sb-content"><input type="text" name="unit-floor" id="unit-floor" placeholder="Unit Floor" required></div>
                <br>
                <div class="right-sb-content"><input type="submit" value="Create Unit" id="create-unit-button" name="create-unit-button"></div>
            </form>
            <?php
                if (isset($_POST['create-unit-button'])) {
                    $unit_name = $_POST['unit-name'];
                    $unit_capacity = $_POST['unit-capacity'];
                    $unit_floor = $_POST['unit-floor'];
                    $username = $_SESSION['username'];

                    $get_admin_id = "SELECT admin_id FROM admin_accounts WHERE username = '$username'";
                    try{
                    $result = mysqli_query($conn, $get_admin_id);
                    }
                    catch(mysqli_sql_exception){
                        echo "Could not create unit into your account.";
                    }
                    $row = mysqli_fetch_assoc($result);
                    $admin_id = $row['admin_id'];

                    $sql = "INSERT INTO units (unit_id, unit_name, status, capacity, unit_floor, admin_id, tenant_id) 
                            VALUES (NULL, '$unit_name', 'Available', '$unit_capacity', '$unit_floor', '$admin_id', NULL)";

                    try{
                        mysqli_query($conn, $sql);
                        
                    }
                    catch(mysqli_sql_exception){
                        echo "error with sql statement";
                    }
                }
            ?>
        </div>

        <div class="main-content" style="padding:20px;">
            <div class="cards item-1">
                <h1>0</h1>
                <br>
                Total Units
            </div>


            <div class="cards item-2">
                <h1>0</h1>
                <br>
                Occupied Units

            </div>
            <div class="cards item-3">
                <h1>0</h1>
                <br>
                Total Tenants
            </div>
            <div class="cards item-4">
                <div style="width: 100%; display:flex; justify-content:start;">
                    <h1>Overview</h1>
                </div>
                <br>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" style="width: 100%; display:flex; flex-direction:column; align-items:start;">
                    <div style="width: 100%; display:flex; justify-content:start;">
                        <input type="text" name="search" id="search" placeholder="Search Units" class="form-content" style="width: 300px; height: 35px; border: 1px solid #ccc; border-radius: 3px; margin-bottom: 10px; padding-left:20px; outline:none;">
                        <input type="submit" name="search-button" value="Search" id="button" style="width: 100px; height: 35px; margin-left: 10px; border:none; background-color:#62929E; color:white; border-radius:3px; cursor:pointer;">
                    </div>
                    <br>
                    <div style="width: 100%; display:flex; justify-content:start; padding:20px; box-sizing:border-box;">
                        <input type="submit" name="add-unit-button" value="Add New Unit" id="add-button" style="width: 150px; height: 35px; margin-bottom:10px; margin-right: 20px; border:none; background-color:#27AE60; color:white; border-radius:3px; cursor:pointer;">
                        <input type="submit" name="assign-tenant" value="Assign a Tenant" id="assign-tenant" style="width: 150px; height: 35px; margin-bottom:10px; margin-right: 20px; border:none; background-color:#27AE60; color:white; border-radius:3px; cursor:pointer;">
                        <input type="submit" name="edit-button" value="Edit Selected Unit" id="edit-button" style="width: 150px; height: 35px; margin-bottom:10px; margin-right: 20px; border:none; background-color:#F39C12; color:white; border-radius:3px; cursor:pointer;">
                        <input type="submit" name="delete-button" value="Delete Selected Units" id="delete-button" style="width: 200px; height: 35px; margin-bottom:10px; border:none; background-color:#E74C3C; color:white; border-radius:3px; cursor:pointer;">
                        <input type="submit" name="refresh-button" value="Refresh" id="refresh-button" style="width: 100px; height: 35px; margin-left:auto; border:none; background-color:#62929E; color:white; border-radius:3px; cursor:pointer;">
                    </div>
                <br>
                <div class="table-container" style="height: 100%; display:flex; width:100%; overflow-y:auto; justify-content:center; align-items:center;">
                    <?php
                        $sql = "SELECT * FROM `units`";
                        $result = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($result) <= 0) {
                            echo "Looks like you don't have any units added yet. Please add units to manage them here.";
                        } else {
                            echo "
                                <table border='1' style='border-collapse: collapse; width: 90%; text-align: center;'>
                                    <tr style='background-color: #62929E; color: white;'>
                                        <th>Select</th>                                   
                                        <th>Unit Name</th>
                                        <th>Capacity</th>
                                        <th>Unit Floor</th>
                                        <th>Status</th>
                                    </tr>";

                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>
                                        <td><input type='checkbox' name='select_unit[]' value='" . $row['unit_id'] . "'></td>
                                        <td>" . $row['unit_name'] . "</td>
                                        <td>" . $row['capacity'] . "</td>
                                        <td>" . $row['unit_floor'] . "</td>
                                        <td>" . $row['status'] . "</td>
                                    </tr>";
                            }

                            echo "</table>
                                <br>";
                                   
                        }
                        if (isset($_POST['delete-button']) && !empty($_POST['select_unit'])) {
                            $selected_units = $_POST['select_unit'];
                        
                            $ids = implode(',', array_map('intval', $selected_units));
                            $delete_sql = "DELETE FROM units WHERE unit_id IN ($ids)";
                            mysqli_query($conn, $delete_sql);

                            echo "<script>window.location.href = window.location.href;</script>";
                        }
                        ?>


                </div>
            </div>
        </div>
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

        function addOrEditUnit() {

        }
    </script>
</body>

</html>
<?php


?>