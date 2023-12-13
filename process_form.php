<?php
// Include database connection file if necessary
include 'database_connection.php';

// Sample events array (replace this with database queries)
$events = [];

// Check if the events array is set and not null
if (isset($events) && is_array($events)) {
    // Handle form submissions to add new events
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['clear_history'])) {
            // Handle clearing past events from the database
            $currentDateTime = date("Y-m-d H:i:s");
            $sql = "DELETE FROM past_events WHERE CONCAT(date, ' ', time) < ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $currentDateTime);
            $stmt->execute();
            $stmt->close();

            // Refresh the page after clearing history
            header("Location: index.php");
            exit();
        } else {
            $newEvent = [
                'title' => $_POST['title'],
                'date' => $_POST['date'],
                'time' => $_POST['time'],
                'location' => $_POST['location'],
                'description' => $_POST['description'],
            ];

            $currentDateTime = date("Y-m-d H:i:s");
            $eventDateTime = $newEvent['date'] . ' ' . $newEvent['time'];

            // Choose the table based on whether the event is upcoming or past
            $upcoming_event = (strtotime($eventDateTime) > strtotime($currentDateTime)) ? true : false;
        }

        // Save the new event to the database
        if ($upcoming_event) {
            $sql = "INSERT INTO upcoming_events (title, date, time, location, description) VALUES (?, ?, ?, ?, ?)";
        } else {
            $sql = "INSERT INTO past_events (title, date, time, location, description) VALUES (?, ?, ?, ?, ?)";
        }

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $newEvent['title'], $newEvent['date'], $newEvent['time'], $newEvent['location'], $newEvent['description']);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Event added successfully";
        } else {
            echo "Error adding event: " . $stmt->error;
        }
        $stmt->close();
    }

    // Fetch events from the database (replace this with your specific logic)
    $sql = "SELECT * FROM upcoming_events";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }

    // Check for events with a time less than or equal to the current time
    $currentDateTime = date("Y-m-d H:i:s");
    $sql = "SELECT * FROM upcoming_events WHERE CONCAT(date, ' ', time) <= ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $currentDateTime);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    $databaseEvents = [];

    while ($row = $result->fetch_assoc()) {
        $databaseEvents[] = $row;
    }
}

$conn->close();
?>