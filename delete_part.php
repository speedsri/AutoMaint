<?php
include 'db_connect.php';

$partID = $_GET['id'];

$sql = "DELETE FROM PartsUsed WHERE PartID='$partID'";

if ($conn->query($sql) === TRUE) {
    echo "Part record deleted successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
header("Location: parts_dashboard.php");
?>
