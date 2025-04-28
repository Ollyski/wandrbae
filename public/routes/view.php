<?php 
require_once('../../private/initialize.php'); 
require_member_login();
include_header();

$id = isset($_GET['id']) ? $_GET['id'] : 0;
$id = intval($id);
$route = find_route_by_id($id);

$waypoints = find_waypoints_by_route_id($id);

$show_map = true;
?>
<main>
  <div class="container">
    <section>
      <div class="route-detail-header">
        <h2><?php echo h($route['route_name']); ?></h2>
        <a href="../members/routes/index.php" class="back-to-rides">Back to Routes</a>
      </div>
      
      <div class="route-detail-card ride-card">
        <div class="ride-header">
          <h3 class="ride-name"><?php echo h($route['route_name']); ?></h3>
        </div>
        
        <div class="ride-info">
          <div class="ride-detail">
            <div class="ride-detail-icon">📏</div>
            <div class="ride-detail-text">
              <span class="ride-detail-label">Distance</span>
              <?php echo h($route['distance_km']); ?> km
            </div>
          </div>
          
          <div class="ride-detail">
            <div class="ride-detail-icon">🏞️</div>
            <div class="ride-detail-text">
              <span class="ride-detail-label">Terrain</span>
              <?php echo h($route['terrain_name']); ?>
            </div>
          </div>
          
          <div class="ride-detail">
            <div class="ride-detail-icon">⚠️</div>
            <div class="ride-detail-text">
              <span class="ride-detail-label">Difficulty</span>
              <?php echo h($route['difficulty_type']); ?>
            </div>
          </div>
          
          <?php if(isset($route['bike_lane'])): ?>
          <div class="ride-detail">
            <div class="ride-detail-icon">🚲</div>
            <div class="ride-detail-text">
              <span class="ride-detail-label">Bike Lane</span>
              <?php echo $route['bike_lane'] ? 'Available' : 'Not Available'; ?>
            </div>
          </div>
          <?php endif; ?>
          
          <?php if(isset($route['landmarks']) && !empty($route['landmarks'])): ?>
          <div class="ride-detail">
            <div class="ride-detail-icon">🗺️</div>
            <div class="ride-detail-text">
              <span class="ride-detail-label">Landmarks</span>
              <?php echo h($route['landmarks']); ?>
            </div>
          </div>
          <?php endif; ?>
        </div>
      </div>
      
      <div class="map-container">
        <h3>Route Map</h3>
        <div id="map" style="height: 400px; width: 100%; border-radius: 10px; margin-top: 20px;"></div>
      </div>
    </section>
  </div>
</main>

<!-- Pass route waypoints to JavaScript before loading the external JS file -->
<script>
// Safely encode waypoints data
window.routeWaypoints = <?php 
  // Ensure proper JSON encoding
  echo json_encode($waypoints, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE); 
?>;

// Debug - print JSON to console
console.log('Route waypoints:', window.routeWaypoints);
</script>

<!-- Load the external JavaScript file with the correct path -->
<script src="../../public/js/route_map.js"></script>

<?php include(SHARED_PATH . '/public_footer.php'); ?>