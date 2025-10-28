<?php
session_start();
include 'database.php';

if(isset($_POST['send-request-button'])){
    $tenant_id = $_SESSION['tenant_id'];
    $subject = $_POST['message-subject'];
    $message = $_POST['message-content'];

    $stmt = "SELECT unit_id FROM tenant_units WHERE tenant_id = '$tenant_id'";
    $result = mysqli_query($conn, $stmt);
    $row = mysqli_fetch_assoc($result);
    $unit_id = $row['unit_id'];

    $sql = "INSERT INTO `requests` 
    (`tenant_id`, `subject`, `message`, `unit_id`) 
    VALUES ('$tenant_id', '$subject', '$message', '$unit_id');";
    try{
    $sql_result = mysqli_query($conn, $sql);
    $message = "✅ Request sent to admin.";
    $message_type = "success";
    } catch (mysqli_sql_exception){
        $message = "❌ Error sending request.";
        $message_type = "error";
    }
    
}
if (isset($_POST['edit-account-button'])) {
    $tenant_id = $_SESSION['tenant_id'];

    $edit_username = trim($_POST['edit-username']);
    $edit_password = trim($_POST['edit-password']);
    $edit_password_confirm = trim($_POST['edit-password-confirm']);
    $edit_fullname = trim($_POST['edit-fullname']);
    $edit_email = trim($_POST['edit-email']);
    $edit_phone = trim($_POST['edit-phone']);

    $hashed_password = password_hash($edit_password, PASSWORD_DEFAULT);
    $updates = [];
    if (!empty($edit_username)) $updates[] = "username = '$edit_username'";
    if (!empty($edit_password)) $updates[] = "password = '$hashed_password'";
    if (!empty($edit_fullname)) $updates[] = "full_name = '$edit_fullname'";
    if (!empty($edit_email)) $updates[] = "email = '$edit_email'";
    if (!empty($edit_phone)) $updates[] = "phone_number = '$edit_phone'";

    if ($edit_password != $edit_password_confirm) {
        $message = "❌ Passwords don't match.";
        $message_type = "error";
    } else {
        $update_sql = "UPDATE tenant_accounts SET " . implode(", ", $updates) . " WHERE tenant_id = '$tenant_id'";
        try {
            mysqli_query($conn, $update_sql);
            $message = "✅ Account updated successfully!";
            $message_type = "success";
        } catch (mysqli_sql_exception) {
            $message = "❌ Failed to update account.";
            $message_type = "error";
        }
    }
    if (empty($updates)) {
        $message = "⚠ No changes were made.";
        $message_type = "error";
    }
}

if (isset($_POST['logout-button'])) {
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
    /* Reset */
    * {
        box-sizing: border-box;
        font-family: Inter-Regular;
    }

    body {
        overflow-x: hidden;
        margin: 0;
        padding: 0;
        background-color: #f7f9fc;
        color: #333;
    }

    /* Main layout */
    .main-container {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        grid-template-rows: 1fr 1fr;
        gap: 25px;
        justify-items: center;
        align-items: start;
        width: 100%;
        min-height: 100vh;
        padding: 50px 20px;
    }

    /* Card styling */
    .cards {
        width: 100%;
        max-width: 100%;
        height: 100%;
        max-height: 100%;
        background-color: #ffffff;
        border: 1px solid #e6e9ee;
        border-radius: 16px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
        padding: 25px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .cards:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.12);
    }

    h2 {
        color: #62929E;
        margin-bottom: 10px;
        font-size: 1.3rem;
    }

    /* Account / Unit info content */
    .cards div {
        line-height: 1.6;
        font-size: 0.95rem;
    }

    /* Announcements */
    .view-announcements label {
    display: block;
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 14px;
    color: #62929E;
}
.announcement-container {
    max-height: 360px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 14px;
    padding-right: 8px;
}

