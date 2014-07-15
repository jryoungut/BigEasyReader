<?php
ob_start();

$tbl_name="users"; // Table name
$count = 0;

session_start();

include ("dbConfig.php");
include ("dbOpen.php");


// Define $myemail and $mypassword
$myemail=$_POST['myemail'];
$mypassword=$_POST['mypassword'];

// clean the input variables
$myemail = str_replace("'", "", $myemail);
$mypassword = str_replace("'", "", $mypassword);
$myemail = str_replace(";", "", $myemail);
$mypassword = str_replace(";", "", $mypassword);
$myemail = stripslashes($myemail);
$mypassword = stripslashes($mypassword);
$myemail = mysqli_real_escape_string($conn, $myemail);
$mypassword = mysqli_real_escape_string($conn, $mypassword);


$encrypted_mypassword = md5($mypassword);

$query = "SELECT * FROM $tbl_name WHERE Email='$myemail' and password='$encrypted_mypassword'";
$result = $conn->query($query);
if($result === false) {
	trigger_error('Wrong SQL: ' . $query . ' Error: ' . $conn->error, E_USER_ERROR);
}
else {
	$last_inserted_id = $conn->insert_id;
	$affected_rows = $conn->affected_rows;
	if($affected_rows==1){
		if ($row = $result->fetch_assoc()){
			if ($row['IsSuperUser'] == 1){
				$_SESSION['SUPERUSER'] = 1;
			}
			else{
				$_SESSION['SUPERUSER'] = 0;
			}
			$_SESSION['brUserID'] = $row['ID'];
			header("location:pages.php");
		}
	}
	else{
		header("location:login.php?e=1");
	}
}


//$result = mysql_query($sql);

// Mysql_num_row is counting table row
//if($result != FALSE){
//	$count = mysql_num_rows($result);
//}

// If result matched $myemail and $mypassword, table row must be 1 row

ob_end_flush();
?>