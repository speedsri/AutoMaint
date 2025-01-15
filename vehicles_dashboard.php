<?php
include 'db_connect.php';

// Fetch data from Vehicles
$sql = "SELECT * FROM Vehicles";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicles Management Dashboard</title>
    <link rel="stylesheet" href="css/tailwind.min.css">	
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Back to Home Button -->
            <div class="mb-6">
                <a href="index.php" 
                   class="inline-block px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition duration-150 ease-in-out">
                    Back to Home
                </a>
            </div>

            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Vehicles Management Dashboard</h1>
                <a href="add_vehicle.php"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                    Add New Vehicle
                </a>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-lg shadow-sm p-6 overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">VehicleID</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Vehicle Reg No</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Make</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Model</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Year</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Engine No</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Chassis Number</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Vehicle Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr class='hover:bg-gray-50'>";
                                echo "<td class='py-2 px-4 border-b border-gray-200 whitespace-nowrap'>
                                        <div class='flex space-x-2'>
                                            <a href='edit_vehicle.php?id=" . $row['VehicleID'] . "' class='text-blue-600 hover:text-blue-800 px-2 py-1 rounded-md hover:bg-blue-100'>Edit</a>
                                            <a href='delete_vehicle.php?id=" . $row['VehicleID'] . "' class='text-red-600 hover:text-red-800 px-2 py-1 rounded-md hover:bg-red-100'>Delete</a>
                                        </div>
                                      </td>";
                                echo "<td class='py-2 px-4 border-b border-gray-200'>" . $row['VehicleID'] . "</td>";
                                echo "<td class='py-2 px-4 border-b border-gray-200'>" . $row['Vehicle_Reg_No'] . "</td>";
                                echo "<td class='py-2 px-4 border-b border-gray-200'>" . $row['Make'] . "</td>";
                                echo "<td class='py-2 px-4 border-b border-gray-200'>" . $row['Model'] . "</td>";
                                echo "<td class='py-2 px-4 border-b border-gray-200'>" . $row['Year'] . "</td>";
                                echo "<td class='py-2 px-4 border-b border-gray-200'>" . $row['Engine_No'] . "</td>";
                                echo "<td class='py-2 px-4 border-b border-gray-200'>" . $row['Chassis Number'] . "</td>";
                                echo "<td class='py-2 px-4 border-b border-gray-200'>" . $row['vehicle_description'] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9' class='py-2 px-4 border-b border-gray-200 text-center'>No records found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>