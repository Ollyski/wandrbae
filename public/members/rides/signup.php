<?php
require_once('../../../private/initialize.php');
require_member_login();
?>

<?php
$page_title = 'Sign Up for Ride';
include(SHARED_PATH . '/member_header.php');

// Get the ride_id from URL parameter
$ride_id = $_GET['ride_id'] ?? false;

if (!$ride_id) {
  redirect_to(url_for('/public/ride.php'));
}

// Find the ride
$ride = Ride::find_by_id($ride_id);

// Check if ride exists
if ($ride === false) {
  $_SESSION['message'] = 'Ride not found.';
  redirect_to(url_for('/public/ride.php'));
}

// Get current member
$member_id = $member_session->get_member_id();
$member = Member::find_by_id($member_id);

if (!$member) {
  $_SESSION['message'] = 'You must be logged in to sign up for rides.';
  redirect_to(url_for('/login.php'));
}

// Check if member is already signed up
$already_signed_up = RideParticipant::is_user_signed_up($member_id, $ride_id);

// Process form submission
if (is_post_request()) {
  // Create participant
  $participant = new RideParticipant;
  $participant->user_id = $member_id;
  $participant->ride_id = $ride_id;
  $participant->joined_at = date('Y-m-d H:i:s');

  // Save participant
  $result = $participant->save();

  if ($result) {
    $_SESSION['message'] = 'You have successfully signed up for this ride!';
    redirect_to(url_for('/public/ride.php'));
  } else {
    // If there was an error
    $_SESSION['message'] = 'Failed to sign up for ride. Please try again.';
  }
}
?>

<div id="content">
  <div id="main">
    <a href="<?php echo url_for('/public/ride.php'); ?>">&laquo; Back to Rides</a>

    <div id="page">
      <h1>Sign Up for Ride: <?php echo h($ride->ride_name); ?></h1>

      <?php echo display_session_message(); ?>

      <div>
        <p><strong>Starts At:</strong> <?php echo h($ride->start_time); ?></p>
        <p><strong>Ends At:</strong> <?php echo h($ride->end_time); ?></p>
        <p><strong>Location:</strong> <?php echo h($ride->location_name); ?></p>
        <p><strong>Address:</strong> <?php echo h($ride->street_address); ?></p>

        <?php
        // Get the number of current participants
        $current_participants = RideParticipant::count_participants_for_ride($ride_id);
        ?>
        <p><strong>Current Participants:</strong> <?php echo $current_participants; ?></p>
      </div>

      <?php if ($already_signed_up) { ?>
        <div>
          <p>You are already signed up for this ride!</p>

          <form action="<?php echo url_for('/members/rides/cancel_signup.php'); ?>" method="post">
            <input type="hidden" name="ride_id" value="<?php echo h($ride_id); ?>">
            <button type="submit" onclick="return confirm('Are you sure you want to cancel your signup?');">Cancel My Signup</button>
          </form>
        </div>
      <?php } else { ?>
        <form action="<?php echo url_for('/members/rides/signup.php?ride_id=' . h($ride_id)); ?>" method="post">
          <p>Would you like to sign up for this ride?</p>

          <button type="submit">Sign Up</button>
        </form>
      <?php } ?>
    </div>
  </div>
</div>

<?php include(SHARED_PATH . '/member_footer.php'); ?>