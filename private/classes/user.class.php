<?php

class User extends DatabaseObject
{
  protected static $table_name = 'user';
  protected static $db_columns = ['user_id', 'username', 'email', 'hashed_password', 'first_name', 'last_name', 'user_role_id', 'is_member'];

  public $user_id;
  public $username;
  public $email;
  protected $hashed_password;
  public $first_name;
  public $last_name;
  public $user_role_id;
  public $is_member;
  public $password;
  public $confirm_password;
  public $errors = [];

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

  public function is_admin()
  {
    return ($this->user_role_id == 2 || $this->user_role_id == 3);
  }

  public function is_super_admin()
  {
    return ($this->user_role_id == 3);
  }

  public function get_role_name()
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

  protected function set_hashed_password()
  {
    if(!empty($this->password)) {
      $this->hashed_password = password_hash($this->password, PASSWORD_BCRYPT);
    }
  }

  public function verify_password($password)
  {
    if (empty($this->hashed_password)) {
      return false;
    }
    return password_verify($password, $this->hashed_password);
  }

  protected function validate()
  {
    $this->errors = [];

    if (is_blank($this->username)) {
      $this->errors[] = "Username cannot be blank.";
    } elseif (!has_length($this->username, ['min' => 3, 'max' => 255])) {
      $this->errors[] = "Username must be between 3 and 255 characters.";
    } elseif (!has_unique_username($this->username, $this->user_id ?? 0)) {
      $this->errors[] = "Username is already taken.";
    }

    if (is_blank($this->email)) {
      $this->errors[] = "Email cannot be blank.";
    } elseif (!has_valid_email_format($this->email)) {
      $this->errors[] = "Email is not in a valid format.";
    }

    if (is_blank($this->first_name)) {
      $this->errors[] = "First name cannot be blank.";
    } elseif (!has_length($this->first_name, ['min' => 2, 'max' => 255])) {
      $this->errors[] = "First name must be between 2 and 255 characters.";
    }

    if (is_blank($this->last_name)) {
      $this->errors[] = "Last name cannot be blank.";
    } elseif (!has_length($this->last_name, ['min' => 2, 'max' => 255])) {
      $this->errors[] = "Last name must be between 2 and 255 characters.";
    }

    if (is_blank($this->password) && $this->user_id == '') {
      $this->errors[] = "Password cannot be blank.";
    } elseif (isset($this->password) && !is_blank($this->password)) {
      if (!has_length($this->password, ['min' => 6])) {
        $this->errors[] = "Password must contain 6 or more characters";
      }

      if (!preg_match('/[A-Z]/', $this->password)) {
        $this->errors[] = "Password must contain at least 1 uppercase letter";
      }

      if (!preg_match('/[a-z]/', $this->password)) {
        $this->errors[] = "Password must contain at least 1 lowercase letter";
      }

      if (!preg_match('/[0-9]/', $this->password)) {
        $this->errors[] = "Password must contain at least 1 number";
      }

      if (!preg_match('/[^A-Za-z0-9\s]/', $this->password)) {
        $this->errors[] = "Password must contain at least 1 symbol";
      }

      if (is_blank($this->confirm_password)) {
        $this->errors[] = "Confirm password cannot be blank.";
      } elseif ($this->password !== $this->confirm_password) {
        $this->errors[] = "Password and confirm password must match.";
      }
    }

    return $this->errors;
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
  
  public static function find_all_admins()
  {
    $sql = "SELECT * FROM " . static::$table_name . " ";
    $sql .= "WHERE user_role_id IN (2, 3) ";
    $sql .= "ORDER BY last_name ASC, first_name ASC";
    return static::find_by_sql($sql);
  }

  protected function create()
  {
    $this->validate();
    if (!empty($this->errors)) {
      return false;
    }

    if ($this->password != '') {
      $this->set_hashed_password();
    }

    $attributes = $this->attributes();
    $sql = "INSERT INTO " . static::$table_name . " (";
    $sql .= join(', ', array_keys($attributes));
    $sql .= ") VALUES ('";
    $sql .= join("', '", array_values($attributes));
    $sql .= "')";

    $result = self::$database->query($sql);
    if ($result) {
      $this->user_id = self::$database->insert_id;
    }
    return $result;
  }

  protected function update()
  {
    $this->validate();
    if (!empty($this->errors)) {
      return false;
    }

    if ($this->password != '') {
      $this->set_hashed_password();
    } else {
      // Skip password validation
    }

    $attributes = $this->attributes();
    $attribute_pairs = [];
    foreach ($attributes as $key => $value) {
      $attribute_pairs[] = "{$key}='{$value}'";
    }

    $sql = "UPDATE " . static::$table_name . " SET ";
    $sql .= join(', ', $attribute_pairs);
    $sql .= " WHERE user_id='" . self::$database->escape_string($this->user_id) . "' ";
    $sql .= "LIMIT 1";

    $result = self::$database->query($sql);
    return $result;
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