<?php

class Session
{
  private $admin_id;
  private $last_login;

  public function __construct()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
    $this->check_stored_login();
  }

  public function login($admin)
  {
    if ($admin) {
      // Regenerate session ID for security
      session_regenerate_id();
      $_SESSION['admin_id'] = $admin->user_id;
      $_SESSION['last_login'] = time();

      // Store these values in the object as well
      $this->admin_id = $admin->user_id;
      $this->last_login = $_SESSION['last_login'];

      // If there was an intended destination, get it
      $intended_destination = $_SESSION['intended_destination'] ?? url_for('/admin/index.php');
      unset($_SESSION['intended_destination']);

      // Set a welcome message
      $_SESSION['message'] = 'Welcome, ' . $admin->full_name() . '!';

      return $intended_destination;
    }
    return false;
  }

  public function is_logged_in()
  {
    return isset($this->admin_id);
  }

  public function logout()
  {
    unset($_SESSION['admin_id']);
    unset($_SESSION['last_login']);
    unset($this->admin_id);
    unset($this->last_login);

    // Don't destroy the whole session as it might contain
    // other important data
    return true;
  }

  public function get_admin_id()
  {
    return $this->admin_id ?? false;
  }

  public function get_last_login()
  {
    return $this->last_login ?? false;
  }

  private function check_stored_login()
  {
    if (isset($_SESSION['admin_id'])) {
      $this->admin_id = $_SESSION['admin_id'];
      $this->last_login = $_SESSION['last_login'] ?? time();
    }
  }

  public function message($msg = "")
  {
    if (!empty($msg)) {
      $_SESSION['message'] = $msg;
      return true;
    } else {
      return $_SESSION['message'] ?? '';
    }
  }

  public function clear_message()
  {
    unset($_SESSION['message']);
  }
}
