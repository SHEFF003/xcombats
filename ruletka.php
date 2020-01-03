<?php

define('GAME',true);
include_once('_incl_data/__config.php');
include_once('_incl_data/class/__db_connect.php');
include_once('_incl_data/class/__user.php');

if(!isset($u->bank['id'])) {
	die();	
}

	if($u->info['admin'] == 0) {
		//die('');
	}

	mysql_query("LOCK TABLES
	`actions` WRITE,
	`bank` WRITE,
	
	`users` WRITE,
	`users_delo` WRITE,
	
	`chat` WRITE,
	
	`stats` WRITE,
	
	`ruletka` WRITE,
	`ruletka_coin` WRITE,
	
	`bandit` WRITE;");


	/*
	33|2-0-0-0-2-0,
	32|4-0-0-0-4-6,
	11|4-0-0-42-4-0,
	21|2-0-0-0-2-0,
	2-4-6-8-10-11-13-15-17-20-22-24-26-28-29-31-33-35|8-0-8-0-0-0,
	7|4-0-0-0-4-0,
	2|4-0-0-0-4-0,
	17|4-0-0-4-4-0,
	1|6-0-0-0-0-6,
	18|4-0-0-0-4-0,
	30|4-0-0-6-4-6,
	16|4-0-0-0-4-0,
	13|4-0-0-0-4-0,
	25|6-0-0-0-0-6,
	27|2-0-0-0-2-0,
	36|2-0-0-0-2-0,
	3|4-0-0-0-4-0,
	20|4-0-0-0-4-0,
	8|8-0-0-16-4-0,
	38|2-0-0-0-2-6,
	4|8-0-0-16-0-0,
	34|4-0-0-0-4-0,
	37|1-0-0-0-4-7,
	19|2-0-0-0-2-0,
	10|4-0-0-10-2-0,
	31|6-0-0-6-4-0
	*/

if($u->room['name']!='Рулетка')
{
	die();
}else{
	
	//Раздаем выйгрыши
	
	
	function get2str($key='', $val='') {
	  $get = $_GET;
	  if ( is_array($key) ) {
		if ( count($key)>0 ) foreach ( $key as $k=>$v ) $get[$k] = $v;
	  } else $get[$key] = $val;
	  if ( count($get)>0 ) {
		foreach ( $get as $k=>$v ) if ( empty($v) ) unset($get[$k]);
	  }
	  if ( count($get)>0 ) {
		foreach ( $get as $k=>$v ) $get[$k] = $k.'='.urlencode($v);
		return '?'.implode('&', $get);
	  }
	}

	$r = '';
	$time = 74; //сек до новой игры
	$status = 0; //статус игры, 2 - крутим колесо
	$win = array(
		0 => 0, //число выйгрыша
		1 => '', //линии выйгрыша
		2 => 0,  //сумма выйгрыша
		3 => 0,  //ставка на эту игру текущего игрока
		//ставки
		4 => '',
		//игроки которые делали ставки
		5 => ''
	);
	

	//Выделяем текущую игру, если её нет, то создаем новую
	$gid = mysql_fetch_array(mysql_query('SELECT * FROM `ruletka` WHERE (`end` = 0 OR `id` = "'.((int)$_GET['id']).'") ORDER BY `id` DESC LIMIT 1'));
	if($gid['time_start']+16>time() || isset($_GET['bet']))
	{
		$mnr = true;
	}
	$gid3 = mysql_fetch_array(mysql_query('SELECT * FROM `ruletka` WHERE `end` >0 AND `id` = "'.((int)$_GET['id']).'" ORDER BY `id` DESC LIMIT 1'));
	if(isset($gid3['id']))
	{
		$gid = $gid3;
		unset($gid3);
	}
	$add = false;
	if(isset($gid['id']))
	{
		//Игра существует, проверяем
		$time = $gid['time_start']-time();
		if($time<1)
		{
			//крутим колесо и заканчиваем игру + выдаем выйгрыш
			//mysql_query('UPDATE `ruletka` SET `end` = "'.time().'" WHERE `id` = "'.$gid['id'].'" LIMIT 1');
			//выводим предыдущий выйгрыш
			$win[0] = $gid['win'];
			$win[1] = $gid['win_line'];
			$win[2] = 0;
			$win[3] = 0;
			if($gid['end']==0)
			{
				$add = true;
			}
		}else{
			//ожидаем начала игры, делаем ставки
			if(isset($_GET['bet']))
			{
				$bt = $_GET['bet'];
				$good = 0;
				$i = 0;
				while($i<=38)
				{
					if($i==$bt)
					{
						$good++;
					}
					$i++;
				}
				
	/*
	Ставки и значения
	
	1, ... ,36 - ставка на числа [x8]
	
	2-4-6-8-10-11-13-15-17-20-22-24-26-28-29-31-33-35 - черное  [x2]
	1-3-5-7-9-12-14-16-18-19-21-23-25-27-30-32-34-36  - красное [x2]
	
	37 - два нуля [x36]
	38 - ноль     [x36]
	
	1-2-3-37-38 - потолок [x5]
	
	1-2-3-4-5-6-7-8-9-10-11-12          - 1 сектор [x3]
	13-14-15-16-17-18-19-20-21-22-23-24 - 2 сектор [x3]
	25-26-27-28-29-30-31-32-33-34-35-36 - 3 сектор [x3]
	
	*/
				
				if($bt == '' && $good == 0 ) {
					$good++;
				}elseif($bt == '' && $good == 0 ) {
					$good++;
				}elseif($bt == '' && $good == 0 ) {
					$good++;
				}elseif($bt == '' && $good == 0 ) {
					$good++;
				}elseif($bt == '' && $good == 0 ) {
					$good++;
				}elseif($bt == '' && $good == 0 ) {
					$good++;
				}elseif($bt == '' && $good == 0 ) {
					$good++;
				}elseif($bt == '' && $good == 0 ) {
					$good++;
				}elseif($bt == '' && $good == 0 ) {
					$good++;
				}elseif($bt == '' && $good == 0 ) {
					$good++;
				}elseif($bt == '' && $good == 0 ) {
					$good++;
				}elseif($bt == '' && $good == 0 ) {
					$good++;
				}elseif($bt == '' && $good == 0 ) {
					$good++;
				}elseif($bt == '' && $good == 0 ) {
					$good++;
				}elseif($bt == '' && $good == 0 ) {
					$good++;
				}elseif($bt == '' && $good == 0 ) {
					$good++;
				}elseif($bt == '' && $good == 0 ) {
					$good++;
				}elseif($bt == '' && $good == 0 ) {
					$good++;
				}elseif($bt == '35-36' && $good == 0 ) {
					$good++;
				}elseif($bt == '34-35' && $good == 0 ) {
					$good++;
				}elseif($bt == '32-33' && $good == 0 ) {
					$good++;
				}elseif($bt == '31-32' && $good == 0 ) {
					$good++;
				}elseif($bt == '29-30' && $good == 0 ) {
					$good++;
				}elseif($bt == '28-29' && $good == 0 ) {
					$good++;
				}elseif($bt == '26-27' && $good == 0 ) {
					$good++;
				}elseif($bt == '25-26' && $good == 0 ) {
					$good++;
				}elseif($bt == '23-24' && $good == 0 ) {
					$good++;
				}elseif($bt == '22-23' && $good == 0 ) {
					$good++;
				}elseif($bt == '20-21' && $good == 0 ) {
					$good++;
				}elseif($bt == '19-20' && $good == 0 ) {
					$good++;
				}elseif($bt == '17-18' && $good == 0 ) {
					$good++;
				}elseif($bt == '16-17' && $good == 0 ) {
					$good++;
				}elseif($bt == '14-15' && $good == 0 ) {
					$good++;
				}elseif($bt == '13-14' && $good == 0 ) {
					$good++;
				}elseif($bt == '11-12' && $good == 0 ) {
					$good++;
				}elseif($bt == '10-11' && $good == 0 ) {
					$good++;
				}elseif($bt == '8-9' && $good == 0 ) {
					$good++;
				}elseif($bt == '7-8' && $good == 0 ) {
					$good++;
				}elseif($bt == '5-6' && $good == 0 ) {
					$good++;
				}elseif($bt == '4-5' && $good == 0 ) {
					$good++;
				}elseif($bt == '2-3' && $good == 0 ) {
					$good++;
				}elseif($bt == '1-2' && $good == 0 ) {
					$good++;
				}elseif($bt == '32-33-35-36' && $good == 0 ) {
					$good++;
				}elseif($bt == '31-32-34-35' && $good == 0 ) {
					$good++;
				}elseif($bt == '29-30-32-33' && $good == 0 ) {
					$good++;
				}elseif($bt == '28-29-31-32' && $good == 0 ) {
					$good++;
				}elseif($bt == '26-27-29-30' && $good == 0 ) {
					$good++;
				}elseif($bt == '25-26-28-29' && $good == 0 ) {
					$good++;
				}elseif($bt == '23-24-26-27' && $good == 0 ) {
					$good++;
				}elseif($bt == '22-23-25-26' && $good == 0 ) {
					$good++;
				}elseif($bt == '20-21-23-24' && $good == 0 ) {
					$good++;
				}elseif($bt == '19-20-22-23' && $good == 0 ) {
					$good++;
				}elseif($bt == '17-18-20-21' && $good == 0 ) {
					$good++;
				}elseif($bt == '16-17-19-20' && $good == 0 ) {
					$good++;
				}elseif($bt == '14-15-17-18' && $good == 0 ) {
					$good++;
				}elseif($bt == '13-14-16-17' && $good == 0 ) {
					$good++;
				}elseif($bt == '11-12-14-15' && $good == 0 ) {
					$good++;
				}elseif($bt == '10-11-13-14' && $good == 0 ) {
					$good++;
				}elseif($bt == '8-9-11-12' && $good == 0 ) {
					$good++;
				}elseif($bt == '7-8-10-11' && $good == 0 ) {
					$good++;
				}elseif($bt == '5-6-8-9' && $good == 0 ) {
					$good++;
				}elseif($bt == '4-5-7-8' && $good == 0 ) {
					$good++;
				}elseif($bt == '2-3-5-6' && $good == 0 ) {
					$good++;
				}elseif($bt == '1-2-4-5' && $good == 0 ) {
					$good++;
				}elseif($bt == '2-3-37' && $good == 0 ) {
					$good++;
				}elseif($bt == '1-2-38' && $good == 0 ) {
					$good++;
				}elseif($bt == '38-1' && $good == 0 ) {
					$good++;
				}elseif($bt == '2-4-6-8-10-12-14-16-18-20-22-24-26-28-30-32-34-36' && $good == 0 ) {
					$good++;
				}elseif($bt == '1-3-5-7-9-11-13-15-17-19-21-23-25-27-29-31-33-35' && $good == 0 ) {
					$good++;
				}elseif($bt == '19-20-21-22-23-24-25-26-27-28-29-30-31-32-33-34-35-36' && $good == 0 ) {
					$good++;
				}elseif($bt == '1-2-3-4-5-6-7-8-9-10-11-12-13-14-15-16-17-18' && $good == 0 ) {
					$good++;
				}elseif($bt == '28-29-30-31-32-33' && $good == 0 ) {
					$good++;
				}elseif($bt == '22-23-24-25-26-27' && $good == 0 ) {
					$good++;
				}elseif($bt == '16-17-18-19-20-21' && $good == 0 ) {
					$good++;
				}elseif($bt == '10-11-12-13-14-15' && $good == 0 ) {
					$good++;
				}elseif($bt == '4-5-6-7-8-9' && $good == 0 ) {
					$good++;
				}elseif($bt == '31-32-33-34-35-36' && $good == 0 ) {
					$good++;
				}elseif($bt == '25-26-27-28-29-30' && $good == 0 ) {
					$good++;
				}elseif($bt == '19-20-21-22-23-24' && $good == 0 ) {
					$good++;
				}elseif($bt == '13-14-15-16-17-18' && $good == 0 ) {
					$good++;
				}elseif($bt == '7-8-9-10-11-12' && $good == 0 ) {
					$good++;
				}elseif($bt == '1-2-3-4-5-6' && $good == 0 ) {
					$good++;
				}elseif($bt == '34-35-36' && $good == 0 ) {
					$good++;
				}elseif($bt == '31-32-33' && $good == 0 ) {
					$good++;
				}elseif($bt == '28-29-30' && $good == 0 ) {
					$good++;
				}elseif($bt == '25-26-27' && $good == 0 ) {
					$good++;
				}elseif($bt == '22-23-24' && $good == 0 ) {
					$good++;
				}elseif($bt == '19-20-21' && $good == 0 ) {
					$good++;
				}elseif($bt == '16-17-18' && $good == 0 ) {
					$good++;
				}elseif($bt == '13-14-15' && $good == 0 ) {
					$good++;
				}elseif($bt == '10-11-12' && $good == 0 ) {
					$good++;
				}elseif($bt == '7-8-9' && $good == 0 ) {
					$good++;
				}elseif($bt == '6-5-4' && $good == 0 ) {
					$good++;
				}elseif($bt == '1-2-3' && $good == 0 ) {
					$good++;
				}elseif($bt == '3-6-9-12-15-18-21-24-27-30-33-36' && $good == 0 ) {
					$good++;
				}elseif($bt == '2-5-8-11-14-17-20-23-26-29-32-35' && $good == 0 ) {
					$good++;
				}elseif($bt == '1-4-7-10-13-16-19-22-25-28-31-34' && $good == 0 ) {
					$good++;
				}elseif($bt == '2-4-6-8-10-11-13-15-17-20-22-24-26-28-29-31-33-35' && $good==0)
				{
					$good++;
				}elseif($bt == '1-3-5-7-9-12-14-16-18-19-21-23-25-27-30-32-34-36' && $good==0)
				{
					$good++;
				}elseif($bt == '1-2-3-37-38' && $good==0)
				{
					$good++;
				}elseif($bt == '1-2-3-4-5-6-7-8-9-10-11-12' && $good==0)
				{
					$good++;
				}elseif($bt == '13-14-15-16-17-18-19-20-21-22-23-24' && $good==0)
				{
					$good++;
				}elseif($bt == '25-26-27-28-29-30-31-32-33-34-35-36' && $good==0)
				{
					$good++;
				}elseif($bt == '1-2-4-5' && $good==0)
				{
					$good++;
				}
				$_GET['coin'] = (int)$_GET['coin'];
				if($_GET['coin']<1)
				{
					$good = 0;
				}
				if($_GET['coin']>$u->bank['money2'])
				{
					$good = 0;
				}
				if($good==1)
				{
					$u->bank['money2'] -= ((int)$_GET['coin']);
					
					$stvka = '';
					
					if($stvka == '') {
						$stvka = '<i>неизвестная зона ставки</i>';
					}
					
					
					mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$u->info['city']."','".$u->info['room']."','','','<small>[<b><font color=0066ff>Крупье</font></b>] Игрок <b>".mysql_real_escape_string($u->info['login'])."</b> сделал ставку: ".(0+((int)$_GET['coin'])).".00 кр. на ".$stvka.", игра №".$gid['id']."</small>','".time()."','6','0')");
					
					
					mysql_query('UPDATE `bank` SET `money2` = '.$u->bank['money2'].' WHERE `id` = "'.$u->bank['id'].'" LIMIT 1');
					mysql_query('INSERT INTO `ruletka_coin` (`uid`,`login`,`money`,`time`,`game_id`,`win2`) VALUES ("'.$u->info['id'].'","'.$u->info['login'].'","'.$_GET['coin'].'","'.time().'","'.$gid['id'].'","'.$bt.'")');
				}
			}
		}		
	}else{
		$add = true;
	}
	
			//обновляем ставки
			$pos = array(
				0  => '2-4-6-8-10-11-13-15-17-20-22-24-26-28-29-31-33-35',
				1  => '1-3-5-7-9-12-14-16-18-19-21-23-25-27-30-32-34-36',
				2  => '1-2-3-37-38',
				3  => '1-2-3-4-5-6-7-8-9-10-11-12',
				4  => '13-14-15-16-17-18-19-20-21-22-23-24',
				5  => '25-26-27-28-29-30-31-32-33-34-35-36',
				6  => '1-4-7-10-13-16-19-22-25-28-31-34',
				7  => '2-5-8-11-14-17-20-23-26-29-32-35',
				8  => '3-6-9-12-15-18-21-24-27-30-33-36',
				9  => '1-2-3',
				10 => '6-5-4',
				11 => '7-8-9',
				12 => '10-11-12',
				13 => '13-14-15',
				14 => '16-17-18',
				15 => '19-20-21',
				16 => '22-23-24',
				17 => '25-26-27',
				18 => '28-29-30',
				19 => '31-32-33',
				20 => '34-35-36',
				21 => '1-2-3-4-5-6',
				22 => '7-8-9-10-11-12',
				23 => '13-14-15-16-17-18',
				24 => '19-20-21-22-23-24',
				25 => '25-26-27-28-29-30',
				26 => '31-32-33-34-35-36',
				27 => '4-5-6-7-8-9',
				28 => '10-11-12-13-14-15',
				29 => '16-17-18-19-20-21',
				30 => '22-23-24-25-26-27',
				31 => '28-29-30-31-32-33',
				32 => '1-2-3-4-5-6-7-8-9-10-11-12-13-14-15-16-17-18',
				33 => '19-20-21-22-23-24-25-26-27-28-29-30-31-32-33-34-35-36',
				34 => '1-3-5-7-9-11-13-15-17-19-21-23-25-27-29-31-33-35',
				35 => '2-4-6-8-10-12-14-16-18-20-22-24-26-28-30-32-34-36',
				36 => '38-1',
				37 => '1-2-38',
				38 => '2-3-37',
				39 => '32-33-35-36',
				40 => '31-32-34-35',
				41 => '29-30-32-33',
				42 => '28-29-31-32',
				43 => '26-27-29-30',
				44 => '25-26-28-29',
				45 => '23-24-26-27',
				46 => '22-23-25-26',
				47 => '20-21-23-24',
				48 => '19-20-22-23',
				49 => '17-18-20-21',
				50 => '16-17-19-20',
				51 => '14-15-17-18',
				52 => '13-14-16-17',
				53 => '11-12-14-15',
				54 => '10-11-13-14',
				55 => '8-9-11-12',
				56 => '7-8-10-11',
				57 => '5-6-8-9',
				58 => '4-5-7-8',
				59 => '2-3-5-6',
				60 => '1-2-4-5',
				61 => '1-2',
				62 => '2-3',
				63 => '4-5',
				64 => '5-6',
				65 => '7-8',
				66 => '8-9',
				67 => '10-11',
				68 => '11-12',
				69 => '13-14',
				70 => '14-15',
				71 => '16-17',
				72 => '17-18',
				73 => '19-20',
				74 => '20-21',
				75 => '22-23',
				76 => '23-24',
				77 => '25-26',
				78 => '26-27',
				79 => '28-29',
				80 => '29-30',
				81 => '31-32',
				82 => '32-33',
				83 => '34-35',
				84 => '35-36'
			);
			
			function testCoin($s,$stt)
			{
				global $u,$win;
				$sp = mysql_query('SELECT * FROM `ruletka_coin` WHERE `game_id` = "'.$s.'" AND `money` > 0 AND `win2` = "'.$stt.'" AND `uid` != "'.$u->info['id'].'"');
				$cr = 0; $am = 0;
				$usr = ''; $lu = array();
				while($pl = mysql_fetch_array($sp))
				{
					$cr = $pl['money'];
					if(!isset($lu[$pl['uid']]) && count($lu)<4)
					{
						$usr .= '-'.$pl['money'];
						$lu[$pl['uid']] = true;
					}
					$am++;
				}
				$us = 0; //Ставка игрока
				$sp = mysql_query('SELECT * FROM `ruletka_coin` WHERE `game_id` = "'.$s.'" AND `money` > 0 AND `win2` = "'.$stt.'" AND `uid` = "'.$u->info['id'].'" LIMIT 100');
				while($pl = mysql_fetch_array($sp))
				{
					$cr = $pl['money'];
					$us += $pl['money'];
					$am++;
				}
				$i = 0;
				while($i<4)
				{
					if($i > count($lu))
					{
						$usr .= '-0';
					}
					$i++;
				}
				if($am>0)
				{
					$win[4] .= $stt.'|'.$cr.'-'.$us.''.$usr.',';				
				}
			}
			
			//ставки на числа
			$i = 1;
			while($i<=38)
			{
				testCoin($gid['id'],$i);
				$i++;
			}
			//комбинированные ставки
			$i = 0;
			while($i<count($pos))
			{
				testCoin($gid['id'],$pos[$i]);
				$i++;
			}
	
	if($add==true)
	{
		//создаем новую игру
		$gid2 = array('id'=>0,'room'=>$u->info['room'],'time'=>time(),'time_start'=>time()+74,'win'=>floor(rand(100,3800)/100),'win_line'=>'','end'=>0);
		$s37 = mysql_fetch_array(mysql_query('SELECT `id` FROM `ruletka` WHERE `win` = "37" OR `win` = "38" AND `time` > "'.(time()-round(3600/10000*rand(5000,10000))).'" LIMIT 1'));
		if(isset($s37['id'])) {
			$gid2['win'] = floor(rand(100,3600)/100);
		}
		$ins = mysql_query('INSERT INTO `ruletka` (`room`,`time`,`time_start`,`win`,`win_line`) VALUES ("'.$gid2['room'].'","'.$gid2['time'].'","'.$gid2['time_start'].'","'.$gid2['win'].'","'.$gid2['win_line'].'")');
		$gid2['id'] = mysql_insert_id();
		if($ins)
		{
			$gid = $gid2;
		}
		unset($gid2);
	}
	
		$u_w = array(0=>array(),1=>array(),2=>array(),3=>array());
	
				$sm = $u->testAction('`city` = "'.$u->info['city'].'" AND `vars` = "casino_balance" LIMIT 1',1);
				if(!isset($sm['id'])) {			
					$u->addAction(time(),'casino_balance',0);
				}
	
		//обновляем выйгрыши
		$sp = mysql_query('SELECT * FROM `ruletka` WHERE `end` = "0" AND `time_start` <= '.time().'');
		while($pl = mysql_fetch_array($sp))
		{			
			/*$pl['win'] = floor(rand(10000000,360000000)/10000000);
			if( rand(0,1) == 1 ) {
				$pl['win'] = floor(rand(10000000,360000000)/10000000);
			}
			if( rand(0,1) == 1 ) {
				$pl['win'] = floor(rand(10000000,360000000)/10000000);
			}*/
			$end = mysql_query('UPDATE `ruletka` SET `end` = "'.time().'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');			
			if($end)
			{
				//выдаем выйгрышь
				$sp2 = mysql_query('SELECT * FROM `ruletka_coin` WHERE `end` = "0" AND `game_id` = "'.$pl['id'].'"');
				while($pl2 = mysql_fetch_array($sp2))
				{
					$upd = mysql_query('UPDATE `ruletka_coin` SET `end` = "'.time().'" WHERE `id` = "'.$pl2['id'].'" LIMIT 1');
					if($upd)
					{
						$wn = 0; $wn2 = 0;					
						$xv = 2;
						if($pl2['win2']==$pl['win'])
						{
							$wn++;
						}else{
							$i = 0; $j = explode('-',$pl2['win2']);
							$xv = floor(1+34/count($j));
							while($i<count($j))
							{
								if($j[$i]==$pl['win'])
								{
									$wn2++;
								}
								$i++;
							}
						}
												
						if($wn>0)
						{
							//перечисляем деньги [x8], если зеро то [x36]
							if($pl['win']>36)
							{
								$nmn = ((int)$pl2['money']*35);
							}else{
								$nmn = ((int)$pl2['money']*35);
							}
							mysql_query('UPDATE `bank` SET `money2` = `money2` + "'.$nmn.'" WHERE `uid` = "'.$pl2['uid'].'" ORDER BY `useNow` DESC LIMIT 1');
						}elseif($wn2>0)
						{
							//перечисляем деньги по определенной формуле
							$nmn = ((int)$pl2['money']*$xv);
							mysql_query('UPDATE `bank` SET `money2` = `money2` + "'.$nmn.'" WHERE `uid` = "'.$pl2['uid'].'" ORDER BY `useNow` DESC LIMIT 1');
						}else{
							//проиграли
							mysql_query('UPDATE `ruletka_coin` SET `end` = "1" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
							mysql_query('UPDATE `actions` SET `vals` = `vals` + "'.mysql_real_escape_string(0+$pl2['money']).'" WHERE `id` = "'.$sm['id'].'" LIMIT 1');
						}
						
						if($wn > 0 || $wn2 > 0) {
							$u->addDelo(1,$pl2['uid'],'&quot;<font color=red>Casino.'.$u->info['city'].'</font>&quot;: Выиграл '.$nmn.' екр.',time(),$u->info['city'],'Casino.'.$u->info['city'].'',0,0);
							if(!isset($u_w[1][$pl2['uid']])) {
								$u_w[0][count($u_w[0])] = $pl2['uid'];
								$u_w[2][count($u_w[0])-1] = $pl2['game_id'];
							}
							$u_w[1][$pl2['uid']] += (int)$nmn;
						}
						$u_w[3][$pl2['uid']] += (int)$pl2['money'];
						
					}
				}
			}
		}
		
		if(count($u_w[0]) > 0) {
			//mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$pl['city']."','225','Крупье','','Игрок <b>".$pl['login']."</b> выиграл <b>".$nmn.".00</b> кр.','".time()."','6','0')");
			$i = 0;
			while($i < count($u_w[0])) {
				if($u_w[1][$u_w[0][$i]] > 0) {
				$infu = mysql_fetch_array(mysql_query('SELECT 
				`u`.`id`,
				`u`.`align`,
				`u`.`login`,
				`u`.`clan`,
				`u`.`level`,
				`u`.`city`,
				`u`.`online`,
				`u`.`sex`,
				`u`.`cityreg`
				FROM `users` AS `u` WHERE `u`.`id`="'.mysql_real_escape_string($u_w[0][$i]).'" LIMIT 1'));
			
					mysql_query('UPDATE `actions` SET `vals` = `vals` + "'.mysql_real_escape_string((0+$u_w[3][$u_w[0][$i]])-$u_w[1][$u_w[0][$i]]).'" WHERE `id` = "'.$sm['id'].'" LIMIT 1');
					mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$u->info['city']."','389','','','<small>[<b><font color=0066ff>Крупье</font></b>] Игрок <b>".mysql_real_escape_string($infu['login'])."</b> выиграл <b>".$u_w[1][$u_w[0][$i]].".00</b> екр., ставка: ".(0+$u_w[3][$u_w[0][$i]]).".00 екр., игра №".$u_w[2][$i]."</small>','".time()."','6','0')");
				}
				$i++;
			}
		}
		
	if(isset($gid['id']))
	{
		if($time<0)
		{
			$time = 0;
		}
		//Выбираем статус игры
		if($time>0)
		{
			//делаем ставки
			$status = 1;
			$sp = mysql_query('SELECT * FROM `ruletka_coin` WHERE `end` = "0" AND `uid` = "'.$u->info['id'].'" AND `game_id` = "'.$gid['id'].'"');
			$win[3] = 0;
			while($pl = mysql_fetch_array($sp))
			{
				$win[3] += $pl['money'];
			}
		}else{
			//играем
			$status = 2;
			//выводим выйгрыш + ставку
			$sp = mysql_query('SELECT * FROM `ruletka_coin` WHERE `end` > "0" AND `uid` = "'.$u->info['id'].'" AND `game_id` = "'.$gid['id'].'"');
			$win[2] = 0;
			while($pl = mysql_fetch_array($sp))
			{
				$win[2] += $pl['money'];
				$win[3] += $pl['money'];
				$win[1] += $pl['money'];
			}
		}
		if($win[0]>0)
		{
			unset($mnr);
		}
		if(isset($mnr))
		{
			$mnr = '&cash='.floor(0+$u->bank['money2']);
		}
		$r = 'time='.$time.'&game='.$gid['id'].''.$mnr.'&betsum='.$win[3].'&status='.$status.'&players='.$win[5].'&bets='.$win[4].'&win='.$win[0].'&wbets='.$win[1].'&wmoney='.$win[2].'';
		echo $r;
	}
}
mysql_query('UNLOCK TABLES');
?>