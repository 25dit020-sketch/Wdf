<?php
// Practical 12: Update Event Details and Poster Image
session_start();
require_once 'db.php';

$eventId = intval($_GET['id'] ?? 0);
if ($eventId <= 0) {
    header("Location: index.php?err=" . urlencode("Invalid event ID."));
    exit();
}

// Fetch existing event
$stmt = mysqli_prepare($conn, "SELECT * FROM events WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $eventId);
mysqli_stmt_execute($stmt);
$event = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$event) {
    header("Location: index.php?err=" . urlencode("Event record not found."));
    exit();
}

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
        $posterImageName = $event['poster_image'];

        // If replacement file uploaded
        if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['poster']['tmp_name'];
            $fileName    = $_FILES['poster']['name'];
            $fileSize    = $_FILES['poster']['size'];
            $fileExt     = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            $maxFileSize = 2 * 1024 * 1024;

            if (!in_array($fileExt, $allowedExtensions)) {
                $error = "Invalid file type. Allowed formats: JPG, JPEG, PNG, WEBP.";
            } elseif ($fileSize > $maxFileSize) {
                $error = "File size exceeds 2MB limit.";
            } else {
                $newFileName = 'event_' . uniqid() . '.' . $fileExt;
                $destPath = __DIR__ . '/uploads/' . $newFileName;

                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    // Remove old poster if it was custom
                    if (!empty($event['poster_image']) && $event['poster_image'] !== 'default_event.svg') {
                        $oldFilePath = __DIR__ . '/uploads/' . $event['poster_image'];
                        if (file_exists($oldFilePath)) {
                            unlink($oldFilePath);
                        }
                    }
                    $posterImageName = $newFileName;
                } else {
                    $error = "Failed to upload file.";
                }
            }
        }

        if (empty($error)) {
            $updateStmt = mysqli_prepare($conn, "UPDATE events SET title = ?, category = ?, description = ?, event_date = ?, venue = ?, max_seats = ?, poster_image = ? WHERE id = ?");
            mysqli_stmt_bind_param($updateStmt, "sssssisi", $title, $category, $description, $eventDate, $venue, $maxSeats, $posterImageName, $eventId);

            if (mysqli_stmt_execute($updateStmt)) {
                mysqli_stmt_close($updateStmt);
                header("Location: index.php?msg=" . urlencode("Event '$title' updated successfully."));
                exit();
            } else {
                $error = "Failed to update event: " . mysqli_error($conn);
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
  <title>Edit Event | StudentHub</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <div class="form-container">
    <h2>Edit Event Details</h2>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="edit_event.php?id=<?php echo $eventId; ?>" enctype="multipart/form-data">
      
      <div class="form-group">
        <label>Event Title *</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" required>
      </div>

      <div class="form-group">
        <label>Category *</label>
        <select name="category" required>
          <?php 
          $cats = ["Technical", "Cultural", "Workshop", "Sports", "Seminar"];
          foreach ($cats as $c): ?>
            <option value="<?php echo $c; ?>" <?php echo ($event['category'] === $c) ? 'selected' : ''; ?>><?php echo $c; ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label>Description *</label>
        <textarea name="description" required><?php echo htmlspecialchars($event['description']); ?></textarea>
      </div>

      <div style="display: flex; gap: 15px;">
        <div class="form-group" style="flex:1;">
          <label>Event Date *</label>
          <input type="date" name="event_date" value="<?php echo htmlspecialchars($event['event_date']); ?>" required>
        </div>

        <div class="form-group" style="flex:1;">
          <label>Max Seating Capacity *</label>
          <input type="number" name="max_seats" min="5" max="1000" value="<?php echo htmlspecialchars($event['max_seats']); ?>" required>
        </div>
      </div>

      <div class="form-group">
        <label>Venue / Lab Location *</label>
        <input type="text" name="venue" value="<?php echo htmlspecialchars($event['venue']); ?>" required>
      </div>

      <div class="form-group">
        <label>Current Poster Banner</label>
        <?php 
        $currentImg = 'uploads/' . $event['poster_image'];
        if (empty($event['poster_image']) || !file_exists($currentImg)) $currentImg = 'uploads/default_event.svg';
        ?>
        <img src="<?php echo htmlspecialchars($currentImg); ?>" alt="Poster Preview" class="preview-thumb">
      </div>

      <div class="form-group">
        <label>Replace Poster (Leave blank to keep existing)</label>
        <input type="file" name="poster" accept=".jpg,.jpeg,.png,.webp">
      </div>

      <button type="submit" class="btn btn-primary" style="width:100%; padding:10px;">Update Event</button>
      
      <div style="text-align:center; margin-top:15px;">
        <a href="index.php" style="color:#003366; text-decoration:none;">&larr; Cancel and Return</a>
      </div>
    </form>
  </div>

</body>
</html>
