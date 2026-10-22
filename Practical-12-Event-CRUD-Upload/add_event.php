<?php
// Practical 12: Create Event with Poster Image Upload
session_start();
require_once 'db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title       = trim($_POST['title'] ?? '');
    $category    = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $eventDate   = trim($_POST['event_date'] ?? '');
    $venue       = trim($_POST['venue'] ?? '');
    $maxSeats    = intval($_POST['max_seats'] ?? 50);

    if (empty($title) || empty($category) || empty($description) || empty($eventDate) || empty($venue)) {
        $error = "Please fill in all mandatory event fields.";
    } else {
        $posterImageName = 'default_event.svg';

        // Check if user uploaded a poster file
        if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['poster']['tmp_name'];
            $fileName    = $_FILES['poster']['name'];
            $fileSize    = $_FILES['poster']['size'];
            $fileExt     = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            $maxFileSize = 2 * 1024 * 1024; // 2 Megabytes

            if (!in_array($fileExt, $allowedExtensions)) {
                $error = "Invalid file type. Allowed formats: JPG, JPEG, PNG, WEBP.";
            } elseif ($fileSize > $maxFileSize) {
                $error = "File size exceeds 2MB limit. Please upload a smaller image.";
            } else {
                // Generate collision-free unique filename
                $newFileName = 'event_' . uniqid() . '.' . $fileExt;
                $uploadDir = __DIR__ . '/uploads/';

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $destPath = $uploadDir . $newFileName;
                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    $posterImageName = $newFileName;
                } else {
                    $error = "Failed to save uploaded file on server. Check folder permissions.";
                }
            }
        }

        // If no upload errors, insert event into database
        if (empty($error)) {
            $createdBy = $_SESSION['user_id'] ?? 1; // Default to admin user ID 1
            $stmt = mysqli_prepare($conn, "INSERT INTO events (title, category, description, event_date, venue, max_seats, poster_image, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sssssisi", $title, $category, $description, $eventDate, $venue, $maxSeats, $posterImageName, $createdBy);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                header("Location: index.php?msg=" . urlencode("Event '$title' published successfully!"));
                exit();
            } else {
                $error = "Database insert failed: " . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create New Event | StudentHub</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <div class="form-container">
    <h2>Schedule New Campus Event</h2>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="add_event.php" enctype="multipart/form-data">
      
      <div class="form-group">
        <label>Event Title *</label>
        <input type="text" name="title" placeholder="e.g. Annual Hackathon 2026" value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>" required>
      </div>

      <div class="form-group">
        <label>Category *</label>
        <select name="category" required>
          <option value="">-- Select Category --</option>
          <option value="Technical">Technical</option>
          <option value="Cultural">Cultural</option>
          <option value="Workshop">Workshop</option>
          <option value="Sports">Sports</option>
          <option value="Seminar">Seminar</option>
        </select>
      </div>

      <div class="form-group">
        <label>Description *</label>
        <textarea name="description" placeholder="Brief outline, schedule, and guidelines for students" required><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
      </div>

      <div style="display: flex; gap: 15px;">
        <div class="form-group" style="flex:1;">
          <label>Event Date *</label>
          <input type="date" name="event_date" value="<?php echo htmlspecialchars($_POST['event_date'] ?? date('Y-m-d')); ?>" required>
        </div>

        <div class="form-group" style="flex:1;">
          <label>Max Seating Capacity *</label>
          <input type="number" name="max_seats" min="5" max="1000" value="<?php echo htmlspecialchars($_POST['max_seats'] ?? '100'); ?>" required>
        </div>
      </div>

      <div class="form-group">
        <label>Venue / Lab Location *</label>
        <input type="text" name="venue" placeholder="e.g. Lab 101, IT Building" value="<?php echo htmlspecialchars($_POST['venue'] ?? ''); ?>" required>
      </div>

      <div class="form-group">
        <label>Event Poster Banner (JPG, PNG, WEBP - Max 2MB)</label>
        <input type="file" name="poster" accept=".jpg,.jpeg,.png,.webp">
      </div>

      <button type="submit" class="btn btn-primary" style="width:100%; padding:10px;">Publish Event</button>
      
      <div style="text-align:center; margin-top:15px;">
        <a href="index.php" style="color:#003366; text-decoration:none;">&larr; Cancel and Return</a>
      </div>
    </form>
  </div>

</body>
</html>
