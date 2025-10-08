<?php

    $db_host = 'localhost';
    $db_user = 'root';
    $db_pass = '';
    $db_name = 'unitease';

    try{
        $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
    }
    catch (mysqli_sql_exception) {
        echo 'Connection failed';
    }
?>