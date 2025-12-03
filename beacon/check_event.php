<?php
// Check event ID 5 details
$host = '127.0.0.1';
$dbname = 'beacon_db';
$username = 'root';
$password = '';

$mysqli = new mysqli($host, $username, $password, $dbname);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$result = $mysqli->query("SELECT event_id, event_name, date, time, end_date, end_time, status FROM events WHERE event_id = 5");
$event = $result->fetch_assoc();

echo "Event Data:\n";
echo json_encode($event, JSON_PRETTY_PRINT);
echo "\n\n";

$now = time();
echo "Current time: " . date('Y-m-d H:i:s', $now) . " (timestamp: $now)\n\n";

// Parse start time
$eventTime = trim($event['time']);
if (stripos($eventTime, 'AM') !== false || stripos($eventTime, 'PM') !== false) {
    $eventDateTime = $event['date'] . ' ' . $eventTime;
    $eventTimestamp = strtotime($eventDateTime);
} else {
    if (substr_count($eventTime, ':') == 2) {
        $timeParts = explode(':', $eventTime);
        $eventTime = $timeParts[0] . ':' . $timeParts[1];
    }
    $eventDateTime = $event['date'] . ' ' . $eventTime;
    $eventTimestamp = strtotime($eventDateTime);
}

echo "Event start: " . date('Y-m-d H:i:s', $eventTimestamp) . " (timestamp: $eventTimestamp)\n";

// Parse end time
$endDateTime = null;
if (!empty($event['end_date']) && !empty($event['end_time'])) {
    $endTime = trim($event['end_time']);
    if (stripos($endTime, 'AM') !== false || stripos($endTime, 'PM') !== false) {
        $endDateTime = strtotime($event['end_date'] . ' ' . $endTime);
    } else {
        if (substr_count($endTime, ':') == 2) {
            $timeParts = explode(':', $endTime);
            $endTime = $timeParts[0] . ':' . $timeParts[1];
        }
        $endDateTime = strtotime($event['end_date'] . ' ' . $endTime);
    }
} elseif (!empty($event['end_time'])) {
    $endTime = trim($event['end_time']);
    if (stripos($endTime, 'AM') !== false || stripos($endTime, 'PM') !== false) {
        $endDateTime = strtotime($event['date'] . ' ' . $endTime);
    } else {
        if (substr_count($endTime, ':') == 2) {
            $timeParts = explode(':', $endTime);
            $endTime = $timeParts[0] . ':' . $timeParts[1];
        }
        $endDateTime = strtotime($event['date'] . ' ' . $endTime);
    }
} elseif (!empty($event['end_date'])) {
    $endDateTime = strtotime($event['end_date'] . ' 23:59:59');
} else {
    $endDateTime = strtotime($event['date'] . ' 23:59:59');
}

echo "Event end: " . date('Y-m-d H:i:s', $endDateTime) . " (timestamp: $endDateTime)\n\n";

// Calculate status
$newStatus = 'upcoming';
if ($now > $endDateTime) {
    $newStatus = 'ended';
} elseif ($now >= $eventTimestamp && $now <= $endDateTime) {
    $newStatus = 'ongoing';
}

echo "Current status in DB: " . $event['status'] . "\n";
echo "Calculated status: " . $newStatus . "\n";
echo "Should update? " . ($event['status'] !== $newStatus ? 'YES' : 'NO') . "\n\n";

// Check if event should be ended based on user's expectation
echo "Time comparison:\n";
echo "  Now: " . $now . "\n";
echo "  End: " . $endDateTime . "\n";
echo "  Difference: " . ($now - $endDateTime) . " seconds\n";
echo "  Is now > end? " . ($now > $endDateTime ? 'YES' : 'NO') . "\n";

// If user says it should be ended, maybe the date is wrong or timezone issue
// Let's check if using just the date (without time) would make it ended
$eventDateOnly = strtotime($event['date'] . ' 00:00:00');
$todayDateOnly = strtotime(date('Y-m-d') . ' 00:00:00');
echo "\nDate-only comparison:\n";
echo "  Event date: " . date('Y-m-d', $eventDateOnly) . "\n";
echo "  Today date: " . date('Y-m-d', $todayDateOnly) . "\n";
echo "  Is event date < today? " . ($eventDateOnly < $todayDateOnly ? 'YES' : 'NO') . "\n";

$mysqli->close();
?>

