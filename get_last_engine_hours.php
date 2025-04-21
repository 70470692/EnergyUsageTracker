<?php
// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "fuel_log_db";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$equipment_id = isset($_GET['equipment_id']) ? $conn->real_escape_string($_GET['equipment_id']) : '';

if ($equipment_id !== '') {
    $sql = "SELECT engine_hours FROM fuel_logs WHERE equipment_id = '$equipment_id' ORDER BY id DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $row = $result->fetch_assoc()) {
        echo $row['engine_hours'];
    } else {
        echo "0";
    }
} else {
    echo "0";
}

$conn->close();
?>