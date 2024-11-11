<?php
$web_root = '';
$document_root = $_SERVER['DOCUMENT_ROOT'] . $web_root;
$db_servername = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'raposa';

function connect_to_db() {
    global $db_servername, $db_username, $db_password, $db_name;
    $conn = new mysqli($db_servername, $db_username, $db_password);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    $conn->select_db($db_name);
    return $conn;
}
