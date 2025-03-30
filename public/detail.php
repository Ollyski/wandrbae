<?php require_once('../private/initialize.php');
require_login(); ?>
<?php $page_title = 'Rides'; ?>
<?php include(SHARED_PATH . '/public_header.php'); ?>


<?php

$id = $_GET['id'] ?? false;

if (!$id) {
  redirect_to('ride.php');
}

$ride = Ride::find_by_id($id);
?>

<div id="main">

  <a href="ride.php">Back to Rides</a>

  <div id="page">

    <div class="detail">
      <dl>
        <dt>Ride Name</dt>
        <dd><?php echo h($ride->ride_name); ?></dd>
      </dl>
      <dl>
        <dt>Created By</dt>
        <dd><?php echo h($ride->username); ?></dd>
      </dl>
      <dl>
        <dt>Starts At</dt>
        <dd><?php echo h($ride->start_time); ?></dd>
      </dl>
      <dl>
        <dt>Ends At</dt>
        <dd><?php echo h($ride->end_time); ?></dd>
      </dl>
      <dl>
        <dt>Where</dt>
        <dd><?php echo h($ride->location_name); ?></dd>
      </dl>
      <dl>
        <dt>Location</dt>
        <dd><?php echo h($ride->street_address); ?></dd>
      </dl>
    </div>

  </div>

</div>

<?php
$id = $_GET['id'] ?? false;
if (!$id) {
  redirect_to('ride.php');
}
$ride = Ride::find_by_id($id);

// Add this check
if ($ride === false) {
  echo "No ride found with ID: " . h($id);
  exit();
}
?>

<?php include(SHARED_PATH . '/public_footer.php'); ?>