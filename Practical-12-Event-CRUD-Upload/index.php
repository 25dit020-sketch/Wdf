<?php
// Practical 12: Campus Event Management (List / Read View)
session_start();
require_once 'db.php';

$query = "SELECT * FROM events ORDER BY event_date ASC";
$result = mysqli_query($conn, $query);
$totalEvents = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Practical 12 - Campus Event Management | StudentHub</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <div class="container">
    
    <div class="top-bar">
      <div>
        <h2>Campus Events &amp; Workshops</h2>
        <span style="font-size: 13px; color: #666;">Active Events: <strong><?php echo $totalEvents; ?></strong></span>
      </div>
      <div>
        <a href="add_event.php" class="btn btn-primary">+ Create New Event</a>
      </div>
    </div>

    <!-- Feedback Message Alerts -->
    <?php if (isset($_GET['msg'])): ?>
      <div class="alert alert-success"><?php echo htmlspecialchars($_GET['msg']); ?></div>
    <?php endif; ?>

    <?php if (isset($_GET['err'])): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['err']); ?></div>
    <?php endif; ?>

    <!-- Events Cards Grid -->
    <?php if ($totalEvents > 0): ?>
      <div class="events-grid">
        <?php while ($event = mysqli_fetch_assoc($result)): 
          $posterPath = 'uploads/' . $event['poster_image'];
          if (empty($event['poster_image']) || !file_exists($posterPath)) {
              $posterPath = 'uploads/default_event.svg';
          }
        ?>
          <div class="event-card">
            <img src="<?php echo htmlspecialchars($posterPath); ?>" alt="Event Poster" class="event-poster">
            <div class="event-body">
              <span class="event-badge"><?php echo htmlspecialchars($event['category']); ?></span>
              <h3 class="event-title"><?php echo htmlspecialchars($event['title']); ?></h3>
              <div class="event-meta"><strong>Date:</strong> <?php echo htmlspecialchars($event['event_date']); ?></div>
              <div class="event-meta"><strong>Venue:</strong> <?php echo htmlspecialchars($event['venue']); ?></div>
              <div class="event-meta"><strong>Capacity:</strong> <?php echo htmlspecialchars($event['max_seats']); ?> Seats</div>
              <p class="event-desc"><?php echo htmlspecialchars($event['description']); ?></p>
              
              <div class="event-actions">
                <a href="edit_event.php?id=<?php echo $event['id']; ?>" class="btn btn-edit">Edit</a>
                <a href="delete_event.php?id=<?php echo $event['id']; ?>" 
                   onclick="return confirm('Are you sure you want to delete event: <?php echo htmlspecialchars(addslashes($event['title'])); ?>?');" 
                   class="btn btn-delete">Delete</a>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <p style="text-align: center; color: #777; padding: 30px;">No events scheduled yet. <a href="add_event.php">Click here to add one.</a></p>
    <?php endif; ?>

    <div style="margin-top: 30px; font-size: 13px;">
      <a href="../Practical-10-Secure-Auth-Sessions/admin_dashboard.php">&larr; Return to Admin Dashboard</a>
    </div>

  </div>

</body>
</html>
