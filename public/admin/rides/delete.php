<?php
require_once('../../../private/initialize.php');
$page_title = 'Delete Ride';
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
    // Show errors
    $errors = $ride->errors ?? ["An error occurred during deletion."];
  }
} else {
  // Display confirmation page
  $errors = [];
}

// Display session message if exists
if (isset($_SESSION['message'])) {
  echo "<div class='message'>" . $_SESSION['message'] . "</div>";
  // Clear the message after displaying
  $_SESSION['message'] = null;
}
?>

<main role="main">
  <section>
    <div class="ride delete">
      <h1>Delete Ride</h1>
      <p>Are you sure you want to delete this ride?</p>
      <p class="item-name"><?php echo h($ride->ride_name); ?></p>

      <?php echo display_errors($errors); ?>

      <form action="<?php echo url_for('/admin/rides/delete.php?id=' . h(u($id))); ?>" method="post">
        <div id="operations">
        <a href="<?php echo url_for('/admin/rides/index.php'); ?>" class="btn">&laquo; Back to List</a>
        <input type="submit" name="commit" value="Delete Ride" class="btn" />
        </div>
      </form>
    </div>
  </section>
</main>

<?php include(SHARED_PATH . '/public_footer.php'); ?>