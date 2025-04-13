<?php require_once('../../../private/initialize.php');
require_member_login();

// Process form submission
if (is_post_request()) {
  $ride_id = $_POST['ride_id'] ?? false;

  if (!$ride_id) {
    $_SESSION['message'] = 'Invalid request.';
    redirect_to(url_for('/public/ride.php'));
  }

  // Get current member
  $member_id = $member_session->get_member_id();

  // Find the participant
  $participant = RideParticipant::find_participant($ride_id, $member_id);

  if (!$participant) {
    $_SESSION['message'] = 'You are not signed up for this ride.';
    redirect_to(url_for('/public/ride.php'));
  }

  // Delete the participation record
  $result = $participant->delete();

  if ($result) {
    $_SESSION['message'] = 'Your signup has been canceled.';
  } else {
    $_SESSION['message'] = 'Failed to cancel signup. Please try again.';
  }

  redirect_to(url_for('/public/ride.php'));
} else {
  // Not a POST request, redirect
  redirect_to(url_for('/public/ride.php'));
}
