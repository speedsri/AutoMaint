<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$record_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$success_message = '';
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vehicle_reg_no = $_POST['vehicle_reg_no'];
    $maintenance_date = $_POST['maintenance_date'];
    $odometer = $_POST['odometer'];
    $service_type = $_POST['service_type'];
    $description = $_POST['description'];
    $cost = $_POST['cost'];
    
    // Handle file upload if a new image is provided
    if (isset($_FILES['service_image']) && $_FILES['service_image']['size'] > 0) {
        $target_dir = "uploads/";
        $file_extension = strtolower(pathinfo($_FILES["service_image"]["name"], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        // Check if image file is valid
        $valid_types = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array($file_extension, $valid_types) && move_uploaded_file($_FILES["service_image"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
            
            $sql = "UPDATE MaintenanceRecords SET 
                    Vehicle_Reg_No = ?, 
                    MaintenanceDate = ?,
                    OdometerReading = ?,
                    ServiceType = ?,
                    ServiceDescription = ?,
                    Cost = ?,
                    ServiceImage = ?
                    WHERE RecordID = ?";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssissssi", 
                $vehicle_reg_no,
                $maintenance_date,
                $odometer,
                $service_type,
                $description,
                $cost,
                $image_path,
                $record_id
            );
        } else {
            $error_message = "Invalid file type or upload failed";
        }
    } else {
        // Update without changing the image
        $sql = "UPDATE MaintenanceRecords SET 
                Vehicle_Reg_No = ?, 
                MaintenanceDate = ?,
                OdometerReading = ?,
                ServiceType = ?,
                ServiceDescription = ?,
                Cost = ?
                WHERE RecordID = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisssi", 
            $vehicle_reg_no,
            $maintenance_date,
            $odometer,
            $service_type,
            $description,
            $cost,
            $record_id
        );
    }

    if ($stmt->execute()) {
        header("Location: index.php?status=success&message=" . urlencode("Record updated successfully"));
        exit();
    } else {
        $error_message = "Error updating record: " . $conn->error;
    }
}

// Fetch existing record data
$sql = "SELECT * FROM MaintenanceRecords WHERE RecordID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $record_id);
$stmt->execute();
$result = $stmt->get_result();
$record = $result->fetch_assoc();

if (!$record) {
    header("Location: index.php?status=error&message=" . urlencode("Record not found"));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Maintenance Record</title>
    <link href="css/tailwind.min.css" rel="stylesheet">
    <link href="css/all.min.css" rel="stylesheet">	
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body
<body class="bg-gray-50">
    <div class="min-h-screen py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Add the Back to Home button here -->
            <div class="mb-6">
                <a href="index.php" 
                   class="inline-block px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition duration-150 ease-in-out">
                    Back to Home
                </a>
            </div>
            
            <div class="bg-white rounded-lg shadow px-6 py-8">

 <class="bg-gray-50">
    <div class="min-h-screen py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow px-6 py-8">
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-gray-900">Edit Maintenance Record</h1>
                    <p class="mt-2 text-sm text-gray-600">Update the maintenance record details below.</p>
                </div>

                <?php if ($error_message): ?>
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data" class="space-y-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Vehicle Registration Number</label>
                            <input type="text" name="vehicle_reg_no" value="<?php echo htmlspecialchars($record['Vehicle_Reg_No']); ?>" 
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Maintenance Date</label>
                            <input type="date" name="maintenance_date" value="<?php echo htmlspecialchars($record['MaintenanceDate']); ?>"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Odometer Reading</label>
                            <input type="number" name="odometer" value="<?php echo htmlspecialchars($record['OdometerReading']); ?>"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Service Type</label>
                            <input type="text" name="service_type" value="<?php echo htmlspecialchars($record['ServiceType']); ?>"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Service Description</label>
                            <textarea name="description" rows="3" 
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            ><?php echo htmlspecialchars($record['ServiceDescription']); ?></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cost</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">Rs.</span>
                                </div>
                                <input type="number" step="0.01" name="cost" value="<?php echo htmlspecialchars($record['Cost']); ?>"
                                       class="block w-full pl-7 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Update Service Image</label>
                            <input type="file" name="service_image" accept="image/*"
                                   class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <p class="mt-1 text-sm text-gray-500">Leave empty to keep existing image</p>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="index.php" 
                           class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </a>
                        <button type="submit"
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Update Record
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>