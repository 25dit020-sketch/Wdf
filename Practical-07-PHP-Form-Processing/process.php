<?php
// Practical 7: Process Form Data and Save to CSV & JSON
// Beginner Friendly Procedural PHP

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    // If not a POST request, redirect back to the form
    header("Location: index.php");
    exit();
}

// 1. Collect and Sanitize Inputs
$fullName   = trim($_POST['fullName'] ?? '');
$rollNo     = trim($_POST['rollNo'] ?? '');
$email      = trim($_POST['email'] ?? '');
$department = trim($_POST['department'] ?? '');
$semester   = trim($_POST['semester'] ?? '');
$remarks    = trim($_POST['remarks'] ?? '');

// 2. Server-side Validation
if (empty($fullName) || empty($rollNo) || empty($email) || empty($department) || empty($semester)) {
    header("Location: index.php?error=" . urlencode("All required fields must be filled."));
    exit();
}

// Validate Email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: index.php?error=" . urlencode("Invalid email format provided."));
    exit();
}

// Validate Name (letters and spaces only)
if (!preg_match("/^[A-Za-z\s]{3,50}$/", $fullName)) {
    header("Location: index.php?error=" . urlencode("Name must contain letters only (min 3 characters)."));
    exit();
}

// Current timestamp
$timestamp = date("Y-m-d H:i:s");

// Ensure data folder exists
$dataDir = __DIR__ . '/data';
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0777, true);
}

// 3. Save to CSV File (data/students.csv)
$csvFile = $dataDir . '/students.csv';
$csvExists = file_exists($csvFile);

$fileHandle = fopen($csvFile, 'a');
if ($fileHandle !== false) {
    // If new file, write header row first
    if (!$csvExists) {
        fputcsv($fileHandle, ['Timestamp', 'Full Name', 'Roll No', 'Email', 'Department', 'Semester', 'Remarks']);
    }
    // Write student record
    fputcsv($fileHandle, [$timestamp, $fullName, $rollNo, $email, $department, $semester, $remarks]);
    fclose($fileHandle);
}

// 4. Save to JSON File (data/students.json)
$jsonFile = $dataDir . '/students.json';
$existingStudents = [];

if (file_exists($jsonFile)) {
    $jsonContent = file_get_contents($jsonFile);
    $decoded = json_decode($jsonContent, true);
    if (is_array($decoded)) {
        $existingStudents = $decoded;
    }
}

// Create new student record associative array
$newRecord = [
    'id'         => count($existingStudents) + 1,
    'timestamp'  => $timestamp,
    'fullName'   => $fullName,
    'rollNo'     => $rollNo,
    'email'      => $email,
    'department' => $department,
    'semester'   => $semester,
    'remarks'    => $remarks
];

// Append record and write back formatted JSON
$existingStudents[] = $newRecord;
file_put_contents($jsonFile, json_encode($existingStudents, JSON_PRETTY_PRINT));

// 5. Redirect to view.php with success flag
header("Location: view.php?status=success");
exit();
?>
