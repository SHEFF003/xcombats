<?php

die('Что-то тут не так...');

define('GAME',true);
include('_incl_data/__config.php');
include('_incl_data/class/__db_connect.php');
include('_incl_data/class/__user.php');

if( !isset($u->info['id']) || $u->info['admin'] < 1 ) {
	header('location: http://xcombats.com/');
	die();
}

$i = 0;
while( $i < 100 ) {
	if(!isset($c['battle_cfg'][$i])) {
		$c['battle_cfg'][$i] = 0;
	}
	$i++;
}

if($u->info['admin'] > 0) {
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Тестирование ботов</title>
<script src="http://img.xcombats.com/js/Lite/gameEngine.js" type="text/javascript"></script>
<script src="js/jquery-1.11.3.min.js"></script>
<script src="js/ion.rangeSlider.js"></script>
<script type="text/javascript" src="js/title.js"></script>
<link href="http://img.xcombats.com/css/main.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="css/normalize.css" />
<link rel="stylesheet" href="css/ion.rangeSlider.css" />
<link rel="stylesheet" href="css/ion.rangeSlider.skinFlat.css" />
<style type="text/css">
h3 {
	text-align: center;
}
.CSSteam	{ font-weight: bold; cursor:pointer; }
.CSSteam0	{ font-weight: bold; cursor:pointer; }
.CSSteam1	{ font-weight: bold; color: #6666CC; cursor:pointer; }
.CSSteam2	{ font-weight: bold; color: #B06A00; cursor:pointer; }
.CSSteam3 	{ font-weight: bold; color: #269088; cursor:pointer; }
.CSSteam4 	{ font-weight: bold; color: #A0AF20; cursor:pointer; }
.CSSteam5 	{ font-weight: bold; color: #0F79D3; cursor:pointer; }
.CSSteam6 	{ font-weight: bold; color: #D85E23; cursor:pointer; }
.CSSteam7 	{ font-weight: bold; color: #5C832F; cursor:pointer; }
.CSSteam8 	{ font-weight: bold; color: #842B61; cursor:pointer; }
.CSSteam9 	{ font-weight: bold; color: navy; cursor:pointer; }
.CSSvs 		{ font-weight: bold; }
.nprsl0 {
	cursor:pointer;
	filter:progid:DXImageTransform.Microsoft.BasicImage(grayscale=1);
	filter:progid:DXImageTransform.Microsoft.Alpha(opacity=30);
	opacity:0.30;
	filter:alpha(opacity=30);
}
.nprsl0:hover {
	cursor:pointer;
	filter:progid:DXImageTransform.Microsoft.Alpha(opacity=90);
	opacity:0.90;
	filter:alpha(opacity=90);
}
.nprsl1 {
	cursor:pointer;
	filter:progid:DXImageTransform.Microsoft.Alpha(opacity=100);
	opacity:1.00;
	filter:alpha(opacity=100);
}
.ttl_css
{
	position: absolute;
	padding-left: 3px;
	padding-right: 3px;
	padding-top: 2px;
	padding-bottom: 2px;
	background-color: #ffffcc;
	border: 1px solid #6F6B5E;
}
</style>
<link href="http://img.xcombats.com/css/main.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/jquery.js"></script>
<script>
function eff(user,id) {
	if( $('#e'+user+'_'+id).attr('class') == 'nprsl0' ) {
		$('#e'+user+'_'+id).attr({'class':'nprsl1'});
		$('#eu'+user+'_'+id).attr({'value':'1'});
	}else{
		$('#e'+user+'_'+id).attr({'class':'nprsl0'});
		$('#eu'+user+'_'+id).attr({'value':'0'});
	}
}
</script>
</head>
<body bgcolor="#E2E0E0">
<div id="ttl" class="ttl_css" style="display:none;z-index:1111;" /></div>
<div id="gi" class="gifin" style="display:none;"></div>
<H3>Создать тестовый бой (Записать icq персу и скрыть: 17768719)</H3>
<br>
<br>
<center>
<form method="post" action="testing.php">
<?
//

if(isset($_POST['botadminatack2'])) {
	$error = '';	
	$user1 = mysql_fetch_array(mysql_query('SELECT `a`.*,`b`.* FROM `users` AS `a` LEFT JOIN `stats` AS `b` ON `a`.`id` = `b`.`id` WHERE `a`.`id` = "'.mysql_real_escape_string($_POST['botadminatack1']).'" LIMIT 1'));
	$user2 = mysql_fetch_array(mysql_query('SELECT `a`.*,`b`.* FROM `users` AS `a` LEFT JOIN `stats` AS `b` ON `a`.`id` = `b`.`id` WHERE `a`.`id` = "'.mysql_real_escape_string($_POST['botadminatack2']).'" LIMIT 1'));
	if(isset($user1['id'],$user2['id'])) {
		$user1['loclon'] = 1;
		$user2['loclon'] = 1;
		$user1['loclon555'] = 1;
		$user2['loclon555'] = 1;
		//
		mysql_query('INSERT INTO `battle` (`start1` , `city` , `time_start` , `timeout` , `type` ) VALUES (
			"'.time().'","capitalcity" , "'.time().'" , "60" , "329"
		)');		
		$logid = mysql_insert_id();		
		//
		$user1['login'] = $user1['login'].' (Бот для боя '.$logid.')';
		$user2['login'] = $user2['login'].' (Бот для боя '.$logid.')';
		//
		$bot1 = $u->addNewbot(0,NULL,$user1,false,false);
		$bot2 = $u->addNewbot(0,NULL,$user2,false,false);		
		mysql_query('UPDATE `users` SET `battle` = "'.$logid.'" WHERE `id` = "'.$bot1.'" LIMIT 1');
		mysql_query('UPDATE `users` SET `battle` = "'.$logid.'" WHERE `id` = "'.$bot2.'" LIMIT 1');
		mysql_query('UPDATE `stats` SET `hpNow` = 100000000 , `bot` = 2 , `team` = "1" WHERE `id` = "'.$bot1.'" LIMIT 1');
		mysql_query('UPDATE `stats` SET `hpNow` = 100000000 , `bot` = 2 , `team` = "2" WHERE `id` = "'.$bot2.'" LIMIT 1');
		//
		mysql_query('INSERT INTO `battle_logs` (
			`time`,`battle`,`id_hod`,`type`,`text`
		) VALUES (
			"'.time().'","'.$logid.'","1","1","Часы показывали <b>'.date('d.m.Y H:i:s').'</b>, когда <b>'.$user1['login'].'</b> и <b>'.$user2['login'].'</b> бросили вызов друг другу."
		)');
		//
		//Эффекты
		$i = 0;
		while( $i < 1000 ) {
			//
			$efs = mysql_fetch_array(mysql_query('SELECT * FROM `eff_main` WHERE `id2` = "'.$i.'" LIMIT 1'));
			if( $_POST['eu1_'.$efs['id2']] == 1 ) {
				mysql_query('INSERT INTO `eff_users` 
				( `id_eff` , `uid` , `name` , `data` , `timeUse`  ) VALUES (
					"'.$efs['id2'].'" , "'.$bot1.'" , "'.$efs['mname'].'" , "'.$efs['mdata'].'" , "'.time().'"
				)');
			}
			if( $_POST['eu2_'.$efs['id2']] == 1 ) {
				mysql_query('INSERT INTO `eff_users` 
				( `id_eff` , `uid` , `name` , `data` , `timeUse`  ) VALUES (
					"'.$efs['id2'].'" , "'.$bot2.'" , "'.$efs['mname'].'" , "'.$efs['mdata'].'" , "'.time().'"
				)');
			}
			//
			$i++;
		}
		//
		function inuser_go_btl($id) {
			if(isset($id['id'])) {
				file_get_contents('http://xcombats.com/jx/battle/refresh.php?uid='.$id['id'].'&cron_core='.md5($id['id'].'_brfCOreW@!_'.$id['pass']).'&pass='.$id['pass']);
			}
		}
		$sp = mysql_query('SELECT `id`,`time_start` FROM `battle` WHERE `team_win` = "-1" AND `time_over` = "0" AND `type` = 329 LIMIT 100');
		while($pl = mysql_fetch_array($sp)) {
			$user1 = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `battle` = "'.$pl['id'].'" LIMIT 1'));
			inuser_go_btl($user1);
		}	
		//
		$error = 'Бой между <u>'.$user1['login'].'</u> ['.$user1['level'].'] <a  target="_blank" href="/inf.php?'.$bot1.'"><img src="http://img.xcombats.com/i/inf_capitalcity.gif"></a> и <u>'.$user2['login'].'</u> ['.$user2['level'].'] <a href="/inf.php?'.$bot2.'" target="_blank"><img src="http://img.xcombats.com/i/inf_capitalcity.gif"></a> начался! <a href="/logs.php?log='.$logid.'" target="_blank">Лог боя</a>';
	}else{
		$error = 'Один из персонажей не найден в базе.';
	}
	
	echo '<div style="color:red"><b>'.$error.'</b></div>';
}

//
$ef1 = '';
$ef2 = '';
$sp = mysql_query('SELECT * FROM `eff_main` WHERE `mdata` != "" AND `actionTime` > 0 ORDER BY `mname` ASC');
$i = 0;
while( $pl = mysql_fetch_array($sp) ) {
	$i++;
	//
	//Действие эффекта
	$ei = '';
	$tr = ''; $t = $u->items['add'];
	$x = 0; $ed = $u->lookStats($pl['mdata']);	
	while($x<count($t)) {
		$n = $t[$x];
		if(isset($ed['add_'.$n],$u->is[$n])) {
			$z = '';
			if($ed['add_'.$n]>0) {
				$z = '+';
			}
			$tr .= '<br>'.$u->is[$n].': '.$z.''.$ed['add_'.$n];
		}
		$x++;
	}
	if($tr!='') {
		$ei .= $tr;
	}
	if($e['info']!='') {
		$ei .= '<br><i>Информация:</i><br>'.$e['info'];
	}
	//
	$ef = '<img onmouseover="hi(this,\'<b>'.$pl['mname'].'</b>'.$ei.'\',event,3,1,1,1,\'\');" onMouseOut="hic();" onMouseDown="hic();" src="http://img.xcombats.com/i/eff/'.$pl['img'].'" width="40" height="25">';
	$ef1 .= '<input id="eu1_'.$pl['id2'].'" name="eu1_'.$pl['id2'].'" value="0" type="hidden"><span class="nprsl0" id="e1_'.$pl['id2'].'" onclick="eff(1,'.$pl['id2'].');">'.$ef.'</span>';
	$ef2 .= '<input id="eu2_'.$pl['id2'].'" name="eu2_'.$pl['id2'].'" value="0" type="hidden"><span class="nprsl0" id="e2_'.$pl['id2'].'" onclick="eff(2,'.$pl['id2'].');">'.$ef.'</span>';
}
//
$dv = '';
$da = '';
$dv .= '<select style="font-size:12px;" name="botadminatack2"><option value="0">------ Выберите клона из списка ------</option>';
$sp_m = mysql_query('SELECT * FROM `users` WHERE `icq` = "17768719" ORDER BY `id` ASC');
while($pl_m = mysql_fetch_array($sp_m) ) {
	$dv .= '<option value="'.$pl_m['id'].'">'.$pl_m['id'].' [ '.$pl_m['align'].' ] - '.$pl_m['login'].' ['.$pl_m['level'].']</option>';
	$da .= '<option value="'.$pl_m['id'].'">'.$pl_m['id'].' [ '.$pl_m['align'].' ] - '.$pl_m['login'].' ['.$pl_m['level'].']</option>';
}
$dv .= '</select>';
$da = '<select style="font-size:12px;" name="botadminatack1"><option value="0">------ Выберите клона из списка ------</option>'.$da.'</select>';
?>
<table width="700" border="0" cellpadding="10" cellspacing="10">
  <tr>
    <td width="350" align="center" valign="middle">&nbsp;
      <p>
        <?=$da?>
        &nbsp;</p>
      <p>&nbsp;</p></td>
    <td align="center" valign="middle">&nbsp; <b>против</b> &nbsp;</td>
    <td width="350" align="center" valign="middle">&nbsp;
      <p>
        <?=$dv?>
        &nbsp;</p>
      <p>&nbsp;</p></td>
  </tr>
  <tr>
    <td align="center" valign="middle"><?=$ef1?></td>
    <td align="center" valign="middle">&nbsp;<b style="color:#03C">эффекты<br></b>&nbsp;</td>
    <td align="center" valign="middle"><?=$ef2?></td>
  </tr>
  <tr>
    <td align="center" valign="middle">&nbsp;</td>
    <td align="center" valign="middle"><br><br><input class="btnnew" type="submit" value="Начать!"></td>
    <td align="center" valign="middle">&nbsp;</td>
  </tr>
  </table>
<br>
</form>
</center>
</body>
</html>
<? } ?>