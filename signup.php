<?php include("database.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="wih2h=device-wih2h, initial-scale=1.0">
    <link rel="stylesheet" href="signup.css">
    <title>Signup - UnitEase</title>
</head>
<body>
    <div class="navbar">
            <ul>
                <li class="nav-items">
                    <a href="index.php" id="unitease"><span style="color: #62929E">U</span>nitEase <img src="images/logo-blue.png" alt="logo blue" style="width: min(40px, 5vw);"></a>
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
    <form action="signup.php" method="post" id="form-box">
        <img src="images/logo-blue.png" alt="logo blue" style="width: 40px">
        <div class="form-content"><h3 style="color: #393D3F;">Create New Account</h3></div>
        <div class="form-content">
            <input type="text" id="username" name="username" placeholder="Username" class="input-field"  required><br>
        </div>
        <div class="form-content">
            <input type="email" id="email" name="email" placeholder="Email" class="input-field"  required>
        </div>
        <div class="form-content">
            <input type="password" id="password" name="password" placeholder="Password"  class="input-field" required>
        </div>
        <div class="form-content">
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" class="input-field"  required>
        </div>
        <div class="form-content">
            <input type="tel" id="phone" name="phone" placeholder="Phone Number" class="input-field"  required>
        </div>
        <div class="form-content">
            <button type="submit" id="signup-button">Create Account</button>
        </div>
        <span>
            <a href="login.php" style="text-decoration: none; color: ;">Already have an account?</a>
        </span>
    </form>
    </div>
    <script src="index.js"></script>
</body>
</html>