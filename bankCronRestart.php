<?php
/*

	Обновление данных о курсе внутреней валюты

*/

function getIP() {
   if(isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
   return $_SERVER['REMOTE_ADDR'];
}

# Получаем IP
function getIPblock() {
   if(isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
   return $_SERVER['REMOTE_ADDR'];
}

# Выполняем проверку безопасности. 

if(!isset($_GET['kill'])) {
	//if( $_SERVER['HTTP_CF_CONNECTING_IP'] != $_SERVER['SERVER_ADDR'] && $_SERVER['HTTP_CF_CONNECTING_IP'] != '127.0.0.1' ) {	die('Hello pussy!');   }
	if(getIPblock() != $_SERVER['SERVER_ADDR'] && getIPblock() != '127.0.0.1' && getIPblock() != '' && getIPblock() != '91.228.154.180') {
		die(getIPblock().'<br>'.$_SERVER['SERVER_ADDR']);
	}
}


//$curency = 5.21; //курст 1 екр. к 1 руб. рф.
$true = array(
	'AUD' => true,
	'AZN' => true,
	'AMD' => true,
	'BYR' => true,
	'BGN' => true,
	'BRL' => true,
	'HUF' => true,
	'KRW' => true,
	'DKK' => true,
	'USD' => true,
	'EUR' => true,
	'INR' => true,
	'KZT' => true,
	'CAD' => true,
	'KGS' => true,
	'CNY' => true,
	'LVL' => true,
	'LTL' => true,
	'MDL' => true,
	'RON' => true,
	'TMT' => true,
	'NOK' => true,
	'PLN' => true,
	'XDR' => true,
	'SGD' => true,
	'TJS' => true,
	'TRY' => true,
	'UZS' => true,
	'UAH' => true,
	'GBP' => true,
	'CZK' => true,
	'SEK' => true,
	'CHF' => true,
	'ZAR' => true,
	'JPY' => true
);

define('GAME',true);

setlocale(LC_CTYPE ,"ru_RU.CP1251");

include('_incl_data/__config.php');
include('_incl_data/class/__db_connect.php');

//Обнуление передач раз в сутки
mysql_query('UPDATE `stats` SET `transfers` = 200 WHERE `transfers` < 200');

	function str_count($str,$col) { 
		if (strlen($str) > $col) 
		{ 
			$str = substr($str,0,$col); 
		} 
		return ($str); 
	}
	
function getCurs(){
    // создаем объект для работы с XML
    $xml = new DOMDocument();
    // ссылка на сайт банка
    $url = 'http://www.cbr.ru/scripts/XML_daily.asp?date_req=' . date('d.m.Y');
    // получаем xml с курсами всех валют
    if ($xml->load($url)){
        // массив для хранения курсов валют
        $result = array(); 
        // разбираем xml
        $root = $xml->documentElement;
        // берем все теги 'Valute' и их содержимое
        $items = $root->getElementsByTagName('Valute');
        // переберем теги 'Valute' по одному
        foreach ($items as $item){
            // получаем код валюты
            $code = $item->getElementsByTagName('CharCode')->item(0)->nodeValue;
            // получаем значение курса валюты, относительно рубля
            $value = $item->getElementsByTagName('Value')->item(0)->nodeValue;
			// номинал
			$nominal = $item->getElementsByTagName('Nominal')->item(0)->nodeValue;
            // записываем в массив, предварительно заменив запятую на точку
            $result[$code] = round(str_replace(',', '.', $value),5)/$nominal;
        }
        // возвращаем значение курса, для запрошенной валюты
        return $result;
    }else{
        // если не получили xml возвращаем false
        return false;
    }
}
$get = getCurs();
//
if( $c['curency_name'] == 'RUB' ) {
	$curency = $c['curency_value'];
}else{
	$curency = round($get[$c['curency_name']]*$c['curency_value'],4);
}
//
if( $get['USD'] > 0 ) {
	$price = array(
		'AUD','AZN','AMD','BYR','BGN','BRL','HUF','KRW','DKK','USD','EUR','INR','KZT','CAD','KGS','CNY','LVL','LTL','MDL','RON','TMT','NOK','PLN','XDR','SGD','TJS','TRY','UZS','UAH','GBP','CZK','SEK','CHF','ZAR','JPY'
	);
	$i = 0;
	while( $i < count($price) ) {
		if( isset($price[$i]) && $get[$price[$i]] > 0 ) {
			if( $price[$i] == 'UAH' || $price[$i] == 'BYR' ) {
				//$get[$price[$i]] -= round($get[$price[$i]]/6,4);
			}
			$r .= ',`'.$price[$i].'`="'.$get[$price[$i]].'"';
		}
		$i++;
	}
	//echo 'INSERT INTO `bank_table` SET `time` = "'.time().'",`cur` = "'.$curency.'",`data` = "'.date('d.m.Y').'"'.$r.'<br>';
	mysql_query('INSERT INTO `bank_table` SET `time` = "'.time().'",`cur` = "'.$curency.'",`data` = "'.date('d.m.Y').'"'.$r.'');
}
?>