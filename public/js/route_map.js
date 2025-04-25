if (typeof window._routeMapLoaded === 'undefined') {
  window._routeMapLoaded = true;
  console.log('route_map.js loaded at', new Date().toISOString());
  
  // Begin IIFE to isolate variables
  (function() {
    'use strict';
    
    // Private module variables
    let routeMap;
    let routePath;
    
    // Ensure routeWaypoints is defined globally
    window.routeWaypoints = window.routeWaypoints || [];
    
    async function loadGoogleMapsAPI() {
      if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
        console.log('Loading Google Maps API dynamically...');
        const script = document.createElement('script');
        script.src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyDDVvFVJsg2uT4iKCrLxMnrQTEA_l--7n4&callback=initMap&loading=async&libraries=marker";
        script.async = true;
        script.defer = true;
        document.head.appendChild(script);
      } else {
        console.log('Google Maps API already loaded');
        initMap(); 
      }
    }
    
    // Make initMap globally available for Google Maps callback
    window.initMap = async function() {
      console.log('initMap called');
      
      // Import the marker library
      const { AdvancedMarkerElement, PinElement } = await google.maps.importLibrary("marker");
      
      // Default center (will be overridden if waypoints exist)
      let mapCenter = { lat: 35.5951, lng: -82.5515 }; // Asheville default
      let zoomLevel = 12;
    
      // Use window.routeWaypoints to ensure it's globally accessible
      if (!window.routeWaypoints || !Array.isArray(window.routeWaypoints)) {
        console.log('routeWaypoints not defined or not an array, initializing empty array');
        window.routeWaypoints = [];
      }
      
      // Debug: Check for routeWaypoints
      console.log('routeWaypoints available:', window.routeWaypoints.length > 0);
      
      // Check if we have route waypoints
      if (window.routeWaypoints.length > 0) {
        console.log('Using route waypoints for map center');
        
        // Handle both property naming conventions
        const firstPoint = window.routeWaypoints[0];
        
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
        mapTypeControl: true,
        mapId: "8fd263ce76ccf9c7"
      };
    
      console.log('Creating map with center:', mapCenter);
    
      // Create map instance
      routeMap = new google.maps.Map(document.getElementById("map"), mapOptions);
    
      // If we have waypoints, draw the route
      if (window.routeWaypoints.length > 0) {
        console.log('Drawing route with', window.routeWaypoints.length, 'waypoints');
        drawRouteOnMap(AdvancedMarkerElement, PinElement);
      } else {
        console.log('No waypoints, just adding default marker');
        
        // Create a default pin element for the marker
        const pin = new PinElement({
          background: "#4285F4",
          borderColor: "#FFFFFF",
          glyphColor: "#FFFFFF"
        });
        
        // Place an Advanced Marker at the center
        const marker = new AdvancedMarkerElement({
          position: mapCenter,
          map: routeMap,
          title: "Wandrbae Starting Point",
          content: pin.element
        });
      }
    
      console.log("Map loaded!");
    };
    
    function drawRouteOnMap(AdvancedMarkerElement, PinElement) {
      try {
        // Convert waypoints to Google Maps LatLng objects
        const path = window.routeWaypoints.map(point => {
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
        routePath.setMap(routeMap);
        
        // Helper function to create markers based using Advanced Markers
        function createMarker(position, title, color) {
          // Create a custom pin with specified color
          const pin = new PinElement({
            background: color || "#4285F4",
            borderColor: "#FFFFFF",
            glyphColor: "#FFFFFF"
          });
          
          // Create and return the Advanced Marker
          return new AdvancedMarkerElement({
            position: position,
            map: routeMap,
            title: title,
            content: pin.element
          });
        }
        
        // Add start marker
        if (path.length > 0) {
          createMarker(
            path[0], 
            "Start", 
            "#008000" // Green color
          );
          
          // Add end marker if different from start
          if (path.length > 1) {
            createMarker(
              path[path.length - 1],
              "End",
              "#FF0000" // Red color
            );
          }
        }
        
        // Fit map to bounds of the route
        const bounds = new google.maps.LatLngBounds();
        path.forEach(point => bounds.extend(point));
        routeMap.fitBounds(bounds);
        
        console.log('Route drawing complete');
      } catch (error) {
        console.error('Error drawing route:', error);
      }
    }
    
    // Single event listener for DOMContentLoaded
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', function () {
        if (document.getElementById('map')) {
          console.log('Map element found, calling loadGoogleMapsAPI');
          loadGoogleMapsAPI();
        } else {
          console.log('Map element not found, skipping map initialization');
        }
      });
    } else {
      // DOM already loaded
      if (document.getElementById('map')) {
        console.log('Map element found (DOM already loaded), calling loadGoogleMapsAPI');
        loadGoogleMapsAPI();
      } else {
        console.log('Map element not found, skipping map initialization');
      }
    }
  })();
} else {
  console.log('route_map.js already loaded, skipping execution');
}