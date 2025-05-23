<?php
require_once('../../private/initialize.php');
require_admin_login();
include_header();

if(!isset($_GET['id'])) {
  redirect_to(url_for('/admin/index.php'));
}
$id = $_GET['id'];
$user = User::find_by_id($id);
if($user == false) {
  redirect_to(url_for('/admin/index.php'));
}

  if(is_post_request()) {
    $result = $user->delete();
    $_SESSION['message'] = 'The admin was deleted successfully.';
    redirect_to(url_for('/admin/index.php'));

  } else {
}
?>

<?php $page_title = 'Delete Admin'; ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/admin/index.php'); ?>">&laquo; Back to List</a>

  <div class="admin delete">
    <h1>Delete Admin</h1>
    <p>Are you sure you want to delete this admin?</p>
    <p class="item"><?php echo h($user->full_name()); ?></p>

    <form action="<?php echo url_for('/admin/delete.php?id=' . h(u($id))); ?>" method="post">
      <div id="operations">
        <input type="submit" name="commit" value="Delete Admin" />
      </div>
    </form>
  </div>

</div>

<?php include(SHARED_PATH . '/member_footer.php'); ?>
