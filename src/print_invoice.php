<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$id = $_GET['id'] ?? null;
if (!$id) die('Product ID required.');

$conn = new mysqli("localhost", "root", "root", "pos_mart");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$sql = "SELECT p.ProductName, p.Price, p.StockQuantity, p.add_date, c.CategoryName
        FROM Products p
        JOIN Categories c ON p.CategoryID = c.CategoryID
        WHERE p.ProductID = $id";

$result = $conn->query($sql);
if ($result->num_rows === 0) die("Product not found.");

$row = $result->fetch_assoc();

// Create HTML
$html = "
    <h2 style='text-align:center;'>Product Invoice</h2>
    <table border='0' cellpadding='8'>
        <tr><td><strong>Name:</strong></td><td>{$row['ProductName']}</td></tr>
        <tr><td><strong>Category:</strong></td><td>{$row['CategoryName']}</td></tr>
        <tr><td><strong>Price:</strong></td><td>$" . number_format($row['Price'], 2) . "</td></tr>
        <tr><td><strong>Stock:</strong></td><td>{$row['StockQuantity']}</td></tr>
        <tr><td><strong>Date Added:</strong></td><td>{$row['add_date']}</td></tr>
    </table>
    <p style='text-align:center;'>Thank you for using POS Mart</p>
";

// Generate PDF
$options = new Options();
$options->set('defaultFont', 'DejaVu Sans');
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Save PDF to a temp file
$pdfPath = __DIR__ . "/product_invoice_{$id}.pdf";
file_put_contents($pdfPath, $dompdf->output());


// ✅ Send to Telegram
$botToken = "8137492627:AAGO4hPv_HzQAnUcknQY1AaZ7PNhucTNBdE";
$chatId = "1108054392"; // Replace with your actual chat ID

$ch = curl_init("https://api.telegram.org/bot$botToken/sendDocument");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, [
    'chat_id' => $chatId,
    'document' => new CURLFile($pdfPath),
    'caption' => "🧾 Invoice for: {$row['ProductName']}"
]);
$response = curl_exec($ch);
curl_close($ch);

// Clean up temp file
unlink($pdfPath);

// Optional: show success message
echo "Invoice sent to Telegram!";
