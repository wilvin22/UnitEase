<?php
session_start();
include 'database.php';

$message = "";
$message_type = "";
if(isset($_POST['submit'])){
    $email = $_POST['email'];
    $username = $_POST['username'];
    $sql = "SELECT * FROM accounts WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    $stmt = "SELECT * FROM accounts WHERE email = '$email'";
    $stmt_result = mysqli_query($conn, $stmt);

    if(mysqli_num_rows($result) <= 0){
        $message = "❌ Username not found.";
        $message_type = "error";
    } 
    else if (mysqli_num_rows($stmt_result) <= 0){
        $message = "❌ Email not found.";
        $message_type = "error";
    }
    else{
        $row = mysqli_fetch_assoc($result);
        $username = $row['username'];

        $a = "SELECT * FROM admin_accounts WHERE username = '$username'";
        $a_result = mysqli_query($conn, $a);

        if(mysqli_num_rows($a_result) <= 0){
            //this means the account is a tenant account
            $temp_pass = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 6);
            $hashed_pass = password_hash($temp_pass, PASSWORD_DEFAULT);

            // Update password
            $update = "UPDATE tenant_accounts SET password = '$hashed_pass' WHERE username = '$username'";
            mysqli_query($conn, $update);

            // Send email
            $subject = "Your New Temporary Password";
            $body = "Hello,\n\nYour new temporary password is: $temp_pass\n\nPlease log in and change it immediately.\n\n- Unitease Team";
            $headers = "From: noreply@unitease.com\r\n";
            
            if (mail($email, $subject, $body, $headers)) {
                $message = "✅ A new password has been sent to your email.";
            } else {
                $message = "❌ Failed to send email. Check mail settings.";
        } 
    }
        else{
            //this means the account is an admin account
            $temp_pass = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 6);
            $hashed_pass = password_hash($temp_pass, PASSWORD_DEFAULT);

            // Update password
            $update = "UPDATE admin_accounts SET password = '$hashed_pass' WHERE username = '$username'";
            mysqli_query($conn, $update);

            // Send email
            $subject = "Your New Temporary Password";
            $body = "Hello,\n\nYour new temporary password is: $temp_pass\n\nPlease log in and change it immediately.\n\n- Unitease Team";
            $headers = "From: noreply@unitease.com\r\n";
            
            if (mail($email, $subject, $body, $headers)) {
                $message = "✅ A new password has been sent to your email.";
                $message_type = "success";
            } else {
                $message = "❌ Failed to send email. Check mail settings.";
                $message_type = "error";
        } 
        }
    }
}




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body{
            display: flex;
            justify-content: center;
            align-items: center;

            background-image: url(images/white-background2.jpg);
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        .forgot-password {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #ffffff;
            padding: 28px 32px;
            border-radius: 12px;
            min-width: 360px;
            max-width: 90%;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            text-align: center;
            transition: all 0.3s ease-in-out;
        }
        .forgot-password-content {
            margin-bottom: 16px;
        }

        .forgot-password-content label {
            font-weight: 600;
            font-size: 18px;
            color: #202124;
        }
        .forgot-password-content input {
            width: 100%;
            height: 40px;
            padding: 0 12px;
            border: 1px solid #d0d7de;
            border-radius: 8px;
            font-size: 15px;
            outline: none;
            box-sizing: border-box;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .forgot-password-content input:focus {
            border-color: #62929E;
            background-color: #ffffff;
            box-shadow: 0 0 4px rgba(98, 146, 158, 0.3);
        }

        #submit{
            color: #62929E;
            border: none;
            border-radius: 8px;
            height: 42px;
            width: 100%;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
        }

        #submit:hover {
            background-color: #c7c7c7ff;
            transform: translateY(-2px);
        }

        #forgot-password-close {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #62929E;
            font-weight: 500;
            width: fit-content;
            padding: 6px 10px;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        #forgot-password-close:hover {
            background-color: #e3f0f3;
            cursor: pointer;
        }
        a{
            text-decoration: none;
        }

    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="navbar.css">
</head>
<body>
    <div class = "forgot-password">
        <a href="login.php">
        <div id="forgot-password-close">
                <img src="images/back-arrow-blue.png" alt="close" id="close-icon" style="width:30px; height:30px; margin:10px;">
                Back to Log-in
            </div>
            </a>
            <br>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="forgot-password-content">
                <label for="email">Password Reset</label><br><br>
                <input type="email" name="email" id="email" placeholder="Email" required>
            </div>
            <br>
            <div class="forgot-password-content">
                <input type="text" name="username" id="username" placeholder="Username" required>
            </div>
            <br>
            <div class="forgot-password-content">
                <input type="submit" name="submit" id="submit" value="Send">
            </div>
            <?php if (!empty($message)): ?>
                <div class="form-content">
                    <p style="color: <?php echo($message_type == 'success') ? 'green' : 'red';?>; font-size: 15px; margin-top: 10px;"><?php echo $message; ?></p>
                </div>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>