<?php
require_once('../../private/initialize.php');
require_admin_login();
include_header();

if(is_post_request()) {
  // Create record using post parameters
  $args = $_POST['user'];
  $user = new User($args);
  $result = $user->save();

  if($result === true) {
    $new_id = $user->user_id;
    $_SESSION['message'] = 'The user was created successfully.';
    redirect_to(url_for('/admin/show_user.php?id=' . $new_id));
  }
} else {
  // Display a blank form
  $user = new User();
  
  // Set default values
  // Only set is_user if it's a property that exists in the User class
  if(property_exists('User', 'is_user')) {
    $user->is_user = 1;  // Active by default
  }
}

?>

<?php $page_title = 'Create User'; ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/admin/userlist.php'); ?>">&laquo; Back to User List</a>

  <div class="user new">
    <h1>Create User</h1>

    <?php echo display_errors($user->errors); ?>

    <form action="<?php echo url_for('/admin/new_user.php'); ?>" method="post">

      <?php include('form_fields.php'); ?>

      <div id="operations">
        <input type="submit" value="Create User" />
      </div>
    </form>

  </div>

</div>

<?php include(SHARED_PATH . '/public_footer.php'); ?>