<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Initialize search parameters
$where_conditions = [];
$params = [];
$types = "";

// Handle search form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['vehicle_reg_no'])) {
        $where_conditions[] = "Vehicle_Reg_No LIKE ?";
        $params[] = "%" . $_POST['vehicle_reg_no'] . "%";
        $types .= "s";
    }
    
    if (!empty($_POST['date_from']) && !empty($_POST['date_to'])) {
        $where_conditions[] = "MaintenanceDate BETWEEN ? AND ?";
        $params[] = $_POST['date_from'];
        $params[] = $_POST['date_to'];
        $types .= "ss";
    } elseif (!empty($_POST['date_from'])) {
        $where_conditions[] = "MaintenanceDate >= ?";
        $params[] = $_POST['date_from'];
        $types .= "s";
    } elseif (!empty($_POST['date_to'])) {
        $where_conditions[] = "MaintenanceDate <= ?";
        $params[] = $_POST['date_to'];
        $types .= "s";
    }

    if (!empty($_POST['service_type'])) {
        $where_conditions[] = "ServiceType LIKE ?";
        $params[] = "%" . $_POST['service_type'] . "%";
        $types .= "s";
    }
}

// Construct the SQL query
$sql = "SELECT RecordID, Vehicle_Reg_No, MaintenanceDate, ServiceType, ServiceImage 
        FROM MaintenanceRecords";

if (!empty($where_conditions)) {
    $sql .= " WHERE " . implode(" AND ", $where_conditions);
}
$sql .= " ORDER BY MaintenanceDate DESC";

// Prepare and execute the query
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Images Search</title>
    <link href="css/tailwind.min.css" rel="stylesheet">
    <link href="css/all.min.css" rel="stylesheet">	
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen p-8">
    <div class="max-w-6xl mx-auto">
        <!-- Search Form -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h1 class="text-2xl font-bold mb-6">Search Maintenance Images</h1>
            
            <form method="POST" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Registration</label>
                    <input type="text" name="vehicle_reg_no" 
                           value="<?php echo isset($_POST['vehicle_reg_no']) ? htmlspecialchars($_POST['vehicle_reg_no']) : ''; ?>"
                           class="w-full p-2 border rounded-md">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                    <input type="date" name="date_from"
                           value="<?php echo isset($_POST['date_from']) ? htmlspecialchars($_POST['date_from']) : ''; ?>"
                           class="w-full p-2 border rounded-md">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                    <input type="date" name="date_to"
                           value="<?php echo isset($_POST['date_to']) ? htmlspecialchars($_POST['date_to']) : ''; ?>"
                           class="w-full p-2 border rounded-md">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Service Type</label>
                    <input type="text" name="service_type"
                           value="<?php echo isset($_POST['service_type']) ? htmlspecialchars($_POST['service_type']) : ''; ?>"
                           class="w-full p-2 border rounded-md">
                </div>
                
                <div class="md:col-span-2 lg:col-span-4">
                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600">
                        Search
                    </button>
                    <a href="image_view.php" class="ml-4 text-gray-600 hover:text-gray-800">Reset</a>
                </div>
            </form>
        </div>

        <!-- Results Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <?php if ($row['ServiceImage']): ?>
                        <div class="aspect-w-16 aspect-h-9 mb-4">
                            <img src="<?php echo $row['ServiceImage']; ?>" 
                                 alt="Service Image" 
                                 class="w-full h-48 object-cover rounded-lg">
                        </div>
                    <?php else: ?>
                        <div class="bg-gray-200 w-full h-48 flex items-center justify-center rounded-lg mb-4">
                            <span class="text-gray-500">No image available</span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="mt-2">
                        <p class="font-semibold"><?php echo htmlspecialchars($row['Vehicle_Reg_No']); ?></p>
                        <p class="text-gray-600">Date: <?php echo htmlspecialchars($row['MaintenanceDate']); ?></p>
                        <p class="text-gray-600">Service: <?php echo htmlspecialchars($row['ServiceType']); ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
            
            <?php if ($result->num_rows === 0): ?>
                <div class="col-span-full text-center py-8">
                    <p class="text-gray-500">No maintenance records found matching your search criteria.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="mt-8 text-center">
            <a href="index.php" class="text-blue-600 hover:text-blue-800">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>