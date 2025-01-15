<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db_connect.php';

// Get unique vehicle registration numbers for dropdown
$vehicle_reg_query = "SELECT DISTINCT Vehicle_Reg_No FROM MaintenanceRecords ORDER BY Vehicle_Reg_No";
$vehicle_reg_result = $conn->query($vehicle_reg_query);

// Get unique service types for dropdown
$service_type_query = "SELECT DISTINCT ServiceType FROM MaintenanceRecords ORDER BY ServiceType";
$service_type_result = $conn->query($service_type_query);

// Initialize filter variables
$where_conditions = [];
$params = [];
$param_types = "";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Vehicle Registration filter
    if (!empty($_GET['vehicle_reg'])) {
        $where_conditions[] = "Vehicle_Reg_No = ?";
        $params[] = $_GET['vehicle_reg'];
        $param_types .= "s";
    }

    // Date range filter
    if (!empty($_GET['date_from'])) {
        $where_conditions[] = "MaintenanceDate >= ?";
        $params[] = $_GET['date_from'];
        $param_types .= "s";
    }
    if (!empty($_GET['date_to'])) {
        $where_conditions[] = "MaintenanceDate <= ?";
        $params[] = $_GET['date_to'];
        $param_types .= "s";
    }

    // Service type filter
    if (!empty($_GET['service_type'])) {
        $where_conditions[] = "ServiceType = ?";
        $params[] = $_GET['service_type'];
        $param_types .= "s";
    }
}

// Build the SQL query
$sql = "SELECT * FROM MaintenanceRecords";
if (!empty($where_conditions)) {
    $sql .= " WHERE " . implode(" AND ", $where_conditions);
}
$sql .= " ORDER BY MaintenanceDate DESC";

