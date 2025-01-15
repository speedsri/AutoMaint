<?php
// Include database connection
include 'db_connect.php';

header('Content-Type: application/json');

if (isset($_GET['recordID'])) {
    try {
        $stmt = $conn->prepare("SELECT Vehicle_Reg_No FROM servicerecords WHERE recordID = ?");
        $stmt->execute([$_GET['recordID']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'vehicle_reg_no' => $result['Vehicle_Reg_No']
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'No vehicle registration found for this record'
            ]);
        }
    } catch(PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Record ID not provided'
    ]);
}
?>