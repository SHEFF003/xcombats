<?php
if(!defined('GAME'))
{
	die();
}

$uShow = explode('|',$u->info['showmenu']);
if(isset($_GET['showmenu']))
{
	$_GET['showmenu'] = round($_GET['showmenu']);
	if($_GET['showmenu']>=1 && $_GET['showmenu']<=8)
	{
	 	if($uShow[$_GET['showmenu']-1]==0)
		{
			$uShow[$_GET['showmenu']-1] = 1;
		}else{
			$uShow[$_GET['showmenu']-1] = 0;
		}
		$u->info['showmenu'] = implode('|',$uShow);
		mysql_query('UPDATE `stats` SET `showmenu`="'.$u->info['showmenu'].'" WHERE `id`="'.$u->info['id'].'"');
	} 
}
?>
<style type="text/css">
.linestl1 {
	background-color: #E2E0E0;
	font-size: 10px;
	font-weight: bold;
}
</style>
<script>
function getLine(id,name,a,b,o,id2)
{
	var tss = '<td width="20"><img src="http://img.xcombats.com/i/minus.gif" style="display:block;cursor:pointer;margin-bottom:3px;" title="Скрыть" class="btn-slide" onClick="location=\'main.php?inv=1&otdel=<? echo $_GET['otdel']; ?>&showmenu='+id2+'&rnd=<? echo $code; ?>\';"></td>';
	if(o==0)
	{
		tss ='<td width="20"><img src="http://img.xcombats.com/i/plus.gif" style="display:block;cursor:pointer;margin-bottom:3px;" title="Показать" class="btn-slide" onClick="location=\'main.php?inv=1&otdel=<? echo $_GET['otdel']; ?>&showmenu='+id2+'&rnd=<? echo $code; ?>\';"></td>';
	}
	var sts01 = '<a href="main.php?inv=1&otdel=<? echo $_GET['otdel']; ?>&up='+id+'&rnd=<? echo $code; ?>"><img style="display:block;float:right; margin-bottom:3px;" src="http://img.xcombats.com/i/3.gif"></a>';
	if(id==0)
	{
		sts01 = '<img style="display:block;float:right;margin-bottom:3px;" src="http://img.xcombats.com/i/4.gif">';
	}
	var sts02 = '<a href="main.php?inv=1&otdel=<? echo $_GET['otdel']; ?>&down='+id+'&rnd=<? echo $code; ?>"><img style="display:block; margin-bottom:3px; float:right;" src="http://img.xcombats.com/i/1.gif"></a>';
	if(id==7)
	{
		sts02 = '<img style="display:block;float:right;margin-bottom:3px;" src="http://img.xcombats.com/i/2.gif">';
	}
	var sts2 = '<td width="40"><div style="float:right;">'+sts01+''+sts02+'</div></td>';
	document.write('<table class="mroinv" width="100%" border="0" cellspacing="2" cellpadding="0">'+
	'<tr>'+tss+
    '<td style="font-size:9px;"><span class="linestl1">&nbsp;'+name+'&nbsp;</span></td>'+sts2+'</tr>'+ 
	'</table>');
}

