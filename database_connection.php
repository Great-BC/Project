<?php
$servername = "localhost";
$username = "root";
$password = "Incidious@101";
$dbname = "php_event_manager";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define table names
$upcomingEventsTable = "upcoming_events";
$pastEventsTable = "past_events";
?>