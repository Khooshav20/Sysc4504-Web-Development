<?php
   session_start();

   //include the connection details
   include("common/connection.php");

   if ($_SERVER["REQUEST_METHOD"] === "POST"){
      $email = trim($_POST['student_email']);
      $password = $_POST['password'];

      $sql = "SELECT * FROM users_info INNER JOIN users_passwords ON users_info.student_id = users_passwords.student_id WHERE users_info.student_email=?";
      $statement = $conn->prepare($sql);
      $statement->bind_param("s", $email);
      $statement->execute();
      $login_result = $statement->get_result();

      if ($row = $login_result->fetch_assoc()) {
        if (password_verify($password, $row['password'])){
            $_SESSION["student_id"] = $row['student_id'];
            header("Location: index.php");
        } else {
            $invalid_login = true;
        }
      } else {
          $invalid_login = true;
      }
    }

    $conn->close();
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
               <form name="registerForm" method="post" action="" onsubmit="return validateForm()">
                  <fieldset>
                     <legend><span>Login</span></legend>
                     <table>
                        <tbody>
                          <tr>
                            <td>
                                <?php if($invalid_login): ?>
                                  <p class="error">Wrong email or password. Please try again.</p>
                                <?php endif; ?>
                                <label for="email">Email Address:</label>
                                <input type="email" name="student_email" id="email" required value="<?php echo $email; ?>">
                            </td>
                          </tr>
                          <tr>
                            <td>
                                <label for="password">Password:</label>
                                <input type="password" name="password" id="password" required>
                            </td>
                          </tr>
                          <tr>
                            <td>
                                <input type = "submit" id="post_button" value="LOGIN">
                            </td>
                          </tr>
                        </tbody>
                     </table>
                  </fieldset>
               </form>
               <p class="redirect-link">Don't have an account? <a href="register.php">Register here</a></p>
            </section>
         </main>

         <?php include("common/user_info.php"); ?>
      </div>
   </body>
</html>