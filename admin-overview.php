<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body{
            margin: 0;
            font-family: Arial, sans-serif;
            padding: 0;
        }
        .dashboard{
            height:100vh;
            width:min(300px, 40%);
            background-color:orange;
        }
        @media (max-width:600px){
            .dashboard{
                display:none;
            }
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin-dashboard.css">
    <title>Document</title>
</head>
<body>
    <img src="images/hamburger-blue.png" alt="menu" id="menu-icon" style="width:30px; height:30px; margin:10px; cursor:pointer;">
    <div class="dashboard">admin</div>
</body>
</html>