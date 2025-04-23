<?php require_once('../private/initialize.php'); ?>
<?php $page_title = 'Rides'; ?>
<?php include(SHARED_PATH . '/public_header.php'); ?>


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

// Get the number of current participants
$current_participants = RideParticipant::count_participants_for_ride($id);

// Check if current user is signed up (if logged in)
$is_signed_up = false;
if ($user_session->is_logged_in()) {
  $user_id = $user_session->get_user_id();
  $is_signed_up = RideParticipant::is_user_signed_up($user_id, $id);
}
?>

<div id="main">
  <section>
    <a class="btn" href="ride.php">Back to Rides</a>

    <?php echo display_session_message(); ?>

    <div id="page">

      <div class="detail">
        <h1><?php echo h($ride->ride_name); ?></h1>

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
        <dl>
          <dt>Participants</dt>
          <dd><?php echo $current_participants; ?> rider(s)</dd>
        </dl>

        <div class="signup-area">
          <?php if ($user_session->is_logged_in()) { ?>
            <?php if ($is_signed_up) { ?>
              <p class="alert success">You are signed up for this ride!</p>

              <form action="<?php echo url_for('/members/rides/cancel_signup.php'); ?>" method="post">
                <input type="hidden" name="ride_id" value="<?php echo h($id); ?>">
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel your signup?');">Cancel My Signup</button>
              </form>
            <?php } else { ?>
              <a href="<?php echo url_for('/members/rides/signup.php?ride_id=' . h($id)); ?>" class="btn">Sign Up for This Ride</a>
            <?php } ?>
          <?php } else { ?>
            <p>Please <a href="<?php echo url_for('/login.php'); ?>">login</a> to sign up for this ride.</p>
          <?php } ?>
        </div>
      </div>
    </section>
  </div>
</div>

<?php include(SHARED_PATH . '/public_footer.php'); ?>