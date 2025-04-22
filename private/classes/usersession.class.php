<?php

class UserSession
{
  private $user_id;
  private $user_role_id;
  private $last_login;

  public function __construct()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
    $this->check_stored_login();
  }

  public function login($user)
  {
    if ($user) {
      // Regenerate session ID for security
      session_regenerate_id();
      $_SESSION['user_id'] = $user->user_id;
      $_SESSION['user_role_id'] = $user->user_role_id;
      $_SESSION['last_login'] = time();

      // Store these values in the object as well
      $this->user_id = $user->user_id;
      $this->user_role_id = $user->user_role_id;
      $this->last_login = $_SESSION['last_login'];

      // Determine appropriate destination based on role
      $intended_destination = $_SESSION['intended_destination'] ?? '';
      
      if (empty($intended_destination)) {
        // Default destinations based on role
        if ($this->is_admin()) {
          $intended_destination = url_for('/admin/index.php');
        } else {
          $intended_destination = url_for('/ride.php');
        }
      }
      
      unset($_SESSION['intended_destination']);

      // Set a welcome message
      $_SESSION['message'] = 'Welcome, ' . $user->full_name() . '!';

      return $intended_destination;
    }
    return false;
  }

  public function is_logged_in()
  {
    return isset($this->user_id);
  }

  public function is_admin()
  {
    return $this->is_logged_in() && ($this->user_role_id == 2 || $this->user_role_id == 3);
  }

  public function is_super_admin()
  {
    return $this->is_logged_in() && $this->user_role_id == 3;
  }

  public function is_member()
  {
    // All logged in users have member capabilities, including admins
    return $this->is_logged_in();
  }

  public function get_user_role()
  {
    if (!$this->is_logged_in()) return false;
    
    switch ($this->user_role_id) {
      case 1:
        return "Member";
      case 2:
        return "Admin";
      case 3:
        return "Superadmin";
      default:
        return "Unknown";
    }
  }

  public function logout()
  {
    unset($_SESSION['user_id']);
    unset($_SESSION['user_role_id']);
    unset($_SESSION['last_login']);
    unset($this->user_id);
    unset($this->user_role_id);
    unset($this->last_login);

    // Don't destroy the whole session as it might contain
    // other important data
    return true;
  }

  public function get_user_id()
  {
    return $this->user_id ?? false;
  }

  public function get_last_login()
  {
    return $this->last_login ?? false;
  }

  private function check_stored_login()
  {
    if (isset($_SESSION['user_id'])) {
      $this->user_id = $_SESSION['user_id'];
      $this->user_role_id = $_SESSION['user_role_id'] ?? 1;
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