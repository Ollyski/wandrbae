<?php
require_once('../../private/initialize.php');
require_admin();

// Get ride ID and user ID from URL parameters
$ride_id = $_GET['ride_id'] ?? false;
$user_id = $_GET['user_id'] ?? false;

if (!$ride_id || !$user_id) {
  $_SESSION['message'] = 'Invalid request.';
  redirect_to(url_for('/admin/rides/index.php'));
}

// Get ride information
$ride = Ride::find_by_id($ride_id);

// Check if ride exists
if ($ride === false) {
  $_SESSION['message'] = 'Ride not found.';
  redirect_to(url_for('/admin/rides/index.php'));
}

// Find the participant
$participant = RideParticipant::find_participant($ride_id, $user_id);

if (!$participant) {
  $_SESSION['message'] = 'User is not signed up for this ride.';
  redirect_to(url_for('/admin/rides/participants.php?id=' . $ride_id));
}

// Delete the participant
$result = $participant->delete();

if ($result) {
  $_SESSION['message'] = 'Participant has been removed from the ride.';
} else {
  $_SESSION['message'] = 'Failed to remove participant. Please try again.';
}

redirect_to(url_for('/admin/rides/participants.php?id=' . $ride_id));
