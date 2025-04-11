<?php
// index.php

// Include the database connection file
require_once 'db/connection_db.php';


session_start();
ob_start(); // start output buffering
// Start the session (if not already started)

$login_message = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Prepare and execute the SQL query to fetch the user
    $stmt = $conn->prepare("SELECT UserID, Username, Password, Role FROM users WHERE Username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    header("Location: /src/dashboard.php");
    exit();
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $storedHashedPassword = $row["Password"];

        // Verify the entered password against the stored hash
        if (password_verify($password, $storedHashedPassword)) {
            $login_message = "<p class='text-green-500 mb-4'>Login successful!</p>";
            $_SESSION['UserID'] = $row["UserID"];
            $_SESSION['Username'] = $row["Username"];
            $_SESSION['Role'] = $row['Role']; // Assuming your Users table has a 'Role' column
           
        } else {
            $login_message = "<p class='text-red-500 mb-4'>Login failed. Incorrect password.</p>";
        }
    } else {
        $login_message = "<p class='text-red-500 mb-4'>Login failed. User not found.</p>";
    }

    var_dump($password, $storedHashedPassword, password_verify($password, $storedHashedPassword));
// exit();

    $stmt->close();
}

$conn->close();

// If login was not successful, you can display the message on the form
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Inventory Management System</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome Icons -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
<div class="max-w-md w-full mx-4">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-indigo-600 py-6 px-8">
            <h2 class="text-2xl font-bold text-white text-center">Inventory Management</h2>
        </div>

        <div class="p-8">
            <div class="flex justify-center mb-6">
                <div class="bg-indigo-100 rounded-full p-3">
                    <i class="fas fa-user-lock text-indigo-600 text-2xl"></i>
                </div>
            </div>

            <h3 class="text-xl font-semibold text-center text-gray-800 mb-6">Account Login</h3>

            <?php if (!empty($login_message)) { echo $login_message; } ?>

            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="space-y-6">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input
                                type="text"
                                id="username"
                                name="username"
                                required
                                class="pl-10 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 border p-3"
                                placeholder="Enter your username"
                        >
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                class="pl-10 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 border p-3"
                                placeholder="Enter your password"
                        >
                    </div>
                </div>

                <div>
                    <button
                            type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200"
                    >
                        <i class="fas fa-sign-in-alt mr-2"></i> Login
                    </button>
                </div>

                <div class="text-center text-sm text-gray-500">
                    <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500 transition duration-200">Forgot your password?</a>
                </div>
            </form>
        </div>
    </div>

    <div class="text-center mt-4 text-sm text-gray-600">
        &copy; <?php echo date("Y"); ?> Inventory Management System
    </div>
</div>

<script>
    // Optional: Add simple password visibility toggle
    document.addEventListener('DOMContentLoaded', function() {
        const passwordField = document.getElementById('password');
        const togglePassword = document.createElement('i');
        togglePassword.className = 'fas fa-eye text-gray-400 cursor-pointer absolute right-3 top-1/2 transform -translate-y-1/2';
        togglePassword.style.zIndex = '10';

        passwordField.parentNode.appendChild(togglePassword);
        
        togglePassword.addEventListener('click', function() {
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                togglePassword.className = 'fas fa-eye-slash text-gray-400 cursor-pointer absolute right-3 top-1/2 transform -translate-y-1/2';
            } else {
                passwordField.type = 'password';
                togglePassword.className = 'fas fa-eye text-gray-400 cursor-pointer absolute right-3 top-1/2 transform -translate-y-1/2';
            }
        });
    });
</script>
</body>
</html>