.announcement-content {
    background-color: #f9fbff;
    border: 1px solid #dce7f5;
    border-radius: 12px;
    padding: 14px 16px;
    line-height: 1.6;
    font-size: 0.95rem;
    color: #333;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.announcement-content:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
}

    /* Send request form */
    .send-request label {
        font-weight: 600;
        color: #62929E;
    }

    .view-announcements{
        grid-column: 1 / 3;
    }
    .send-request input[type="text"] {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #ccd6e0;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: border-color 0.2s;
    }

    .send-request input[type="text"]:focus {
        border-color: #62929E;
        outline: none;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
    }

    #send-request-button {
        background-color: #62929E;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 16px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    #send-request-button:hover {
        background-color: #62929E;
    }
    #top-bar {
        background-color: #393D3F;
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    #unitease {
        text-decoration: none;
        color: white;
        font-size: 1.4rem;
        font-weight: bold;
        display: flex;
        align-items: center;
    }
    #down-arrow {
            display: none;
        }
    a {
        text-decoration: none;
        color: white;
    }
    @media (max-width:768px) {
        #tenant-account {
            display: none;
        }
        #down-arrow {
            display: block;
        }
    }

    .view-profile {
            display: none;
            position: fixed;
            background: var(--WHITE);
            top: 50px;
            right: 0;
            padding: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            min-width: 300px;
            font-size: 20px;
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
            transition: all 0.4s ease-in-out;
        }

        .view-profile.active {
            display: flex;
            flex-direction: column;
            gap: 10px;
            z-index: 999;
        }

        .view-profile-content {
            background-color: var(--WHITE);
            outline: none;
            width: 100%;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: var(--DARK);
            border-radius: 8px;
            border: none;
        }

        .view-profile-content:hover {
            background-color: var(--BLUE);
            color: var(--WHITE); 
            cursor: pointer;
        }
        .view-profile-content:first-child:hover{
            cursor: default;
            background-color: white;
            color: black;

        }
        .edit-account {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #ffffff;
            padding: 28px 32px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            min-width: 400px;
            max-width: 90%;
            text-align: left;
            transition: all 0.3s ease-in-out;
        }

        .edit-account.active {
            display: block;
            z-index: 999;
        }

        #edit-account-close {
            display: flex;
            align-items: center;
            gap: 8px;
            width: fit-content;
            padding: 6px 10px;
            border-radius: 6px;
            font-weight: 500;
            color: #444;
            transition: background 0.3s;
        }

        #edit-account-close:hover {
            background-color: #f1f3f4;
            cursor: pointer;
        }

        /* --- Inputs --- */
        .edit-account-content input {
            height: 40px;
            width: 100%;
            font-size: 15px;
            outline: none;
            border: 1px solid #d0d7de;
            border-radius: 8px;
            padding: 0 12px;
            box-sizing: border-box;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .edit-account-content input:focus {
            border-color: #58828dff;
            box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.15);
        }

        /* --- Submit button --- */
        #edit-account-button {
            background-color: #62929E;
            color: white;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            height: 42px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        #edit-account-button:hover {
            background-color: #58828dff;
            transform: translateY(-1px);
        }

        #edit-account-button:active {
            transform: translateY(0);
        }

        @media (max-height: 500px) {
            .edit-account {
                max-height: 90vh;
                overflow-y: auto;
            }
        }
        
        
</style>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="navbar.css">
    <title>Document</title>
