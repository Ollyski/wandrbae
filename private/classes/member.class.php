<?php

class Member extends DatabaseObject
{
  protected static $table_name = 'user'; // Assuming you're using the 'user' table for members
  protected static $db_columns = ['user_id', 'username', 'email', 'hashed_password', 'first_name', 'last_name', 'created_at'];

  public $user_id;
  public $username;
  public $email;
  public $hashed_password;
  public $first_name;
  public $last_name;
  public $created_at;
  protected $password;
  public $confirm_password;
  public $errors = [];

  public function __construct($args = [])
  {
    $this->username = $args['username'] ?? '';
    $this->email = $args['email'] ?? '';
    $this->first_name = $args['first_name'] ?? '';
    $this->last_name = $args['last_name'] ?? '';
    $this->created_at = $args['created_at'] ?? date('Y-m-d H:i:s');
    $this->password = $args['password'] ?? '';
    $this->confirm_password = $args['confirm_password'] ?? '';
  }

  public function full_name()
  {
    return $this->first_name . " " . $this->last_name;
  }

  protected function set_hashed_password()
  {
    $this->hashed_password = password_hash($this->password, PASSWORD_BCRYPT);
  }

  public function verify_password($password)
  {
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

  protected function create()
  {
    $this->validate();
    if (!empty($this->errors)) {
      return false;
    }

    $this->set_hashed_password();

    $attributes = $this->sanitized_attributes();

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
    }

    $attributes = $this->sanitized_attributes();
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
}
