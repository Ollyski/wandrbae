<?php

class RideParticipant extends DatabaseObject
{
  protected static $table_name = 'ride_participant';
  protected static $db_columns = ['ride_id', 'user_id', 'joined_at'];

  public $ride_id;
  public $user_id;
  public $joined_at;

  public function __construct($args = [])
  {
    $this->user_id = $args['user_id'] ?? '';
    $this->ride_id = $args['ride_id'] ?? '';
    $this->joined_at = $args['joined_at'] ?? date('Y-m-d H:i:s');
  }

  // Find a specific participant record
  public static function find_participant($ride_id, $user_id)
  {
    $sql = "SELECT * FROM " . static::$table_name . " ";
    $sql .= "WHERE ride_id='" . self::$database->escape_string($ride_id) . "' ";
    $sql .= "AND user_id='" . self::$database->escape_string($user_id) . "'";
    $obj_array = static::find_by_sql($sql);
    if (!empty($obj_array)) {
      return array_shift($obj_array);
    } else {
      return false;
    }
  }

  public static function find_all()
  {
    $sql = "SELECT * FROM " . static::$table_name;
    return static::find_by_sql($sql);
  }

  public static function find_by_sql($sql)
  {
    $result = self::$database->query($sql);
    if (!$result) {
      exit("Database query failed.");
    }

    // Convert results into objects
    $object_array = [];
    while ($record = $result->fetch_assoc()) {
      $object_array[] = static::instantiate($record);
    }

    $result->free();
    return $object_array;
  }

  protected static function instantiate($record)
  {
    $object = new static;
    foreach ($record as $property => $value) {
      if (property_exists($object, $property)) {
        $object->$property = $value;
      }
    }
    return $object;
  }

  public function validate()
  {
    $this->errors = [];

    if (empty($this->user_id)) {
      $this->errors[] = "User ID cannot be empty.";
    }

    if (empty($this->ride_id)) {
      $this->errors[] = "Ride ID cannot be empty.";
    }

    // Check if user is already signed up for this ride
    if (static::is_user_signed_up($this->user_id, $this->ride_id)) {
      $this->errors[] = "User is already signed up for this ride.";
    }

    return $this->errors;
  }

  protected function create()
  {
    $this->validate();
    if (!empty($this->errors)) {
      return false;
    }

    $attributes = $this->sanitized_attributes();

    $sql = "INSERT INTO " . static::$table_name . " (";
    $sql .= join(', ', array_keys($attributes));
    $sql .= ") VALUES ('";
    $sql .= join("', '", array_values($attributes));
    $sql .= "')";

    $result = self::$database->query($sql);
    return $result;
  }

  protected function update()
  {
    $this->validate();
    if (!empty($this->errors)) {
      return false;
    }

    $attributes = $this->sanitized_attributes();
    $attribute_pairs = [];
    foreach ($attributes as $key => $value) {
      if ($key != 'ride_id' && $key != 'user_id') {
        $attribute_pairs[] = "{$key}='{$value}'";
      }
    }

    $sql = "UPDATE " . static::$table_name . " SET ";
    $sql .= join(', ', $attribute_pairs);
    $sql .= " WHERE ride_id='" . self::$database->escape_string($this->ride_id) . "' ";
    $sql .= "AND user_id='" . self::$database->escape_string($this->user_id) . "'";

    $result = self::$database->query($sql);
    return $result;
  }

  public function save()
  {
    // Check if record exists
    $participant = static::find_participant($this->ride_id, $this->user_id);
    if ($participant) {
      return $this->update();
    } else {
      return $this->create();
    }
  }

  public function delete()
  {
    $sql = "DELETE FROM " . static::$table_name . " ";
    $sql .= "WHERE ride_id='" . self::$database->escape_string($this->ride_id) . "' ";
    $sql .= "AND user_id='" . self::$database->escape_string($this->user_id) . "'";

    $result = self::$database->query($sql);
    return $result;
  }

  public function attributes()
  {
    $attributes = [];
    foreach (static::$db_columns as $column) {
      $attributes[$column] = $this->$column;
    }
    return $attributes;
  }

  protected function sanitized_attributes()
  {
    $sanitized = [];
    foreach ($this->attributes() as $key => $value) {
      $sanitized[$key] = self::$database->escape_string($value);
    }
    return $sanitized;
  }

  // Check if a user is already signed up for a specific ride
  public static function is_user_signed_up($user_id, $ride_id)
  {
    $sql = "SELECT COUNT(*) as count FROM " . static::$table_name . " ";
    $sql .= "WHERE user_id='" . self::$database->escape_string($user_id) . "' ";
    $sql .= "AND ride_id='" . self::$database->escape_string($ride_id) . "'";

    $result = self::$database->query($sql);
    $row = $result->fetch_assoc();
    return ($row['count'] > 0);
  }

  // Count participants for a specific ride
  public static function count_participants_for_ride($ride_id)
  {
    $sql = "SELECT COUNT(*) as count FROM " . static::$table_name . " ";
    $sql .= "WHERE ride_id='" . self::$database->escape_string($ride_id) . "'";

    $result = self::$database->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'];
  }

  // Find all users signed up for a specific ride
  public static function find_users_by_ride_id($ride_id)
  {
    $sql = "SELECT u.* FROM user u ";
    $sql .= "JOIN " . static::$table_name . " rp ON u.user_id = rp.user_id ";
    $sql .= "WHERE rp.ride_id='" . self::$database->escape_string($ride_id) . "' ";
    $sql .= "ORDER BY u.username ASC";

    return Member::find_by_sql($sql);
  }

  // Find all rides a user is signed up for
  public static function find_rides_by_user_id($user_id)
  {
    $sql = "SELECT r.* FROM ride r ";
    $sql .= "JOIN " . static::$table_name . " rp ON r.ride_id = rp.ride_id ";
    $sql .= "WHERE rp.user_id='" . self::$database->escape_string($user_id) . "' ";
    $sql .= "ORDER BY r.start_time ASC";

    return Ride::find_by_sql($sql);
  }
}
