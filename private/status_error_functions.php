<?php
function require_login()
{
  global $session;
  if (!$session->is_logged_in()) {
    $_SESSION['intended_destination'] = $_SERVER['REQUEST_URI'];
    redirect_to(url_for('/admin/login.php'));
  }
}
function require_admin()
{
  global $session;
  require_login();
  $current_user = Admin::find_by_id($session->get_admin_id());
  if (!$current_user || !$current_user->isAdmin()) {
    $_SESSION['message'] = "You do not have permission to access that page.";
    redirect_to(url_for('/admin/index.php'));
  }
}

function require_super_admin()
{
  global $session;
  require_login();

  $current_user = Admin::find_by_id($session->get_admin_id());
  if (!$current_user || !$current_user->isSuperAdmin()) {
    $_SESSION['message'] = "You do not have permission to access that page.";
    redirect_to(url_for('/admin/index.php'));
  }
}

function current_user()
{
  global $session;
  if ($session->is_logged_in()) {
    return Admin::find_by_id($session->get_admin_id());
  } else {
    return false;
  }
}

function has_role($role_id)
{
  $user = current_user();
  if (!$user) {
    return false;
  }
  return $user->user_role_id == $role_id;
}

function can_access($page_type)
{
  $user = current_user();
  if (!$user) {
    return false;
  }

  switch ($page_type) {
    case 'member_only':
      return true; // All logged in users can access
    case 'admin_only':
      return $user->isAdmin();
    case 'super_admin_only':
      return $user->isSuperAdmin();
    default:
      return false;
  }
}
function display_errors($errors = array())
{
  $output = '';
  if (!empty($errors)) {
    $output .= "<div class=\"errors\">";
    $output .= "Please fix the following errors:";
    $output .= "<ul>";
    foreach ($errors as $error) {
      $output .= "<li>" . h($error) . "</li>";
    }
    $output .= "</ul>";
    $output .= "</div>";
  }
  return $output;
}

function get_and_clear_session_message()
{
  if (isset($_SESSION['message']) && $_SESSION['message'] != '') {
    $msg = $_SESSION['message'];
    unset($_SESSION['message']);
    return $msg;
  }
}

function display_session_message()
{
  $msg = get_and_clear_session_message();
  if (isset($msg) && $msg != '') {
    return '<div id="message">' . h($msg) . '</div>';
  }
}
