<?php
// Database connection settings
$host = "localhost";
$username = "root";
$password = "";
$database = "fuel_log_db";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Handle connection error
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Fetch distinct equipment IDs
$sql = "SELECT DISTINCT equipment_id FROM fuel_logs ORDER BY equipment_id ASC";
$result = $conn->query($sql);

$equipment_ids = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $equipment_ids[] = $row['equipment_id'];
    }
}

// Output JSON
header('Content-Type: application/json');
echo json_encode($equipment_ids);

// Close connection
$conn->close();
?>