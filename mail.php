<?
function GetRealIp(){
	if (!empty($_SERVER['HTTP_CLIENT_IP']))
		return $_SERVER['HTTP_CLIENT_IP'];
	else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		return $_SERVER['HTTP_X_FORWARDED_FOR'];
	return $_SERVER['REMOTE_ADDR'];
}

define('IP',GetRealIp());
include('_incl_data/__config.php');
define('GAME',true);
include('_incl_data/class/__db_connect.php');
include('_incl_data/class/__users.php');

if( isset($_GET['list']) && $_GET['list'] == 2015 ) {
	
	$mail = array();
	$yes = array();
	$sp = mysql_query('SELECT * FROM `aaa_send_count`');
	while( $pl = mysql_fetch_array($sp) ) {
		if(!isset($yes[$pl['mail']])) {
			$yes[$pl['mail']] = true;
			$mail[] = $pl['mail'];
		}
	}
	$mail[] = 'get.money@list.ru';
	$sp = mysql_query('SELECT * FROM `users` WHERE `real` > 0');
	while( $pl = mysql_fetch_array($sp) ) {
		if(!isset($yes[$pl['mail']])) {
			$yes[$pl['mail']] = true;
			$mail[] = $pl['mail'];
		}
	}
	$mail[] = 'q.joker@mail.ru';
	$sp = mysql_query('SELECT * FROM `users_kill`');
	while( $pl = mysql_fetch_array($sp) ) {
		if(!isset($yes[$pl['mail']])) {
			$yes[$pl['mail']] = true;
			$mail[] = $pl['mail'];
		}
	}
	$sp = mysql_query('SELECT * FROM `beta_testers`');
	while( $pl = mysql_fetch_array($sp) ) {
		if(!isset($yes[$pl['mail']])) {
			$yes[$pl['mail']] = true;
			$mail[] = $pl['mail'];
		}
	}
	$sp = mysql_query('SELECT * FROM `users_rbk`');
	while( $pl = mysql_fetch_array($sp) ) {
		if(!isset($yes[$pl['email']])) {
			$yes[$pl['email']] = true;
			$mail[] = $pl['email'];
		}
	}
	
	$e = explode(',','mails');	
	$i = 0;
	while( $i < count($e) ) {
		if(!isset($yes[$e[$i]])) {
			$yes[$yes[$e[$i]]] = true;
			$mail[] = $e[$i];
		}
		$i++;
	}
	
	$i = 0;
	while( $i < count($mail) ) {
		echo $mail[$i] . '<br>';
		$i++;
	}
	
}

$keymd5 = '$����$����$';

