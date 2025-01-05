<?php

error_reporting(~E_DEPRECATED & ~E_NOTICE);

define('DBHOST', 'localhost');
define('DBUSER', 'root');
define('DBPASS', '');
define('DBNAME', 'gpa');

// Create connection
$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
