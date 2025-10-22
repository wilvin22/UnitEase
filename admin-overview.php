<!-- 
 MGA KULANG: 
- search bar (optional)

-->


<?php
session_start();
include 'database.php';

$message = "";
$message_type = "";

//creating a unit
if (isset($_POST['create-unit-button'])) {
    $unit_name = $_POST['unit-name'];
    $unit_capacity = $_POST['unit-capacity'];
    $unit_floor = $_POST['unit-floor'];

    //this query selects the entered unit name within the same admin account
    $admin_id = $_SESSION['admin_id'];
    $check_sql = "SELECT * FROM units WHERE unit_name = '$unit_name' AND admin_id = '$admin_id'";
    $check_result = mysqli_query($conn, $check_sql);

    //if it already exists:
    if (mysqli_num_rows($check_result) > 0) {
        $message = "❌ A unit with this name already exists.";
        $message_type = "error";
    }
    //if it doesn't:
    else {
        $sql = "INSERT INTO units (unit_id, unit_name, status, capacity, unit_floor, admin_id) 
                VALUES (NULL, '$unit_name', 'Available', '$unit_capacity', '$unit_floor', '$admin_id')";

        if (mysqli_query($conn, $sql)) {
            $message = "✅ Unit created successfully!";
            $message_type = "success";
        } else {
            $message = "❌ Unit could not be created.";
            $message_type = "error";
        }
    }
}

//assigning a tenant
if (isset($_POST['assign-tenant-button'])) {
    $unit_name = $_POST['unit-name'];
    $tenant_username = $_POST['tenant-username'];

    //checks if the entered username of the tenant from the user exists:
    $get_tenant = "SELECT tenant_id FROM tenant_accounts WHERE username = '$tenant_username'";
    $tenant_result = mysqli_query($conn, $get_tenant);

    //if it doesn't:
    if (mysqli_num_rows($tenant_result) <= 0) {
        $message = "❌ Tenant does not exist.";
        $message_type = "error";
    }
    //if it does:
    else {
        $tenant_row = mysqli_fetch_assoc($tenant_result);
        $tenant_id = $tenant_row['tenant_id'];

        //checks if the entered unit name from the user exists:
        $get_unit_data = "SELECT unit_id, unit_name, capacity FROM units WHERE unit_name = '$unit_name'";
        $unit_result = mysqli_query($conn, $get_unit_data);

        //if it doesn't:
        if (mysqli_num_rows($unit_result) <= 0) {
            $message = "❌ Unit does not exist.";
            $message_type = "error";
        }
        //if it does:
        //at this point, both the tenant and the unit exists
        else {
            $unit_row = mysqli_fetch_assoc($unit_result);
            $unit_id = $unit_row['unit_id'];
            $capacity = $unit_row['capacity'];

            //checks the occupancy of the unit from the table `tenant_units`
            $count_query = "SELECT COUNT(*) AS occupancy FROM tenant_units WHERE unit_id = '$unit_id'";
            $count_result = mysqli_query($conn, $count_query);
            $count_row = mysqli_fetch_assoc($count_result);
            $current_occupancy = $count_row['occupancy'];

            //checks if the tenant is already assigned to the same unit
            $check_tenant = "SELECT * FROM tenant_units WHERE tenant_id = '$tenant_id'";
            $check_tenant_result = mysqli_query($conn, $check_tenant);
            $check_tenant_row = mysqli_num_rows($check_tenant_result);

            //if it does:
            if ($check_tenant_row > 0) {

                $sql = "SELECT unit_id FROM tenant_units WHERE tenant_id = '$tenant_id'";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
                $existing_unit_id = $row['unit_id'];

                $stmt = "SELECT unit_name FROM units WHERE unit_id = '$existing_unit_id'";
                $stmt_result = mysqli_query($conn, $stmt);
                $stmt_row = mysqli_fetch_assoc($stmt_result);
                $unit_name = $stmt_row['unit_name'];

                $message = "❌ Tenant is already assigned to unit " . $unit_name;
                $message_type = "error";
            }

            //if the unit is not full, it will insert the tenant
            else if ($current_occupancy < $capacity) {
                $sql = "INSERT INTO tenant_units (unit_id, tenant_id) VALUES ('$unit_id', '$tenant_id')";
                if (!mysqli_query($conn, $sql)) {
                    $message = "❌ Error assigning tenant.";
                    $message_type = "error";
                } else {
                    $message = "✅ Tenant assigned successfully.";
                    $message_type = "success";
                }
            }
            //if it is:
            else {
                $message = "❌ Unit is already full.";
                $message_type = "error";
            }
        }
    }
}