</head>
<body>
    <div id="top-bar">

        <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="unitease">
            <span style="color: #62929E">U</span>nitEase
            <img src="images/logo-blue.png" alt="logo blue" style="width: min(30px, 5vw);">
        </a>
        <span id="tenant-account" onclick=viewProfile() style="cursor: pointer;">
            <h2 style="color: white; height: 100%; display: flex; flex-shrink: 1;align-items: center; margin-right: 30px;">
                <?php
                echo "⌄ Tenant Account";
                ?>
            </h2>
        </span>

        <span id="down-arrow" onclick=viewProfile() style="cursor: pointer;">
            <h2 style="color: white; height: 100%; display: flex; flex-shrink: 1;align-items: center; margin-right: 30px;">
                <?php
                echo "⌄";
                ?>
            </h2>
        </span>
    </div>

    <div class="main-container">
    <div class="cards account-info">
        <h2>Account Information</h2>
        <br><br>
            <div>
                <?php
                    $tenant_id = $_SESSION['tenant_id'];
                    $sql = "SELECT * FROM tenant_accounts WHERE tenant_id = '$tenant_id'";
                    $result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($result);

                    echo "Username: " . $row['username'] . "<br><br>";
                    echo "Full Name: " . $row['full_name'] . "<br><br>";
                    echo "Email: " . $row['email'] . "<br><br>";
                    echo "Phone number: " . $row['phone_number'];
                ?>
            </div>
    </div>

       <div class="cards unit-info">
        <h2>Unit Information</h2>
        <br><br>
            <div>
                <?php
                    $tenant_id = $_SESSION['tenant_id'];

                    $stmt = "SELECT * FROM tenant_units WHERE tenant_id = '$tenant_id'";
                    $stmt_result = mysqli_query($conn, $stmt);
                    
                    if(mysqli_num_rows($stmt_result) <= 0){
                        echo "Looks like you're not added to a unit.";
                    } else{

                    $sql = "SELECT * FROM tenant_units INNER JOIN units ON tenant_units.unit_id = units.unit_id WHERE tenant_id = '$tenant_id'";
                    $result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($result);

                    echo "Unit Name: " . $row['unit_name'] . "<br><br>";
                    echo "Unit Floor: " . $row['unit_floor'] . "<br><br>";
                    echo "Unit Capacity: " . $row['capacity'] . "<br><br>";
                    echo "Unit Status: " . $row['status'];
                    }
                ?>
            </div>
    </div>

    <div class="cards admin-info">
        <h2>Admin Information</h2>
        <br><br>
            <div>
                <?php
                    $tenant_id = $_SESSION['tenant_id'];

                    $sql = "SELECT * FROM tenant_units INNER JOIN units ON tenant_units.unit_id = units.unit_id WHERE tenant_id = '$tenant_id'";
                    $result = mysqli_query($conn, $sql);

                    if(mysqli_num_rows($result) <= 0){
                        echo "Looks like you don't have an admin yet.";
                    } else{
                    $row = mysqli_fetch_assoc($result);
                    $admin_id = $row['admin_id'];

                    $stmt = "SELECT * FROM admin_accounts WHERE admin_id = '$admin_id'";
                    $stmt_result = mysqli_query($conn, $stmt);
                    $stmt_row = mysqli_fetch_assoc($stmt_result);

                    echo "Username: " . $stmt_row['username'] . "<br><br>";
                    echo "Full Name: " . $stmt_row['full_name'] . "<br><br>";
                    echo "Email: " . $stmt_row['email'] . "<br><br>";
                    echo "Phone number: " . $stmt_row['phone_number'];
                    }
                ?>
            </div>
    </div>

    <div class="cards view-announcements">
        <label>Announcements</label>
            <?php

            $sql = "SELECT * FROM announcements";
            $result = mysqli_query($conn, $sql);


            if (mysqli_num_rows($result) > 0) {

                $b = "SELECT * FROM announcements INNER JOIN admin_accounts ON announcements.admin_id = admin_accounts.admin_id;";
                $b_result = mysqli_query($conn, $b);
                $b_row = mysqli_fetch_assoc($b_result);

                echo "<div class='announcement-container'>";
                while ($row = mysqli_fetch_assoc($result)) {

                    echo "<div class='announcement-content'>";
                    echo "From admin: " . $b_row['full_name'] . "<br><br>";
                    echo "</div >";

                    echo "<div class='announcement-content'>";
                    echo "Subject: " . $row['subject'] . "<br><br>";
                    echo "</div >";

                    echo "<div class='announcement-content'>";
                    echo "Message: " . $row['message'] . "<br><br>";
                    echo "</div>";

                    echo "<div class='announcement-content'>";
                    echo "Date Created: " . $row['time_created'];
                    echo "</div>";
                    echo "<br><br><br><br><br>";
                }
                echo "</div>";
            } else {
                echo "<p>No announcements found.</p>";
            }
            ?>
        </div>

   <div class="cards send-request">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="send-request-content">
                    <label>Send a Request</label>
                    <?php if (!empty($message)) : ?>
                            <span style="color: <?php echo ($message_type == 'success') ? 'green' : 'red'; ?>; margin-left:auto; font-size: 20px;"><?php echo $message ?></span>

                        <?php endif; ?>
                    <br><br>
                    <input type="text" name="message-subject" id="message-subject" placeholder="Subject:"></div>
                <br>
                <div class="send-request-content"><input type="text" name="message-content" id="message-content" placeholder="Message:" required></div>
                <br>
                <div class="send-request-content"><input type="submit" value="Send request to admin" id="send-request-button" name="send-request-button"></div>
            </form>
        </div>

        <div class="view-profile">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="font-family: Inter-Regular; width:100%;">
                <div class="view-profile-content">
                    <div id="message" style="margin-left: auto;">
                    <?php
                    $tenant_id = $_SESSION['tenant_id'];
                    $sql = "SELECT username FROM tenant_accounts WHERE tenant_id = '$tenant_id'";
                    $result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($result);

                    echo "Username: " . $row['username'];
                    ?>
                    </div>
                </div>
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
                <label>
                    <h2>Edit Account</h2>
                </label>
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
    </div>
    <script>
        function viewProfile() {
            document.querySelector('.view-profile').classList.toggle('active');
        }
        function editAccount() {
            document.querySelector('.edit-account').classList.toggle('active');
        }
    </script>
</body>
</html>