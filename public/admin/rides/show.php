<?php require_once('../../../private/initialize.php'); 
$page_title = 'Show Ride';
require_admin_login();
include_header();

// Get ride ID and find the ride
$id = $_GET['id'] ?? '1';
$ride = Ride::find_by_id($id);

if (!$ride) {
  $_SESSION['message'] = "Ride not found.";
  redirect_to(url_for('/members/rides/index.php'));
}

// Set page title with ride name
$page_title = 'Show Ride: ' . h($ride->ride_name);

// Display session message if exists
if (isset($_SESSION['message'])) {
  echo "<div class='message'>" . $_SESSION['message'] . "</div>";
  // Clear the message after displaying
  $_SESSION['message'] = null;
}
?>

<main role="main">
  <section>
    <div class="ride show">
      <h1>Ride: <?php echo h($ride->ride_name); ?></h1>
      <p>View all the details for this ride below.</p>

      <div class="attributes">
        <dl>
          <dt>Created By</dt>
          <dd><?php echo h($ride->username); ?></dd>
        </dl>
        <dl>
          <dt>Route</dt>
          <dd><?php 
            // Just display the route ID since Route class is not available
            echo h($ride->route_id); 
          ?></dd>
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

      <div class="actions">
        <a href="<?php echo url_for('/admin/rides/edit.php?id=' . h(u($ride->ride_id))); ?>" class="btn">Edit Ride</a>
        <a href="<?php echo url_for('/admin/rides/index.php'); ?>" class="btn">&laquo; Back to List</a>
      </div>
    </div>
  </section>
</main>

<?php include(SHARED_PATH . '/public_footer.php'); ?>