<?php
	include("dbConfig.php");
	include("dbOpen.php");
    //phpinfo();
	$htmlStr = '';
    
    $query = "SELECT ID, Title, Location FROM books WHERE IsActive = 1";
	$result = $conn->query($query);
	
	if($result === false) {
		trigger_error('Wrong SQL: ' . $query . ' Error: ' . $conn->error, E_USER_ERROR);
	} else {
		$rows_returned = $result->num_rows;
	}
	
	$result->data_seek(0);
	
	while($row = $result->fetch_assoc())
	{
		$htmlStr .= '<li><a href="' . $row['Location'] . '" data-bookid="' . $row['ID'] . '" data-ajax="false">' . $row['Title'] . '</a></li>';
	}
	
	echo($htmlStr);
	include("dbClose.php");
?>