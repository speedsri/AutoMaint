<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recordID = $_POST['RecordID'];
    $Vehicle_Reg_No = $_POST['Vehicle_Reg_No'];
    $partName = $_POST['PartName'];
    $partNumber = $_POST['PartNumber'];
    $quantity = $_POST['Quantity'];
    $cost = $_POST['Cost'];
    $typeOfService = $_POST['type_of_service'];
    $description = $_POST['Description'];

    $sql = "INSERT INTO PartsUsed (RecordID, Vehicle_Reg_No, PartName, PartNumber, Quantity, Cost, type_of_service, Description)
            VALUES ('$recordID', '$Vehicle_Reg_No', '$partName', '$partNumber', '$quantity', '$cost', '$typeOfService', '$description')";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800' role='alert'>
                <span class='font-medium'>Success!</span> New part record created successfully.
              </div>";
    } else {
        echo "<div class='p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800' role='alert'>
                <span class='font-medium'>Error!</span> " . $conn->error . "
              </div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Part</title>
    <link href="css/tailwind.min.css" rel="stylesheet">
    <link href="css/all.min.css" rel="stylesheet">	
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Add New Part</h1>
        <form method="post" action="add_part.php" class="space-y-4">
            <div>
                <label for="RecordID" class="block text-sm font-medium text-gray-700">RecordID:</label>
                <input type="number" id="RecordID" name="RecordID" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="Vehicle_Reg_No" class="block text-sm font-medium text-gray-700">Vehicle Reg No:</label>
                <input type="text" id="Vehicle_Reg_No" name="Vehicle_Reg_No" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="PartName" class="block text-sm font-medium text-gray-700">Part Name:</label>
                <input type="text" id="PartName" name="PartName" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="PartNumber" class="block text-sm font-medium text-gray-700">Part Number:</label>
                <input type="text" id="PartNumber" name="PartNumber" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="Quantity" class="block text-sm font-medium text-gray-700">Quantity:</label>
                <input type="number" id="Quantity" name="Quantity" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="Cost" class="block text-sm font-medium text-gray-700">Cost:</label>
                <input type="number" step="0.01" id="Cost" name="Cost" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="type_of_service" class="block text-sm font-medium text-gray-700">Type of Service:</label>
                <select id="type_of_service" name="type_of_service" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="Seasonal Maintenance">Seasonal Maintenance</option>
                    <option value="Preventive Maintenance">Preventive Maintenance</option>
                    <option value="Electrical Repair">Electrical Repair</option>
                    <option value="Engine Repair">Engine Repair</option>
                    <option value="Oil/oil filter/air filter changed">Oil/oil filter/air filter changed</option>
                    <option value="Battery replacement">Battery replacement</option>
                    <option value="Engine tune-up">Engine tune-up</option>
                    <option value="Wheels aligned/balanced">Wheels aligned/balanced</option>
                </select>
            </div>
            <div>
                <label for="Description" class="block text-sm font-medium text-gray-700">Description:</label>
                <textarea id="Description" name="Description" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
            </div>
            <div>
                <input type="submit" value="Add Part" class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            </div>
        </form>
        <a href="parts_dashboard.php" class="mt-4 inline-block text-indigo-600 hover:text-indigo-800">Back to Dashboard</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>
