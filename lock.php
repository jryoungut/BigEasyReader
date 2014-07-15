<?php
include('dbConfig.php');
include ('dbOpen.php');

session_start();

$user_check = $_SESSION['brUserID'];

$query = "SELECT * FROM users WHERE ID = '" . $user_check . "'";
$result = $conn->query($query);
if($result === false) {
	trigger_error('Wrong SQL: ' . $query . ' Error: ' . $conn->error, E_USER_ERROR);
} else {
	$rows_returned = $result->num_rows;
	$result->data_seek(0);
	while($row = $result->fetch_assoc())
	{
		$login_session = $row['ID'];
	}
	if(!isset($login_session))
	{
		header("Location: login.php?e=4");
	}
}
?>