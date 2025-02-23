<?php
$mysqli = new mysqli("localhost", "root", "", "quiz_app");

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
echo "Connected successfully";
?>
