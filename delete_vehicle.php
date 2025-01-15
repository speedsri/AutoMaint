<?php
include 'db_connect.php';

$vehicleID = $_GET['id'];

$sql = "DELETE FROM Vehicles WHERE VehicleID='$vehicleID'";

if ($conn->query($sql) === TRUE) {
    echo "Vehicle record deleted successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
header("Location: vehicles_dashboard.php");
?>
