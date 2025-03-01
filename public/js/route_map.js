'use strict';

// Global variables
let map;
let routePath;

function loadGoogleMapsAPI() {
  if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
    console.log('Loading Google Maps API dynamically...');
    const script = document.createElement('script');
    script.src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyDDVvFVJsg2uT4iKCrLxMnrQTEA_l--7n4&callback=initMap";
    script.async = true;
    script.defer = true;
    document.head.appendChild(script);
  } else {
    console.log('Google Maps API already loaded');
    initMap(); // If it's already loaded, just fire the map right away
  }
}

document.addEventListener('DOMContentLoaded', function () {
  if (document.getElementById('map')) {
    console.log('Map element found, calling loadGoogleMapsAPI');
    loadGoogleMapsAPI();
  } else {
    console.log('Map element not found, skipping map initialization');
  }
});

function initMap() {
  console.log('initMap called');
  
  // Default center (will be overridden if waypoints exist)
  let mapCenter = { lat: 35.5951, lng: -82.5515 }; // Asheville default
  let zoomLevel = 12;

  if (typeof routeWaypoints === 'undefined') {
    console.log('routeWaypoints not defined, initializing empty array');
    window.routeWaypoints = [];
  }
  
  // Debug: Check for routeWaypoints
  console.log('routeWaypoints available:', typeof routeWaypoints !== 'undefined');
  
  // Check if we have route waypoints
  if (typeof routeWaypoints !== 'undefined' && routeWaypoints.length > 0) {
    console.log('Using route waypoints for map center');
    
    // Handle both property naming conventions
    const firstPoint = routeWaypoints[0];
    
    // Debug first waypoint
    console.log('First waypoint:', firstPoint);
    
    // Get lat/lng from the first waypoint
    const lat = parseFloat(firstPoint.latitude || firstPoint.lat || 0);
    const lng = parseFloat(firstPoint.longitude || firstPoint.lng || 0);
    
    console.log('First waypoint coordinates:', lat, lng);
    
    if (lat && lng) {
      mapCenter = { lat, lng };
      zoomLevel = 14; // Closer zoom for specific route
    }
  }

  // Set map options
  const mapOptions = {
    center: mapCenter,
    zoom: zoomLevel,
    mapTypeId: "terrain", // Shows elevation + landscape features
    mapTypeControl: true
  };

  console.log('Creating map with center:', mapCenter);

  // Create the map instance
  map = new google.maps.Map(document.getElementById("map"), mapOptions);

  // If we have waypoints, draw the route
  if (typeof routeWaypoints !== 'undefined' && routeWaypoints.length > 0) {
    console.log('Drawing route with', routeWaypoints.length, 'waypoints');
    drawRouteOnMap();
  } else {
    console.log('No waypoints, just adding default marker');
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
  try {
    // Convert waypoints to Google Maps LatLng objects
    const path = routeWaypoints.map(point => {
      // Try to extract latitude/longitude however they're named
      const lat = parseFloat(point.latitude || point.lat || 0); 
      const lng = parseFloat(point.longitude || point.lng || 0);
      
      console.log('Processing waypoint:', lat, lng);
      
      return { lat, lng };
    }).filter(coord => coord.lat !== 0 && coord.lng !== 0); // Filter out invalid coordinates
    
    console.log('Processed path:', path);
    
    if (path.length === 0) {
      console.log('No valid coordinates found');
      return;
    }
    
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
    
    console.log('Route drawing complete');
  } catch (error) {
    console.error('Error drawing route:', error);
  }
}

// Add event listener to initialize map when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
  // Check if map element exists before trying to initialize
  if (document.getElementById('map')) {
    console.log('Map element found, initializing...');
    // If Google Maps API is already loaded, initialize map
    if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
      initMap();
    } else {
      console.log('Google Maps not loaded yet');
    }
  } else {
    console.log('Map element not found');
  }
});