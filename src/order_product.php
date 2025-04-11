<?php
/*--------------------------------------------------------------
Order Processing System (Single File Version)
--------------------------------------------------------------*/
session_start();

/*--------------------------------------------------------------
Database Connection
--------------------------------------------------------------*/
require_once '../db/connection_db.php';
if (!isset($_SESSION['UserID']) || !isset($_SESSION['Username'])) {
    header("Location: ../index.php");
    exit();
}

/*--------------------------------------------------------------
Initialize Variables
--------------------------------------------------------------*/
$error = '';
$success = '';
$sale_id = null;

$products = [];
$category_query = "SELECT c.CategoryID, c.CategoryName, p.ProductID, p.ProductName FROM categories c INNER JOIN products p USING (CategoryID) ORDER BY c.CategoryName, p.ProductName;";
$result = $conn->query($category_query);

// Organize products by category
while ($row = $result->fetch_assoc()) {
    $products[$row['CategoryName']][] = $row;

}

/*--------------------------------------------------------------
Process Form Submission
--------------------------------------------------------------*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Verify user authentication
        if (!isset($_SESSION['UserID'])) {
            throw new Exception("You must be logged in to process orders");
        }

        // Validate required fields
        if (!isset($_POST['items']) || !is_array($_POST['items']) || empty($_POST['payment_method'])) {
            throw new Exception("Invalid order data received");
        }

        /*--------------------------------------------------------------
        Transaction Start
        --------------------------------------------------------------*/
        $conn->begin_transaction();

        /*--------------------------------------------------------------
        Customer Processing
        --------------------------------------------------------------*/
