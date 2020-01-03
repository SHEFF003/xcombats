<?
if(!defined('GAME'))
{
	die();
}
if($u->room['file']=='an/post')
{
	
/* Разделы почты */	
$r = 1; $otdel = 1;
if(isset($_POST['torzer'])) {
	$_GET['r'] = $_POST['torzer'];
}

if(isset($_POST['tootdel'])) {
	if($_POST['tootdel'] == 2) {
		$otdel = 2;
	}elseif($_POST['tootdel'] == 3) {
		$otdel = 3;
	}elseif($_POST['tootdel'] == 4) {
		$otdel = 4;
	}elseif($_POST['tootdel'] == 6) {
		$otdel = 6;
	}
}

if(isset($_GET['r'])) {
	if($_GET['r'] == 2) {
		$r = 2;
	}elseif($_GET['r'] == 3) {
		$r = 3;
	}elseif($_GET['r'] == 4) {
		$r = 4;
	}
}

$tmgo = 30; //минут

if(isset($_POST['touser'])) {
	$pu = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['touser']).'" ORDER BY `id` ASC LIMIT 1'));
	if($u->info['allLock'] > time()) {
		$pu = false;
		echo '<script>setTimeout(function(){alert("Вам запрещены передачи до '.date('d.m.y H:i',$u->info['allLock']).'")},250);</script>';
	}elseif( $u->info['transfers'] < 1 ) {
		$pu = false;
		echo '<script>setTimeout(function(){alert("Лимит передач на сегодня исчерпан.")},250);</script>';
	}
}

if(!isset($pu['id'])) {
	unset($_POST['touser']);
}

