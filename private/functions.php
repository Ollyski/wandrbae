<?php

  function url_for($script_path) {
    // add the leading '/' if not present
    if($script_path[0] != '/') {
      $script_path = "/" . $script_path;
    }
    return WWW_ROOT . $script_path;
  }

  //maybe this bit
  function u($string="") {
    if(is_null($string)) {
      $string = "";
    }
    return urlencode($string);
  }
 //maybe

  function raw_u($string="") {
    if(is_null($string)) {
      $string = "";
    }
    return rawurlencode($string);
  }

  function h($string="") {
    if (is_null($string)) {
      $string = "";
    }
    return htmlspecialchars($string);
  }

  function is_blank($value) {
    return !isset($value) || trim($value) === '';
  }

  function error_404() {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    exit();
  }

  function error_500() {
    header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
    exit();
  }

  function redirect_to($location) {
    header("Location: " . $location);
    exit;
  }

  function is_post_request() {
    return $_SERVER['REQUEST_METHOD'] == 'POST';
  }

  function is_get_request() {
    return $_SERVER['REQUEST_METHOD'] == 'GET';
  }

  /**
 * Includes a React component in the page
 * 
 * @param string $component_name The name of the React component to include
 * @param array $props Optional props to pass to the component
 * @param bool $include_dependencies Whether to include React dependencies (set to false if multiple components on same page)
 */
  function include_react_component($component_name, $props = array(), $include_dependencies = true) {
    // Convert props to JSON for passing to React
    $props_json = htmlspecialchars(json_encode($props), ENT_QUOTES, 'UTF-8');
    
    // Component container ID
    $container_id = strtolower($component_name) . '-container';
    
    // Output the container
    echo '<div id="' . $container_id . '" class="react-component"></div>';
    
    // Include the necessary scripts if requested
    if ($include_dependencies) {
      echo '<script src="https://unpkg.com/react@17/umd/react.production.min.js"></script>';
      echo '<script src="https://unpkg.com/react-dom@17/umd/react-dom.production.min.js"></script>';
      echo '<script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script>';
    }
    
    // Make props available to the component
    echo '<script>
      window.' . $component_name . 'Props = ' . $props_json . ';
    </script>';
    
    // Include component-specific JavaScript - point to your JS folder
    echo '<script src="' . url_for('/js/' . strtolower($component_name) . '.js') . '" type="text/babel"></script>';
  }
?>