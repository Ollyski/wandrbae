<?php require_once('../../private/initialize.php'); 
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

?>

<?php $page_title = 'Show User: ' . h($user->full_name()); ?>

<main role="main">
  <section>
    <div class="user show">
      <h1>User: <?php echo h($user->full_name()); ?></h1>
      <a href="<?php echo url_for('/admin/userlist.php'); ?>" class="btn">&laquo; Back to User List</a>

      <?php
      // Display session message if exists
      if (isset($_SESSION['message'])) {
        echo "<div class='message'>" . $_SESSION['message'] . "</div>";
        // Clear the message after displaying
        $_SESSION['message'] = null;
      }
      ?>

      <div class="attributes">
        <dl>
          <dt>First name</dt>
          <dd><?php echo h($user->first_name); ?></dd>
        </dl>
        <dl>
          <dt>Last name</dt>
          <dd><?php echo h($user->last_name); ?></dd>
        </dl>
        <dl>
          <dt>Email</dt>
          <dd><?php echo h($user->email); ?></dd>
        </dl>
        <dl>
          <dt>Username</dt>
          <dd><?php echo h($user->username); ?></dd>
        </dl>
        <dl>
          <dt>Role</dt>
          <dd><?php echo h($user->get_role_name()); ?></dd>
        </dl>
      </div>

      <div class="actions">
        <a href="<?php echo url_for('/admin/edit_user.php?id=' . h(u($user->user_id))); ?>" class="btn">Edit User</a>
        <a href="<?php echo url_for('/admin/delete_user.php?id=' . h(u($user->user_id))); ?>" class="btn">Delete User</a>
      </div>
    </div>
  </section>
</main>

<?php include(SHARED_PATH . '/public_footer.php'); ?>