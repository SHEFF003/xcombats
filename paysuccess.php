<?
header('Content-Type: text/html; charset=windows-1251');
function GetRealIp(){
	if (!empty($_SERVER['HTTP_CLIENT_IP']))
		return $_SERVER['HTTP_CLIENT_IP'];
	else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		return $_SERVER['HTTP_X_FORWARDED_FOR'];
	return $_SERVER['REMOTE_ADDR'];
}
define('IP',GetRealIp());		
define('GAME',true);
include('_incl_data/__config.php');	
include('_incl_data/class/__db_connect.php');	
include('_incl_data/class/__user.php');

// регистрационная информация (пароль #1)
// registration info (password #1)
$mrh_pass1 = "2t1vpldi";

// чтение параметров
// read parameters
$out_summ = $_REQUEST["OutSum"];
$inv_id = $_REQUEST["InvId"];
$shp_item = $_REQUEST["Shp_item"];
$crc = $_REQUEST["SignatureValue"];
$inv_desc = $_REQUEST["Inv_desc"];

$crc = strtoupper($crc);

$my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item"));

// проверка корректности подписи
// check signature
if ($my_crc != $crc)
{
  echo "bad sign\n";
  exit();
}

// проверка наличия номера счета в истории операций
// check of number of the order info in history of operations
/*$f=@fopen("order.txt","r+") or die("error");

while(!feof($f))
{
  $str=fgets($f);

  $str_exp = explode(";", $str);
  if ($str_exp[0]=="order_num :$inv_id")
  { 
	//
	//$pay = mysql_fetch_array(mysql_query('SELECT * FROM `pay_operation` WHERE `id` = "'.mysql_real_escape_string((int)$shp_item).'" LIMIT 1'));
	//mysql_query('UPDATE `pay_operation` SET `good` = "'.time().'",`ip2` = "'.mysql_real_escape_string(IP).'" WHERE `id` = "'.$pay['id'].'" LIMIT 1');
	//
	echo "Операция прошла успешно\n";
	echo "Operation of payment is successfully completed\n";
  }
}
fclose($f);*/

	echo "Операция прошла успешно\n";
	echo "Operation of payment is successfully completed\n";

?>


