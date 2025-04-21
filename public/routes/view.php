<?php 
require_once('../../private/initialize.php'); 

$id = isset($_GET['id']) ? $_GET['id'] : 0;
$id = intval($id);
$route = find_route_by_id($id);

$waypoints = find_waypoints_by_route_id($id);

$show_map = true;

include(SHARED_PATH . '/public_header.php'); 
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
<main>
<!-- Pass route waypoints to JavaScript -->
<script>
  // Convert PHP arrays to JavaScript arrays for the map
  const routeWaypoints = <?php echo json_encode($waypoints); ?>;
  const routeName = "<?php echo h($route['route_name']); ?>";
</script>
<script>
  console.log("Current file path: <?php echo __FILE__; ?>");
  console.log("Route map should be at: <?php echo url_for('/javascripts/route_map.js'); ?>");
</script>

<?php include(SHARED_PATH . '/public_footer.php'); ?>