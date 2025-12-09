<?php
// Manually update event ID 5 to "ended" status
$host = '127.0.0.1';
$dbname = 'beacon_db';
$username = 'root';
$password = '';

$mysqli = new mysqli($host, $username, $password, $dbname);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Update event ID 5 to "ended"
$stmt = $mysqli->prepare("UPDATE events SET status = 'ended' WHERE event_id = 5");
$result = $stmt->execute();
$stmt->close();

if ($result) {
    echo "Event ID 5 has been updated to 'ended' status.\n";
    
    // Verify the update
    $result = $mysqli->query("SELECT event_id, event_name, status FROM events WHERE event_id = 5");
    $event = $result->fetch_assoc();
    echo "Verification: Event ID {$event['event_id']} ('{$event['event_name']}') status is now: {$event['status']}\n";
} else {
    echo "Failed to update event ID 5.\n";
}

$mysqli->close();
?>






