<?php
/*

	���������� ������ � ����� ��������� ������

*/

function getIP() {
   if(isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
   return $_SERVER['REMOTE_ADDR'];
}

# �������� IP
function getIPblock() {
   if(isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
   return $_SERVER['REMOTE_ADDR'];
}

# ��������� �������� ������������. 

if(!isset($_GET['kill'])) {
	//if( $_SERVER['HTTP_CF_CONNECTING_IP'] != $_SERVER['SERVER_ADDR'] && $_SERVER['HTTP_CF_CONNECTING_IP'] != '127.0.0.1' ) {	die('Hello pussy!');   }
	if(getIPblock() != $_SERVER['SERVER_ADDR'] && getIPblock() != '127.0.0.1' && getIPblock() != '' && getIPblock() != '91.228.154.180') {
		die(getIPblock().'<br>'.$_SERVER['SERVER_ADDR']);
	}
}


//$curency = 5.21; //����� 1 ���. � 1 ���. ��.
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

//��������� ������� ��� � �����
mysql_query('UPDATE `stats` SET `transfers` = 200 WHERE `transfers` < 200');

	function str_count($str,$col) { 
		if (strlen($str) > $col) 
		{ 
			$str = substr($str,0,$col); 
		} 
		return ($str); 
	}
	
function getCurs(){
    // ������� ������ ��� ������ � XML
    $xml = new DOMDocument();
    // ������ �� ���� �����
    $url = 'http://www.cbr.ru/scripts/XML_daily.asp?date_req=' . date('d.m.Y');
    // �������� xml � ������� ���� �����
    if ($xml->load($url)){
        // ������ ��� �������� ������ �����
        $result = array(); 
        // ��������� xml
        $root = $xml->documentElement;
        // ����� ��� ���� 'Valute' � �� ����������
        $items = $root->getElementsByTagName('Valute');
        // ��������� ���� 'Valute' �� ������
        foreach ($items as $item){
            // �������� ��� ������
            $code = $item->getElementsByTagName('CharCode')->item(0)->nodeValue;
            // �������� �������� ����� ������, ������������ �����
            $value = $item->getElementsByTagName('Value')->item(0)->nodeValue;
			// �������
			$nominal = $item->getElementsByTagName('Nominal')->item(0)->nodeValue;
            // ���������� � ������, �������������� ������� ������� �� �����
            $result[$code] = round(str_replace(',', '.', $value),5)/$nominal;
        }
        // ���������� �������� �����, ��� ����������� ������
        return $result;
    }else{
        // ���� �� �������� xml ���������� false
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