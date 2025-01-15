<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $vehicleRegNo = mysqli_real_escape_string($conn, $_POST['Vehicle_Reg_No']);
    $make = mysqli_real_escape_string($conn, $_POST['Make']);
    $model = mysqli_real_escape_string($conn, $_POST['Model']);
    $year = mysqli_real_escape_string($conn, $_POST['Year']);
    $engineNo = mysqli_real_escape_string($conn, $_POST['Engine_No']);
    $chassisNumber = mysqli_real_escape_string($conn, $_POST['Chassis_Number']);
    $vehicleDescription = mysqli_real_escape_string($conn, $_POST['vehicle_description']);

    $sql = "INSERT INTO Vehicles (Vehicle_Reg_No, Make, Model, Year, Engine_No, `Chassis Number`, vehicle_description)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssisss", $vehicleRegNo, $make, $model, $year, $engineNo, $chassisNumber, $vehicleDescription);

    if ($stmt->execute()) {
        $successMessage = "New vehicle record created successfully";
    } else {
        $errorMessage = "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Vehicle</title>
    <link href="css/tailwind.min.css" rel="stylesheet">
    <link href="css/all.min.css" rel="stylesheet">

	
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen p-6">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Add Vehicle</h1>
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
            <div class="bg-gray-200 rounded-lg shadow-sm p-6">
                <form method="post" action="add_vehicle.php" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Vehicle Reg No -->
                    <div>
                        <label for="Vehicle_Reg_No" class="block text-sm font-medium text-gray-700">Vehicle Reg No</label>
                        <input type="text"
                               id="Vehicle_Reg_No"
                               name="Vehicle_Reg_No"
                               required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Make -->
                    <div>
                        <label for="Make" class="block text-sm font-medium text-gray-700">Make</label>
                        <input type="text"
                               id="Make"
                               name="Make"
                               required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Model -->
                    <div>
                        <label for="Model" class="block text-sm font-medium text-gray-700">Model</label>
                        <input type="text"
                               id="Model"
                               name="Model"
                               required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Year -->
                    <div>
                        <label for="Year" class="block text-sm font-medium text-gray-700">Year</label>
                        <input type="number"
                               id="Year"
                               name="Year"
                               required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Engine No -->
                    <div>
                        <label for="Engine_No" class="block text-sm font-medium text-gray-700">Engine No</label>
                        <input type="text"
                               id="Engine_No"
                               name="Engine_No"
                               required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Chassis Number -->
                    <div>
                        <label for="Chassis_Number" class="block text-sm font-medium text-gray-700">Chassis Number</label>
                        <input type="text"
                               id="Chassis_Number"
                               name="Chassis_Number"
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
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="md:col-span-2 flex justify-end">
                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                            Add Vehicle
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
