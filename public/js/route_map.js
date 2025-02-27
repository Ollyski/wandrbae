'use strict';

// Global variables
let map;
let routePath;

function initMap() {
  // Default center (will be overridden if waypoints exist)
  let mapCenter = { lat: 35.5951, lng: -82.5515 }; // Asheville default
  let zoomLevel = 12;
  
  // Check if we have route waypoints
  if (typeof routeWaypoints !== 'undefined' && routeWaypoints.length > 0) {
    // Use first waypoint as center if available
    mapCenter = {
      lat: parseFloat(routeWaypoints[0].latitude),
      lng: parseFloat(routeWaypoints[0].longitude)
    };
    zoomLevel = 14; // Closer zoom for specific route
  }

  // Set map options
  const mapOptions = {
    center: mapCenter,
    zoom: zoomLevel,
    mapTypeId: "terrain", // Shows elevation + landscape features
    mapTypeControl: true, // Enables the map type selector
    mapTypeControlOptions: {
      style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
      position: google.maps.ControlPosition.TOP_RIGHT,
      mapTypeIds: ["roadmap", "satellite", "hybrid", "terrain"]
    }
  };

  // Create the map instance
  map = new google.maps.Map(document.getElementById("map"), mapOptions);

  // If we have waypoints, draw the route
  if (typeof routeWaypoints !== 'undefined' && routeWaypoints.length > 0) {
    drawRouteOnMap();
  } else {
    // Just place a marker at the center
    const marker = new google.maps.Marker({
      position: mapCenter,
      map: map,
      title: "Wandrbae Starting Point",
    });
  }

  console.log("Map loaded!");
}

function drawRouteOnMap() {
  // Convert waypoints to Google Maps LatLng objects
  const path = routeWaypoints.map(point => {
    return {
      lat: parseFloat(point.latitude || point.lat), 
      lng: parseFloat(point.longitude || point.lng)
    };
  });
  
  // Create polyline for the route
  routePath = new google.maps.Polyline({
    path: path,
    geodesic: true,
    strokeColor: "#FF4500",
    strokeOpacity: 1.0,
    strokeWeight: 4
  });
  
  // Add to map
  routePath.setMap(map);
  
  // Add start marker
  if (path.length > 0) {
    new google.maps.Marker({
      position: path[0],
      map: map,
      title: "Start",
      icon: {
        url: "http://maps.google.com/mapfiles/ms/icons/green-dot.png"
      }
    });
    
    // Add end marker if different from start
    if (path.length > 1) {
      new google.maps.Marker({
        position: path[path.length - 1],
        map: map,
        title: "End",
        icon: {
          url: "http://maps.google.com/mapfiles/ms/icons/red-dot.png"
        }
      });
    }
  }
  
  // Fit map to bounds of the route
  const bounds = new google.maps.LatLngBounds();
  path.forEach(point => bounds.extend(point));
  map.fitBounds(bounds);
}

// Add event listener to initialize map when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
  // Check if map element exists before trying to initialize
  if (document.getElementById('map')) {
    // If Google Maps API is already loaded, initialize map
    if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
      initMap();
    }
    // Otherwise, the script in the header should call initMap automatically
  }
});