<?php
define('GAME',true);
include('_incl_data/__config.php');
include('_incl_data/class/__db_connect.php');
include('_incl_data/class/__user.php');
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<meta http-equiv=Cache-Control Content=no-cache>
<meta http-equiv=PRAGMA content=NO-CACHE>
<meta http-equiv=Expires Content=0>
<link href="http://img.xcombats.com/css/main.css" rel="stylesheet" type="text/css">
<title>Монитор СБК - Хаоты, Башня смерти, Излом хаоса ,Физические бои, Хаотичные бои, Групповые бои, Текущие бои</title>
</head>
<body style="padding-top:0px; margin-top:7px; height:100%; background-color:#dedede;">
<script type="text/javascript" src="js/jquery.js"></script>

<center style="float:right"><a class="btnnew" href="http://xcombats.com/hmonitor.php">Обновить</a></center>

<h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Заявки на хаотические поединки</h3>
<?
$html = ''; $i = 0;
$sp = mysql_query('SELECT * FROM `zayvki` WHERE `razdel` = 5 AND `cancel` = 0 AND `btl_id` = 0 ORDER BY `id` ASC');
while( $pl = mysql_fetch_array($sp) ) {
	$i++;
	//
	$users = '';
	//
	$spu = mysql_query('SELECT `id` FROM `stats` WHERE `zv` = "'.$pl['id'].'"');
	while( $plu = mysql_fetch_array($spu) ) {
		$users .= $u->microLogin($plu['id'],1).',';
	}
	//
	if( $users == '' ) {
		$users = '<i><font color=grey><small>Поле боя ждет своих героев!</small></font></i>';
	}else{
		$users = rtrim($users,',');
	}
	//
	$html .= $i . '. <span class="date">'.date('H:i',$pl['time']).'</span> ';
	$html .= ' <img title="'.$u->city_name[$pl['city']].'" src="http://img.xcombats.com/i/city_ico/'.$pl['city'].'.gif">';
	$html .= ' ('.$users.') ('.$pl['min_lvl_1'].'-'.$pl['max_lvl_1'].') Тип боя: <img src="http://img.xcombats.com/i/fighttype'.$pl['type'].'.gif"> ';
	if( $pl['noinc'] > 0 ) {
		$html .= '<img src="http://img.xcombats.com/closefight.gif" title="В поединок нельзя вмешаться">';
	}
	if( $pl['fastfight'] > 0 ) {
		$html .= '<img src="http://img.xcombats.com/fastfight.gif" title="Для старта поединка требуется минимум двое участников">';
	}
	if( $pl['arand'] > 0 ) {
		$html .= '<img src="http://img.xcombats.com/arand.gif" title="Команды делятся на две равные команды (равные по количеству, но не по мощности)">';
	}
	$html .= '<font color="grey">Бой начнется через <b>'.$u->timeOut($pl['time']+$pl['time_start']-time()).'</b>, таймаут '.$u->timeOut($pl['timeout']).'</font>';
	if( $pl['priz'] > 0 ) {
		$html .= '<a href="http://xcombats.com/lib/prizovoi-haot/" target="_blank"><span style="color:#e65700;" title="Участники получают жетоны, чем больше призовых хаотов за сутки, тем больше падает жетонов за победу "><b>(Призовой хаот)</b></span></a></font></i>';
	}
	$html .= '<br>';
}
if( $html == '' ) {
	$html = '<center>(Раздел пуст)</center>';
}
echo $html; $html = '';
?>
<h3>Текущие бои</h3>
<?
$i = 0;
$sp = mysql_query('SELECT * FROM `battle` WHERE `team_win` = -1');
while( $pl = mysql_fetch_array($sp) ) {
	//
	$users = '';
	$usersa = array();
	$userst = array();
	//
	$spu = mysql_query('SELECT `a`.`id`,`b`.`team` FROM `users` AS `a` LEFT JOIN `stats` AS `b` ON `a`.`id` = `b`.`id` WHERE `a`.`battle` = "'.$pl['id'].'"');
	while( $plu = mysql_fetch_array($spu) ) {
		if(!isset($usersa[$plu['team']])) {
			$userst[] = $plu['team'];
		}
		$usersa[$plu['team']] .= $u->microLogin($plu['id'],1).',';
	}
	//
	if( count($usersa) > 0 ) {
		$j = 0;
		while( $j < count($userst) ) {
			if( $users != '' ) {
				$users .= ' &nbsp;<b><font color=red><small>против</small></font></b>&nbsp; ';
			}
			$users .= $usersa[$userst[$j]];
			$users = rtrim($users,',');
			$j++;
		}
		//
		$i++;
		//
		
		$html .= $i . '. <span class="date">'.date('d.m.Y H:i',$pl['time_start']).'</span> ';
		$html .= ' <img title="'.$u->city_name[$pl['city']].'" src="http://img.xcombats.com/i/city_ico/'.$pl['city'].'.gif">';
		$html .= ' ('.$users.') Тип боя: <img src="http://img.xcombats.com/i/fighttype'.$pl['type'].'.gif"> ';
		if( $pl['noinc'] > 0 ) {
			$html .= '<img src="http://img.xcombats.com/closefight.gif" title="В поединок нельзя вмешаться">';
		}
		if( $pl['fastfight'] > 0 ) {
			$html .= '<img src="http://img.xcombats.com/fastfight.gif" title="Для старта поединка требуется минимум двое участников">';
		}
		if( $pl['arand'] > 0 ) {
			$html .= '<img src="http://img.xcombats.com/arand.gif" title="Команды делятся на две равные команды (равные по количеству, но не по мощности)">';
		}
		$html .= '<font color="grey">, таймаут '.$u->timeOut($pl['timeout']).'</font>';
		if( $pl['priz'] > 0 ) {
			$html .= '<a href="http://xcombats.com/lib/prizovoi-haot/" target="_blank"><span style="color:#e65700;" title="Участники получают жетоны, чем больше призовых хаотов за сутки, тем больше падает жетонов за победу "><b>(Призовой хаот)</b></span></a></font></i>';
		}
		$html .= ' <a href="/logs.php?log='.$pl['id'].'" target="_blank">&raquo;&raquo;</a> ';
		$html .= '<br>';
	}
	//
}
if( $html == '' ) {
	$html = '<center>(Раздел пуст)</center>';
}
echo $html; $html = '';
?>
<h3>Башня смерти</h3>
<?
$sp = mysql_query('SELECT * FROM `bs_turnirs`');
$i = 0;
while( $pl = mysql_fetch_array($sp) ) {
	$i++;
	//
	$html .= $i.'. <img title="'.$u->city_name[$pl['city']].'" src="http://img.xcombats.com/i/city_ico/'.$pl['city'].'.gif">';
	//
	$html .= ' ['.$pl['level'].']';
	if( $pl['status'] == 0 ) {
		//Ожидаем начала нового турнира
		$html .= ' Начало турнира: <span class="date">'.date('d.m.Y H:i',$pl['time_start']).'</span> (<small> <font color=grey>Начнется через <b>'.$u->timeOut($pl['time_start']-time()).'</b></font></small>) Призовой фонд на текущий момент: <b>'.round(($pl['money']/100*85),2).'</b> кр. Всего подано заявок: <b>'.$pl['users'].'</b>';
	}else{
		//Уже идет турнир
		$users = '';
		$spu = mysql_query('SELECT `id` FROM `users` WHERE `inTurnir` = "'.$pl['id'].'"');
		while( $plu = mysql_fetch_array($spu) ) {
			$users .=  $u->microLogin($plu['id'],1). ',';
		}
		$users = rtrim($users,',');
		$html .= ' <span title="['.$pl['status'].']">Турнир уже идет.</span>';
		$html .= ' Участники: '.$users.', Лог турнира: <a href="/towerlog.php?towerid='.$pl['id'].'&id='.$pl['count'].'" target="_blank">&raquo;&raquo;</a>';
	}
	//
	$html .= '<br>';
	//
}
if( $html == '' ) {
	$html = '<center>(Раздел пуст)</center>';
}
echo $html; $html = '';
?>

