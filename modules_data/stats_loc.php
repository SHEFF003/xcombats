<?php
if(!defined('GAME'))
{
	die();
}
?>
<span style="font-size:12px;">
Сила: <? echo $u->stats['s1']; ?><br />
Ловкость:&nbsp;<? echo $u->stats['s2']; ?><br />
Интуиция:&nbsp;<? echo $u->stats['s3']; ?><br />
Выносливость:&nbsp;<? echo $u->stats['s4']; ?><br />
<? if($u->info['level'] > 3 || $u->stats['s5']!=0){ ?>Интеллект:&nbsp;<? echo 0+$u->stats['s5']; ?><br /><? } ?>
<? if($u->info['level'] > 6 || $u->stats['s6']!=0){ ?>Мудрость:&nbsp;<? echo 0+$u->stats['s6']; ?><br /><? } ?>
<? if($u->info['level'] > 9 || $u->stats['s7']!=0){ ?>Духовность:&nbsp;<? echo 0+$u->stats['s7']; ?><br /><? } ?>
<? if($u->info['level'] > 13 || $u->stats['s8']!=0){ ?>Воля:&nbsp;<? echo 0+$u->stats['s8']; ?><br /><? } ?>
<? if($u->info['level'] > 14 || $u->stats['s9']!=0){ ?>Свобода духа:&nbsp;<? echo 0+$u->stats['s9']; ?><br /><? } ?>
<? if($u->info['level'] > 19 || $u->stats['s10']!=0){ ?>Божественный:&nbsp;<? echo 0+$u->stats['s10']; ?><br /><? } ?>
<?
/*
Энергия:&nbsp;<? echo 0+$u->stats['s11']; ?> &nbsp; <small>[<?=round($u->info['enNow'],3)?>/<?=$u->stats['enAll']?>]</small><br />
*/
if($u->info['ability'] > 0) 
{ 
echo '<a href="main.php?skills=1&side=1">+ Способности</a><br />'; 
}
if($u->info['skills'] > 0 && $u->info['level'] > 0)
{ 
echo '&bull;&nbsp;<a href="main.php?skills=1&side=1">Обучение</a><br />'; 
} 
	$expbase = number_format($u->stats['levels']['exp'], 0, ",", " ");
	if( $expbase-1 == $u->info['exp'] && $c['nolevel'] == true ) {
		//Проверяем блок опыта
		$tlus = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `users` WHERE `real` = 1 AND `level` = "'.$u->info['level'].'" LIMIT 1'));
		if($tlus[0] < $u->info['level']*10) {
			$expbase = '<strike>'.$expbase.'</strike>';
		}
		$expbase = '(<a href="http://xcombats.com/exp.php" target="_blank">'.$expbase.'</a>)';
		if(((1+$u->info['level']*10)-$tlus[0]) > 0) {
			$expbase .= ' <u><a onMouseOver="top.hi(this,\'<b>Персонажи '.$u->info['level'].' уровня не получают опыт.</b><br>Для разблокировки не хватает <u>'.((1+$u->info['level']*10)-$tlus[0]).' персонажей</u> данного уровня.\',event,0,1,1,1,\'\');" onMouseOut="top.hic();" onMouseDown="top.hic();" style="cursor:help">[?]</a></u>';
		}
	}else{
		$expbase = '(<a href="http://xcombats.com/exp.php" target="_blank">'.$expbase.'</a>)';
	}
	$trks5 = '';
	if( $u->info['exp_eco'] > 0 ) {
		if( $u->info['exp_eco'] > 17500 ) {
			$u->info['exp_eco'] = 17500;
		}
		$trks5 = '<font color="navy">Замороженный опыт: <b>'.number_format($u->info['exp_eco'], 0, ",", " ").'</b></font><br>';
	}
