<?php
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

/* check connection */
if ($conn->connect_error) {
	trigger_error('Database connection failed: '  . $conn->connect_error, E_USER_ERROR);
}
?>