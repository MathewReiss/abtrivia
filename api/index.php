<?php

	header("Access-Control-Allow-Headers: Content-Type");
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET");
	header("Content-Type: application/json");

	$conn = pg_connect(getenv("DATABASE_URL"));

	$myquery = "SELECT * FROM questions ORDER BY day DESC LIMIT 1";
	$result = pg_query($conn, $myquery);

	$row = pg_fetch_row($result);
	echo '{ "date" : "' . $row[0] . '", "questions" : ' . html_entity_decode(str_replace("&quot;" , "'", $row[1])) . ' }';
	
	pg_close();

?>