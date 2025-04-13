<?php require_once('../private/initialize.php'); ?>
<?php $page_title = 'Rides'; ?>
<?php include(SHARED_PATH . '/public_header.php'); ?>
<div id="main">
  <div id="page">
    <div>
      <h2>Wander with your baes!</h2>
      <p>Sign up for a ride!</p>
      <?php echo display_session_message(); ?>
    </div>

    <div>
      <?php
      $rides = Ride::find_all();

      foreach ($rides as $ride) {
        // Get the number of current participants
        $current_participants = RideParticipant::count_participants_for_ride($ride->ride_id);
      ?>
        <div>
          <div>
            <h3><?php echo h($ride->ride_name); ?></h3>
            <div>Created by: <?php echo h($ride->username); ?></div>
          </div>

          <div>
            <div>
              <div>⏰</div>
              <div>
                <span>Starts At</span>
                <?php echo h($ride->start_time); ?>
              </div>
            </div>

            <div>
              <div>🏁</div>
              <div>
                <span>Ends At</span>
                <?php echo h($ride->end_time); ?>
              </div>
            </div>

            <div>
              <div>📍</div>
              <div>
                <span>Location</span>
                <?php echo h($ride->location_name); ?>
              </div>
            </div>

            <div>
              <div>🏠</div>
              <div>
                <span>Address</span>
                <?php echo h($ride->street_address); ?>
              </div>
            </div>

            <div>
              <div>👥</div>
              <div>
                <span>Participants</span>
                <?php echo $current_participants; ?> rider(s)
              </div>
            </div>
          </div>

          <div>
            <a href="detail.php?id=<?php echo $ride->ride_id; ?>">View Details</a>
            <?php if ($member_session->is_logged_in()) { ?>
              <a href="<?php echo url_for('members/rides/signup.php?ride_id=' . $ride->ride_id); ?>">Sign Up</a>
            <?php } else { ?>
              <a href="<?php echo url_for('login.php'); ?>">Login to Sign Up</a>
            <?php } ?>
          </div>
        </div>
      <?php } ?>
    </div>

    <?php
    // Using find_by_sql to get the ride objects
    $sql = "SELECT * FROM ride";
    $ride_objects = Ride::find_by_sql($sql);

    // Check if any rides were found
    if (empty($ride_objects)) {
      echo "<div style='text-align: center; padding: 2rem;'>No rides found.</div>";
    }
    ?>
  </div>
</div>
<?php include(SHARED_PATH . '/public_footer.php'); ?>