//        $customer_id = null;
//        if (!empty($_POST['customer_name'])) {
//            // Check existing customer
//            $stmt = $conn->prepare("SELECT CustomerID FROM customers
//                                  WHERE CustomerName = ? OR ContactNumber = ?");
//            $stmt->bind_param("ss", $_POST['customer_name'], $_POST['contact_number']);
//            $stmt->execute();
//            $result = $stmt->get_result();
//
//            if ($result->num_rows > 0) {
//                $customer_id = $result->fetch_assoc()['CustomerID'];
//            } else {
//                // Insert new customer
//                $stmt = $conn->prepare("INSERT INTO customers
//                                      (CustomerName, ContactNumber, Email, CustomerAddress)
//                                      VALUES (?, ?, ?, ?)");
//                $stmt->bind_param(
//                    "ssss",
//                    $_POST['customer_name'],
//                    $_POST['contact_number'],
//                    $_POST['email'],
//                    $_POST['address']
//                );
//                $stmt->execute();
//                $customer_id = $stmt->insert_id;
//            }
//        }

        $customer_id = null;
        if (!empty($_POST['customer_name'])) {
            // Insert new customer directly
            $stmt = $conn->prepare("INSERT INTO customers
                          (CustomerName, ContactNumber, Email, CustomerAddress)
                          VALUES (?, ?, ?, ?)");
            $stmt->bind_param(
                "ssss",
                $_POST['customer_name'],
                $_POST['contact_number'],
                $_POST['email'],
                $_POST['address']
            );
            $stmt->execute();
            $customer_id = $stmt->insert_id;
        }

        /*--------------------------------------------------------------
        Create Sale Record
        --------------------------------------------------------------*/
        $stmt = $conn->prepare("INSERT INTO sales (CustomerID, UserID, TotalAmount) 
                              VALUES (?, ?, 0)");
        $stmt->bind_param("ii", $customer_id, $_SESSION['user_id']);
        $stmt->execute();
        $sale_id = $stmt->insert_id;

        /*--------------------------------------------------------------
        Process Order Items
        --------------------------------------------------------------*/
        $total_amount = 0;
        foreach ($_POST['items'] as $item) {
            // Validate item format
            if (!isset($item['product_id'], $item['quantity'])) {
                throw new Exception("Invalid item format");
            }

            // Get product information
            $stmt = $conn->prepare("SELECT Price, StockQuantity FROM products WHERE ProductID = ?");
            $stmt->bind_param("i", $item['product_id']);
            $stmt->execute();
            $product = $stmt->get_result()->fetch_assoc();

            if (!$product) {
                throw new Exception("Product ID {$item['product_id']} not found");
            }

            // Check stock availability
            if ($product['StockQuantity'] < $item['quantity']) {
                throw new Exception("Insufficient stock for product ID {$item['product_id']}");
            }

            // Calculate subtotal
            $subtotal = $item['quantity'] * $product['Price'];
            $total_amount += $subtotal;

            // Insert sale item
            $stmt = $conn->prepare("INSERT INTO saleitems 
                                  (SaleID, ProductID, Quantity, PricePerUnit, Subtotal)
                                  VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param(
                "iiidd",
                $sale_id,
                $item['product_id'],
                $item['quantity'],
                $product['Price'],
                $subtotal
            );
            $stmt->execute();

            // Update inventory
            $stmt = $conn->prepare("UPDATE products SET StockQuantity = StockQuantity - ? 
                                  WHERE ProductID = ?");
            $stmt->bind_param("ii", $item['quantity'], $item['product_id']);
            $stmt->execute();
        }

        /*--------------------------------------------------------------
        Finalize Sale
        --------------------------------------------------------------*/
        // Update total amount
        $stmt = $conn->prepare("UPDATE sales SET TotalAmount = ? WHERE SaleID = ?");
        $stmt->bind_param("di", $total_amount, $sale_id);
        $stmt->execute();

        // Record payment
        $stmt = $conn->prepare("INSERT INTO payments (SaleID, PaymentMethod, Amount)
                              VALUES (?, ?, ?)");
        $stmt->bind_param(
            "isd",
            $sale_id,
            $_POST['payment_method'],
            $total_amount
        );
        $stmt->execute();

        // Commit transaction
        $conn->commit();
        $success = "Order processed successfully! Sale ID: $sale_id";

    } catch (Exception $e) {
        $conn->rollback();
        $error = "Error processing order: " . $e->getMessage();
    }
}


?>


<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Order System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
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
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .dark .payment-card {
            background-color: #1f2937;
            border-color: #374151;
        }

        .dark .payment-card:hover {
            border-color: #3b82f6;
        }

        .dark .payment-option input:checked + .payment-card {
            background-color: #1e3a8a;
            border-color: #3b82f6;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-secondary-800 dark:text-gray-200 flex min-h-screen">
<?php include 'sidebar.php'; ?>

<div class="lg:ml-64 w-full p-6">
    <div class="max-w-6xl mx-auto">
        <header class="mb-8">
            <h1 class="text-xl md:text-3xl font-bold text-secondary-900 dark:text-white mb-2">Order Processing</h1>
            <p class="text-sm md:text-base text-secondary-500 dark:text-gray-400">Create a new order by filling in the details below</p>
        </header>

        <?php if ($error): ?>
            <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 rounded-md text-red-700 dark:text-red-300 p-4 mb-6 shadow-sm" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium"><?= $error ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 rounded-md text-green-700 dark:text-green-300 p-4 mb-6 shadow-sm" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium"><?= $success ?></p>
                    </div>
                </div>
            </div>
            <a href="<?= $_SERVER['PHP_SELF'] ?>" class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-sm transition-all">
                <i class="fas fa-plus mr-2"></i> New Order
            </a>
        <?php else: ?>
            <form method="post" class="space-y-6 md:space-y-8">
                <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-card hover:shadow-lg transition-all">
                    <div class="flex items-center mb-4 md:mb-5">
                        <div class="rounded-full bg-primary-100 dark:bg-primary-900/30 p-2 mr-3">
                            <i class="fas fa-user text-primary-600 dark:text-primary-400"></i>
                        </div>
                        <h3 class="text-base md:text-lg font-medium text-secondary-900 dark:text-white">Customer Details</h3>
                        <span class="ml-2 text-xs text-secondary-500 dark:text-gray-400 bg-secondary-100 dark:bg-gray-700 px-2 py-1 rounded-full">Optional</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-5">
                        <div>
                            <label for="customer_name" class="block text-sm font-medium text-secondary-700 dark:text-gray-300 mb-1">Customer Name</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user-tag text-secondary-400 dark:text-gray-500"></i>
                                </div>
                                <input type="text" id="customer_name" name="customer_name" class="pl-10 w-full p-2 border border-secondary-300 dark:border-gray-600 rounded-lg shadow-input focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none bg-white dark:bg-gray-700 text-secondary-900 dark:text-gray-200 text-sm">
                            </div>
                        </div>

                        <div>
                            <label for="contact_number" class="block text-sm font-medium text-secondary-700 dark:text-gray-300 mb-1">Phone Number</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-phone text-secondary-400 dark:text-gray-500"></i>
                                </div>
                                <input type="text" id="contact_number" name="contact_number" class="pl-10 w-full p-2 border border-secondary-300 dark:border-gray-600 rounded-lg shadow-input focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none bg-white dark:bg-gray-700 text-secondary-900 dark:text-gray-200 text-sm">
                            </div>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-secondary-700 dark:text-gray-300 mb-1">Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-secondary-400 dark:text-gray-500"></i>
                                </div>
                                <input type="email" id="email" name="email" class="pl-10 w-full p-2 border border-secondary-300 dark:border-gray-600 rounded-lg shadow-input focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none bg-white dark:bg-gray-700 text-secondary-900 dark:text-gray-200 text-sm">
                            </div>
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-secondary-700 dark:text-gray-300 mb-1">Address</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-map-marker-alt text-secondary-400 dark:text-gray-500"></i>
                                </div>
                                <input type="text" id="address" name="address" class="pl-10 w-full p-2 border border-secondary-300 dark:border-gray-600 rounded-lg shadow-input focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none bg-white dark:bg-gray-700 text-secondary-900 dark:text-gray-200 text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-card hover:shadow-lg transition-all">
                    <div class="flex items-center mb-4 md:mb-5">
                        <div class="rounded-full bg-primary-100 dark:bg-primary-900/30 p-2 mr-3">
                            <i class="fas fa-shopping-basket text-primary-600 dark:text-primary-400"></i>
                        </div>
                        <h3 class="text-base md:text-lg font-medium text-secondary-900 dark:text-white">Order Items</h3>
                    </div>

                    <div id="items" class="space-y-3 md:space-y-4">
                        <div class="item bg-gray-50 dark:bg-gray-700 p-3 rounded-lg border border-secondary-200 dark:border-gray-600">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 md:gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-secondary-700 dark:text-gray-300 mb-1">Product</label>
                                    <select name="items[0][product_id]" class="w-full p-2 border border-secondary-300 dark:border-gray-600 rounded-lg shadow-input focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none bg-white dark:bg-gray-700 text-secondary-900 dark:text-gray-200 text-sm" required>
                                        <option value="">Select Product</option>
                                        <?php foreach ($products as $category => $items) { ?>
                                            <optgroup label="<?= htmlspecialchars($category) ?>">
                                                <?php foreach ($items as $product) { ?>
                                                    <option value="<?= $product['ProductID'] ?>">
                                                        <?= htmlspecialchars($product['ProductName']) ?>
                                                    </option>
                                                <?php } ?>
                                            </optgroup>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-secondary-700 dark:text-gray-300 mb-1">Quantity</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-hashtag text-secondary-400 dark:text-gray-500"></i>
                                        </div>
                                        <input type="number" name="items[0][quantity]" class="pl-10 w-full p-2 border border-secondary-300 dark:border-gray-600 rounded-lg shadow-input focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none bg-white dark:bg-gray-700 text-secondary-900 dark:text-gray-200 text-sm" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" onclick="addItem()" class="mt-4 md:mt-5 inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-sm transition-all">
                        <i class="fas fa-plus mr-2"></i> Add Item
                    </button>
                </div>

                <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-card hover:shadow-lg transition-all">
                    <div class="flex items-center mb-4 md:mb-5">
                        <div class="rounded-full bg-primary-100 dark:bg-primary-900/30 p-2 mr-3">
                            <i class="fas fa-credit-card text-primary-600 dark:text-primary-400"></i>
                        </div>
                        <h3 class="text-base md:text-lg font-medium text-secondary-900 dark:text-white">Payment Method</h3>
                    </div>

                    <div class="space-y-3">
                        <label class="block text-sm font-medium text-secondary-700 dark:text-gray-300 mb-1">Select Payment Method</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 md:gap-4">
                            <label class="payment-option relative">
                                <input type="radio" name="payment_method" value="cash" class="absolute opacity-0 h-0 w-0" required>
                                <div class="cursor-pointer border border-secondary-200 dark:border-gray-600 hover:border-primary-500 rounded-lg p-3 flex items-center transition-all payment-card">
                                    <div class="mr-3 text-xl text-secondary-500 dark:text-gray-400"><i class="fas fa-money-bill-wave"></i></div>
                                    <span class="font-medium text-sm">Cash</span>
                                </div>
                            </label>

                            <label class="payment-option relative">
                                <input type="radio" name="payment_method" value="credit_card" class="absolute opacity-0 h-0 w-0" required>
                                <div class="cursor-pointer border border-secondary-200 dark:border-gray-600 hover:border-primary-500 rounded-lg p-3 flex items-center transition-all payment-card">
                                    <div class="mr-3 text-xl text-secondary-500 dark:text-gray-400"><i class="fas fa-credit-card"></i></div>
                                    <span class="font-medium text-sm">Credit Card</span>
                                </div>
                            </label>

                            <label class="payment-option relative">
                                <input type="radio" name="payment_method" value="debit_card" class="absolute opacity-0 h-0 w-0" required>
                                <div class="cursor-pointer border border-secondary-200 dark:border-gray-600 hover:border-primary-500 rounded-lg p-3 flex items-center transition-all payment-card">
                                    <div class="mr-3 text-xl text-secondary-500 dark:text-gray-400"><i class="fas fa-credit-card"></i></div>
                                    <span class="font-medium text-sm">Debit Card</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-base font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-md transition-all w-full md:w-auto">
                        <i class="fas fa-shopping-cart mr-2"></i> Process Order
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<script>
    // JavaScript for dynamic item addition with product dropdowns
    let itemCount = 1;
    function addItem() {
        const container = document.getElementById('items');
        const div = document.createElement('div');
        div.className = 'item bg-gray-50 dark:bg-gray-700 p-3 rounded-lg border border-secondary-200 dark:border-gray-600';
        div.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 md:gap-4">
                <div>
                    <label class="block text-sm font-medium text-secondary-700 dark:text-gray-300 mb-1">Product</label>
                    <select name="items[${itemCount}][product_id]" class="w-full p-2 border border-secondary-300 dark:border-gray-600 rounded-lg shadow-input focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none bg-white dark:bg-gray-700 text-secondary-900 dark:text-gray-200 text-sm" required>
                        <option value="">Select Product</option>
                        <?php foreach ($products as $category => $items): ?>
                        <optgroup label="<?= htmlspecialchars($category) ?>">
                            <?php foreach ($items as $product): ?>
                            <option value="<?= $product['ProductID'] ?>">
                                <?= htmlspecialchars($product['ProductName']) ?>
                            </option>
                            <?php endforeach; ?>
                        </optgroup>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-secondary-700 dark:text-gray-300 mb-1">Quantity</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-hashtag text-secondary-400 dark:text-gray-500"></i>
                        </div>
                        <input type="number" name="items[${itemCount}][quantity]" class="pl-10 w-full p-2 border border-secondary-300 dark:border-gray-600 rounded-lg shadow-input focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none bg-white dark:bg-gray-700 text-secondary-900 dark:text-gray-200 text-sm" required>
                    </div>
                </div>
            </div>
            <button type="button" onclick="removeItem(this)" class="mt-2 text-red-600 hover:text-red-800 text-sm font-medium">
                <i class="fas fa-trash-alt"></i> Remove
            </button>
        `;
        container.appendChild(div);
        itemCount++;
    }

    function removeItem(button) {
        button.parentElement.remove();
    }
</script>

</body>
</html>
