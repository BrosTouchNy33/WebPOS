<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

// === Telegram Bot Config ===
$botToken = "8137492627:AAGO4hPv_HzQAnUcknQY1AaZ7PNhucTNBdE";
$chatId   = "-4574040232```php
<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

// === Telegram Bot Config ===
$botToken = "8137492627:AAGO4hPv_HzQAnUcknQY1AaZ7PNhucTNBdE";
$chatId = "-4574040232"; // Confirmed correct group ID


// === Database Connection ===
$conn = new mysqli("localhost", "root", "root", "pos_mart");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// === Fetch Product Data ===
$sql = "SELECT p.ProductID, p.ProductName, p.Price, p.StockQuantity, p.add_date, c.CategoryName 
        FROM Products p 
        INNER JOIN Categories c ON p.CategoryID = c.CategoryID";
$result = $conn->query($sql);

// === Build HTML for PDF ===
$html = "<h2 style='text-align:center;'>All Products Report</h2>";
$html .= "<table border='1' cellpadding='8' cellspacing='0' width='100%'>";
$html .= "<thead>
<tr>
    <th>ID</th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Add Date</th>
</tr>
</thead><tbody>";

while ($row = $result->fetch_assoc()) {
    $html .= "<tr>
        <td>{$row['ProductID']}</td>
        <td>{$row['ProductName']}</td>
        <td>{$row['CategoryName']}</td>
        <td>$" . number_format($row['Price'], 2) . "</td>
        <td>{$row['StockQuantity']}</td>
        <td>{$row['add_date']}</td>
    </tr>";
}
$html .= "</tbody></table>";

// === Generate PDF ===
$options = new Options();
$options->set('defaultFont', 'DejaVu Sans');
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

// === Save PDF Locally ===
$filename = "products_export.pdf";
$pdfPath = __DIR__ . "/$filename";
file_put_contents($pdfPath, $dompdf->output());

// === Send PDF to Telegram Group ===
$ch = curl_init("https://api.telegram.org/bot$botToken/sendDocument");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, [
    'chat_id' => $chatId,
    'document' => new CURLFile($pdfPath),
    'caption' => "📄 Product List Exported from POS Mart"
]);
$telegramResponse = curl_exec($ch);
curl_close($ch);

// Optional: debug if Telegram fails
if (!$telegramResponse || strpos($telegramResponse, '"ok":false') !== false) {
    file_put_contents(__DIR__ . "/telegram_error.log", $telegramResponse);
}

// === Force PDF Download in Browser ===
header("Content-Type: application/pdf");
header("Content-Disposition: attachment; filename=$filename");
readfile($pdfPath);

// === Clean up local file ===
unlink($pdfPath);
exit;
```"; // ✅ Confirmed correct group ID

// === Database Connection ===
$conn = new mysqli("localhost", "root", "root", "pos_mart");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// === Fetch Product Data ===
$sql = "SELECT p.ProductID, p.ProductName, p.Price, p.StockQuantity, p.add_date, c.CategoryName 
        FROM Products p 
        INNER JOIN Categories c ON p.CategoryID = c.CategoryID";
$result = $conn->query($sql);

// === Build HTML for PDF ===
$html = "<h2 style='text-align:center;'>All Products Report</h2>";
$html .= "<table border='1' cellpadding='8' cellspacing='0' width='100%'>";
$html .= "<thead>
<tr>
    <th>ID</th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Add Date</th>
</tr>
</thead><tbody>";

while ($row = $result->fetch_assoc()) {
    $html .= "<tr>
        <td>{$row['ProductID']}</td>
        <td>{$row['ProductName']}</td>
        <td>{$row['CategoryName']}</td>
        <td>$" . number_format($row['Price'], 2) . "</td>
        <td>{$row['StockQuantity']}</td>
        <td>{$row['add_date']}</td>
    </tr>";
}
$html .= "</tbody></table>";

// === Generate PDF ===
$options = new Options();
$options->set('defaultFont', 'DejaVu Sans');
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

// === Save PDF Locally ===
$filename = "products_export.pdf";
$pdfPath = __DIR__ . "/$filename";
file_put_contents($pdfPath, $dompdf->output());

// === Send PDF to Telegram Group ===
$ch = curl_init("https://api.telegram.org/bot$botToken/sendDocument");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, [
    'chat_id' => $chatId,
    'document' => new CURLFile($pdfPath),
    'caption' => "📄 Product List Exported from POS Mart"
]);
$telegramResponse = curl_exec($ch);
curl_close($ch);

// Optional: debug if Telegram fails
if (!$telegramResponse || strpos($telegramResponse, '"ok":false') !== false) {
    file_put_contents(__DIR__ . "/telegram_error.log", $telegramResponse);
}

// === Force PDF Download in Browser ===
header("Content-Type: application/pdf");
header("Content-Disposition: attachment; filename=$filename");
readfile($pdfPath);

// === Clean up local file ===
unlink($pdfPath);
exit;
