<?php
require('../config/config.php');

if (isset($_POST['name'])) {
    $machine = $_POST['name'];
    $conn = connect_to_db();

    if ($conn) {
        $sql = "SELECT command FROM machines WHERE name = ? ORDER BY id DESC LIMIT 1";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param('s', $machine);
            $stmt->execute();
            $stmt->bind_result($command);
            $stmt->fetch();

            $output = $command ?: "no_command";
            echo $output;
            $stmt->close();

            if ($command) {
                $update_sql = "UPDATE machines SET command = '' WHERE name = ?";
                $update_stmt = $conn->prepare($update_sql);

                if ($update_stmt) {
                    $update_stmt->bind_param('s', $machine);
                    $update_stmt->execute();
                    $update_stmt->close();
                }
            }
        } else {
            echo "no_command";
        }

        $conn->close();
    } else {
        echo "no_command";
    }
} else {
    echo "no_command";
}