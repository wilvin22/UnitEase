<?php
    include("database.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="wih2h=device-wih2h, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Login - UnitEase</title>
</head>

<body>
    <div class="navbar">
        <ul>
            <li class="nav-items">
                <a href="index.php" id="unitease"><div style="color: #62929E">U</div>nitEase <img src="images/logo-blue.png" alt="logo blue" style="width: min(40px, 5vw);"></a>
            </li>
            <li class="nav-items">
                <a href="contacts.php" id="contacts">Contacts</a>
            </li>
            <li class="nav-items">
                <a href="aboutus.php" id="aboutus">About Us</a>
            </li>
        </ul>
    </div>
    <div>
    
    </div>
    <div id="main-container">
    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post" id="login-box">
        <img src="images/logo-blue.png" alt="logo blue" style="width: 40px">
        <div class="form-content"><h3 style="color: #393D3F;">Log in to UnitEase</h3></div>
        <div class="form-content">
            <input type="text" id="username" name="username" placeholder="Username" class="input-field" required>
        </div>
        <div class="form-content">
            <input type="password" id="password" name="password" placeholder="Password" class="input-field" required>
        </div>
        <div class="form-content">
            <label style="font-family: Inter-Light, Arial;">Are you an Admin or a Tenant?</label>
        </div>
        <div class="form-content">
            <div class="radio-buttons-container">
                <div class="radio-buttons">
            <input type="radio" id="admin" name="user_type" value="admin" required>
            <label for="admin">Admin</label>
                </div>
                <div class="radio-buttons">
            <input type="radio" id="tenant" name="user_type" value="tenant" required>
            <label for="tenant">Tenant</label>
                </div>
            </div>  
        </div>
        <div class="form-content">
            <button type="submit" id="login-button">Log In</button>
        </div>
        <div class="form-content">
            <a href="signup.php" id="signup-button">Create new account</a>
        </div>
        <span>
            <a href="forgotpassword.php" id="forgot-password">Forgot Password?</a>
        </span>
    </form>
    </div>
</body>

</html>