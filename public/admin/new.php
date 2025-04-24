<?php

require_once('../../private/initialize.php');
require_admin_login();
include_header();

if(is_post_request()) {

  
  $args = $_POST['admin'];
  $user = new User($args);
  $result = $user->save();

  if($result === true) {
    $new_id = $user->user_id;
    $_SESSION['message'] = 'The admin was created successfully.';
    redirect_to(url_for('/admin/show.php?id=' . $new_id));
  } else {
  }

} else {

  $user = new User;
}

?>

<?php $page_title = 'Create Admin'; ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/admin/index.php'); ?>">&laquo; Back to List</a>

  <div class="admin new">
    <h1>Create Admin</h1>

    <?php echo display_errors($user->errors); ?>

    <form action="<?php echo url_for('/admin/new.php'); ?>" method="post">

      <?php include('form_fields.php'); ?>

      <div id="operations">
        <input type="submit" value="Create Admin" />
      </div>
    </form>

  </div>

</div>

<?php include(SHARED_PATH . '/member_footer.php'); ?>
