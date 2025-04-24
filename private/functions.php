<?php

  function url_for($script_path) {
    // add the leading '/' if not present
    if($script_path[0] != '/') {
      $script_path = "/" . $script_path;
    }
    return WWW_ROOT . $script_path;
  }

  function u($string="") {
    if(is_null($string)) {
      $string = "";
    }
    return urlencode($string);
  }

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

  function require_admin_login() {
    global $user_session;
    
    if (!$user_session->is_admin()) {
      $_SESSION['intended_destination'] = $_SERVER['REQUEST_URI'];
      redirect_to(url_for('/admin/login.php'));
    }
  }

  function include_header() {
    global $user_session;
    
    if ($user_session->is_logged_in()) {
      if ($user_session->is_super_admin()) {
        include(SHARED_PATH . '/superadmin_header.php');
      } else if ($user_session->is_admin()) {
        include(SHARED_PATH . '/admin_header.php');
      } else {
        include(SHARED_PATH . '/member_header.php');
      }
    } else {
      include(SHARED_PATH . '/public_header.php');
    }
  }

?>