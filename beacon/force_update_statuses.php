<?php
// Force update all event statuses - Direct database approach
// Database credentials - update these if needed
$host = '127.0.0.1';
$dbname = 'beacon_db'; // From Database.php config
$username = 'root';
$password = '';

try {
    $mysqli = new mysqli($host, $username, $password, $dbname);
    if ($mysqli->connect_error) {
        throw new Exception("Connection failed: " . $mysqli->connect_error);
    }
    $mysqli->set_charset("utf8mb4");
    
    echo "Starting force update of all event statuses...\n\n";
    
    // Get all events
    $result = $mysqli->query("SELECT event_id, event_name, date, time, end_date, end_time, status FROM events");
    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
    
    $updatedCount = 0;
    $errorCount = 0;
    
    // Set timezone to match your location (Philippines is UTC+8)
    // Change this to your actual timezone if different
    date_default_timezone_set('Asia/Manila');
    $now = time();
    
    echo "Current timezone: " . date_default_timezone_get() . "\n";
    echo "Current time: " . date('Y-m-d H:i:s', $now) . "\n\n";
    
    foreach ($events as $event) {
        $eventId = $event['event_id'];
        $oldStatus = $event['status'] ?? 'upcoming';
        
        try {
            // Parse start time
            $eventTime = trim($event['time'] ?? '00:00:00');
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
            
            // Calculate status
            // Rules:
            // 1. If current time > end date/time: status = 'ended'
            // 2. If current time >= start date/time AND current time <= end date/time: status = 'ongoing'
            // 3. If current time < start date/time: status = 'upcoming'
            $newStatus = 'upcoming';
            
            // Debug output for event ID 5
            if ($eventId == 5) {
                echo "\nDEBUG Event ID 5:\n";
                echo "  Now: " . date('Y-m-d H:i:s', $now) . " (timestamp: $now)\n";
                echo "  Start: " . date('Y-m-d H:i:s', $eventTimestamp) . " (timestamp: $eventTimestamp)\n";
                echo "  End: " . date('Y-m-d H:i:s', $endDateTime) . " (timestamp: $endDateTime)\n";
                echo "  Now > End? " . ($now > $endDateTime ? 'YES' : 'NO') . "\n";
                echo "  Now >= Start && Now <= End? " . (($now >= $eventTimestamp && $now <= $endDateTime) ? 'YES' : 'NO') . "\n";
            }
            
            if ($now > $endDateTime) {
                $newStatus = 'ended';
            } elseif ($now >= $eventTimestamp && $now <= $endDateTime) {
                $newStatus = 'ongoing';
            }
            
            // Update in database
            $stmt = $mysqli->prepare("UPDATE events SET status = ? WHERE event_id = ?");
            $stmt->bind_param("si", $newStatus, $eventId);
            $result = $stmt->execute();
            $stmt->close();
            
            if ($result) {
                if ($oldStatus !== $newStatus) {
                    echo "Event ID {$eventId} ('{$event['event_name']}'): {$oldStatus} -> {$newStatus}\n";
                    $updatedCount++;
                } else {
                    echo "Event ID {$eventId} ('{$event['event_name']}'): Already {$newStatus}\n";
                }
            } else {
                echo "ERROR: Failed to update event ID {$eventId}\n";
                $errorCount++;
            }
        } catch (\Exception $e) {
            echo "ERROR: Event ID {$eventId}: " . $e->getMessage() . "\n";
            $errorCount++;
        }
    }
    
    echo "\n\nForce update complete!\n";
    echo "Total events: " . count($events) . "\n";
    echo "Updated: {$updatedCount}\n";
    echo "Errors: {$errorCount}\n";
    
} catch (Exception $e) {
    echo "Database connection error: " . $e->getMessage() . "\n";
    echo "\nPlease update the database credentials in this script.\n";
} finally {
    if (isset($mysqli)) {
        $mysqli->close();
    }
}
?>
