<?php require_once('../../../private/initialize.php')?>

<?php

  $user_set = find_all_users();

  <?php
    mysqli_free_result($subject_set);
  ?>