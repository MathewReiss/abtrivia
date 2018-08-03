<?php

	header("Access-Control-Allow-Headers: Content-Type");
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET");
	header("Content-Type: application/json");

	function pg_connection_string_from_database_url() {
		extract(parse_url($_ENV["DATABASE_URL"]));
		return "user=$user password=$pass host=$host dbname=" . substr($path, 1) . " sslmode=require";
	}

	$conn = pg_connect(getenv("DATABASE_URL"));

	$myquery = "SELECT data FROM questions ORDER BY day DESC LIMIT 1";
	$result = pg_query($conn, $myquery);

	$row = pg_fetch_row($result);
	echo "Results: $row[0]";
	
	pg_close();

?>