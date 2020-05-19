<?php

include "./Classes/PHPExcel.php";

$phpExcel = new PHPExcel();

$phpExcel->setActiveSheetIndex()
    ->setCellValue();


$filename = iconv("UTF-8", "EUC-KR", "트와이스_TWICE");

header("Content-Type:application/vnd.ms-excel");
header("Content-Disposition: attachment;filename=".$filename.".xls");
header("Cache-Control:max-age=0");

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel5");
$objWriter -> save("php://output");