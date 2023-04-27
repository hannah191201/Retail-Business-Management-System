<?php
    $hostname = "localhost";
    $username="root";
    $password="dbgroup15";
    $database="group_project";
    $conn = mysqli_connect($hostname, $username, $password);
    $db = mysqli_select_db($conn, $database);
?>