if(isset($_GET['count'])) {
	if( md5($keymd5.'+'.$_GET['count']) == $_GET['sd4'] ) {
		$mail = mysql_fetch_array(mysql_query('SELECT * FROM `aaa_send_count` WHERE `mail` = "'.mysql_real_escape_string($_GET['count']).'" LIMIT 1'));
		if(isset($mail['id'])) {
			mysql_query('UPDATE `aaa_send_count` SET `time` = "'.time().'",`ip` = "'.mysql_real_escape_string(IP).'" WHERE `id` = "'.$mail['id'].'" LIMIT 1');
		}else{
			mysql_query('INSERT INTO `aaa_send_count` (`mail`,`time`,`ip`) VALUES (
				"'.mysql_real_escape_string($_GET['count']).'","'.time().'","'.mysql_real_escape_string(IP).'"
			)');
		}
		echo '[SD4]';
	}else{
		echo '[ERROR_SD4_KEY]';
	}
	die('[IMG]');
}elseif(isset($_GET['uncancel'])) {
	$mail = mysql_fetch_array(mysql_query('SELECT * FROM `aaa_send_count` WHERE `mail` = "'.mysql_real_escape_string($_GET['uncancel']).'" LIMIT 1'));
	if(isset($mail['id'])) {
		mysql_query('UPDATE `aaa_send_count` SET `cancel` = "0",`time` = "'.time().'",`ip` = "'.mysql_real_escape_string(IP).'" WHERE `id` = "'.$mail['id'].'" LIMIT 1');
		echo '��.����� <b>'.htmlspecialchars($_GET['uncancel']).'</b> ������� �������� �� ���� �������!';
	}else{
		echo '��.����� <b>'.htmlspecialchars($_GET['uncancel']).'</b> �� ������ � ����.';
	}
	die();
}elseif(isset($_GET['cancel'])) {
	if( md5($keymd5.'+'.$_GET['cancel']) == $_GET['sd4'] ) {
		$mail = mysql_fetch_array(mysql_query('SELECT * FROM `aaa_send_count` WHERE `mail` = "'.mysql_real_escape_string($_GET['cancel']).'" LIMIT 1'));
		if(isset($mail['id'])) {
			if($mail['cancel'] > 0) {
				echo '�� ���������� �� ��������: <b>'.date('d.m.Y H:i:s',$mail['cancel']).'</b>, ������ ����������� �����? <a href="http://xcombats.com/mail.php?uncancel='.$mail['mail'].'">����������� �� �������� �����</a></b>';
			}else{
				mysql_query('UPDATE `aaa_send_count` SET `cancel` = "'.time().'",`time` = "'.time().'",`ip` = "'.mysql_real_escape_string(IP).'" WHERE `id` = "'.$mail['id'].'" LIMIT 1');
				echo '��.����� <b>'.htmlspecialchars($_GET['cancel']).'</b> ������� �������� �� ����� ��������!';
			}
		}else{
			echo '��.����� <b>'.htmlspecialchars($_GET['cancel']).'</b> �� ��������� �� ���� ��������.';
		}
	}else{
		echo '�� �� ���������� �.�. SD4 ���� �� �������� � ��.����� <b>'.htmlspecialchars($_GET['cancel']).'.</b>. ��������� �� ������ ��������� � ������, ���� �������� ��� � ������ ��������� support@xcombats.com';
	}
	die();
}

if(isset($_GET['send6102'])) {
	// specify your email below and that's all ;)	
	$message = '����� ���������!';	
	//	***************************************
	
	function sendmail($mail,$login) {
		global $message, $keymd5;
		//
		$md5mail = md5($keymd5.'+'.$mail);
		$message = str_replace('{mail}',$mail,$message);
		$message = str_replace('{login}',$login,$message);
		$message = str_replace('{md5mail}',$md5mail,$message);
		//
		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=windows-1251\r\n";	
		$headers .= "From: support@xcombats.com\r\n";
		//
		$to = $mail;
		//
		$subject = '���: ������ ���������� ����';
		$message = '����������� ��� e-mail ��� <b>'.$mail['name'].'</b>.<br><br>� ���������,<br>������������� xcombats.com';
		//
		if (mail($to, $subject, $message, $headers) == true) {
			//return true;
			echo '[Yes]';
		}else{
			//return false;
			echo '[No]';
		}
	}	

	$mail = mysql_fetch_array(mysql_query('SELECT * FROM `users_rbk` WHERE `email` != "" AND `send` != 3 AND `email` NOT LIKE "%@1%" LIMIT 1'));
	$x = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `users_rbk` WHERE `email` != "" AND `send` != 3 AND `email` NOT LIKE "%@1%" LIMIT 1'));
	$x = 0+$x[0];
	if(isset($mail['email'])) {
		//$mail['email'] = 'ditsfree@gmail.com';
		
		sendmail($mail['email'],$mail['login']);
		mysql_query('UPDATE `users_rbk` SET `send` = 3 WHERE `email` = "'.$mail['email'].'"');
		echo '['.$mail['email'].']<hr>����������� ��� e-mail ��� <b>'.$mail['name'].'</b>.<br><br>� ���������,<br>������������� xcombats.com<hr>';
		echo '[+]<script>setTimeout("top.location = top.location",1000);</script>';
	}else{
		echo '[-]';
		echo '['.$mail['email'].']';
		/*echo '[-]<script>setTimeout("top.location = top.location",100);</script>';*/
	}
	die('<br>����������: '.$x);
	if( $k > 0 ) {
		echo '<script>setTimeout(\'location.href="mail.php?send=1&i='.$j.'";\',1500);</script>';		
	}
	echo '������� ����������: '.$k.' / 100 �����. ����� ������ ��������: '.(count($mails)-$k).'.<br><a href="mail.php?send=1&i='.$j.'">��������� 50 ������!</a>';
}
?>