<?php
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\IOFactory;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$request=Request::find($filters['request_id']);
$valuations=$request->valuations()->where('have_changed','!=','без изменений')->get();

$reader = IOFactory::createReader("Xlsx");
$spreadsheet = $reader->load($path['xsd'].'act.xlsx');
$sheet=$spreadsheet->getActiveSheet();
$sheet->setCellValue('A1','Акт определения кадастровой стоимости объектов недвижимости  № '.$request->number);
$sheet->setCellValue('A2','от '.date("d.m.Y"));

$baseRow = 4;
$r=1;
$row=$baseRow;
foreach ($valuations as $valuation) {
    
    $row = $baseRow + $r;
    $sheet->insertNewRowBefore($row,1);
    // $sheet->duplicateStyle($sheet->getStyle('A'.($row+1)),'A'.$row.':H'.$row);

    $sheet->setCellValue('A'.$row, $r)
                                  ->setCellValue('B'.$row, $valuation->cadastral_number)
                                  ->setCellValue('C'.$row, 'Статья 16 Федерального закона от 03.07.2016 N 237-ФЗ О государственной кадастровой оценке')
                                  ->setCellValue('D'.$row, $valuation->request->name)
                                  ->setCellValue('E'.$row, $valuation->usage)
                                  ->setCellValue('F'.$row, $valuation->method)
                                  ->setCellValue('G'.$row, 'Отчет №01/КС ОН/2018 об итогах государственной кадастровой оценки объектов недвижимости (за исключением земельных участков) на территории Пермского края. Пункт 1.8 страница 16')
                                  ->setCellValue('H'.$row, $valuation->cadastral_cost)
                                   ;
    // устанавливаем авто подбор высоты 
    $sheet->getRowDimension($row)->setRowHeight(-1);

    // и авто перенос текста
    $sheet->getStyle('A'.$row.':H'.$row)->getAlignment()->setWrapText(true);

    $sheet->getRowDimension($row)->setRowHeight(100);
    $r++;
    
        
}

$sheet->removeRow($row+1,1);
$sheet->getRowDimension($row+1)->setRowHeight(-1);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Акт определения КС N'.$request->number.' от '.date("d.m.Y").'.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, "Xlsx");
$writer->save('php://output');