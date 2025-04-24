<?php
require_once('../../../private/initialize.php');
require_admin_login();
include_header();
if (!isset($_GET['id'])) {
  redirect_to(url_for('/admin/rides/index.php'));
}
$id = $_GET['id'];

$ride = Ride::find_by_id($id);
if ($ride == false) {
  redirect_to(url_for('/admin/rides/index.php'));
}

if (is_post_request()) {
  // Delete ride
  $result = $ride->delete();
  
  if ($result === true) {
    $_SESSION['message'] = 'The ride was deleted successfully.';
    redirect_to(url_for('/admin/rides/index.php'));
  } else {
    // show errors
  }
} else {
  // Display confirmation page
}

?>

<?php $page_title = 'Delete Ride'; ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/admin/rides/index.php'); ?>">&laquo; Back to List</a>

  <div>
    <h1>Delete Ride</h1>
    <p>Are you sure you want to delete this ride?</p>
    <p><?php echo h($ride->ride_name); ?></p>

    <form action="<?php echo url_for('/admin/rides/delete.php?id=' . h(u($id))); ?>" method="post">
      <div id="operations">
        <input type="submit" name="commit" value="Delete Ride" />
      </div>
    </form>
  </div>

</div>

<?php include(SHARED_PATH . '/member_footer.php'); ?>