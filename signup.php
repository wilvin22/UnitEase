<?php include("database.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="wih2h=device-wih2h, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
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
    <h2>Signup</h2>
    <form action="signup.php" method="post" id="login-box">
        
        <input type="text" id="username" name="username" placeholder="Username" class="input-field"  required><br>

        <input type="email" id="email" name="email" placeholder="Email" class="input-field"  required><br>

        <input type="password" id="password" name="password" placeholder="Password"  class="input-field" required><br>

        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" class="input-field"  required><br>

        <input type="tel" id="phone" name="phone" placeholder="Phone Number" class="input-field"  required><br>

        
        <button type="submit" id="signup-button">Create Account</button>
        <br><br>
        <span>
            Already have an account? <a href="login.php">Log in</a>
        </span>
    </form>
    </div>
    <script src="index.js"></script>
</body>
</html>

<?php

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
        $phone = filter_input(INPUT_POST, "phone", FILTER_SANITIZE_SPECIAL_CHARS);
        
       
            $sql = "INSERT INTO accounts(username, password, email, phone_number) VALUES (($username), ($email), ($password), ($phone))";
            mysqli_query($conn, $sql);
            echo "Account Created Successfully!";
    }

?>