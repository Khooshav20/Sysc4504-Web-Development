<?php
   include("common/connection.php");
    //student_id is first retrived from session
    $student_id = isset($_SESSION["student_id"]) ? (int) $_SESSION["student_id"] : null;
    $isLoggedIn = false;

    if ($student_id !== null) {
        // retrieve from users_info
        $sql = "SELECT * FROM users_info INNER JOIN users_permissions ON users_info.student_id = users_permissions.student_id WHERE users_info.student_id=?";
        $statement = $conn->prepare($sql);
        $statement->bind_param("i", $student_id);
        $statement->execute();
        $auth_result = $statement->get_result();
        $row = $auth_result->fetch_assoc();

        // if users_info does not exist for this student_id, redirect to register
        if ($auth_result->num_rows === 0) {
            unset($_SESSION["student_id"]);
        } else {
            $isLoggedIn = true;
            $isAdmin = $row["account_type"] === 0;
        }
    }
?>