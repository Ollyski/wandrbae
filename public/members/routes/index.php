<?php require_once('../../../private/initialize.php'); 
require_member_login();
include_header(); ?>

<?php
// Get all routes
$sql = "SELECT r.route_id, r.route_name, r.distance_km, 
         tt.terrain_name, d.difficulty_type, r.bike_lane,
         SUBSTRING(r.landmarks, 1, 100) AS short_landmarks
         FROM route r
         JOIN terrain_type tt ON r.terrain_type_id = tt.terrain_type_id
         JOIN difficulty d ON r.difficulty_id = d.difficulty_id
         ORDER BY r.route_name ASC";
$result = mysqli_query($db, $sql);
?>

<div id="main">
  <div id="page">
    <div class="intro">
      <h2>Explore Our Routes</h2>
      <p>Discover beautiful paths around the city</p>
      <?php echo display_session_message(); ?>
    </div>
    
    <div class="routes-container">
        <?php while($route = mysqli_fetch_assoc($result)) { ?>
        <div class="ride-card route-card">
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
                
                <?php if($route['bike_lane']) { ?>
                <div class="ride-detail">
                    <div class="ride-detail-icon">🚲</div>
                    <div class="ride-detail-text">
                        <span class="ride-detail-label">Bike Lane</span>
                        Available
                    </div>
                </div>
                <?php } ?>
                
                <div class="ride-detail">
                    <div class="ride-detail-icon">🗺️</div>
                    <div class="ride-detail-text">
                        <span class="ride-detail-label">Landmarks</span>
                        <?php echo h($route['short_landmarks']); ?>...
                    </div>
                </div>
            </div>
            
            <div class="ride-footer">
                <a href="<?php echo url_for('/routes/view.php?id=' . $route['route_id']); ?>" class="btn">View Route</a>
            </div>
        </div>
        <?php } ?>
    </div>
  </div>
</div>
<?php include(SHARED_PATH . '/public_footer.php'); ?>