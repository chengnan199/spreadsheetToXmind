<?php
require './vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use \PhpOffice\PhpSpreadsheet\IOFactory ;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

$fileName = './老年大学教务平台服务端.xlsx';
$spreadsheet = IOFactory::load($fileName);
//$spreadsheet = IOFactory::createReaderForFile($fileName);
$worksheet = $spreadsheet->getActiveSheet();
echo '<table  border="1">' . PHP_EOL;
$rawArr = [];
foreach ($worksheet->getRowIterator()  as $k=>$v){
    echo '<tr>' . PHP_EOL;
    $cellIterator = $v->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(FALSE);
    $raw = [];

    foreach ($cellIterator as $cell) {

        $mergeRange =  $cell->getMergeRange(); // A1:A4
        $column = $cell->getColumn();
        $columnIndex =  Coordinate::columnIndexFromString($column);
//        $arr[]
        if ($cell->getColumn() == 'A' || $cell->getRow() == '1'){
            continue ;
        }
        $raw[] = [
            'value'=>$cell->getValue(),
            'mergeRange'=>$mergeRange,
            'column'=>$column,
            'row'=>$cell->getRow(),
            'coordinate'=>$cell->getCoordinate(),
        ];
//        echo '<td>' . $cell->getValue(). '-'. $mergeRange. '</td>' . PHP_EOL;
        echo '<td>' . $cell->getRow() . '</td>' . PHP_EOL;
//        echo '<td>' . $cell->getCoordinate() . '</td>' . PHP_EOL;
//        echo '<td>' . $cell->getColumn() . '</td>' . PHP_EOL;
//        echo '<td>' . var_dump($cell->getFormattedValue()) . '</td>' . PHP_EOL;
//        die();
    }
    $raw &&  $rawArr[] = $raw;
//    die();
    echo '</tr>' . PHP_EOL;
}

function tree ($rawArr){
        foreach ($rawArr as $k=>$v){
            foreach ($v as  $kk=>$vv){

            }
        }
        return ;
}
echo '</table>' . PHP_EOL;
file_put_contents('./json.json',json_encode($rawArr,JSON_UNESCAPED_UNICODE));
var_export(json_encode($rawArr,JSON_UNESCAPED_UNICODE));
//var_export($spreadsheet->createReaderForFile());
//foreach ($spreadsheet as $k =>$v){
//    var_dump($k,$v);
//}
//echo '<pre>';
// print_r ($spreadsheet);
//echo '</pre>';




