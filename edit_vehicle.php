<?php
include 'db_connect.php';

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $vehicleID = $_GET['id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $vehicleRegNo = $_POST['Vehicle_Reg_No'];
        $make = $_POST['Make'];
        $model = $_POST['Model'];
        $year = $_POST['Year'];
        $engineNo = $_POST['Engine_No'];
        $chassisNumber = $_POST['Chassis_Number'];
        $vehicleDescription = $_POST['vehicle_description'];

        $sql = "UPDATE Vehicles SET Vehicle_Reg_No=?, Make=?, Model=?, Year=?, Engine_No=?, `Chassis Number`=?, vehicle_description=? WHERE VehicleID=?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssisssi", $vehicleRegNo, $make, $model, $year, $engineNo, $chassisNumber, $vehicleDescription, $vehicleID);

        if ($stmt->execute()) {
            $successMessage = "Vehicle record updated successfully";
        } else {
            $errorMessage = "Error: " . $stmt->error;
        }
        $stmt->close();
    }

    $sql = "SELECT * FROM Vehicles WHERE VehicleID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $vehicleID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
} else {
    // Handle the case where the 'id' parameter is not set
    $errorMessage = "Vehicle ID is missing.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Vehicle</title>
    <link rel="stylesheet" href="css/tailwind.min.css">	
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen p-6">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Edit Vehicle</h1>
                <a href="vehicles_dashboard.php"
                   class="text-gray-600 hover:text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Dashboard
                </a>
            </div>

            <!-- Error Message -->
            <?php if (isset($errorMessage)): ?>
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    <?php echo $errorMessage; ?>
                </div>
            <?php endif; ?>

            <!-- Success Message -->
            <?php if (isset($successMessage)): ?>
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    <?php echo $successMessage; ?>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <?php if (isset($row)): ?>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <form method="post" action="edit_vehicle.php?id=<?php echo $vehicleID; ?>" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Vehicle Reg No -->
                        <div>
                            <label for="Vehicle_Reg_No" class="block text-sm font-medium text-gray-700">Vehicle Reg No</label>
                            <input type="text"
                                   id="Vehicle_Reg_No"
                                   name="Vehicle_Reg_No"
                                   value="<?php echo htmlspecialchars($row['Vehicle_Reg_No']); ?>"
                                   required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Make -->
                        <div>
                            <label for="Make" class="block text-sm font-medium text-gray-700">Make</label>
                            <input type="text"
                                   id="Make"
                                   name="Make"
                                   value="<?php echo htmlspecialchars($row['Make']); ?>"
                                   required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Model -->
                        <div>
                            <label for="Model" class="block text-sm font-medium text-gray-700">Model</label>
                            <input type="text"
                                   id="Model"
                                   name="Model"
                                   value="<?php echo htmlspecialchars($row['Model']); ?>"
                                   required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Year -->
                        <div>
                            <label for="Year" class="block text-sm font-medium text-gray-700">Year</label>
                            <input type="number"
                                   id="Year"
                                   name="Year"
                                   value="<?php echo htmlspecialchars($row['Year']); ?>"
                                   required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Engine No -->
                        <div>
                            <label for="Engine_No" class="block text-sm font-medium text-gray-700">Engine No</label>
                            <input type="text"
                                   id="Engine_No"
                                   name="Engine_No"
                                   value="<?php echo htmlspecialchars($row['Engine_No']); ?>"
                                   required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Chassis Number -->
                        <div>
                            <label for="Chassis_Number" class="block text-sm font-medium text-gray-700">Chassis Number</label>
                            <input type="text"
                                   id="Chassis_Number"
                                   name="Chassis_Number"
                                   value="<?php echo htmlspecialchars($row['Chassis Number']); ?>"
                                   required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Vehicle Description -->
                        <div class="md:col-span-2">
                            <label for="vehicle_description" class="block text-sm font-medium text-gray-700">Vehicle Description</label>
                            <textarea id="vehicle_description"
                                      name="vehicle_description"
                                      required
                                      rows="4"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"><?php echo htmlspecialchars($row['vehicle_description']); ?></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="md:col-span-2 flex justify-end">
                            <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
