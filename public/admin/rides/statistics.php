<?php
require_once('../private/initialize.php');
require_login();

// Set the title
$page_title = 'Ride Statistics Dashboard';

// Define the time periods for statistics
$current_date = date('Y-m-d');
$last_week_start = date('Y-m-d', strtotime('-7 days'));
$last_month_start = date('Y-m-d', strtotime('-30 days'));
$last_year_start = date('Y-m-d', strtotime('-365 days'));

// Get statistics for different time periods
function get_ride_stats($start_date = null, $end_date = null)
{
  global $db;

  $sql = "SELECT 
            COUNT(DISTINCT r.ride_id) as total_rides,
            COALESCE(SUM((SELECT COUNT(*) FROM ride_participant rp WHERE rp.ride_id = r.ride_id)), 0) as total_signups,
            COALESCE(SUM((SELECT COUNT(*) FROM ride_participant rp WHERE rp.ride_id = r.ride_id AND rp.attended = 1)), 0) as total_attended,
            COALESCE(SUM((SELECT COUNT(*) FROM ride_walkin rw WHERE rw.ride_id = r.ride_id)), 0) as total_walkins,
            AVG((SELECT COUNT(*) FROM ride_participant rp WHERE rp.ride_id = r.ride_id AND rp.attended = 1) + 
                (SELECT COUNT(*) FROM ride_walkin rw WHERE rw.ride_id = r.ride_id)) as avg_participants
          FROM ride r 
          WHERE 1=1 ";

  if ($start_date) {
    $sql .= "AND r.start_time >= '" . db_escape($db, $start_date . ' 00:00:00') . "' ";
  }

  if ($end_date) {
    $sql .= "AND r.start_time <= '" . db_escape($db, $end_date . ' 23:59:59') . "' ";
  }

  $result = mysqli_query($db, $sql);
  if (!$result) {
    return [
      'total_rides' => 0,
      'total_signups' => 0,
      'total_attended' => 0,
      'total_walkins' => 0,
      'avg_participants' => 0,
      'attendance_rate' => 0
    ];
  }

  $stats = mysqli_fetch_assoc($result);
  mysqli_free_result($result);

  // Calculate attendance rate
  $stats['attendance_rate'] = ($stats['total_signups'] > 0) ?
    round(($stats['total_attended'] / $stats['total_signups']) * 100, 1) : 0;

  // Round average participants
  $stats['avg_participants'] = round($stats['avg_participants'], 1);

  return $stats;
}

// Get top locations
function get_top_locations($limit = 5)
{
  global $db;

  $sql = "SELECT 
            r.location_name, 
            r.city, 
            r.state, 
            COUNT(DISTINCT r.ride_id) as ride_count,
            SUM((SELECT COUNT(*) FROM ride_participant rp WHERE rp.ride_id = r.ride_id AND rp.attended = 1) + 
                (SELECT COUNT(*) FROM ride_walkin rw WHERE rw.ride_id = r.ride_id)) as total_participants
          FROM ride r
          GROUP BY r.location_name, r.city, r.state
          ORDER BY total_participants DESC
          LIMIT " . intval($limit);

  $result = mysqli_query($db, $sql);
  if (!$result) {
    return [];
  }

  $locations = [];
  while ($location = mysqli_fetch_assoc($result)) {
    $locations[] = $location;
  }
  mysqli_free_result($result);

  return $locations;
}

// Get participation growth over time
function get_participation_growth()
{
  global $db;

  $months = 6; // Show last 6 months

  $data = [];
  for ($i = 0; $i < $months; $i++) {
    $month_start = date('Y-m-01', strtotime("-$i months"));
    $month_end = date('Y-m-t', strtotime("-$i months"));
    $month_label = date('M Y', strtotime("-$i months"));

    $sql = "SELECT 
              COUNT(DISTINCT r.ride_id) as rides,
              SUM((SELECT COUNT(*) FROM ride_participant rp WHERE rp.ride_id = r.ride_id)) as signups,
              SUM((SELECT COUNT(*) FROM ride_participant rp WHERE rp.ride_id = r.ride_id AND rp.attended = 1)) as attended,
              SUM((SELECT COUNT(*) FROM ride_walkin rw WHERE rw.ride_id = r.ride_id)) as walkins
            FROM ride r
            WHERE r.start_time BETWEEN '" . db_escape($db, $month_start . ' 00:00:00') . "' AND '" . db_escape($db, $month_end . ' 23:59:59') . "'";

    $result = mysqli_query($db, $sql);
    if (!$result) {
      continue;
    }

    $month_data = mysqli_fetch_assoc($result);
    mysqli_free_result($result);

    $month_data['month'] = $month_label;
    $month_data['total_participants'] = intval($month_data['attended']) + intval($month_data['walkins']);

    $data[] = $month_data;
  }

  // Reverse to show oldest to newest
  return array_reverse($data);
}

// Get all statistics
$all_time_stats = get_ride_stats();
$last_year_stats = get_ride_stats($last_year_start, $current_date);
$last_month_stats = get_ride_stats($last_month_start, $current_date);
$last_week_stats = get_ride_stats($last_week_start, $current_date);
$top_locations = get_top_locations(5);
$growth_data = get_participation_growth();

