<?php require_once('../../../private/initialize.php'); ?>
<?php
if (isset($_SESSION['message'])) {
  echo "<div class='message'>" . $_SESSION['message'] . "</div>";
}
?>


<?php

$id = $_GET['id'] ?? '1';

$ride = Ride::find_by_id($id);
if (!$ride) {
  $_SESSION['message'] = "Ride not found.";
  redirect_to(url_for('/members/rides/index.php'));
}

$page_title = 'Show Ride: ' . h($ride->ride_name());
?>


<?php $page_title = 'Show Ride: ' . h($ride->ride_name()); ?>
<?php include(SHARED_PATH . '/member_header.php'); ?>

<div id="content">

  <a href="<?php echo url_for('/ride.php'); ?>">&laquo; Back to List</a>

  <div>

    <h1>Ride: <?php echo h($ride->ride_name()); ?></h1>

    <div class="attributes">
      <dl>
        <dt>Created By</dt>
        <dd><?php echo h($ride->created_by); ?></dd>
      </dl>
      <dl>
        <dt>Route</dt>
        <dd><?php echo h($ride->route_id); ?></dd>
      </dl>
      <dl>
        <dt>Start Time</dt>
        <dd><?php echo h($ride->start_time); ?></dd>
      </dl>
      <dl>
        <dt>End Time</dt>
        <dd><?php echo h($ride->end_time); ?></dd>
      </dl>
      <dl>
        <dt>Location</dt>
        <dd><?php echo h($ride->location_name); ?></dd>
      </dl>
      <dl>
        <dt>Street Address</dt>
        <dd><?php echo h($ride->street_address); ?></dd>
      </dl>
      <dl>
        <dt>City</dt>
        <dd><?php echo h($ride->city); ?></dd>
      </dl>
      <dl>
        <dt>State</dt>
        <dd><?php echo h($ride->state); ?></dd>
      </dl>
      <dl>
        <dt>Zip Code</dt>
        <dd><?php echo h($ride->zip_code); ?></dd>
      </dl>
    </div>

  </div>

</div>