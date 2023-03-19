<?php 
// --
require __DIR__ . '/../../src/core/app.php';
// --
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


$reader = IOFactory::createReader('Xls');
$spreadsheet = $reader->load(__DIR__ . '/../../templates/template_products.xls');

 // --
$sql = 'SELECT 
    p.id as "id_product", 
    p.name as "product", 
    p.description as "product_description", 
    p.price, c.id as "id_category", 
    c.name as "category", 
    c.description as "category_description"
FROM product p
INNER JOIN category c on p.id_category = c.id
WHERE p.status = 1
';
// --
$result = $pdo->fetchAll($sql); 
$data_array = array();
foreach ($result as $item) {
    // --
    $data_array[] = array($item['id_product'], $item['product'], $item['product_description']);
}


$spreadsheet->getActiveSheet()->fromArray($data_array, null, 'A4');
// --
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="TuPapi.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');
// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0
// --
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;

?>