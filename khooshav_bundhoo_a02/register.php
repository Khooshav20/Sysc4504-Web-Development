<?php
   session_start();

   //include the connection details
   include("connection.php");

   //create the connection
   $conn = new mysqli($server_name, $username, $password, $database_name);

   if ($conn->connect_error){
      die("Couldn't establish connection");
   }

   $programs_list = array(
      "Computer Systems Engineering",
      "Software Engineering",
      "Communications Engineering",
      "Biomedical and Electrical Engineering",
      "Electrical Engineering",
      "Special"
   );

   if ($_SERVER["REQUEST_METHOD"] === "POST"){

      $first = $_POST['first_name'];
      $last = $_POST['last_name'];
      $email = $_POST['student_email'];
      $program = $_POST['program']; 
      $dob = $_POST['DOB'];

      
      //create the sql command to execute - into users_info
      $sql = "INSERT INTO users_info(student_email, first_name, last_name, DOB)
               VALUES('$email', '$first', '$last', '$dob')"; 
      
      //execute the command
      $result = $conn->query($sql);

      //get the auto-generated primary key (student_id) from the first table
      $id = $conn->insert_id;

      //into users_program
      $sql = "INSERT INTO users_program(student_id, Program) 
               VALUES($id, '$program')";
      $result = $conn->query($sql);

      //into users_avatar
      $sql = "INSERT INTO users_avatar(student_id, avatar)
               VALUES($id, 0)";
      $result = $conn->query($sql);
      
      //into users_address
      $sql = "INSERT INTO users_address(student_id, street_number, street_name, city, province, postal_code) 
               VALUES($id, 0, NULL, NULL, NULL, NULL)";
      $result = $conn->query($sql);

      $sql = "SELECT * FROM users_info WHERE student_id=$id;"; //for users_info
      $result = $conn->query($sql);
      //create a new data array
      $data_info = array();
      if($result->num_rows > 0){
         while($row = $result->fetch_assoc()){
            array_push($data_info, $row); 
         }
      }
      //close the connection
      $conn->close();

      $_SESSION["student_id"]=$id;

      // Redirect to profile PHP 
      header("Location: profile.php");
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
            <h2>Register a new profile</h2>
            <form method="post" action="">
               <fieldset>
                  <legend><span>Personal information</span> </legend>
                  <table>
                     <tr>
                        <td>
                           <label>First name:</label>
                           <input type="text" placeholder="Insert first name" name="first_name">
                        </td>
                        <td>
                           <label>Last name:</label>
                           <input type="text" placeholder="Insert last name" name="last_name">
                        </td>
                        <td>
                           <label>DOB:</label>
                           <input type="date" name="DOB">
                        </td>
                     </tr>
                  </table>
               </fieldset>
               <fieldset>
                  <legend><span>Profile information</span></legend>
                  <table>
                     <tr>
                        <td>
                           <label for="email">Email Address:</label>
                           <input type="email" name="student_email" id="student_email">
                        </td>
                     </tr>
                     <tr>
                        <td>
                           <label for="program">Program</label>
                           <select name="program" id="program">
                              <option>-- Please choose a program --</option>
                              <?php
                                 foreach ($programs_list as $p) {
                                    echo "<option>$p</option>";
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
                  </table>   
               </fieldset>
            </form>
         </section>
      </main>
   </body>
</html>