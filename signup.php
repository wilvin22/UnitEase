<?php
    include('database.php');   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
        <input type="text" name="username" placeholder="Username">
        <br>
        <input type="password" name="password" placeholder="Password">
        <br>
        <input type="email" name="email" placeholder="Email">
        <br>
        <input type="phone" name="phone" placeholder="Phone Number">
        <br>
        <button type="submit">Sign Up</button>
    </form>
    
</body>
</html>

<?php

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $phone = filter_input(INPUT_POST, "phone", FILTER_SANITIZE_SPECIAL_CHARS);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        if(empty($username) || empty($password) || empty($email) || empty($phone)){
            echo "Please fill all required fields";
            exit;
        }
        else{
            $sql = "INSERT INTO accounts (username, password, email, phone_number) VALUES ('$username', '$hashed_password', '$email', '$phone')";
            try{
            mysqli_query($conn, $sql);
            echo "Account created successfully";
            }
            catch (mysqli_sql_exception) {
                echo "Username is already taken.";
            }
        }

    }

    mysqli_close($conn);

?>