<?php

class Admin extends DatabaseObject
{

  static protected $table_name = "user";
  static protected $db_columns = ['user_id', 'first_name', 'last_name', 'email', 'username', 'hashed_password', 'user_role_id', 'is_member'];

  public $user_id;
  public $first_name;
  public $last_name;
  public $email;
  public $username;
  protected $hashed_password;
  public $password;
  public $confirm_password;
  public $user_role_id;
  public $role_name;
  public $is_member;

  public function __construct($args = [])
  {
    $this->first_name = $args['first_name'] ?? '';
    $this->last_name = $args['last_name'] ?? '';
    $this->email = $args['email'] ?? '';
    $this->username = $args['username'] ?? '';
    $this->password = $args['password'] ?? '';
    $this->confirm_password = $args['confirm_password'] ?? '';
    $this->user_role_id = $args['user_role_id'] ?? 1;
    $this->is_member = $args['is_member'] ?? 1;
  }

  public function full_name()
  {
    return $this->first_name . " " . $this->last_name;
  }

  public function isAdmin()
  {
    return ($this->user_role_id == 2 || $this->user_role_id == 3);
  }

  public function isSuperAdmin()
  {
    return ($this->user_role_id == 3);
  }

  public function getRoleName()
  {
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

  public static function find_by_username($username)
  {
    $sql = "SELECT * FROM " . static::$table_name . " ";
    $sql .= "WHERE username='" . self::$database->escape_string($username) . "'";
    $obj_array = static::find_by_sql($sql);
    if (!empty($obj_array)) {
      return array_shift($obj_array);
    } else {
      return false;
    }
  }

  protected function create()
  {
    if ($this->password != '') {
      $this->set_hashed_password();
    }
    return parent::create();
  }

  public function set_password($password)
  {
    $this->password = $password;
    $this->set_hashed_password();
    return $this->save();
  }

  public static function find_all_admins()
  {
    $sql = "SELECT * FROM " . static::$table_name . " ";
    $sql .= "WHERE user_role_id IN (2, 3) ";
    $sql .= "ORDER BY last_name ASC, first_name ASC";
    return static::find_by_sql($sql);
  }

  public static function find_by_id($id)
  {
    $sql = "SELECT * FROM " . static::$table_name . " ";
    $sql .= "WHERE user_id='" . self::$database->escape_string($id) . "'";
    $obj_array = static::find_by_sql($sql);
    if (!empty($obj_array)) {
      return array_shift($obj_array);
    } else {
      return false;
    }
  }

  protected function set_hashed_password()
  {
    $this->hashed_password = password_hash($this->password, PASSWORD_BCRYPT);
  }

  public function verify_password($password)
  {
    if (empty($this->hashed_password)) {
      return false;
    }
    return password_verify($password, $this->hashed_password);
  }

  protected function update()
  {
    if ($this->password != '') {
      $this->set_hashed_password();
    } else {
      unset($this->password);
    }
    return parent::update();
  }

  public function has_password()
  {
    return !empty($this->hashed_password);
  }

  public function attributes()
  {
    $attributes = [];
    foreach (static::$db_columns as $column) {
      if ($column == 'user_id') {
        continue;
      }

      // Special handling for hashed_password
      if ($column == 'hashed_password') {
        // Only include hashed_password if it's been set
        if (isset($this->hashed_password)) {
          $attributes[$column] = $this->hashed_password;
        }
      } else {
        // For other attributes
        if (property_exists($this, $column)) {
          $attributes[$column] = $this->$column;
        }
      }
    }
    return $attributes;
  }
}
