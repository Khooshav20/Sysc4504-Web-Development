<?php
   session_start();

   unset($_SESSION["student_id"]);
   header("Location: index.php");
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Logged out</title>
    </head>
    <header>
        <h1>Signing you off...!</h1>
    </header>
</html>
