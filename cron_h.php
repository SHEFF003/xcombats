<?php

function getIP() {
   if(isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
   return $_SERVER['REMOTE_ADDR'];
}

# �������� IP
function getIPblock() {
   if(isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
   return $_SERVER['REMOTE_ADDR'];
}

define('GAME',true);

include('_incl_data/__config.php');
include('_incl_data/class/__db_connect.php');

function e($t) {
	mysql_query('INSERT INTO `chat` (`text`,`city`,`to`,`type`,`new`,`time`) VALUES ("core #'.date('d.m.Y').' %'.date('H:i:s').' (����������� ������): <b>'.mysql_real_escape_string($t).'</b>","capitalcity","ENERGY STAR","6","1","-1")');
}

function send_chat($type,$from,$text,$time) {
	mysql_query('INSERT INTO `chat` (`text`,`city`,`login`,`to`,`type`,`new`,`time`,`room`) VALUES ("'.mysql_real_escape_string($text).'","capitalcity","'.mysql_real_escape_string($from).'","","'.$type.'","1","'.mysql_real_escape_string($time).'","3")');
}

# ��������� �������� ������������. 

if(getIPblock() != $_SERVER['SERVER_ADDR'] && getIPblock() != '127.0.0.1' && getIPblock() != '' && getIPblock() != '91.228.154.180' ) {
	die(getIPblock().'<br>'.$_SERVER['SERVER_ADDR']);
}

/* ���� ���� */
function many_start() {
	$qtext = '';
	$atext = '';
	$msec = 120;
	$lmvc = array();
	$lmv = mysql_query('SELECT `id` FROM `aaa_many` WHERE `time` < "'.(time()-86400).'" ORDER BY `time` ASC');
	while( $plmv = mysql_fetch_array($lmv) ) {
		$lmvc[] = $plmv['id'];
	}
	$lmvc = $lmvc[rand(0,(count($lmvc)-1))];
	$lmv = mysql_fetch_array(mysql_query('SELECT * FROM `aaa_many` WHERE `id` = "'.$lmvc.'" LIMIT 1'));
	if( isset($lmv['id']) ) {
		$qtext = $lmv['text'];
		$atext = $lmv['answer'];
		mysql_query('UPDATE `aaa_many` SET `time` = "'.time().'" WHERE `id` = "'.$lmv['id'].'" LIMIT 1');
	}
	if( $qtext != '' ) {
		$qtext = str_replace('"','&quot;',$qtext);
		$qtext = str_replace("'",'&quot;',$qtext);
		$qtext = str_replace('<','&lt;',$qtext);
		$qtext = str_replace('>','&gt;',$qtext);
		$qtext = str_replace('
',' ',$qtext);
		$qtext = str_replace("\r",' ',$qtext);
		$qtext = str_replace('�','c',$qtext);
		$qtext = str_replace('�','x',$qtext);
		$qtext = str_replace('�','y',$qtext);
		$qtext = str_replace('�','e',$qtext);
		$qtext = str_replace('�','o',$qtext);
		$qtext = str_replace('�','p',$qtext);
		$qtext = str_replace('�','u',$qtext);
		mysql_query('UPDATE `users` SET `online` = "'.time().'" WHERE `id` = 1003553 LIMIT 1');
		send_chat(1,'���� ����','<font color=#cb0000><b>��������!</b> ���������� ���������! �������, ��� ��������� ������� �� ��������� ������ ����� �������� �������� ����. ����� �������� ������ '.$msec.' ������. ���� ������ ������ � ��� <b>to [���� ����]</b>. ��������, ������: <b>'.$qtext.'</b></font>',time());
		mysql_query('INSERT INTO `aaa_many_now` (`qid`,`time`) VALUES ("'.$lmv['id'].'","'.(time()+$msec).'")');
		send_chat(1,'���� ����','<font color=red>����� �� ������! ���������� �����: <b>'.$atext.'</b></font>.',-(time()+$msec));
	}
}

many_start();
?>