// Format growth data for chart
$months = [];
$participants = [];
foreach ($growth_data as $month) {
  $months[] = $month['month'];
  $participants[] = $month['total_participants'];
}

?>

<?php include(SHARED_PATH . '/header.php'); ?>

<div class="content">
  <h1>Ride Statistics Dashboard</h1>

  <?php echo display_session_message(); ?>

  <div class="dashboard-grid">
    <div class="stats-card">
      <h2>All Time Statistics</h2>
      <div class="stats-grid">
        <div class="stat-item">
          <span class="stat-value"><?php echo number_format($all_time_stats['total_rides']); ?></span>
          <span class="stat-label">Total Rides</span>
        </div>
        <div class="stat-item">
          <span class="stat-value"><?php echo number_format($all_time_stats['total_signups']); ?></span>
          <span class="stat-label">Total Sign-ups</span>
        </div>
        <div class="stat-item">
          <span class="stat-value"><?php echo number_format($all_time_stats['total_attended'] + $all_time_stats['total_walkins']); ?></span>
          <span class="stat-label">Total Participants</span>
        </div>
        <div class="stat-item">
          <span class="stat-value"><?php echo number_format($all_time_stats['attendance_rate']); ?>%</span>
          <span class="stat-label">Attendance Rate</span>
        </div>
      </div>
    </div>

    <div class="stats-card">
      <h2>Recent Statistics</h2>
      <div class="time-period-tabs">
        <button class="tab-button active" data-target="last-week">Last Week</button>
        <button class="tab-button" data-target="last-month">Last Month</button>
        <button class="tab-button" data-target="last-year">Last Year</button>
      </div>

      <div class="time-period-content active" id="last-week">
        <div class="stats-grid">
          <div class="stat-item">
            <span class="stat-value"><?php echo number_format($last_week_stats['total_rides']); ?></span>
            <span class="stat-label">Rides</span>
          </div>
          <div class="stat-item">
            <span class="stat-value"><?php echo number_format($last_week_stats['avg_participants']); ?></span>
            <span class="stat-label">Avg. Participants</span>
          </div>
          <div class="stat-item">
            <span class="stat-value"><?php echo number_format($last_week_stats['total_attended']); ?></span>
            <span class="stat-label">Checked In</span>
          </div>
          <div class="stat-item">
            <span class="stat-value"><?php echo number_format($last_week_stats['total_walkins']); ?></span>
            <span class="stat-label">Walk-ins</span>
          </div>
        </div>
      </div>

      <div class="time-period-content" id="last-month">
        <div class="stats-grid">
          <div class="stat-item">
            <span class="stat-value"><?php echo number_format($last_month_stats['total_rides']); ?></span>
            <span class="stat-label">Rides</span>
          </div>
          <div class="stat-item">
            <span class="stat-value"><?php echo number_format($last_month_stats['avg_participants']); ?></span>
            <span class="stat-label">Avg. Participants</span>
          </div>
          <div class="stat-item">
            <span class="stat-value"><?php echo number_format($last_month_stats['total_attended']); ?></span>
            <span class="stat-label">Checked In</span>
          </div>
          <div class="stat-item">
            <span class="stat-value"><?php echo number_format($last_month_stats['total_walkins']); ?></span>
            <span class="stat-label">Walk-ins</span>
          </div>
        </div>
      </div>

      <div class="time-period-content" id="last-year">
        <div class="stats-grid">
          <div class="stat-item">
            <span class="stat-value"><?php echo number_format($last_year_stats['total_rides']); ?></span>
            <span class="stat-label">Rides</span>
          </div>
          <div class="stat-item">
            <span class="stat-value"><?php echo number_format($last_year_stats['avg_participants']); ?></span>
            <span class="stat-label">Avg. Participants</span>
          </div>
          <div class="stat-item">
            <span class="stat-value"><?php echo number_format($last_year_stats['total_attended']); ?></span>
            <span class="stat-label">Checked In</span>
          </div>
          <div class="stat-item">
            <span class="stat-value"><?php echo number_format($last_year_stats['total_walkins']); ?></span>
            <span class="stat-label">Walk-ins</span>
          </div>
        </div>
      </div>
    </div>

    <div class="stats-card">
      <h2>Top Locations</h2>
      <table class="locations-table">
        <thead>
          <tr>
            <th>Location</th>
            <th>Rides</th>
            <th>Participants</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($top_locations as $location) : ?>
            <tr>
              <td>
                <?php
                echo h($location['location_name']);
                if (!empty($location['city'])) {
                  echo ', ' . h($location['city']);
                }
                if (!empty($location['state'])) {
                  echo ', ' . h($location['state']);
                }
                ?>
              </td>
              <td><?php echo number_format($location['ride_count']); ?></td>
              <td><?php echo number_format($location['total_participants']); ?></td>
            </tr>
          <?php endforeach; ?>

          <?php if (count($top_locations) === 0) : ?>
            <tr>
              <td colspan="3">No location data available</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="stats-card wide-card">
      <h2>Participation Growth</h2>
      <div class="chart-container">
        <canvas id="growthChart"></canvas>
      </div>
    </div>

    <div class="stats-card wide-card">
      <h2>Attendance Rate Comparison</h2>
      <div class="comparison-container">
        <div class="comparison-item">
          <div class="comparison-label">Last Week</div>
          <div class="progress-bar">
            <div class="progress" style="width: <?php echo min(100, $last_week_stats['attendance_rate']); ?>%;"><?php echo $last_week_stats['attendance_rate']; ?>%</div>
          </div>
        </div>

        <div class="comparison-item">
          <div class="comparison-label">Last Month</div>
          <div class="progress-bar">
            <div class="progress" style="width: <?php echo min(100, $last_month_stats['attendance_rate']); ?>%;"><?php echo $last_month_stats['attendance_rate']; ?>%</div>
          </div>
        </div>

        <div class="comparison-item">
          <div class="comparison-label">Last Year</div>
          <div class="progress-bar">
            <div class="progress" style="width: <?php echo min(100, $last_year_stats['attendance_rate']); ?>%;"><?php echo $last_year_stats['attendance_rate']; ?>%</div>
          </div>
        </div>

        <div class="comparison-item">
          <div class="comparison-label">All Time</div>
          <div class="progress-bar">
            <div class="progress" style="width: <?php echo min(100, $all_time_stats['attendance_rate']); ?>%;"><?php echo $all_time_stats['attendance_rate']; ?>%</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="links">
    <a href="<?php echo url_for('/rides/history.php'); ?>" class="action-link">View Detailed Ride History</a>
    <a href="<?php echo url_for('/rides/index.php'); ?>" class="back-link">&laquo; Back to Rides</a>
  </div>
