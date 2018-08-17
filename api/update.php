<?php

	header("Access-Control-Allow-Headers: Content-Type");
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET");
	header("Content-Type: application/json");

	$fitbit = $_GET['fitbit'];
	$correct = $_GET['correct'];

	$conn = pg_connect(getenv("DATABASE_URL"));

	$myquery = "SELECT * FROM users WHERE fitbit='{$fitibt}';";
	$result = pg_query($conn, $myquery);

	$row = pg_fetch_row($result);

	echo "<script>console.log('Row Returned: " . $row . "');</script>";

	$today = date('Y-m-d');

	$streak = $row ? $row[1] : 0;
	$accuracy = $row ? $row[2] : 0;
	$continuous = $row ? $row[3] : 0;
	$display = $row ? $row[4] : $_GET['display'];
	$lastgame = $row ? $row[5] : $today;
	$currentstreak = $row ? $row[6] : 0;
	$numgamesplayed = $row ? $row[7] : 0;
	$numcorrect = $row ? $row[8] : $correct;
	$currentcontinuous = $row ? $row[9] : 0;
	$lastgameperfect = $row ? $row[10] : false;

	echo "<script>console.log('Row: " . $streak . ", " . $accuracy . ", " . $continuous . "');</script>";

	if($today == $lastgame + (date(w) == 1 ? 3 : 1)) {
		echo "<script>console.log('Played continuously');</script>";
		$currentcontinuous++;
		if($lastgameperfect) {
			$currentstreak += $correct;
		}
	} else {
		$currentstreak = $correct;
		$currentcontinuous = 1;
	}

	$continuous = max($continuous, $currentcontinuous);
	$streak = max($streak, $currentstreak);

	$lastgameperfect = ($correct == 6);

	$numcorrect += $correct;
	$numgamesplayed++;

	$accuracy = max($accuracy, $numcorrect / ($numgamesplayed*6));

	$lastgame = $today;

	$myquery = "INSERT INTO users 
				VALUES({$fitbit}, {$streak}, {$accuracy}, {$continuous}, {$display}, {$lastgame}, {$currentstreak}, {$numgamesplayed}, {$numcorrect}, {$currentcontinuous}, {$lastgameperfect}) 
				ON CONFLICT (fitbit) DO UPDATE 
				SET streak = EXCLUDED.streak, 
					accuracy = EXCLUDED.accuracy, 
					continuous = EXCLUDED.continuous, 
					displayname = EXCLUDED.displayname, 
					lastgame = EXCLUDED.lastgame, 
					currentstreak = EXCLUDED.currentstreak, 
					numgamesplayed = EXCLUDED.numgamesplayed, 
					numcorrect = EXCLUDED.numcorrect, 
					currentcontinuous = EXCLUDED.currentcontinuous, 
					lastgameperfect = EXCLUDED.lastgameperfect;";

	echo $myquery;

	$result = pg_query($conn, $myquery);

	pg_close();

?>