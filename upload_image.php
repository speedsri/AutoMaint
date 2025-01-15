<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehicle_reg_no = $_POST['vehicle_reg_no'];
    $maintenance_date = $_POST['maintenance_date'];

    // Check if an image file is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the file is an actual image or a fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            // Check file size
            if ($_FILES["image"]["size"] > 5000000) {
                $error = "Sorry, your file is too large.";
            } else {
                // Allow certain file formats
                $allowed_extensions = array("jpg", "jpeg", "png", "gif");
                if (in_array($imageFileType, $allowed_extensions)) {
                    // Move the uploaded file to the target directory
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                        // Update the database with the image path
                        $image_path = $target_file;
                        $sql = "UPDATE MaintenanceRecords SET ServiceImage = ? WHERE Vehicle_Reg_No = ? AND MaintenanceDate = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("sss", $image_path, $vehicle_reg_no, $maintenance_date);
                        if ($stmt->execute()) {
                            $success = "The file ". htmlspecialchars(basename($_FILES["image"]["name"])). " has been uploaded.";
                        } else {
                            $error = "Error updating the database: " . $stmt->error;
                        }
                        $stmt->close();
                    } else {
                        $error = "Sorry, there was an error uploading your file.";
                    }
                } else {
                    $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                }
            }
        } else {
            $error = "File is not an image.";
        }
    } else {
        $error = "No file uploaded.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Service Image</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold mb-4 text-center">Upload Service Image</h1>
        <?php if (isset($error)) { ?>
            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
                <span class="font-medium"><?php echo $error; ?></span>
            </div>
        <?php } ?>
        <?php if (isset($success)) { ?>
            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                <span class="font-medium"><?php echo $success; ?></span>
            </div>
        <?php } ?>
        <form method="post" action="upload_image.php" class="space-y-4" enctype="multipart/form-data">
            <div>
                <label for="vehicle_reg_no" class="block text-sm font-medium text-gray-700">Vehicle Registration Number:</label>
                <input type="text" id="vehicle_reg_no" name="vehicle_reg_no" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="maintenance_date" class="block text-sm font-medium text-gray-700">Maintenance Date:</label>
                <input type="date" id="maintenance_date" name="maintenance_date" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700">Select Image:</label>
                <input type="file" id="image" name="image" accept="image/*" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <input type="submit" value="Upload Image" class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            </div>
        </form>
        <div class="mt-4 text-center">
            <a href="index.php" class="text-indigo-600 hover:text-indigo-800">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
