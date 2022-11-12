<?php
require './vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use \PhpOffice\PhpSpreadsheet\IOFactory ;

$fileName = './老年大学教务平台服务端.xlsx';
$spreadsheet = IOFactory::load($fileName);
//$spreadsheet = IOFactory::createReaderForFile($fileName);
$worksheet = $spreadsheet->getActiveSheet();
echo '<table  border="1">' . PHP_EOL;
$array = [];
foreach ($worksheet->getRowIterator()  as $k=>$v){
    echo '<tr>' . PHP_EOL;
    $cellIterator = $v->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
    //    even if a cell value is not set.
    // For 'TRUE', we loop through cells
    //    only when their value is set.
    // If this method is not called,
    //    the default value is 'false'.
//    var_dump($cellIterator);die();
    foreach ($cellIterator as $cell) {
//        echo '<td>' . $cell->getValue() . '</td>' . PHP_EOL;
//        echo '<td>' . $cell->getRow() . '</td>' . PHP_EOL;
        echo '<td>' . $cell->getCoordinate() . '</td>' . PHP_EOL;
//        echo '<td>' . $cell->getColumn() . '</td>' . PHP_EOL;
//        echo '<td>' . var_dump($cell->getFormattedValue()) . '</td>' . PHP_EOL;
//        die();
    }
//    die();
    echo '</tr>' . PHP_EOL;
}
echo '</table>' . PHP_EOL;
//var_export($spreadsheet->createReaderForFile());
//foreach ($spreadsheet as $k =>$v){
//    var_dump($k,$v);
//}
//echo '<pre>';
// print_r ($spreadsheet);
//echo '</pre>';

