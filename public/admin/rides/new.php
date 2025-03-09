<?php require_once('../../../private/initialize.php');?>
<?php $page_title = 'Create Ride'; ?>
<?php 
$errors = []; 

if(is_post_request()) {
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
  
  if(empty($args['state'])) {
    $errors[] = "State is required.";
  }
  
  if(empty($args['route_id'])) {
    $errors[] = "Route is required.";
  }

  if(empty($errors)) {
    $ride = new Ride($args);
    $result = $ride->create();
  }
  if($result === true) {
    $new_id = $ride->id;
    $_SESSION['message'] = 'The ride was created successfully.';
    redirect_to(url_for('/admin/rides/show.php?id=' . $new_id));
  } else {
    $errors[] = "Database error: " . $result;
  }
} else {
  $ride = new stdClass();
}
?>

<?php include(SHARED_PATH . '/member_header.php'); ?>

<div id="content">
  <a class="back-link" href="<?php echo url_for('/admin/rides/index.php'); ?>">&laquo; Back to List</a>
  <div class="ride new">
    <h1>Create Ride</h1>
    <?php echo display_errors($errors); ?>
    <form action="<?php echo url_for('/admin/rides/new.php'); ?>" method="post">
      <?php include('form_fields.php'); ?>
      <div id="operations">
        <input type="submit" value="Create Ride" />
      </div>
    </form>
  </div>
</div>

<?php include(SHARED_PATH . '/member_footer.php'); ?>