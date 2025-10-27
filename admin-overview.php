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

    if(!is_numeric($unit_capacity) || $unit_capacity <= 0){
        $message = "❌ Invalid unit capacity.";
        $message_type = "error";
    }
    else{
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
        }
        else {
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

if (isset($_POST['confirm-delete-button']) && !empty($_POST['select_unit'])) {
    $selected_units = explode(',', $_POST['select_unit']);
    $ids = implode(',', array_map('intval', $selected_units));

    $delete_sql = "DELETE FROM units WHERE unit_id IN ($ids)";
    if (mysqli_query($conn, $delete_sql)) {
        $message = "✅ Selected unit(s) deleted successfully.";
        $message_type = "success";
    } else {
        $message = "❌ Could not delete unit(s).";
        $message_type = "error";
    }
}

if (isset($_POST['edit-account-button']) && !empty($_POST['select_unit'])) {
    $admin_id = $_SESSION['admin_id'];

    $edit_username = trim($_POST['edit-username']);
    $edit_password = trim($_POST['edit-password']);
    $edit_password_confirm = trim($_POST['edit-password-confirm']);
    $edit_fullname = trim($_POST['edit-fullname']);
    $edit_email = trim($_POST['edit-email']);
    $edit_phone = trim($_POST['edit-phone']);

    $updates = [];
    if (!empty($edit_username)) $updates[] = "username = '$edit_username'";
    if (!empty($edit_password)) $updates[] = "password = '$edit_password'";
    if (!empty($edit_fullname)) $updates[] = "full_name = '$edit_fullname'";
    if (!empty($edit_email)) $updates[] = "email = '$edit_email'";
    if (!empty($edit_phone)) $updates[] = "phone_number = '$edit_phone'";

    if($edit_password != $edit_password_confirm){
        $message = "❌ Passwords don't match.";
        $message_type = "error";
    }

    else{
        $update_sql = "UPDATE admin_accounts SET " . implode(", ", $updates) . " WHERE admin_id = '$admin_id'";
        try{
            mysqli_query($conn, $update_sql); 
            $message = "✅ Account updated successfully!";
            $message_type = "success";
        } catch(mysqli_sql_exception) {
            $message = "❌ Failed to update account.";
            $message_type = "error";
        }
    }
    if(empty($updates)){
        $message = "⚠ No changes were made.";
        $message_type = "error";
    }
}

if (isset($_POST['send-announcement-button']) && !empty($_POST['select_unit'])) {
    $admin_id = $_SESSION['admin_id'];
    $subject = $_POST['message-subject'];
    $message = $_POST['message-content'];

    $units = $_POST['select_unit'];

    foreach ($units as $unit_id) {
        $sql = "INSERT INTO announcements (admin_id, subject, message, unit_id)
                VALUES ('$admin_id', '$subject', '$message', '$unit_id')";
        mysqli_query($conn, $sql);
    }

    $message = "✅ Announcement sent to selected units.";
    $message_type = "success";
}
else if(isset($_POST['send-all-announcement-button'])){
    $admin_id = $_SESSION['admin_id'];
    $subject = $_POST['message-subject'];
    $announcement_message = $_POST['message-content'];
    
    $units = mysqli_query($conn, "SELECT unit_id FROM units");
    while($row = mysqli_fetch_assoc($units)){
        $unit_id = $row['unit_id'];
        $sql = "INSERT INTO announcements (admin_id, subject, message, unit_id)
                VALUES ('$admin_id', '$subject', '$announcement_message', '$unit_id')";
        mysqli_query($conn, $sql);
    }
    $message = "✅ Announcement has been sent to all units.";
    $message_type = "success";
}

if(isset($_POST['logout-button'])){
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        body {
            margin: 0;
            font-family: Inter-Regular, Arial, sans-serif;
            padding: 0;
            overflow-y: hidden;
            background-color: #f7f7f8ff;
        }

        .main-container {
            display: flex;
            height: 90vh;
            width: 100%;
        }

        a {
            text-decoration: none;
            color: white;
        }
        #down-arrow{
            display:none;
        }
        @media (max-width:768px) {
            #admin-account{
                display: none;
                
            }
            #down-arrow{
                display:block;
            }
        }

        .main-content {
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
            padding: 20px;
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
        .item-3,
        .item-4 {
            width: max(250px, 20%);
            height: 200px;
            display: flex;
            justify-content: center;
            align-items: start;
            flex-direction: column;
        }

        .item-5 {
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

        .delete-unit {
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

        .delete-unit.active {
            display: block;
        }

        .delete-unit-content input {
            height: 40px;
            width: 100%;
            font-size: 15px;
            outline: none;
            cursor: pointer;
        }

        #delete-unit-close {
            display: flex;
            align-items: center;
            width: 40%;
        }

        #delete-unit-close:hover {
            background-color: #d4d4d4ff;
            cursor: pointer;
        }

        tr {
            height: 30px;
        }
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

        .send-announcement{
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

        .send-announcement.active {
            display: block;
        }

        .send-announcement-content input {
            height: 40px;
            width: 100%;
            font-size: 15px;
            outline: none;
        }

        #send-announcement-close {
            display: flex;
            align-items: center;
            width: 40%;
        }

        #send-announcement-close:hover {
            background-color: #d4d4d4ff;
            cursor: pointer;
        }
        .view-requests {
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

        .view-requests.active {
            display: block;
        }

        .view-requests-content input {
            height: 40px;
            width: 100%;
            font-size: 15px;
            outline: none;
        }

        #view-requests-close {
            display: flex;
            align-items: center;
            width: 40%;
        }

        #view-requests-close:hover {
            background-color: #d4d4d4ff;
            cursor: pointer;
        }

        .edit-account {
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

        .edit-account.active {
            display: block;
        }

        .edit-account-content input {
            height: 40px;
            width: 100%;
            font-size: 15px;
            outline: none;
        }

        #edit-account-close {
            display: flex;
            align-items: center;
            width: 40%;
        }

        #edit-account-close:hover {
            background-color: #d4d4d4ff;
            cursor: pointer;
        }

        .view-profile {
            display: none;
            position: fixed;
            background: white;
            right: 0;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            min-width: 300px;
            font-size: 20px;
        }

        .view-profile.active {
            display: flex;
        }

        .view-profile-content{
            background-color: white;
            border: none;
            outline: none;
            width: 100%;
            height: 50px;
            display:flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        .view-profile-content:hover{
            background-color: #e9e9e9ff;
            cursor: pointer;
        }
        .view-profile-content:first-child{
            cursor: default;
        }
        .view-profile-content:first-child:hover{
            background-color: white;
        }
        #view-profile-close {
            display: flex;
            align-items: center;
            width: 40%;
        }

        #view-profile-close:hover {
            background-color: #d4d4d4ff;
            cursor: pointer;
        }

        .account-info{
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
        }

        .account-info.active {
            display: block;
        }

        #account-info-close {
            display: flex;
            align-items: center;
            width: 40%;
        }

        #account-info-close:hover {
            background-color: #d4d4d4ff;
            cursor: pointer;
        }
        .overview-buttons-container{
            width: 100%;   
            display:flex;
            justify-content: start;
            height: 60px;
            gap: 5px;
        }
        .overview-buttons{
            border:none;
            outline: none;
            flex: 1;                            
            padding: 10px;
            box-sizing: border-box;
            color:white;
            font-family: Inter-Bold;
            cursor: pointer;
            background-color: #ebebebff;
            color: #62929E;
            font-size:16px;
        }
        .overview-buttons:hover{
            background-color: #d4d4d4ff;
        }
        #remove-tenant, #delete-button{
            background-color: #ffa8aaff;
            color: #464646ff;
        }
        #create-unit-button{
            color:#62929E;
            font-family: Inter-Bold, Arial;
            border: none;
            cursor: pointer;
        }
        #create-unit-button:hover{
            background-color: #d4d4d4ff;
        }
        #assign-tenant-button{
            color:#62929E;
            font-family: Inter-Bold, Arial;
            border: none;
            cursor: pointer;
        }
        #assign-tenant-button:hover{
            background-color: #d4d4d4ff;
        }
        #edit-unit-button{
            color:#62929E;
            font-family: Inter-Bold, Arial;
            border: none;
            cursor: pointer;
        }
        #edit-unit-button:hover{
            background-color: #d4d4d4ff;
        }
        #remove-tenant{
            font-family: Inter-Bold, Arial;
            border: none;
            cursor: pointer;
        }
        #remove-tenant:hover{
            background-color: #ee8385ff;
        }
        #delete-button{
            font-family: Inter-Bold, Arial;
            border: none;
            cursor: pointer;
        }
        #delete-button:hover{
            background-color: #ee8385ff;
        }
        .table-container{
            width: 100%;   
            overflow-x: scroll;
            min-width: 1195px;
            height: 100%;
        }
        #top-bar{
            background-color: #393D3F; 
            width: 100%; 
            display: flex; 
            justify-content: space-between;
            align-items: center;
        }
        #message-content{
            height: 100px;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="navbar.css">
    <title>Manage Units</title>
