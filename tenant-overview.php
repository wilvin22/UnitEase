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
            overflow-y: hidden;
            background-color: #f7f7f8ff;
        }
        #top-bar{
            background-color: #393D3F; 
            width: 100%; 
            display: flex; 
            justify-content: space-between;
            align-items: center;
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
        .view-announcements{
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

        .view-announcements.active {
            display: block;
        }

        .view-announcements-content input {
            height: 40px;
            width: 100%;
            font-size: 15px;
            outline: none;
        }

        #view-announcements-close {
            display: flex;
            align-items: center;
            width: 40%;
        }

        #view-announcements-close:hover {
            background-color: #d4d4d4ff;
            cursor: pointer;
        }
        .send-request {
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

        .send-request.active {
            display: block;
        }

        .send-request-content input {
            height: 40px;
            width: 100%;
            font-size: 15px;
            outline: none;
        }

        #send-request-close {
            display: flex;
            align-items: center;
            width: 40%;
        }

        #send-request-close:hover {
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

    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="navbar.css">
    <title>Tenant Homepage</title>
</head>
<body>
    <div id = "top-bar">
        
     <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="unitease">
        <span style="color: #62929E">U</span>nitEase
        <img src="images/logo-blue.png" alt="logo blue" style="width: min(30px, 5vw);" >
    </a>
        <span id ="admin-account" onclick = viewProfile() style="cursor: pointer;"><h2 style="color: white; height: 100%; display: flex; flex-shrink: 1;align-items: center; margin-right: 30px;">
        <?php
        echo"⌄ Tenant Account";
        ?>
        </h2></span>

        <span id ="down-arrow" onclick = viewProfile() style="cursor: pointer;"><h2 style="color: white; height: 100%; display: flex; flex-shrink: 1;align-items: center; margin-right: 30px;">
        <?php
        echo"⌄";
        ?>
        </h2></span>
    </div>

    <main>
        <div class="view-announcements">
            <div onclick=viewAnnouncements() id="view-announcements-close">
                <img src="images/close-blue.png" alt="close" id="close-icon" style="width:30px; height:30px; margin:10px;">
                Close
            </div>
            <br>
            <?php
            ?>
            Announcements go here.
        </div>

        <div class="send-request">
            <div onclick=sendRequest() id="send-request-close">
                <img src="images/close-blue.png" alt="close" id="close-icon" style="width:30px; height:30px; margin:10px;">
                Close
            </div>
            <br>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="send-request-content"><input type="text" name="message-subject" id="message-subject" placeholder="Subject"></div>
                <br>
                <div class="send-request-content"><input type="text" name="message-content" id="message-content" placeholder="Message..." required></div>
                <br>
                <div class="send-request-content"><input type="submit" value="Send to admin" id="send-request-button" name="send-request-button"></div>

            </form>
        </div>

        <div class="view-profile">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="font-family: Inter-Regular; width:100%;">
                <div class="view-profile-content">
                    <?php
                    $tenant_id = $_SESSION['tenant_id'];
                    $sql = "SELECT username FROM tenant_accounts WHERE tenant_id = '$tenant_id'";
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
    </main>

        <div>
            <button onclick=viewAnnouncements()>View Announcements</button>
            <button onclick=sendRequest()>Send a Request</button>
            <button onclick=editAccount()>Edit Account</button>
            <button onclick=accountInfo()>Account Info</button>
            <button onclick=editAccount()></button>
        </div>


    <script>
        function viewAnnouncements() {
            document.querySelector('.view-announcements').classList.toggle('active');
        }
        function sendRequest() {
            document.querySelector('.send-request').classList.toggle('active');
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

    </script>
</body>
</html>