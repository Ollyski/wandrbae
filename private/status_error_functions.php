<?php
function require_login()
{
  global $session;
  if (!$session->is_logged_in()) {
    $_SESSION['intended_destination'] = $_SERVER['REQUEST_URI'];
    redirect_to(url_for('/admin/login.php'));
  }
}

// New function for member login requirement
function require_member_login()
{
  global $member_session;
  if (!$member_session->is_logged_in()) {
    $_SESSION['intended_destination'] = $_SERVER['REQUEST_URI'];
    redirect_to(url_for('/login.php'));
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

// New function to get current member
function current_member()
{
  global $member_session;
  if ($member_session->is_logged_in()) {
    return Member::find_by_id($member_session->get_member_id());
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

//**Validation functions */
function has_length_greater_than($value, $min) {
  $length = strlen($value);
  return $length > $min;
}

function has_length_less_than($value, $max) {
  $length = strlen($value);
  return $length < $max;
}

function has_length_exactly($value, $exact) {
  $length = strlen($value);
  return $length == $exact;
}

function has_length($value, $options) {
  if(isset($options['min']) && !has_length_greater_than($value, $options['min'] - 1)) {
    return false;
  } elseif(isset($options['max']) && !has_length_less_than($value, $options['max'] + 1)) {
    return false;
  } elseif(isset($options['exact']) && !has_length_exactly($value, $options['exact'])) {
    return false;
  }
  return true;
}

function has_valid_email_format($value) {
  $email_regex = '/\A[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}\Z/i';
  return preg_match($email_regex, $value) === 1;
}

function has_unique_username($username, $current_id="0") {
  global $db;
  
  $sql = "SELECT * FROM user ";
  $sql .= "WHERE username='" . db_escape($db, $username) . "' ";
  $sql .= "AND user_id != '" . db_escape($db, $current_id) . "'";

  $result = mysqli_query($db, $sql);
  $user_count = mysqli_num_rows($result);
  mysqli_free_result($result);

  return $user_count === 0;
}

function db_escape($connection, $string) {
  return mysqli_real_escape_string($connection, $string);
}
