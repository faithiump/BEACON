<?php
// Check status ENUM in events table
$host = '127.0.0.1';
$dbname = 'beacon_db';
$username = 'root';
$password = '';

$mysqli = new mysqli($host, $username, $password, $dbname);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$result = $mysqli->query("SHOW COLUMNS FROM events WHERE Field='status'");
$row = $result->fetch_assoc();

echo "Status Column Info:\n";
echo json_encode($row, JSON_PRETTY_PRINT);
echo "\n\n";

// Extract ENUM values
if (preg_match("/enum\((.*)\)/i", $row['Type'], $matches)) {
    $enumValues = str_replace("'", "", $matches[1]);
    $enumArray = explode(',', $enumValues);
    echo "ENUM Values: " . implode(', ', $enumArray) . "\n";
    
    if (!in_array('ongoing', $enumArray)) {
        echo "\nWARNING: 'ongoing' is NOT in the ENUM!\n";
    }
    if (!in_array('ended', $enumArray)) {
        echo "WARNING: 'ended' is NOT in the ENUM!\n";
    }
}

$mysqli->close();
?>


