<?php
$servername = "localhost";
$username = "root";
$password = "admin@123";
$dbname = "vehicleMaintenancedb";
$charset = 'utf8mb4';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset($charset);

// Data Source Name (for PDO if needed)
$dsn = "mysql:host=$servername;dbname=$dbname;charset=$charset";

// Options
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
?>
