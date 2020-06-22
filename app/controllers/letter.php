<?php
use \PhpOffice\PhpWord\PhpWord;
use \PhpOffice\PhpWord\TemplateProcessor;

function cloneRowAndSetValues($search, $values)
{
    global $templateProcessor;
    $templateProcessor->cloneRow($search, count($values));

    foreach ($values as $rowKey => $rowData) {
        $rowNumber = $rowKey + 1;
        foreach ($rowData as $macro => $replace) {
            $templateProcessor->setValue($macro . '#' . $rowNumber, $replace);
        }
    }
}

$request=Request::find($filters['request_id']);
$not_appraised=$request->valuations()->where('have_changed','без изменений')->get();
$valuations=$request->valuations()->where('have_changed','!=','без изменений')->get();
$not_val_array=[];
foreach ($not_appraised as $key => $value) {
    $not_val_array[]=['num'=>$key+1,'cadastral_number'=>$value->cadastral_number];
}
$not_app=count($not_appraised);
$npages=intval($not_app/40)+1;
$sheets_word=$npages==1?'листе':'листах';


$templateProcessor = new TemplateProcessor($path['xsd'].'letter.docx');
$templateProcessor->setValue('number', $request->number);
$templateProcessor->setValue('not_appraised', $not_app);
$templateProcessor->setValue('npages', $npages);
$templateProcessor->setValue('sheets_word', $sheets_word);
$templateProcessor->setValue('appraised', count($valuations));
$templateProcessor->setValue('request.name', str_replace('Письмо', 'письмом', $request->name));
$templateProcessor->setValue('date', date("d.m.Y"));
cloneRowAndSetValues('num', $not_val_array);  

header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment;filename="в_Росреестр_.docx"');
header('Cache-Control: max-age=0');
// $templateProcessor->saveAs($output_path);
$templateProcessor->saveAs('php://output');
die();
        

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