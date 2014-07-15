<?php
ob_start();

$tbl_name="users"; // Table name
$count = 0;

session_start();

include ("dbConfig.php");
include ("dbOpen.php");
include ("utils.php");


// Define $myemail and $mypassword
$myemail=$_POST['myemail'];
$mypassword=$_POST['mypassword'];
$myfirstname=$_POST['myfirstname'];
$mylastname=$_POST['mylastname'];
$mybirthdate=$_POST['mybirthdate'];

// clean the input variables
$myemail = CleanInput($myemail, $conn);
$myfirstname = CleanInput($myfirstname, $conn);
$mylastname = CleanInput($mylastname, $conn);

$encrypted_mypassword = md5($mypassword);

if($mybirthdate == ""){
	$mybirthdate = "0000-00-00 00:00:00";
}
else {
	$time = strtotime($mybirthdate);
	$mybirthdate = date('Y-m-d',$time);
}

$query = "SELECT * FROM $tbl_name WHERE Email='$myemail'";
//$result = mysql_query($sql);
if($conn->query($query) === false) {
	trigger_error('Wrong SQL: ' . $query . ' Error: ' . $conn->error, E_USER_ERROR);
} else {
	$affected_rows = $conn->affected_rows;
	if($affected_rows == 1)
	{
		//Oops, already exists in the database.
		header("location:signup.php?e=1");
	}
	else
	{
		//OK.  It doesn't already exist.  Let's try inserting it.
		$query = "INSERT INTO $tbl_name (Email, Password, FirstName, LastName, BirthDate, IsResetRequired, IsActive) VALUES ('$myemail', '$encrypted_mypassword', '$myfirstname', '$mylastname', '$mybirthdate', 0, 1)";

		if($conn->query($query) === false) {
			//trigger_error('Wrong SQL: ' . $query . ' Error: ' . $conn->error, E_USER_ERROR);
			header("location:signup.php?e=2");
		} else {
			$affected_rows = $conn->affected_rows;
			$userID = $conn->insert_id;
						
			//OK, now we have a new user, lets add the corresponding settings
			$query = "INSERT INTO settings (UserID, SettingID, SettingValue, DateCreated) ";
			$query .= "VALUES "; 
			$query .= "($userID, 1, '1', NOW()), ";
			$query .= "($userID, 2, '60', NOW()), ";
			$query .= "($userID, 3, '180', NOW()), ";
			$query .= "($userID, 4, '0,0', NOW())";
			if($conn->query($query) === false) {
				header("location:signup.php?e=2");
			} else {
				header("location:login.php");
			}
				
		}
	}
}


ob_end_flush();

?>