function showDiv (id)
{
	var block = document.getElementById('block_'+id);
	block.style.display = 'block';	
}
function hiddenDiv (id)
{
	var block = document.getElementById('block_'+id);
	block.style.display = 'none';	
}
<?
$rb = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `bank` WHERE `block` = 0 AND `uid` = "'.$u->info['id'].'"'));
?>
function bank_info() {
<? if(!isset($u->bank['id']) && $rb[0]==0){ ?>
	alert('У Вас нет активных счетов. \n\n На правах рекламы: Вы можете открыть счёт в Банке "<? echo $c['title3']; ?>",'+
		' на Страшилкиной улице*\n\n* Мелким шрифтом: услуга платная.');
<?
}elseif($rb[0]>0){ 
?>
				var ddtpswBank = '<div><form action="main.php?inv=1&otdel=<? echo $_GET['otdel']; ?>&rnd=<? echo $code; ?>" method="post">'+
				        '<table style="border:1px solid #B1A996;" width="300" border="0" cellspacing="0" cellpadding="0"><tr><td bgcolor="#B1A996"><div align="center"><strong>Счёт в банке</strong><a href="javascript:void(0)" onclick="document.getElementById(\'chpassbank\').style.display=\'none\'" title="Закрыть окно" style="float:right;padding-right:5px;">x</a></div></td></tr><tr><td bgcolor="#DDD5C2" style="padding:5px;"><div align="center"><small>Выберите счёт и введите пароль<br />'+
                        '<select name="bank" id="bank">'+
						<?
                        $scet = mysql_query('SELECT `id` FROM `bank` WHERE `block` = "0" AND `uid` = "'.$u->info['id'].'"');
                        while ($num_scet = mysql_fetch_array($scet)) 
                        {
                       		 echo "'<option>".$u->getNum($num_scet['id'])."</option>'+";
                        }
						?>
                        '</select><input style="margin-left:5px;" type="password" name="bankpsw" id="bankpsw" /><label></label></small><input style="margin-left:3px;" type="submit" name="button" id="button" value=" ok " /></div></td></tr></table></form></div>';
						var ddtpsBankDiv = document.getElementById('chpassbank');
						if(ddtpsBankDiv!=undefined)
						{
							ddtpsBankDiv.style.display = '';
							ddtpsBankDiv.innerHTML = ddtpswBank;
						}
<?
}
?>
}
function save_com_can()
{
	var ddtpsBankDiv = document.getElementById('chpassbank');
	if(ddtpsBankDiv!=undefined)
	{
		ddtpsBankDiv.style.display = 'none';
		ddtpsBankDiv.innerHTML = '';
	}
}
function save_compl()
{
				var ddtpswBank = '<div><form action="main.php?inv=1&otdel=<? echo $_GET['otdel']; ?>&rnd=<? echo $code; ?>" method="post">'+
				        '<table style="border:1px solid #B1A996;" width="250" border="0" cellspacing="0" cellpadding="0"><tr><td bgcolor="#B1A996"><div align="center"><strong>Сохранить комплект</strong></div></td></tr><tr><td bgcolor="#DDD5C2" style="padding:5px;"><div align="center"><small>Введите название боевого комплекта:<br />'+
                        '<input style="width:90%;" type="text" name="compname" value="" id="compname" /><label></label></small><br><input style="margin-left:3px;cursor:pointer;" type="submit" name="button" id="button" value=" Сохранить " /><input style="margin-left:3px;cursor:pointer;" onClick="save_com_can();" type="button" value=" Отмена " /></div></td></tr></table></form></div>';
						var ddtpsBankDiv = document.getElementById('chpassbank');
						if(ddtpsBankDiv!=undefined)
						{
							ddtpsBankDiv.style.display = '';
							ddtpsBankDiv.innerHTML = ddtpswBank;
						}
}
function za_block(id) {
	if($('#za_block_'+id).css('display') == 'none') {
		$('#za_block_'+id).css('display','');
	}else{
		$('#za_block_'+id).css('display','none');
	}
}
</script>
<style>
.mroinv {
	/*background-color:#e2e2e2;border-top:1px solid #eeeeee;border-left:1px solid #eeeeee;border-right:1px solid #a0a0a0;border-bottom:1px solid #a0a0a0;*/
	background:url(http://img.xcombats.com/i/back.gif) 0 2px;
}
.mroinv img {
	display:inline-block;
	border:0;
	padding-top:3px;
	padding-left:1px;
}
.dot {
	display:block;
	padding-bottom:2px;
    text-decoration: none; /* Убираем подчеркивание */
    border-bottom: 1px dotted #080808; /* Добавляем свою линию */
	cursor:pointer;
}
.dot:hover {
    border-bottom: 1px dotted #080808; /* Добавляем свою линию */
	background-color:#BEBEBE;
}
</style>
<div id="chpassbank" style="display:none; position:absolute; top:50px; left:250px;"></div>
<?php
	
	$rz0 = '';
	$rz1 = '';
	$rz2 = '';
	$rz3 = '';
	$rz4 = '';
	$rz5 = '';
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
	if($u->info['exp'] == 12499 && $c['infinity5level'] == true) {
		$trks5 = '<div align="center"><hr><font color=red>Для получения следующего уровня вам необходим <a target="_blank" href="http://xcombats.com/item/1204">Кристалл Вечности</a></font><hr></div>';
	}
	if( $u->info['exp_eco'] > 0 ) {
		if( $u->info['exp_eco'] > 17500 ) {
			$u->info['exp_eco'] = 17500;
		}
		$trks5 .= '<font color="navy">Замороженный опыт: <b>'.number_format($u->info['exp_eco'], 0, ",", " ").'</b></font><br>';
	}
	echo '
	<table width="100%" border="0" cellspacing="3" cellpadding="0">
	<tr><td>&nbsp;</td></tr>
	<tr>
    <td height="15">
	<small>
	<div style="padding:5px 5px 0px 5px;">
	Опыт:&nbsp;<span style="float0:right"><b>'.number_format($u->info['exp'], 0, ",", " ").'</b> '.$expbase.'</span><br>'.$trks5.'
	Бои:&nbsp;<span style="float0:right"><span title="Побед: '.number_format($u->info['win'], 0, ",", " ").'"><b>'.number_format($u->info['win'], 0, ",", " ").' <img width="7" height="7" title="Побед: '.number_format($u->info['win'], 0, ",", " ").'"
	src="http://img.xcombats.com/i/ico/wins.gif" style="display:inline-block;" /></b></span> &nbsp; <span title="Поражений: '.number_format($u->info['lose'], 0, ",", " ").'"><b>'.number_format($u->info['lose'], 0, ",", " ").' <img width="7" height="7" alt="Поражений: '.number_format($u->info['lose'], 0, ",", " ").'"
	src="http://img.xcombats.com/i/ico/looses.gif" style="display:inline-block;" /></b></span> &nbsp; <span title="Ничьих: '.number_format($u->info['nich'], 0, ",", " ").'"><b>'.number_format($u->info['nich'], 0, ",", " ").' <img width="7" height="7" alt="Ничьих: '.number_format($u->info['nich'], 0, ",", " ").'"
	src="http://img.xcombats.com/i/ico/draw.gif" style="display:inline-block;" /></b></span></span><br />
	<!--Серия побед:&nbsp;'.(0+$u->info['swin']).'<br>
	Серия поражений:&nbsp;'.(0+$u->info['slose']).'<br>-->
	</div><div style="padding:0px 5px 5px 5px;">
	Деньги:&nbsp;<img src="/coin1.png" height="11">&nbsp;<b>'.$u->info['money'].'</b> кр.<br />
	<span style="padding-left:38px;">&nbsp;</span> &nbsp;<img src="/coin2.png" height="11">&nbsp;<b><font color=green>'.$u->bank['money2'].'</b> екр.</font><br />
	Воинственность:&nbsp;<img src="/voins.png" height="11">&nbsp;<b>'.$u->info['money3'].'</b>&nbsp;ед.<br />
	';
	if($u->info['level'] < 8 && $c['zuby'] == true) {
		echo 'Зубы:&nbsp;'.$u->zuby($u->info['money4']).'<br>';
	}
	
	/*
	if($u->info['level']>0 && $u->info['inTurnir'] == 0 && !isset($u->info['noreal']))
	{
		echo'
		<br>Банк:&nbsp;
		';
		if(!isset($u->bank['id']))
		{ 
			if($rb[0]>0)
			{
				echo '<a href="javascript:bank_info();">выбрать счёт</a>';
			}else{
				echo '<a href="javascript:bank_info();">информация</a>';
			}
		}else{
			echo 'счет № <b>'.$u->bank['id'].'</b><br> <img src="/coin1.png" height="11">&nbsp;<b>'.$u->bank['money1'].'</b> кр. <img src="/coin2.png" height="11">&nbsp;<b>'.$u->bank['money2'].'</b> екр. <img style="display:inline-block;cursor:pointer;" src="http://img.xcombats.com/i/close_bank.gif" onClick="top.frames[\'main\'].location=\'main.php?inv=1&otdel='.$_GET['otdel'].'&bank_exit='.$code.'\';" title="Закончить работу со счётом" style="cursor:pointer">';
		}
		echo '<br>';
	}
	*/
	

	if( $u->info['inTurnir'] == 0 ) {
		/*if( $u->stats['silver'] > 0 ) {
			echo '<div style="padding-top:5px;padding-bottom:5px;color:#ad4421;">';
			echo '<a style="color:#ad4421;" href="/main.php?vip='.$u->stats['silver'].'">Благословление Ангелов: <img title="Благословление Ангелов '.$u->stats['silver'].' уровня" src="http://img.xcombats.com/blago/'.$u->stats['silver'].'.png" width="15" height="15" style="vertical-align:sub;display:inline-block;"></a>';
			echo '</div>';
		}else{
			echo '<div style="padding-top:5px;padding-bottom:5px;color:#ad4421;">';
			echo '<a style="color:#ad4421;" href="/benediction/" target="_blank">Благословление Ангелов: <img title="Купите Благословление Ангелов чтобы стать лучше" src="http://img.xcombats.com/blago/1d.png" width="15" height="15" style="vertical-align:sub;display:inline-block;"></a>';
			echo '</div>';
		}*/
	}

	if( $u->info['level'] > 3 && $u->info['inTurnir'] == 0 && $c['bonusonline'] == true && !isset($u->info['noreal']) ) {
		$bns = mysql_fetch_array(mysql_query('SELECT `id`,`time` FROM `aaa_bonus` WHERE `uid` = "'.$u->info['id'].'" AND `time` > '.time().' LIMIT 1'));
		if(isset($_GET['takebns']) && $u->newAct($_GET['takebns'])==true && !isset($bns['id'])) {
			$u->takeBonus();
			$bns = mysql_fetch_array(mysql_query('SELECT `id`,`time` FROM `aaa_bonus` WHERE `uid` = "'.$u->info['id'].'" AND `time` > '.time().' AND `type` = 0 LIMIT 1'));
		}
		if(isset($bns['id'])) {
			echo '<button style="width:224px;margin-top:5px;" onclick="alert(\'Вы сможете взять бонус через '.$u->timeOut($bns['time']-time()).'\');" class="btnnew"> Через '.$u->timeOut($bns['time']-time()).'  </button>';
		}else{
			//echo 'Получить бонус:<br><div align="left"><button class="btnnew" onclick="location.href=\'main.php?inv=1&takebns='.$u->info['nextAct'].'\';">25 кр.!</button>';
			//echo '<button class="btnnew" onclick="location.href=\'main.php?inv=1&takebns='.$u->info['nextAct'].'&getb1w=2\';">'.($u->info['level']*3).' Воин.!</button><button class="btnnew" onclick="location.href=\'main.php?inv=1&takebns='.$u->info['nextAct'].'&getb1w=3\';">1 екр.</button></div>';
			//if( $u->info['level'] == 7 ) {
			//	echo '<button style="width:224px;margin-top:5px;" class="btnnew" onclick="top.captcha(\'Бонус за онлайн\',\'main.php?inv=1&takebns='.$u->info['nextAct'].'&getb1w=3\')">Получить '.$u->zuby(round($u->info['level']*0.75,2),1).'</button></div>';
			//}elseif( $u->info['level'] >= 8 ) {
				if( $u->info['align'] == 0 || $u->info['align'] == 2 ) {
					$ngtxt = 'Получить '.round($c['bonline'][0][$u->info['level']]*$c['bonusonline_kof'],2).' кр.';
				}elseif( $u->info['align'] > 0 ) {
					$ngtxt = 'Получить '.round($c['bonline'][1][$u->info['level']]*$c['bonusonline_kof'],2).' екр.';
				}				
				echo '<button style="width:224px;margin-top:5px;" class="btnnew" onclick="top.captcha(\'Бонус за онлайн\',\'main.php?inv=1&takebns='.$u->info['nextAct'].'&getb1w=3\')">'.$ngtxt.'</button>';
			//}else{
			//	$expad = round(50+($u->stats['levels']['exp']-$u->info['exp'])/2);
			//	if($expad > 100) {
			//		$expad = 100;
			//	}
			//	echo '<button style="width:224px;margin-top:5px;" class="btnnew" onclick="top.captcha(\'Бонус за онлайн\',\'main.php?inv=1&takebns='.$u->info['nextAct'].'&getb1w=3\')">Получить '.$expad.' опыта</button></div>';
			//}
		}
		echo '<button onclick="location.href=\'pay.php\';" style="width:224px;margin-top:5px;" class="btnnew"><img src="/coin2.png" height="12"><b><font color=green> Покупка екр. онлайн</font></b></button>';
		echo '</div>';
	}
	
	//Получение уровня задания
	if( $u->info['level'] >= 0 && $u->info['inTurnir'] == 0 && $c['bonuslevel'] == true && !isset($u->info['noreal']) && $c['bonussocial'] == true ) {
		/*
[1] Нужно докачаться на клоне до 1 уровня.
[2]-[3]-[4] Нужно подтвердить e-mail чтобы получить со 2 по 4 уровень на выбор.
[5]-[6] Нужно подтвердить страницу ВКонтакте чтобы получить 5 или 6 уровень на выбор.
[7] Нужно привести 1 друга.
[8] Нужно привести 3 друзей и провести 3 хаота.
[9] Нужно привести 5 друзей и провести 50 хаотов.
[10] Нужно привести 7 друзей и провести 100 хаотов.
		*/
		$mxlvl = mysql_fetch_array(mysql_query('SELECT `id`,`level` FROM `users` WHERE `real` = 1 AND `admin` = 0 AND `banned` = 0 ORDER BY `level` DESC LIMIT 1'));
		if(isset($mxlvl['id']) && $mxlvl['level'] > $u->info['level']+1) {
			$gd = 1;
			$gb = 1;
			$sl = $u->info['level'];
			$ml = $u->info['level']+1;
			//
			if(isset($_GET['takelevelplease'])) {
				$er8 = '';
				//
				if($ml <= 1) {
					$er8 = 'Качайтесь на клоне до 1 уровня.';
				}elseif($ml <= 4) {
					$mcf = mysql_fetch_array(mysql_query('SELECT * FROM `mini_actions` WHERE `uid` = "'.$u->info['id'].'" AND `val` = "mailconfirm" AND `ok` > 0 LIMIT 1'));
					if(!isset($mcf['id'])) {
						$er8 = 'Вы не подтвердили E-mail.';
					}else{
						if($ml == 2) {
							mysql_query('UPDATE `stats` SET `exp` = 420 WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						}elseif($ml == 3) {
							mysql_query('UPDATE `stats` SET `exp` = 1300 WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						}elseif($ml == 4) {
							mysql_query('UPDATE `stats` SET `exp` = 2500 WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						}
						$er8 = 'Вы успешно получили '.$ml.' уровень!';
						$u->info['level']++;
						$ml++;
					}
				}elseif($ml <= 6) {
					$mcf = mysql_fetch_array(mysql_query('SELECT * FROM `mini_actions` WHERE `uid` = "'.$u->info['id'].'" AND (`val` = "vkauth" OR `val` = "fbauth" OR `val` = "okauth") LIMIT 1'));
					if(!isset($mcf['id'])) {
						$er8 = 'Вы не подтвердили страницу в социальной сети.';
					}else{
						$itmsv = mysql_fetch_array(mysql_query('SELECT `id` FROM `items_users` WHERE `item_id` = 1204 AND `delete` = 0 AND `inShop` = 0 AND `inTransfer` = 0 AND `uid` = "'.$u->info['id'].'" LIMIT 1'));
						if($ml == 5) {
							mysql_query('UPDATE `stats` SET `exp` = 5000 WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						}elseif($ml == 6) {
							mysql_query('UPDATE `stats` SET `exp` = 12500 WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						}
						if($ml == 6 && !isset($itmsv['id']) && $c['infinity5level'] == true) {
							$er8 = 'Требуется Кристалл Вечности!';
						}else{
							if($ml == 6) {
								mysql_query('INSERT INTO `mini_actions` (
									`uid`,`time`,`val`,`var`
								) VALUES (
									"'.$u->info['id'].'","'.time().'","mbtnlvl6","0"
								)');
							}
							$er8 = 'Вы успешно получили '.$ml.' уровень!';
							$u->info['level']++;
							$ml++;
						}
					}
				}elseif($ml <= 7) {
					$tstlvl = mysql_fetch_array(mysql_query('SELECT * FROM `mini_actions` WHERE `uid` = "'.$u->info['id'].'" AND `val` = "mbtnlvl6" LIMIT 1'));
					//$refs = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `users` WHERE `level` >= 5 AND `timereg` > "'.(0+$tstlvl['time']).'" AND `real` = 1 AND `host_reg` = "'.$u->info['id'].'" LIMIT 1'));
					//$btls = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `battle` WHERE `time_start` > "'.(0+$tstlvl['time']).'" AND `id` IN (SELECT `battle_id` FROM `battle_last` WHERE `uid` = "'.$u->info['id'].'" AND `time` > "'.(0+$tstlvl['time']).'") LIMIT 1'));
					//
					$btls[0] = $u->info['win'];
					if( $btls[0] >= 50 ) {
						//
						mysql_query('UPDATE `stats` SET `exp` = 30000 WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						//
						mysql_query('INSERT INTO `mini_actions` (
							`uid`,`time`,`val`,`var`
						) VALUES (
							"'.$u->info['id'].'","'.time().'","mbtnlvl7","0"
						)');
						$er8 = 'Вы успешно получили '.$ml.' уровень!';
						$u->info['level']++;
						$ml++;
					}else{
						$er8 = 'Вы не провели несколько боев (Осталось '.(50-$btls[0]).' побед).';
					}
				}elseif($ml <= 8) {
					$tstlvl = mysql_fetch_array(mysql_query('SELECT * FROM `mini_actions` WHERE `uid` = "'.$u->info['id'].'" AND `val` = "mbtnlvl7" LIMIT 1'));
					$refs = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `users` WHERE `level` >= 5 AND `timereg` > "'.(0+$tstlvl['time']).'" AND `real` = 1 AND `host_reg` = "'.$u->info['id'].'" LIMIT 1'));
					$btls = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `battle` WHERE `time_start` > "'.(0+$tstlvl['time']).'" AND `razdel` = 5 AND `id` IN (SELECT `battle_id` FROM `battle_last` WHERE `uid` = "'.$u->info['id'].'" AND `time` > "'.(0+$tstlvl['time']).'") LIMIT 1'));
					//
					if($refs[0] < 3 || $btls[0] < 3) {
						$er8 = 'Вы не пригласили '.(0+$refs[0]).'/3 друзей или не провели '.(0+$btls[0]).'/3 хаота.';
					}else{
						//
						mysql_query('UPDATE `stats` SET `exp` = 300000 WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						//
						mysql_query('INSERT INTO `mini_actions` (
							`uid`,`time`,`val`,`var`
						) VALUES (
							"'.$u->info['id'].'","'.time().'","mbtnlvl8","0"
						)');
						$er8 = 'Вы успешно получили '.$ml.' уровень!';
						$u->info['level']++;
						$ml++;
					}
				}elseif($ml <= 9) {
					$er8 = 'Вы не пригласили 5 друзей или не провели 50 хаотов.';
					$er8 = 'Доступ закрыт!';
				}elseif($ml <= 10) {
					$er8 = 'Вы не пригласили 7 друзей или не провели 100 хаотов.';
					$er8 = 'Доступ закрыт!';
				}else{
					$er8 = 'Что-то тут не так...';
					$er8 = 'Доступ закрыт!';
				}
				//
				if($sl != $u->info['level']) {
					
					die('<script>location.href="/main.php?inv";</script>');
				}
				if(isset($mxlvl['id']) && ($mxlvl['level'] > $ml+1 || $u->info['admin'] > 0)) {
					//
				}else{
					if($sl != $u->info['level']) {
						$gd = 0;
					}
				}
			}
			//
			if($ml <= 1) {
				$zd = '<font color=red>Нужно докачаться на клоне до 1 уровня.</font>';
				$gb = 1;
			}elseif($ml <= 4) {
				$zd = 'Нужно подтвердить E-mail, чтобы получить 2-4 уровень.';
				$gb = 1;
			}elseif($ml <= 6) {
				$zd = 'Нужно подтвердить страницу в социальной сети, чтобы получить 5-6 уровень.';
				$gb = 1;
			}elseif($ml <= 7) {
				$zd = 'Нужно <!--привести одного друга по реферальной ссылке и -->выиграть 50 боев, чтобы получить 7 уровень.';
				$gb = 1;
			}elseif($ml <= 8) {
				$zd = 'Нужно привести 3 друзей по реферальной ссылке и провести 3 хаотичных поединка, чтобы получить 8 уровень.';
				$gb = 1;
			}elseif($ml <= 9) {
				$zd = 'Нужно привести 5 друзей по реферальной ссылке и провести 50 хаотичных поединков, чтобы получить 9 уровень.';
				$gb = 1;
			}elseif($ml <= 10) {
				$zd = 'Нужно привести 7 друзей по реферальной ссылке и провести 100 хаотичных поединков, чтобы получить 10 уровень.';
				$gb = 1;
			}else{
				$zd = '';
				$gb = 0;
			}
			//
			if($gd == 1) {
				//
				if($zd!='') {
					echo '<hr><b><center>Необходимо выполнить задание:</center></b><div align="center" style="border:1px solid #aeaeae;background-color:#FFF;margin-top:3px;padding:4px;">'.$zd.'</div>';
				}else{
					echo '<hr>';
				}
				if($er8 != '') {
					echo '<div style="padding:3px;" align="center"><font color=red>'.$er8.'</font></div>';
				}
				//
				if($gb == 1 && $ml > 1) {
					//Получаем
					echo '<button style="width:224px;margin-top:5px;" class="btnnew" onclick="location.href=\'/main.php?inv&takelevelplease=1\';">Получить '.($u->info['level']+1).' уровень</button></div>';
				}
				//
			}
		}
	}
	
	//Бонус за мыло, контакт
	if(!isset($u->info['noreal']) && $c['bonussocial'] == true) {
		$mcf = mysql_fetch_array(mysql_query('SELECT * FROM `mini_actions` WHERE `uid` = "'.$u->info['id'].'" AND `val` = "mailconfirm" LIMIT 1'));
		if((isset($mcf['id']) && $mcf['ok'] == 0) || !isset($mcf['id'])) {
			if(isset($_GET['confmail'])) {
				//
				$gd = 0;
				$zdml = 3600; //час
				if(isset($mcf['id']) && $mcf['time'] > time() - $zdml ) {
					echo '<hr><center><font color="red">Нельзя подтверждать эл.почту так часто. Осталось '.$u->timeOut($mcf['time']+$zdml-time()).'</font></center>';
					$gd = 1;
				}elseif(!preg_match('#^[a-z0-9.!\#$%&\'*+-/=?^_`{|}~]+@([0-9.]+|([^\s]+\.+[a-z]{2,6}))$#si', $_GET['confmail'])) {
					echo '<hr><center><font color="red">Вы указали явно ошибочный E-mail.</font></center>';
					$gd = 1;
				}
				
				if( $gd == 0 ) {
					if(isset($mcf['id'])) {
						mysql_query('UPDATE `mini_actions` SET `time` = "'.time().'",`var` = "'.mysql_real_escape_string($_GET['confmail']).'" WHERE `id` = "'.$mcf['id'].'" LIMIT 1');
					}else{
						mysql_query('INSERT INTO `mini_actions` (`uid`,`time`,`val`,`var`,`ok`) VALUES (
							"'.$u->info['id'].'","'.time().'","mailconfirm","'.mysql_real_escape_string($_GET['confmail']).'","0"
						)');
					}
					function sendmail($message,$keymd5,$mail) {
						global $u;
						//
						$md5mail = md5($keymd5.'+'.$mail);
						//
						$msgtxt = 'Для подтверждения вашего E-mail у персонажа <b>'.$u->info['login'].'</b> перейдите по ссылке:';
						$msgtxt .= ' <a href="http://xcombats.com/mail/key='.$md5mail.'&mail='.$mail.'">Нажмите тут</a> (http://xcombats.com/mail/key='.$md5mail.'&mail='.$mail.')';
						$msgtxt .= '<br>Переходя по ссылке, вы подтверждаете свое желание получать игровые новости.';
						$msgtxt .= '<br>Если вы не имеете отношения к нашей игре и не хотите получать письма, тогда перейдите по этой ссылке: <a href="http://xcombats.com/mail/key='.$md5mail.'&mail='.$mail.'&cancel">Нажмите тут</a> (http://xcombats.com/mail/key='.$md5mail.'&mail='.$mail.'&cancel)<br><br>- - - - - - -<br><br>С уважением,<br>Администрация Легендарного Бойцовского Клуба';
						//
						$headers  = "MIME-Version: 1.0\r\n";
						$headers .= "Content-type: text/html; charset=windows-1251\r\n";
						$headers .= "From: Старый БК <zahodite@xcombats.com> \r\n";		
						$to = $mail;
						//
						$subject = 'СБК: '.$u->info['login'] . ' - Подтверждение вашей эл.почты';
						//
						if (mail($to, $subject, $msgtxt, $headers) == true) {
							return true;
						}else{
							return false;
						}
					}
					//
					$mcf = mysql_fetch_array(mysql_query('SELECT * FROM `mini_actions` WHERE `uid` = "'.$u->info['id'].'" AND `val` = "mailconfirm" LIMIT 1'));
					//
					sendmail( '' , 'mailconf*15' , $mcf['var'] );
					//
					echo '<hr><center><font color="red">На ваш E-mail отправлено письмо.</font></center>';
					//
				}
			}
			$mcff = 'Подтвердить E-mail за 1 екр.';
			if(isset($mcf['id'])) {
				$mcff = '<b>'.$mcf['var'].'</b><br><font color="grey"><small>(На этот адрес отправлено письмо)</small></font>';
			}
			echo '<div align="center"><button style="width:224px;margin-top:5px;" class="btnnew" onclick="top.mailConf();"><img src="http://img.xcombats.com/mini_mail.png" height="13" width="13"> '.$mcff.'</button></div>';
		}else{
			//echo '<div align="center"><button style="width:224px;margin-top:5px;" class="btnnew" onclick="top.mailConf();"><img src="http://img.xcombats.com/mini_mail.png" height="13" width="13"> Подтвердить E-mail за 1 екр.</button></div>';
		}
		
		$mcf = mysql_fetch_array(mysql_query('SELECT * FROM `mini_actions` WHERE `uid` = "'.$u->info['id'].'" AND (`val` = "vkauth" OR `val` = "fbauth" OR `val` = "okauth") LIMIT 1'));
		if(!isset($mcf['id'])) {
			require_once('vk/VK.php');
			require_once('vk/VKException.php');
			$vk_config = array(
				'app_id'        => '5145826',
				'api_secret'    => 'V90yIzlgSglfgrnHw7Ny',
				'callback_url'  => 'http://xcombats.com/social.php?vkconnect',
				'api_settings'  => 'offline,friends,email'
			);
				
			if(isset($_GET['vkconnect'])) {
				echo '<hr>';				
				try {
					$vk = new VK\VK($vk_config['app_id'], $vk_config['api_secret']);
					
					if (!isset($_REQUEST['code'])) {
						/**
						 * If you need switch the application in test mode,
						 * add another parameter "true". Default value "false".
						 * Ex. $vk->getAuthorizeURL($api_settings, $callback_url, true);
						 */
						$authorize_url = $vk->getAuthorizeURL(
						$vk_config['api_settings'], $vk_config['callback_url']);
							
						/*echo '<script>location.href="'.$authorize_url.'";</script>';
							*/
						//echo '<a target="_blank" href="' . $authorize_url . '">Подключить свой аккаунт VK.com</a>';
					} else {
						$access_token = $vk->getAccessToken($_REQUEST['code'], $vk_config['callback_url']);
						
						echo 'access token: ' . $access_token['access_token']
							. '<br />expires: ' . $access_token['expires_in'] . ' sec.'
							. '<br />user id: ' . $access_token['user_id'] . '<br /><br />';
							
					}
				} catch (VK\VKException $error) {
					echo $error->getMessage();
				}
				
				echo '<hr>';		
			}else{
				$vk = new VK\VK($vk_config['app_id'], $vk_config['api_secret']);
				$authorize_url = $vk->getAuthorizeURL(
				$vk_config['api_settings'], $vk_config['callback_url']);
			}
			
			echo '<div align="center"><hr>';
			
			echo 'Подтвердите одну из страничек в социальных сетях за 1 екр. и 150 кр.<br><br>';
			
			echo '<button style="width:224px;margin-top:5px;" class="btnnew" onclick="window.open(\''.$authorize_url.'\', \'opener\', \'width=660,height=450\');"><img src="http://img.xcombats.com/vk.png" height="13" width="13"> Подтвердить ВКонтакте &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;</button>';
			
			echo '<br>или<br>';
			
			echo '<button style="width:224px;margin-top:5px;" class="btnnew" onclick="window.open(\'http://xcombats.com/social.php?fbconnect\', \'opener\', \'width=660,height=450\');"><img src="http://img.xcombats.com/fb.png" height="13" width="13"> Подтвердить Facebook &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</button>';
			
			//echo '<br>или<br>';
			
			//echo '<button style="width:224px;margin-top:5px;" class="btnnew" onclick="window.open(\''.$authorize_url.'\', \'opener\', \'width=660,height=450\');"><img src="http://img.xcombats.com/ok.png" height="13" width="13"> Подтвердить Одноклассники</button>';
			
			echo '</div>';
		}
	}
	echo '</div>';
	$prt = explode('|',$u->info['prmenu']);
	if(isset($_GET['up']))
	{
		$i = 0;
		if(isset($prt[$_GET['up']],$prt[$_GET['up']-1]))
		{
			$prt1 = $prt[intval($_GET['up'])];
			$prt[$_GET['up']] = $prt[$_GET['up']-1];
			$prt[$_GET['up']-1] = $prt1;
			$prtNew = implode('|',$prt);
			$u->info['prmenu'] = $prtNew;
			mysql_query('UPDATE `stats` SET `prmenu`="'.mysql_real_escape_string($u->info['prmenu']).'" WHERE `id`="'.$u->info['id'].'" LIMIT 1');
			$prt = explode('|',$u->info['prmenu']);
		}
	}elseif(isset($_GET['down']))
	{
		$i = 0;
		if(isset($prt[$_GET['down']],$prt[$_GET['down']+1]))
		{
			$prt1 = $prt[intval($_GET['down'])];
			$prt[$_GET['down']] = $prt[$_GET['down']+1];
			$prt[$_GET['down']+1] = $prt1;
			$prtNew = implode('|',$prt);
			$u->info['prmenu'] = $prtNew;
			mysql_query('UPDATE `stats` SET `prmenu`="'.mysql_real_escape_string($u->info['prmenu']).'" WHERE `id`="'.$u->info['id'].'" LIMIT 1');
			$prt = explode('|',$u->info['prmenu']);
		}
	}
	
	$i = 0;
	while($i<count($prt))
	{
		$prtpos[$prt[$i]] = $i;
		$i++;
	}
	
	$rz0 = '<script>getLine('.$prtpos[0].',"Характеристики ","0","0","'.$uShow[0].'",1);</script>';
	$rz0 .= '<font id="rz0">';
	if($uShow[0]==1){
		$i = 1;
		while( $i <= 10 ) {
			$u->stats['s'.$i] = 0+$u->stats['s'.$i];
			$i++;
		}
		$rz0 .= '
		Сила: <b>'.$u->stats['s1'].'</b><br />
		Ловкость:&nbsp;<b>'.$u->stats['s2'].'</b><br />
		Интуиция:&nbsp;<b>'.$u->stats['s3'].'</b><br />
		Выносливость:&nbsp;<b>'.$u->stats['s4'].'</b><br />
		';
		if($u->info['level'] >= 4 || $u->stats['n5']!=0)
		{
			$rz0 .= '
			Интеллект:&nbsp;<b>'.$u->stats['s5'].'</b><br />
			';
		}
		if($u->info['level'] >= 7 || (@isset($u->stats['n6']) && @$u->stats['n6']>0))
		{
			$rz0 .= '
			Мудрость:&nbsp;<b>'.@$u->stats['s6'].'</b><br />
			';
		}
		if($u->info['level'] >= 10 || @$u->stats['s7']>0)
		{
			$rz0 .= '
			Духовность:&nbsp;<b>'.@$u->stats['s7'].'</b><br />
			';
		}
		if($u->info['level'] >= 14 || @$u->stats['s8']>0)
		{
			$rz0 .= '
			Воля:&nbsp;<b>'.@$u->stats['s8'].'</b><br />
			';
		}
		if($u->info['level'] >= 15 || @$u->stats['s9']>0)
		{
			$rz0 .= '
			Свобода духа:&nbsp;<b>'.@$u->stats['s9'].'</b><br />
			';
		}
		if($u->info['level'] >= 20 || @$u->stats['s10']>0)
		{
			$rz0 .= '
			Божественный:&nbsp;<b>'.@$u->stats['s10'].'</b><br />
			';
		}
			//$rz0 .= '
			//Энергия:&nbsp;<b>'.(0+$u->stats['s11']).'</b> &nbsp; <small>['.round($u->info['enNow'],3).'/'.$u->stats['enAll'].']</small><br />
			//';
		if($u->info['ability'] > 0)
		{ 
			$rz0 .= '&nbsp;<a href="main.php?skills=1&side=1">+ Способности</a>'; 
			if($u->info['skills'] != 0)
			{
				$rz0 .= '<br>';
			} 
		}

		if($u->info['skills'] > 0 && $u->info['level'] > 0)
		{ 
			$rz0 .= '&nbsp;&bull; <a href="main.php?skills=1&side=1">Обучение</a><br />'; 
		}
	}
	$rz0 .= '</font>';
	$rz1 = '<script>getLine('.$prtpos[1].',"Модификаторы ","0","0",'.$uShow[1].',2);</script>';
	if($uShow[1]==1)
	{
		//if( $u->info['admin'] > 0 ) {
			$rz1 .= 'Урон: '.$u->inform('yrontest').'';
			$rz1 .= '<br><font color=maroon>Крит. урон: '.$u->inform('yrontest-krit').'</font>';
		//}else{
		//	$rz1 .= 'Урон: '.$u->inform('yron');
		//}
			$rz1 .= '
			<br>
			<span>Мф. крит. удара: '.$u->inform('m1').'';
			//if($u->inform('m3')!='0')
			//{
				$rz1 .='
				</span><br>
				<nobr>
				<span>Мф. мощности крит. удара: '.$u->inform('m3').'';
			//}
			if( $u->inform('antm3') != '0' && $u->info['admin'] > 0 ) {
				$rz1 .='
				</span><br>
				<nobr>
				<span>Мф. против мощности крита: '.$u->inform('antm3').'';
			}
			$rz1 .= '
			</span></nobr><br />
			<span>Мф. против крит. удара: '.$u->inform('m2').'';
			$rz1 .= '</span><br />
			<span>Мф. увертывания: '.$u->inform('m4').'';
			$rz1 .= '</span><br>
			<nobr><span>Мф. против увертывания: '.$u->inform('m5').'';
			$rz1 .= '</span></nobr><br>
			<span>Мф. пробоя брони: '.$u->inform('m9').'';
			$rz1 .= '</span><br>
			<span>Мф. контрудара: '.$u->inform('m6').'';
			$rz1 .='
			</span><br>
			<span>Мф. парирования: '.$u->inform('m7').'';
			$rz1 .= '</span><br />
			<span>Мф. блока щитом: '.$u->inform('m8').'';
			$rz1 .= '</span>';
		$rz1 .= '</nobr>';
	}
	$rz2 ='<script>getLine('.$prtpos[2].',"Броня ","0","0",'.$uShow[2].',3);</script>';
	if($uShow[2]==1)
	{		
		$rz2 .= '
		Броня головы: '.$u->stats['mib1'].'-'.$u->stats['mab1'].' ('.($u->stats['mib1']).'+d'.($u->stats['mab1']-($u->stats['mib1'])+1).')<br />
		Броня груди: '.$u->stats['mib2'].'-'.$u->stats['mab2'].' ('.($u->stats['mib2']).'+d'.($u->stats['mab2']-($u->stats['mib2'])+1).')<br />
		Броня живота: '.$u->stats['mib2'].'-'.$u->stats['mab2'].' ('.($u->stats['mib2']).'+d'.($u->stats['mab2']-($u->stats['mib2'])+1).')<br />
		Броня пояса: '.$u->stats['mib3'].'-'.$u->stats['mab3'].' ('.($u->stats['mib3']).'+d'.($u->stats['mab3']-($u->stats['mib3'])+1).')<br />
		Броня ног: '.$u->stats['mib4'].'-'.$u->stats['mab4'].' ('.($u->stats['mib4']).'+d'.($u->stats['mab4']-($u->stats['mib4'])+1).')<br />';
	}
	$rz3 = '<script>getLine('.$prtpos[3].',"Мощность ","0","0",'.$uShow[3].',4);</script>';
	if($uShow[3]==1)
	{
		$i = 1;
		while($i<=4)
		{
			$rz3 .= ucfirst(str_replace('Мф. мощности','Мощность ',$u->is['pa'.$i].': '));
			if($u->stats['pa'.$i]>0)
			{
				//$rz3 .= '+';
			}
			//$rz3 .= $u->stats['pa'.$i].'<br />';
			$rz3 .= $u->inform('pa'.$i).'<br>';
			$i++;
		}
		$i = 1;
		while($i<=7)
		{
			$rz3 .= ucfirst(str_replace('Мф. мощности ','Мощность ',$u->is['pm'.$i].': '));
			if($u->stats['pm'.$i]>0)
			{
				//$rz3 .= '+';
			}
			//$rz3 .= $u->stats['pm'.$i].'<br />';
			$rz3 .= $u->inform('pm'.$i).'<br>';
			$i++;
		}
	}
	
	$zi = array( //Предметы влияющие на зоны
		'n' => array(
			'','голова','грудь','живот','пояс','ноги'
		),
		1 => array( 1 , 8 , 9 , 52 ), //голова
		2 => array( 4 , 5 , 6 ), //грудь
		3 => array( 2 , 4 , 5 , 6 , 13 ), //живот
		4 => array( 7 , 16 , 10 , 11 , 12 ), //пояс
		5 => array( 17 )  //ноги
	);
		
	$rz4 = '<script>getLine('.$prtpos[4].',"Защита: ","0","0",'.$uShow[4].',5);</script>';
	if($uShow[4]==1)
	{
		function zago($v) {
			if($v > 1000) {
				$v = 1000;
			}
			$r = (1-( pow(0.5, ($v/250) ) ))*100;
			return $r;
		}
		//if($u->info['admin'] > 0 ) {
			//echo '<hr>';
			//print_r($u->stats['sv_']);
		//}
		$i = 1;
		while($i<=4)
		{
			$rz4 .= '<span onclick="za_block('.$i.')" class="dot">'.ucfirst(/*str_replace('Защита от ','',*/$u->is['za'.$i]/*)*/).': ';
			if($u->stats['za'.$i]>0)
			{
				//$rz4 .= '+';
			}
			$rz4 .= ''.$u->stats['za'.$i].' ('.round(zago($u->stats['za'.$i])).'%)</span>';
			$rz4 .= '<span style="display:none" id="za_block_'.$i.'">';
			$j = 1;
			while( $j <= 5 ) {
				$k = 0;
				$rk = $u->stats['za'.$i];
				while( $k < count($zi[$j]) ) {
					if( $u->stats['sv_']['z'][$zi[$j][$k]] > 0 ) {
						$rk += $u->stats['sv_']['z'][$zi[$j][$k]]['za']+$u->stats['sv_']['z'][$zi[$j][$k]]['za'.$i];
					}
					$k++;
				}
				$rz4 .= ' &nbsp; &nbsp; <span style="min-width:55px;display:inline-block;">&bull; '.$zi['n'][$j].':</span> '.$rk.' ('.round(zago($rk)).'%)<br>';
				$j++;
			}
			$rz4 .= '</span>';
			$i++;
		}
		$i = 1;
		while($i<=7)
		{
			/*$rz4 .= ucfirst(str_replace('Защита от ','',$u->is['zm'.$i])).': ';
			if($u->stats['zm'.$i]>0)
			{
				//$rz4 .= '+';
			}
			$rz4 .= $u->stats['zm'.$i].' ('.round(zago($u->stats['zm'.$i])).'%)<br />';
			$i++;*/
			$rz4 .= '<span onclick="za_block('.($i+4).')" class="dot">'.ucfirst(/*str_replace('Защита от ','',*/$u->is['zm'.$i]/*)*/).': ';
			if($u->stats['zm'.$i]>0)
			{
				//$rz4 .= '+';
			}
			$rz4 .= ''.$u->stats['zm'.$i].' ('.round(zago($u->stats['zm'.$i])).'%)</span>';
			$rz4 .= '<span style="display:none" id="za_block_'.($i+4).'">';
			$j = 1;
			while( $j <= 5 ) {
				$k = 0;
				$rk = $u->stats['zm'.$i];
				while( $k < count($zi[$j]) ) {
					if( $u->stats['sv_']['z'][$zi[$j][$k]] > 0 ) {
						$rk += $u->stats['sv_']['z'][$zi[$j][$k]]['zm']+$u->stats['sv_']['z'][$zi[$j][$k]]['zm'.$i];
					}
					$k++;
				}
				$rz4 .= ' &nbsp; &nbsp; <span style="min-width:55px;display:inline-block;">&bull; '.$zi['n'][$j].':</span> '.$rk.' ('.round(zago($rk)).'%)<br>';
				$j++;
			}
			$rz4 .= '</span>';
			$i++;
		}
	}
	$rz5 = '<script>getLine('.$prtpos[5].',"Кнопки ","0","0",'.$uShow[5].',6);</script>';
	if($uShow[5]==1)
	{
		$rz5 .= '<center style="padding:5px;">';
		$rz5 .= '<input class="btnnew3" style="padding:3px 15px 3px 15px;" type="button" name="snatvso" value="Снять всё" class="btn" onclick="top.frames[\'main\'].location=\'main.php?inv=1&remitem&otdel='.$_GET['otdel'].'\';"
		style="font-weight:bold;" />
		&nbsp;';
		$hgo = $u->testHome();
		if(!isset($hgo['id']))
		{
			$rz5 .=  '<input class="btnnew3" style="padding:3px 15px 3px 15px;" type="button" value="Возврат" class="btn" onclick="top.frames[\'main\'].location=\'main.php?homeworld&rnd='.$code.'\';" style="font-weight:bold;width: 90px" />';
		}
		unset($hgo);
		$rz5 .= '</center>';
		$rz5 .=  '';
	}
	
	$rz6 ='<script>getLine('.$prtpos[6].',"Комплекты&nbsp;&nbsp;&nbsp;<a href=\"#\" onClick=\"save_compl();\">запомнить</a>&nbsp;","0","0",'.$uShow[6].',7);</script>';
	if($uShow[6]==1)
	{
		$rz6 .= '<div>';
		$sp = mysql_query('SELECT * FROM `save_com` WHERE `uid` = "'.$u->info['id'].'" AND `delete` = "0" LIMIT 10');
		while($pl = mysql_fetch_array($sp))
		{
			$rz6 .= '<a href="?inv=1&delc1='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&rnd='.$code.'"><img src="http://img.xcombats.com/i/close2.gif" title="Удалить комплект" width="9" height="9"></a> <small><a href="?inv=1&usec1='.$pl['id'].'&otdel='.((int)$_GET['otdel']).'&rnd='.$code.'">Надеть &quot;'.$pl['name'].'&quot;</a></small><br>';
		}
		$rz6 .= '</div>';
	}
	
	$rz7 ='<script>getLine('.$prtpos[7].',"Приемы &nbsp; &nbsp;<a href=\"/main.php?skills=1&rz=4&rnd='.$code.'\">настроить</a>&nbsp;","0","0",'.$uShow[7].',8);</script>';
	if($uShow[7]==1)
	{
		$rz6 .= '<div>';
		$sp = mysql_query('SELECT * FROM `complects_priem` WHERE `uid` = "'.$u->info['id'].'" LIMIT 10');
		$rz7 .= '<small>';
		while($pl = mysql_fetch_array($sp)) {
			$rz7 .= '<a onclick="if(confirm(\'Удалить набор  ?\')){location=\'main.php?inv=1&otdel='.round((int)$_GET['otdel']).'&delcop='.$pl['id'].'\'}" href="javascript:void(0)"><img src="http://'.$c['img'].'/i/close2.gif" width="9" height="9"></a> <a href="main.php?inv=1&otdel='.round((int)$_GET['otdel']).'&usecopr='.$pl['id'].'">Использовать &quot;'.$pl['name'].'&quot;</a><br>';
		}
		$rz7 .= '</small>';
		$rz6 .= '</div>';
	}
	
	$i = 0;
	while($i<count($prt))
	{
		if(isset(${'rz'.$prt[$i]}))
		{
			echo ${'rz'.$prt[$i]};
		}
		$i++;
	}
?>
</td>
</tr>
</table>