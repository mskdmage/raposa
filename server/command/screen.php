<?php
require('../config/config.php');

if (isset($_POST['name']) && isset($_POST['screen'])) {
    $machine = $_POST['name'];
    $screen = $_POST['screen'];
    
    if (!empty($machine) && !empty($screen)) {
        $conn = connect_to_db();

        $query = "UPDATE machines SET desktop = ? WHERE name = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('bs', $screen, $machine);
        $stmt->send_long_data(0, base64_decode($screen));
        $stmt->execute();

        $stmt->close();
        $conn->close();
    }
}
