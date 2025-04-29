/**
 * route_map.js
 * 
 * This script handles the initialization and rendering of a Google Maps route display.
 * It uses the Google Maps JavaScript API to create an interactive map showing a route
 * between waypoints defined in the global window.routeWaypoints array.
 * 
 * The script supports:
 * - Dynamic loading of the Google Maps API
 * - Drawing routes between waypoints with styled polylines
 * - Adding start/end markers with custom styling
 * - Automatically fitting the map view to show the entire route
 * - Falling back to a default location when no waypoints are provided
*/

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
    
    window.initMap = async function() {
      
      const { AdvancedMarkerElement, PinElement } = await google.maps.importLibrary("marker");
      
      // Default center (will be overridden if waypoints exist)
      let mapCenter = { lat: 35.5951, lng: -82.5515 }; // Asheville default
      let zoomLevel = 12;
    
      // Use window.routeWaypoints to ensure it's globally accessible
      if (!window.routeWaypoints || !Array.isArray(window.routeWaypoints)) {
        console.log('routeWaypoints not defined or not an array, initializing empty array');
        window.routeWaypoints = [];
      }
      
      // Check if we have route waypoints
      if (window.routeWaypoints.length > 0) {
        
        // Handle both property naming conventions
        const firstPoint = window.routeWaypoints[0];
        const lat = parseFloat(firstPoint.latitude || firstPoint.lat || 0);
        const lng = parseFloat(firstPoint.longitude || firstPoint.lng || 0);
        
        if (lat && lng) {
          mapCenter = { lat, lng };
          zoomLevel = 14; // Closer zoom for specific route
        }
      }
    
      const mapOptions = {
        center: mapCenter,
        zoom: zoomLevel,
        mapTypeId: "terrain", // Shows elevation + landscape features
        mapTypeControl: true,
        mapId: "8fd263ce76ccf9c7"
      };
    
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
    };
    
      /**
     * Draws the route polyline and adds start/end markers to the map
     * @param {Class} AdvancedMarkerElement - Google Maps marker class for creating custom markers
     * @param {Class} PinElement - Google Maps class for creating custom pins
    */

    function drawRouteOnMap(AdvancedMarkerElement, PinElement) {
      try {
        const path = window.routeWaypoints.map(point => {
          const lat = parseFloat(point.latitude || point.lat || 0); 
          const lng = parseFloat(point.longitude || point.lng || 0);
          
          return { lat, lng };
        }).filter(coord => coord.lat !== 0 && coord.lng !== 0); 
        
        if (path.length === 0) {
          return;
        }
        
        routePath = new google.maps.Polyline({
          path: path,
          geodesic: true,
          strokeColor: "#FF4500",
          strokeOpacity: 1.0,
          strokeWeight: 4
        });
        
        routePath.setMap(routeMap);
        
         /**
         * Helper function to create markers using Advanced Markers
         * @param {Object} position - LatLng position for the marker
         * @param {string} title - Tooltip text for the marker
         * @param {string} color - Hex color code for the pin background
         * @returns {Object} The created marker instance
        */

        function createMarker(position, title, color) {
          const pin = new PinElement({
            background: color || "#4285F4",
            borderColor: "#FFFFFF",
            glyphColor: "#FFFFFF"
          });
          
          return new AdvancedMarkerElement({
            position: position,
            map: routeMap,
            title: title,
            content: pin.element
          });
        }
        
        if (path.length > 0) {
          createMarker(
            path[0], 
            "Start", 
            "#008000" // Green color
          );
          
          if (path.length > 1) {
            createMarker(
              path[path.length - 1],
              "End",
              "#FF0000" // Red color
            );
          }
        }
        
        const bounds = new google.maps.LatLngBounds();
        path.forEach(point => bounds.extend(point));
        routeMap.fitBounds(bounds);
        
      } catch (error) {
        console.error('Error drawing route:', error);
      }
    }
    
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