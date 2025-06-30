<?php
// Configuration
define('DATA_DIR', __DIR__ . '/data');
define('ATTENDANCE_FILE', DATA_DIR . '/attendance.csv');
define('TIMEZONE', 'Africa/Kampala');
define('MAX_DAILY_RECORDS', 999);

// Initialize
date_default_timezone_set(TIMEZONE);

// Create data directory if not exists
if (!file_exists(DATA_DIR)) {
    mkdir(DATA_DIR, 0755, true);
}

// Create CSV header if file doesn't exist
if (!file_exists(ATTENDANCE_FILE)) {
    $header = ["RegNo", "Name", "Section", "Contact", "TimeIn", "DateIn"];
    file_put_contents(ATTENDANCE_FILE, implode(',', $header) . PHP_EOL);
}
?>