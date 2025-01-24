<?php
    //student_id is first retrived from session
    $student_id = isset($_SESSION["student_id"]) ? (int) $_SESSION["student_id"] : null;
    $isLoggedIn = false;

    if ($student_id !== null) {
        // retrieve from users_info
        $sql = "SELECT * FROM users_info WHERE student_id=$student_id;";
        $result = $conn->query($sql);

        // if users_info does not exist for this student_id, redirect to register
        if ($result->num_rows === 0) {
            unset($_SESSION["student_id"]);
        } else {
            $isLoggedIn = true;
        }
    }
?>