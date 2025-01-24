<?php
   session_start();

   include("connection.php");
   $conn = new mysqli($server_name, $username, $password, $database_name);

   if ($conn->connect_error){
      die("Couldn't establish connection");
   }

   include("authentication.php");

   if($_SERVER['REQUEST_METHOD'] === 'POST'){
      if($isLoggedIn === true) {
         $post_content = $_POST['new_post'];
   
         $sql = "INSERT INTO users_posts(student_id, new_post) VALUES($student_id, '$post_content')";
         $result = $conn->query($sql);
      } else {
         echo "<script>alert('Please register before posting!');</script>";
      }
   }

   if($isLoggedIn === true) {
      $sql = "SELECT * FROM users_posts WHERE student_id=$student_id ORDER BY post_date DESC LIMIT 5;";
      $posts_result = $conn->query($sql);
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
      <header>
         <h1><strong>SYSCX</strong></h1>
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
            <form method="post" action = "">
               <fieldset>
                  <legend>New Post</legend>
                  <table class="post-table">
                     <tr>
                        <td><textarea name="new_post" id="new_post" cols="90" rows="10"
                           placeholder="What is happening?! (max 280 char)" maxlength="280"></textarea>
                        </td>
                     </tr>
                     <tr>
                        <td><input type = "submit" id="post_button" value="POST">
                           <input type = "reset" id="reset_button" value="RESET">
                        </td>
                     </tr>
                  </table>
               </fieldset>
            </form>
         </section>
         <section>
            <?php
               if ($posts_result) {
                  while($post = $posts_result->fetch_assoc())
                  {
                     echo "<div>";
                     echo "<label><strong>->Post " . $post['post_ID'] . "</strong></label><br>";
                     echo $post['new_post'];
                     echo "</div>";
                  }
               }
            ?>
            <!-- <div>
               <label><strong>->Post 1</strong></label><br>
                  Hi Ravens!!! Have a cool talent? Interested in showcasing it to the community? Join us in the Raven's Roost this
                  Friday Feb 16th @ 9pm for an Open Mic & Talent. Email us <a href="">infocarleton.sysc.ca</a> for more information. Cheers. <br>
               <img id="Talentshow"src="images/TalentShow.jpeg" alt="Talent Show poster" title="Talent Show">
            </div>
            <br>
            <div>
                  <label><strong>->Post 2</strong></label><br>
                     Hello students, this announcement is aimed at informing all you that you should all register beforehand and
                     registration closes on Feb 16th @12pm. Email us <a href="">infocarleton.sysc.ca</a> for more information. Cheers.
            </div>
            <br>
            <div>
               <label><strong>->Post 3</strong></label><br>
                     Dear SYSC students, <br>
                     Only <strong> 50 </strong> seats of available to compete. Hurry up and secure your seats now!
                     Email us <a href="khooshavbundhoo@cmail.carleton.ca">khooshavbundhoo@cmail.carleton.ca</a> for more information. Cheers.
            </div> -->
         </section>

         <div class="info-box">
            <h2>Khooshav Bundhoo</h2>
            <img id="avatar00" src="images/img_avatar3.png" alt="Avatar Image" title="Avatar">
            <p> <strong>Email:</strong> <a href="khooshavbundhoo@cmail.carleton.ca">khooshavbundhoo@cmail.carleton.ca</a></p> <br>
            <p><strong>Program:</strong> Software Engineering</p>
         </div>
      </main>
   </body>
</html>

