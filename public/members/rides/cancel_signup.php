<?php require_once('../../../private/initialize.php');
require_member_login();
include_header();

$ride_id = $_POST['ride_id'] ?? $_GET['ride_id'] ?? false;

if (!$ride_id) {
  $_SESSION['message'] = 'Invalid request.';
  redirect_to(url_for('/ride.php'));
}

$user_id = $user_session->get_user_id();
$participant = RideParticipant::find_participant($ride_id, $user_id);

if (!$participant) {
  $_SESSION['message'] = 'You are not signed up for this ride.';
  redirect_to(url_for('/ride.php'));
}

if (!is_post_request()) {
  $ride = Ride::find_by_id($ride_id);
  $page_title = 'Cancel Signup';
  ?>
  <div id="content">
    <main role="main">
      <section>
        <h1>Cancel Ride Signup</h1>
        <p>Are you sure you want to cancel your signup for this ride?</p>
        
        <?php if($ride) { ?>
        <p><strong>Ride Name:</strong> <?php echo h($ride->name); ?></p>
        <p><strong>Date:</strong> <?php echo h($ride->formatted_date()); ?></p>
        <?php } ?>
        
        <form action="<?php echo url_for('/members/rides/cancel.php'); ?>" method="post">
          <input type="hidden" name="ride_id" value="<?php echo h($ride_id); ?>" />
          <button type="submit" class="button">Yes, Cancel My Signup</button>
          <a href="<?php echo url_for('/ride.php'); ?>" class="button">No, Keep My Signup</a>
        </form>
      </section>
    </main>
  </div>
  <?php
  include(SHARED_PATH . '/public_footer.php');
  exit;
}

$result = $participant->delete();

if ($result) {
  $_SESSION['message'] = 'Your signup has been canceled.';
} else {
  $_SESSION['message'] = 'Failed to cancel signup. Please try again.';
}

redirect_to(url_for('/ride.php'));