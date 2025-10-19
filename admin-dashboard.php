<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
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
    <title>Document</title>
</head>
<body>
    <div class="dashboard">
        admin
    </div>
</body>
</html>