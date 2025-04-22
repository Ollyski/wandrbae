<?php
require_once('../../private/initialize.php');
echo "Current intended destination: " . ($_SESSION['intended_destination'] ?? 'Not set');

if ($user_session->is_admin()) {
  $redirect_to = $_SESSION['intended_destination'] ?? url_for('/admin/index.php');
  unset($_SESSION['intended_destination']);
  redirect_to($redirect_to);
}

$errors = [];
$username = '';
$password = '';

if (is_post_request()) {

  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';


  if (is_blank($username)) {
    $errors[] = "Username cannot be blank.";
  }
  if (is_blank($password)) {
    $errors[] = "Password cannot be blank.";
  }


  if (empty($errors)) {
    if ($user != false && $user->verify_password($password) && $user->is_admin()) {
      $redirect_to = $user_session->login($user);
      redirect_to($redirect_to);

      } else {
      
      if (isset($_SESSION['intended_destination']) && !empty($_SESSION['intended_destination'])) {
        $redirect_to = $_SESSION['intended_destination'];
        unset($_SESSION['intended_destination']);
      } else {
      $errors[] = "Log in was unsuccessful.";
      }
    }
  }
}

?>

<?php $page_title = 'Log in'; ?>
<?php include(SHARED_PATH . '/member_header.php'); ?>
<div id="content">
  <main role="main">
    <section>
      <h1>Log in</h1>

      <?php echo display_errors($errors); ?>
      <?php echo display_session_message(); ?>

      <form action="login.php" method="post">
        Username:<br />
        <input type="text" name="username" value="<?php echo h($username); ?>" /><br />
        Password:<br />
        <input type="password" name="password" value="" /><br />
        <input type="submit" name="submit" value="Submit" />
      </form>
    </section>
  </main>
</div>
<?php include(SHARED_PATH . '/public_footer.php'); ?>

</body>
</html>