if (isset($_POST['remove-tenant-button'])) {
    $unit_name = $_POST['unit-name'];
    $tenant_fullname = $_POST['tenant-fullname'];

    $get_tenant = "SELECT tenant_id FROM tenant_accounts WHERE full_name = '$tenant_fullname'";
    $tenant_result = mysqli_query($conn, $get_tenant);

    if (mysqli_num_rows($tenant_result) <= 0) {
        $message = "❌ Tenant does not exist.";
        $message_type = "error";
    } 
    //if it does:
    else {
        $tenant_row = mysqli_fetch_assoc($tenant_result);
        $tenant_id = $tenant_row['tenant_id'];

        $get_unit = "SELECT unit_id, capacity FROM units WHERE unit_name = '$unit_name'";
        $unit_result = mysqli_query($conn, $get_unit);

        //checks if the unit exists
        //if it doesn't:
        if (mysqli_num_rows($unit_result) <= 0) {
            $message = "❌ Unit does not exist.";
            $message_type = "error";
        } else {
            $unit_row = mysqli_fetch_assoc($unit_result);
            $unit_id = $unit_row['unit_id'];

            $sql = "DELETE FROM tenant_units WHERE `tenant_id` = '$tenant_id' AND `unit_id` = '$unit_id'";
            if (mysqli_query($conn, $sql)) {
                $message = "✅ Tenant has been removed successfully.";
                $message_type = "success";
            } else {
                $message = "❌ Could not remove tenant.";
                $message_type = "error";
            }
        }
    }
}

if (isset($_POST['edit-unit-button']) && !empty($_POST['select_unit'])) {
    $unit_id = $_POST['select_unit'][0];

    $edit_name = trim($_POST['edit-unit-name']);
    $edit_capacity = trim($_POST['edit-unit-capacity']);
    $edit_floor = trim($_POST['edit-unit-floor']);

    $updates = [];
    if (!empty($edit_name)) $updates[] = "unit_name = '$edit_name'";
    if (!empty($edit_capacity)) $updates[] = "capacity = '$edit_capacity'";
    if (!empty($edit_floor)) $updates[] = "unit_floor = '$edit_floor'";

    if (!empty($updates)) {
        $count_query = "SELECT COUNT(*) AS occupancy FROM tenant_units WHERE unit_id = '$unit_id'";
        $count_result = mysqli_query($conn, $count_query);
        $count_row = mysqli_fetch_assoc($count_result);
        $current_occupancy = $count_row['occupancy'];

        if (!empty($edit_capacity) && $edit_capacity < $current_occupancy) {
            $message = "❌ Capacity could not be smaller than current occupancy.";
            $message_type = "error";
        } else {
            $update_sql = "UPDATE units SET " . implode(", ", $updates) . " WHERE unit_id = '$unit_id'";
            if (mysqli_query($conn, $update_sql)) {
                $message = "✅ Unit updated successfully!";
                $message_type = "success";
            } else {
                $message = "❌ Failed to update unit.";
                $message_type = "error";
            }
        }
    } else {
        $message = "⚠️ No changes were made.";
        $message_type = "error";
    }
}


