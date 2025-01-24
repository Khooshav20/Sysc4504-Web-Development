<?php
   session_start();

   include("common/authentication.php");
   include("common/avatars.php");
   include("common/programs.php");

   if (!$isLoggedIn) {
      header("Location: login.php");
   }

   if($_SERVER['REQUEST_METHOD'] === 'POST'){
      //On profile.php, we need to account for new data such as street number, street name, city, province, postal code and avatar
      $st_name = $_POST["street_name"];
      $st_number = $_POST["street_number"];
      $city = $_POST["city"];
      $province = $_POST["province"];
      $code = $_POST['postal_code'];
      $avatar = (int) $_POST['avatar'];

      //should also account for any updated information about first name, last name, DOB, program
      $first = $_POST["first_name"];
      $last = $_POST["last_name"];
      $DOB = $_POST["DOB"];
      $program = $_POST["program"];

      //populate users_address
      $sql = "UPDATE users_address SET street_name=?, street_number=?, city=?, province=?, postal_code=? WHERE student_id=?;";
      $statement = $conn->prepare($sql);
      $statement->bind_param("sssssi", $st_name, $st_number, $city, $province, $code, $student_id);
      $statement->execute();

      //populate users_avatar
      $sql = "UPDATE users_avatar SET avatar=? WHERE student_id=?;";
      $statement = $conn->prepare($sql);
      $statement->bind_param("ii", $avatar, $student_id);
      $statement->execute();

      //update info and program accordingly
      $sql = "UPDATE users_info SET first_name=?, last_name=?, DOB=? WHERE student_id=?;";
      $statement = $conn->prepare($sql);
      $statement->bind_param("sssi", $first, $last, $DOB, $student_id);
      $statement->execute();

      $sql = "UPDATE users_program SET Program=? WHERE student_id=?;";
      $statement = $conn->prepare($sql);
      $statement->bind_param("si", $program, $student_id);
      $statement->execute();
   }

   $sql = "SELECT * FROM users_info
   INNER JOIN users_address ON users_info.student_id = users_address.student_id
   INNER JOIN users_program ON users_info.student_id = users_program.student_id
   INNER JOIN users_avatar ON users_info.student_id = users_avatar.student_id
   WHERE users_info.student_id=?;";
   $statement = $conn->prepare($sql);
   $statement->bind_param("i", $student_id);
   $statement->execute();
   $user_profile_result = $statement->get_result();
   $row = $user_profile_result->fetch_assoc();

   $student_email = $row['student_email'];
   $first_name = $row['first_name'];
   $last_name = $row['last_name'];
   $DOB = $row['DOB'];
   $street_number = $row['street_number'];
   $street_name = $row['street_name'];
   $city = $row['city'];
   $province = $row['province'];
   $postal_code = $row['postal_code'];
   $program = $row['Program'];
   $avatar = (int) $row['avatar'];
   $avatar = $avatar === 0 ? 1 : $avatar;

   $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>Update SYSCX profile</title>
      <link rel="stylesheet" href="assets/css/reset.css">
      <link rel="stylesheet" href="assets/css/style.css">
   </head>

   <body>
      <?php include("common/header.php"); ?>

      <div class="container">
         <?php include("common/nav.php"); ?>

         <main>
            <section>
               <h2>Update Profile information</h2>
               <form method="post" action="" id="profile_form">
                  <fieldset>
                     <legend><span>PERSONAL INFORMATION</span></legend>
                     <table>
                        <tbody>
                           <tr>
                              <td>
                                 <label>First name:</label>
                                 <input type="text" placeholder="Ex. John Snow" name="first_name" value="<?php echo $first_name; ?>">
                              </td>
                              <td>
                                 <label>Last name:</label>
                                 <input type="text" placeholder="Insert last name" name="last_name" value="<?php echo $last_name; ?>">
                              </td>
                              <td>
                                 <label>DOB:</label>
                                 <input type="date" name="DOB" value="<?php echo $DOB; ?>">
                              </td>
                           </tr>
                        </tbody>
                     </table>
                  </fieldset>
                  <fieldset>
                     <legend><span>ADDRESS</span></legend>
                     <table>
                        <tbody>
                           <tr>
                              <td>
                                 <label>Street number:</label>
                                 <input type="number" id="street_number" name="street_number" value="<?php echo $street_number; ?>">
                              </td>
                              <td colspan="2">
                                 <label>Street name:</label>
                                 <input type="text" id="street_name" name="street_name" value="<?php echo $street_name; ?>">
                              </td>
                           </tr>
                           <tr>
                              <td>
                                 <label>City: </label>
                                 <input type="text" name="city" id="city" value="<?php echo $city; ?>">
                              </td>
                              <td>
                                 <label>Province: </label>
                                 <input type="text" id="province" name="province" value="<?php echo $province; ?>">
                              </td>
                              <td>
                                 <label>Postal Code: </label>
                                 <input type="text" name="postal_code" id="postal_code" value="<?php echo $postal_code; ?>">
                              </td>
                           </tr>
                        </tbody>
                     </table>
                  </fieldset>
                  <fieldset>
                     <legend><span>PROFILE INFORMATION</span></legend>
                     <table>
                        <tbody>
                           <tr>
                              <td>
                                 <label for="email">Email Address:</label>
                                 <input type="email" name="student_email" id="email" disabled value="<?php echo $student_email; ?>">
                              </td>
                           </tr>
                           <tr>
                              <td>
                                 <label for="program">Program</label>
                                 <select id="program" name="program">
                                    <option>-- Choose a program --</option>
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
                                 <label>Choose Avatar</label><br>
                                 <?php
                                    foreach ($avatars as $key => $src) {
                                       $checked = $avatar === $key ? "checked" : "";
                                       echo "<input type='radio' name='avatar' class='avatar' value='$key' $checked><img src='$src' alt='Avatar$key' class='avatar-image'>";
                                    }
                                 ?>
                              </td>
                           </tr>
                           <tr>
                              <td>
                                 <input type = "submit" id="post_button" value="SUBMIT">
                                 <input type = "reset" id="reset_button" value="RESET">
                              </td>
                           </tr>
                        </tbody>
                     </table>
                  </fieldset>
               </form>
            </section>
         </main>

         <?php include("common/user_info.php"); ?>
      </div>
   </body>
</html>