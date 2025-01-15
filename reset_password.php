<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM Users WHERE ResetToken='$token' AND TokenExpiry > NOW()";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $update_sql = "UPDATE Users SET Password='$hashed_password', ResetToken=NULL, TokenExpiry=NULL WHERE UserID='$user[UserID]'";
        $conn->query($update_sql);

        $success = "Your password has been reset successfully.";
    } else {
        $error = "Invalid or expired token.";
    }
} elseif (isset($_GET['token'])) {
    $token = $_GET['token'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold mb-4 text-center">Reset Password</h1>
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
        <form method="post" action="reset_password.php" class="space-y-4">
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">New Password:</label>
                <input type="password" id="password" name="password" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <input type="submit" value="Reset Password" class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            </div>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
