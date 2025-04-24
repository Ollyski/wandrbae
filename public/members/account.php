<?php
require_once('../../private/initialize.php');
include_header();
// Ensure user is logged in
if(!$user_session->is_logged_in()) {
  $_SESSION['intended_destination'] = $_SERVER['REQUEST_URI'];
  $_SESSION['message'] = 'You need to log in first.';
  redirect_to(url_for('/login.php'));
}

// Get the current user's information
$user_id = $user_session->get_user_id();
$user = User::find_by_id($user_id);

if(!$user) {
  // User not found in database - redirect to login
  $user_session->logout();
  $_SESSION['message'] = 'There was a problem with your account. Please log in again.';
  redirect_to(url_for('/login.php'));
}

if(is_post_request()) {
  // Update account information
  $args = $_POST['user'];
  $user->merge_attributes($args);
  $result = $user->save();

  if($result === true) {
    $_SESSION['message'] = 'Account updated successfully.';
  }
}

?>

<?php $page_title = 'My Account'; ?>


<div id="content">
  <div class="member account">
    <h1>My Account</h1>
    
    <?php echo display_errors($user->errors); ?>
    
    <?php 
    if(isset($_SESSION['message'])) {
      echo "<div class=\"message\">" . h($_SESSION['message']) . "</div>";
      unset($_SESSION['message']);
    }
    ?>

    <div class="view-section">
      <h2>Account Information</h2>
      <dl>
        <dt>Name</dt>
        <dd><?php echo h($user->full_name()); ?></dd>
      </dl>
      <dl>
        <dt>Username</dt>
        <dd><?php echo h($user->username); ?></dd>
      </dl>
      <dl>
        <dt>Email</dt>
        <dd><?php echo h($user->email); ?></dd>
      </dl>
    </div>

    <div class="edit-section">
      <h2>Update Information</h2>
      <form action="<?php echo url_for('/account.php'); ?>" method="post">
        <?php include('user_form_fields.php'); ?>
        <div id="operations">
          <input type="submit" value="Update Account" />
        </div>
      </form>
    </div>
  </div>
</div>

<?php include(SHARED_PATH . '/member_footer.php'); ?>