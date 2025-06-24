<?php
require '../vendor/TCPDF-main/tcpdf.php';
include '../components/connect.php';

$pdf = new TCPDF();
$pdf->AddPage();

// PDF Content
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Sales Report', 0, 1, 'C');

$pdf->SetFont('helvetica', '', 12);

$html = '<table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Date</th>
            </tr>';

// Filter values
$from_date = htmlspecialchars($_GET['from_date'] ?? '');
$to_date = htmlspecialchars($_GET['to_date'] ?? '');

$query = "SELECT * FROM sales WHERE date BETWEEN :from_date AND :to_date";
$stmt = $conn->prepare($query);
$stmt->execute([':from_date' => $from_date, ':to_date' => $to_date]);
$salesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($salesData as $data) {
    $html .= "<tr>
                <td>{$data['order_id']}</td>
                <td>{$data['customer_name']}</td>
                <td>{$data['product_name']}</td>
                <td>{$data['quantity']}</td>
                <td>{$data['total_price']}</td>
                <td>{$data['date']}</td>
              </tr>";
}

$html .= '</table>';

$pdf->writeHTML($html, true, false, true, false, '');

try {
    $pdf->Output('Sales_Report.pdf', 'D');
} catch (Exception $e) {
    echo "Error generating PDF report: " . $e->getMessage();
}
exit;
?>
