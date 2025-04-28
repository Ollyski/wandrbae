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
  // Delete user
  $result = $user->delete();
  
  if($result === true) {
    $_SESSION['message'] = 'The user was deleted successfully.';
    redirect_to(url_for('/admin/userlist.php'));
  }
}

?>

<?php $page_title = 'Delete User'; ?>

<main role="main">
  <section>
    <div class="user delete">
      <h1>Delete User</h1>
      <p>Are you sure you want to delete this user?</p>
      <a href="<?php echo url_for('/admin/userlist.php'); ?>" class="btn">&laquo; Back to User List</a>
      
      <?php
      // Display session message if exists
      if (isset($_SESSION['message'])) {
        echo "<div class='message'>" . $_SESSION['message'] . "</div>";
        // Clear the message after displaying
        $_SESSION['message'] = null;
      }
      ?>
      
      <p class="item"><?php echo h($user->full_name()); ?></p>

      <form action="<?php echo url_for('/admin/delete_user.php?id=' . h(u($id))); ?>" method="post">
        <div id="operations">
          <input type="submit" name="commit" class="btn" value="Delete User" />
        </div>
      </form>
      
      <div class="actions">
        <a href="<?php echo url_for('/admin/show_user.php?id=' . h(u($id))); ?>" class="btn">View User</a>
      </div>
    </div>
  </section>
</main>

<?php include(SHARED_PATH . '/public_footer.php'); ?>