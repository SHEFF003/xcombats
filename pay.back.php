<?
header('Content-Type: text/html; charset=windows-1251');

if( isset($_POST['ekr']) ) {
	$_GET['ekr'] = $_POST['ekr'];
}

//die('� ��������� ���������� �������� ����������. �������� ��������� �� ����������. (��������� �� 1-2 ���� 2015)');
	
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

if( $u->bank['id'] == 0 ) {
	die('��������������� � �����.');
}


if(isset($_GET['buy_ekr'])) {
	
?><!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=windows-1251" /><title>������� ������������ ������</title>
<link href="http://img.xcombats.com/css/main.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/jquery.1.11.js"></script>
</head><body style="padding-top:0px; margin-top:7px; height:100%; background-color:#dedede;">
<div style="padding:50px 50px 50px 50px;">
<?
$bnsts = mysql_fetch_array(mysql_query('SELECT `id` FROM `bank` WHERE `uid` = "'.$u->info['id'].'" AND `moneyBuy` > 0 LIMIT 1'));
if(!isset($bnsts['id']) && date('d.m.Y') == '08.01.2017') {
	echo '<div align="center"><b><font color=red>�� �������� +100% ����� � ������ �������, ���� ��������� � �������!</font></b><hr></div>';
}
?>
<fieldset style="background-color:#DDEAD7">
<legend><img src="http://img.xcombats.com/i/align/align50.gif" width="12" height="15" /> <b style="color:#5F3710">������������ ���. ������ / <?=$u->microLogin($u->info['id'],1)?> / ���� �<?=$u->bank['id']?></b> </legend>
<style>
#pay_btn {
	background-color:#00FF99;
	color:#009900;
	cursor:pointer;	
}
#pay_btn:hover {
	background-color:#CCC;
	color:#FFF;
	cursor:pointer;	
}
</style>
<center>����� ���.: <input id="pay_in" style="padding-left:2px;width:77px;" value="1.00">
<input id="pay_btn" name="pay_btn" value="��������" type="button" onclick="window.open('/pay.back.php?ekr='+$('#pay_in').val()+'&code=1&ref=0','_blank');" style="padding:5px;" />
<hr /><small>(����������� ����� �������: 0.10 ���.)</small>
</center>
</div></body></html>
<?
	die();
}

$cur = mysql_fetch_array(mysql_query('SELECT * FROM `bank_table` WHERE `cur` > 1 ORDER BY `id` DESC LIMIT 1'));

// 1.
// ������ �������� ����� � ������� ������ �� ����� ��������
// Payment of the set sum with a choice of currency on merchant site 

// ��������������� ���������� (�����, ������ #1)
// registration info (login, password #1)
$mrh_login = "43256";
$mrh_pass1 = "2t1vpldi";
//pass2 = sku1h5mi

// ����� ������
// number of order
$inv_id = 0;

// ����� ������
// sum of order

$out_ekr = round($_GET['ekr'],2);

if( $out_ekr < 1 ) {
	//
	$out_ekr = 1;
}

$out_summ = round($out_ekr*$cur['cur'],2);

// �������� ������
// order description
//$inv_desc = "ekrx".$out_ekr."x".$u->bank['id']."x".$out_summ."x".time()."x".round((int)$_GET['ref'])."";

$inv_desc = '���������� '.$out_ekr.' ���., ���������� ���� �'.$u->bank['id'].', ����� �'.round((int)$_GET['ref']).'';

// ��� ������
// code of goods
$shp_item = 0;

//��������� � ����
mysql_query('INSERT INTO `pay_operation` (
	`uid`,`bank`,`code`,`ekr`,`time`,`good`,`cur`,`var`,`val`,`ref`,`ref2`,`ip`,`date`
) VALUES (
	"'.$u->info['id'].'","'.$u->bank['id'].'","'.mysql_real_escape_string((int)$_GET['code']).'","'.mysql_real_escape_string($out_ekr).'",
	"'.time().'","0","'.mysql_real_escape_string($cur['cur']).'","buy_ekr","0","'.mysql_real_escape_string($u->info['host_reg']).'",
	"'.mysql_real_escape_string((int)$_GET['ref']).'","'.mysql_real_escape_string(IP).'","'.date('Y-m-d H:i:s').'"
)');

$shp_item = mysql_insert_id();

if($shp_item > 0) {
	//������� ������
}else{
	die('������ � ��������� �������, ���������� � �������������');
}

// ������������ ������ �������
// default payment e-currency
$in_curr = "";

// ����
// language
$culture = "ru";

// ������������ �������
// generate signature
$crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item");

// ����� ������ ������
// payment form
$url  = 'http://www.free-kassa.ru/merchant/cash.php?';
$url .= 'MrchLogin='.$mrh_login.'&';
$url .= 'OutSum='.$out_summ.'&';
$url .= 'InvId='.$inv_id.'&';
$url .= 'Desc='.$inv_desc.'&';
$url .= 'SignatureValue='.$crc.'&';
$url .= 'Shp_item='.$shp_item.'&';
$url .= 'IncCurrLabel='.$in_curr.'&';
$url .= 'Culture='.$culture.'&';
//
header('location: '.$url);
die();

// ����� ������ ������
// payment form
print "<html>".
      "<script type=\"text/javascript\" src=\"js/jquery.js\"></script><form id=\'F1\' action='http://www.free-kassa.ru/merchant/cash.php' method=POST>".
	  "����� �������: ".$out_ekr." ���. ".
      "<input type=hidden name=MrchLogin value=$mrh_login>".
      "<input type=hidden name=OutSum value='$out_summ'>".
      "<input type=hidden name=InvId value=$inv_id>".
      "<input type=hidden name=Desc value='$inv_desc'>".
      "<input type=hidden name=SignatureValue value=$crc>".
      "<input type=hidden name=Shp_item value='$shp_item'>".
      "<input type=hidden name=IncCurrLabel value=$in_curr>".
      "<input type=hidden name=Culture value=$culture>".
      "<input type=submit value='��������'><Br>".
	  "(��� �������� ���� �� �������� � ��������� ����)".
      "</form></html>";
?>