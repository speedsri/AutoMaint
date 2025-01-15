<?php
include 'db_connect.php';

// Fetch vehicle registration numbers from the database
$vehicleQuery = "SELECT Vehicle_Reg_No FROM Vehicles";
$vehicleResult = $conn->query($vehicleQuery);
$vehicles = [];
if ($vehicleResult->num_rows > 0) {
    while ($row = $vehicleResult->fetch_assoc()) {
        $vehicles[] = $row['Vehicle_Reg_No'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $vehicleID = mysqli_real_escape_string($conn, $_POST['VehicleID']);
    $vehicleRegNo = mysqli_real_escape_string($conn, $_POST['Vehicle_Reg_No']);
    $maintenanceDate = mysqli_real_escape_string($conn, $_POST['MaintenanceDate']);
    $odometerReading = mysqli_real_escape_string($conn, $_POST['OdometerReading']);
    $serviceType = mysqli_real_escape_string($conn, $_POST['ServiceType']);
    $serviceDescription = mysqli_real_escape_string($conn, $_POST['ServiceDescription']);
    $cost = mysqli_real_escape_string($conn, $_POST['Cost']);

    // Check if an image file is uploaded
    $imagePath = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the file is an actual image or a fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            // Check file size
            if ($_FILES["image"]["size"] > 5000000) {
                $errorMessage = "Sorry, your file is too large.";
            } else {
                // Allow certain file formats
                $allowed_extensions = array("jpg", "jpeg", "png", "gif");
                if (in_array($imageFileType, $allowed_extensions)) {
                    // Move the uploaded file to the target directory
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                        $imagePath = $target_file;
                    } else {
                        $errorMessage = "Sorry, there was an error uploading your file.";
                    }
                } else {
                    $errorMessage = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                }
            }
        } else {
            $errorMessage = "File is not an image.";
        }
    }

    if (empty($errorMessage)) {
        $sql = "INSERT INTO MaintenanceRecords (VehicleID, Vehicle_Reg_No, MaintenanceDate, OdometerReading, ServiceType, ServiceDescription, Cost, ServiceImage)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ississds", $vehicleID, $vehicleRegNo, $maintenanceDate, $odometerReading, $serviceType, $serviceDescription, $cost, $imagePath);

        if ($stmt->execute()) {
            $successMessage = "Record added successfully!";
        } else {
            $errorMessage = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Maintenance Record</title>
    <link href="css/tailwind.min.css" rel="stylesheet">
    <link href="css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-00">
    <div class="min-h-screen p-6">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Add Maintenance Record</h1>
                <a href="index.php"
                   class="text-gray-600 hover:text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Dashboard
                </a>
            </div>

            <!-- Success/Error Messages -->
            <?php if (isset($successMessage)): ?>
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    <?php echo $successMessage; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($errorMessage)): ?>
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    <?php echo $errorMessage; ?>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <div class="bg-gray-100 rounded-lg shadow-sm p-6">
                <form method="post" action="add_record.php" class="grid grid-cols-1 md:grid-cols-2 gap-6" enctype="multipart/form-data">
                    <!-- Vehicle ID -->
                    <div>
                        <label for="VehicleID" class="block text-sm font-medium text-gray-700">Vehicle ID</label>
                        <input type="number"
                               id="VehicleID"
                               name="VehicleID"
                               required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Vehicle Reg No -->
                    <div>
                        <label for="Vehicle_Reg_No" class="block text-sm font-medium text-gray-700">Vehicle Reg No</label>
                        <select id="Vehicle_Reg_No"
                                name="Vehicle_Reg_No"
                                required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select a vehicle</option>
                            <?php foreach ($vehicles as $vehicle): ?>
                                <option value="<?php echo htmlspecialchars($vehicle); ?>"><?php echo htmlspecialchars($vehicle); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Maintenance Date -->
                    <div>
                        <label for="MaintenanceDate" class="block text-sm font-medium text-gray-700">Maintenance Date</label>
                        <input type="date"
                               id="MaintenanceDate"
                               name="MaintenanceDate"
                               required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Odometer Reading -->
                    <div>
                        <label for="OdometerReading" class="block text-sm font-medium text-gray-700">Odometer Reading</label>
                        <input type="number"
                               id="OdometerReading"
                               name="OdometerReading"
                               required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Service Type -->
                    <div>
                        <label for="ServiceType" class="block text-sm font-medium text-gray-700">Service Type</label>
                        <select id="ServiceType"
                                name="ServiceType"
                                required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select a service type</option>
                            <option value="Differential Repair">Differential Repair</option>
                            <option value="Engine Repair">Engine Repair</option>
                            <option value="Transmission Repair">Transmission Repair</option>
                            <option value="Brake System Repair">Brake System Repair</option>
                            <option value="Fuel System Repair">Fuel System Repair</option>
                            <option value="Electrical System Repair">Electrical System Repair</option>							
                            <option value="Suspension Repair">Suspension Repair</option>
                            <option value="Exhaust & Emission System Repair">Exhaust & Emission System Repair</option>
                            <option value="Air Conditioning & Climate Control Repair">Air Conditioning & Climate Control Repair</option>
                            <option value="Body & Exterior Repair">Body & Exterior Repair</option>
                            <option value="Interior Repair">Interior Repair</option>
                            <option value="Routine Services">Routine Services</option>							
                            <option value="Renewal of Parts">Renewal of Parts</option>							
                            <option value="Miscellaneous Repairs">Miscellaneous Repairs</option>														
                        </select>
                    </div>

                    <!-- Service Description -->
                    <div class="md:col-span-2">
                        <label for="ServiceDescription" class="block text-sm font-medium text-gray-700">Service Description</label>
                        <textarea id="ServiceDescription"
                                  name="ServiceDescription"
                                  required
                                  rows="4"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>

                    <!-- Cost -->
                    <div>
                        <label for="Cost" class="block text-sm font-medium text-gray-700">Cost (Rs.)</label>
                        <input type="number"
                               id="Cost"
                               name="Cost"
                               step="0.01"
                               required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Image Upload -->
                    <div class="md:col-span-2">
                        <label for="image" class="block text-sm font-medium text-blue-700">Upload Image</label>
                        <input type="file"
                               id="image"
                               name="image"
                               accept="image/*"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Submit Button -->
                    <div class="md:col-span-2 flex justify-end">
                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                            Add Record
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>
