<?php


 function find_all_users() {
  global $db;

  $sql = "SELECT * FROM user ";
  $sql .= "ORDER BY username ASC";
  $result = mysqli_query($db, $sql);
  return $result;
 }
?>