<?php
// fetch_utils.php

function getVehicleRegistrationNumbers($conn) {
    $sql = "SELECT DISTINCT Vehicle_Reg_No FROM vehicles ORDER BY Vehicle_Reg_No";
    $result = $conn->query($sql);
    $reg_numbers = array();
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $reg_numbers[] = $row['Vehicle_Reg_No'];
        }
    }
    
    return $reg_numbers;
}

function getServiceTypes($conn) {
    // Fetch ENUM values from the database
    $sql = "SHOW COLUMNS FROM MaintenanceRecords WHERE Field = 'ServiceType'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    
    // Parse ENUM values from the type definition
    preg_match("/^enum\(\'(.*)\'\)$/", $row['Type'], $matches);
    $values = explode("','", $matches[1]);
    
    return $values;
}
?>