<?php
    $server_name = 'localhost';
    $database_name = 'khooshav_bundhoo_syscx';
    $username = 'root';
    $password = '';

    $conn = new mysqli($server_name, $username, $password, $database_name);

    if ($conn->connect_error){
       die("Couldn't establish connection");
    }
?>