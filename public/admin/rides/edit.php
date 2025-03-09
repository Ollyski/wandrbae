<?php
require_once('../../../private/initialize.php');

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

  $ride = [];

  $result = false;
  if ($result === true) {
    $_SESSION['message'] = 'The ride was updated successfully.';
    redirect_to(url_for('/admin/rides/show.php?id=' . $id));
  } else {
    // show errors
  }
} else {


  $ride = Ride::find_by_id($id);
  if ($ride == false) {
    redirect_to(url_for('/members/rides/index.php'));
  }
}

?>

<?php $page_title = 'Edit Ride'; ?>
<?php include(SHARED_PATH . '/member_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/members/rides/index.php'); ?>">&laquo; Back to List</a>

  <div>
    <h1>Edit Ride</h1>


    <form action="<?php echo url_for('/admin/rides/form_fields.php?id=' . h(u($id))); ?>" method="post">

      <?php include('form_fields.php'); ?>

      <div id="operations">
        <input type="submit" value="Edit Ride" />
      </div>
    </form>

  </div>

</div>

<?php include(SHARED_PATH . '/member_footer.php'); ?>