<?php

$request=Request::find($filters['request_id']);
$valuations=$request->valuations()->where('have_changed','!=','без изменений')->get();
$valuations_list=[];

foreach ($valuations as $parcel) {
    $cadastr = explode(":", $parcel->cadastral_number);
    $valuations_list[$cadastr[0]][$cadastr[1]][$cadastr[2]][$cadastr[3]]=$parcel;
}

$doc = simplexml_load_file($path['xsd'].'cost.xml');
$doc->eDocument["Date"]= date("Y-m-d");
$doc->eDocument->Sender["Date_Upload"]=date("Y-m-d");
foreach ($valuations_list as $cadastral_region_key=>$cadastral_region){
    $cad_region=$doc->Package->Federal->Cadastral_Regions->addChild('Cadastral_Region');
    $cad_region->addAttribute('CadastralNumber',$cadastral_region_key);
    $cad_districts=$cad_region->addChild('Cadastral_Districts');
    foreach($cadastral_region as $cadastral_district_key=>$cadastral_district){
        $cad_district=$cad_districts->addChild('Cadastral_District');
        $cad_district->addAttribute('CadastralNumber',$cadastral_region_key.':'.$cadastral_district_key);
        $cad_blocks=$cad_district->addChild('Cadastral_Blocks');
        foreach($cadastral_district as $cadastral_block_key=>$cadastral_block){
            $cad_block=$cad_blocks->addChild('Cadastral_Block');
            $cad_block->addAttribute('CadastralNumber',$cadastral_region_key.':'.$cadastral_district_key.':'.$cadastral_block_key);
            $valuations=$cad_block->addChild('Parcels');
            foreach($cadastral_block as $parcel){
                $par=$valuations->addChild('Parcel');
                $par->addAttribute('CadastralNumber',$parcel->cadastral_number);
                $ground_payments=$par->addChild('Ground_Payments');
                $s_cost=$ground_payments->addChild('Specific_CadastralCost');
                $s_cost->addAttribute('Value',$parcel->specific_cadastral_cost);
                $s_cost->addAttribute('Unit',"1002");
                $cost=$ground_payments->addChild('CadastralCost');
                $cost->addAttribute('Value',$parcel->cadastral_cost);
                $cost->addAttribute('Unit',"383");
            }
        }
    }    
}
$dom = new DOMDocument("1.0");
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$dom->loadXML($doc->asXML());
header('Content-Type: application/xml');
header('Content-Disposition: attachment;filename="COST_59_59_'.date("Ymd").'_'.$request->number.'.XML"');
header('Cache-Control: max-age=0');
echo $dom->saveXML();