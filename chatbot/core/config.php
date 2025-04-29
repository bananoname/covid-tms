<?php
// Set timezone
date_default_timezone_set('Asia/Kolkata');

// Database connection using MySQLi
$con = mysqli_connect("localhost", "root", "", "covidtmsdb");

if (mysqli_connect_errno()) {
    echo "Connection Failed: " . mysqli_connect_error();
    exit();
}
?>