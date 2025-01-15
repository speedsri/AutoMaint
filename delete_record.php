<?php
include 'db_connect.php';

$recordID = $_GET['id'];

$sql = "DELETE FROM MaintenanceRecords WHERE RecordID='$recordID'";

if ($conn->query($sql) === TRUE) {
    echo "Record deleted successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
header("Location: dashboard.php");
?>
