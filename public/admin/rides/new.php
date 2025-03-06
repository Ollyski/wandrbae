<?php require_once('../../../private/initialize.php');?>
<?php include(SHARED_PATH . '/member_header.php'); ?>
<?php $page_title = 'Create Ride'; ?>
<?php
 if(is_post_request()) {

  // Create record using post parameters
  $args = [];
  $args['ride_name'] = $_POST['ride_name'] ?? NULL;
  $args['username'] = $_POST['username'] ?? NULL;
  $args['start_time'] = $_POST['start_time'] ?? NULL;
  $args['end_time'] = $_POST['end_time'] ?? NULL;
  $args['location_name'] = $_POST['location_name'] ?? NULL;
  $args['street_address'] = $_POST['street_address'] ?? NULL;

  $ride = new Ride($args);
  $result = $ride->create();

  if($result === true) {
    $new_id = $ride->id;
    $_SESSION['message'] = 'The ride was created successfully.';
    redirect_to(url_for('/admin/rides/show.php?id=' . $new_id));
  } else {
    // show errors
  }

  } else {
  // display the form
  $ride = [];
  }

  ?>

  <?php $page_title = 'Create Ride'; ?>
  <?php include(SHARED_PATH . '/member_header.php'); ?>

  <div id="content">

  <a class="back-link" href="<?php echo url_for('/admin/rides/index.php'); ?>">&laquo; Back to List</a>

  <div class="ride new">
    <h1>Create Ride</h1>

    <?php // echo display_errors($errors); ?>

    <form action="<?php echo url_for('/admin/rides/new.php'); ?>" method="post">

      <?php include('form_fields.php'); ?>

      <div id="operations">
        <input type="submit" value="Create Ride" />
      </div>
    </form>

  </div>

  </div>

}

<?php include(SHARED_PATH . '/member_footer.php'); ?>