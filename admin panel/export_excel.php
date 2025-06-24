<?php
require '../Composer/vendor/autoload.php'; 

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include '../components/connect.php';

// Check if download_excel is set
if (!isset($_GET['download_excel'])) {
    echo "Invalid request!";
    exit;
}

// Filter values
$from_date = isset($_GET['from_date']) ? htmlspecialchars($_GET['from_date']) : '';
$to_date = isset($_GET['to_date']) ? htmlspecialchars($_GET['to_date']) : '';
$month = isset($_GET['month']) ? htmlspecialchars($_GET['month']) : '';
$year = isset($_GET['year']) ? htmlspecialchars($_GET['year']) : '';

// Data Fetching Logic
$query = "SELECT id, name, product_id, qty, price, dates FROM orders WHERE 1=1";

$params = [];
if (!empty($from_date) && !empty($to_date)) {
    $query .= " AND DATE(dates) BETWEEN :from_date AND :to_date";
    $params[':from_date'] = $from_date;
    $params[':to_date'] = $to_date;
}

if (!empty($month)) {
    $query .= " AND MONTH(dates) = :month";
    $params[':month'] = $month;
}

if (!empty($year)) {
    $query .= " AND YEAR(dates) = :year";
    $params[':year'] = $year;
}

$stmt = $conn->prepare($query);
$stmt->execute($params);
$orderData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Excel File Creation
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Headers
$sheet->setCellValue('A1', 'Order ID')
      ->setCellValue('B1', 'Customer Name')
      ->setCellValue('C1', 'Product ID')
      ->setCellValue('D1', 'Quantity')
      ->setCellValue('E1', 'Total Price')
      ->setCellValue('F1', 'Order Date');

// Data Rows
$row = 2;
foreach ($orderData as $data) {
    $total_price = (float)$data['price'] * (int)$data['qty'];
    $sheet->setCellValue("A{$row}", $data['id'])
          ->setCellValue("B{$row}", $data['name'])
          ->setCellValue("C{$row}", $data['product_id'])
          ->setCellValue("D{$row}", $data['qty'])
          ->setCellValue("E{$row}", $total_price) 
          ->setCellValue("F{$row}", $data['dates']);
    $row++;
}

// File Output
$filename = "Orders_Report_" . date('Y-m-d') . ".xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);

try {
    $writer->save('php://output');
} catch (Exception $e) {
    echo "Error generating Excel report: " . $e->getMessage();
}
exit;
?>
