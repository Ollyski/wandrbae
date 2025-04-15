<?php require_once('../private/initialize.php'); ?>

<?php
echo "Intended destination: " . ($_SESSION['intended_destination'] ?? 'none') . "<br>";
$errors = [];
$username = '';
$password = '';

if (is_post_request()) {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  // Validations
  if (is_blank($username)) {
    $errors[] = "Username cannot be blank.";
  }
  if (is_blank($password)) {
    $errors[] = "Password cannot be blank.";
  }

  // If no errors, try to login
  if (empty($errors)) {
    $member = Member::find_by_username($username);
    if ($member != false && $member->verify_password($password)) {
      $destination = $member_session->login($member);
      echo "Redirecting to: " . $destination;
      exit;
      //*redirect_to($destination);//
    } else {
      // Username found, but password does not match
      $errors[] = "Log in was unsuccessful.";
    }
  }
}
?>

<?php $page_title = 'Log in'; ?>
<?php include(SHARED_PATH . '/public_header.php'); ?>

<div id="content">
  <div id="main">
    <h1>Log in</h1>

    <?php echo display_errors($errors); ?>
    <?php echo display_session_message(); ?>

    <form action="<?php echo url_for('/login.php'); ?>" method="post">
      <div>
        <label for="username">Username:</label>
        <input type="text" name="username" value="<?php echo h($username); ?>" />
      </div>
      <div>
        <label for="password">Password:</label>
        <input type="password" name="password" value="" />
      </div>
      <div>
        <input type="submit" name="submit" value="Log in" />
      </div>
    </form>

    <p>Don't have an account? <a href="<?php echo url_for('/register.php'); ?>">Register here</a>.</p>
  </div>
</div>

<?php include(SHARED_PATH . '/public_footer.php'); ?>