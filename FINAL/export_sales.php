<?php
include 'database.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="sales_report.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Sale ID', 'Total Price', 'Sale Date']);

$sales = $pdo->query("SELECT id, total_price, sale_date FROM sales")->fetchAll();
foreach ($sales as $sale) {
    fputcsv($output, $sale);
}
fclose($output);
?>
