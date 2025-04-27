<?php

class RideParticipant
{
  // Active Record Code
  static protected $database;
  static protected $db_columns = ['ride_id', 'user_id', 'joined_at', 'attended', 'attendance_timestamp'];

  static public function set_database($database)
  {
    self::$database = $database;
  }

  static public function find_by_sql($sql)
  {
    $result = self::$database->query($sql);
    if (!$result) {
      exit("Database query failed.");
    }
    $object_array = [];
    while ($record = $result->fetch_assoc()) {
      $object_array[] = self::instantiate($record);
    }
    $result->free();
    return $object_array;
  }

  static public function find_all()
  {
    $sql = "SELECT * FROM ride_participant";
    return self::find_by_sql($sql);
  }

  static public function find_participant($ride_id, $user_id)
  {
    $sql = "SELECT * FROM ride_participant ";
    $sql .= "WHERE ride_id='" . self::$database->escape_string($ride_id) . "' ";
    $sql .= "AND user_id='" . self::$database->escape_string($user_id) . "'";
    $obj_array = self::find_by_sql($sql);
    if (!empty($obj_array)) {
      return array_shift($obj_array);
    } else {
      return false;
    }
  }

  static public function is_user_signed_up($user_id, $ride_id)
  {
    // Check if database is initialized
    if (!isset(self::$database)) {
        global $db;
        self::set_database($db);
    }
    
    // Check if database is available now
    if (!isset(self::$database)) {
        return false;
    }
    
    $sql = "SELECT COUNT(*) as count FROM ride_participant ";
    $sql .= "WHERE user_id='" . self::$database->escape_string($user_id) . "' ";
    $sql .= "AND ride_id='" . self::$database->escape_string($ride_id) . "'";

    $result = self::$database->query($sql);
    if (!$result) {
        return false;
    }
    
    $row = $result->fetch_assoc();
    return ($row['count'] > 0);
  }

  static public function find_users_by_ride_id($ride_id)
  {
    $sql = "SELECT u.* FROM user u ";
    $sql .= "JOIN ride_participant rp ON u.user_id = rp.user_id ";
    $sql .= "WHERE rp.ride_id='" . self::$database->escape_string($ride_id) . "' ";
    $sql .= "ORDER BY u.last_name ASC, u.first_name ASC";

    return User::find_by_sql($sql);
  }

  static public function count_participants_for_ride($ride_id)
  {
    // Check if database is initialized
    if (!isset(self::$database)) {
        global $db;
        self::set_database($db);
    }
    
    // Check if database is available now
    if (!isset(self::$database)) {
        return 0; 
    }
    
    $sql = "SELECT COUNT(*) as count FROM ride_participant ";
    $sql .= "WHERE ride_id='" . self::$database->escape_string($ride_id) . "'";

    $result = self::$database->query($sql);
    if (!$result) {
        return 0; 
    }
    
    $row = $result->fetch_assoc();
    return $row['count'];
  }

  // New methods for attendance tracking
  static public function count_signed_up($ride_id)
  {
    $sql = "SELECT COUNT(*) as count FROM ride_participant ";
    $sql .= "WHERE ride_id='" . self::$database->escape_string($ride_id) . "'";

    $result = self::$database->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'];
  }

  static public function count_attended($ride_id)
  {
    $sql = "SELECT COUNT(*) as count FROM ride_participant ";
    $sql .= "WHERE ride_id='" . self::$database->escape_string($ride_id) . "' ";
    $sql .= "AND attended = 1";

    $result = self::$database->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'];
  }

  static public function find_all_who_attended($ride_id)
  {
    $sql = "SELECT rp.*, u.username, u.first_name, u.last_name FROM ride_participant rp ";
    $sql .= "JOIN user u ON rp.user_id = u.user_id ";
    $sql .= "WHERE rp.ride_id='" . self::$database->escape_string($ride_id) . "' ";
    $sql .= "AND rp.attended = 1 ";
    $sql .= "ORDER BY u.last_name ASC, u.first_name ASC";

    return self::find_by_sql($sql);
  }

  static public function find_all_no_shows($ride_id)
  {
    $sql = "SELECT rp.*, u.username, u.first_name, u.last_name FROM ride_participant rp ";
    $sql .= "JOIN user u ON rp.user_id = u.user_id ";
    $sql .= "WHERE rp.ride_id='" . self::$database->escape_string($ride_id) . "' ";
    $sql .= "AND rp.attended = 0 ";
    $sql .= "ORDER BY u.last_name ASC, u.first_name ASC";

    return self::find_by_sql($sql);
  }

