<?php

    include("connection.php");

    $conn = new mysqli($server_name, $username, $password, $database_name);

    if ($conn->connect_error){
        die("Could not connect. " . $conn->connect_error);
    }

    echo "Connection Successful";

    $conn->close();

?>