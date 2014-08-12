<?php
// include("dbConfig.php");
// include("dbOpen.php");

ob_start();

$tbl_name="settings"; // Table name

$userID 			= 0;
$user_name 			= "";
$settingColor 		= "1";
$settingColorValue 	= "";
$settingSize 		= "50";
$settingSpeed 		= "180";
$settingFixation 	= "0,0";

session_start();

// Make sure we have a logged in user and that a function is being called
if(isset($_SESSION['brUserID'])){
	$userID = $_SESSION['brUserID'];
} //else {
	// User is not properly logged in...  exit.
	//include("dbClose.php");
	//echo "=== Not Logged In ===";
	//header("location:index.php");
	//ob_end_flush();
	//exit;
//}


//Get the setting values
function GetSettingsValues($conn){
	//################################################################
	//Get Setting for user
	$userID = $_SESSION['brUserID'];
	$settingColor = '1';
	$settingSize = '60';
	$settingSpeed = '210';
	$settingFixation = '0,0';
	
	$query = "SELECT SettingID, SettingValue FROM settings WHERE UserID = '" . $userID . "'";
	$result = $conn -> query($query);
	if ($result === false) {
		trigger_error('Wrong SQL: ' . $query . ' Error: ' . $conn -> error, E_USER_ERROR);
	} else {
		$rows_returned = $result -> num_rows;
	}
	$result -> data_seek(0);
	while ($row = $result -> fetch_assoc()) {
		switch($row['SettingID']){
			case "1":
				$settingColor = $row['SettingValue'];
				break;
			case "2":
				$settingSize = $row['SettingValue'];
				break;
			case "3":
				$settingSpeed = $row['SettingValue'];
						break;
			case "4":
				$settingFixation = $row['SettingValue'];
				break;
		}
	}
	
	switch ($settingColor) {
		case "1" :
			$settingColorValue = "Dark Theme";
			break;
		case "2" :
			$settingColorValue = "Light Theme";
			break;
	}
	
	$settingsArray = array(
		"settingColor" => $settingColor,
		"settingColorValue" => $settingColorValue,
		"settingSize" => $settingSize,
		"settingSpeed" => $settingSpeed,
		"settingFixation" => $settingFixation
				
	);
	return $settingsArray;
}

function GetUserFullName($conn){
	$userID = $_SESSION['brUserID'];
	$query = "SELECT * FROM users WHERE ID = '" . $userID . "'";
	$result = $conn -> query($query);
	if ($result === false) {
		trigger_error('Wrong SQL: ' . $query . ' Error: ' . $conn -> error, E_USER_ERROR);
	} else {
		$rows_returned = $result -> num_rows;
	}
	$result -> data_seek(0);
	while ($row = $result -> fetch_assoc()) {
		$user_name = $row['FirstName'] . " " . $row['LastName'];
	}
}

// include("dbClose.php");
ob_end_flush();

?>
