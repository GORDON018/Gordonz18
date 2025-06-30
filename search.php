<?php
require_once 'config.php';

header("Content-Type: application/json");

$searchTerm = trim($_GET['q'] ?? '');
$dateFilter = trim($_GET['date'] ?? '');
$sectionFilter = trim($_GET['section'] ?? '');

$results = [];

if (($handle = fopen(ATTENDANCE_FILE, "r")) !== false) {
    // Skip header
    fgetcsv($handle);
    
    while (($row = fgetcsv($handle)) !== false) {
        $record = [
            'regNo' => $row[0] ?? '',
            'name' => $row[1] ?? '',
            'section' => $row[2] ?? '',
            'contact' => $row[3] ?? '',
            'timeIn' => $row[4] ?? '',
            'date' => $row[5] ?? ''
        ];
        
        $match = true;
        
        // Apply filters
        if (!empty($searchTerm)) {
            $searchMatch = false;
            foreach (['regNo', 'name', 'contact'] as $field) {
                if (stripos($record[$field], $searchTerm) !== false) {
                    $searchMatch = true;
                    break;
                }
            }
            $match = $match && $searchMatch;
        }
        
        if (!empty($dateFilter) && $record['date'] !== $dateFilter) {
            $match = false;
        }
        
        if (!empty($sectionFilter) && $record['section'] !== $sectionFilter) {
            $match = false;
        }
        
        if ($match) {
            $results[] = $record;
        }
    }
    fclose($handle);
}

echo json_encode([
    'success' => true,
    'count' => count($results),
    'results' => $results
]);
?>