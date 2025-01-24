<?php
  session_start();

  include("common/authentication.php");
  include("common/avatars.php");

  if ($isLoggedIn) {
    $sql = "SELECT * FROM users_info
      INNER JOIN users_program ON users_info.student_id = users_program.student_id
      INNER JOIN users_avatar ON users_info.student_id = users_avatar.student_id
      WHERE users_info.student_id=?;";
    $statement = $conn->prepare($sql);
    $statement->bind_param("i", $student_id);
    $statement->execute();
    $user_info_result = $statement->get_result();
    $row = $user_info_result->fetch_assoc();
  
    $student_email = $row['student_email'];
    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
    $program = $row['Program'];
    $avatar = (int) $row['avatar'];
    $avatar = $avatar === 0 ? 1 : $avatar;
 }

  $conn->close();
?>

<div class="info-box">
  <?php if($isLoggedIn): ?>
  <h2><?php echo "$first_name $last_name"; ?></h2>
  <img id="avatar00" src="<?php echo $avatars[$avatar]; ?>" alt="Avatar Image" title="Avatar">
  <p> <strong>Email:</strong> <a href="mailto:<?php echo $student_email; ?>"><?php echo $student_email; ?></a></p> <br>
  <p><strong>Program:</strong> <?php echo $program; ?></p>
  <?php endif; ?>
</div>