// Prepare and execute the query with filters
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($param_types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Calculate statistics for filtered results
$total_records = $result->num_rows;
$total_cost = 0;
$recent_records = 0;
$current_date = date('Y-m-d');
$thirty_days_ago = date('Y-m-d', strtotime('-30 days'));

// Calculate vehicle-specific total cost if vehicle is selected
$selected_vehicle_cost = 0;
if (!empty($_GET['vehicle_reg'])) {
    $cost_query = "SELECT SUM(Cost) as vehicle_total_cost FROM MaintenanceRecords WHERE Vehicle_Reg_No = ?";
    $cost_stmt = $conn->prepare($cost_query);
    $cost_stmt->bind_param("s", $_GET['vehicle_reg']);
    $cost_stmt->execute();
    $cost_result = $cost_stmt->get_result();
    $cost_row = $cost_result->fetch_assoc();
    $selected_vehicle_cost = $cost_row['vehicle_total_cost'];
}

// Reset result pointer
$result->data_seek(0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoMaint - A Multi-Vehicle Maintenance System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #CBD5E1;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background-color: #F1F5F9;
        }
        .dropdown-menu {
            transform-origin: top right;
            transition: all 0.1s ease-out;
        }
    </style>
    <script>
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            const allDropdowns = document.querySelectorAll('.dropdown-menu');

            allDropdowns.forEach(menu => {
                if (menu.id !== id) {
                    menu.classList.add('hidden');
                }
            });

            dropdown.classList.toggle('hidden');
        }

        // Close dropdowns when clicking outside
        window.addEventListener('click', function(e) {
            if (!e.target.closest('.nav-item')) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.classList.add('hidden');
                });
            }
        });

        // Add active state to current page in navigation
        document.addEventListener('DOMContentLoaded', function() {
            const currentPage = window.location.pathname.split('/').pop();
            const menuItems = document.querySelectorAll('nav a');

            menuItems.forEach(item => {
                if (item.getAttribute('href') === currentPage) {
                    item.classList.add('text-blue-600');
                }
            });
        });

        // Real-time search
        function searchRecords() {
            const query = document.getElementById('searchInput').value;
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'search_records.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById('recordsTableBody').innerHTML = xhr.responseText;
                }
            };
            xhr.send('query=' + encodeURIComponent(query));
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Heading -->
    <div class="bg-blue-600 text-white py-4 text-center">
        <h1 class="text-2xl font-bold">AutoMaint - A Multi-Vehicle Maintenance System</h1>
    </div>

    <!-- Navigation Bar -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <span class="text-xl font-bold text-blue-600">AutoMaint</span>
                    </div>

                    <!-- Main Navigation -->
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <!-- Home Dropdown -->
                        <div class="relative nav-item">
                            <button onclick="toggleDropdown('homeDropdown')" class="border-transparent border-b-2 hover:border-gray-300 text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 text-sm font-medium">
                                Home
                                <svg class="ml-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="homeDropdown" class="hidden dropdown-menu absolute z-10 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                <div class="py-1">
                                    <a href="vehicle_maintenance_dashboard.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                                    <a href="reports.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Reports</a>
                                    <a href="settings.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                                </div>
                            </div>
                        </div>

                        <!-- Maintenance Dropdown -->
                        <div class="relative nav-item">
                            <button onclick="toggleDropdown('maintenanceDropdown')" class="border-transparent border-b-2 hover:border-gray-300 text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 text-sm font-medium">
                                Maintenance
                                <svg class="ml-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="maintenanceDropdown" class="hidden dropdown-menu absolute z-10 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                <div class="py-1">
                                    <a href="add_record.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Add Record</a>
                                    <a href="vehicle_maintenance_dashboard.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">View Records</a>
                                    <a href="vehicle_maintenance_dashboard2.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Advance Search</a>
                                    <a href="image_view.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">View Images</a>
                                </div>
                            </div>
                        </div>

                        <!-- Vehicles Dropdown -->
                        <div class="relative nav-item">
                            <button onclick="toggleDropdown('vehiclesDropdown')" class="border-transparent border-b-2 hover:border-gray-300 text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 text-sm font-medium">
                                Vehicles
                                <svg class="ml-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="vehiclesDropdown" class="hidden dropdown-menu absolute z-10 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                <div class="py-1">
                                    <a href="add_vehicle.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Add Vehicle</a>
                                    <a href="vehicles_dashboard.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">List of Vehicles</a>
                                </div>
                            </div>
                        </div>

                        <!-- Parts Dropdown -->
                        <div class="relative nav-item">
                            <button onclick="toggleDropdown('partsDropdown')" class="border-transparent border-b-2 hover:border-gray-300 text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 text-sm font-medium">
                                Parts
                                <svg class="ml-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="partsDropdown" class="hidden dropdown-menu absolute z-10 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                <div class="py-1">
                                    <a href="add_part.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Add Part</a>
                                    <a href="parts_dashboard.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Parts Inventory</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right side buttons -->
                <div class="flex items-center space-x-3">
                    <a href="add_record.php" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Record
                    </a>
                    <a href="add_vehicle.php" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200">
                        Add Vehicle
                    </a>
                    <a href="add_part.php" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200">
                        Add Part
                    </a>
                    <a href="logout.php" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-gray-700 bg-blue-100 hover:bg-blue-200">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Filter Section -->
        <div class="bg-white shadow rounded-lg mb-6 p-4">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Vehicle Registration Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle Registration</label>
                    <select name="vehicle_reg" class="w-full border rounded-md p-2">
                        <option value="">All Vehicles</option>
                        <?php while($reg = $vehicle_reg_result->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($reg['Vehicle_Reg_No']); ?>"
                                    <?php echo (isset($_GET['vehicle_reg']) && $_GET['vehicle_reg'] == $reg['Vehicle_Reg_No']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($reg['Vehicle_Reg_No']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Date Range Filters -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                    <input type="date" name="date_from" class="w-full border rounded-md p-2"
                           value="<?php echo isset($_GET['date_from']) ? $_GET['date_from'] : ''; ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                    <input type="date" name="date_to" class="w-full border rounded-md p-2"
                           value="<?php echo isset($_GET['date_to']) ? $_GET['date_to'] : ''; ?>">
                </div>

                <!-- Service Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Service Type</label>
                    <select name="service_type" class="w-full border rounded-md p-2">
                        <option value="">All Services</option>
                        <?php while($type = $service_type_result->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($type['ServiceType']); ?>"
                                    <?php echo (isset($_GET['service_type']) && $_GET['service_type'] == $type['ServiceType']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($type['ServiceType']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Filter Button -->
                <div class="md:col-span-4 flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Apply Filters
                    </button>
                    <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            <!-- Total Records Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dt class="text-sm font-medium text-gray-500 truncate">Filtered Records</dt>
                            <dd class="text-lg font-semibold text-gray-900"><?php echo $total_records; ?></dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Cost Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                <?php echo !empty($_GET['vehicle_reg']) ? 'Vehicle Total Cost' : 'Total Filtered Cost'; ?>
                            </dt>
                            <dd class="text-lg font-semibold text-gray-900">
                                Rs.<?php echo number_format(!empty($_GET['vehicle_reg']) ? $selected_vehicle_cost : $total_cost, 2); ?>
                            </dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Average Cost Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dt class="text-sm font-medium text-gray-500 truncate">Average Cost</dt>
                            <dd class="text-lg font-semibold text-gray-900">
                                Rs.<?php echo $total_records > 0 ? number_format($total_cost / $total_records, 2) : '0.00'; ?>
                            </dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="mb-4">
            <input type="text" id="searchInput" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Search records..." onkeyup="searchRecords()">
        </div>

        <!-- Maintenance Records Table -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Record ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle_Reg_No</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Odometer</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service Type</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cost</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">View Image</th>
                            </tr>
                        </thead>
                        <tbody id="recordsTableBody" class="bg-white divide-y divide-gray-200">
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="edit_record.php?id=<?php echo $row['RecordID']; ?>" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                            <a href="delete_record.php?id=<?php echo $row['RecordID']; ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $row['RecordID']; ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $row['VehicleID']; ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $row['Vehicle_Reg_No']; ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo date('M d, Y', strtotime($row['MaintenanceDate'])); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo number_format($row['OdometerReading']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                <?php echo $row['ServiceType']; ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo $row['ServiceDescription']; ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rs.<?php echo number_format($row['Cost'], 2); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="image_view.php?vehicle_reg_no=<?php echo urlencode($row['Vehicle_Reg_No']); ?>&maintenance_date=<?php echo urlencode($row['MaintenanceDate']); ?>" class="text-blue-600 hover:text-blue-900">View Image</a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">No records found</td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white text-center py-4">
        <p>&copy; <?php echo date('Y'); ?> Kevin Digital Developers. All Rights Reserved.</p>
		<p><a href="mailto:earsekanayake@yandex.com">earsekanayake@yandex.com</a></p>
    </footer>
</body>
</html>

<?php
$conn->close();
?>
