<?
if(!defined('GAME')) { die(); }
if($u->room['file']=='katok') {
	
$ku = mysql_fetch_array(mysql_query('SELECT * FROM `katok_zv` WHERE `uid` = "'.$u->info['id'].'" LIMIT 1'));
//
$tcount = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `katok_zv` LIMIT 1'));
$tcount = 0 + $tcount[0];
//	
if(isset($_POST['join'])) {
	if($tcount >= 12) {
		$u->error = 'Группа сформирована! Сечас начнется этот туринр и вы сможете подать заявку на новый!';
	}elseif(!isset($ku['id'])) {
		//
		$team = 0; //Не профессиональный турнир
		//
		mysql_query('INSERT INTO `katok_zv` (
			`uid`,`time`,`team`
		) VALUES (
			"'.$u->info['id'].'","'.time().'","'.$team.'"
		)');
		//
		$ku = mysql_fetch_array(mysql_query('SELECT * FROM `katok_zv` WHERE `uid` = "'.$u->info['id'].'" LIMIT 1'));
		if(isset($ku['id'])) {
			$tcount++;
		}
		//
		$u->error = 'Вы успешно приняли заявку на участие в турнире!';
	}
}elseif(isset($_POST['cancel'])) {
	if(isset($ku['id'])) {
		mysql_query('DELETE FROM `katok_zv` WHERE `uid` = "'.$u->info['id'].'"');
		unset($ku);
		$tcount--;
		$u->error = 'Вы отменили заявку на участие в турнире.';
	}
}

if($tcount >= 6 ) {
	
	//Создаем пещеру
	mysql_query('INSERT INTO `dungeon_now` (
		`id2` , `name` , `time_start` , `time_finish` , `uid` , `city` , `type` , `bsid`
	) VALUES (
		"15" , "Хоккей" , "'.time().'" , "0" , "0" , "'.$u->info['city'].'" , "0" , "2015"
	)');
	$dnew = mysql_insert_id();	
	
	//Расставляем обьекты: Сундуки, Ворота + Шайба (должна быть предметом) , Полынь , Двери
	//Добавляем обьекты
    $vls32 = '';
	$sphj = mysql_query('SELECT * FROM `dungeon_obj` WHERE `for_dn` = "15"');
	while($plhj = mysql_fetch_array($sphj)) {
		$vls32 .= '("'.$dnew.'","'.$plhj['name'].'","'.$plhj['img'].'","'.$plhj['x'].'","'.$plhj['y'].'","'.$plhj['action'].'","'.$plhj['type'].'","'.$plhj['w'].'","'.$plhj['h'].'","'.$plhj['s'].'","'.$plhj['s2'].'","'.$plhj['os1'].'","'.$plhj['os2'].'","'.$plhj['os3'].'","'.$plhj['os4'].'","'.$plhj['type2'].'","'.$plhj['top'].'","'.$plhj['left'].'","'.$plhj['date'].'"),';
	}
	$vls32 = rtrim($vls32,',');
	if($vls32!='') {
		$ins232 = mysql_query('INSERT INTO `dungeon_obj` (`dn`,`name`,`img`,`x`,`y`,`action`,`type`,`w`,`h`,`s`,`s2`,`os1`,`os2`,`os3`,`os4`,`type2`,`top`,`left`,`date`) VALUES '.$vls32.'');
	}
	unset($vls32,$ins232);

	
	//Расставляем тренеров
	
	
	//Расставляем игроков (создаем ботов и кидаем их на позиции) и вселяем игроков + телепортация в хоккей
	$sp = mysql_query('SELECT * FROM `katok_zv`');
	$tmr = rand(1,2);
	while($pl = mysql_fetch_array($sp)) {
		$bus = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `id` = "'.$pl['uid'].'" LIMIT 1'));
		if(isset($bus['id'])) {
			if( $tmr == 1 ) {
				$tmr = 2;
			}else{
				$tmr = 1;
			}
			//
			$pl['team'] = $tmr;
			//Создаем бота + выдаем предметы
			if( $bus['align'] >= 1 && $bus['align'] < 2 ) {
				$bus['align'] = 1;
			}elseif( $bus['align'] >= 3 && $bus['align'] < 4 ) {
				$bus['align'] = 3;
			}elseif( $bus['align'] == 7 ) {
				$bus['align'] = 7;
			}else{
				$bus['align'] = 0;
			}
			//
			if( $pl['team'] == 1 ) {
				$obraz = 'ih59.gif';
			}elseif( $pl['team'] == 2 ) {
				$obraz = 'ih60.gif';
			}
			//
			mysql_query('INSERT INTO `users` (`obraz`,`chatColor`,`align`,`inTurnir`,`molch1`,`molch2`,`activ`,`login`,`room`,`name`,`sex`,`level`,`bithday`) VALUES (
				"'.$obraz.'","'.$bus['chatColor'].'","'.$bus['align'].'","'.$pl['id'].'","'.$bus['molch1'].'","'.$bus['molch2'].'","0","'.$bus['login'].'","411","'.$bus['name'].'","'.$bus['sex'].'","4","'.date('d.m.Y').'")');
			//
			$inbot = mysql_insert_id(); //айди бота
			if( $inbot > 0 ) {
				//Бот
				$mp = rand(0,count($mapu)-1);
				// X: 9,Y: 14 или 1 , 0
				if( $pl['team'] == 1 ) {
					$x1 = 1;
					$y1 = 0;
					$rx = 1;
					$ry = 0;
				}else{
					$x1 = 9;
					$y1 = 14;
					$rx = 9;
					$ry = 14;
				}
				unset($mapu[$mp]);
				//
				mysql_query('INSERT INTO `stats` (`res_x`,`res_y`,`timeGo`,`timeGoL`,`upLevel`,`dnow`,`id`,`stats`,`exp`,`ability`,`skills`,`x`,`y`)
				VALUES (
					"'.$rx.'","'.$ry.'",
					"'.(time()+180).'","'.(time()+180).'","98","'.$dnew.'","'.$inbot.'",
					"s1=3|s2=3|s3=3|s4=7|s5=0|s6=0|rinv=40|m9=5|m6=10","0",
					"39","5","'.$x1.'","'.$y1.'"
				)');
				//Выдаем амуницию
				$u->addItem(4815,$inbot);
				if($pl['team'] == 1) {
					$u->addItem(4816,$inbot);
					$u->addItem(4818,$inbot);
					$u->addItem(4820,$inbot);
					$u->addItem(4822,$inbot);
					$u->addItem(4824,$inbot);
				}elseif($pl['team'] == 2) {
					$u->addItem(4817,$inbot);
					$u->addItem(4819,$inbot);
					$u->addItem(4821,$inbot);
					$u->addItem(4823,$inbot);
					$u->addItem(4825,$inbot);
				}
				//
				mysql_query('UPDATE `users` SET `room` = "410", `inUser` = "'.$inbot.'" WHERE `id` = "'.$bus['id'].'" LIMIT 1');
				//
			}
			//Добавляем путы
			//
			mysql_query('INSERT INTO `eff_users` (`id_eff`,`uid`,`name`,`data`,`overType`,`timeUse`,`img2`) VALUES (
				"2","'.$inbot.'","Путы","add_speedhp=30000|add_speedmp=30000|puti='.(time()+180).'","1","'.(time()+180).'","chains.gif"
			) ');
			//			
		}
		//Удаляем заявку
		mysql_query('DELETE FROM `katok_zv` WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		//
		mysql_query('INSERT INTO `katok_now` (
			`uid`,`time`,`team`,`clone`
		) VALUES (
			"'.$pl['uid'].'","'.time().'","'.$pl['team'].'","'.$inbot.'"
		)');
		//
	}	
	die('<font color=red>Начало матча...</font><script>setTimeout("location.href=\'/main.php\';",2000);</script>');
}

?>
<style>
body {
	background-color:#E2E2E2;
	background-image: url(http://img.xcombats.com/i/misc/showitems/dungeon.jpg);
	background-repeat:no-repeat;background-position:top right;
}
</style>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <div style="padding-left:0px;" align="center">
      <h3><? echo $u->room['name']; ?></h3>
    </div><?
    if($u->error != '') {
		echo '<font color="red"><b>'.$u->error.'</b></font><br>';
	}
    if($re != '') {
		echo '<font color="red"><b>'.$re.'</b></font><br>';
	}
	?><br />
    <table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top"><form id="from" autocomplete="off" name="from" action="main.php?join=<? echo $code; ?>" method="post">
          <fieldset style='padding-left: 5; width=50%'>
            <legend><b> Группа </b></legend>
            <b>Всего заявок:</b> <?=$tcount?> &nbsp; <? if(!isset($ku['id'])){ ?><input type="submit" name="join" value="Присоед." /><? }else{ ?><input type="submit" name="cancel" value="Отменить" /><? } ?> &nbsp; <input onclick="location.href='/main.php';" type="button" name="cancel" value="Обновить" />
          </fieldset>
        </form></td>
      </tr>
    </table>
    <br />
    <b>Правила участия:</b><br />
    <br /><font color=red>&bull; На время тестов требуется 6 игроков для старта!</font><br />
	&bull; Для начала игры необходимо набрать 12 заявок - это 2 команды по 6 участников. Уровень игроков значения не имеет т.к. в начале игры все персонажи переселяются в новые тела [4] уровня. 
	<Br />&bull; Как только набирается 12 заявок, автоматически создаётся 2 команды и начинается Матч. Игроки распределяются в команды рандомно.
    <br />&bull; Задержка на посещение 1 час после выхода с хоккея, если вы вышли во время игры, тогда вы не сможете посещать каток еще 4 часа.
    <br />&bull; Вы не сможете выйти с катка пока не завершится игра
    </td>
    <td width="200" valign="top"><div align="right">
      <table cellspacing="0" cellpadding="0">
        <tr>
          <td width="100%">&nbsp;</td>
          <td>
          <table  border="0" cellpadding="0" cellspacing="0">
              <tr align="right" valign="top">
                <td><!-- -->
                    <? echo $goLis; ?>
                    <!-- -->
                    <table border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td nowrap="nowrap"><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#DEDEDE">
                            <tr>
								<td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td>
								<td bgcolor="#D3D3D3" nowrap="nowrap"><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=1.180.0.323&rnd=<? echo $code; ?>';">Выход с катка</a></td>
                            </tr>
                        </table>
						</td>
                      </tr>
                  </table></td>
              </tr>
          </table>
          </td>
        </tr>
      </table>
      </div></td>
  </tr>
</table>
<? } ?>
