<?php require_once('../private/initialize.php'); ?>
<?php $page_title = 'Rides'; ?>
<?php include(SHARED_PATH . '/public_header.php'); ?>
<div id="main">
  <div id="page">
    <div class="intro">
      <h2>Wander with your baes!</h2>
      <p>Sign up for a ride!</p>
    </div>
    
    <div class="rides-container">
      <?php
      $rides = Ride::find_all();
      
      foreach($rides as $ride) { ?>
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
          </div>
          
          <div class="ride-footer">
            <a href="detail.php?id=<?php echo $ride->ride_id; ?>" class="view-btn">View Details</a>
            <a href="signup.php?ride_id=<?php echo $ride->ride_id; ?>" class="btn signup-btn">Sign Up</a>
          </div>
        </div>
      <?php } ?>
    </div>
    
    <?php
    // Using find_by_sql to get the ride objects
    $sql = "SELECT * FROM ride";
    $ride_objects = Ride::find_by_sql($sql);
    
    // Check if any rides were found
    if(empty($ride_objects)) {
      echo "<div style='text-align: center; padding: 2rem;'>No rides found.</div>";
    }
    ?>
  </div>
</div>
<?php include(SHARED_PATH . '/public_footer.php'); ?>