</head>

<body>
    
    <div id = "top-bar">
        
     <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="unitease">
        <span style="color: #62929E">U</span>nitEase
        <img src="images/logo-blue.png" alt="logo blue" style="width: min(30px, 5vw);" >
    </a>
        <span id ="admin-account" onclick = viewProfile() style="cursor: pointer;"><h2 style="color: white; height: 100%; display: flex; flex-shrink: 1;align-items: center; margin-right: 30px;">
        <?php
        echo"⌄ Admin Account";
        ?>
        </h2></span>

        <span id ="down-arrow" onclick = viewProfile() style="cursor: pointer;"><h2 style="color: white; height: 100%; display: flex; flex-shrink: 1;align-items: center; margin-right: 30px;">
        <?php
        echo"⌄";
        ?>
        </h2></span>
    </div>
    <div class="main-container">
       

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
                    <input type="text" name="unit-name" id="unit-name" placeholder="Unit Name" required>
                </div>
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
                    <input type="text" name="tenant-username" id="tenant-username" placeholder="Tenant Username" required>
                </div>
                <br>
                <div class="assign-tenant-content">
                    <input type="text" name="unit-name" id="unit-name" placeholder="Unit Name" required>
                </div>
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
                <label>Edit Unit</label>
                <br>
                <br>
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

        <div class="send-announcement">
            <div onclick=sendAnnouncement() id="send-announcement-close">
                <img src="images/close-blue.png" alt="close" id="close-icon" style="width:30px; height:30px; margin:10px;">
                Close
            </div>
            <br>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="hidden" name="select_unit[]" id="send-announcement-array">
                <div class="send-announcement-content"><input type="text" name="message-subject" id="message-subject" placeholder="Subject:"></div>
                <br>
                <div class="send-announcement-content"><input type="text" name="message-content" id="message-content" placeholder="Message:" required></div>
                <br>
                <div class="send-announcement-content"><input type="submit" value="Send to selected units only" id="send-announcement-button" name="send-announcement-button"></div>
                <br>

                <div class="send-announcement-content"><input type="submit" value="Send to all units" id="send-all-announcement-button" name="send-all-announcement-button"></div>
                <br>
            </form>
        </div>

        <div class="view-requests">
            <div onclick=viewRequests() id="view-requests-close">
                <img src="images/close-blue.png" alt="close" id="close-icon" style="width:30px; height:30px; margin:10px;">
                Close
            </div>
            <br>
            <div>No requests at the moment.</div>
        </div>

        <div class="view-profile">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="font-family: Inter-Regular; width:100%;">
                <div class="view-profile-content">
                    <?php
                    $admin_id = $_SESSION['admin_id'];
                    $sql = "SELECT username FROM admin_accounts WHERE admin_id = '$admin_id'";
                    $result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($result);
                    
                    echo "Username: " . $row['username'];
                    ?>
                </div>
    
                <input onclick=accountInfo() class="view-profile-content" type="button" value="Account Info">
                <input onclick=editAccount() class="view-profile-content" type="button" value="Edit Account">
                <input class="view-profile-content" type="submit" value="Log-out" name="logout-button" id="logout-button">
            </form>
        </div>

        <div class="edit-account">
            <div onclick=editAccount() id="edit-account-close">
                <img src="images/close-blue.png" alt="close" id="close-icon" style="width:30px; height:30px; margin:10px;">
                Close
            </div>
            <br>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <label><h2>Edit Account</h2></label>
                <br>
                <br>
                <input type="hidden" name="select_unit[]" id="edit-account-array">
                <div class="edit-account-content">
                    <input type="text" name="edit-username" id="edit-username" placeholder="Change Username (Leave empty to ignore)">
                </div>
                <br>
                <div class="edit-account-content">
                    <input type="password" name="edit-password" id="edit-password" placeholder="Change Password (Leave empty to ignore)">
                </div>
                <br>
                <div class="edit-account-content">
                    <input type="password" name="edit-password-confirm" id="edit-password-confirm" placeholder="Confirm Password">
                </div>
                <br>
                <div class="edit-account-content">
                    <input type="text" name="edit-fullname" id="edit-fullname" placeholder="Edit Full Name (Leave empty to ignore)">
                </div>
                <br>
                <div class="edit-account-content">
                    <input type="email" name="edit-email" id="edit-email" placeholder="Change Email (Leave empty to ignore)">
                </div>
                <br>
                <div class="edit-account-content">
                    <input type="tel" name="edit-phone" id="edit-phone" placeholder="Change Phone Number (Leave empty to ignore)">
                </div>
                <br>
                <div class="edit-account-content">
                    <input type="submit" value="Apply Changes" id="edit-account-button" name="edit-account-button">
                </div>
                <br>
            </form>
        </div>
        
        <div class="account-info">
            <div onclick=accountInfo() id="account-info-close">
                <img src="images/close-blue.png" alt="close" id="close-icon" style="width:30px; height:30px; margin:10px;">
                Close
            </div>

            
            <div class ="account-info-content"><center><h2>Account Information</h2></center><br><br>
            <div>
            <?php
            $admin_id = $_SESSION['admin_id'];
            $sql = "SELECT * FROM admin_accounts WHERE admin_id = '$admin_id'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            
            echo "Username: " . $row['username'] . "<br><br>";
            echo "Full Name: " . $row['full_name'] . "<br><br>";
            echo "Email: " . $row['email'] . "<br><br>";
            echo "Phone number: " . $row['phone_number'];
            
            ?>
            </div>
        </div>

        </div>
        <div class="delete-unit">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <label>Delete Selected Units?</label>
                <br>
                <br>
                <input type="hidden" name="select_unit" id="delete-unit-array">
                <div class="delete-unit-content">
                    <input type="submit" name="confirm-delete-button" id="confirm-delete-button" value="Confirm Delete">
                </div>
                <br>
                <div class="delete-unit-content">
                    <input onclick=deleteUnit() type="button" name="cancel-delete-button" id="cancel-delete-button" value = "Cancel">
                </div>
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
                    $sql = "SELECT SUM(capacity) AS total_capacity FROM units WHERE status = 'Available'";
                    $result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($result);
                    $total_capacity = (int) ($row['total_capacity'] ?? 0);

                    $admin_id = $_SESSION['admin_id'];
                    $stmt = "SELECT COUNT(DISTINCT tu.tenant_id) AS tenant_count
                            FROM tenant_units tu
                            JOIN units u ON tu.unit_id = u.unit_id
                            WHERE u.admin_id = '$admin_id' AND status = 'Available'";

                    $count_result = mysqli_query($conn, $stmt);
                    $count_row = mysqli_fetch_assoc($count_result);
                    $total_tenants = (int) ($count_row['tenant_count']);
                    echo $total_capacity - $total_tenants;
                    ?></h1>
                <br>
                Available Slots

            </div>

            <div class="cards item-4">
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
            <div class="cards item-5" style="height: 70%;">
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
                    <div class="overview-buttons-container">
                        <input class="overview-buttons" onclick=addUnit() type="button" name="add-unit-button" value="Add New Unit" id="add-button">
                        <input class="overview-buttons" onclick=assignTenant() type="button" name="assign-tenant" value="Assign a Tenant" id="assign-tenant">
                        <input class="overview-buttons" onclick=editUnit() type="button" name="edit-button" value="Edit Selected Unit" id="edit-button">
                        <input class="overview-buttons" onclick=sendAnnouncement() type="button" name="send-announcement" value="Send Announcement" id="send-announcement">
                        <input class="overview-buttons" onclick=viewRequests() type="button" name="view-requests" value="View Requests" id="view-requests">
                        <input class="overview-buttons" type="submit" name="refresh-button" value="Refresh" id="refresh-button">
                        <input class="overview-buttons" onclick=removeTenant() type="button" name="remove-tenant" value="Remove a Tenant" id="remove-tenant">
                        <input class="overview-buttons" onclick = deleteUnit() type="button" name="delete-button" value="Delete Selected Units" id="delete-button">
                    </div>
                    <br>
                    <div class="table-container" style="height: 100%; width:100%; overflow-y:auto; justify-content:center; align-items:center;">
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
                                <table border='1' style='border-collapse: collapse; border-color: black; width: 100%; text-align: center; border-radius: 20px;'>
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
                                    $status_bg = '#fc8d8fff';
                                    $status_color = "black";
                                } else {
                                    $status_bg = '#8ac7bb';
                                    $status_color = "black";
                                }

                                echo "<tr>
                                        <td><input type='checkbox' name='select_unit[]' value='" . $row['unit_id'] . "'></td>
                                        <td>" . $row['unit_name'] . "</td>
                                        <td>" . $row['capacity'] . "</td>
                                        <td>" . $row['unit_floor'] . "</td>
                                        <td style='background-color: $status_bg; color: $status_color;'>" . $status . "</td>
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
    </div>
    </div>

    <script>
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
        function sendAnnouncement() {
            const checkboxes = document.getElementsByName("select_unit[]");
            const selected = [];

            for (let i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    selected.push(checkboxes[i].value);
                }
            }

            if (selected.length == 0) {
                alert("Please select at least one unit to send the announcement to.");
                return;
            }

            document.getElementById('send-announcement-array').value = selected.join(',');
            document.querySelector('.send-announcement').classList.toggle('active');
        }
        function viewRequests() {
            document.querySelector('.view-requests').classList.toggle('active');
        }
        function viewProfile() {
            document.querySelector('.view-profile').classList.toggle('active');
        } 
        function accountInfo() {
            document.querySelector('.account-info').classList.toggle('active');
        } 
        function editAccount() {
            document.querySelector('.edit-account').classList.toggle('active');
        } 
        function deleteUnit() {
            const checkboxes = document.getElementsByName("select_unit[]");
            const selected = [];

            for (let i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    selected.push(checkboxes[i].value);
                }
            }

            if (selected.length == 0) {
                alert("Please select at least one unit to delete.");
                return;
            }

            document.getElementById('delete-unit-array').value = selected.join(',');
            document.querySelector('.delete-unit').classList.toggle('active');
        }
    </script>
</body>

</html>
<?php


?>