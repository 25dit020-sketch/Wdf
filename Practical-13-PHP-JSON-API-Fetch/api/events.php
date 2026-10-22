<?php
// Practical 13: RESTful JSON API for Campus Events
// Beginner-friendly PHP JSON API

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../db.php';

$method = $_SERVER['REQUEST_METHOD'];
// Decode incoming JSON body
$rawInput = file_get_contents("php://input");
$input = json_decode($rawInput, true) ?? [];

switch ($method) {

    // 1. GET: Fetch all events or a single event
    case 'GET':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $stmt = mysqli_prepare($conn, "SELECT * FROM events WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $event = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
            mysqli_stmt_close($stmt);

            if ($event) {
                http_response_code(200);
                echo json_encode(["status" => "success", "data" => $event]);
            } else {
                http_response_code(404);
                echo json_encode(["status" => "error", "message" => "Event not found"]);
            }
        } else {
            $result = mysqli_query($conn, "SELECT * FROM events ORDER BY event_date ASC");
            $events = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $events[] = $row;
            }
            http_response_code(200);
            echo json_encode(["status" => "success", "count" => count($events), "data" => $events]);
        }
        break;

    // 2. POST: Create a new event
    case 'POST':
        $title       = trim($input['title'] ?? '');
        $category    = trim($input['category'] ?? '');
        $description = trim($input['description'] ?? '');
        $eventDate   = trim($input['event_date'] ?? '');
        $venue       = trim($input['venue'] ?? '');
        $maxSeats    = intval($input['max_seats'] ?? 50);

        if (empty($title) || empty($category) || empty($description) || empty($eventDate) || empty($venue)) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "All fields (title, category, description, event_date, venue) are required."]);
            exit();
        }

        $stmt = mysqli_prepare($conn, "INSERT INTO events (title, category, description, event_date, venue, max_seats, poster_image) VALUES (?, ?, ?, ?, ?, ?, 'default_event.svg')");
        mysqli_stmt_bind_param($stmt, "sssssi", $title, $category, $description, $eventDate, $venue, $maxSeats);

        if (mysqli_stmt_execute($stmt)) {
            $newId = mysqli_insert_id($conn);
            mysqli_stmt_close($stmt);
            http_response_code(201);
            echo json_encode(["status" => "success", "message" => "Event published successfully", "id" => $newId]);
        } else {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Database insert error: " . mysqli_error($conn)]);
        }
        break;

    // 3. PUT: Update an existing event
    case 'PUT':
        $id          = intval($input['id'] ?? 0);
        $title       = trim($input['title'] ?? '');
        $category    = trim($input['category'] ?? '');
        $description = trim($input['description'] ?? '');
        $eventDate   = trim($input['event_date'] ?? '');
        $venue       = trim($input['venue'] ?? '');
        $maxSeats    = intval($input['max_seats'] ?? 50);

        if ($id <= 0 || empty($title) || empty($category) || empty($description) || empty($eventDate) || empty($venue)) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Valid event ID and all fields are required for update."]);
            exit();
        }

        $stmt = mysqli_prepare($conn, "UPDATE events SET title = ?, category = ?, description = ?, event_date = ?, venue = ?, max_seats = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "sssssii", $title, $category, $description, $eventDate, $venue, $maxSeats, $id);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            http_response_code(200);
            echo json_encode(["status" => "success", "message" => "Event updated successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Database update error: " . mysqli_error($conn)]);
        }
        break;

    // 4. DELETE: Remove an event
    case 'DELETE':
        $id = intval($_GET['id'] ?? ($input['id'] ?? 0));

        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "A valid event ID is required for deletion."]);
            exit();
        }

        $stmt = mysqli_prepare($conn, "DELETE FROM events WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            http_response_code(200);
            echo json_encode(["status" => "success", "message" => "Event deleted successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Database delete error: " . mysqli_error($conn)]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["status" => "error", "message" => "HTTP Method Not Allowed"]);
        break;
}
?>
