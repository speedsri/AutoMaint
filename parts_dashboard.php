<?php
include 'db_connect.php';

// Fetch data from PartsUsed
$sql = "SELECT * FROM PartsUsed";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Parts Management Dashboard</title>
    <link href="css/tailwind.min.css" rel="stylesheet">
    <link href="css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Parts Management Dashboard</h1>
        <a href="add_part.php" class="mb-4 inline-block bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700">Add New Part</a>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PartID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">RecordID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle Reg No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Part Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Part Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cost</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type of Service</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='px-6 py-4 whitespace-nowrap'><a href='edit_part.php?id=" . $row['PartID'] . "' class='text-indigo-600 hover:text-indigo-900'>Edit</a> | <a href='delete_part.php?id=" . $row['PartID'] . "' class='text-red-600 hover:text-red-900'>Delete</a></td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row['PartID'] . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row['RecordID'] . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row['Vehicle_Reg_No'] . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row['PartName'] . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row['PartNumber'] . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row['Quantity'] . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row['Cost'] . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row['type_of_service'] . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row['Description'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='10' class='px-6 py-4 whitespace-nowrap text-center'>No records found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <a href="index.php" class="mt-4 inline-block text-indigo-600 hover:text-indigo-800">Back to Home Dashboard</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>
