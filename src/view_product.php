<?php
// view_products.php

require_once '../db/connection_db.php'; // Include database connection
// require_once '../vendor/autoload.php';


session_start();

// Check if the user is logged in (adjust this check if needed)
if (!isset($_SESSION['UserID']) || !isset($_SESSION['Username'])) {
    header("Location: ../index.php");
    exit();
}

// Fetch all products from the database
$sql_products = "SELECT p.ProductID, p.ProductName, p.product_image, p.Price, p.StockQuantity, p.add_date, c.CategoryName FROM Products p INNER JOIN Categories c ON p.CategoryID = c.CategoryID";
$result_products = $conn->query($sql_products);
$products = $result_products->fetch_all(MYSQLI_ASSOC);

$sql_categories = "SELECT CategoryID, CategoryName FROM Categories";
$result_categories = $conn->query($sql_categories);
$categories = $result_categories->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }
  
@media print {
  body * {
    visibility: hidden !important;
  }
  #print-modal,
  #print-modal * {
    visibility: visible !important;
  }
  #print-modal {
    position: absolute !important;
    top: 0;
    left: 0;
    width: 100% !important;
    background: white !important;
    z-index: 9999 !important;
  }
}
</style>

</style>

    </style>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>
<body class="bg-gray-50 dark:bg-gray-900 min-h-screen flex">


<?php include 'sidebar.php' ?>

<div class="flex-1 ml-0 md:ml-64 transition-all duration-300 ease-in-out">
    <div class="p-6 bg-gray-50 dark:bg-gray-900">
        <div class="flex justify-between items-center mb-8">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Available Products</h3>
            <a href="add_product.php" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-5 rounded-lg flex items-center gap-2 transition duration-300 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>Add Product</span>
               
            </a>
        </div>

        <?php if (!empty($products)): ?>
            <div class="overflow-x-auto rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
                <table class="min-w-full bg-white dark:bg-gray-800">
                    <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                        <th class="py-3 px-4 md:py-4 md:px-6 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Product Name</th>
                        <th class="py-3 px-4 md:py-4 md:px-6 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Image</th>
                        <th class="py-3 px-4 md:py-4 md:px-6 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Price</th>
                        <th class="py-3 px-4 md:py-4 md:px-6 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Stock</th>
                        <th class="py-3 px-4 md:py-4 md:px-6 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Category</th>
                        <th class="py-3 px-4 md:py-4 md:px-6 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Add_Date</th>
                        <th class="py-3 px-4 md:py-4 md:px-6 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <?php foreach ($products as $product): ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                            <td class="py-3 px-4 md:py-4 md:px-6 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white"><?php echo htmlspecialchars($product['ProductName']); ?></td>
                            <td class="py-3 px-4 md:py-4 md:px-6 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                <img src="<?php echo htmlspecialchars($product['product_image']); ?>" alt="<?php echo htmlspecialchars($product['ProductName']); ?>" class="h-12 sm:h-16 w-auto max-w-full object-cover rounded-md shadow-md border border-gray-200 dark:border-gray-700 transition-transform duration-200 hover:scale-105">
                            </td>
                            <td class="py-3 px-4 md:py-4 md:px-6 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">$<?php echo htmlspecialchars(number_format($product['Price'], 2)); ?></td>
                            <td class="py-3 px-4 md:py-4 md:px-6 whitespace-nowrap">
                                <span class="px-2 py-1 md:px-3 md:py-1 inline-flex text-xs md:text-sm leading-5 font-medium rounded-full <?php echo ($product['StockQuantity'] > 10) ? 'bg-green-100 text-green-800 dark:bg-green-600 dark:text-green-100' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-600 dark:text-yellow-100'; ?>">
                                    <?php echo htmlspecialchars($product['StockQuantity']); ?>
                                </span>
                            </td>
                            <td class="py-3 px-4 md:py-4 md:px-6 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white"><?php echo htmlspecialchars($product['CategoryName']); ?></td>
                            <td class="py-3 px-4 md:py-4 md:px-6 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white"><?php echo htmlspecialchars($product['add_date']); ?></td>
                            <td class="py-3 px-4 md:py-4 md:px-6 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2 md:space-x-3">
                                    <button data-product-id="<?php echo $product['ProductID']; ?>" class="text-indigo-600 hover:text-indigo-900 focus:outline-none focus:underline edit-btn">
                                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button class="text-red-600 hover:text-red-900 focus:outline-none focus:underline delete-btn" data-product-id="<?php echo $product['ProductID']; ?>">
                                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                    <button class="text-gray-600 hover:text-blue-500 print-btn" data-product-id="<?= $product['ProductID'] ?>" title="Print">
                                        <i class="fa-solid fa-print"></i>
                                    </button>


                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
    </div>
    <?php endif; ?>
    </div>

        <!-- Print Modal -->
