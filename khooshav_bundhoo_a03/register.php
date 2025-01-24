<?php
   session_start();

   //include the connection details
   include("common/connection.php");
   include("common/programs.php");

   if ($_SERVER["REQUEST_METHOD"] === "POST"){
      $first = trim($_POST['first_name']);
      $last = trim($_POST['last_name']);
      $email = trim($_POST['student_email']);
      $program = $_POST['program'];
      $dob = $_POST['DOB'];
      $password = $_POST['password'];

      $sql = "SELECT COUNT(*) AS total FROM users_info WHERE student_email=?;";
      $statement = $conn->prepare($sql);
      $statement->bind_param("s", $email);
      $statement->execute();
      $result = $statement->get_result();
      $email_already_exists = false;
      if ($row = $result->fetch_assoc()) {
         $count = $row['total'];
         $email_already_exists = (int) $count > 0;
     }

      if (!$email_already_exists) {
         //create the sql command to execute - into users_info
         $sql = "INSERT INTO users_info(student_email, first_name, last_name, DOB) VALUES (?, ?, ?, ?)";
         $statement = $conn->prepare($sql);
         $statement->bind_param("ssss", $email, $first, $last, $dob);
         $statement->execute();
   
         //get the auto-generated primary key (student_id) from the first table
         $id = $conn->insert_id;
   
         //into users_passwords
         $sql = "INSERT INTO users_passwords(student_id, password) VALUES (?, ?)";
         $statement = $conn->prepare($sql);
         $statement->bind_param("is", $id, password_hash($password, PASSWORD_BCRYPT));
         $statement->execute();
   
         //into users_permissions
         $sql = "INSERT INTO users_permissions(student_id, account_type) VALUES (?, 1)";
         $statement = $conn->prepare($sql);
         $statement->bind_param("i", $id);
         $statement->execute();
   
         //into users_program
         $sql = "INSERT INTO users_program(student_id, Program) VALUES (?, ?)";
         $statement = $conn->prepare($sql);
         $statement->bind_param("is", $id, $program);
         $statement->execute();
   
         //into users_avatar
         $sql = "INSERT INTO users_avatar(student_id, avatar) VALUES (?, 0)";
         $statement = $conn->prepare($sql);
         $statement->bind_param("i", $id);
         $statement->execute();
         
         //into users_address
         $sql = "INSERT INTO users_address(student_id, street_number, street_name, city, province, postal_code) VALUES (?, 0, NULL, NULL, NULL, NULL)";
         $statement = $conn->prepare($sql);
         $statement->bind_param("i", $id);
         $statement->execute();
   
         $_SESSION["student_id"]=$id;
   
         // Redirect to profile PHP 
         header("Location: profile.php");
      }

      //close the connection
      $conn->close();
   }
?>

<!DOCTYPE html>
<html lang="en">

   <head>
      <meta charset="utf-8">
      <title>Register on SYSCX</title>
      <link rel="stylesheet" href="assets/css/reset.css">
      <link rel="stylesheet" href="assets/css/style.css">

      <script type="text/javascript">
         function passwordsMatch() {
            var password = document.forms["registerForm"]["password"].value;
            var confirm_password = document.forms["registerForm"]["confirm_password"].value;

            return password === confirm_password;
         }

         function validatePassword() {
            if(!passwordsMatch()) {
               document.getElementById("password-error").style.display = "block";
               return false;
            }

            document.getElementById("password-error").style.display = "none";
            return true;
         }

         function validateForm() {
            return validatePassword();
         }
      </script>
   </head>

   <body>
      <?php include("common/header.php"); ?>

      <div class="container">
         <?php include("common/nav.php"); ?>
         <main>
            <section>
               <h2>Register a new profile</h2>
               <form name="registerForm" method="post" action="" onsubmit="return validateForm()">
                  <fieldset>
                     <legend><span>Personal information</span></legend>
                     <table>
                        <tbody>
                           <tr>
                              <td>
                                 <label>First name:</label>
                                 <input type="text" placeholder="Insert first name" name="first_name" required value="<?php echo $first; ?>">
                              </td>
                              <td>
                                 <label>Last name:</label>
                                 <input type="text" placeholder="Insert last name" name="last_name" required value="<?php echo $last; ?>">
                              </td>
                              <td>
                                 <label>DOB:</label>
                                 <input type="date" name="DOB" required value="<?php echo $dob; ?>">
                              </td>
                           </tr>
                        </tbody>
                     </table>
                  </fieldset>
                  <fieldset>
                     <legend><span>Profile information</span></legend>
                     <table>
                        <tbody>
                           <tr>
                              <td>
                                 <?php if($email_already_exists): ?>
                                    <p class="error">A user already exists with this email. Use another one.</p>
                                 <?php endif; ?>
                                 <label for="email">Email Address:</label>
                                 <input type="email" name="student_email" id="email" required value="<?php echo $email; ?>">
                              </td>
                           </tr>
                           <tr>
                              <td>
                                 <label for="password">Password:</label>
                                 <input type="password" name="password" id="password" oninput="validatePassword();" required>
                                 <span id="password-error">Passwords do not match</span>
                              </td>
                           </tr>
                           <tr>
                              <td>
                                 <label for="confirm_password">Confirm Password:</label>
                                 <input type="password" name="confirm_password" id="confirm_password" oninput="validatePassword();" required>
                              </td>
                           </tr>
                           <tr>
                              <td>
                                 <label for="program">Program</label>
                                 <select name="program" id="program" required>
                                    <option value="">-- Please choose a program --</option>
                                    <?php
                                       foreach ($programs as $p) {
                                          $selected = $p === $program ? "selected" : "";
                                          echo "<option $selected>$p</option>";
                                       }
                                    ?>
                                 </select>
                              </td>
                           </tr>
                           <tr>
                              <td>
                                 <input type = "submit" id="post_button" value="REGISTER">
                                 <input type = "reset" id="reset_button" value="RESET">
                              </td>
                           </tr>
                        </tbody>
                     </table>
                  </fieldset>
               </form>
               <p class="redirect-link">Already have an account? <a href="login.php">Login here</a></p>
            </section>
         </main>

         <?php include("common/user_info.php"); ?>
      </div>
   </body>
</html>