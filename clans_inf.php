<?php
include_once('_incl_data/__config.php');
define('GAME',true);
include_once('_incl_data/class/__db_connect.php');
include_once('_incl_data/class/__user.php');

define('LOWERCASE',3);
define('UPPERCASE',1);

if(!isset($_GET['allclans'])) {	
	$uplogin = explode('&',$_SERVER['QUERY_STRING']);
	$uplogin = $uplogin[0];
	$uplogin = preg_replace('/%20/'," ",$uplogin);	
	if(!isset($_GET['id']))
	{
		$_GET['id'] = 0;
	}	
	if(!isset($upLogin)){ $upLogin = ''; }
	$utf8Login = '';
	$utf8Login2 = '';
	$utf8Login  = iconv("utf-8", "windows-1251",$uplogin);	
	if($uplogin == 'delete' || $utf8Login == 'delete' || $utf8Login2 == 'delete') {
		
	}else{
		$clan = mysql_fetch_array(mysql_query('SELECT `u`.* FROM `clan` AS `u` WHERE (`u`.`id`="'.mysql_real_escape_string($_GET['id']).'" OR `u`.`id`="'.mysql_real_escape_string($uplogin).'" OR `u`.`name`="'.mysql_real_escape_string($uplogin).'") LIMIT 1'));
	}	
	if(!isset($clan['id']))	{
		die('<html><head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="Content-Language" content="ru">
		<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
		<TITLE>Произошла ошибка</TITLE></HEAD><BODY text="#FFFFFF"><p><font color=black>
		Произошла ошибка: <pre>Указанный клан не найден...</pre>
		<b><p><a href = "javascript:window.history.go(-1);">Назад</b></a>
		<HR>
		<p align="right">(c) <a href="index.html">'.$c['title'].'</a></p>
		'.$c['counters'].'
		</body></html>');
	}	
	$clan_inf = mysql_fetch_array(mysql_query('SELECT * FROM `clan_info` WHERE `id` = "'.$clan['id'].'" LIMIT 1'));
	$clan['reiting'] = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `clan` WHERE `exp` >= "'.$clan['exp'].'" AND `id` <= "'.$clan['id'].'" ORDER BY `id` DESC LIMIT 1'));
	$clan['reiting'] = $clan['reiting'][0];
}else{
	?>
<HTML>
<HEAD>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title>Таблица кланового опыта</title>
<meta content="text/html; charset=utf-8" http-equiv=Content-type>
<link href="http://img.xcombats.com/i/move/design3.css" rel="stylesheet" type="text/css">
<link href="http://img.xcombats.com/css/main.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/jquery.js"></script>
<META Http-Equiv=Cache-Control Content="no-cache, max-age=0, must-revalidate, no-store">
<meta http-equiv=PRAGMA content=NO-CACHE>
<META Http-Equiv=Expires Content=0>
<style>
.exptable td {border-bottom: 1px dotted #666666; padding: 2 4 2 4;}
.exptable td.last {border-bottom: 0px;}
.exptable td.header {background-color: #AAAAAA; border-bottom: 1px solid #666666;}
</style>
</HEAD>
<body style="margin:10px; margin-top:5px; background-image: url(http://img.xcombats.com/i/clan/big_<?=$clan['name_mini']?>.gif); background-repeat:no-repeat; background-position: top right" bgcolor=e2e0e0>
<table width="100%" cellpadding=0 cellspacing=0 border=0>
<tr><td colspan=2 align=center><h3>Таблица кланов</h3></td></tr>
</table>
<br>
<?
$pg = 1;
?>
<table width=700 align="center" cellpadding=2 cellspacing=0 class=exptable style="border: 1px solid #666666;">
<tr>
<td width=36 class=header colspan=4>&nbsp;</td>
<td class=header><b><a href="clans_info/allclans&sort=clan&page=1">Клан</a></b></td>
<td class=header><b><a href="clans_info/allclans&sort=exp&page=1">Уровень</a></b></td>
<td class=header width=100><b><a href="clans_info/allclans&sort=persons&page=1">Персонажей</a></b></td>
</tr>

<?
$i = 1;
$sort = ' ORDER BY `exp` DESC ';
$p1 = round(1*$pg-1);
$p2 = round($p1+100);
$sp = mysql_query('SELECT * FROM `clan`'.$sort.' LIMIT '.$p1.','.$p2.'');
while($pl = mysql_fetch_array($sp)) {
	$pl['reiting'] = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `clan` WHERE `exp` >= "'.$pl['exp'].'" AND `id` <= '.$pl['id'].' ORDER BY `id` DESC LIMIT 1'));
	$pl['reiting'] = $pl['reiting'][0];
	$pl['users'] = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `users` WHERE `clan` = "'.$pl['id'].'"'));
	$pl['users'] = $pl['users'][0];
?>
<tr >
<td width=12  align=center><a name="#<?=$pl['reiting']?>" style='font-weight: bold; color: black;'><?=$pl['reiting']?></a></b></td>
<td width=12  align=center>&nbsp;</td>
<td width=12 align=center ><img src="http://img.xcombats.com/i/align/align<?=$pl['align']?>.gif"></td>
<td width=12 align=center ><img src="http://img.xcombats.com/i/clan/<?=$pl['name_mini']?>.gif"></td>
<td ><a href="/clans_info/<?=$pl['id']?>"><?=$pl['name']?></a></td>
<td ><?=$pl['level']?></td>
<td  align=center><?=$pl['users']?></td>
</tr>
<?
	$i++;
}
?>
</table>
<BR>
<br><center>
Страницы: <strong><font style='font-size: 14px;'>1</font></strong>
<? 
/*
<a href='/clans_inf.pl?allclans&sort=clan&page=2'>2</a>
<a href='/clans_inf.pl?allclans&sort=clan&page=3'>3</a>
<a href='/clans_inf.pl?allclans&sort=clan&page=4'>4</a>
<a href='/clans_inf.pl?allclans&sort=clan&page=5'>5</a>
<a href='/clans_inf.pl?allclans&sort=clan&page=6'>6</a>
<a href='/clans_inf.pl?allclans&sort=clan&page=7'>7</a>
<a href='/clans_inf.pl?allclans&sort=clan&page=8'>8</a>
*/
?>
</center>
<DIV>
<?=$c['counters']?>
</DIV>
</td>
</tr>
</table>
</body>
</HTML>
    <?
	die();
}
?>

<HTML>
<HEAD>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title>Информация о клане <?=$clan['name']?></title>
<meta content="text/html; charset=utf-8" http-equiv=Content-type>
<link href="http://img.xcombats.com/i/move/design3.css" rel="stylesheet" type="text/css">
<link href="http://img.xcombats.com/css/main.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/jquery.js"></script>
<META Http-Equiv=Cache-Control Content="no-cache, max-age=0, must-revalidate, no-store">
<meta http-equiv=PRAGMA content=NO-CACHE>
<META Http-Equiv=Expires Content=0>
</HEAD>
<body style="margin:10px; margin-top:5px; background-image: url(http://img.xcombats.com/i/clan/<?=$clan['name_mini']?>_big.gif); background-repeat:no-repeat; background-position: top right" bgcolor=e2e0e0>
<table width="100%" cellpadding=0 cellspacing=0 border=0>
<tr><td colspan=2 align=center>Информация о клане  <b>"<?=$clan['name']?>"</b></td></tr>
</table>
<table width="100%" cellpadding=0 cellspacing=0 border=0>
<tr>
<td width="50%">Уровень: <FONT color=#007200><B><?=$clan['level']?></B></FONT></td>
<td width="50%">
Знак клана: <img src="http://img.xcombats.com/i/clan/<?=$clan['name_mini']?>.gif"> Склонность: <img src="http://img.xcombats.com/i/align/align<?=$clan['align']?>.gif">
</td>
</tr>
<tr>
<td>Рейтинг: <a style='color: #007200;' href="clans_info/allclans&clan=<?=$clan['name']?>#<?=$clan['reiting']?>"><?=$clan['reiting']?></a></td>
<td>Тип правления: <FONT color=#007200><B><? if($clan['politic'] == 1) { ?>Диктатура<? }else{ ?>Демократия<? } ?></B></FONT></td>
</tr>
<?
if(isset($clan['site']) && $clan['site']!='') { ?>
<tr><td colspan=2>Сайт Клана: <a target="_blank" href="http://<?=$clan['site']?>/">http://<?=$clan['site']?>/</a></td></tr>
<tr>
<? } ?>
<tr><td colspan=2>&nbsp;</td></tr>
<tr>
<? if(isset($clan_inf['deviz']) && $clan_inf['deviz']!='') { ?>
<tr><td colspan=2>Девиз Клана: &laquo;<b><?=$clan_inf['deviz']?></b>&raquo;</td></tr>
<tr>
<? } 
if(isset($clan_inf['info']) && $clan_inf['info']!='') { ?>
<tr><td colspan=2>Описание Клана:<br><div id="infoCL" style="width:500px;min-width:64px;overflow:hidden;position:relative;"><div id="infoCL2" style="position:absolute;top:0px;left:0px;">&nbsp; &nbsp;<i><?=$clan_inf['info']?></i></div></div><br>
<script>
var ih = Math.round($('#infoCL').height()/16);
var ih2 = Math.round($('#infoCL2').height()/16);
if(ih2 > 4) {
	document.write('<div align="center" style="width:500px;border-top:1px solid #cac9c7;margin-top:5px;padding-top:5px;margin-right: 15px;"><a href="javascript:void" onclick="opn()"><span id="infoCLm">Развернуть</span> информацию о клане</a></div>');
}
$('#infoCL').height((4*16)+'px');
function opn() {
	if(top.ih2 > top.ih) {
		$('#infoCL').animate({'height':(top.ih2*16)+'px'},'fast',null,function(){top.ih = Math.round($('#infoCL').height()/16);});
		$('#infoCLm').html('Свернуть');
	}else{
		$('#infoCL').animate({'height':(4*16)+'px'},'fast',null,function(){top.ih = Math.round($('#infoCL').height()/16);});
		$('#infoCLm').html('Развернуть');
	}
}
</script>
</td></tr>
<tr>
<? } ?>
<td colspan=2 align=center style='padding-right: 100px;'>
<div style="border-top:1px solid #cac9c7;margin-top:5px;padding-top:5px;margin-right: 15px;"></div>
</td>
</tr>
<tr valign=top>
<td>
<?
$i = 0; $glv = ''; $usrs = '';
$sp = mysql_query('SELECT * FROM `users` WHERE `clan` = "'.$clan['id'].'" AND `login` != "delete"');
while($pl = mysql_fetch_array($sp)) {
	if($pl['clan_prava'] == 'glava') {
		if($glv != '') {
			$glv .= ', ';
		}
		$glv .= $u->microLogin($pl,2);		
	}	
	$usrs .= $u->microLogin($pl,2).'<br>';
	$i++;
}
?>
<div style="margin-right: 15px;">
Глава клана: <?=$glv?>
</div>
<? if($clan['join1'] > 0) {
$j1 = mysql_fetch_array(mysql_query('SELECT * FROM `clan_joint` WHERE `id` = "'.$clan['join1'].'" AND `type` = "1" LIMIT 1'));
if(isset($j1['id'])) {	
?>
<div style="border-top:1px solid #cac9c7;margin-top:5px;padding-top:5px;margin-right: 15px;">
Союз: <b><?=$j1['name']?></b> (
<?
$r = '';
$sp = mysql_query('SELECT * FROM `clan` WHERE `join1` = "'.$j1['id'].'"');
while($pl = mysql_fetch_array($sp)) {
	if($r != '') {
		$r .= ', ';
	}
	$r .= '<img style="vertical-align:bottom" src="http://img.xcombats.com/i/clan/'.$pl['name_mini'].'.gif" width="24" height="15"><a href="clans_info/'.$pl['name'].'" target="_blank">'.$pl['name'].'</a>';
}
echo $r;
?> )
</div>
<? }
}

if($clan['join2'] > 0) {
$j2 = mysql_fetch_array(mysql_query('SELECT * FROM `clan_joint` WHERE `id` = "'.$clan['join2'].'" AND `type` = "2" LIMIT 1'));
if(isset($j2['id'])) {	
?>
<div style="border-top:1px solid #cac9c7;margin-top:5px;padding-top:5px;margin-right: 15px;">
Альянс: <b><?=$j2['name']?></b> (<?
$r = '';
$sp = mysql_query('SELECT * FROM `clan` WHERE `join2` = "'.$j2['id'].'"');
while($pl = mysql_fetch_array($sp)) {
	if($r != '') {
		$r .= ', ';
	}
	$r .= '<img style="vertical-align:bottom" src="http://img.xcombats.com/i/clan/'.$pl['name_mini'].'.gif" width="24" height="15"><a href="clans_info/'.$pl['name'].'" target="_blank">'.$pl['name'].'</a>';
}
echo $r;
?> )
</div>
<? }
}
?>

</td>
<td>
Бойцы клана:<br>
<?=$usrs?>
Всего: <b><?=$i?></b>
</td>
</tr>
<tr>
<td colspan=2 align=right>
<a href="clans_info/allclans">таблица кланов</a><br>
Место клана в рейтинге: <a href="clans_info/allclans#<?=$clan['reiting']?>"><?=$clan['reiting']?></a>
</td>
</tr>
</table>
<BR>
<DIV>
<?=$c['counters']?>
</DIV>
</td>
</tr>
</table>
</body>
</HTML>
