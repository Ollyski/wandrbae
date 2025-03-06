<?php
require_once('../private/initialize.php');

// Set content type to JSON
header('Content-Type: application/json');

// Get route ID from request (default to 1 for Lake Louise)
$route_id = isset($_GET['route_id']) ? intval($_GET['route_id']) : 1;

// Get route details
$route_query = "SELECT r.*, tt.terrain_name, d.difficulty_type, af.feature_name 
  FROM route r
  LEFT JOIN terrain_type tt ON r.terrain_type_id = tt.terrain_type_id
  LEFT JOIN difficulty d ON r.difficulty_id = d.difficulty_id
  LEFT JOIN accessibility_feature accessibility_feature_id = af.accessibility_feature_id
  WHERE r.route_id = ?";

$stmt = $db->prepare($route_query);
$stmt->bind_param("i", $route_id);
$stmt->execute();
$route_result = $stmt->get_result();
$route = $route_result->fetch_assoc();

// Get the waypoints for this route
$waypoint_query = "SELECT w.latitude, w.longitude, w.sequence_number
  FROM waypoint w
  JOIN route_recording rr ON w.recording_recording_id
  WHERE rr.route_id = ?
  ORDER BY w.sequence_number ASC";

$stmt = $db->prepare($waypoint_query);
$stmt->bind_param("i", $route_id);
$stmt->execute();
$waypoint_result = $stmt->get_result();

$waypoints = [];
while($waypoint = $waypoint_result->fetch_assoc()) {
  $waypoints[] = [
    'lat' => (float)$waypoint['latitude'],
    'lng' => (float)$waypoint['longitude'],
    'sequence' => (int)$waypoint['sequence_number']
  ];
}

// Combine into one response
$response = [
  'route' => $route,
  'waypoints' => $waypoints
];

echo json_encode($response);
?>