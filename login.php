<?php
session_start();
include("database.php");

$message = '';
$message_type = '';

if (isset($_POST['login-button'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $user_type = $_POST['user-type'];

    $sql = "SELECT * FROM accounts WHERE username = '$username' AND account_type = '$user_type'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        if (password_verify($password, $row['password'])){
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_type'] = $row['account_type'];
            $_SESSION['logged_in'] = true;

            if ($row['account_type'] == 'Admin') {
                header("Location: admin.php");
            } 
            else if ($row['account_type'] == 'Tenant'){
                header("Location: tenant.php");
            }
            exit();

        } else {
            $message = "⚠ Incorrect password.";
            $message_type = 'error';
        }
    } 
    else {
        $message = "⚠ Account not found.";
        $message_type = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="wih2h=device-wih2h, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="navbar.css">
    <title>Login - UnitEase</title>
</head>

<body>
    <div class="navbar">
        <ul>
            <li class="nav-items">
                <a href="index.php" id="unitease">
                    <div style="color: #62929E">U</div>nitEase <img src="images/logo-blue.png" alt="logo blue" style="width: min(40px, 5vw);">
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
    <div>

    </div>
    <div id="main-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post" id="login-box">
            <img src="images/logo-blue.png" alt="logo blue" style="width: 40px">
            <div class="form-content">
                <h3 style="color: #393D3F;">Log in to UnitEase</h3>
            </div>
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
                <button type="submit" id="login-button" name="login-button">Log In</button>
            </div>
            <div class="form-content">
                <a href="signup.php" id="signup-button">Create new account</a>
            </div>
            <span>
                <a href="forgotpassword.php" id="forgot-password">Forgot Password?</a>
            </span>
            <?php if (!empty($message)) : ?>
                <div class="form-content">
                    <p style="color: <?php echo($message_type == 'success') ? 'green' : 'red';?>; font-size: 15px; margin-top: 10px;"><?php echo $message; ?></p>
                </div>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>