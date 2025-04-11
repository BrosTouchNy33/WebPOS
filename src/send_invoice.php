<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

// === Telegram Bot Config ===
$botToken = "8137492627:AAGO4hPv_HzQAnUcknQY1AaZ7PNhucTNBdE";
$chatId = "-4574040232";  // Replace with your actual chat ID

// === Validate product ID ===
$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    http_response_code(400);
    die("❌ Missing or invalid product ID.");
}

// === DB Connection ===
$conn = new mysqli("localhost", "root", "root", "pos_mart");
if ($conn->connect_error) {
    http_response_code(500);
    die("❌ Database connection failed.");
}

// === Get product info ===
$sql = "SELECT p.ProductName, p.Price, p.StockQuantity, p.add_date, c.CategoryName
        FROM Products p
        JOIN Categories c ON p.CategoryID = c.CategoryID
        WHERE p.ProductID = $id";
$result = $conn->query($sql);
if ($result->num_rows === 0) {
    http_response_code(404);
    die("❌ Product not found.");
}
$row = $result->fetch_assoc();

// === Generate Invoice HTML ===
$html = "
<h2 style='text-align:center;'>🧾 Product Invoice</h2>
<table border='0' cellpadding='8' style='font-size:14px;'>
<tr><td><strong>Name:</strong></td><td>{$row['ProductName']}</td></tr>
<tr><td><strong>Category:</strong></td><td>{$row['CategoryName']}</td></tr>
<tr><td><strong>Price:</strong></td><td>$" . number_format($row['Price'], 2) . "</td></tr>
<tr><td><strong>Stock:</strong></td><td>{$row['StockQuantity']}</td></tr>
<tr><td><strong>Date Added:</strong></td><td>{$row['add_date']}</td></tr>
</table>
<p style='text-align:center;'>Thank you for using POS Mart!</p>
";

// === Generate PDF ===
$options = new Options();
$options->set('defaultFont', 'DejaVu Sans');
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// === Save PDF ===
$pdfPath = __DIR__ . "/invoice_{$id}.pdf";
file_put_contents($pdfPath, $dompdf->output());

if (!file_exists($pdfPath)) {
    http_response_code(500);
    die("❌ Failed to generate PDF.");
}

// === Send to Telegram ===
$ch = curl_init("https://api.telegram.org/bot$botToken/sendDocument");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, [
    'chat_id' => $chatId,
    'document' => new CURLFile($pdfPath),
    'caption' => "📄 Invoice for: {$row['ProductName']}"
]);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// === Cleanup & Response ===
unlink($pdfPath);

if ($httpCode === 200) {
    echo "✅ Invoice sent to Telegram!";
} else {
    http_response_code(500);
    echo "❌ Failed to send to Telegram.\n$response";
}