  static public function find_rides_user_attended($user_id)
  {
    $sql = "SELECT r.* FROM ride r ";
    $sql .= "JOIN ride_participant rp ON r.ride_id = rp.ride_id ";
    $sql .= "WHERE rp.user_id='" . self::$database->escape_string($user_id) . "' ";
    $sql .= "AND rp.attended = 1 ";
    $sql .= "ORDER BY r.start_time DESC";

    return Ride::find_by_sql($sql);
  }

  static protected function instantiate($record)
  {
    $object = new self;
    foreach ($record as $property => $value) {
      if (property_exists($object, $property)) {
        $object->$property = $value;
      }
    }
    return $object;
  }

  public $ride_id;
  public $user_id;
  public $joined_at;
  public $attended;
  public $attendance_timestamp;

  // Added for display purposes
  public $username;
  public $first_name;
  public $last_name;

  public function __construct($args = [])
  {
    $this->ride_id = $args['ride_id'] ?? '';
    $this->user_id = $args['user_id'] ?? '';
    $this->joined_at = $args['joined_at'] ?? date('Y-m-d H:i:s');
    $this->attended = $args['attended'] ?? 0;
    $this->attendance_timestamp = $args['attendance_timestamp'] ?? NULL;
  }

  // Mark a participant as attended
  public function mark_as_attended()
  {
    $this->attended = 1;
    $this->attendance_timestamp = date('Y-m-d H:i:s');
    return $this->save();
  }

  // Unmark a participant as attended
  public function unmark_attendance()
  {
    $this->attended = 0;
    $this->attendance_timestamp = NULL;
    return $this->save();
  }

  protected function validate()
  {
    $errors = [];

    if (empty($this->user_id)) {
      $errors[] = "User ID cannot be empty.";
    }

    if (empty($this->ride_id)) {
      $errors[] = "Ride ID cannot be empty.";
    }

    // We don't need to check for duplicates as the primary key will handle that

    return $errors;
  }

  public function save()
  {
    $errors = $this->validate();
    if (!empty($errors)) {
      return false;
    }

    // Check if the participant record already exists
    $participant = self::find_participant($this->ride_id, $this->user_id);

    if ($participant) {
      // Update
      $sql = "UPDATE ride_participant SET ";
      $sql .= "joined_at = '" . self::$database->escape_string($this->joined_at) . "', ";
      $sql .= "attended = '" . self::$database->escape_string($this->attended) . "', ";

      if ($this->attendance_timestamp === NULL) {
        $sql .= "attendance_timestamp = NULL ";
      } else {
        $sql .= "attendance_timestamp = '" . self::$database->escape_string($this->attendance_timestamp) . "' ";
      }

      $sql .= "WHERE ride_id = '" . self::$database->escape_string($this->ride_id) . "' ";
      $sql .= "AND user_id = '" . self::$database->escape_string($this->user_id) . "' ";
      $sql .= "LIMIT 1";
    } else {
      // Insert
      $sql = "INSERT INTO ride_participant (";
      $sql .= "ride_id, user_id, joined_at, attended, attendance_timestamp";
      $sql .= ") VALUES (";
      $sql .= "'" . self::$database->escape_string($this->ride_id) . "', ";
      $sql .= "'" . self::$database->escape_string($this->user_id) . "', ";
      $sql .= "'" . self::$database->escape_string($this->joined_at) . "', ";
      $sql .= "'" . self::$database->escape_string($this->attended) . "', ";

      if ($this->attendance_timestamp === NULL) {
        $sql .= "NULL";
      } else {
        $sql .= "'" . self::$database->escape_string($this->attendance_timestamp) . "'";
      }

      $sql .= ")";
    }

    $result = self::$database->query($sql);
    return $result;
  }

  public function delete()
  {
    $sql = "DELETE FROM ride_participant ";
    $sql .= "WHERE ride_id = '" . self::$database->escape_string($this->ride_id) . "' ";
    $sql .= "AND user_id = '" . self::$database->escape_string($this->user_id) . "' ";
    $sql .= "LIMIT 1";

    $result = self::$database->query($sql);
    return $result;
  }
}