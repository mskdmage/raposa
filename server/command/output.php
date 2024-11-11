<?php
require('../config/config.php');

$machine = $_POST['name'];
$returns = $_POST['returns'];
$conn = connect_to_db();
$query = "UPDATE machines SET output = ? WHERE name = ?";
$stmt = $conn->prepare($query);

if ($stmt) {
    $stmt->bind_param('ss', $returns, $machine);
    $stmt->execute();    
    $stmt->close();
}

$conn->close();