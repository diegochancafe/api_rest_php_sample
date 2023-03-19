<?php 
// --
require __DIR__ . '/../../src/core/app.php';
// --
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// --
$spreadsheet = new Spreadsheet();
$dataArray = [
    ['2010', 'Q1', 'United States', 790],
    ['2010', 'Q2', 'United States', 730],
    ['2010', 'Q3', 'United States', 860],
    ['2010', 'Q4', 'United States', 850],
    ['2011', 'Q1', 'United States', 800],
    ['2011', 'Q2', 'United States', 700],
    ['2011', 'Q3', 'United States', 900],
    ['2011', 'Q4', 'United States', 950],
    ['2010', 'Q1', 'Belgium', 380],
    ['2010', 'Q2', 'Belgium', 390],
    ['2010', 'Q3', 'Belgium', 420],
    ['2010', 'Q4', 'Belgium', 460],
    ['2011', 'Q1', 'Belgium', 400],
    ['2011', 'Q2', 'Belgium', 350],
    ['2011', 'Q3', 'Belgium', 450],
    ['2011', 'Q4', 'Belgium', 500],
    ['2010', 'Q1', 'UK', 690],
    ['2010', 'Q2', 'UK', 610],
    ['2010', 'Q3', 'UK', 620],
    ['2010', 'Q4', 'UK', 600],
    ['2011', 'Q1', 'UK', 720],
    ['2011', 'Q2', 'UK', 650],
    ['2011', 'Q3', 'UK', 580],
    ['2011', 'Q4', 'UK', 510],
    ['2010', 'Q1', 'France', 510],
    ['2010', 'Q2', 'France', 490],
    ['2010', 'Q3', 'France', 460],
    ['2010', 'Q4', 'France', 590],
    ['2011', 'Q1', 'France', 620],
    ['2011', 'Q2', 'France', 650],
    ['2011', 'Q3', 'France', 415],
    ['2011', 'Q4', 'France', 570],
];

$spreadsheet->getActiveSheet()->fromArray($dataArray, null, 'A2');
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