<?php
   session_start();

   include("connection.php");
   $conn = new mysqli($server_name, $username, $password, $database_name);

   if ($conn->connect_error){
      die("Couldn't establish connection");
   }

   include("authentication.php");

   $programs_list = array(
      "Computer Systems Engineering",
      "Software Engineering",
      "Communications Engineering",
      "Biomedical and Electrical Engineering",
      "Electrical Engineering",
      "Special"
   );

   $avatars_list = array(
      1 => "images/img_avatar1.png",
      2 => "images/img_avatar2.png",
      3 => "images/img_avatar3.png",
      4 => "images/img_avatar4.png",
      5 => "images/img_avatar5.png"
   );

   if ($isLoggedIn === false) {
      header("Location: register.php");
   }

   if($_SERVER['REQUEST_METHOD'] === 'POST'){
      //On profile.php, we need to account for new data such as street number, street name, city, province, postal code and avatar
      $st_name = $_POST["street_name"];
      $st_number = $_POST["street_number"];
      $city = $_POST["city"];
      $province = $_POST["province"];
      $code = $_POST['postal_code'];
      $avatar = (int) $_POST['avatar'];

      //should also account for any updated information about first name, last name, DOB, email, program
      $first = $_POST["first_name"];
      $last = $_POST["last_name"];
      $DOB = $_POST["DOB"];
      $email = $_POST["student_email"];
      $program = $_POST["program"];

      //populate users_address
      $sql = "UPDATE users_address SET street_name='$st_name', street_number=$st_number, city='$city', province='$province', postal_code='$code' WHERE student_id=$student_id;";
      $conn->query($sql);

      //populate users_avatar
      $sql = "UPDATE users_avatar SET avatar=$avatar WHERE student_id=$student_id;";
      $conn->query($sql);

      //update info and program accordingly
      $sql = "UPDATE users_info SET first_name='$first', last_name='$last', DOB='$DOB',  student_email='$email' WHERE student_id=$student_id;";
      $conn->query($sql);

      $sql = "UPDATE users_program SET Program='$program' WHERE student_id=$student_id;";
      $conn->query($sql);
   }

   // retrieve from users_info
   $sql = "SELECT * FROM users_info WHERE student_id=$student_id;";
   $result = $conn->query($sql);
   $row = $result->fetch_assoc();

   $student_email = $row['student_email'];
   $first_name = $row['first_name'];
   $last_name = $row['last_name'];
   $DOB = $row['DOB'];

   // retrieve from users_address
   $sql = "SELECT * FROM users_address WHERE student_id=$student_id;";
   $result = $conn->query($sql);
   $row = $result->fetch_assoc();

   $street_number = $row['street_number'];
   $street_name = $row['street_name'];
   $city = $row['city'];
   $province = $row['province'];
   $postal_code = $row['postal_code'];

   // retrieve from users_program
   $sql = "SELECT * FROM users_program WHERE student_id=$student_id;";
   $result = $conn->query($sql);
   $row = $result->fetch_assoc();

   $program = $row['Program'];

   // retrieve from users_avatar
   $sql = "SELECT * FROM users_avatar WHERE student_id=$student_id;";
   $result = $conn->query($sql);
   $row = $result->fetch_assoc();

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
      <header>
         <h1>SYSCX</h1>
         <p>Social media for SYSC students in Carleton University</p>
      </header>
      <nav>
         <table>
            <tr>
               <td>
                  <a href="index.php">Home</a>
               </td>
            </tr>
            <tr>
               <td>
                  <a href="profile.php">Profile</a>
               </td>
            </tr>
            <tr>
               <td>
                  <a href="register.php">Register</a>
               </td>
            </tr>
            <tr>
               <td>
                  <a href="logout.php">Log Out</a>
               </td>
         </table>
      </nav>

      <main>
         <section>
            <h2>Update Profile information</h2>
            <form method="post" action="" id="profile_form">
               <fieldset>
                  <legend><span>PERSONAL INFORMATION</span></legend>
                  <table>
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
                  </table>
               </fieldset>
               <fieldset>
                  <legend><span>ADDRESS</span></legend>
                  <table>
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
                  </table>
               </fieldset>
               <fieldset>
                  <legend><span>PROFILE INFORMATION</span></legend>
                  <table>
                     <tr>
                        <td>
                           <label for="email">Email Address:</label>
                           <input type="email" name="student_email" id="email" value="<?php echo $student_email; ?>">
                        </td>
                     </tr>
                     <tr>
                        <td>
                           <label for="program">Program</label>
                           <select id="program" name="program">
                              <option>-- Choose a program --</option>
                              <?php
                                 foreach ($programs_list as $p) {
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
                              foreach ($avatars_list as $key => $src) {
                                 $checked = $avatar === $key ? "checked" : "";
                                 echo "<input type='radio' name='avatar' class='avatar' value='$key' $checked><img src='$src' alt='Avatar$key' class='avatar-image'>";
                              }
                           ?>
                        </td>
                     </tr>
                     <tr>
             cd           <td>
                           <input type = "submit" id="post_button" value="SUBMIT">
                           <input type = "reset" id="reset_button" value="RESET">
                        </td>
                     </tr>
                  </table>
               </fieldset>
            </form>
         </section>
         <div class="info-box">
            <h2><?php echo "$first_name $last_name"; ?></h2>
            <img id="avatar00" src="<?php echo $avatars_list[$avatar]; ?>" alt="Avatar Image" title="Avatar">
            <p> <strong>Email:</strong> <a href="mailto:<?php echo $student_email; ?>"><?php echo $student_email; ?></a></p> <br>
            <p><strong>Program:</strong> <?php echo $program; ?></p>
         </div>
      </main>
   </body>
</html>