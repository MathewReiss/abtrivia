<?php

	header("Access-Control-Allow-Headers: Content-Type");
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET");
	header("Content-Type: application/json");

	$conn = pg_connect(getenv("DATABASE_URL"));

	$day = $_GET['day'];

	$myquery = "SELECT * FROM questions WHERE day='{$day}' LIMIT 1;";
	$result = pg_query($conn, $myquery);

	if(pg_num_rows($result) == 0){
		$myquery = "SELECT * FROM questions ORDER BY day DESC LIMIT 1;";
		$result = pg_query($conn, $myquery);
	}

	$row = pg_fetch_row($result);
	echo '{ "date" : "' . $row[0] . '", "questions" : ' . html_entity_decode(str_replace("&quot;" , "'", str_replace("&#039;", "'", $row[1]))) . ' }';
	
	pg_close();

?>
