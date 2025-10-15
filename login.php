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
    <div id="main-container">

    <h2>Login</h2>
    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post" id="login-box">

            <input type="text" id="username" name="username" placeholder="Username" class="input-field" required> <br>
            <input type="password" id="password" name="password" placeholder="Password" class="input-field" required>
        <br>
        <div>
            <label>Are you an Admin or a Tenant?</label>
        </div>
        <br>
        <div>
            <input type="radio" id="admin" name="user_type" value="admin" required>
            <label for="admin">Admin</label>
        
            <input type="radio" id="tenant" name="user_type" value="tenant" required>
            <label for="tenant">Tenant</label>
        </div>
        <br>
        <div>
            <button type="submit" id="login-button">Login</button>
        </div>
        <br>
        
        <div>
            Don't have an account? <a href="signup.php">Sign Up</a>
        </div>
    </form>
    </div>
</body>

</html>