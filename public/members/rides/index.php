<?php require_once('../../../private/initialize.php')?>

<?php

  $user_set = find_all_users();

  
  mysqli_free_result($user_set);
?>