if (isset($_POST['delete-button']) && !empty($_POST['select_unit'])) {
    $ids = implode(',', array_map('intval', $_POST['select_unit']));
    $delete_sql = "DELETE FROM units WHERE unit_id IN ($ids)";
    try {
        mysqli_query($conn, $delete_sql);
    } catch (mysqli_sql_exception) {
        echo "Could not delete units.";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<?php
if (isset($_SESSION['message'])) {
    echo "<script>alert('" . $_SESSION['message'] . "');</script>";
    unset($_SESSION['message']);
}
?>

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

        .menu-icon:hover {
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
            margin-bottom: 5vh;
        }

        .add-unit {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            border-radius: 8px;
            min-width: 300px;
            text-align: center;
        }

        .add-unit.active {
            display: block;
        }

        .add-unit-content input {
            height: 40px;
            width: 100%;
            font-size: 15px;
            outline: none;
        }

        #add-unit-close {
            display: flex;
            align-items: center;
            width: 40%;
        }

        #add-unit-close:hover {
            background-color: #d4d4d4ff;
            cursor: pointer;
        }

        .assign-tenant {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            border-radius: 8px;
            min-width: 300px;
            text-align: center;
        }

        .assign-tenant.active {
            display: block;
        }

        .assign-tenant-content input {
            height: 40px;
            width: 100%;
            font-size: 15px;
            outline: none;
        }

        #assign-tenant-close {
            display: flex;
            align-items: center;
            width: 40%;
        }

        #assign-tenant-close:hover {
            background-color: #d4d4d4ff;
            cursor: pointer;
        }

        .remove-tenant {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            border-radius: 8px;
            min-width: 300px;
            text-align: center;
        }

        .remove-tenant.active {
            display: block;
        }

        .remove-tenant-content input {
            height: 40px;
            width: 100%;
            font-size: 15px;
            outline: none;
        }

        #remove-tenant-close {
            display: flex;
            align-items: center;
            width: 40%;
        }

        #remove-tenant-close:hover {
            background-color: #d4d4d4ff;
            cursor: pointer;
        }

        .edit-unit {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            border-radius: 8px;
            min-width: 300px;
            text-align: center;
        }

        .edit-unit.active {
            display: block;
        }

        .edit-unit-content input {
            height: 40px;
            width: 100%;
            font-size: 15px;
            outline: none;
        }

        #edit-unit-close {
            display: flex;
            align-items: center;
            width: 40%;
        }

        #edit-unit-close:hover {
            background-color: #d4d4d4ff;
            cursor: pointer;
        }

        tr {
            height: 30px;
        }

        .menu-icon,
        .close-icon {
            width: 30px;
            height: 30px;
            margin: 10px;
        }

        #edit-unit-status {
            width: 100%;
            height: 40px;
            text-align: center;
            outline: none;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="navbar.css">
    <title>Manage Units</title>
</head>

