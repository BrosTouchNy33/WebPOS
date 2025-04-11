<?php
// add_category.php

require_once '../db/connection_db.php'; // Include database connection

session_start();

// Check if the user is logged in and has admin role
if (!isset($_SESSION['UserID']) || !isset($_SESSION['Username']) || $_SESSION['Role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $categoryName = $_POST["categoryName"];

    // Basic validation
    if (empty($categoryName)) {
        $message = "<p class='text-red-500 text-sm'>Please enter a category name.</p>";
    } else {
        // Check if the category name already exists
        $stmt_check = $conn->prepare("SELECT CategoryID FROM Categories WHERE CategoryName = ?");
        $stmt_check->bind_param("s", $categoryName);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $message = "<p class='text-red-500 text-sm'>Category name already exists.</p>";
        } else {
            // Insert the new category into the database
            $stmt_insert = $conn->prepare("INSERT INTO Categories (CategoryName) VALUES (?)");
            $stmt_insert->bind_param("s", $categoryName);

            if ($stmt_insert->execute()) {
                $message = "<p class='text-green-500 text-sm'>Category added successfully!</p>";
            } else {
                $message = "<p class='text-red-500 text-sm'>Error adding category: " . $stmt_insert->error . "</p>";
            }
            $stmt_insert->close();
        }
        $stmt_check->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Category</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            light: '#818cf8',
                            DEFAULT: '#4f46e5',
                            dark: '#3730a3',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 min-h-screen flex">
<?php include 'sidebar.php'; ?>

<!-- Main Content Area -->
<div id="content" class="flex-1 transition-all duration-300 ml-0 md:ml-64">
    <!-- Top Bar -->
    <div class="bg-white dark:bg-gray-800 shadow-sm h-16 flex items-center px-6 sticky top-0 z-10">
        <button id="menu-toggle" class="md:hidden mr-4 p-2 -ml-2 rounded-md text-gray-600 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
        <h1 class="text-xl font-semibold text-gray-800 dark:text-white">Add New Category</h1>
    </div>

    <!-- Content Container -->
    <main class="p-6">
        <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-sm">
            <div class="p-6 md:p-8">
                <?php if (!empty($message)): ?>
                    <div class="mb-6 p-4 rounded-lg <?= strpos($message, 'success') !== false ?
                        'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300' :
                        'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300' ?>">
                        <?= $message ?>
                    </div>
                <?php endif; ?>

                <div class="space-y-1 mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">New Category</h2>
                    <p class="text-gray-500 dark:text-gray-400">Create a new product category</p>
                </div>

                <form method="post" class="space-y-6">
                    <div class="space-y-2">
                        <label for="categoryName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Category Name
                        </label>
                        <input
                                type="text"
                                id="categoryName"
                                name="categoryName"
                                required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-primary focus:border-primary bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400"
                                placeholder="Electronics, Clothing, etc."
                        >
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Use a descriptive and unique category name
                        </p>
                    </div>

                    <button
                            type="submit"
                            class="w-full py-3 px-6 bg-primary hover:bg-primary-dark text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                    >
                        Create Category
                    </button>
                </form>

                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700">
                    <a href="dashboard.php" class="inline-flex items-center text-sm text-primary hover:text-primary-dark font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Return to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>
