<?php
require_once('../../private/initialize.php');

$errors = [];
$username = '';
$password = '';

if(is_post_request()) {

  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';


  if(is_blank($username)) {
    $errors[] = "Username cannot be blank.";
  }
  if(is_blank($password)) {
    $errors[] = "Password cannot be blank.";
  }


  if(empty($errors)) {
    $admin = Admin::find_by_username($username);
  
    // Add this debugging code
    if($admin != false) {
      // Debug output
      echo "User found in database<br>";
      echo "Username: " . $admin->username . "<br>";
      echo "Has hashed_password: " . (isset($admin->hashed_password) ? "Yes" : "No") . "<br>";
      
      // This will help debug the password verification
      var_dump($admin);
      var_dump(password_verify($password, $admin->hashed_password));
      exit(); // Stop execution here for debugging
    }
  
    if($admin != false && $admin->verify_password($password)) {
      
      $session->login($admin);
      redirect_to(url_for('/members/index.php'));
    } else {
      
      $errors[] = "Log in was unsuccessful.";
    }
  }

}

?>

<?php $page_title = 'Log in'; ?>
<?php include(SHARED_PATH . '/member_header.php'); ?>

<div id="content">
  <h1>Log in</h1>

  <?php echo display_errors($errors); ?>

  <form action="login.php" method="post">
    Username:<br />
    <input type="text" name="username" value="<?php echo h($username); ?>" /><br />
    Password:<br />
    <input type="password" name="password" value="" /><br />
    <input type="submit" name="submit" value="Submit"  />
  </form>

</div>

<?php include(SHARED_PATH . '/public_footer.php'); ?>
