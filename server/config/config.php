<?php
$WEBROOT = '';
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'] . $WEBROOT;
$DB_SERVERNAME = 'localhost';
$DB_USERNAME = 'root';
$DB_PASSWORD = '';
$DB_NAME = 'raposa';

function connect_to_db() {
    global $DB_SERVERNAME, $DB_USERNAME, $DB_PASSWORD, $DB_NAME;
    $conn = new mysqli($DB_SERVERNAME, $DB_USERNAME, $DB_PASSWORD);
    
    if ($conn->connect_error) {
        die("Connection failed: $conn->connect_error");
    }

    $conn->select_db($DB_NAME);
    return $conn;
}