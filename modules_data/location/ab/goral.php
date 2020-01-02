<?
if(!defined('GAME'))
{
	die();
}

if($u->room['file']=='ab/goral')
{

$dun = 13; //для новичков 2-7 лвл

$er = '';

$dzs = '';

$g111 = 1;
$g11 = $u->testAction('`uid` = "'.$u->info['id'].'" AND `vars` = "psh1" AND `time`>'.(time()-7200).' LIMIT 1',1);

$moder = mysql_fetch_array(mysql_query('SELECT * FROM `moder` WHERE `align` = "'.$u->info['align'].'" LIMIT 1'));

if($u->info['dn']>0)
{
	$zv = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_zv` WHERE `id`="'.$u->info['dn'].'" AND `delete` = "0" LIMIT 1'));
	if(!isset($zv['id']))
	{
		mysql_query('UPDATE `stats` SET `dn` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		$u->info['dn'] = 0;
	}
}

if(isset($g11['id']))
{
	$g111 = 0;
	if(isset($_GET['start']))
	{
		$re = 'Поход в пещеры разрешен один раз в два часа. Осталось еще: '.$u->timeOut(7200-time()+$g11['time']);
	}
}
if(isset($_GET['start']) && $zv['uid']==$u->info['id'] && $g111 == 1)
{	
	//начинаем поход
	//начинаем поход
	$ig = 1;
	if($ig>0)
	{
		//перемещаем игроков в пещеру
		//$u->addAction(time(),'psh1','');
		$ins = mysql_query('INSERT INTO `dungeon_now` (`city`,`uid`,`id2`,`name`,`time_start`)
		VALUES ("'.$zv['city'].'","'.$zv['uid'].'","'.$dun.'","Бездна","'.time().'")');
		if($ins)
		{
			$zid = mysql_insert_id();
			//обновляем пользователей
			$su = mysql_query('SELECT `u`.`id`,`st`.`dn` FROM `stats` AS `st` LEFT JOIN `users` AS `u` ON (`st`.`id` = `u`.`id`) WHERE `st`.`dn`="'.$zv['id'].'" LIMIT '.($zv['team_max']+1).'');
			$ids = '';
			while($pu = mysql_fetch_array($su))
			{
				$ids .= ' `id` = "'.$pu['id'].'" OR';
				$u->addAction(time(),'psh13','',$pu['id']);
			}
			$ids = rtrim($ids,'OR');
			$upd1 = mysql_query('UPDATE `stats` SET `x`="0",`y`="0",`dn` = "0",`dnow` = "'.$zid.'" WHERE '.$ids.' LIMIT '.($zv['team_max']+1).'');
			if($upd1)
			{
				$upd2 = mysql_query('UPDATE `users` SET `room` = "315" WHERE '.$ids.' LIMIT '.($zv['team_max']+1).'');
				//Добавляем ботов и обьекты в пещеру $zid с for_dn = $dun
				//Добавляем ботов
				$vls = '';
				$sp = mysql_query('SELECT * FROM `dungeon_bots` WHERE `for_dn` = "'.$dun.'"');
				while($pl = mysql_fetch_array($sp))
				{
					$vls .= '("'.$zid.'","'.$pl['id_bot'].'","'.$pl['colvo'].'","'.$pl['items'].'","'.$pl['x'].'","'.$pl['y'].'","'.$pl['dialog'].'","'.$pl['items'].'"),';
				}
				$vls = rtrim($vls,',');				
				$ins1 = mysql_query('INSERT INTO `dungeon_bots` (`dn`,`id_bot`,`colvo`,`items`,`x`,`y`,`dialog`,`atack`) VALUES '.$vls.'');
				//Добавляем обьекты
				$vls = '';
				$sp = mysql_query('SELECT * FROM `dungeon_obj` WHERE `for_dn` = "'.$dun.'"');
				while($pl = mysql_fetch_array($sp))
				{
					$vls .= '("'.$zid.'","'.$pl['name'].'","'.$pl['img'].'","'.$pl['x'].'","'.$pl['y'].'","'.$pl['action'].'","'.$pl['type'].'","'.$pl['w'].'","'.$pl['h'].'","'.$pl['s'].'","'.$pl['s2'].'","'.$pl['os1'].'","'.$pl['os2'].'","'.$pl['os3'].'","'.$pl['os4'].'","'.$pl['type2'].'","'.$pl['top'].'","'.$pl['left'].'","'.$pl['date'].'"),';
				}
				$vls = rtrim($vls,',');	
				if($vls!='')
				{			
					$ins2 = mysql_query('INSERT INTO `dungeon_obj` (`dn`,`name`,`img`,`x`,`y`,`action`,`type`,`w`,`h`,`s`,`s2`,`os1`,`os2`,`os3`,`os4`,`type2`,`top`,`left`,`date`) VALUES '.$vls.'');
				}else{
					$ins2 = true;
				}
				if($upd2 && $ins1 && $ins2)
				{
					mysql_query('UPDATE `dungeon_zv` SET `delete` = "'.time().'" WHERE `id` = "'.$zv['id'].'" LIMIT 1');
					die('<script>location="main.php?rnd='.$code.'";</script>');
				}else{
					$re = 'Ошибка перехода в подземелье...';
				}
			}else{
				$re = 'Ошибка перехода в подземелье...';
			}
		}else{
			$re = 'Ошибка перехода в подземелье...';
		}
	}
}elseif(isset($_POST['go'],$_POST['goid']) && $g111==1)
{
	if(!isset($zv['id']))
	{
		$zv = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_zv` WHERE `city` = "'.$u->info['city'].'" AND `id`="'.mysql_real_escape_string($_POST['goid']).'" AND `delete` = "0" LIMIT 1'));
		if(isset($zv['id']))
		{
			if($u->info['level']>7)
			{
				$row = 0;
				if(5>$row)
				{
					$upd = mysql_query('UPDATE `stats` SET `dn` = "'.$zv['id'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					if(!$upd)
					{
						$re = 'Не удалось вступить в эту группу';
						unset($zv);
					}else{
						$u->info['dn'] = $zv['id'];
					}
				}else{
					$re = 'В группе нет места';
					unset($zv);
				}
			}else{
				$re = 'Вы не подходите по уровню';
				unset($zv);
			}
		}else{
			$re = 'Заявка не найдена';
		}
	}else{
		$re = 'Вы уже находитесь в группе';
	}
}elseif(isset($_POST['leave']) && isset($zv['id']) && $g111 == 1)
{
	if($zv['uid']==$u->info['id'])
	{
		//ставим в группу нового руководителя
		$ld = mysql_fetch_array(mysql_query('SELECT `id` FROM `stats` WHERE `dn` = "'.$zv['id'].'" AND `id` != "'.$u->info['id'].'" LIMIT 1'));
		if(isset($ld['id']))
		{
			$zv['uid'] = $ld['id'];
			mysql_query('UPDATE `dungeon_zv` SET `uid` = "'.$zv['uid'].'" WHERE `id` = "'.$zv['id'].'" LIMIT 1');
			mysql_query('UPDATE `stats` SET `dn` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			$u->info['dn'] = 0;
			unset($zv);
		}else{
			//удаляем группу целиком
			mysql_query('UPDATE `dungeon_zv` SET `delete` = "'.time().'" WHERE `id` = "'.$zv['id'].'" LIMIT 1');
			mysql_query('UPDATE `stats` SET `dn` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			$u->info['dn'] = 0;
			unset($zv);
		}
	}else{
		//просто выходим с группы
		mysql_query('UPDATE `stats` SET `dn` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		$u->info['dn'] = 0;
		unset($zv);
	}
}elseif(isset($_POST['add']) && $u->info['level']>1 && $g111 == 1)
{
	if($u->info['dn']==0)
	{
		$lmn = $u->info['level']-1;
		$lmx = $u->info['level']+1;
		$tmx = 0;
		
		if($lmn<2){ $lmn = 2; }
		if($lmn>7){ $lmn = 7; }
		if($lmx<2){ $lmx = 2; }
		if($lmx>7){ $lmx = 7; }
		
		if($u->info['level']>7)
		{
			$tmx = 5;
		}
		
		//только своего уровня
		$lmn = $u->info['level'];
		
		$ins = mysql_query('INSERT INTO `dungeon_zv`
		(`city`,`time`,`uid`,`dun`,`pass`,`com`,`lvlmin`,`lvlmax`,`team_max`) VALUES
		("'.$u->info['city'].'","'.time().'","'.$u->info['id'].'","'.$dun.'",
		"'.mysql_real_escape_string($_POST['pass']).'",
		"'.mysql_real_escape_string($_POST['text']).'",
		"'.$lmn.'",
		"'.$lmx.'",
		"'.$tmx.'")');
		if($ins)
		{
			$u->info['dn'] = mysql_insert_id();
			$zv['id'] = $u->info['dn'];
			$zv['uid'] = $u->info['id'];
			mysql_query('UPDATE `stats` SET `dn` = "'.$u->info['dn'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			$re = 'Вы успешно создали группу';
		}else{
			$re = 'Не удалось создать группу';
		}
	}else{
		$re = 'Вы уже находитесь в группе';
	}
}

//Генерируем список групп
$sp = mysql_query('SELECT * FROM `dungeon_zv` WHERE `city` = "'.$u->info['city'].'" AND `delete` = "0" AND `time` > "'.(time()-60*60*2).'"');
while($pl = mysql_fetch_array($sp))
{
	$dzs .= '<div style="padding:2px;">';
	if($u->info['dn']==0)
	{
		$dzs .= '<input type="radio" name="goid" id="goid" value="'.$pl['id'].'" />';
	}
	$dzs .= '<span class="date">'.date('H:i',$pl['time']).'</span> ';
	
	$pus = ''; //группа
	$su = mysql_query('SELECT `u`.`id`,`u`.`login`,`u`.`level`,`u`.`align`,`u`.`clan`,`st`.`dn`,`u`.`city`,`u`.`room` FROM `stats` AS `st` LEFT JOIN `users` AS `u` ON (`st`.`id` = `u`.`id`) WHERE `st`.`dn`="'.$pl['id'].'" LIMIT '.($pl['team_max']+1).'');
	while($pu = mysql_fetch_array($su))
	{
		$pus .= '<b>'.$pu['login'].'</b> ['.$pu['level'].']<a href="info/'.$pu['id'].'" target="_blank"><img src="http://img.xcombats.com/i/inf_capitalcity.gif" title="Инф. о '.$pu['login'].'"></a>';
		$pus .= ', ';
	}
	$pus = trim($pus,', ');
	
	$dzs .= $pus;
	
	if($pl['com']!='')
	{
		$dl = '';
		if(($moder['boi']==1 || $u->info['admin']>0) && $pl['dcom']==0)
		{
			$dl .= ' (<a href="?delcom='.$pl['id'].'&key='.$u->info['nextAct'].'&rnd='.$code.'">удалить комментарий</a>)';
			if(isset($_GET['delcom']) && $_GET['delcom']==$pl['id'] && $u->newAct($_GET['key'])==true)
			{
				mysql_query('UPDATE `dungeon_zv` SET `dcom` = "'.$u->info['id'].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
				$pl['dcom'] = $u->info['id'];
			}
		}
		
		$pl['com'] = htmlspecialchars($pl['com'],NULL,'cp1251');
		
		if($pl['dcom']>0)
		{
			$dl = ' <font color="grey"><i>комментарий удален модератором</i></font>';
		}	
		
		if($pl['dcom']>0)
		{
			if($moder['boi']==1 || $u->info['admin']>0)
			{
				$pl['com'] = '<font color="red">'.$pl['com'].'</font>';
			}else{
				$pl['com'] = '';
			}
		}
		
		$dzs .= '<small> | '.$pl['com'].''.$dl.'</small>';
	}
		
	$dzs .= '</div>';
}
?>
<style>
body
{
	background-color:#E2E2E2;
	background-image: url(http://img.xcombats.com/i/misc/showitems/dungeon.jpg);
	background-repeat:no-repeat;background-position:top right;
}
</style>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div style="padding-left:0px;" align="center">
      <h3><? echo $u->room['name']; ?></h3>
    </div></td>
    <td width="200"><div align="right">
      <table cellspacing="0" cellpadding="0">
        <tr>
          <td width="100%">&nbsp;</td>
          <td><table  border="0" cellpadding="0" cellspacing="0">
              <tr align="right" valign="top">
                <td><!-- -->
                    <? echo $goLis; ?>
                    <!-- -->
                    <table border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td nowrap="nowrap"><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#DEDEDE">
                            <tr>
                              <td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td>
                              <td bgcolor="#D3D3D3" nowrap="nowrap"><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=3.180.0.267&rnd=<? echo $code; ?>';" title="<? thisInfRm('3.180.0.267',1); ?>">Вход в подземелье</a></td>
                            </tr>
                        </table></td>
                      </tr>
                  </table></td>
              </tr>
          </table></td>
        </tr>
      </table>
      </div></td>
  </tr>
</table>
<?
if($re!='')
{
	echo '<font color="red"><b>'.$re.'</b></font><br>';
}

//отображаем
if($dzs=='')
{
	$dzs = '';
}else{
	if(!isset($zv['id']))
	{
		if($g111==1)
		{
			$pr = '<input name="go" type="submit" value="Вступить в группу">';
		}
		$dzs = '<form action="main.php?rnd='.$code.'" method="post">'.$pr.'<br>'.$dzs.''.$pr.'</form>';
	}
	$dzs .= '<hr>';
}

echo $dzs;
if($g111 == 1)
{
if($u->info['dn']==0)
{
?>
<table width="350" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
    <form id="from" name="from" action="main.php?pz1=<? echo $code; ?>" method="post">
      <fieldset style='padding-left: 5; width=50%'>
      <legend><b> Группа </b> </legend>
        Комментарий
        <input type="text" name="text" maxlength="40" size="40" />
      <br />
        Пароль
  <input type="password" name="pass" maxlength="25" size="25" />
  <br />
  <input type="submit" name="add" value="Создать группу" />
  &nbsp;<br />
      </fieldset>
    </form>
    </td>
  </tr>
</table>
<?
}else{
    $psh_start = '';
	if(isset($zv['id']))
	{
		if($zv['uid']==$u->info['id'])
		{
			$psh_start = '<INPUT type=\'button\' name=\'start\' value=\'Начать\' onClick="top.frames[\'main\'].location = \'main.php?start=1&rnd='.$code.'\'"> &nbsp;';
		}
		  
		echo '<br><FORM id="REQUEST" method="post" style="width:210px;" action="main.php?rnd='.$code.'">
		<FIELDSET style=\'padding-left: 5; width=50%\'>
		<LEGEND><B> Группа </B> </LEGEND>
		'.$psh_start.'
		<INPUT type=\'submit\' name=\'leave\' value=\'Покинуть группу\'> 
		</FIELDSET>
		</FORM>';
	}
}
?>

<?
}else{
	echo 'Поход в пещеры разрешен один раз в два часа. Осталось еще: '.$u->timeOut(7200-time()+$g11['time']).'<br><small style="color:grey">Но Вы всегда можете приобрести ключ от прохода у любого &quot;копателя пещер&quot; в Торговом зале ;)</small>';
}
}

?>