</div>

<style>
  .dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
  }

  .stats-card {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
  }

  .wide-card {
    grid-column: 1 / -1;
  }

  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 15px;
    margin-top: 15px;
  }

  .stat-item {
    text-align: center;
    padding: 15px 10px;
    background-color: #f8fafc;
    border-radius: 8px;
  }

  .stat-value {
    display: block;
    font-size: 1.8em;
    font-weight: bold;
    color: #2d3748;
    margin-bottom: 5px;
  }

  .stat-label {
    display: block;
    font-size: 0.9em;
    color: #718096;
  }

  .time-period-tabs {
    display: flex;
    border-bottom: 1px solid #e2e8f0;
    margin-bottom: 15px;
  }

  .tab-button {
    background: none;
    border: none;
    padding: 10px 15px;
    cursor: pointer;
    font-weight: 500;
    color: #718096;
    border-bottom: 3px solid transparent;
  }

  .tab-button.active {
    color: #4299e1;
    border-bottom-color: #4299e1;
  }

  .time-period-content {
    display: none;
  }

  .time-period-content.active {
    display: block;
  }

  .locations-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
  }

  .locations-table th,
  .locations-table td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
  }

  .locations-table th {
    background-color: #f8fafc;
    font-weight: bold;
    color: #4a5568;
  }

  .chart-container {
    height: 300px;
    margin-top: 15px;
  }

  .comparison-container {
    margin-top: 20px;
  }

  .comparison-item {
    margin-bottom: 15px;
  }

  .comparison-label {
    font-weight: bold;
    margin-bottom: 5px;
  }

  .progress-bar {
    background-color: #edf2f7;
    border-radius: 4px;
    height: 25px;
    overflow: hidden;
  }

  .progress {
    height: 100%;
    background-color: #4299e1;
    color: white;
    text-align: right;
    padding-right: 10px;
    line-height: 25px;
    font-weight: bold;
    transition: width 0.5s ease;
    min-width: 35px;
  }

  .action-link {
    display: inline-block;
    margin-right: 15px;
    background-color: #4299e1;
    color: white;
    padding: 10px 15px;
    border-radius: 5px;
    text-decoration: none;
  }

  .back-link {
    display: inline-block;
    color: #4a5568;
    text-decoration: none;
  }

  .back-link:hover {
    text-decoration: underline;
  }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Tab functionality
  document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');

    tabButtons.forEach(button => {
      button.addEventListener('click', function() {
        // Remove active class from all buttons and content
        document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.time-period-content').forEach(content => content.classList.remove('active'));

        // Add active class to clicked button and its target content
        this.classList.add('active');
        document.getElementById(this.dataset.target).classList.add('active');
      });
    });

    // Growth chart
    const ctx = document.getElementById('growthChart').getContext('2d');
    const growthChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: <?php echo json_encode($months); ?>,
        datasets: [{
          label: 'Total Participants',
          data: <?php echo json_encode($participants); ?>,
          backgroundColor: 'rgba(66, 153, 225, 0.2)',
          borderColor: 'rgba(66, 153, 225, 1)',
          borderWidth: 2,
          tension: 0.3,
          fill: true
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              precision: 0
            }
          }
        },
        plugins: {
          legend: {
            display: true,
            position: 'top',
          },
          tooltip: {
            mode: 'index',
            intersect: false,
          }
        }
      }
    });
  });
</script>

<?php include(SHARED_PATH . '/footer.php'); ?>