<?php
namespace src\controller;

use PhpOffice\PhpSpreadsheet\IOFactory ;

class Excel {
    function getExcelData ($fileName) :array{
        $spreadsheet = IOFactory::load($fileName);
        $worksheet = $spreadsheet->getActiveSheet();
//        echo '<table  border="1">' . PHP_EOL;
        $rawArr = [];
        foreach ($worksheet->getRowIterator()  as $v){
//            echo '<tr>' . PHP_EOL;
            $cellIterator = $v->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(FALSE);
            $raw = [];
            foreach ($cellIterator as $cell) {
                $mergeRange =  $cell->getMergeRange(); // A1:A4
                $column = $cell->getColumn();
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
//        echo '<td>' . $cell->getRow() . '</td>' . PHP_EOL;
//        echo '<td>' . $cell->getCoordinate() . '</td>' . PHP_EOL;
//        echo '<td>' . $cell->getColumn() . '</td>' . PHP_EOL;
//        echo '<td>' . var_dump($cell->getFormattedValue()) . '</td>' . PHP_EOL;
            }
            $raw &&  $rawArr[] = $raw;
//            echo '</tr>' . PHP_EOL;
        }

        return $rawArr;
//        echo '</table>' . PHP_EOL;
//        file_put_contents('./json.json',json_encode($rawArr,JSON_UNESCAPED_UNICODE));
    }
}