<!-- Invoice Print Modal -->
<div id="print-modal" class="fixed inset-0 z-50 bg-black bg-opacity-50 hidden">
  <div class="flex items-center justify-center min-h-screen px-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-md p-6 print:bg-white">
      <!-- Header -->
      <div class="flex justify-between items-center border-b pb-4 mb-4">
        <div>
          <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Invoice</h2>
          <p class="text-sm text-gray-500 dark:text-gray-300">POS Mart</p>
        </div>
        <div class="text-sm text-right text-gray-600 dark:text-gray-300">
          <p id="invoice-date"></p>
          <p>Invoice #: <span id="invoice-id" class="font-semibold"></span></p>
        </div>
      </div>

      <!-- Invoice Body -->
      <div class="space-y-3 text-gray-700 dark:text-gray-100 text-sm">
        <div class="flex justify-between">
          <span class="font-medium">Product Name:</span>
          <span id="invoice-name"></span>
        </div>
        <div class="flex justify-between">
          <span class="font-medium">Category:</span>
          <span id="invoice-category"></span>
        </div>
        <div class="flex justify-between">
          <span class="font-medium">Price:</span>
          <span>$<span id="invoice-price"></span></span>
        </div>
        <div class="flex justify-between">
          <span class="font-medium">Stock Quantity:</span>
          <span id="invoice-stock"></span>
        </div>
        <div class="flex justify-between">
          <span class="font-medium">Added Date:</span>
          <span id="invoice-date-added"></span>
        </div>
      </div>

      <!-- Footer -->
      <div class="border-t mt-6 pt-4 text-center text-xs text-gray-500 dark:text-gray-400">
        Thank you for choosing POS Mart
      </div>

      <!-- Actions -->
      <div class="mt-6 flex justify-end space-x-3">
        <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-lg shadow">
          <i class="fas fa-print mr-1"></i> Print
          <button id="send-telegram" class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg shadow">
             <i class="fa-brands fa-telegram mr-1"></i> Send to Telegram
         </button>

        </button>
        <button id="print-cancel" class="bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm px-4 py-2 rounded-lg dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
          Cancel
        </button>
      </div>
    </div>
  </div>
</div>






    <div id="delete-modal" class="fixed z-50 inset-0 bg-black bg-opacity-50 overflow-hidden hidden">
        <div class="flex items-center justify-center min-h-screen p-4 w-full">
            <div class="relative w-full max-w-md bg-white dark:bg-gray-800 rounded-lg shadow-md">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Confirm Delete</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">Are you sure you want to delete this product?</p>
                    <div class="flex justify-end gap-4">
                        <button id="delete-cancel" class="bg-gray-300 hover:bg-gray-400 text-gray-700 dark:text-gray-300 font-medium py-2 px-4 rounded focus:outline-none">Cancel</button>
                        <button id="delete-confirm" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded focus:outline-none">Delete</button>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <a href="export_pdf.php" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 px-5 rounded-lg flex items-center gap-2 transition duration-300 shadow-sm">
    <i class="fa-solid fa-file-pdf"></i>
    <span>Export PDF</span>
</a>



    <div id="edit-modal" class="fixed z-50 inset-0 bg-black bg-opacity-50 overflow-hidden hidden">
        <div class="flex items-center justify-center min-h-screen p-4 w-full">
            <div class="relative w-full max-w-md bg-white dark:bg-gray-800 rounded-lg shadow-md">
                <form id="edit-product-form" action="edit_product.php" method="post" class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Edit Product</h3>
                    <input type="hidden" id="edit-product-id" name="productID">
                    <div class="mb-4">
                        <label for="edit-product-name" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Product Name</label>
                        <input type="text" name="productName" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-200 leading-tight focus:outline-none focus:shadow-outline bg-white dark:bg-gray-900" id="edit-product-name">
                    </div>
                    <div class="mb-4">
                        <label for="edit-product-price" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Price</label>
                        <input type="number" name="price" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-200 leading-tight focus:outline-none focus:shadow-outline bg-white dark:bg-gray-900" id="edit-product-price">
                    </div>
                    <div class="mb-4">
                        <label for="edit-product-stock" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Stock Quantity</label>
                        <input type="number" name="stockQuantity" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-200 leading-tight focus:outline-none focus:shadow-outline bg-white dark:bg-gray-900" id="edit-product-stock">
                    </div>

                    <div class="mb-4">
                        <label for="edit-product-category" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Category</label>
                        <select name="categoryname" id="edit-product-category" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-200 leading-tight focus:outline-none focus:shadow-outline bg-white dark:bg-gray-900">
                            <option value="">-- Select Category --</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= htmlspecialchars($category['CategoryName']) ?>">
                                    <?= htmlspecialchars($category['CategoryName']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="flex justify-end gap-4">
                        <button type="button" id="edit-cancel" class="bg-gray-300 hover:bg-gray-400 text-gray-700 dark:text-gray-300 font-medium py-2 px-4 rounded focus:outline-none">Cancel</button>
                        <button type="submit" id="edit-confirm" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded focus:outline-none">Apply Change</button>
                    </div>
                </form>
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
                }
            }
        }
 const printButtons = document.querySelectorAll('.print-btn');
