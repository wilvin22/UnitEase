<?php
include("database.php");

$message = '';
$message_type = '';

if (isset($_POST['signup-button'])) {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $phone_number = $_POST['phone_number'];
    $user_type = $_POST['user-type'];
    $username_check = "SELECT * FROM accounts WHERE username = '$username'";
    $username_check_result = mysqli_query($conn, $username_check);
    
    if ($password != $confirm_password) {
        $message = "⚠ Passwords don't match";
        $message_type = 'error';
    }
    else if (mysqli_num_rows($username_check_result) > 0) {
        $message = "⚠ Username already taken!";
        $message_type = 'error';
    }
    else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        if ($user_type == 'Admin') {
            $sql = "INSERT INTO `accounts` (`account_id`, `account_type`, `username`, `password`, `email`, `phone_number`, `reg_date`) 
                    VALUES (NULL, 'Admin', '$username', '$hashed_password', '$email', '$phone_number', current_timestamp())";
        } else if ($user_type == 'Tenant') {
            $sql = "INSERT INTO `accounts` (`account_id`, `account_type`, `username`, `password`, `email`, `phone_number`, `reg_date`) 
                    VALUES (NULL, 'Tenant', '$username', '$hashed_password', '$email', '$phone_number', current_timestamp())";
        }

        if(mysqli_query($conn, $sql)){
            $message = "✅ Account created successfully!";
            $message_type = 'success';
        }
        else{
            $message = "⚠ Could not create account.";
            $message_type = 'error';
        }
       
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-wih2h, initial-scale=1.0">
    <link rel="stylesheet" href="signup.css">
    <link rel="stylesheet" href="navbar.css">
    <title>Signup - UnitEase</title>
</head>

<body>
    <div class="navbar">
        <ul>
            <li class="nav-items">
                <a href="index.php" id="unitease"><span style="color: #62929E">U</span>nitEase 
                    <img src="images/logo-blue.png" alt="logo blue" style="width: min(40px, 5vw);">
                </a>
            </li>
            <li class="nav-items">
                <a href="contacts.php" id="contacts">Contacts</a>
            </li>
            <li class="nav-items">
                <a href="aboutus.php" id="aboutus">About Us</a>
            </li>
        </ul>
    </div>
    <div id="main-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post" id="form-box">
            <img src="images/logo-blue.png" alt="logo blue" style="width: 40px">
            <div class="form-content">
                <h3 style="color: #393D3F;">Create New Account</h3>
            </div>
            <div class="form-content">
                <input type="text" id="username" name="username" placeholder="Username" class="input-field" required>
            </div>
            <div class="form-content">
                <input type="password" id="password" name="password" placeholder="Password" class="input-field" required>
            </div>
            <div class="form-content">
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" class="input-field" required>
            </div>
            <div class="form-content">
                <input type="email" id="email" name="email" placeholder="Email" class="input-field" required>
            </div>
            <div class="form-content">
                <input type="tel" id="phone_number" name="phone_number" placeholder="Phone Number" class="input-field" required>
            </div>
            <div class="form-content">
                <label style="font-family: Inter-Light, Arial;">Create account as:</label>
            </div>
            <div class="form-content">
                <div class="radio-buttons-container">
                    <div class="radio-buttons">
                        <input type="radio" id="admin" name="user-type" value="Admin" required>
                        <label for="admin">Admin</label>
                    </div>
                    <div class="radio-buttons">
                        <input type="radio" id="tenant" name="user-type" value="Tenant" required>
                        <label for="tenant">Tenant</label>
                    </div>
                </div>
            </div>
            <div class="form-content">
                <button type="submit" id="signup-button" name="signup-button">Create Account</button>
            </div>
            <span id="already-have-an-account">
                <a href="login.php" style="text-decoration: none; color: black;">Already have an account?</a>
            </span>

            <?php if (!empty($message)) : ?>
                <div class="form-content">
                    <p style="color: <?php echo($message_type == 'success') ? 'green' : 'red';?>; font-size: 15px; margin-top: 10px;"><?php echo $message; ?></p>
                </div>
            <?php endif; ?>
        </form>
    </div>
    <script src="index.js"></script>
</body>
</html>
