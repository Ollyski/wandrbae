<?php
require_once('../private/initialize.php');
include_header();
$errors = [];
$username = '';
$password = '';

if(is_post_request()) {
  
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  // Validations
  if(is_blank($username)) {
    $errors[] = "Username cannot be blank.";
  }
  if(is_blank($password)) {
    $errors[] = "Password cannot be blank.";
  }

  // If no errors, try to login
  if(empty($errors)) {
    $user = User::find_by_username($username);
    
    if($user && $user->verify_password($password)) {
      // Password matches
      $user_session->login($user);
      redirect_to(url_for('/index.php'));
    } else {
      // Username was not found or password did not match
      $errors[] = "Login was unsuccessful.";
    }
  }
}

?>

<?php $page_title = 'Log in'; ?>


<div id="main">
  <section>
    <h1>Log in</h1>

    <?php echo display_errors($errors); ?>

    <form action="<?php echo url_for('/login.php'); ?>" method="post">
      <dl>
        <dt>Username</dt>
        <dd><input type="text" name="username" value="<?php echo h($username); ?>" /></dd>
      </dl>

      <dl>
        <dt>Password</dt>
        <dd><input type="password" name="password" value="" /></dd>
      </dl>

      <div id="operations">
        <input type="submit" value="Log in" class="btn" />
      </div>
    </form>

    <p>Don't have an account? <a class="btn" href="<?php echo url_for('/join.php'); ?>">Sign up now</a>.</p>
  </section>
</div>

<?php include(SHARED_PATH . '/public_footer.php'); ?>