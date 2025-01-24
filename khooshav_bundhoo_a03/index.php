<?php
   session_start();

   include("common/authentication.php");

   if($_SERVER['REQUEST_METHOD'] === 'POST'){
      if($isLoggedIn) {
         $post_content = $_POST['new_post'];
   
         $sql = "INSERT INTO users_posts(student_id, new_post) VALUES(?, ?)";
         $statement = $conn->prepare($sql);
         $statement->bind_param("is", $student_id, $post_content);
         $statement->execute();
      } else {
         echo "<script>alert('Please register before posting!');</script>";
      }
   }

   if($isLoggedIn) {
      $sql = "SELECT * FROM users_posts ORDER BY post_date DESC LIMIT 10;";
      $statement = $conn->prepare($sql);
      $statement->execute();
      $posts_result = $statement->get_result();
   } else {
      header("Location: login.php");
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
            </section>
         </main>
         <?php include("common/user_info.php"); ?>
      </div>

   </body>
</html>
