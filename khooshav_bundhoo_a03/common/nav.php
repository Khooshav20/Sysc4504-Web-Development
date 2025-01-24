<?php
  include("common/authentication.php");
?>

<nav>
  <ul>
    <li>
      <a href="index.php">Home</a>
    </li>
    <?php if($isLoggedIn): ?>
    <li>
      <a href="profile.php">Profile</a>
    </li>
      <?php if($isAdmin): ?>
      <li>
        <a href="user_list.php">Users</a>
      </li>
      <?php endif; ?>
    <li>
      <a href="logout.php">Logout</a>
    </li>
    <?php else: ?>
    <li>
      <a href="login.php">Login</a>
    </li>
    <li>
      <a href="register.php">Register</a>
    </li>
    <?php endif; ?>
  </ul>
</nav>