?>
&nbsp;<br />
Опыт:&nbsp;<b><? echo number_format($u->info['exp'], 0, ",", " "); ?></b> <?=$expbase?><br />
<?=$trks5?>
Уровень:&nbsp;<? echo $u->info['level']; ?><br />
Побед:&nbsp;<? echo number_format($u->info['win'], 0, ",", " "); ?><br />
Поражений:&nbsp;<? echo number_format($u->info['lose'], 0, ",", " "); ?><br />
Ничьих:&nbsp;<? echo number_format($u->info['nich'], 0, ",", " "); ?><br />
<!--Серия побед:&nbsp;<?=(0+$u->info['swin'])?><br>
Серия поражений:&nbsp;<?=(0+$u->info['slose'])?><br>-->
Деньги:&nbsp;<img src="/coin1.png" height="11">&nbsp;<b><? echo $u->info['money']; ?></b>&nbsp;кр.<br />
<span style="padding-left:42px;">&nbsp;</span> &nbsp;<img src="/coin2.png" height="11">&nbsp;<b><font color=green><? echo $u->bank['money2']; ?></b>&nbsp;екр.</font><br />
Воинственность:&nbsp;<img src="/voins.png" height="11">&nbsp;<b><? echo $u->info['money3']; ?></b>&nbsp;ед.<br />
<? if($u->info['level'] < 8 && $c['zuby'] == true) { ?>
Зубы:&nbsp;<small><?=$u->zuby($u->info['money4'])?></small><br /><? } ?>
<? if($u->info['level'] > 3) { ?>
Передач:&nbsp;<?=$u->info['transfers']?><br /><? } ?></span>
<?
/*if( $u->info['level'] > 0 && $c['bonusonline'] == true ) {
	$bns = mysql_fetch_array(mysql_query('SELECT `id`,`time` FROM `aaa_bonus` WHERE `uid` = "'.$u->info['id'].'" AND `time` > '.time().' LIMIT 1'));
	if(isset($_GET['takebns']) && $u->newAct($_GET['takebns'])==true && !isset($bns['id'])) {
		$u->takeBonus();
		$bns = mysql_fetch_array(mysql_query('SELECT `id`,`time` FROM `aaa_bonus` WHERE `uid` = "'.$u->info['id'].'" AND `time` > '.time().' LIMIT 1'));
	}
	if(isset($bns['id'])) {
		echo '<button style="width:164px;margin-top:5px;" onclick="alert(\'Вы сможете взять бонус через '.$u->timeOut($bns['time']-time()).'\');" class="btnnew"> Через '.$u->timeOut($bns['time']-time()).' </button>';
	}else{
		//echo '<small>Получить бонус:</small><br>';
		//echo '<div align="left"><button class="btnnew" onclick="location.href=\'main.php?takebns='.$u->info['nextAct'].'\';">25 кр.!</button>';
		//echo '<button class="btnnew" onclick="location.href=\'main.php?takebns='.$u->info['nextAct'].'&getb1w=2\';">'.($u->info['level']*3).' Воин.!</button>';
		
		//echo '<button style="width:164px;margin-top:5px;" class="btnnew" onclick="top.captcha(\'Бонус за онлайн\',\'main.php?takebns='.$u->info['nextAct'].'&getb1w=3\')">Получить '.$u->info['level'].' кр.</button></div>';
		//if( $u->info['level'] == 7 ) {
		//	echo '<button style="width:164px;margin-top:5px;" class="btnnew" onclick="top.captcha(\'Бонус за онлайн\',\'main.php?takebns='.$u->info['nextAct'].'&getb1w=3\')">Получить '.$u->zuby(round($u->info['level']*0.75,2),1).'</button></div>';
		//}elseif( $u->info['level'] >= 8 ) {
			echo '<button style="width:164px;margin-top:5px;" class="btnnew" onclick="top.captcha(\'Бонус за онлайн\',\'main.php?takebns='.$u->info['nextAct'].'&getb1w=3\')">Получить '.round($u->info['level']*$c['bonusonline_kof'],2).' кр.</button>';
		//}else{
		//	$expad = round(50+($u->stats['levels']['exp']-$u->info['exp'])/2);
		//	if( $expad > 100 ) {
		//		$expad = 100;
		//	}
		//	echo '<button style="width:164px;margin-top:5px;" class="btnnew" onclick="top.captcha(\'Бонус за онлайн\',\'main.php?takebns='.$u->info['nextAct'].'&getb1w=3\')">Получить '.$expad.' опыта</button></div>';
		//}
	}
	echo '<button onclick="location.href=\'pay.php\';" style="width:164px;margin-top:5px;" class="btnnew">Покупка екр. онлайн</button>';
	echo '</div>';
}*/


