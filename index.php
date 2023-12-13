<?php
include 'process_form.php';
include 'database_connection.php';

// Function to fetch all events from the database
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Manager</title>
    <!-- Add any necessary CSS styles here -->
</head>
<body>
    <h1>Event Manager</h1>   
<!-- Display past events -->
<h2>Past Events</h2>
<ul id="pastEvents">
    <?php
    $sql = "SELECT * FROM past_events WHERE CONCAT(date, ' ', time) < NOW()";
    $result = $conn->query($sql);

    while ($event = $result->fetch_assoc()):
    ?>
        <li>
            <strong><?= $event['title']; ?></strong>
            <br>Date: <?= $event['date']; ?> | Time: <?= $event['time']; ?>
            <br>Location: <?= $event['location']; ?>
            <br>Description: <?= $event['description']; ?>
            <br>
            <em>Past Event</em>
        </li>
    <?php endwhile; ?>
</ul>

<!-- Display upcoming events -->
<h2>Upcoming Events</h2>
<ul id="upcomingEvents">
    <?php
    $sql = "SELECT * FROM upcoming_events WHERE CONCAT(date, ' ', time) > NOW()";
    $result = $conn->query($sql);

    while ($event = $result->fetch_assoc()):
    ?>
        <li data-date-time="<?= $event['date'] . ' ' . $event['time']; ?>">
            <strong><?= $event['title']; ?></strong>
            <br>Date: <?= $event['date']; ?> | Time: <?= $event['time']; ?>
            <br>Location: <?= $event['location']; ?>
            <br>Description: <?= $event['description']; ?>
        </li>
    <?php endwhile; ?>
</ul>


   
    <!-- Form to add new events -->
    <h2>Add New Event</h2>
    <form action="index.php" method="post">
        <label for="title">Event Title:</label>
        <input type="text" name="title" id="title" required>
        
        <label for="date">Date:</label>
        <input type="date" name="date" id="date" required>
        
        <label for="time">Time:</label>
        <input type="time" name="time" id="time" required>
        
        <label for="location">Location:</label>
        <input type="text" name="location" id="location" required>
        
        <label for="description">Description:</label>
        <textarea name="description" id="description" required></textarea>

        <button type="submit">Add Event</button>
        
    </form>

    <!-- Form to clear past events -->
    <form action="index.php" method="post">
            <button type="submit" name="clear_history">Clear History</button>
    </form>

    <!-- Refresh button -->
    <button><a href="index.php">Refresh</a></button>

<script>
    // JavaScript code to move upcoming events to past events when the date and time are passed
    var upcomingEvents = document.getElementById('upcomingEvents');
    var pastEvents = document.getElementById('pastEvents');
    var currentDateAndTime = new Date();

    for (var i = 0; i < upcomingEvents.children.length; i++) {
        var eventLi = upcomingEvents.children[i];
        var eventDateTime = new Date(eventLi.getAttribute('data-date-time'));

        if (eventDateTime < currentDateAndTime) {
            // Move the event to past events
            pastEvents.appendChild(eventLi);
            i--; // Adjust the loop index since the length of upcoming events has changed
            
            
        }
        if (eventDateTime == currentDateAndTime) {
            for (var i = 0; 1 < 2; i++) {
                location.reload();

            }
        }
    }
</script>

</body>
</html>