if($r == 1) {
	if(isset($_POST['itm_post']) && (int)$_POST['itm_post'] > 0) {
		$itm = mysql_fetch_array(mysql_query('SELECT `iu`.*,`im`.*,`iu`.item_id as item_id FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON `im`.`id` = `iu`.`item_id` WHERE `iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `iu`.`id` = "'.mysql_real_escape_string($_POST['itm_post']).'" LIMIT 1'));
		if( $u->info['align'] == 2 ) {
			$u->error = 'Хаосники не могут передавать кредиты и предметы';
		}elseif(isset($itm['id'])) {
			$itm['id'] = mysql_real_escape_string(round((int)$_POST['itm_post']));
			$see1 = 1;
			$po = $u->lookStats($itm['data']);
			$po['toclan1'] = explode('#',$po['toclan']);
			$po['toclan1'] = $po['toclan1'][0];
			 
			if($po['toclan1'] > 0) {
				$see1 = 0;
			}
			if($po['frompisher'] > 0) {
				$see1 = 0;
			}
			if($itm['gift'] > 0) {
				$see1 = 0;
			}
			if($po['sudba'] > 0) {
				$see1 = 0;
			}
			
			if($po['zazuby'] > 0) {
				$see1 = 0;
			}
			
			if($see1 == 1) {
				$x = $u->itemsX($itm['id']);
				$mny = round(1+$itm['price1']/100*7);
				if($x > 1) {
					$mny += ($x-1)*$mny;
				}
				if($u->info['money'] >= $mny) {
					if($x > 1) {
						mysql_query('UPDATE `items_users` SET `uid` = "-51'.$pu['id'].'",`lastUPD` = "'.(time()+$tmgo*60).'" WHERE `uid`="'.$u->info['id'].'" AND `item_id`="'.$itm['item_id'].'" AND `inGroup` = "'.mysql_real_escape_string($itm['inGroup']).'" LIMIT '.$x);
						$itm['name'] .= ' (x'.$x.')';
					} else {
						mysql_query('UPDATE `items_users` SET `uid` = "-51'.$pu['id'].'",`lastUPD` = "'.(time()+$tmgo*60).'" WHERE `id` = "'.mysql_real_escape_string($itm['id']).'" LIMIT 1');
					}
					$u->info['money'] -= $mny;
					mysql_query('UPDATE `users` SET `money` = "'.$u->info['money'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					$u->error = 'Вы успешно передали &quot;'.$itm['name'].'&quot; к персонажу &quot;'.$pu['login'].'&quot; за '.$mny.' кр. ';
					
					
					$u->info['transfers']--;
					mysql_query('UPDATE `stats` SET `transfers` = "'.$u->info['transfers'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					
					//логи
					$txt = 'Предмет от <b>'.$u->info['login'].'</b>: &quot;'.$itm['name'].'&quot;. Прибытие: '.date('d.m.Y H:i',(time()+$tmgo*60)).'';
					mysql_query('INSERT INTO `post` (`text`,`uid`,`time`,`sender_id`,`item_id`,`money`) VALUES ("'.mysql_real_escape_string($txt).'",
					"'.$pu['id'].'","'.time().'","'.$u->info['id'].'","'.mysql_real_escape_string($itm['id']).'","0")');
					$txt = 'Отправление предмета к <b>'.$pu['login'].'</b>: &quot;'.$itm['name'].'&quot;. Прибытие: '.date('d.m.Y H:i',(time()+$tmgo*60)).'';
					mysql_query('INSERT INTO `post` (`text`,`sender_id`,`time`,`uid`,`item_id`,`money`) VALUES ("'.mysql_real_escape_string($txt).'",
					"-'.$pu['id'].'","'.time().'","'.$u->info['id'].'","'.mysql_real_escape_string($itm['id']).'","0")');
					
					//чат
					mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES (
					'1','".$pu['city']."','0','','".$pu['login']."','<font color=red>Внимание!</font> Получена новая почта от &quot;".$u->info['login']."&quot;','-".(time()+$tmgo*60)."','5','0')");
				}else{
					$u->error = 'Не достаточно денежных средств';	
				}
			}else{
				$u->error = 'Подходящий предмет не найден';	
			}
		}else{
			$u->error = 'Предмет не найден';
		}
	}
}elseif($r == 3) {
	if(isset($_GET['itm_take'])) {
		$itm = mysql_fetch_array(mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON `im`.`id` = `iu`.`item_id` WHERE `iu`.`uid`="-51'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `iu`.`id` = "'.mysql_real_escape_string($_GET['itm_take']).'" LIMIT 1'));
		if( $u->info['align'] == 2 ) {
			$u->error = 'Хаосники не могут передавать кредиты и предметы';
		}elseif(isset($itm['id'])) {
			if($itm['item_id'] == 1220) {
				//Прием денег
				$post = mysql_fetch_array(mysql_query('SELECT * FROM `post` WHERE `item_id` = "0" AND `money` = "'.$itm['1price'].'" AND `uid` = "'.$u->info['id'].'" AND `finish` = "0" ORDER BY `id` DESC LIMIT 1'));
				if(isset($post['id'])) {
					if($post['sender_id'] < 0) {
						$post['sender_id'] = -$post['sender_id'];
					}
					
					$pup = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `id` = "'.$post['sender_id'].'" LIMIT 1'));
					if($post['sender_id'] == 0) {
						$pup = array(
						'login' => 'Администрация',
						'id'	=> 0
						);
					}
					$u->error = 'Вы успешно забрали '.$itm['1price'].' кр. от '.$pup['login'];
					$u->info['money'] += $itm['1price'];
					mysql_query('UPDATE `users` SET `money` = "'.$u->info['money'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');					
					mysql_query('UPDATE `post` SET `finish` = "'.time().'" WHERE `item_id` = "'.$post['id'].'" ORDER BY `id` DESC LIMIT 1');
					mysql_query('UPDATE `items_users` SET `delete` = "'.time().'",`uid` = "'.$u->info['id'].'" WHERE `id` = "'.mysql_real_escape_string($_GET['itm_take']).'" LIMIT 1');
					
					$u->info['transfers']--;
					mysql_query('UPDATE `stats` SET `transfers` = "'.$u->info['transfers'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					
					//логи
					$txt = 'Деньги для <b>'.$u->info['login'].'</b>: '.$itm['1price'].' кр. <font color="green">Доставлены.</font>';
					mysql_query('INSERT INTO `post` (`text`,`uid`,`time`,`sender_id`,`item_id`,`money`) VALUES ("'.mysql_real_escape_string($txt).'",
					"'.$pup['id'].'","'.time().'","'.$u->info['id'].'","'.mysql_real_escape_string($_GET['itm_take']).'","2")');
					
					$txt = 'Деньги от <b>'.$pup['login'].'</b>: '.$itm['1price'].' кр. <font color="green">Получены.</font>';
					mysql_query('INSERT INTO `post` (`text`,`sender_id`,`time`,`uid`,`item_id`,`money`) VALUES ("'.mysql_real_escape_string($txt).'",
					"-'.$pup['id'].'","'.time().'","'.$u->info['id'].'","'.mysql_real_escape_string($_GET['itm_take']).'","2")');
				}
			}else{
				//Прием вещей
				$post = mysql_fetch_array(mysql_query('SELECT `p`.id,`p`.sender_id, count(`iuu`.id) as inGroupCount, `iu`.id as idItem, `iuu`.item_id, `iuu`.inGroup FROM `post` as `p` LEFT JOIN `items_users` as `iu` ON (`iu`.id = `p`.item_id ) LEFT JOIN `items_users` as `iuu` ON (`iuu`.item_id = `iu`.item_id  AND  `iu`.`inGroup` = `iu`.`inGroup` AND  `iuu`.`uid` = `iu`.`uid`) WHERE (`p`.`sender_id` = "'.$u->info['id'].'" OR `p`.`sender_id` = "-'.$u->info['id'].'") AND `iu`.`id` = "'.mysql_real_escape_string($_GET['itm_take']).'" GROUP BY `iu`.id ORDER BY `iu`.`id` DESC LIMIT 1'));
				if(isset($post['id'])) {
					if($post['sender_id'] < 0) {
						$post['sender_id'] = -$post['sender_id'];
					}
					if($post['inGroup'] > 0) {
						$x = $u->itemsX(mysql_real_escape_string($post['idItem']));
						if($x > 1) {
							$item = mysql_query('UPDATE `items_users` SET `uid` = "'.$u->info['id'].'", `lastUPD` = "'.time().'" WHERE `item_id` = "'.mysql_real_escape_string($post['item_id']).'" AND `inGroup` = "'.mysql_real_escape_string($post['inGroup']).'" AND `uid` = "-51'.mysql_real_escape_string($u->info['id']).'" LIMIT '.$x);
							$itm['name'] .= ' (x'.$x.')';
						}else{
							$item = mysql_query('UPDATE `items_users` SET `uid` = "'.$u->info['id'].'", `lastUPD` = "'.time().'" WHERE `id` = "'.mysql_real_escape_string($_GET['itm_take']).'" LIMIT 1');
						}
					} else {
						$item = mysql_query('UPDATE `items_users` SET `uid` = "'.$u->info['id'].'", `lastUPD` = "'.time().'" WHERE `id` = "'.mysql_real_escape_string($_GET['itm_take']).'" LIMIT 1');
					}
					if($item) {
						mysql_query('UPDATE `post` SET `finish` = "'.time().'" WHERE `id` = "'.mysql_real_escape_string($post['id']).'" ORDER BY `id` DESC LIMIT 1');
						$u->info['transfers']--;
						mysql_query('UPDATE `stats` SET `transfers` = "'.$u->info['transfers'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						$u->error = 'Вы успешно забрали &quot;'.$itm['name'].'&quot;';
						$pup = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `id` = "'.$post['sender_id'].'" LIMIT 1'));
						if($post['sender_id'] == 0) {
							$pup = array(
							'login' => 'Администрация',
							'id'	=> 0
							);
						}
						//логи
						$txt = 'Предмет для <b>'.$u->info['login'].'</b>: &quot;'.$itm['name'].'&quot;. <font color="green">Доставлен.</font>';
						mysql_query('INSERT INTO `post` (`text`,`uid`,`time`,`sender_id`,`item_id`,`money`,`finish`) VALUES ("'.mysql_real_escape_string($txt).'","'.$pup['id'].'","'.time().'","'.$u->info['id'].'","'.mysql_real_escape_string($_GET['itm_take']).'","2", "'.time().'")');
					
						$txt = 'Предмет от <b>'.$pup['login'].'</b>: &quot;'.$itm['name'].'&quot;. <font color="green">Получен.</font>';
						mysql_query('INSERT INTO `post` (`text`,`sender_id`,`time`,`uid`,`item_id`,`money`,`finish`) VALUES ("'.mysql_real_escape_string($txt).'","-'.$pup['id'].'","'.time().'","'.$u->info['id'].'","'.mysql_real_escape_string($_GET['itm_take']).'","2", "'.time().'")');
					} else {
						$u->error = 'Не удалось забрать предмет ('.$itm['id'].')';	
					}
				}else{
					$u->error = 'Не удалось найти предмет';	
				}
			}
		}else{
			$u->error = 'Предмет не найден';
		}
		if($u->error != '') {
			echo '<div>'.$u->error.'</div>';
		}
	}
}

?>
<style type="text/css">
.pH3 {COLOR: #8f0000;  FONT-FAMILY: Arial;  FONT-SIZE: 12pt;  FONT-WEIGHT: bold; }
</style>
<table width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top"><br />
    <div style="background-color:#d2d2d2;height:35px;">
        <div style="float:left;margin:9px;" class="pH3">Почтовое отделение<?
        if($r == 1) {
			echo ' &nbsp; &bull; &nbsp; Передать предметы';
		}elseif($r == 2) {
			echo ' &nbsp; &bull; &nbsp; Кредиты и телеграф';
		}elseif($r == 3) {
			echo ' &nbsp; &bull; &nbsp; Получение вещей и кредитов';
		}elseif($r == 4) {
			echo ' &nbsp; &bull; &nbsp; Отчеты';
		}
		?></div>
        <div style="float:right;margin:9px;"><?=$u->microLogin($u->info['id'],1)?></div>
    </div>
	<? if($u->error!=''){ ?>
    <br />
	<div align="left" style="height:18px;"><font color="#FF0000"><b><? echo $u->error; ?></b></font></div>
    <? } ?>
	<form id="postfm" name="postfm" method="post" action="main.php">
	<input name="touser" id="touser" type="hidden" value="<?=$pu['login']?>" />
    <input name="torzer" id="torzer" type="hidden" value="<?=$r?>" />
    <input name="tootdel" id="tootdel" type="hidden" value="<?=$otdel?>" />
    <input name="itm_post" id="itm_post" type="hidden" value="" />
    <?
if(isset($pu['id'])) {
?>
<div style="padding:0 10px 5px 10px; margin:5px; border-bottom:1px solid #cac9c7;">
	К кому передавать: <?=$u->microLogin($pu,2)?> &nbsp; <input type="button" onclick="opennedWinPost(1)" value="сменить" /><br />
    <?
	if($pu['city'] == $u->info['city']) {
		echo 'Находится в этом городе.<br>';
	}else{
		echo 'Находится в <b>'.$u->city[$pu['city']].'</b>.<br>';
	}	
	echo 'Примерное время доставки: 0 ч. 30 мин.';
	?>
</div>
<?	
}
if(isset($pu['id'])) {
	if($r == 1) {
		//Передача предметов
		$itmAll = ''; $itmAllSee = '';
		$itmAll = $u->genInv(67,'`iu`.`uid` = "'.$u->info['id'].'" AND `iu`.`delete` = "0" AND `iu`.`inOdet` = "0" AND `iu`.`inShop` = "0" AND `im`.`inRazdel`="'.mysql_real_escape_string($otdel).'" AND `iu`.`data` NOT LIKE "%zazuby=%" ORDER BY `lastUPD` DESC'); 
		if($itmAll[0]==0){
			$itmAllSee = '<tr><td align="center" bgcolor="#e2e0e0" style="padding:10px;">ПУСТО</td></tr>';
		}else{
			$itmAllSee = $itmAll[2];
		}
?>
<TABLE width=100% cellspacing=0 cellpadding=3 bgcolor=d4d2d2><TR>
	<TD width="20%" align=center bgcolor="<?=($otdel==1)?"#A5A5A5":""?>"><A onclick="sendFormer(0,1,1);" HREF="javascript:void(0)">Обмундирование</A></TD>
	<TD width="20%" align=center bgcolor="<?=($otdel==2)?"#A5A5A5":""?>"><A onclick="sendFormer(0,1,2);" HREF="javascript:void(0)">Заклятия</A></TD>
	<TD width="20%" align=center bgcolor="<?=($otdel==3)?"#A5A5A5":""?>"><A onclick="sendFormer(0,1,3);" HREF="javascript:void(0)">Эликсиры</A></TD>
	<TD width="20%" align=center bgcolor="<?=($otdel==6)?"#A5A5A5":""?>"><A onclick="sendFormer(0,1,6);" HREF="javascript:void(0)">Руны</A></TD>
	<TD width="20%" align=center bgcolor="<?=($otdel==4)?"#A5A5A5":""?>"><A onclick="sendFormer(0,1,4);" HREF="javascript:void(0)">Прочее</A></TD>
</TR></TABLE>
<table border=0 cellpadding=0 cellspacing=0 width=100% bgcolor="#A5A5A5"><tr><td width=99% align=center><B>Рюкзак (масса: <?=$u->aves['now']?>/<?=$u->aves['max']?>, предметов: <?=$u->aves['items']?>)</B></td></tr></table>
<table width="100%" border="0" cellspacing="1" align="center" cellpadding="0" bgcolor="#A5A5A5">
<? if($u->info['invBlock']==0){ echo $itmAllSee; }else{ echo '<div align="center" style="padding:10px;background-color:#A5A5A5;"><form method="post" action="main.php?inv=1&otdel='.$_GET['otdel'].'&relockinvent"><b>Рюкзак закрыт.</b><br><img title="Замок для рюкзака" src="http://img.xcombats.com/i/items/box_lock.gif"> Введите пароль: <input id="relockInv" name="relockInv" type="password"><input type="submit" value="Открыть"></form></div>'; } ?>
</table>
<?
	}elseif($r == 2) {
		//Передача кредитов и телеграмм
		$e1 = '';
		$e2 = '';
		$e3 = '';
		if(isset($_POST['send1'])) {
			$m = round($_POST['snd_money'],2);
			$cm = round(($m/100*5),2);
			if($cm < 1) {
				$cm = 1;
			}
			if($m > 0) {
				if($u->info['money'] >= round($m+$cm,2)) {
					$e1 = 'Вы успешно перевели <b>'.$m.'</b> кр. (Комиссия: '.$cm.' кр.) к персонажу '.$pu['login'].'';
					$u->info['money'] -= round($m+$cm,2);
					mysql_query('UPDATE `users` SET `money` = "'.mysql_real_escape_string($u->info['money']).'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					
					$u->info['transfers']--;
					mysql_query('UPDATE `stats` SET `transfers` = "'.$u->info['transfers'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					
					mysql_query("INSERT INTO `items_users`(`item_id`,`1price`,`uid`,`delete`,`lastUPD`)VALUES('1220','".mysql_real_escape_string($m)."','-51".$pu['id']."','0','".(time()+$tmgo*60)."');");
					
					$txt = 'Деньги от <b>'.$u->info['login'].'</b>: '.round($m,2).' кр. Прибытие: '.date('d.m.Y H:i',(time()+$tmgo*60)).'';
					mysql_query('INSERT INTO `post` (`uid`,`sender_id`,`time`,`money`,`text`) VALUES("'.$pu['id'].'","-'.$u->info['id'].'","'.time().'",
					"'.mysql_real_escape_string(round($m,2)).'","'.mysql_real_escape_string($txt).'")');
					
					$txt = 'Деньги к <b>'.$pu['login'].'</b>: '.round($m,2).' кр. Прибытие: '.date('d.m.Y H:i',(time()+$tmgo*60)).'';
					mysql_query('INSERT INTO `post` (`uid`,`sender_id`,`time`,`money`,`text`) VALUES("'.$u->info['id'].'","'.$pu['id'].'","'.time().'",
					"0","'.mysql_real_escape_string($txt).'")');
					
					//чат
					mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES (
					'1','".$pu['city']."','0','','".$pu['login']."','<font color=red>Внимание!</font> Получена новая почта от &quot;".$u->info['login']."&quot;','-".(time()+$tmgo*60)."','5','0')");
				}else{
					$e1 = 'У вас недостаточно средств';
				}
			}
		}elseif(isset($_POST['send2'])) {
			if($u->info['money'] >= 0.1) {
				$ttest = mysql_fetch_array(mysql_query('SELECT `id`,`time` FROM `chat` WHERE `text` LIKE "%Телеграмма от <b>'.$u->info['login'].'</b>%" ORDER BY `id` DESC LIMIT 1'));
				if( $ttest['time'] < 0 ) {
					$ttest['time'] = -$ttest['time'];
				}
				if( !isset($ttest['id']) || ( $ttest['time']-$tmgo*60-time()+10 ) <= 0 ) {
					$_POST['snd_telegraf'] = htmlspecialchars($_POST['snd_telegraf'],NULL,'cp1251');
					$_POST['snd_telegraf'] = substr($_POST['snd_telegraf'],0,100);
					$_POST['snd_telegraf'] = str_replace('<','&lt;',$_POST['snd_telegraf']);
					$_POST['snd_telegraf'] = str_replace('\x3e','&lt;',$_POST['snd_telegraf']);
					$_POST['snd_telegraf'] = str_replace(']:[','] : [',$_POST['snd_telegraf']);
					$_POST['snd_telegraf'] = str_replace('>','&gt;',$_POST['snd_telegraf']);
					$_POST['snd_telegraf'] = str_replace("'", "",$_POST['snd_telegraf']);
					$e2 = 'Ваше сообщение успешно доставлено';
					
					$u->info['transfers']--;
					mysql_query('UPDATE `stats` SET `transfers` = "'.$u->info['transfers'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					
					$u->info['money'] -= 0.1;
					mysql_query('UPDATE `users` SET `money` = "'.$u->info['money'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES (
					'1','".$pu['city']."','0','','".$pu['login']."','<font color=red>Внимание!</font> ".date("d.m.y H:i")." Телеграмма от <b>".$u->info['login']."</b>: \'".mysql_real_escape_string($_POST['snd_telegraf'])."\' .','-".(time()+$tmgo*60)."','5','0')");
				}else{
					$e2 = 'Вы не можете отправлять сообщения так часто. Осталось '.( $ttest['time']-$tmgo*60-time()+10 ).' сек.';
				}
			}else{
				$e2 = 'У вас недостаточно средств (0.1 кр.)';	
			}
		}elseif(isset($_POST['send3'])) {
			if($u->info['money'] >= 1) {
				//max_text
				$_POST['snd_post'] = htmlspecialchars($_POST['snd_post'],NULL,'cp1251');
				$_POST['snd_post'] = substr($_POST['snd_post'],0,500);
				$_POST['snd_post'] = str_replace('<','&lt;',$_POST['snd_post']);
				$_POST['snd_post'] = str_replace('\x3e','&lt;',$_POST['snd_post']);
				$_POST['snd_post'] = str_replace(']:[','] : [',$_POST['snd_post']);
				$_POST['snd_post'] = str_replace('>','&gt;',$_POST['snd_post']);
				$_POST['snd_post'] = str_replace("'", "",$_POST['snd_post']);
				$_POST['snd_post'] = str_replace("\n", "<br>",$_POST['snd_post']);
				$e3 = 'Ваше письмо успешно доставлено';
				$u->info['money'] -= 1;
				
				$u->info['transfers']--;
				mysql_query('UPDATE `stats` SET `transfers` = "'.$u->info['transfers'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				
				mysql_query('UPDATE `users` SET `money` = "'.$u->info['money'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				mysql_query("INSERT INTO `items_users` (`item_id`,`1price`,`uid`,`delete`,`lastUPD`) VALUES ('2131','0','-51".$pu['id']."','0','".(time()+$tmgo*0)."')");
				$id = mysql_insert_id();
				mysql_query("INSERT INTO `items_text` (`item_id`,`time`,`login`,`text`,`x`) VALUES ('".$id."','".time()."','".$u->info['login']."','<br>".mysql_real_escape_string($_POST['snd_post'])."','1')");
				//логи
				$txt = 'Предмет от <b>'.$u->info['login'].'</b>: &quot;Письмо&quot;. Прибытие: '.date('d.m.Y H:i',(time()+$tmgo*60)).'';
				mysql_query('INSERT INTO `post` (`text`,`uid`,`time`,`sender_id`,`item_id`,`money`) VALUES ("'.mysql_real_escape_string($txt).'",
				"'.$pu['id'].'","'.time().'","'.$u->info['id'].'","'.$id.'","0")');
				$txt = 'Отправление предмета к <b>'.$pu['login'].'</b>: &quot;Письмо&quot;. Прибытие: '.date('d.m.Y H:i',(time()+$tmgo*60)).'';
				mysql_query('INSERT INTO `post` (`text`,`sender_id`,`time`,`uid`,`item_id`,`money`) VALUES ("'.mysql_real_escape_string($txt).'",
				"-'.$pu['id'].'","'.time().'","'.$u->info['id'].'","'.$id.'","0")');
				//чат
				mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES (
				'1','".$pu['city']."','0','','".$pu['login']."','<font color=red>Внимание!</font> Получена новая почта от &quot;".$u->info['login']."&quot;','-".(time()+$tmgo*60)."','5','0')");
			}else{
				$e3 = 'У вас недостаточно средств (1 кр.)';
			}
		}
?>
<div style="padding:0 10px 5px 10px; margin:5px; border-bottom:1px solid #cac9c7;">
<h4>Передать кредиты</h4>
	<? if($e1!=''){ ?>
    <br />
	<div align="left" style="height:18px;"><font color="#FF0000"><b><? echo $e1; ?></b></font></div>
    <? } ?>
У вас на счету: <b style="color:#158e1d"><?=$u->info['money']?></b> кр.<br />
Передать кредиты, минимально 1 кр. Комиссия составит 5%<br />
Укажите передаваемую сумму: <input name="snd_money" value="" type="text" /><input type="submit" id="send1" name="send1" value="Передать" />
</div>
<div style="padding:0 10px 5px 10px; margin:5px; border-bottom:1px solid #cac9c7;">
<h4>Телеграф</h4>
	<? if($e2!=''){ ?>
    <br />
	<div align="left" style="height:18px;"><font color="#FF0000"><b><? echo $e2; ?></b></font></div>
    <? } ?>
Услуга платная: <b>0.1</b> кр.<br />
Сообщение: (Максимально 100 символов)<br />
<input type="text" name="snd_telegraf" value="" size="75" maxlength="100" /><input type="submit" id="send2" name="send2" value="Передать" />
</div>
<div style="padding:0 10px 5px 10px; margin:5px; border-bottom:1px solid #cac9c7;">
<h4>Письмо</h4>
	<? if($e3!=''){ ?>
    <br />
	<div align="left" style="height:18px;"><font color="#FF0000"><b><? echo $e3; ?></b></font></div>
    <? } ?>
Услуга платная: <b>1</b> кр.<br />
Сообщение: (Время доставки 30 мин.)<br />
<textarea name="snd_post" cols="89" rows="5"/></textarea><br />
(Максимально 500 символов) <input type="submit" id="send3" name="send3" value="Отправить" />
</div>
<?
	}
}elseif($r == 3) {
	//получить предметы
	$itmAll = ''; $itmAllSee = '';
	$itmAll = $u->genInv(68,'`iu`.`uid` = "-51'.$u->info['id'].'" AND `iu`.`delete` = 0 AND `iu`.`inOdet` = 0 AND `iu`.`inShop` = 0 AND `iu`.`lastUPD` < '.time().' ORDER BY `lastUPD` DESC');
	if($itmAll[0]==0)
	{
		$itmAllSee = '<tr><td align="center" bgcolor="#e2e0e0" style="padding:10px;">Для Вас пока-что нет посылок, проверьте раздел <b>Отчеты</b></td></tr>';
	}else{
		$itmAllSee = $itmAll[2];
	}
?>
<table width="100%" border="0" cellspacing="1" align="center" cellpadding="0" bgcolor="#A5A5A5">
<? if($u->info['invBlock']==0){ echo $itmAllSee; }else{ echo '<div align="center" style="padding:10px;background-color:#A5A5A5;"><form method="post" action="main.php?inv=1&otdel='.$_GET['otdel'].'&relockinvent"><b>Рюкзак закрыт.</b><br><img title="Замок для рюкзака" src="http://img.xcombats.com/i/items/box_lock.gif"> Введите пароль: <input id="relockInv" name="relockInv" type="password"><input type="submit" value="Открыть"></form></div>'; } ?>
</table>
<?
}elseif($r == 4) {
	//отчеты
?>
<br /><br />
<div style="padding:0 10px 5px 10px; margin:5px; border-bottom:1px solid #cac9c7;">
В данном разделе отображаются все почтовые операции Вашего персонажа.
</div>
<?
$pg = round((int)$_GET['page']);
if($pg < 1) {
	$pg = 1;
}
$p1 = round(50*($pg-1));
$p2 = round($p1+50);
$sp = mysql_query('SELECT * FROM `post` WHERE `uid` = "'.$u->info['id'].'" ORDER BY `id` DESC');
$r = '';
while($pl = mysql_fetch_array($sp)) {
	$r .= '<div style="padding:0 10px 5px 10px;';
	/*
	if($pl['finish'] == 0 && $pl['sender_id'] < 0) {
		$r .= 'background-color:#e6eee0;';
	}
	*/
	$r .= 'margin:5px; border-bottom:1px solid #cac9c7;">';
	$r .= '<font color="green">'.date('d.m.Y H:i',$pl['time']).'</font> &nbsp; '.$pl['text'].'</div>';
}
if($r == '') {
	$r = '<div style="padding:0 10px 5px 10px; margin:5px; border-bottom:1px solid #cac9c7;">Отчеты отсутствуют</div>';
}else{
	$ap = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `post` WHERE `uid` = "'.$u->info['id'].'"'));
	$ap = ceil($ap[0]/50);
	if($ap > 1) {
		$r .= '<div style="padding:0 10px 5px 10px; margin:5px; border-bottom:1px solid #cac9c7;">';
		$r .= 'Страницы: ';
		$i = 1;
		while($i <= $ap) {
			if($i == $pg) {
				$r .= ' <b>'.$i.'</b> ';
			}else{
				$r .= ' <a href="?r=4&page='.$i.'">'.$i.'</a> ';
			}
			$i++;
		}
	}
	$r .= '</div>';
}
echo $r;
}else{
	//общий раздел
?>
<BR><BR>
&bull; <B>Передать предмет</B><BR>
Вы можете отправить предмет любому персонажу, даже если он находится в другом городе. Цена и время доставки зависят от расстояния.<BR>
<BR>
&bull; <B>Кредиты и Телеграф</B><BR>
Вы можете отправить короткое сообщение любому персонажу, даже если он находится в offline или другом городе.<BR>
Вы можете отправить некоторую сумму денег персонажу.<BR>
<BR>
&bull; <B>Получить вещи</B><BR>
Вы можете получить вещи, которые были отправлены вам другими игроками.<BR>
Посылка хранится на почте 7 дней, но не более одного дня с момента как вы увидели ее в списке вещей для получения.
По истечению этого срока, посылка отправляется обратно или удаляется.
<BR>
<small><BR>Администрация почты заявляет, что не несет ответственности за хранимый или пересылаемый товар/кредиты/сообщения и не гарантирует 100% его доставку. В случае форс-мажорных обстоятельств, товар/кредиты/сообщения могут быть утеряны.</small>
<?
}
?>
</form>
    </td>
    <?
	if ($u->error != '') {
		echo '<b><font style="float:right" color=red>'.$u->error.'</font></b>';
	}
	if ($re != '') {
		echo '<b><font style="float:right" color=red>'.$re.'</font></b>';
	}
	?>
    <td width="280" valign="top"><table align="right" cellpadding="0" cellspacing="0">
      <tr>
        <td width="100%">&nbsp;</td>
        <td><table  border="0" cellpadding="0" cellspacing="0">
          <tr align="right" valign="top">
            <td><!-- -->
              <? echo $goLis; ?>
              <!-- -->
              <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td nowrap="nowrap">
                  <table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#DEDEDE">
                    <tr>
                      <td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td>
                      <td bgcolor="#D3D3D3" nowrap="nowrap"><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=2.180.0.234&amp;rnd=<? echo $code; ?>';" title="<? thisInfRm('2.180.0.234',1); ?>">Центральная площадь</a></td>
                    </tr>
                    <tr>
                      <td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td>
                      <td bgcolor="#D3D3D3" nowrap="nowrap"><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=2.180.0.403&amp;rnd=<? echo $code; ?>';" title="<? thisInfRm('2.180.0.403',1); ?>">Аукцион</a></td>
                    </tr>
<?
		$hgo = $u->testHome();
		if(!isset($hgo['id']))
		{
?>
                    <tr>
                      <td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td>
                      <td bgcolor="#D3D3D3" nowrap="nowrap"><a href="#" id="greyText" class="menutop" onclick="location='main.php?homeworld&rnd=<? echo $code; ?>';" title="<? thisInfRm('1.180.0.9',1); ?>">Возврат</a></td>
                    </tr>
<?
		}
?>
                  </table>
                  </td>
                </tr>
              </table></td>
          </tr>
        </table></td>
      </tr>
    </table>
      <div style="margin-left:10px;"><br />
        <p>&nbsp;</p>
        <p> Деньги: <?=$u->info['money']?> кр.
        <br />
        Передач: <?=$u->info['transfers']?>
        <br />
        <br />
        <a onclick="<? if(!isset($pu['id'])) { echo 'opennedWinPost(1);'; }else{ echo 'sendFormer(0,1,0);'; } ?>" href="javascript:void(0)">Передать предметы</a><br />
        <a onclick="<? if(!isset($pu['id'])) { echo 'opennedWinPost(2);'; }else{ echo 'sendFormer(0,2,0);'; } ?>" href="javascript:void(0)">Кредиты и Телеграф</a><br />
        <?
		$ot = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `items_users` WHERE `uid` = "-51'.$u->info['id'].'"'));
		$ot = $ot[0];
		if($ot > 0) {
       		echo '<a href="?r=3">Получить вещи</a>';
		}else{
			echo '<font color="grey">Получить вещи</font>';
		}
		?>
        <br /><br />
        <a href="?r=4">Отчеты</a>
        </p>
      </div></td>
  </tr>
</table>
<script>
function opennedWinPost(rz) {
top.win.add('post_win','Почтовые услуги &nbsp;','<center>Укажите логин персонажа:<br><small>(можно щелкнуть по логину в чате)</small><br></center>',{'a1':'top.frames[\'main\'].sendFormer($(\'#post_win_inp\').val(),'+rz+')','usewin':'top.chat.inObj=$(\'#post_win_inp\');$(\'#post_win_inp\').focus()','d':'<center><input style="width:96%; margin:5px;" id="post_win_inp" class="inpt2" type="text" value=""></center>'},3,1,'min-width:300px;');
}
function itmToUser(id) {
	document.getElementById('itm_post').value = id;
	sendFormer(0,1,0);
}
function sendFormer(vl,r,o) {
	if(vl != 0) {
		document.getElementById('touser').value = vl;
	}
	if(o != 0) {
		document.getElementById('tootdel').value = o;
	}
	document.getElementById('torzer').value = r;
	document.getElementById('postfm').submit();
}
</script>
<?
}
?>