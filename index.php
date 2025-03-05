<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Soccer Schedule</title>
</head>
<body>
	<h1>Soccer Schedule</h1>
<?php

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

			// Convert start/end times to minutes since midnight
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
	return ($hours * 60) + $minutes;
}

function formatTime($minutes) {
	$hours = floor($minutes / 60);
	$mins = $minutes % 60;
	$period = ($hours >= 12) ? "PM" : "AM";
	$hours = ($hours % 12) ?: 12; // Convert 0 to 12 for AM times
	return sprintf("%d:%02d %s", $hours, $mins, $period);
}

$schedule = readCSV("schedule.csv");
foreach ($schedule as $date => $games): ?>
	<h2>Games on <?php echo $date; ?></h2>
	<table>
		<tr>
			<th>Start Time</th>
			<th>End Time</th>
			<th>Child</th>
			<th>Field</th>
			<th>Opponent</th>
			<th>Location</th>
		<th></th>
	</tr>
<?php foreach ($games as $game): ?>
		<tr>
			<td><?php echo formatTime($game['StartTime']); ?></td>
			<td><?php echo formatTime($game['EndTime']); ?></td>
			<td><?php echo htmlspecialchars($game['Child']); ?></td>
			<td><a href='spring-2025-field-<?php echo htmlspecialchars($game['Field']); ?>.jpg'><?php echo htmlspecialchars($game['Field']); ?></a></td>
			<td><?php echo htmlspecialchars($game['Opponent']); ?></td>
			<td><?php echo htmlspecialchars($game['Location']); ?></td>
			<td><b><?php echo htmlspecialchars($game['Snack']); ?></b></td>
		</tr>
<?php endforeach; ?>
	</table>
<?php endforeach; ?>
</body>
</html>

