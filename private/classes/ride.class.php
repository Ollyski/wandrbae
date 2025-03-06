<?php

class Ride {
  // Active Record Code
  static protected $database;

  static public function set_database($database) {
    self::$database = $database;
  }

  static public function find_by_sql($sql) {
    $result = self::$database->query($sql);
    if(!$result) {
      exit("Database query failed.");
    }
    $object_array = [];
    while($record = $result->fetch_assoc()) {
      $object_array[] = self::instantiate($record);
    }
    $result->free();
    return $object_array;
  }

  static public function find_all() {
    $sql = "SELECT r.*, u.username FROM ride r ";
    $sql .= "LEFT JOIN user u ON r.created_by = u.user_id";
    return self::find_by_sql($sql);
  }

  static public function find_by_id($id) {
    $sql = "SELECT r.*, u.username FROM ride r ";
    $sql .= "LEFT JOIN user u ON r.created_by = u.user_id ";
    $sql .= "WHERE r.ride_id='" . self::$database->escape_string($id) . "'";
    $obj_array = self::find_by_sql($sql);
    if(!empty($obj_array)) {
      return array_shift($obj_array);
    } else {
      return false;
    }
  }

  static protected function instantiate($record) {
    $object = new self;
    foreach($record as $property => $value) {
      if(property_exists($object, $property)) {
        $object->$property = $value;
      }
    }
    return $object;
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
}