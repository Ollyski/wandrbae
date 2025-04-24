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
      <h1><?php echo h($route['route_name']); ?></h1>
      <a href="../members/routes/index.php">Back to Routes</a>
      
      <div class="route-details">
        <div class="route-info">
          <p><strong>Distance:</strong> <?php echo h($route['distance_km']); ?> km</p>
          <p><strong>Terrain:</strong> <?php echo h($route['terrain_name']); ?></p>
          <p><strong>Difficulty:</strong> <?php echo h($route['difficulty_type']); ?></p>
          <?php if(isset($route['bike_lane'])): ?>
            <p><strong>Bike Lane:</strong> <?php echo $route['bike_lane'] ? 'Yes' : 'No'; ?></p>
          <?php endif; ?>
          <?php if(isset($route['landmarks']) && !empty($route['landmarks'])): ?>
            <h3>Landmarks</h3>
            <p><?php echo h($route['landmarks']); ?></p>
          <?php endif; ?>
        </div>
        
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