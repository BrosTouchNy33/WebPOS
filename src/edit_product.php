<?php
// edit_product.php

require_once '../db/connection_db.php'; // Include database connection

session_start();

// Check if the user is logged in and has admin role (adjust as needed)
if (!isset($_SESSION['UserID']) || !isset($_SESSION['Username']) || $_SESSION['Role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['productID']) && is_numeric($_POST['productID'])) {
        $productID = $_POST['productID'];
        $productName = $_POST['productName'];
        $price = $_POST['price'];
        $stockQuantity = $_POST['stockQuantity'];
        $categoryName = $_POST['categoryname'];


        $stmt = $conn->prepare("UPDATE Products SET ProductName = ?, Price = ?, StockQuantity = ?, CategoryID = (SELECT CategoryID FROM Categories WHERE CategoryName = ?) WHERE ProductID = ?");
        $stmt->bind_param("sdisi", $productName, $price, $stockQuantity, $categoryName, $productID); // Corrected number of parameters

        if ($stmt->execute()) {
            header("Location: view_product.php?update=success"); // Redirect with success message
            exit();
        } else {
            header("Location: view_product.php?update=error"); // Redirect with error message
            exit();
        }

        $stmt->close();
    } else {
        // Handle invalid ProductID
        header("Location: view_product.php?error=invalid_id");
        exit();
    }
} else {
    // If accessed directly without submitting the form
    header("Location: view_product.php");
    exit();
}

$conn->close();
?>