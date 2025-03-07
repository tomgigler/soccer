<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Gantt Chart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;

        }
        .gantt-container {
            overflow-x: auto;
            padding: 10px;
            width: 95%;
        }
        .gantt-chart {
            display: grid;
            grid-template-columns: repeat(18, 1fr);
            gap: 2px;
            align-items: center;
            width: 90%;
            border: 1px solid #ccc;
            padding: 10px;
        }
        .gantt-header {
            padding: 8px;
            text-align: center;
            font-weight: bold;
            background: #f3f3f3;
            white-space: nowrap;
        }
        .gantt-bar {
            height: 20px;
            text-align: center;
            color: white;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center;"><a href="index.php">Simple Gantt Chart</a></h2>
    <div class="gantt-container">
    <div class="gantt-chart">
<?php

$color = [
    "Luke" => "#22CC22",
    "Zack" => "#2196F3",
    "Gage" => "#CC0022"
];

function readCSV($filename) {
	$schedule = [];

	 // Open CSV file
	 if (($handle = fopen($filename, "r")) !== FALSE) {
		$headers = fgetcsv($handle);  // Read the header row

		 while (($data = fgetcsv($handle)) !== FALSE) {
			 $game = array_combine($headers, $data);  // Create associative array per row

			 $date = $game['Date'];  // Extract the date

			// Initialize the date key if not set
			if (!isset($schedule[$date])) {
				$schedule[$date] = [];
			}

			// Convert start/end times to quarter hours since 8am
			$game['StartTime'] = convertToMinutes($game['StartTime']);
			$game['EndTime'] = convertToMinutes($game['EndTime']);

			$schedule[$date][] = $game;
		}

		fclose($handle);
	} else {
		die("Error opening CSV file.");
	}

	return $schedule;
}

function convertToMinutes($timeStr) {
	list($hours, $minutes) = explode(':', $timeStr);
    $time = ((($hours - 8) * 60) + $minutes)/15;
    if($time > 32) $time -= 32;
	return $time;
}

$schedule = readCSV("schedule.csv");
foreach ($schedule as $date => $games) {
        echo '<div class="gantt-header" style="grid-column: span 18;">' . $date . '</div>';
    foreach ($games as $game) {
			if($game['StartTime'] > 0) echo '<div style="grid-column: span ' . $game['StartTime'] . ';"></div>';
            echo '<div class="gantt-bar" style="grid-column: span ' . ($game['EndTime'] - $game['StartTime']) . '; background: ' . $color[$game['Child']] .';">' . $game['Child'] . '</div>';
			if($game['EndTime'] < 18) echo '<div style="grid-column: span ' . (18 - $game['EndTime']) . ';"></div>';
    }
} ?>

    </div>
    </div>
</body>
</html>
