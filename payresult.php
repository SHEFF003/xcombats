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

// ��������������� ���������� (������ #2)
// registration info (password #2)
$mrh_pass2 = "q7eklnmi";

//��������� �������� �������
//current date
$tm=getdate(time()+9*3600);
$date="$tm[year]-$tm[mon]-$tm[mday] $tm[hours]:$tm[minutes]:$tm[seconds]";

// ������ ����������
// read parameters
$out_summ = $_REQUEST["OutSum"];
$inv_id = $_REQUEST["InvId"];
$shp_item = $_REQUEST["Shp_item"];
$crc = $_REQUEST["SignatureValue"];

$crc = strtoupper($crc);

$my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass2:Shp_item=$shp_item"));

// �������� ������������ �������
// check signature
if ($my_crc !=$crc)
{
  echo "bad sign\n";
  exit();
}

// ������� ������� ����������� ��������
// success
echo "OK$inv_id\n";

// ������ � ���� ���������� � ����������� ��������
// save order info to file
/*$f=@fopen("order.txt","a+") or
          die("error");
fputs($f,"order_num :$inv_id;Summ :$out_summ;Date :$date\n");
fclose($f);*/

//��������� ��� � ��������� ������ �� ����
$pay = mysql_fetch_array(mysql_query('SELECT * FROM `pay_operation` WHERE `id` = "'.mysql_real_escape_string((int)$shp_item).'" LIMIT 1'));
//
$bank = mysql_fetch_array(mysql_query('SELECT * FROM `bank` WHERE `id` = "'.$pay['bank'].'" LIMIT 1'));
//
$user = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `id` = "'.$bank['uid'].'" LIMIT 1'));
//
$ref = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `id` = "'.$pay['ref'].'" LIMIT 1'));
//
$bank2 = mysql_fetch_array(mysql_query('SELECT * FROM `bank` WHERE `uid` = "'.$ref['id'].'" ORDER BY `id` DESC LIMIT 1'));

if( $pay['good'] == 0 ) {
	//
	if(isset($ref['id']) && true == false) {
		//����� �� ��������
		$r = '<span class=date>'.date('d.m.Y H:i').'</span> <img src=http://img.xcombats.com/i/align/align50.gif width=12 height=15 /><u><b>����</b> &laquo;������� ����������� �����&raquo; / ����������</u> ��������: ';				
		if( floor($pay['ekr']*0.10) > 0 && $bank2['id'] > 0 ) {
			if($ref['sex'] == 1) {
				$r .= '���������';
			}else{
				$r .= '���������';
			}
			$r .= ' <b>'.$ref['login'].'</b>, �� ��� ���������� ���� �'.$bank2['id'].' ��������� '.floor($pay['ekr']*0.10).' Ekr. (��� ������� <b>'.$user['login'].'</b> �������� Ekr.)';
			mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$ref['city']."','".$ref['room']."','','".$ref['login']."','".$r."','-1','5','0')");
			mysql_query('UPDATE `bank` SET `money2` = "'.mysql_real_escape_string($bank2['money2']+floor($pay['ekr']*0.10)).'" WHERE `id` = "'.$bank2['id'].'" LIMIT 1');
		}
	}
	//
	$r = '<span class=date>'.date('d.m.Y H:i').'</span> <img src=http://img.xcombats.com/i/align/align50.gif width=12 height=15 /><u><b>����</b> &laquo;������� ����������� �����&raquo; / ����������</u> ��������: ';				
	if($user['sex'] == 1) {
		$r .= '���������';
	}else{
		$r .= '���������';
	}						
	$r .= ' <b>'.$user['login'].'</b>, �� ��� ���������� ���� �'.$bank['id'].' ��������� '.$pay['ekr'].' Ekr. ���������� ��� �� �������!';
	mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$user['city']."','".$user['room']."','','".$user['login']."','".$r."','-1','5','0')");
	//
	$bnsts = mysql_fetch_array(mysql_query('SELECT `id` FROM `bank` WHERE `uid` = "'.$user['id'].'" AND `moneyBuy` > 0 LIMIT 1'));
	if(!isset($bnsts['id']) && date('d.m.Y') == '08.01.2017') {
		$bank['money2'] += $pay['ekr'];
		mysql_query('UPDATE `bank` SET `shara` = "'.mysql_real_escape_string($bank['shara']+$pay['ekr']).'" WHERE `id` = "'.$bank['id'].'" LIMIT 1');
		$r .= ' <b>'.$user['login'].'</b>, �� ��� ���������� ���� �'.$bank['id'].' ��������� '.$pay['ekr'].' Ekr. (�����)';
		mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$user['city']."','".$user['room']."','','".$user['login']."','".$r."','-1','5','0')");
	}
	//
	$text_msg = '������� <b><font color=red>Robokassa</font></b> �������� ������� <b>'.$pay['ekr'].'</b> ���. (������ 0% , ����������� 0$). ����������: '.$u->microLogin($user['id'],1).'. ���������� ���� ����������: � <b>'.$bank['id'].'</b>.';
	$money = $pay['ekr']*$pay['cur'];			
	$balance = mysql_fetch_array(mysql_query('SELECT SUM(`money`) FROM `balance_money` WHERE `cancel` = 0'));
	$balance = $balance[0]+$money;
	mysql_query('INSERT INTO `balance_money` (`time`,`ip`,`money`,`comment2`,`balance`,`cancel`) VALUES ("'.time().'","'.mysql_real_escape_string(IP).'","'.mysql_real_escape_string(floor($money)).'","'.mysql_real_escape_string($text_msg).'","'.$balance.'","0")');
	//
	mysql_query('UPDATE `bank` SET `money2` = "'.mysql_real_escape_string($bank['money2']+$pay['ekr']).'",`moneyBuy` = "'.mysql_real_escape_string($bank['moneyBuy']+$pay['ekr']).'" WHERE `id` = "'.$bank['id'].'" LIMIT 1');
	//
	mysql_query('UPDATE `pay_operation` SET `good` = "'.time().'",`ip2` = "'.mysql_real_escape_string(IP).'" WHERE `id` = "'.$pay['id'].'" LIMIT 1');
}
?>


