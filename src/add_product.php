<?php
// add_product.php

require_once '../db/connection_db.php'; // Include database connection

session_start();

// Check if the user is logged in and has admin role
if (!isset($_SESSION['UserID']) || !isset($_SESSION['Username']) || $_SESSION['Role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = $_POST["productName"];
    $categoryId = $_POST["categoryId"];
    $price = $_POST["price"];
    $stockQuantity = $_POST["stockQuantity"];
    $productImage = ""; // Initialize image path

    // Basic validation
    if (empty($productName) || empty($price) || !is_numeric($price) || empty($stockQuantity) || !is_numeric($stockQuantity)) {
        $message = "<p class='text-red-500 text-sm'>Please fill in all required fields with valid data.</p>";
    } else {
        // Handle image upload if a file was selected
        if (isset($_FILES["productImage"]) && $_FILES["productImage"]["error"] == 0) {
            $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
            $filename = basename($_FILES["productImage"]["name"]);
            $filetype = $_FILES["productImage"]["type"];
            $filesize = $_FILES["productImage"]["size"];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);

            $uploadDir = "uploads/products/"; // Directory to store images (create this folder)
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $targetFile = $uploadDir . uniqid() . "." . $ext; // Unique filename

            $maxSize = 2 * 1024 * 1024; // 2MB

            if (!array_key_exists($ext, $allowed) || !in_array($filetype, $allowed)) {
                $message = "<p class='text-red-500 text-sm'>Error: Please select a valid image file (jpg, jpeg, gif, png).</p>";
            } elseif ($filesize > $maxSize) {
                $message = "<p class='text-red-500 text-sm'>Error: Image size should not exceed 2MB.</p>";
            } else {
                if (move_uploaded_file($_FILES["productImage"]["tmp_name"], $targetFile)) {
                    $productImage = $targetFile; // Save the path to the database
                } else {
                    $message = "<p class='text-red-500 text-sm'>Error: There was an error uploading your file.</p>";
                }
            }
        }

        // If no validation errors, insert the product into the database
        if (empty($message)) {
            $stmt = $conn->prepare("INSERT INTO Products (ProductName, CategoryID, Price, StockQuantity, product_image) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sidds", $productName, $categoryId, $price, $stockQuantity, $productImage);

            if ($stmt->execute()) {
                $message = "<p class='text-green-500 text-sm'>Product added successfully!</p>";
            } else {
                $message = "<p class='text-red-500 text-sm'>Error adding product: " . $stmt->error . "</p>";
            }
            $stmt->close();
            header("Location: view_product.php");

        }
    }
}

// Fetch categories for the dropdown
$categories =[];
$result_categories = $conn->query("SELECT CategoryID, CategoryName FROM Categories");

