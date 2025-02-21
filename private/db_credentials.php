<?php

define("DB_SERVER", "localhost");
define("DB_USER", "");
define("DB_PASS", "");
define("DB_NAME", "wandrbae");

<?php

// This guide demonstrates the five fundamental steps
// of database interaction using PHP.

// Credentials
$dbhost = '';
$dbuser = '';
$dbpass = '';
$dbname = '';

// 1. Create a database connection
$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// 2. Perform database query

// 3. Use returned data (if any)

// 4. Release returned data

// 5. Close database connection
mysqli_close($connection);

?>


?>