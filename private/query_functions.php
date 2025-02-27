<?php


 function find_all_users() {
  global $db;

  $sql = "SELECT * FROM user ";
  $sql .= "ORDER BY username ASC";
  $result = mysqli_query($db, $sql);
  return $result;
 }
 
 function find_route_by_id($id) {
  global $db;
  
  $sql = "SELECT r.*, tt.terrain_name, d.difficulty_type, af.feature_name 
          FROM route r
          LEFT JOIN terrain_type tt ON r.terrain_type_id = tt.terrain_type_id
          LEFT JOIN difficulty d ON r.difficulty_id = d.difficulty_id
          LEFT JOIN accessibility_feature af ON r.accessibility_feature_id = af.accessibility_feature_id
          WHERE r.route_id = ?";
          
  $stmt = mysqli_prepare($db, $sql);
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  
  $route = mysqli_fetch_assoc($result);
  mysqli_free_result($result);
  return $route;
}

function find_waypoints_by_route_id($route_id) {
  global $db;
  
  $sql = "SELECT w.latitude, w.longitude, w.sequence_number, w.recorded_at
          FROM waypoint w
          JOIN route_recording rr ON w.recording_id = rr.recording_id
          WHERE rr.route_id = ?
          ORDER BY w.sequence_number ASC";
          
  $stmt = mysqli_prepare($db, $sql);
  mysqli_stmt_bind_param($stmt, "i", $route_id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  
  $waypoints = [];
  while($waypoint = mysqli_fetch_assoc($result)) {
    $waypoints[] = $waypoint;
  }
  
  mysqli_free_result($result);
  return $waypoints;
}
?>