if ($result_categories->num_rows > 0) {
    while ($row = $result_categories->fetch_assoc()) {
        $categories[]= $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 h-screen flex">

<?php include 'sidebar.php' ?>



<div id="content" class="flex-1 p-6 lg:ml-64 flex items-start justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden md:max-w-2xl w-full flex flex-col md:flex-row">

        <div class="p-6 md:p-8 w-full md:w-1/2">
            <h2 class="text-xl md:text-2xl font-bold text-gray-800 dark:text-white mb-4 md:mb-6 text-center md:text-left">Add New Product</h2>
            <?php if (!empty($message)): ?>
                <div class="mb-4 text-center md:text-left text-gray-700 dark:text-gray-300"><?php echo $message; ?></div>
            <?php endif; ?>
            <form method="post" enctype="multipart/form-data" class="space-y-4 md:space-y-5">
                <div class="relative">
                    <input type="text" id="productName" name="productName" required
                           class="peer w-full border-b-2 border-gray-300 dark:border-gray-600 bg-transparent pt-3 pb-2 px-0 text-gray-900 dark:text-gray-200 focus:border-blue-500 focus:outline-none transition-colors text-sm md:text-base"
                           placeholder=" " />
                    <label for="productName"
                           class="absolute left-0 -top-3.5 text-gray-600 dark:text-gray-400 text-xs md:text-sm transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 dark:peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-2 peer-focus:-top-3.5 peer-focus:text-blue-500 peer-focus:text-xs md:text-sm">
                        Product Name
                    </label>
                </div>

                <div class="relative mt-2 md:mt-4">
                    <select id="categoryId" name="categoryId" required
                            class="appearance-none absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10 p-5 mt-2 border-red-950">
                        <option value="" disabled selected hidden></option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['CategoryID']; ?>"><?php echo htmlspecialchars($category['CategoryName']); ?></option>
                        <?php endforeach; ?>
                    </select>

                    <div class="relative w-full">
                        <div class="flex items-center justify-between w-full px-3 md:px-4 py-2 md:py-3 text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl cursor-pointer group hover:border-blue-400 focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-200 transition-all duration-200">
                            <div class="flex items-center space-x-2 md:space-x-3">
                                <svg class="w-4 h-4 md:w-5 md:h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>

                                <span id="categoryDisplay" class="font-medium truncate text-gray-500 dark:text-gray-400 text-sm md:text-base">Select Category</span>
                            </div>

                            <div class="flex items-center transition-transform duration-200 group-focus-within:rotate-180">
                                <svg class="w-4 h-4 md:w-5 md:h-5 text-gray-400 group-hover:text-blue-500 group-focus-within:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>

                        <label for="categoryId" class="absolute -top-2 left-3 px-1 bg-white dark:bg-gray-900 text-xs font-medium text-blue-500">
                            Category
                        </label>
                    </div>
                </div>

                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none">
                        <span class="text-gray-500 dark:text-gray-400 text-sm ml-1">$</span>
                    </div>
                    <input type="number" id="price" name="price" step="0.01" required
                           class="peer w-full border-b-2 border-gray-300 dark:border-gray-600 bg-transparent pt-3 pb-2 pl-5 pr-6 text-gray-900 dark:text-gray-200 focus:border-blue-500 focus:outline-none transition-colors text-sm md:text-base"
                           placeholder=" " />
                    <label for="price"
                           class="absolute left-5 -top-3.5 text-gray-600 dark:text-gray-400 text-xs md:text-sm transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 dark:peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-2 peer-focus:-top-3.5 peer-focus:text-blue-500 peer-focus:text-xs md:text-sm">
                        Price
                    </label>
                </div>

                <div class="relative">
                    <input type="number" id="stockQuantity" name="stockQuantity" required
                           class="peer w-full border-b-2 border-gray-300 dark:border-gray-600 bg-transparent pt-3 pb-2 px-0 text-gray-900 dark:text-gray-200 focus:border-blue-500 focus:outline-none transition-colors text-sm md:text-base"
                           placeholder=" " />
                    <label for="stockQuantity"
                           class="absolute left-0 -top-3.5 text-gray-600 dark:text-gray-400 text-xs md:text-sm transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 dark:peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-2 peer-focus:-top-3.5 peer-focus:text-blue-500 peer-focus:text-xs md:text-sm">
                        Stock Quantity
                    </label>
                </div>

                <div class="mt-4 md:mt-6">
                    <label for="productImage" class="block text-gray-600 dark:text-gray-400 text-sm md:text-base mb-2">Product Image (Optional)</label>
                    <div class="relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 md:p-6 flex justify-center items-center hover:border-blue-500 transition-colors bg-white dark:bg-gray-700">
                        <input type="file" name="productImage" id="productImage" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                        <div class="text-center">
                            <svg class="mx-auto h-8 w-8 md:h-12 md:w-12 text-gray-400 dark:text-gray-600" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Drop an image here or click to upload</p>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-medium py-2 md:py-3 px-4 rounded-lg mt-4 md:mt-6 hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform transition hover:scale-shadow-md text-sm md:text-base">
                    Add Product
                </button>
            </form>
            <p class="mt-4 md:mt-6 text-center text-gray-600 dark:text-gray-400 md:text-left text-sm">
                <a href="dashboard.php" class="text-blue-500 hover:text-blue-700 font-medium">← Back to Dashboard</a>
            </p>
        </div>
        <div class="p-6 md:p-8 w-full md:w-1/2 flex items-center justify-center bg-gray-100 dark:bg-gray-800">
            <div id="image-preview" class="w-full h-full flex items-center justify-center text-gray-400 dark:text-gray-600">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 md:h-16 md:w-16" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <p class="mt-2 text-sm">Image preview will appear here</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                colors: {
                    primary: {
                        50: '#f0f9ff',
                        100: '#e0f2fe',
                        200: '#bae6fd',
                        300: '#7dd3fc',
                        400: '#38bdf8',
                        500: '#0ea5e9',
                        600: '#0284c7',
                        700: '#0369a1',
                        800: '#075985',
                        900: '#0c4a6e',
                        950: '#082f49',
                    },
                    secondary: {
                        50: '#f8fafc',
                        100: '#f1f5f9',
                        200: '#e2e8f0',
                        300: '#cbd5e1',
                        400: '#94a3b8',
                        500: '#64748b',
                        600: '#475569',
                        700: '#334155',
                        800: '#1e293b',
                        900: '#0f172a',
                        950: '#020617',
                    }
                },
                fontFamily: {
                    sans: ['Inter var', 'sans-serif'],
                },
                boxShadow: {
                    'card': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
                    'input': '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
                },
            }
        }
    }

    document.getElementById('productImage').addEventListener('change', function(event) {
        const previewContainer = document.getElementById('image-preview');
        previewContainer.innerHTML = ''; // Clear previous preview

        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('max-w-md', 'max-h-48', 'rounded-md', 'shadow-sm');
                previewContainer.appendChild(img);
            }

            reader.readAsDataURL(file);
        } else {
            // Display default preview message if no file selected
            const defaultPreview = `
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 md:h-16 md:w-16 text-gray-400 dark:text-gray-600" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="mt-2 text-sm">Image preview will appear here</p>
                    </div>
                `;
            previewContainer.innerHTML = defaultPreview;
        }
    });

    document.getElementById('categoryId').addEventListener('change', function() {
        const select = this;
        const text = select.options[select.selectedIndex].text;
        document.getElementById('categoryDisplay').textContent = text;
        document.getElementById('categoryDisplay').classList.remove('text-gray-500', 'dark:text-gray-400');
        document.getElementById('categoryDisplay').classList.add('text-gray-800', 'dark:text-gray-200');
    });


    // Assuming you are using some TWE library for enhanced UI components
    // If not, you can remove this line
    // initTWE({ Input });
</script>
</body>
</html>