const printModal = document.getElementById('print-modal');
const printCancel = document.getElementById('print-cancel');

const productsData = <?php echo json_encode($products); ?>;

printButtons.forEach(button => {
  button.addEventListener('click', () => {
    const productId = button.dataset.productId;
    const product = productsData.find(p => p.ProductID == productId);

    if (product) {
      document.getElementById('invoice-id').textContent = productId.toString().padStart(4, '0');
      document.getElementById('invoice-name').textContent = product.ProductName;
      document.getElementById('invoice-category').textContent = product.CategoryName;
      document.getElementById('invoice-price').textContent = parseFloat(product.Price).toFixed(2);
      document.getElementById('invoice-stock').textContent = product.StockQuantity;
      document.getElementById('invoice-date-added').textContent = product.add_date;
      document.getElementById('invoice-date').textContent = new Date().toLocaleString();

      printModal.classList.remove('hidden');
    }
  });
});

printCancel.addEventListener('click', () => {
  printModal.classList.add('hidden');
});

document.getElementById("send-telegram").addEventListener("click", () => {
  const productId = document.getElementById("invoice-id").textContent;

  fetch(`send_invoice.php?id=${parseInt(productId)}`)
    .then(res => res.text())
    .then(data => {
      alert("✅ Invoice sent to Telegram!");
      console.log(data);
    })
    .catch(err => {
      alert("❌ Failed to send to Telegram");
      console.error(err);
    });
});



        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.edit-btn');
            const editModal = document.getElementById('edit-modal');
            const editCancelButton = document.getElementById('edit-cancel');
            const editForm = document.getElementById('edit-product-form');
            const editProductIdInput = document.getElementById('edit-product-id');
            const editProductNameInput = document.getElementById('edit-product-name');
            const editProductPriceInput = document.getElementById('edit-product-price');
            const editProductStockInput = document.getElementById('edit-product-stock');
            const editProductCategoryInput = document.getElementById('edit-product-category');
            let productIdToEdit = null;

            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    productIdToEdit = this.dataset.productId;
                    editProductIdInput.value = productIdToEdit; // Set the product ID in the hidden input

                    // Find the product data from your $products array (you might need to adjust this based on how you pass data to JS)
                    const productsData = <?php echo json_encode($products); ?>;
                    const product = productsData.find(p => p.ProductID == productIdToEdit);

                    if (product) {
                        editProductNameInput.value = product.ProductName;
                        editProductPriceInput.value = product.Price;
                        editProductStockInput.value = product.StockQuantity;
                        editProductCategoryInput.value = product.CategoryName || "";
                    }

                    editModal.classList.remove('hidden');
                });
            });

            editCancelButton.addEventListener('click', function() {
                editModal.classList.add('hidden');
                productIdToEdit = null;
            });

            const deleteButtons = document.querySelectorAll('.delete-btn');
            const deleteModal = document.querySelector('#delete-modal');
            const delete_cancel = document.querySelector('#delete-cancel');
            let productIdToDelete = null;

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    productIdToDelete = this.dataset.productId;
                    deleteModal.classList.remove('hidden');
                });
            });

            delete_cancel.addEventListener('click', function() {
                deleteModal.classList.add('hidden');
                productIdToDelete = null;
            });

            const deleteConfirmButton = document.getElementById('delete-confirm');
            if (deleteConfirmButton) {
                deleteConfirmButton.addEventListener('click', function() {
                    if (productIdToDelete) {
                        // You would typically send an AJAX request here to delete the product
                        window.location.href = 'delete_product.php?id=' + productIdToDelete;
                    }
                });
            }
        });
    </script>
</body>
</html>