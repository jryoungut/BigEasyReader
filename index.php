<?php
include("dbConfig.php");
include("dbOpen.php");

//variables
session_start();
$displayType = "";
if (isset( $_GET['t'])){
	$displayType = $_GET['t'];
}
$displayData = "";
$user_name = "";
$user_check = 0;

if(isset($_SESSION['brUserID'])){
	$user_check = $_SESSION['brUserID'];
}


//Get logged in user name
$query = "SELECT * FROM users WHERE ID = '" . $user_check . "'";
$result = $conn->query($query);
if($result === false) {
	trigger_error('Wrong SQL: ' . $query . ' Error: ' . $conn->error, E_USER_ERROR);
} else {
	$rows_returned = $result->num_rows;
}
$result->data_seek(0);
while($row = $result->fetch_assoc())
{
	$user_name = $row['FirstName'] . " " . $row['LastName'];
}




include("dbClose.php");
?>
<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Big Easy Reader</title>
    

	<script type="text/javascript">
		var IsLoggedIn =<?php echo($user_check) ?>;
	
	</script>
</head>
<body>
    <div data-role="page" id="pgAll">
        <div data-role="header">
        </div><!-- /header -->

        <div data-role="content">
        	Temp page.
        </div><!-- /content -->

    </div><!-- /page -->

</body>
</html>