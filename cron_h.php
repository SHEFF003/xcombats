<?php

function getIP() {
   if(isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
   return $_SERVER['REMOTE_ADDR'];
}

# Получаем IP
function getIPblock() {
   if(isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
   return $_SERVER['REMOTE_ADDR'];
}

define('GAME',true);

include('_incl_data/__config.php');
include('_incl_data/class/__db_connect.php');

function e($t) {
	mysql_query('INSERT INTO `chat` (`text`,`city`,`to`,`type`,`new`,`time`) VALUES ("core #'.date('d.m.Y').' %'.date('H:i:s').' (Критическая ошибка): <b>'.mysql_real_escape_string($t).'</b>","capitalcity","ENERGY STAR","6","1","-1")');
}

function send_chat($type,$from,$text,$time) {
	mysql_query('INSERT INTO `chat` (`text`,`city`,`login`,`to`,`type`,`new`,`time`,`room`) VALUES ("'.mysql_real_escape_string($text).'","capitalcity","'.mysql_real_escape_string($from).'","","'.$type.'","1","'.mysql_real_escape_string($time).'","3")');
}

# Выполняем проверку безопасности. 

if(getIPblock() != $_SERVER['SERVER_ADDR'] && getIPblock() != '127.0.0.1' && getIPblock() != '' && getIPblock() != '91.228.154.180' ) {
	die(getIPblock().'<br>'.$_SERVER['SERVER_ADDR']);
}

/* Баба маня */
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
		$qtext = str_replace('с','c',$qtext);
		$qtext = str_replace('х','x',$qtext);
		$qtext = str_replace('у','y',$qtext);
		$qtext = str_replace('е','e',$qtext);
		$qtext = str_replace('о','o',$qtext);
		$qtext = str_replace('р','p',$qtext);
		$qtext = str_replace('и','u',$qtext);
		mysql_query('UPDATE `users` SET `online` = "'.time().'" WHERE `id` = 1003553 LIMIT 1');
		send_chat(1,'Баба Маня','<font color=#cb0000><b>Внимание!</b> Проводится викторина! Первому, кто правильно ответит на следующий вопрос будет выплачен денежный приз. Время ожидания ответа '.$msec.' секунд. Ваши ответы пишите в чат <b>to [Баба Маня]</b>. Внимание, вопрос: <b>'.$qtext.'</b></font>',time());
		mysql_query('INSERT INTO `aaa_many_now` (`qid`,`time`) VALUES ("'.$lmv['id'].'","'.(time()+$msec).'")');
		send_chat(1,'Баба Маня','<font color=red>Никто не угадал! Правильный ответ: <b>'.$atext.'</b></font>.',-(time()+$msec));
	}
}

many_start();
?>