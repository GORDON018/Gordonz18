<?php
if (!file_exists("AttendanceData.csv")) {
    echo "Attendance file not found.";
    exit;
}

echo "<h2>Staff Attendance Records</h2><table border='1' cellpadding='5'>";
if (($handle = fopen("AttendanceData.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        echo "<tr>";
        foreach ($data as $field) {
            echo "<td>" . htmlspecialchars($field) . "</td>";
        }
        echo "</tr>";
    }
    fclose($handle);
}
?>