//Ежедневное задание
if( $u->info['real'] > 0 && $u->info['inTurnir'] == 0 && $u->info['inTurnirnew'] == 0 ) {
	
	$nods1 = false;
	
	$daily = mysql_fetch_array(mysql_query('SELECT * FROM `dailybonus` WHERE `uid` = "'.$u->info['id'].'" LIMIT 1'));
	
	if( $daily['day'] > 0 && date('d.m.Y',$daily['finish']) != date('d.m.Y') && date('d.m.Y',$daily['finish']) != date('d.m.Y',time()-86400) ) {
		
		mysql_query('UPDATE `dailybonus` SET
		`turnirgo` = 0 ,`turnirwin` = 0 ,`bsgo` = 0 , `remont` = 0 , `rune` = 0 , `gifts` = 0 ,
		`haot` = 0 , `winhaot` = 0 , `fight` = 0 , `winfight` = 0 , `usepriem` = 0 , `expopen` = 0 , `dun_go` = 0 , `dun_kill` = 0 , `dun_kill_mar` = 0 , `dun_res` = 0 
		 , `date_finish` = "0" , `finish` = "0" , `day` = "0" WHERE `id` = "'.$daily['id'].'" LIMIT 1');
		$daily = mysql_fetch_array(mysql_query('SELECT * FROM `dailybonus` WHERE `uid` = "'.$u->info['id'].'" LIMIT 1'));
		//
		$txtb1 = 'Вы прервали последовательность &quot;<b>Ежедневное Задание</b>&quot; и вам придется начать заново!';
		mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$u->info['city']."','".$u->info['room']."','','".$u->info['login']."','".$txtb1."','".time()."','6','0')");
		//
		
	}
	
	$dbs1 = 0; //% выполнения задания за сегодня
	$dbd1 = $daily['day']+1; //день задания
	//
	$dbsinfo1 = ''; //инфор
	$dbo1 = ''; //нажатие
	if(!isset($daily['id'])) {
		mysql_query('INSERT INTO `dailybonus` (`uid`) VALUES ("'.$u->info['id'].'")');
		$daily = mysql_fetch_array(mysql_query('SELECT * FROM `dailybonus` WHERE `uid` = "'.$u->info['id'].'" LIMIT 1'));
	}
	$ts = mysql_fetch_array(mysql_query('SELECT `time_all`,`time_today` FROM `online` WHERE `uid` = "'.$u->info['id'].'" LIMIT 1'));
	$ts = $ts['time_today'];
	
	$daynaga = array(
		0 => array( 0 , 0.10 ),
		1 => array( 0 , 0.15 ),
		2 => array( 0 , 0.20 ),
		3 => array( 0 , 0.25 ),
		4 => array( 0 , 0.30 ),
		5 => array( 0 , 0.35 ),
		6 => array( 0 , 0.40 )
	);
	
	if( $dbd1 <= 7 ) {
		
		$dtro = array();
		
		if( $dbd1 == 1 ) {
			/*
			1. Принять участие в 10 боях (хаотических/физических/групповых); 
			2. Одержать 5 побед в боях; 
			3. Пробыть в игре онлайн 3 часа. 		
			Награда - набор хилок ("малышам": на 60 НР - 1 шт, 45 НР - 3 шт, 30 НР - 3 шт; от 7 уровня: трехразовые хилки на 600 и 900 НР по 1 шт) + 0,1 екр. 
			*/			
			
			$dtro[0] = 2; // часов в онлайн
			$dtro[1] = 7; // провести хаотов
			$dtro[2] = 3; // побед в хаотах
			
			//Награда
			$daynaga[$dbd1] = array(
				10 , //кр
				0.20 , //екр
				array() //предметы
			);
			
			//Предметы кол-во , айди , износ макс , доп.параметры
			if( $u->info['level'] >= 8 ) {
				$daynaga[$dbd1][2][] = array( 1 , 2544 , 1 , '|notr=1|nosale=1|notransfer=1|sudba=1|srok=604800' );
				$daynaga[$dbd1][2][] = array( 2 , 2543 , 1 , '|notr=1|nosale=1|notransfer=1|sudba=1|srok=604800' );
				$daynaga[$dbd1][2][] = array( 3 , 2542 , 1 , '|notr=1|nosale=1|notransfer=1|sudba=1|srok=604800' );
			}elseif( $u->info['level'] == 7 ) {
				$daynaga[$dbd1][2][] = array( 1 , 4015 , 3 , '|notr=1|nosale=1|notransfer=1|sudba=1|srok=604800' );
			}else{
				$daynaga[$dbd1][2][] = array( 1 , 2711 , 3 , '|notr=1|nosale=1|notransfer=1|sudba=1|srok=604800' );
			}
			
		}elseif( $dbd1 == 2 ) {
			/*
			1. Принять участие в 20 боях (хаотических/физических/групповых); 
			2. Во время боя использовать 30 раз приемы с панели приемов; 
			3. Набрать 15 000 - 30 000 опыта в боях. 			
			Награда: 
			
			 Эликсир Восстановления - 1 бут., Эликсир Жизни - 3 шт, эликсир Неуязвимости и Малое зелье Отрицания (малышам) по 1 бут.,
			 Великое зелье Стойкости и Великое зелье Отрицания (для всех остальных) по 1 бут.; свиток лечение травм (малышам) или 
			 аптечку (тяжелые травмы) от 7-го уровня + 0,25 екр. 			
			*/			
			
			
			$dtro[0] = 0; // часов в онлайн
			$dtro[1] = 10; // провести хаотов
			$dtro[2] = 5; // побед в хаотах
			$dtro[3] = 150; // использовать приемы в бою
			if( $u->info['level'] < 7 ) {
				$dtro[4] = 10000; // набрать опыт в боях
			}else{
				$dtro[4] = 20000; // набрать опыт в боях
			}
			
			//Награда
			$daynaga[$dbd1] = array(
				0 , //кр
				0.25 , //екр
				array() //предметы
			);
			
			//Предметы кол-во , айди , износ макс , доп.параметры
			$daynaga[$dbd1][2][] = array( 1 , 2418 , 10 , '|notr=1|nosale=1|notransfer=1|sudba=1|srok=604800' );
			$daynaga[$dbd1][2][] = array( 1 , 4736 , 30 , '|notr=1|nosale=1|notransfer=1|sudba=1|srok=604800' );
			if( $u->info['level'] < 7 ) {
				// 0 - 6
				$daynaga[$dbd1][2][] = array( 1 , 887 , 3 , '|notr=1|nosale=1|notransfer=1|sudba=1|srok=604800' );
				$daynaga[$dbd1][2][] = array( 1 , 4730 , 3 , '|notr=1|nosale=1|notransfer=1|sudba=1|srok=604800' );
			}elseif( $u->info['level'] < 8 ) {
				// 7
				$daynaga[$dbd1][2][] = array( 1 , 1186 , 3 , '|notr=1|nosale=1|notransfer=1|sudba=1|srok=604800' );
				$daynaga[$dbd1][2][] = array( 1 , 188 , 3 , '|notr=1|nosale=1|notransfer=1|sudba=1|srok=604800' );
			}else{
				//8-21
				$daynaga[$dbd1][2][] = array( 1 , 2139 , 3 , '|notr=1|nosale=1|notransfer=1|sudba=1|srok=604800' );
				$daynaga[$dbd1][2][] = array( 1 , 2140 , 3 , '|notr=1|nosale=1|notransfer=1|sudba=1|srok=604800' );
			}
			
			if( $u->info['level'] > 7 ) {
				$daynaga[$dbd1][2][] = array( 1 , 4414 , 1 , '|notr=1|nosale=1|notransfer=1|sudba=1|srok=604800' );
			}else{
				$daynaga[$dbd1][2][] = array( 1 , 2390 , 5 , '|notr=1|nosale=1|notransfer=1|sudba=1|srok=604800' );
			}
			
		}elseif( $dbd1 == 3 ) {
			/*
			1. Посетить пещеры 3 (5) раза; 
			2. Убить в общей сложности 30 пещерных монстров (в т.ч. монстров из канализации - для малышей); 
			3. Собрать 30 - 50 пещерных ресурсов (в т.ч. болты/гайки/вентили - для малышей). 
			
			Награда: Пропуск забытых - 1 шт, сущность ресурса - 1 шт + 50 кр. 			
			*/			
			
			
			$dtro[0] = 0; // часов в онлайн
			$dtro[1] = 0; // провести хаотов
			$dtro[2] = 0; // побед в хаотах
			$dtro[3] = 0; // использовать приемы в бою
			$dtro[4] = 0; // набрать опыт в боях
			if( $u->info['level'] > 7 ) {
				$dtro[5] = 3; // походов в пещеры
				$dtro[6] = 250; // убить пещерных монстров
				$dtro[7] = 125; // собрать пещерных ресурсов
			}else{
				$dtro[5] = 3;
				$dtro[6] = 150; // убить пещерных монстров
				$dtro[7] = 75; // собрать пещерных ресурсов
			}
			
			//Награда
			$daynaga[$dbd1] = array(
				50 , //кр
				0 , //екр
				array() //предметы
			);
			
			//Предметы кол-во , айди , износ макс , доп.параметры
			$daynaga[$dbd1][2][] = array( 1 , 2412 , 3 , '|notr=1|nosale=1|notransfer=1|sudba=1|srok=604800' );
			$daynaga[$dbd1][2][] = array( 1 , 1035 , 30 , '' );
			
		}elseif( $dbd1 == 4 ) {
			/*
			1. Принять участие в 25 боях (хаотических/физических/групповых); 
			2. Убить в общей сложности 15 марочных пещерных монстров (в т.ч. монстров из канализации - для малышей); 
			3. Использовать 50 раз приемы с панели приемов. 
			
			Награда: свитки "Сокрушение" и "Защита от Оружия" (для тех, кто 7 и выше уровней, без требований к
			 интеллекту) по 1 шт, Зелье Жизни - 3 шт, Эликсир Жизни - 2 шт + 75 кр. + 0,50 екр. 		
			*/			
			
			
			$dtro[0] = 0; // часов в онлайн
			$dtro[1] = 0; // провести хаотов
			$dtro[2] = 0; // побед в хаотах
			$dtro[3] = 250; // использовать приемы в бою
			$dtro[4] = 0; // набрать опыт в боях
			$dtro[5] = 0; // походов в пещеры
			$dtro[6] = 0; // убить пещерных монстров
			$dtro[7] = 0; // собрать пещерных ресурсов
			$dtro[8] = 5; // убить марочных монстров
			$dtro[9] = 15; // принять участие в физах\хаотах\группах
			$dtro[10] = 5; // победить в физах\хаотах\группах
			
			//Награда
			$daynaga[$dbd1] = array(
				75 , //кр
				0.50 , //екр
				array() //предметы
			);
			
			//Предметы кол-во , айди , износ макс , доп.параметры
			$daynaga[$dbd1][2][] = array( 1 , 994 , 4 , '|notr=1|nosale=1|notransfer=1|sudba=1|srok=604800' );
			$daynaga[$dbd1][2][] = array( 1 , 1001 , 4 , '|notr=1|nosale=1|notransfer=1|sudba=1|srok=604800' );
			$daynaga[$dbd1][2][] = array( 1 , 4736 , 20 , '|notr=1|nosale=1|notransfer=1|sudba=1|srok=604800' );
			
		}elseif( $dbd1 == 5 ) {
			/*
			День пятый (Турнирный): 			
			1. Принять участие в 5 турнирных поединках; 
			2. Одержать победу хотя бы в 1 турнирном поединке; 
			3. Посетить Башню Смерти хотя бы 1 раз. 			
			Награда: Свиток "Жажда Жизни" +6 (екровый), свиток "Нападение" 0/5 + 100 кр.	
			*/			
			
			
			$dtro[0] = 0; // часов в онлайн
			$dtro[1] = 5; // провести хаотов
			$dtro[2] = 0; // побед в хаотах
			$dtro[3] = 0; // использовать приемы в бою
			$dtro[4] = 0; // набрать опыт в боях
			$dtro[5] = 0; // походов в пещеры
			$dtro[6] = 0; // убить пещерных монстров
			$dtro[7] = 0; // собрать пещерных ресурсов
			$dtro[8] = 0; // убить марочных монстров
			$dtro[9] = 0; // принять участие в физах\хаотах\группах
			$dtro[10] = 0; // победить в физах\хаотах\группах
			$dtro[11] = 5; // Принять участие в турнирных поединках
			$dtro[12] = 1; // Одержать побед в турнирных поединках
			$dtro[13] = 1; // Принять участие в БС
			
			//Награда
			$daynaga[$dbd1] = array(
				100 , //кр
				0 , //екр
				array() //предметы
			);
			
			//Предметы кол-во , айди , износ макс , доп.параметры
			$daynaga[$dbd1][2][] = array( 1 , 3101 , 5 , '|notr=1|nosale=1|notransfer=1|sudba=1|srok=604800' );
			$daynaga[$dbd1][2][] = array( 1 , 874 , 5 , '|notr=1|nosale=1|notransfer=1|sudba=1|srok=604800' );
			
		}elseif( $dbd1 == 6 ) {
			/*
			1. Принять участие в 50 боях (хаотических/физических/групповых); 
			2. Одержать 25 побед в боях; 
			3. Набрать 30 000 - 50 000 опыта в боях. 
			
			Награда: набор хилок трехразовые хилки на 600 и 900 НР по 1 шт,
			ликсир Восстановления - 1 бут.,
			Эликсир Жизни - 3 шт, 
			эликсир Неуязвимости и Малое зелье Отрицания (малышам) по 1 бут., 
			Великое зелье Стойкости и Великое зелье Отрицания (для всех остальных) по 1 бут.;
			свиток лечение травм (малышам),
			аптечку (тяжелые травмы) от 7-го уровня +0,75 екр.
			*/			
			
			
			$dtro[0] = 0; // часов в онлайн
			$dtro[1] = 0; // провести хаотов
			$dtro[2] = 0; // побед в хаотах
			$dtro[3] = 0; // использовать приемы в бою
			$dtro[4] = 30000; // набрать опыт в боях
			$dtro[5] = 0; // походов в пещеры
			$dtro[6] = 0; // убить пещерных монстров
			$dtro[7] = 0; // собрать пещерных ресурсов
			$dtro[8] = 0; // убить марочных монстров
			$dtro[9] = 50; // принять участие в физах\хаотах\группах
			$dtro[10] = 25; // победить в физах\хаотах\группах
			$dtro[11] = 0; // Принять участие в турнирных поединках
			$dtro[12] = 0; // Одержать побед в турнирных поединках
			$dtro[13] = 0; // Принять участие в БС
			
			//Награда
			$daynaga[$dbd1] = array(
				0 , //кр
				0.75 , //екр
				array() //предметы
			);
			
			//Предметы кол-во , айди , износ макс , доп.параметры
			$daynaga[$dbd1][2][] = array( 1 , 4017 , 3 , '|notr=1|nosale=1|notransfer=1|sudba=1|srok=604800' );
			$daynaga[$dbd1][2][] = array( 1 , 2711 , 3 , '|notr=1|nosale=1|notransfer=1|sudba=1|srok=604800' );
			$daynaga[$dbd1][2][] = array( 1 , 2418 , 10 , '|notr=1|nosale=1|notransfer=1|sudba=1|srok=604800' );
			$daynaga[$dbd1][2][] = array( 1 , 4736 , 30 , '|notr=1|nosale=1|notransfer=1|sudba=1|srok=604800' );
			if( $u->info['level'] < 7 ) {
				// 0 - 6
				$daynaga[$dbd1][2][] = array( 1 , 887 , 3 , '|notr=1|nosale=1|notransfer=1|sudba=1|srok=604800' );
				$daynaga[$dbd1][2][] = array( 1 , 4730 , 3 , '|notr=1|nosale=1|notransfer=1|sudba=1|srok=604800' );
			}elseif( $u->info['level'] < 8 ) {
				// 7
				$daynaga[$dbd1][2][] = array( 1 , 1186 , 3 , '|notr=1|nosale=1|notransfer=1|sudba=1|srok=604800' );
				$daynaga[$dbd1][2][] = array( 1 , 188 , 3 , '|notr=1|nosale=1|notransfer=1|sudba=1|srok=604800' );
			}else{
				//8-21
				$daynaga[$dbd1][2][] = array( 1 , 2139 , 3 , '|notr=1|nosale=1|notransfer=1|sudba=1|srok=604800' );
				$daynaga[$dbd1][2][] = array( 1 , 2140 , 3 , '|notr=1|nosale=1|notransfer=1|sudba=1|srok=604800' );
			}
			if( $u->info['level'] > 7 ) {
				$daynaga[$dbd1][2][] = array( 1 , 4414 , 1 , '|notr=1|nosale=1|notransfer=1|sudba=1|srok=604800' );
			}else{
				$daynaga[$dbd1][2][] = array( 1 , 2390 , 5 , '|notr=1|nosale=1|notransfer=1|sudba=1|srok=604800' );
			}
			
		}elseif( $dbd1 == 7 ) {
			/*
			День седьмой (Расгрузочный): 
			
			1. Выплавить 10 рун в Храме Знаний; 
			2. Отремонтировать снаряжение в общей сложности на 10 - 15 единиц; 
			3. Сделать 5 подарков друзьям или просто игрокам. 
			
			Награда: Сущность ресурса - 3 шт., 1 екр.
			*/			
			
			
			$dtro[0] = 3; // часов в онлайн
			$dtro[1] = 0; // провести хаотов
			$dtro[2] = 0; // побед в хаотах
			$dtro[3] = 0; // использовать приемы в бою
			$dtro[4] = 0; // набрать опыт в боях
			$dtro[5] = 0; // походов в пещеры
			$dtro[6] = 0; // убить пещерных монстров
			$dtro[7] = 0; // собрать пещерных ресурсов
			$dtro[8] = 0; // убить марочных монстров
			$dtro[9] = 0; // принять участие в физах\хаотах\группах
			$dtro[10] = 0; // победить в физах\хаотах\группах
			$dtro[11] = 0; // Принять участие в турнирных поединках
			$dtro[12] = 0; // Одержать побед в турнирных поединках
			$dtro[13] = 0; // Принять участие в БС
			$dtro[14] = 10; // Выплавить рун
			$dtro[15] = 10; // Отремонтировать снаряжение
			$dtro[16] = 5; // Сделать подарков другим игрокам
			
			//Награда
			$daynaga[$dbd1] = array(
				0 , //кр
				1 , //екр
				array() //предметы
			);
			
			//Предметы кол-во , айди , износ макс , доп.параметры
			$daynaga[$dbd1][2][] = array( 3 , 1035 , 1 , '|notr=1|nosale=1|notransfer=1|sudba=1' );			
		}
		
		
		//Проверки
		if( $daily['haot'] > $dtro[1] ) {
			$daily['haot'] = $dtro[1];
		}
		if( $daily['winhaot'] > $dtro[2] ) {
			$daily['winhaot'] = $dtro[2];
		}
		if( $daily['usepriem'] > $dtro[3] ) {
			$daily['usepriem'] = $dtro[3];
		}
		if( $daily['expopen'] > $dtro[4] ) {
			$daily['expopen'] = $dtro[4];
		}
		if( $daily['dun_go'] > $dtro[5] ) {
			$daily['dun_go'] = $dtro[5];
		}	
		if( $daily['dun_kill'] > $dtro[6] ) {
			$daily['dun_kill'] = $dtro[6];
		}	
		if( $daily['dun_res'] > $dtro[7] ) {
			$daily['dun_res'] = $dtro[7];
		}
		if( $daily['dun_bot_mar'] > $dtro[8] ) {
			$daily['dun_bot_mar'] = $dtro[8];
		}
		if( $daily['fight'] > $dtro[9] ) {
			$daily['fight'] = $dtro[9];
		}
		if( $daily['winfight'] > $dtro[10] ) {
			$daily['winfight'] = $dtro[10];
		}
		if( $daily['turnirgo'] > $dtro[11] ) {
			$daily['turnirgo'] = $dtro[11];
		}
		if( $daily['turnirwin'] > $dtro[12] ) {
			$daily['turnirwin'] = $dtro[12];
		}
		if( $daily['bsgo'] > $dtro[13] ) {
			$daily['bsgo'] = $dtro[13];
		}
		if( $daily['rune'] > $dtro[14] ) {
			$daily['rune'] = $dtro[14];
		}
		if( $daily['remont'] > $dtro[15] ) {
			$daily['remont'] = $dtro[15];
		}
		if( $daily['gifts'] > $dtro[16] ) {
			$daily['gifts'] = $dtro[16];
		}
		$dbs1x = 0;		
		//Необходимо провести хаотов			
		if( $dtro[1] > 0 ) {
			$dbs1x++;
			if( $daily['haot'] >= $dtro[1] ) {
				$dbsinfo1 .= '<font color=green>';
			}
			$dbsinfo1  .= 'Проведите хаотические бои: <b>'.$daily['haot'].'/'.$dtro[1].'</b>';
			if( $daily['haot'] >= $dtro[1] ) {
				$dbsinfo1 .= ' (Выполнено!)</font>';
			}
			$dbs1 += ($daily['haot']/$dtro[1]*100);
		}		
		//Победить в хаотах
		if( $dtro[2] > 0 ) {
			$dbs1x++;
			if( $daily['winhaot'] >= $dtro[2] ) {
				$dbsinfo1 .= '<font color=green>';
			}
			$dbsinfo1 .= '<br>Выиграйте в хаотических боях: <b>'.$daily['winhaot'].'/'.$dtro[2].'</b>';
			if( $daily['winhaot'] >= $dtro[2] ) {
				$dbsinfo1 .= ' (Выполнено!)</font>';
			}
			$dbs1 += ($daily['winhaot']/$dtro[2]*100);
		}
		//Использовать приемы в бою
		if( $dtro[3] > 0 ) {
			$dbs1x++;
			if( $daily['usepriem'] >= $dtro[3] ) {
				$dbsinfo1 .= '<font color=green>';
			}
			$dbsinfo1 .= '<br>Использовать приемы в боях: <b>'.$daily['usepriem'].'/'.$dtro[3].'</b>';
			if( $daily['usepriem'] >= $dtro[3] ) {
				$dbsinfo1 .= ' (Выполнено!)</font>';
			}
			$dbs1 += ($daily['usepriem']/$dtro[3]*100);
		}	
		//Набрать опыта
		if( $dtro[4] > 0 ) {
			$dbs1x++;
			if( $daily['expopen'] >= $dtro[4] ) {
				$dbsinfo1 .= '<font color=green>';
			}
			$dbsinfo1 .= '<br>Набрать опыт: <b>'.$daily['expopen'].'/'.$dtro[4].'</b>';
			if( $daily['expopen'] >= $dtro[4] ) {
				$dbsinfo1 .= ' (Выполнено!)</font>';
			}
			$dbs1 += ($daily['expopen']/$dtro[4]*100);
		}
		//Посетить пещеры
		if( $dtro[5] > 0 ) {
			$dbs1x++;
			if( $daily['dun_go'] >= $dtro[5] ) {
				$dbsinfo1 .= '<font color=green>';
			}
			$dbsinfo1 .= '<br>Посетить пещеры: <b>'.$daily['dun_go'].'/'.$dtro[5].'</b>';
			if( $daily['dun_go'] >= $dtro[5] ) {
				$dbsinfo1 .= ' (Выполнено!)</font>';
			}
			$dbs1 += ($daily['dun_go']/$dtro[5]*100);
		}
		//Убить монстров в пещерах
		if( $dtro[6] > 0 ) {
			$dbs1x++;
			if( $daily['dun_kill'] >= $dtro[6] ) {
				$dbsinfo1 .= '<font color=green>';
			}
			$dbsinfo1 .= '<br>Убить пещерных монстров: <b>'.$daily['dun_kill'].'/'.$dtro[6].'</b>';
			if( $daily['dun_kill'] >= $dtro[6] ) {
				$dbsinfo1 .= ' (Выполнено!)</font>';
			}
			$dbs1 += ($daily['dun_kill']/$dtro[6]*100);
		}	
		//Убить марочных монстров
		if( $dtro[8] > 0 ) {
			$dbs1x++;
			if( $daily['dun_kill_mar'] >= $dtro[8] ) {
				$dbsinfo1 .= '<font color=green>';
			}
			$dbsinfo1 .= '<br>Убить <img src=http://img.xcombats.com/i/align/align9.gif >марочных монстров: <b>'.$daily['dun_kill_mar'].'/'.$dtro[8].'</b>';
			if( $daily['dun_kill_mar'] >= $dtro[8] ) {
				$dbsinfo1 .= ' (Выполнено!)</font>';
			}
			$dbs1 += ($daily['dun_kill_mar']/$dtro[8]*100);
		}
		//Собрать пещерные ресурсы
		if( $dtro[7] > 0 ) {
			$dbs1x++;
			if( $daily['dun_res'] >= $dtro[7] ) {
				$dbsinfo1 .= '<font color=green>';
			}
			$dbsinfo1 .= '<br>Собрать ресурсы в пещерах: <b>'.$daily['dun_res'].'/'.$dtro[7].'</b>';
			if( $daily['dun_res'] >= $dtro[7] ) {
				$dbsinfo1 .= ' (Выполнено!)</font>';
			}
			$dbs1 += ($daily['dun_res']/$dtro[7]*100);
		}
		//Сыграть в боях (физ\хаоты\групп)
		if( $dtro[9] > 0 ) {
			$dbs1x++;
			if( $daily['fight'] >= $dtro[9] ) {
				$dbsinfo1 .= '<font color=green>';
			}
			$dbsinfo1 .= '<br>Провести поединки (залы): <b>'.$daily['fight'].'/'.$dtro[9].'</b>';
			if( $daily['fight'] >= $dtro[9] ) {
				$dbsinfo1 .= ' (Выполнено!)</font>';
			}
			$dbs1 += ($daily['fight']/$dtro[9]*100);
		}
		//Победить в боях (физ\хаоты\групп)
		if( $dtro[10] > 0 ) {
			$dbs1x++;
			if( $daily['winfight'] >= $dtro[10] ) {
				$dbsinfo1 .= '<font color=green>';
			}
			$dbsinfo1 .= '<br>Победить в поединках (залы): <b>'.$daily['winfight'].'/'.$dtro[10].'</b>';
			if( $daily['winfight'] >= $dtro[10] ) {
				$dbsinfo1 .= ' (Выполнено!)</font>';
			}
			$dbs1 += ($daily['winfight']/$dtro[10]*100);
		}
		//Победить в боях (физ\хаоты\групп)
		if( $dtro[11] > 0 ) {
			$dbs1x++;
			if( $daily['turnirgo'] >= $dtro[11] ) {
				$dbsinfo1 .= '<font color=green>';
			}
			$dbsinfo1 .= '<br>Принять участие в турнирах (в залах): <b>'.$daily['turnirgo'].'/'.$dtro[11].'</b>';
			if( $daily['turnirgo'] >= $dtro[11] ) {
				$dbsinfo1 .= ' (Выполнено!)</font>';
			}
			$dbs1 += ($daily['turnirgo']/$dtro[11]*100);
		}
		if( $dtro[12] > 0 ) {
			$dbs1x++;
			if( $daily['turnirwin'] >= $dtro[12] ) {
				$dbsinfo1 .= '<font color=green>';
			}
			$dbsinfo1 .= '<br>Победить в турнирах (в залах): <b>'.$daily['turnirwin'].'/'.$dtro[12].'</b>';
			if( $daily['turnirwin'] >= $dtro[12] ) {
				$dbsinfo1 .= ' (Выполнено!)</font>';
			}
			$dbs1 += ($daily['turnirwin']/$dtro[12]*100);
		}
		if( $dtro[13] > 0 ) {
			$dbs1x++;
			if( $daily['bsgo'] >= $dtro[13] ) {
				$dbsinfo1 .= '<font color=green>';
			}
			$dbsinfo1 .= '<br>Принять участие в турнирах Башни Смерти: <b>'.$daily['bsgo'].'/'.$dtro[13].'</b>';
			if( $daily['bsgo'] >= $dtro[13] ) {
				$dbsinfo1 .= ' (Выполнено!)</font>';
			}
			$dbs1 += ($daily['bsgo']/$dtro[13]*100);
		}
		if( $dtro[14] > 0 ) {
			$dbs1x++;
			if( $daily['rune'] >= $dtro[14] ) {
				$dbsinfo1 .= '<font color=green>';
			}
			$dbsinfo1 .= '<br>Выплавить рун: <b>'.$daily['rune'].'/'.$dtro[14].'</b>';
			if( $daily['rune'] >= $dtro[14] ) {
				$dbsinfo1 .= ' (Выполнено!)</font>';
			}
			$dbs1 += ($daily['rune']/$dtro[14]*100);
		}
		if( $dtro[15] > 0 ) {
			$dbs1x++;
			if( $daily['remont'] >= $dtro[15] ) {
				$dbsinfo1 .= '<font color=green>';
			}
			$dbsinfo1 .= '<br>Отремонтировать предметы (ед. долговечности): <b>'.$daily['remont'].'/'.$dtro[15].'</b>';
			if( $daily['rune'] >= $dtro[15] ) {
				$dbsinfo1 .= ' (Выполнено!)</font>';
			}
			$dbs1 += ($daily['remont']/$dtro[15]*100);
		}
		if( $dtro[16] > 0 ) {
			$dbs1x++;
			if( $daily['gifts'] >= $dtro[16] ) {
				$dbsinfo1 .= '<font color=green>';
			}
			$dbsinfo1 .= '<br>Сделать подарки другим игрокам: <b>'.$daily['gifts'].'/'.$dtro[16].'</b>';
			if( $daily['gifts'] >= $dtro[16] ) {
				$dbsinfo1 .= ' (Выполнено!)</font>';
			}
			$dbs1 += ($daily['gifts']/$dtro[16]*100);
		}
		//Провести в онлайне
		if( $dtro[0] > 0 ) {
			$dbs1x++;
			if( $ts > 3600 * $dtro[0] ) {
				$ts = 3600 * $dtro[0];
			}
			$ts = 3600 * $dtro[0] - $ts;
			if( $ts < 1 ) {
				$dbsinfo1 .= '<font color=green>';
			}
			$dbsinfo1 .= '<br>Проведите в онлайне <b>';
			if( $ts > 0 ) {
				$dbsinfo1 .= ' еще '.$u->timeOut($ts);
			}else{
				$dbsinfo1 .= '2 часа';
			}
			$dbsinfo1 .= '</b>';
			if( $ts < 1 ) {
				$dbsinfo1 .= ' (Выполнено!)</font>';
			}
			$dbs1 += ((3600*$dtro[0] - $ts)/(3600*$dtro[0])*100);
		}
		$dbs1 = floor( $dbs1 / $dbs1x );
		//
	}else{
		$nods1 = true;
	}
		
	$dbsinfo1 = '<b>Ежедневное Задание: День '.$dbd1.'</b> (Выполнено на '.round($dbs1,2).'%)<hr>'.$dbsinfo1;
	
	if($dbs1 < 100) {
		$dbo1 = 'alert(\'Необходимо выполнить все задания. Вы выполнили '.round($dbs1,2).'%\');';
	}else{
		$dbsinfo1 = 'Вы выполнили ежедневное задание.<br>Нажмите здесь чтобы получить награду!';
		$dbo1 = 'location.href=\'/main.php?takedaily1=1\'';
	}
	
	if(isset($_GET['takedaily1'])) {
		if( $dbs1 < 100 ) {
			echo '<b><font color="red"><small>Задание не выполнено!</small></font></b>';
		}elseif( date('d.m.Y',$daily['finish']) == date('d.m.Y') ) {
			echo '<b><font color="red"><small>Вы уже завершили задание сегодня!</small></font></b>';
		}else{
			echo '<b><font color="red"><small>Вы выполнили задание!</small></font></b>';
			//
			$nagatxt = '';
			if( $daynaga[$dbd1][0] > 0 ) {
				$nagatxt .= ', <b>'.$daynaga[$dbd1][0].' кр.</b>';
				$u->info['money'] += $daynaga[$dbd1][0];
				mysql_query('UPDATE `users` SET `money` = "'.$u->info['money'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			}
			if( $daynaga[$dbd1][1] > 0 ) {
				$nagatxt .= ', <b><font color=green>'.$daynaga[$dbd1][1].' екр.</font></b>';
				if(isset($u->bank['id'])) {
					$u->bank['money2'] += $daynaga[$dbd1][1];
					$u->bank['shara'] += $daynaga[$dbd1][1];
					mysql_query('UPDATE `bank` SET `money2` = "'.$u->bank['money2'].'" , `shara` = "'.$u->bank['shara'].'" WHERE `id` = "'.$u->bank['id'].'" LIMIT 1');
				}else{
					mysql_query('UPDATE `bank` SET `shara` = `shara` + "'.$daynaga[$dbd1][1].'" , `money2` = `money2` + "'.$daynaga[$dbd1][1].'" WHERE `uid` = "'.$u->info['id'].'" ORDER BY `id` DESC LIMIT 1');
				}
			}
			if( count($daynaga[$dbd1][2]) > 0 ) {
				$itms = $daynaga[$dbd1][2];
				$i = 0;
				$nagatxtit = '';
				while( $i < count($daynaga[$dbd1][2]) ) {
					$itmd = $daynaga[$dbd1][2][$i];
					$itm = mysql_fetch_array(mysql_query('SELECT `id`,`name` FROM `items_main` WHERE `id` = "'.$itmd[1].'" LIMIT 1'));
					if(isset($itm['id'])) {
						$j = 0;
						while( $j < $itmd[0] ) {
							$u->addItem( $itmd[1] , $u->info['id'] , $itmd[3] , NULL , $itmd[2] );
							$j++;
						}
						//
						$nagatxtit .= ''.$itm['name'].'';
						if( $itmd[0] > 1 ) {
							$nagatxtit .= ' (x'.$itmd[0].')';
						}
						$nagatxtit .= ', ';
						//
					}
					//
					$i++;
				}
				if( $nagatxtit != '' ) {
					$nagatxt .= ', <b>Предметы</b>: '.rtrim($nagatxtit,', ');
				}
			}
			//
			$nagatxt = ltrim($nagatxt,', ');
			//
			$daily['day']++;
			$txtb1 = 'Вы успешно выполнили &quot;<b>Ежедневное Задание ('.$daily['day'].' день)</b>&quot; и получаете награду: '.$nagatxt.' (Следующее задание будет доступно завтра)';
			mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','".$u->info['city']."','".$u->info['room']."','','".$u->info['login']."','".$txtb1."','".time()."','6','0')");
			if( $daily['day'] == 7 ) {
				$daily['day'] = 0; // обнуляем с первого дня
			}
			//
			$daily['finish'] = time();
			$daily['date_finish'] = date('d.m.Y');
			mysql_query('UPDATE `dailybonus` SET 
			
			`turnirgo` = 0 ,`turnirwin` = 0 ,`bsgo` = 0 , `remont` = 0 , `rune` = 0 , `gifts` = 0 ,
			`haot` = 0 ,`winhaot` = 0 ,`fight` = 0 ,`winfight` = 0 ,`usepriem` = 0 ,
			`expopen` = 0 , `dun_go` = 0 , `dun_kill` = 0 , `dun_kill_mar` = 0 , `dun_res` = 0 , 
			
			`date_finish` = "'.$daily['date_finish'].'" , `finish` = "'.$daily['finish'].'" , `day` = "'.$daily['day'].'" WHERE `id` = "'.$daily['id'].'" LIMIT 1');
			mysql_query('DELETE FROM `online` WHERE `uid` = "'.$u->info['id'].'"');
			//
		}
	}
	
	
	if( $daily['date_finish'] == date('d.m.Y') ) {
		
	}elseif( $nods1 == false ) {
?>
<div onclick="<?=$dbo1?>" onMouseOver="top.hi(this,'<?=$dbsinfo1?>',event,0,1,1,1,'');" onMouseOut="top.hic();" style="width:156px; height:67px; cursor:pointer; display:inline-block; background-image:url(/dayb0.png);" align="left">
	<div id="daybonus1" style="height:67px; display:inline-block; width:0%; background-image:url(/dayb1.png);" align="right"><?
    if( $dbs1 >= 0 && $dbs1 < 100 ) {
	?><img style="  -ms-filter: 'progid:DXImageTransform.Microsoft.Alpha(Opacity=50)'; filter: alpha(opacity=50);  -moz-opacity: 0.5;  -khtml-opacity: 0.5;  opacity: 0.5;" src="/daya.gif" width="3" height="67"><? }else{
	?><img id="daybonus1a" src="/dayb1a.png"><?
	}?></div>
</div>
<script>
<? if( $dbs1 >= 0 && $dbs1 < 100 ) { ?>
$('#daybonus1').animate({ 'width':'<?=$dbs1?>%' } , 'slow' );
<? }else{ ?>
function daybonus1anim() {
	$('#daybonus1a').animate({opacity:0},1000,null,function() {
		$('#daybonus1a').animate({opacity:1},1000,null,function() { });
	});
}
daybonus1anim();
setInterval('daybonus1anim();',2000);
$('#daybonus1').css({ 'width':'100%' });
<? } ?>
</script>
<?
	}
}
?>
