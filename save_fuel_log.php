<?php 
// Set header first to ensure proper content type
header('Content-Type: application/json');

// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "fuel_log_db";

// Connect to database
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => "Database connection failed: " . $conn->connect_error]);
    exit;
}

// Check if this is an AJAX request
$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

// Validate required fields
$required_fields = ['equipment_id', 'engine_hours', 'fuel_volume', 'log_date', 'log_time'];
$missing_fields = [];
foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        $missing_fields[] = $field;
    }
}

if (!empty($missing_fields)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => "Missing required fields: " . implode(', ', $missing_fields)]);
    exit;
}

// Get and sanitize form data
$equipment_id = $conn->real_escape_string($_POST['equipment_id']);
$engine_hours = floatval($_POST['engine_hours']);
$fuel_volume = floatval($_POST['fuel_volume']);
$bcm = isset($_POST['bcm']) ? floatval($_POST['bcm']) : 0;
$lph = isset($_POST['lph']) ? floatval($_POST['lph']) : 0;
$lpbcm = isset($_POST['lpbcm']) ? floatval($_POST['lpbcm']) : 0;
$log_date = $conn->real_escape_string($_POST['log_date']);
$log_time = $conn->real_escape_string($_POST['log_time']);

// Insert into DB
$sql = "INSERT INTO fuel_logs (
    equipment_id, engine_hours, fuel_volume, bcm, lph, lpbcm, log_date, log_time
) VALUES (
    '$equipment_id', $engine_hours, $fuel_volume, $bcm, $lph, $lpbcm, '$log_date', '$log_time'
)";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['status' => 'success', 'message' => 'Fuel log saved successfully.']);
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => "Database error: " . $conn->error]);
}

$conn->close();
?>