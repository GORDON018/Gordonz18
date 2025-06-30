<?php
date_default_timezone_set('Africa/Kampala');

// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set JSON header
header('Content-Type: application/json');

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['success' => false, 'message' => 'Method not allowed']));
}

// Sanitize and validate inputs
$name = htmlspecialchars(trim($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8');
$section = htmlspecialchars(trim($_POST['section'] ?? ''), ENT_QUOTES, 'UTF-8');
$contact = htmlspecialchars(trim($_POST['contact'] ?? ''), ENT_QUOTES, 'UTF-8');

// Validate inputs
$errors = [];
if (empty($name)) {
    $errors[] = 'Name is required';
} elseif (!preg_match('/^[A-Za-z ]+$/', $name)) {
    $errors[] = 'Name can only contain letters and spaces';
} elseif (strlen($name) > 50) {
    $errors[] = 'Name cannot exceed 50 characters';
}

if (empty($contact)) {
    $errors[] = 'Contact is required';
} elseif (!preg_match('/^[0-9]{10,15}$/', $contact)) {
    $errors[] = 'Contact must be 10-15 digits';
}

if (empty($section)) {
    $errors[] = 'Section is required';
}

if (!empty($errors)) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => implode(', ', $errors)]));
}

// Generate registration number
$dayName = date('l');
$countToday = 0;
$csvFile = 'AttendanceData.csv';

// Count existing registrations for today
if (file_exists($csvFile) && filesize($csvFile) > 0) {
    $file = fopen($csvFile, 'r');
    // Skip header
    fgetcsv($file);
    while (($row = fgetcsv($file)) !== false) {
        if (isset($row[0]) && strpos($row[0], $dayName) === 0) {
            $countToday++;
        }
    }
    fclose($file);
}

$regNo = $dayName . str_pad($countToday + 1, 3, '0', STR_PAD_LEFT);
$timeIn = date('h:i:s A');
$date = date('d-m-Y');

// Save to CSV
try {
    $file = fopen($csvFile, 'a');
    // Add header if file is empty
    if (filesize($csvFile) == 0) {
        fputcsv($file, ['RegNo', 'Name', 'Section', 'Contact', 'TimeIn', 'DateIn']);
    }
    // Lock file during write
    flock($file, LOCK_EX);
    fputcsv($file, [$regNo, $name, $section, $contact, $timeIn, $date]);
    flock($file, LOCK_UN);
    fclose($file);
    
    echo json_encode([
        'success' => true,
        'message' => "Registered successfully! Your ID: $regNo",
        'regNo' => $regNo
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to save registration']);
}
?>