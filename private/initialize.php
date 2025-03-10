<?php


  // Assign file paths to PHP constants
  // __FILE__ returns the current path to this file
  // dirname() returns the path to the parent directory

  session_start();

  define("PRIVATE_PATH", dirname(__FILE__));
  define("PROJECT_PATH", dirname(PRIVATE_PATH));
  define("PUBLIC_PATH", PROJECT_PATH . '/public');
  define("SHARED_PATH", PRIVATE_PATH . '/shared');

  $public_end = strpos($_SERVER['SCRIPT_NAME'], '/public') + 7;
  $doc_root = substr($_SERVER['SCRIPT_NAME'], 0, $public_end);
  define("WWW_ROOT", $doc_root);

  
  require_once('functions.php');
  require_once('status_error_functions.php');
  require_once('database.php');
  require_once('query_functions.php');

  foreach(glob('classes/*.class.php') as $file) {
  require_once($file);
  }

  function my_autoload($class) {
    if(preg_match('/\A\w+\Z/', $class)) {
      include('classes/' . $class . '.class.php');
    }
  }
  spl_autoload_register('my_autoload');
  $db = db_connect();
  ride::set_database($db);

  $session = new Session;

  DatabaseObject::set_database($db);
?>