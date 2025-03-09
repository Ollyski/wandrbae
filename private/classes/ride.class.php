<?php

class Ride
{
  // Active Record Code
  static protected $database;
  static protected $db_columns = ['ride_id', 'ride_name', 'created_by', 'route_id', 'start_time', 'end_time', 'location_name', 'street_address', 'city', 'state', 'zip_code'];

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
    $sql = "SELECT r.*, u.username FROM ride r ";
    $sql .= "LEFT JOIN user u ON r.created_by = u.user_id";
    return self::find_by_sql($sql);
  }

  function find_all_routes()
  {
    global $db;
    $sql = "SELECT * FROM route";
    $result = mysqli_query($db, $sql);

    if (!$result) {
      echo "Database query error: " . mysqli_error($db);
      return false;
    }

    if (mysqli_num_rows($result) === 0) {
      echo "<!-- No routes found in database -->";
    }

    return $result;
  }

  static public function find_by_id($id)
  {
    $sql = "SELECT r.*, u.username FROM ride r ";
    $sql .= "LEFT JOIN user u ON r.created_by = u.user_id ";
    $sql .= "WHERE r.ride_id='" . self::$database->escape_string($id) . "'";
    $obj_array = self::find_by_sql($sql);
    if (!empty($obj_array)) {
      return array_shift($obj_array);
    } else {
      return false;
    }
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
  public function create()
  {

    if (!isset($this->state)) {
      die("Error: State is not set.");
    }

    $this->state = strtoupper(trim($this->state));

    $valid_states = ['AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'FL', 'GA', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV', 'WI', 'WY'];

    // Ensure state is not empty and is a valid abbreviation
    if (empty($this->state) || !in_array(strtoupper(trim($this->state)), $valid_states)) {
      die("Error: Invalid or missing state. Please select a valid U.S. state.");
    }

    $attributes = $this->sanitized_attributes();
    $sql = "INSERT INTO ride (";
    $sql .= join(', ', array_keys($attributes));
    $sql .= ") VALUES ('";
    $sql .= join("', '", array_values($attributes));
    $sql .= "')";

    echo "Debug SQL: " . $sql . "<br>";

    $result = self::$database->query($sql);
    if ($result) {
      $this->ride_id = self::$database->insert_id;
    }
    return $result;
  }

  //Properties which have db columns excluding id
  public function attributes()
  {
    $attributes = [];
    foreach (self::$db_columns as $column) {
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

  //End of active record code

  public $ride_id;
  public $ride_name;
  public $created_by;
  public $username;
  public $route_id;
  public $start_time;
  public $end_time;
  public $location_name;
  public $street_address;
  public $city;
  public $state;
  public $zip_code;

  //constructor method

  public function __construct($args = [])
  {
    $this->ride_name = $args['ride_name'] ?? '';
    $this->created_by = $args['created_by'] ?? '';
    $this->route_id = $args['route_id'] ?? '';
    $this->start_time = $args['start_time'] ?? '';
    $this->end_time = $args['end_time'] ?? '';
    $this->location_name = $args['location_name'] ?? '';
    $this->street_address = $args['street_address'] ?? '';
    $this->city = $args['city'] ?? '';
    $this->state = $args['state'] ?? '';
    $this->zip_code = $args['zip_code'] ?? '';
  }

  public function ride_name()
  {
    return $this->ride_name;
  }
}
