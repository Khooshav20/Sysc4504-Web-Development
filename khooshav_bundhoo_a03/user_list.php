<?php
  session_start();

  include("common/authentication.php");

  if ($isLoggedIn) {
    if ($isAdmin) {
      $sql = "SELECT * FROM users_info
        INNER JOIN users_permissions ON users_info.student_id = users_permissions.student_id
        INNER JOIN users_program ON users_info.student_id = users_program.student_id;";
      $statement = $conn->prepare($sql);
      $statement->execute();
      $users_list_result = $statement->get_result();
    }
  } else {
    header("Location: login.php");
  }
?>

<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>Register on SYSCX</title>
      <link rel="stylesheet" href="assets/css/reset.css">
      <link rel="stylesheet" href="assets/css/style.css">
   </head>

   <body>
      <?php include("common/header.php"); ?>

      <div class="container">
         <?php include("common/nav.php"); ?>

         <main>
           <section>
            <h2>Users List</h2>
            <?php if($isAdmin): ?>
            <table id="users-list">
              <tbody>
                <tr>
                  <th>Student ID</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Email</th>
                  <th>Program</th>
                  <th>Account Type</th>
                </tr>
                <?php
                  if ($users_list_result) {
                    while($user = $users_list_result->fetch_assoc())
                    {
                        echo "<tr>";
                        echo "<td>" . $user['student_id'] . "</td>";
                        echo "<td>" . $user['first_name'] . "</td>";
                        echo "<td>" . $user['last_name'] . "</td>";
                        echo "<td>" . $user['student_email'] . "</td>";
                        echo "<td>" . $user['Program'] . "</td>";
                        echo "<td>" . ($user['account_type'] === 1 ? "User" : "Admin") . "</td>";
                        echo "</tr>";
                    }
                  }
                ?>
              </tbody>
            </table>
            <?php else: ?>
            <p class="redirect-link">Permission denied. <a href="register.php">Go back to Home</a></p>
            <?php endif; ?>
            </section>
         </main>
         <?php include("common/user_info.php"); ?>
      </div>
   </body>
</html>
