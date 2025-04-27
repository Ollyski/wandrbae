<?php
require_once('../private/initialize.php');
require_member_login();

$page_title = 'My Attendance';

// Get current user ID
$user_id = $user_session->get_user_id();

// Get attended rides
$sql = "SELECT r.*, 
         CASE 
           WHEN rp.attended = 1 THEN 'Checked In' 
           WHEN rw.walkin_id IS NOT NULL THEN 'Walk-in'
           ELSE 'Signed Up (No Show)'
         END AS attendance_status,
         COALESCE(rp.attendance_timestamp, rw.joined_at) AS attendance_time
       FROM ride r
       LEFT JOIN ride_participant rp ON r.ride_id = rp.ride_id AND rp.user_id = " . db_escape($db, $user_id) . "
       LEFT JOIN ride_walkin rw ON r.ride_id = rw.ride_id AND rw.user_id = " . db_escape($db, $user_id) . "
       WHERE (rp.user_id = " . db_escape($db, $user_id) . " OR rw.user_id = " . db_escape($db, $user_id) . ")
       ORDER BY r.start_time DESC";

$result = mysqli_query($db, $sql);
if (!$result) {
  // Handle query error
  echo "Database query error: " . mysqli_error($db);
  $rides = [];
} else {
  // Fetch all rides
  $rides = [];
  while ($ride = mysqli_fetch_assoc($result)) {
    $rides[] = $ride;
  }
  mysqli_free_result($result);
}

// Get stats
$total_rides = count($rides);
$attended_rides = 0;
$walkIn_rides = 0;
$no_show_rides = 0;

foreach ($rides as $ride) {
  if ($ride['attendance_status'] == 'Checked In') {
    $attended_rides++;
  } elseif ($ride['attendance_status'] == 'Walk-in') {
    $walkIn_rides++;
  } else {
    $no_show_rides++;
  }
}

$attendance_rate = ($total_rides > 0) ? round((($attended_rides + $walkIn_rides) / $total_rides) * 100, 1) : 0;

?>

<?php include(SHARED_PATH . '/header.php'); ?>

<div class="content">
  <h1>My Ride Attendance</h1>

  <?php echo display_session_message(); ?>

  <div class="stats-summary">
    <div class="stats-grid">
      <div class="stat-box">
        <span class="stat-number"><?php echo $total_rides; ?></span>
        <span class="stat-label">Total Rides</span>
      </div>

      <div class="stat-box">
        <span class="stat-number"><?php echo $attended_rides + $walkIn_rides; ?></span>
        <span class="stat-label">Attended</span>
      </div>

      <div class="stat-box">
        <span class="stat-number"><?php echo $no_show_rides; ?></span>
        <span class="stat-label">No Shows</span>
      </div>

      <div class="stat-box">
        <span class="stat-number"><?php echo $attendance_rate; ?>%</span>
        <span class="stat-label">Attendance Rate</span>
      </div>
    </div>
  </div>

  <div class="rides-section">
    <h2>My Ride History</h2>

    <?php if (count($rides) > 0) : ?>
      <table class="list">
        <tr>
          <th>Ride Name</th>
          <th>Date & Time</th>
          <th>Location</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>

        <?php foreach ($rides as $ride) : ?>
          <tr>
            <td><?php echo h($ride['ride_name']); ?></td>
            <td><?php echo date('M d, Y g:i A', strtotime($ride['start_time'])); ?></td>
            <td>
              <?php
              $location_str = h($ride['location_name']);
              if (!empty($ride['city'])) {
                $location_str .= ', ' . h($ride['city']);
              }
              if (!empty($ride['state'])) {
                $location_str .= ', ' . h($ride['state']);
              }
              echo $location_str;
              ?>
            </td>
            <td>
              <?php if ($ride['attendance_status'] == 'Checked In') : ?>
                <span class="status-attended">Checked In</span>
                <span class="timestamp"><?php echo date('M d, Y g:i A', strtotime($ride['attendance_time'])); ?></span>
              <?php elseif ($ride['attendance_status'] == 'Walk-in') : ?>
                <span class="status-attended">Walk-in</span>
                <span class="timestamp"><?php echo date('M d, Y g:i A', strtotime($ride['attendance_time'])); ?></span>
              <?php else : ?>
                <span class="status-no-show">No Show</span>
              <?php endif; ?>
            </td>
            <td>
              <a href="<?php echo url_for('/rides/show.php?id=' . h(u($ride['ride_id']))); ?>" class="action-link">View Details</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    <?php else : ?>
      <p>You haven't signed up for any rides yet.</p>
    <?php endif; ?>
  </div>

  <div class="links">
    <a href="<?php echo url_for('/rides/index.php'); ?>" class="action-link">View All Rides</a>
  </div>
</div>

<?php include(SHARED_PATH . '/footer.php'); ?>