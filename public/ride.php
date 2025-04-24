<?php require_once('../private/initialize.php');
include_header(); ?>
<?php $page_title = 'Rides'; ?>

<div id="main">
  <div id="page">
    <div class="intro">
      <h2>Wander with your baes!</h2>
      <p>Sign up for a ride!</p>
      <?php echo display_session_message(); ?>
    </div>

    <div class="rides-container">
      <?php
      $rides = Ride::find_all();
      $user_id = $user_session->is_logged_in() ? $user_session->get_user_id() : false;
      foreach ($rides as $ride) {
        $current_participants = RideParticipant::count_participants_for_ride($ride->ride_id);
      ?>
        <div class="ride-card">
          <div class="ride-header">
            <h3 class="ride-name"><?php echo h($ride->ride_name); ?></h3>
            <div class="ride-creator">Created by: <?php echo h($ride->username); ?></div>
          </div>

          <div class="ride-info">
            <div class="ride-detail">
              <div class="ride-detail-icon">⏰</div>
              <div class="ride-detail-text">
                <span class="ride-detail-label">Starts At</span>
                <?php echo h($ride->start_time); ?>
              </div>
            </div>

            <div class="ride-detail">
              <div class="ride-detail-icon">🏁</div>
              <div class="ride-detail-text">
                <span class="ride-detail-label">Ends At</span>
                <?php echo h($ride->end_time); ?>
              </div>
            </div>

            <div class="ride-detail">
              <div class="ride-detail-icon">📍</div>
              <div class="ride-detail-text">
                <span class="ride-detail-label">Location</span>
                <?php echo h($ride->location_name); ?>
              </div>
            </div>

            <div class="ride-detail">
              <div class="ride-detail-icon">🏠</div>
              <div class="ride-detail-text">
                <span class="ride-detail-label">Address</span>
                <?php echo h($ride->street_address); ?>
              </div>
            </div>

            <div class="ride-detail">
              <div class="ride-detail-icon">👥</div>
              <div class="ride-detail-text">
                <span class="ride-detail-label">Participants</span>
                <?php echo $current_participants; ?> rider(s)
              </div>
            </div>
          </div>

          <div class="ride-footer">
            <a href="detail.php?id=<?php echo $ride->ride_id; ?>" class="btn">View Details</a>
            <?php if ($user_session->is_logged_in()) { 
               $is_signed_up = RideParticipant::is_user_signed_up($user_id, $ride->ride_id);
               if ($is_signed_up) { ?>
                <a href="<?php echo url_for('/members/rides/cancel_signup.php?ride_id=' . $ride->ride_id); ?>" class="btn">Cancel Ride</a> 
                <?php } else { ?>
              <a href="<?php echo url_for('/members/rides/signup.php?ride_id=' . $ride->ride_id); ?>" class="btn">Sign Up</a>
              <?php } ?>
            <?php } else { ?>
              <a class="btn" href="<?php echo url_for('/members/rides/signup.php?ride_id=' . $ride->ride_id); ?>">Log in to Sign Up</a>
            <?php } ?>
          </div>
        </div>
      <?php } ?>
    </div>

    <?php
    $sql = "SELECT * FROM ride";
    $ride_objects = Ride::find_by_sql($sql);

    if (empty($ride_objects)) {
      echo "No rides found.";
    }
    ?>
  </div>
</div>
<?php include(SHARED_PATH . '/public_footer.php'); ?>