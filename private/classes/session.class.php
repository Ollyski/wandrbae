<?php

class Session
{

  private $admin_id;

  public function __construct()
  {
    $this->check_stored_login();
  }

  public function login($admin)
  {
    if ($admin) {
      session_regenerate_id();
      $_SESSION['admin_id'] = $admin->user_id;
      $this->admin_id = $admin->user_id;
    }
    return true;
  }

  public function is_logged_in()
  {
    return isset($this->admin_id);
  }

  public function logout()
  {
    unset($_SESSION['admin_id']);
    unset($this->admin_id);
    return true;
  }

  private function check_stored_login()
  {
    if (isset($_SESSION['admin_id'])) {
      $this->admin_id = $_SESSION['admin_id'];
    }
  }
}