<h3>Войны кланов</h3>
<?
if( $html == '' ) {
	$html = '<center>(Раздел пуст)</center>';
}
echo $html; $html = '';
?>

<h3>История Великих сражений</h3>
<?
if( $html == '' ) {
	$html = '<center>(Раздел пуст)</center>';
}
echo $html; $html = '';
?>

<div align="right"><font style="color:#999;" size="1" face="verdana" color="black"><hr style="border-color:#CCC;">Старый Бойцовский Клуб &copy; <?=date('Y')?>, «www.xcombats.com»™ &nbsp; &nbsp; </font></div>
<br /><br />

<div align="right"><div style="display:none"><!--LiveInternet counter--><script type="text/javascript"><!--
        document.write("<a href='//www.liveinternet.ru/click' "+
        "target=_blank><img style='display:inline-block; vertical-align:bottom;' src='//counter.yadro.ru/hit?t25.10;r"+
        escape(document.referrer)+((typeof(screen)=="undefined")?"":
        ";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
        screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
        ";"+Math.random()+
        "' alt='' title='LiveInternet: показано число посетителей за"+
        " сегодня' "+
        "border='0' width='88' height='15'><\/a>")
        //--></script><!--/LiveInternet-->
        <!-- Rating@Mail.ru counter -->
        <script type="text/javascript">
        var _tmr = _tmr || [];
        _tmr.push({id: "2658385", type: "pageView", start: (new Date()).getTime()});
        (function (d, w, id) {
          if (d.getElementById(id)) return;
          var ts = d.createElement("script"); ts.type = "text/javascript"; ts.async = true; ts.id = id;
          ts.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//top-fwz1.mail.ru/js/code.js";
          var f = function () {var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ts, s);};
          if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); }
        })(document, window, "topmailru-code");
        </script><noscript><div style="position:absolute;left:-10000px;">
        <img src="//top-fwz1.mail.ru/counter?id=2658385;js=na" style="border:0;" height="1" width="1" alt="Рейтинг@Mail.ru" />
        </div></noscript>
        <!-- //Rating@Mail.ru counter -->
        <!-- Rating@Mail.ru logo -->
        <a href="http://top.mail.ru/jump?from=2658385">
        <img src="//top-fwz1.mail.ru/counter?id=2658385;t=317;l=1" 
        style="border:0;dispaly:inline-block; vertical-align:bottom;" height="15" width="88" alt="Рейтинг@Mail.ru" /></a>
        <!-- //Rating@Mail.ru logo --></div></div></body>
</html>
