
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <link rel="icon" type="image/png" href="images/UnitEaseLogo.png">
    <title>Log-In</title>
</head>
<body>

    <div id="full-container">
    <div id="header-container">
        <img src="images/UnitEaseLogo.png" alt="Logo" style="width: 200px; margin-bottom: -20%;">
        <h1 id="header">Unit<span style="color:#F7A5A5;">Ease</span></h1>
        <p id="slogan"style="font-family: REM-Regular">Your units, made <span style="color:#F7A5A5; font-family: REM-Bold;">easy.</span></p>
    </div>

    <div id="login-container">
        <img src="images/UnitEaseLogo.png" alt="UnitEase Logo" id="logo" style="width: 150px;">
        <h1 id="login-container-header" style="font-family: REM-Extralight;">Welcome to <span style="font-family: REM-bold;">UnitEase</span></h1>
        <form action="index.php" method="post">
            <label for="username" style="font-family: REM-Regular;">Email:</label>
            <input type="text" id="username" name="username" style="font-family: REM-Regular; background-color: white; border-radius: 20px;" required>
            
            <label for="password" style="font-family: REM-Regular;">Password:</label>
            <input type="password" id="password" minlength="6" maxlength="20" name="password" style="font-family: REM-Regular; background-color:white; border-radius: 20px;" required>
            
            <button type="submit" id="login-button" style="font-family: REM-Bold; border-radius: 20px;">Log-in</button>

            <a href="signup.html">
            <button id="signup-button" style="font-family: REM-Bold; border-radius: 20px;">Sign-up</button>
            </a>
            
            <p><a href="forgot_password.php" id="forgot_password" style="font-family: REM-Bold;">Forgot Password?</a></p>
        </form>
    </div>
    </div>
</body>
</html>