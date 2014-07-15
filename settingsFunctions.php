<?php
include("dbConfig.php");
include("dbOpen.php");

ob_start();

$tbl_name="settings"; // Table name

$settingColor 		= "1";
$settingColorValue 	= "";
$settingSize 		= "50";
$settingSpeed 		= "180";
$settingFixation 	= "0,0";

session_start();

// Make sure we have a logged in user and that a function is being called
if(isset($_SESSION['brUserID']) && isset($_SERVER['PATH_INFO'])){
	$userID = $_SESSION['brUserID'];
	
	switch ($_SERVER['PATH_INFO']) {
		case "/SaveSettings":
			SaveSetting($userID, $_REQUEST['settingID'], $_REQUEST['settingValue'], $conn);
			break;
		default:
			break;
	}
		
} else {
	// User is not properly logged in...  exit.
	include("dbClose.php");
	echo "Not Logged In";
	ob_end_flush();
	exit;
}


//Save a setting
function SaveSetting($userID, $settingID, $settingValue, $conn){
	$queryString = "UPDATE settings SET SettingValue = '" . $settingValue . "' WHERE UserID = " . $userID . " AND SettingID = " . $settingID;
	
	$result = $conn->query($queryString);
	
	echo $queryString;
}


include("dbClose.php");
ob_end_flush();

?>
