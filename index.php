<?php

	header("Access-Control-Allow-Headers: Content-Type");
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET");
	header("Content-Type: application/json");

	$conn = pg_connect(getenv("DATABASE_URL"));

	if(pg_connection_status() === PGSQL_CONNECTION_OK){
		$myquery = "SELECT data FROM questions ORDER BY day DESC LIMIT 1";
		$result = pg_query($conn, $myquery);

		if(!$result) {
			//Query Error
			exit;
		}

		echo "$pg_fetch_row($result)[0]";
	} else {
		//Connection Error
		exit;
	}

?>