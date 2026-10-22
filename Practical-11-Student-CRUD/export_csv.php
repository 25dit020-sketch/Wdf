<?php
// Practical 11: Export Filtered Student Data to CSV
require_once 'db.php';

$search = trim($_GET['search'] ?? '');
$deptFilter = trim($_GET['department'] ?? '');

// Build query
$sql = "SELECT s.id, u.name, u.email, s.roll_no, s.department, s.semester, s.cgpa, s.contact_no 
        FROM students s 
        JOIN users u ON s.user_id = u.id 
        WHERE 1=1";

$params = [];
$types = "";

if (!empty($search)) {
    $sql .= " AND (u.name LIKE ? OR s.roll_no LIKE ? OR u.email LIKE ?)";
    $searchTerm = "%" . $search . "%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= "sss";
}

if (!empty($deptFilter)) {
    $sql .= " AND s.department = ?";
    $params[] = $deptFilter;
    $types .= "s";
}

$sql .= " ORDER BY s.id ASC";

$stmt = mysqli_prepare($conn, $sql);
if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Set HTTP Headers to trigger CSV Download in Browser
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=students_export_' . date('Y-m-d') . '.csv');

// Open standard output stream
$output = fopen('php://output', 'w');

// Write CSV Header
fputcsv($output, ['ID', 'Full Name', 'Email', 'Roll Number', 'Department', 'Semester', 'CGPA', 'Contact Number']);

// Output data rows
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [
        $row['id'],
        $row['name'],
        $row['email'],
        $row['roll_no'],
        $row['department'],
        $row['semester'],
        number_format($row['cgpa'], 2),
        $row['contact_no']
    ]);
}

fclose($output);
exit();
?>
