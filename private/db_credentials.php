<?php

  define("DB_SERVER", "localhost");
  define("DB_USER", "olly");
  define("DB_PASS", "Frogs123!");
  define("DB_NAME", "wandrbae_test");
?>
<?php

  // This guide demonstrates the five fundamental steps
  // of database interaction using PHP.

  // Credentials
  $dbhost = 'localhost';
  $dbuser = 'olly';
  $dbpass = 'Frogs123!';
  $dbname = 'wandrbae_test';

  // 1. Create a database connection
  $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

  // 2. Perform database query
  $query = "SELECT * FROM user";
  $result_set = mysqli_query($connection, $query);

  // 3. Use returned data (if any)

  // 4. Release returned data
  mysqli_free_result($result_set);

  // 5. Close database connection
  mysqli_close($connection);

?>