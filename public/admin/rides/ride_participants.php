<?php
require_once('../../private/initialize.php');
require_admin_login();
include_header();

$page_title = 'Ride Participants';

// Get ride ID from URL parameter
$ride_id = $_GET['id'] ?? false;

if (!$ride_id) {
  $_SESSION['message'] = 'No ride specified.';
  redirect_to(url_for('/admin/rides/index.php'));
}

// Get ride information
$ride = Ride::find_by_id($ride_id);

// Check if ride exists
if ($ride === false) {
  $_SESSION['message'] = 'Ride not found.';
  redirect_to(url_for('/admin/rides/index.php'));
}

// Get all users signed up for this ride
$participants = RideParticipant::find_users_by_ride_id($ride_id);

// Get total participant count
$participant_count = RideParticipant::count_participants_for_ride($ride_id);
?>

<div id="content">
  <div id="main-content">
    <a href="<?php echo url_for('/admin/rides/index.php'); ?>">&laquo; Back to Rides</a>

    <div class="participants listing">
      <h1>Participants for: <?php echo h($ride->ride_name); ?></h1>

      <?php echo display_session_message(); ?>

      <div class="ride-info">
        <p><strong>Start Time:</strong> <?php echo h($ride->start_time); ?></p>
        <p><strong>End Time:</strong> <?php echo h($ride->end_time); ?></p>
        <p><strong>Location:</strong> <?php echo h($ride->location_name); ?> (<?php echo h($ride->street_address); ?>)</p>
        <p><strong>Total Participants:</strong> <?php echo $participant_count; ?></p>
      </div>

      <table class="list">
        <tr>
          <th>Username</th>
          <th>Email</th>
          <th>Full Name</th>
          <th>Joined At</th>
          <th>&nbsp;</th>
        </tr>

        <?php foreach ($participants as $user) {
          // Get joined_at date
          $sql = "SELECT joined_at FROM ride_participant ";
          $sql .= "WHERE user_id='" . db_escape($user->user_id) . "' ";
          $sql .= "AND ride_id='" . db_escape($ride_id) . "' ";
          $sql .= "LIMIT 1";

          $result = $db->query($sql);
          $joined_record = $result->fetch_assoc();
          $joined_at = $joined_record['joined_at'] ?? 'N/A';
        ?>
          <tr>
            <td><?php echo h($user->username); ?></td>
            <td><?php echo h($user->email); ?></td>
            <td><?php echo h($user->first_name . ' ' . $user->last_name); ?></td>
            <td><?php echo h($joined_at); ?></td>
            <td>
              <a class="action" href="<?php echo url_for('/admin/members/show.php?id=' . h(u($user->user_id))); ?>">View</a>
              <a class="action" href="<?php echo url_for('/admin/rides/remove_participant.php?ride_id=' . h(u($ride_id)) . '&user_id=' . h(u($user->user_id))); ?>" onclick="return confirm('Are you sure you want to remove this participant?');">Remove</a>
            </td>
          </tr>
        <?php } ?>
      </table>

      <?php if (empty($participants)) { ?>
        <p>No participants have signed up for this ride yet.</p>
      <?php } ?>
    </div>
  </div>
</div>

<?php include(SHARED_PATH . '/admin_footer.php'); ?>