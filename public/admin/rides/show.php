<?php
if(isset($_SESSION['message'])) {
  echo "<div class='message'>" . $_SESSION['message'] . "</div>";
  unset($_SESSION['message']); // Clear the message after displaying it
}
?>