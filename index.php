<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="wih2h=device-wih2h, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="navbar.css">
    <title>UnitEase</title>
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
    <div class=top-container>
        <div class="main-content">
            <div id="slogan">
                <h1>Your units, made easy.</h1>
            </div>
            <img src="images/logo-blue.png" alt="logo blue" id="logo">
            <div id="description">
                <p>Tired of handling units and tenants manually? UnitEase makes handling units, tenants, and requests effortless.</p>
            </div>
            <div class="buttons">
                <a href="login.php">
                    <button id="login-button">Log In</button>
                </a>
                <a href="signup.php">
                    <button id="signup-button">Get Started</button>
                </a>
            </div>
        </div>
    </div>
    <div class="bottom-container">
            <div class="feature-item" id="feature-1">
                <div class="feature-text">
                    <h2>Manage your Units</h2>
                    <img src="images/key-blue.png" alt="key icon" id="key-icon" style="width: 15%;">
                    <p>Keep all your units organized in one place.</p>
                </div>
            </div>
            <div class="feature-item" id="feature-2">
                <div class="feature-text">
                    <h2>Handle Tenants</h2>
                    <img src="images/user-blue.png" alt="user icon" id="user-icon" style="width: 15%;">
                    <p>Easily track and manage tenant information.</p>
                </div>
            </div>
            <div class="feature-item" id="feature-3">
                <div class="feature-text">
                    <h2>Manage Requests</h2>
                    <img src="images/request-blue.png" alt="request icon" id="request-icon" style="width: 15%;">
                    <p>Respond to tenant requests quickly and efficiently.</p>
                </div>
            </div>
    </div>
</body>

</html>