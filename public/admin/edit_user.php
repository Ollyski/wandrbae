<?php
require_once('../../private/initialize.php');
require_admin_login();
include_header();

$id = $_GET['id'] ?? '';

// If no ID is provided, redirect to the user list
if(empty($id)) {
  redirect_to(url_for('/admin/userlist.php'));
}

$user = User::find_by_id($id);

// If user not found, redirect to the user list
if(!$user) {
  redirect_to(url_for('/admin/userlist.php'));
}

if(is_post_request()) {
  // Save record using post parameters
  $args = $_POST['user'];
  
  // Ensure we're not changing the original role unless explicitly intended
  if(!isset($args['user_role_id']) && isset($user->user_role_id)) {
    $args['user_role_id'] = $user->user_role_id;
  }
  
  // Ensure we're preserving the is_user flag if it exists
  if(!isset($args['is_user']) && property_exists('User', 'is_user') && isset($user->is_user)) {
    $args['is_user'] = $user->is_user;
  }
  
  $user->merge_attributes($args);
  $result = $user->save();

  if($result === true) {
    $_SESSION['message'] = 'The user was updated successfully.';
    redirect_to(url_for('/admin/show_user.php?id=' . $id));
  }
}

?>

<?php $page_title = 'Edit User'; ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/admin/userlist.php'); ?>">&laquo; Back to User List</a>

  <div class="user edit">
    <h1>Edit User</h1>

    <?php echo display_errors($user->errors); ?>

    <form action="<?php echo url_for('/admin/edit_user.php?id=' . h(u($id))); ?>" method="post">

      <?php include('form_fields.php'); ?>

      <div id="operations">
        <input type="submit" value="Edit User" />
      </div>
    </form>

  </div>

</div>

<?php include(SHARED_PATH . '/public_footer.php'); ?>