<?php require_once('../private/initialize.php'); ?>
<?php $page_title = 'Rides'; ?>
<?php include(SHARED_PATH . '/public_header.php'); ?>
<div id="main">
  <div id="page">
    <div class="intro">
      <img class="inset" src="<?php echo url_for('/images/') ?>" />
      <h2>Wander with your baes!</h2>
      <p>Sign up for a ride!</p>
    </div>
    <table id="Rides">
      <tr>
        <th>Ride Name</th>
        <th>Created By</th>
        <th>Starts At</th>
        <th>Ends At</th>
        <th>Where</th>
        <th>Location</th>
        <th>Address</th>
        <th>&nbsp;</th>
      </tr>
<?php
$rides = Ride::find_all();
?>
      <?php foreach($rides as $ride) { ?>
      <tr>
        <td><?php echo h($ride->ride_name); ?></td>
        <td><?php echo h($ride->created_by); ?></td>
        <td><?php echo h($ride->start_time); ?></td>
        <td><?php echo h($ride->end_time); ?></td>
        <td><?php echo h($ride->location_name); ?></td>
        <td><?php echo h($ride->street_address); ?></td>
        <td><a href="detail.php?id=<?php echo $ride->ride_id; ?>">View</a></td>
      </tr>
      <?php } ?>
    </table>
    <?php
    // Using find_by_sql to get the ride objects
    $sql = "SELECT * FROM ride LIMIT 1";
    $ride_objects = Ride::find_by_sql($sql);
    
    // Check if any rides were found
    if(!empty($ride_objects)) {
      $first_ride = $ride_objects[0];
      echo "Featured Ride: " . h($first_ride->ride_name);
    } else {
      echo "No rides found.";
    }
    ?>
  </div>
</div>
<?php include(SHARED_PATH . '/public_footer.php'); ?>