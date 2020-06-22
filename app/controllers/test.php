<?php

// $parcel=Parcel::whereCadastralNumber('59:01:4219233:32')->orderBy('created_at','DESC')->first();
// dd($parcel->data['ObjectType']);

// die();

$row = 0;
$start = 1;

if (($handle = fopen($path['xsd'].'groups.csv', 'r')) !== FALSE) {
    while (($entry = fgetcsv($handle, 0, ';')) !== FALSE) {
        $row++;
        if ($row>$start && $entry[0]!=''){
            $data = array_map(function($val) { 
                return iconv('CP1251', 'UTF-8', $val); 
            }, $entry);
            $e=Group::firstOrCreate(['id'=>$data[0],'name'=>$data[1]]);            
        }
    }
    fclose($handle);
}







die();
// $s=new Source();
// $s->fefe="fefe";
// $s->save();
$vl=Valuation::all();
foreach ($vl as $v) {
    if (!Parcel::whereCadastralNumber($v->cadastral_number)->first()){
        dd($v->cadastral_number);
    }
}
// $s=$data->parcels()->get();
die();
dd($s);
$data=Source::first();
dd($data->request()->get());



$parcels_list=[];

function process_row($input){
    global $parcels_list;
    $data = array_map(function($val) { 
        return iconv('CP1251', 'UTF-8', $val); 
    }, $input);

    $cadastr = explode(":", $data[0]);
    $parcel=['CadastralNumber'=>$data[0],'CadastralCost'=>$data[59],'Specific_CadastralCost'=>$data[60],'Usage'=>$data[5],'Method'=>$data[65]];
    $parcels_list[$cadastr[0]][$cadastr[1]][$cadastr[2]][$cadastr[3]]=$parcel;
}

$row = 0;
$start = 2;
if (($handle = fopen($path['xsd'].'test2.csv', 'r')) !== FALSE) {
    while (($data = fgetcsv($handle, 0, ';')) !== FALSE) {
        $row++;
        if ($row>$start){
            process_row($data);
        }
    }
    fclose($handle);
}

$doc = simplexml_load_file($path['xsd'].'cost.xml');
$doc->eDocument["Date"]= date("Y-m-d");
$doc->eDocument->Sender["Date_Upload"]=date("Y-m-d");
foreach ($parcels_list as $cadastral_region_key=>$cadastral_region){
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
            $parcels=$cad_block->addChild('Parcels');
            foreach($cadastral_block as $parcel){
                $par=$parcels->addChild('Parcel');
                $par->addAttribute('CadastralNumber',$parcel['CadastralNumber']);
                $ground_payments=$par->addChild('Ground_Payments');
                $s_cost=$ground_payments->addChild('Specific_CadastralCost');
                $s_cost->addAttribute('Value',$parcel['Specific_CadastralCost']);
                $s_cost->addAttribute('Unit',"1002");
                $cost=$ground_payments->addChild('CadastralCost');
                $cost->addAttribute('Value',$parcel['CadastralCost']);
                $cost->addAttribute('Unit',"383");
            }
        }
    }    
}
$dom = new DOMDocument("1.0");
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$dom->loadXML($doc->asXML());
echo $dom->saveXML();

die();

// $doc = new DOMDocument();
// $doc->preserveWhiteSpace = true;
// $doc->load($path['xsd'].'ListForRating_v04.xsd');
// $doc->save($path['xsd'].'t.xml');
// $xmlfile = file_get_contents($path['xsd'].'t.xml');
// $parseObj = str_replace($doc->lastChild->prefix.':',"",$xmlfile);
// file_put_contents($path['xsd'].'tt.xml', $parseObj);
// die();
// $xml  = simplexml_load_file($path['upload'].'RR_CTI_59_30012020_00001.xml');
// // die(print_r($xml->Objects->Parcels));

// $client = new MongoDB\Client("mongodb://root:example@mongo:27017");
// $collection = $client->requests->parcels;
// foreach ( $xml->Objects->Parcels->Parcel as $parcel )
// {$result = $collection->updateOne( ['@attributes.CadastralNumber'=>$parcel->attributes->CadastralNumber], ['$set'=>(array)$parcel],['upsert'=>true] );}

// // printf("Inserted %d document(s)\n", $insertManyResult->getInsertedCount());

// die();

// function xml2array($fname){
//   $sxi = new SimpleXmlIterator($fname, null, true);
//   return sxiToArray($sxi);
// }

// function sxiToArray($sxi){
//   $a = array();
//   for( $sxi->rewind(); $sxi->valid(); $sxi->next() ) {
//     if(!array_key_exists($sxi->key(), $a)){
//       $a[$sxi->key()] = array();
//     }
//     foreach ($sxi->attributes() as $key => $value) {
//       $a[$sxi->key()]['attributes'][$key]=$value[0];
//     }

//     if($sxi->hasChildren()){

//       $a[$sxi->key()]['value'][] = sxiToArray($sxi->current());
//     }
//     else{
//       $a[$sxi->key()]['value'][] = (array) $sxi->current();
//     }
//   }
//   return $a;
// }

// function check($arr) {
//   foreach ($arr as &$el) {
//     if (is_array($el)) {
//       $el=check($el);
//       if (count($el)==1 && isset($el[0])){
//         $el=$el[0];
//       }
//     }
//   }
//   return $arr;
// }


// // Read cats.xml and print the results:
// // $catArray = check(xml2array($path['xsd'].'tt.xml'));
// $catArray = xml2array($path['xsd'].'tt.xml');
// echo "<pre>";

// echo json_encode($catArray,JSON_PRETTY_PRINT);
// die();



// $doc = new DOMDocument();
// $doc->preserveWhiteSpace = true;
// $doc->load($path['xsd'].'ListForRating_v04.xsd');
// $doc->save($path['xsd'].'t.xml');
// $xmlfile = file_get_contents($path['xsd'].'t.xml');
// $parseObj = str_replace($doc->lastChild->prefix.':',"",$xmlfile);
// $mydata = new SimpleXmlIterator($path['xsd'].'t.xml', 0, true);
// function getAttributes($obj){

// for ($obj->rewind();$obj->valid();$obj->next()) {
//   $attributes=$obj->current()->attributes();
//   if ($obj->haschildren()) {
//       $attributes['children']=getAttributes($obj->current());
//   }
//   return $attributes;
// }
// }

// $ob= getAttributes($mydata);
// echo "<pre>";
// print_r($ob);
// die();
