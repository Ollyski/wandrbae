<?php
require_once('../../../private/initialize.php');
$page_title = 'Edit Ride';
require_admin_login();
include_header();

if (!isset($_GET['id'])) {
  redirect_to(url_for('/admin/rides/index.php'));
}
$id = $_GET['id'];

if (is_post_request()) {
  // Save record using post parameters
  $args = [];
  $args['ride_name'] = $_POST['ride_name'] ?? NULL;
  $args['created_by'] = $_POST['created_by'] ?? NULL;
  $args['route_id'] = $_POST['route_id'] ?? NULL;
  $args['start_time'] = $_POST['start_time'] ?? NULL;
  $args['end_time'] = $_POST['end_time'] ?? NULL;
  $args['location_name'] = $_POST['location_name'] ?? NULL;
  $args['street_address'] = $_POST['street_address'] ?? NULL;
  $args['city'] = $_POST['city'] ?? NULL;
  $args['state'] = $_POST['state'] ?? NULL;
  $args['zip_code'] = $_POST['zip_code'] ?? NULL;

  $ride = Ride::find_by_id($id);
  $result = $ride->merge_attributes($args);
  $result = $ride->save();

  if ($result === true) {
    $_SESSION['message'] = 'The ride was updated successfully.';
    redirect_to(url_for('/admin/rides/show.php?id=' . $id));
  } else {
    // Show errors
    $errors = $ride->errors;
  }
} else {
  $ride = Ride::find_by_id($id);
  if ($ride == false) {
    redirect_to(url_for('/members/rides/index.php'));
  }
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
    <div class="ride edit">
      <h1>Edit Ride</h1>
      <p>Update the details for this ride.</p>
      <a href="<?php echo url_for('/admin/rides/index.php'); ?>" class="btn">&laquo; Back to List</a>
      <?php echo display_errors($errors); ?>

      <form action="<?php echo url_for('/admin/rides/edit.php?id=' . h(u($id))); ?>" method="post">
        <?php include('form_fields.php'); ?>
        
        <div id="operations">
          <input type="submit" class="btn" value="Update Ride" />
        </div>
      </form>

      <div class="actions">
        <a href="<?php echo url_for('/admin/rides/show.php?id=' . h(u($id))); ?>" class="btn">View Ride</a>
      </div>
    </div>
  </section>
</main>

<?php include(SHARED_PATH . '/public_footer.php'); ?>