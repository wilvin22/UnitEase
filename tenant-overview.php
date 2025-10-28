<?php
session_start();
include 'database.php';


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
        grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
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
        max-width: 420px;
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
                    $sql = "SELECT * FROM tenant_units INNER JOIN units ON tenant_units.unit_id = units.unit_id WHERE tenant_id = '$tenant_id'";
                    $result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($result);

                    echo "Unit Name: " . $row['unit_name'] . "<br><br>";
                    echo "Unit Floor: " . $row['unit_floor'] . "<br><br>";
                    echo "Unit Capacity: " . $row['capacity'] . "<br><br>";
                    echo "Unit Status: " . $row['status'];
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
                    $row = mysqli_fetch_assoc($result);
                    $admin_id = $row['admin_id'];

                    $stmt = "SELECT * FROM admin_accounts WHERE admin_id = '$admin_id'";
                    $stmt_result = mysqli_query($conn, $stmt);
                    $stmt_row = mysqli_fetch_assoc($stmt_result);

                    echo "Username: " . $stmt_row['username'] . "<br><br>";
                    echo "Full Name: " . $stmt_row['full_name'] . "<br><br>";
                    echo "Email: " . $stmt_row['email'] . "<br><br>";
                    echo "Phone number: " . $stmt_row['phone_number'];
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
                    <br><br>
                    <input type="text" name="message-subject" id="message-subject" placeholder="Subject:"></div>
                <br>
                <div class="send-request-content"><input type="text" name="message-content" id="message-content" placeholder="Message:" required></div>
                <br>
                <div class="send-request-content"><input type="submit" value="Send request to admin" id="send-request-button" name="send-request-button"></div>
            </form>
        </div>


    </div>
</body>
</html>