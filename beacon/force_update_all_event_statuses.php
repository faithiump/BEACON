<?php
/**
 * Force update all event statuses
 * Run this script to update all event statuses in the database
 * Usage: php force_update_all_event_statuses.php
 */

require __DIR__ . '/vendor/autoload.php';

// Bootstrap CodeIgniter
$pathsConfig = FCPATH . '../app/Config/Paths.php';
require realpath($pathsConfig) ?: $pathsConfig;
$paths = new Config\Paths();
$bootstrap = rtrim($paths->systemDirectory, '\\/ ') . '/bootstrap.php';
require realpath($bootstrap) ?: $bootstrap;

$app = Config\Services::codeigniter();
$app->initialize();

// Get EventModel
$eventModel = new \App\Models\EventModel();
$db = \Config\Database::connect();

// Get all events
$events = $db->table('events')->get()->getResultArray();

echo "Found " . count($events) . " events to update.\n";

$updated = 0;
$errors = 0;

foreach ($events as $event) {
    $eventId = $event['event_id'] ?? $event['id'];
    if (!$eventId) {
        continue;
    }
    
    try {
        $eventModel->updateEventStatus($eventId);
        $updated++;
        echo "Updated event ID: $eventId\n";
    } catch (\Exception $e) {
        $errors++;
        echo "Error updating event ID $eventId: " . $e->getMessage() . "\n";
    }
}

echo "\nUpdate complete!\n";
echo "Updated: $updated\n";
echo "Errors: $errors\n";