<body>
    <div style="background-color: #393D3F;">
        <img onclick=Sidebar() src="images/hamburger-blue.png" alt="menu" class="menu-icon">
    </div>
    <div class="main-container">
        <div class="dashboard">
            <a href="admin-overview.php">
                <div class="dashboard-item 1">
                    <img src="images/overview-blue.png" alt="menu" class="menu-icon">
                    Overview
                </div>
            </a>

            <a href="admin-profile.php">
                <div class="dashboard-item 2">
                    <img src="images/user-blue.png" alt="menu" class="menu-icon">
                    Profile
                </div>
            </a>

            <a href="login.php">
                <div class="dashboard-item 7">
                    <img src="images/logout-blue.png" alt="menu" class="menu-icon">
                    Log-out
                </div>
            </a>
        </div>

        <div class="add-unit">
            <div onclick=addUnit() id="add-unit-close">
                <img src="images/close-blue.png" alt="close" class="close-icon">
                Close
            </div>
            <br>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="add-unit-content">
                    <label for="unit-name">Add New Unit</label>
                    <br><br>
                    <input type="text" name="unit-name" id="unit-name" placeholder="Unit Name" required></div>
                <br>
                <div class="add-unit-content"><input type="text" name="unit-capacity" id="unit-capacity" placeholder="Unit Capacity" required></div>
                <br>
                <div class="add-unit-content"><input type="text" name="unit-floor" id="unit-floor" placeholder="Unit Floor" required></div>
                <br>
                <div class="add-unit-content"><input type="submit" value="Create Unit" id="create-unit-button" name="create-unit-button"></div>
            </form>
        </div>

        <div class="assign-tenant">
            <div onclick=assignTenant() id="assign-tenant-close">
                <img src="images/close-blue.png" alt="close" id="close-icon" style="width:30px; height:30px; margin:10px;">
                Close
            </div>
            <br>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="assign-tenant-content">
                    <label for="tenant-username">Assign a Tenant</label>
                    <br><br>
                    <input type="text" name="tenant-username" id="tenant-username" placeholder="Tenant Username" required></div>
                <br>
                <div class="assign-tenant-content">
                    <input type="text" name="unit-name" id="unit-name" placeholder="Unit Name" required></div>
                <br>
                <div class="assign-tenant-content"><input type="submit" value="Assign Tenant" id="assign-tenant-button" name="assign-tenant-button"></div>
                <br>
            </form>
        </div>

        <div class="remove-tenant">
            <div onclick=removeTenant() id="remove-tenant-close">
                <img src="images/close-blue.png" alt="close" id="close-icon" style="width:30px; height:30px; margin:10px;">
                Close
            </div>
            <br>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="remove-tenant-content"><input type="text" name="tenant-fullname" id="tenant-fullname" placeholder="Tenant Full Name" required></div>
                <br>
                <div class="remove-tenant-content"><input type="text" name="unit-name" id="unit-name" placeholder="Unit Name" required></div>
                <br>
                <div class="remove-tenant-content"><input type="submit" value="Remove Tenant" id="remove-tenant-button" name="remove-tenant-button"></div>
                <br>
            </form>
        </div>

        <div class="edit-unit">
            <div onclick=editUnit() id="edit-unit-close">
                <img src="images/close-blue.png" alt="close" id="close-icon" style="width:30px; height:30px; margin:10px;">
                Close
            </div>
            <br>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="hidden" name="select_unit[]" id="edit-unit-array">
                <div class="edit-unit-content">
                    <input type="text" name="edit-unit-name" id="edit-unit-name" placeholder="Edit Unit Name (Leave empty to ignore)">
                </div>
                <br>
                <div class="edit-unit-content">
                    <input type="text" name="edit-unit-capacity" id="edit-unit-capacity" placeholder="Edit Unit Capacity (Leave empty to ignore)">
                </div>
                <br>
                <div class="edit-unit-content">
                    <input type="text" name="edit-unit-floor" id="edit-unit-floor" placeholder="Edit Unit Floor (Leave empty to ignore)">
                </div>
                <br>
                <div class="edit-unit-content">
                    <input type="submit" value="Edit Unit" id="edit-unit-button" name="edit-unit-button">
                </div>
                <br>
            </form>
        </div>



        <div class="main-content" style="padding:20px;">
            <div class="cards item-1">
                <h1><?php
                    $sql = "SELECT * FROM units";
                    $result = mysqli_query($conn, $sql);
                    echo ($result) ? mysqli_num_rows($result) : 0;
                    ?></h1>
                <br>
                Total Units
            </div>


            <div class="cards item-2">
                <h1>
                    <?php
                    $sql = "SELECT * FROM units WHERE status = 'Available'";
                    $result = mysqli_query($conn, $sql);
                    echo ($result) ? mysqli_num_rows($result) : 0;
                    ?></h1>
                <br>
                Available Units

            </div>
            <div class="cards item-3">
                <h1>
                    <?php
                    $admin_id = $_SESSION['admin_id'];

                    $sql = "SELECT COUNT(DISTINCT tu.tenant_id) AS tenant_count
                            FROM tenant_units tu
                            JOIN units u ON tu.unit_id = u.unit_id
                            WHERE u.admin_id = '$admin_id'";

                    $result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($result);

                    echo $row['tenant_count'];
                    ?>
                </h1>
                <br>
                Total Tenants
            </div>
            <div class="cards item-4">
                <div style="width: 100%; display:flex; justify-content:start;">
                    <h1>Overview</h1>
                </div>
                <br>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="width: 100%; display:flex; flex-direction:column; align-items:start;">
                    <div style="width: 100%; display:flex; justify-content:start;">
                        
                        <?php if (!empty($message)) : ?>
                            <span style="color: <?php echo ($message_type == 'success') ? 'green' : 'red'; ?>; margin-left:auto;"><?php echo $message ?></span>

                        <?php endif; ?>

                    </div>
                    <br>
                    <div style="width: 100%; display:flex; justify-content:start; padding:20px; box-sizing:border-box;">
                        <input onclick=addUnit() type="button" name="add-unit-button" value="Add New Unit" id="add-button" style="width: 150px; height: 35px; margin-bottom:10px; margin-right: 20px; border:none; background-color:#27AE60; color:white; border-radius:3px; cursor:pointer;">
                        <input onclick=assignTenant() type="button" name="assign-tenant" value="Assign a Tenant" id="assign-tenant" style="width: 150px; height: 35px; margin-bottom:10px; margin-right: 20px; border:none; background-color:#27AE60; color:white; border-radius:3px; cursor:pointer;">
                        <input onclick=removeTenant() type="button" name="remove-tenant" value="Remove a Tenant" id="remove-tenant" style="width: 150px; height: 35px; margin-bottom:10px; margin-right: 20px; border:none; background-color:#E74C3C; color:white; border-radius:3px; cursor:pointer;">
                        <input onclick=editUnit() type="button" name="edit-button" value="Edit Selected Unit" id="edit-button" style="width: 150px; height: 35px; margin-bottom:10px; margin-right: 20px; border:none; background-color:#F39C12; color:white; border-radius:3px; cursor:pointer;">
                        <input onclick=sendAnnounncement() type="button" name="send-announcement" value="Send Announcement" id="send-announcement" style="width: 150px; height: 35px; margin-bottom:10px; margin-right: 20px; border:none; background-color:#27AE60; color:white; border-radius:3px; cursor:pointer;">
                        <input onclick=viewRequests() type="button" name="view-requests" value="View Requests" id="view-requests" style="width: 150px; height: 35px; margin-bottom:10px; margin-right: 20px; border:none; background-color:#27AE60; color:white; border-radius:3px; cursor:pointer;">
                        <input type="submit" name="delete-button" value="Delete Selected Units" id="delete-button" style="width: 200px; height: 35px; margin-bottom:10px; border:none; background-color:#E74C3C; color:white; border-radius:3px; cursor:pointer;">
                        <input type="submit" name="refresh-button" value="Refresh" id="refresh-button" style="width: 100px; height: 35px; margin-left:auto; border:none; background-color:#62929E; color:white; border-radius:3px; cursor:pointer;">
                    </div>
                    <br>
                    <div class="table-container" style="height: 100%; display:flex; width:100%; overflow-y:auto; justify-content:center; align-items:center;">
                        <?php
                        $sql = "SELECT 
                                    u.unit_id,
                                    u.unit_name,
                                    u.capacity,
                                    u.unit_floor,
                                    u.status,
                                    GROUP_CONCAT(t.full_name SEPARATOR ', ') AS tenants
                                FROM units u
                                LEFT JOIN tenant_units tu ON u.unit_id = tu.unit_id
                                LEFT JOIN tenant_accounts t ON tu.tenant_id = t.tenant_id
                                WHERE u.admin_id = '$admin_id'
                                GROUP BY u.unit_id, u.unit_name, u.capacity, u.unit_floor, u.status";
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
                                        <th>Occupancy</th>
                                        <th>Tenant</th>
                                    </tr>";

                            while ($row = mysqli_fetch_assoc($result)) {
                                $unit_id = $row['unit_id'];

                                $count_query = "SELECT COUNT(*) AS occupancy FROM tenant_units WHERE unit_id = '$unit_id'";
                                $count_result = mysqli_query($conn, $count_query);
                                $count_row = mysqli_fetch_assoc($count_result);
                                $current_occupancy = $count_row['occupancy'];

                                if ($current_occupancy >= $row['capacity']) {
                                    $status = 'Full';
                                    $update_status = "UPDATE units SET status = 'Full' WHERE unit_id = '$unit_id'";
                                    mysqli_query($conn, $update_status);
                                } else {
                                    $status = 'Available';
                                    $update_status = "UPDATE units SET status = 'Available' WHERE unit_id = '$unit_id'";
                                    mysqli_query($conn, $update_status);
                                }

                                if ($status == 'Full') {
                                    $status_bg = '#f99';
                                } else {
                                    $status_bg = '#A1D998';
                                }

                                echo "<tr>
                                        <td><input type='checkbox' name='select_unit[]' value='" . $row['unit_id'] . "'></td>
                                        <td>" . $row['unit_name'] . "</td>
                                        <td>" . $row['capacity'] . "</td>
                                        <td>" . $row['unit_floor'] . "</td>
                                        <td style='background-color: $status_bg;'>" . $status . "</td>
                                        <td>" . $current_occupancy . "/" .  $row['capacity'] . "</td>
                                        <td>" . ($row['tenants'] ? $row['tenants'] : '—') . "</td>
                                    </tr>";
                            }

                            echo "</table>
                                <br>";
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

        function addUnit() {
            document.querySelector('.add-unit').classList.toggle('active');
        }

        function assignTenant() {
            document.querySelector('.assign-tenant').classList.toggle('active');
        }

        function removeTenant() {
            document.querySelector('.remove-tenant').classList.toggle('active');
        }

        function editUnit() {
            var checkboxes = document.getElementsByName("select_unit[]");
            var selected = null;
            var count = 0;

            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    selected = checkboxes[i].value;
                    count++;
                }
            }
            if (count == 0) {
                alert("Please select a unit to edit.");
            } else if (count > 1) {
                alert("Please select only one (1) unit to edit.");
            } else {
                document.getElementById('edit-unit-array').value = selected;
                document.querySelector('.edit-unit').classList.toggle('active');
            }
        }
    </script>
</body>

</html>
<?php


?>