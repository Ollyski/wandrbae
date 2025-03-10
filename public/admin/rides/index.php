<?php require_once('../../../private/initialize.php'); ?>

<?php include(SHARED_PATH . '/member_header.php'); ?>

<div id="main">

  <ul id="menu">
    <li><a href="<?php echo url_for('/ride.php'); ?>">View our rides</a></li>
  </ul>

<?php $page_title = 'Rides'; ?>
<div id="main">
  <div id="page">
    <div class="intro">
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
        <td><?php echo h($ride->username); ?></td>
        <td><?php echo h($ride->start_time); ?></td>
        <td><?php echo h($ride->end_time); ?></td>
        <td><?php echo h($ride->location_name); ?></td>
        <td><?php echo h($ride->street_address); ?></td>
        <td><a href="detail.php?id=<?php echo $ride->ride_id; ?>">View</a></td>
        <td><a class="action" href="<?php echo url_for('/admin/rides/edit.php?id=' . h(u($ride->ride_id))); ?>">Edit</a></td>
        <td><a class="action" href="<?php echo url_for('/admin/rides/delete.php?id=' . h(u($ride->ride_id))); ?>">Delete</a></td>
      </tr>
      <?php } ?>
    </table>
    <?php
    // Using find_by_sql to get the ride objects
    $sql = "SELECT * FROM ride";
    $ride_objects = Ride::find_by_sql($sql);
    
    // Check if any rides were found
    if(!empty($ride_objects)) {
      $first_ride = $ride_objects[0];
    } else {
      echo "No rides found.";
    }
    ?>
  </div>
</div>
    
</div>


<?php include(SHARED_PATH . '/member_footer.php'); ?>
