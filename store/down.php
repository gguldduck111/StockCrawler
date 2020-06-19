<?php

include "./Classes/PHPExcel.php";
date_default_timezone_set("Asia/Seoul");
setlocale(LC_CTYPE, 'ko_KR.UTF-8'); // CSV 한글 깨짐 문제
$orderNo = $_POST['orderNo'];
$itemName = $_POST['itemName'];
$count = $_POST['count'];
$recipient = $_POST['recipient'];
$postCode = $_POST['postCode'];
$addr = $_POST['addr'];
$phone1 = $_POST['phone1'];
$phone2 = $_POST['phone2'];
$msg = $_POST['msg'];

$phpExcel = new PHPExcel();


$phpExcel -> getActiveSheet() -> getColumnDimension("A") -> setWidth(12);
$phpExcel -> getActiveSheet() -> getColumnDimension("B") -> setWidth(42);
$phpExcel -> getActiveSheet() -> getColumnDimension("C") -> setWidth(2);
$phpExcel -> getActiveSheet() -> getColumnDimension("D") -> setWidth(5);
$phpExcel -> getActiveSheet() -> getColumnDimension("E") -> setWidth(7);
$phpExcel -> getActiveSheet() -> getColumnDimension("F") -> setWidth(9);
$phpExcel -> getActiveSheet() -> getColumnDimension("G") -> setWidth(90);
$phpExcel -> getActiveSheet() -> getColumnDimension("H") -> setWidth(19);
$phpExcel -> getActiveSheet() -> getColumnDimension("I") -> setWidth(19);
$phpExcel -> getActiveSheet() -> getColumnDimension("J") -> setWidth(50);
$phpExcel -> getActiveSheet() -> getColumnDimension("K") -> setWidth(10);
$phpExcel -> getActiveSheet() -> getColumnDimension("L") -> setWidth(10);


$phpExcel->setActiveSheetIndex(0)
    ->setCellValue('A1','주문번호')
    ->setCellValue('B1','주문상품명')
    ->setCellValue('D1','수량')
    ->setCellValue('E1','수령인')
    ->setCellValue('F1','우편번호')
    ->setCellValue('G1','주소')
    ->setCellValue('H1','전화번호')
    ->setCellValue('I1','핸드폰')
    ->setCellValue('J1','비고')
    ->setCellValue('K1','')
    ->setCellValue('L1','');


$s = 2;
foreach ($orderNo as $i => $item){

    $phpExcel->setActiveSheetIndex(0)
        ->setCellValue(sprintf('A%s',$s),$item)
        ->setCellValue(sprintf('B%s',$s),$itemName[$i])
        ->setCellValue(sprintf('D%s',$s),$count[$i])
        ->setCellValue(sprintf('E%s',$s),$recipient[$i])
        ->setCellValue(sprintf('F%s',$s),$postCode[$i])
        ->setCellValue(sprintf('G%s',$s),$addr[$i])
        ->setCellValue(sprintf('H%s',$s),$phone1[$i])
        ->setCellValue(sprintf('I%s',$s),$phone2[$i])
        ->setCellValue(sprintf('J%s',$s),$msg[$i]);
    $s++;
}

$phpExcel->setActiveSheetIndex(0);
$tmpName = date('Ymd');
$filename = iconv("UTF-8", "EUC-KR", $tmpName.'-발주');

header("Content-Type:application/vnd.ms-excel");
header("Content-Disposition: attachment;filename=".$filename.".xls");
header("Cache-Control:max-age=0");
header("Content-Type:text/html;charset=utf-8");

$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
$objWriter -> save("php://output");