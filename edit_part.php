<?php
include 'db_connect.php';

$partID = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recordID = $_POST['RecordID'];
    $Vehicle_Reg_No = $_POST['Vehicle_Reg_No'];
    $partName = $_POST['PartName'];
    $partNumber = $_POST['PartNumber'];
    $quantity = $_POST['Quantity'];
    $cost = $_POST['Cost'];
    $typeOfService = $_POST['type_of_service'];
    $description = $_POST['Description'];

    $sql = "UPDATE PartsUsed SET RecordID='$recordID', Vehicle_Reg_No='$Vehicle_Reg_No', PartName='$partName', PartNumber='$partNumber', Quantity='$quantity', Cost='$cost', type_of_service='$typeOfService', Description='$description' WHERE PartID='$partID'";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800' role='alert'>
                <span class='font-medium'>Success!</span> Part record updated successfully.
              </div>";
    } else {
        echo "<div class='p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800' role='alert'>
                <span class='font-medium'>Error!</span> " . $conn->error . "
              </div>";
    }
}

$sql = "SELECT * FROM PartsUsed WHERE PartID='$partID'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Part</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Edit Part</h1>
        <form method="post" action="edit_part.php?id=<?php echo $partID; ?>" class="space-y-4">
            <div>
                <label for="RecordID" class="block text-sm font-medium text-gray-700">RecordID:</label>
                <input type="number" id="RecordID" name="RecordID" value="<?php echo $row['RecordID']; ?>" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="Vehicle_Reg_No" class="block text-sm font-medium text-gray-700">Vehicle Reg No:</label>
                <input type="text" id="Vehicle_Reg_No" name="Vehicle_Reg_No" value="<?php echo $row['Vehicle_Reg_No']; ?>" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="PartName" class="block text-sm font-medium text-gray-700">Part Name:</label>
                <input type="text" id="PartName" name="PartName" value="<?php echo $row['PartName']; ?>" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="PartNumber" class="block text-sm font-medium text-gray-700">Part Number:</label>
                <input type="text" id="PartNumber" name="PartNumber" value="<?php echo $row['PartNumber']; ?>" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="Quantity" class="block text-sm font-medium text-gray-700">Quantity:</label>
                <input type="number" id="Quantity" name="Quantity" value="<?php echo $row['Quantity']; ?>" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="Cost" class="block text-sm font-medium text-gray-700">Cost:</label>
                <input type="number" step="0.01" id="Cost" name="Cost" value="<?php echo $row['Cost']; ?>" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="type_of_service" class="block text-sm font-medium text-gray-700">Type of Service:</label>
                <select id="type_of_service" name="type_of_service" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="Seasonal Maintenance" <?php if ($row['type_of_service'] == 'Seasonal Maintenance') echo 'selected'; ?>>Seasonal Maintenance</option>
                    <option value="Preventive Maintenance" <?php if ($row['type_of_service'] == 'Preventive Maintenance') echo 'selected'; ?>>Preventive Maintenance</option>
                    <option value="Electrical Repair" <?php if ($row['type_of_service'] == 'Electrical Repair') echo 'selected'; ?>>Electrical Repair</option>
                    <option value="Engine Repair" <?php if ($row['type_of_service'] == 'Engine Repair') echo 'selected'; ?>>Engine Repair</option>
                    <option value="Oil/oil filter/air filter changed" <?php if ($row['type_of_service'] == 'Oil/oil filter/air filter changed') echo 'selected'; ?>>Oil/oil filter/air filter changed</option>
                    <option value="Battery replacement" <?php if ($row['type_of_service'] == 'Battery replacement') echo 'selected'; ?>>Battery replacement</option>
                    <option value="Engine tune-up" <?php if ($row['type_of_service'] == 'Engine tune-up') echo 'selected'; ?>>Engine tune-up</option>
                    <option value="Wheels aligned/balanced" <?php if ($row['type_of_service'] == 'Wheels aligned/balanced') echo 'selected'; ?>>Wheels aligned/balanced</option>
                </select>
            </div>
            <div>
                <label for="Description" class="block text-sm font-medium text-gray-700">Description:</label>
                <textarea id="Description" name="Description" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"><?php echo $row['Description']; ?></textarea>
            </div>
            <div>
                <input type="submit" value="Update" class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            </div>
        </form>
        <a href="index.php" class="mt-4 inline-block text-indigo-600 hover:text-indigo-800">Back to Dashboard</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>
