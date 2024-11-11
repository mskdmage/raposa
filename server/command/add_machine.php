<?php
require('../config/config.php');

$conn = connect_to_db();

$name = $_POST["name"];
$ip = $_POST["ip"];

$check_query = "SELECT id FROM machines WHERE name = ? AND ip = ?";
$stmt = $conn->prepare($check_query);
$stmt->bind_param('ss', $name, $ip);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $update_query = "UPDATE machines SET last_active = CURRENT_TIMESTAMP WHERE name = ? AND ip = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param('ss', $name, $ip);
    $stmt->execute();
    echo "joined";
    return;
} else {
    $insert_query = "INSERT INTO machines (name, ip, last_active) VALUES (?, ?, CURRENT_TIMESTAMP)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param('ss', $name, $ip);
    $stmt->execute();
    echo "joined";
    return;
}

$stmt->close();
$conn->close();
