<?php require_once('../../../private/initialize.php'); ?>

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

<?php include(SHARED_PATH . '/public_header.php'); ?>

<div class="container">
    <h1>Explore Our Routes</h1>
    
    <div class="routes-list">
        <?php while($route = mysqli_fetch_assoc($result)) { ?>
        <div class="route-card">
            <h2><?php echo h($route['route_name']); ?></h2>
            <div class="route-card-content">
                <div class="route-stats">
                    <span class="route-stat"><?php echo h($route['distance_km']); ?> km</span>
                    <span class="route-stat"><?php echo h($route['terrain_name']); ?></span>
                    <span class="route-stat"><?php echo h($route['difficulty_type']); ?></span>
                    <?php if($route['bike_lane']) { ?>
                    <span class="route-stat badge">Bike Lane</span>
                    <?php } ?>
                </div>
                <p class="route-description">
                    <?php echo h($route['short_landmarks']); ?>...
                </p>
                <div class="route-actions">
                    <a href="<?php echo url_for('/routes/view.php?id=' . $route['route_id']); ?>" class="btn btn-primary">View Route</a>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<?php include(SHARED_PATH . '/public_footer.php'); ?>