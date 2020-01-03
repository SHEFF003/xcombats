<?php
if(!defined('GAME'))
{
	die();
}

if($u->room['file']=='nc/vokzal')
{
	$cs = ''; $cbuy = array(); $tmref = 0;
	$sp = mysql_query('SELECT * FROM `vokzal` WHERE `city` = "'.$u->info['city'].'" OR `tocity` = "'.$u->info['city'].'"');
	while($pl = mysql_fetch_array($sp))
	{
		$vz1 = mysql_fetch_array(mysql_query('SELECT * FROM `room` WHERE `name` = "Вокзал" AND `city` = "'.$pl['city'].'" LIMIT 1'));
		$vz2 = mysql_fetch_array(mysql_query('SELECT * FROM `room` WHERE `name` = "Вокзал" AND `city` = "'.$pl['tocity'].'" LIMIT 1'));	
		$crm = mysql_fetch_array(mysql_query('SELECT * FROM `room` WHERE `name` = "'.$pl['name'].'" LIMIT 1'));
		//period 0 - прибытие в город (стоянка), 1 - движение, 3 - прибытие в другой город (стоянка), 4 - движение (из tocity)
		if($pl['time_start_go']==0)
		{
			//Это новая карета обновляем данные
			mysql_query('UPDATE `vokzal` SET `time_start_go` = "'.(time()+$pl['timeStop']*60).'",`time_finish_go` = "'.(time()+$pl['timeStop']*60+$pl['time_go']*60).'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
			$pl['time_start_go'] = time()+$pl['timeStop']*60;
			$pl['time_finish_go'] = $pl['time_start_go']+$pl['time_go']*60;
		}
		$see = 1;
		$plc = $pl['tocity'];
		$col = 'e6e6e6" style="color:#B7B7B7;"';	
		$tmgo = '<small>(Прибудет в <b>'.date('H:i',$pl['time_finish_go']).'</b>)</small>';
		$bl = '--';
		$bb = 'билетов нет';
		if($pl['time_start_go']-600<time() && $pl['time_start_go']>time())
		{
			//можно знанимать места в карете
			if(isset($crm['id']))
			{
				$sr = mysql_query('SELECT `uid`,`id` FROM `items_users` WHERE `secret_id` = "'.$pl['time_start_go'].'_b'.$pl['id'].'" AND `delete` = "0" LIMIT 100');
				while($pr = mysql_fetch_array($sr))
				{
					$upd1 = mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$pr['id'].'" LIMIT 1');
					if($upd1)
					{
						mysql_query('UPDATE `users` SET `room` = "'.$crm['id'].'" WHERE `online` > '.(time()-120).' AND `id` = "'.$pr['uid'].'" LIMIT 1');
					}
				}
			}
		}
		if((($pl['period']==0 && $u->info['city']==$pl['city']) || ($pl['period']==3 && $u->info['city']==$pl['tocity'])) && $pl['time_start_go']>time() && $pl['citygo']!=$u->info['city'])
		{
			$see = 1;
			$tmgo = date('d.m.Y в H:i',$pl['time_start_go']);
			$col = 'c9c9c9';
			$bl = $pl['bilets'];
			$bb = '<input type="button" onClick="location=\'main.php?buy='.$pl['id'].'&sd4='.$u->info['nextAct'].'\'" value="купить билет">';
			if($pl['bilets']<=0)
			{
				$bb = 'билетов нет';
			}
		}else{
			//отправляем карету в другой город
			if($pl['time_finish_go']<time())
			{
				//прибыли
				if($pl['period']==0)
				{
					//Прибыли в город, время стоянки закончилось, и поехали
					mysql_query('UPDATE `vokzal` SET `period` = "1",`citygo` = "'.$pl['tocity'].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
					$pl['period'] = 1;
				}elseif($pl['period']==1)
				{
					//приехалис в другой город, делаем там стоянку
					if(isset($crm['id']))
					{
						mysql_query('UPDATE `users` SET `city` = "'.$pl['tocity'].'",`room` = "'.$vz2['id'].'" WHERE `room` = "'.$crm['id'].'" LIMIT '.$pl['bilets_default'].'');
					}
					mysql_query('UPDATE `vokzal` SET `bilets` = "'.$pl['bilets_default'].'",`citygo`="'.$pl['city'].'",`time_finish_go` = "'.(time()+$pl['timeStop']*60+$pl['time_go']*60).'",`time_start_go` = "'.(time()+$pl['timeStop']*60).'",`period` = "3" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
					$pl['period'] = 3;
				}elseif($pl['period']==3)
				{
					//Прибыли в город, время стоянки закончилось, и поехали
					mysql_query('UPDATE `vokzal` SET `period` = "4" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
					$pl['period'] = 4;
				}elseif($pl['period']==4)
				{
					//приехалис в другой город, делаем там стоянку
					if(isset($crm['id']))
					{
						mysql_query('UPDATE `users` SET `city` = "'.$pl['city'].'",`room` = "'.$vz1['id'].'" WHERE `room` = "'.$crm['id'].'" LIMIT '.$pl['bilets_default'].'');
					}
					mysql_query('UPDATE `vokzal` SET `bilets` = "'.$pl['bilets_default'].'",`citygo`="'.$pl['tocity'].'",`time_finish_go` = "'.(time()+$pl['timeStop']*60+$pl['time_go']*60).'",`time_start_go` = "'.(time()+$pl['timeStop']*60).'",`period` = "0" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
					$pl['period'] = 0;
				}else{
					echo '[?]';
				}
			}
		}
		
		if($see==1)
		{	
			if($pl['period']==0 || $pl['period']==1)
			{
				$plc = $pl['tocity'];
			}else{
				$plc = $pl['city'];
			}
			$cs .= '<tr>
			<td height="30" bgcolor="#'.$col.'" align="center">'.$tmgo.'</td>
			<td bgcolor="#'.$col.'" align="center">'.$u->city_name[$plc].'</td>
			<td bgcolor="#'.$col.'" align="center">'.$pl['time_go'].' мин.</td>
			<td bgcolor="#'.$col.'" align="center">'.$pl['price1'].' кр.</td>
			<td bgcolor="#'.$col.'" align="center"> нет </td>
			<td bgcolor="#'.$col.'" align="center">'.$bl.'</td>
			<td bgcolor="#'.$col.'" align="center">'.$bb.'</td>
		    </tr>';
			if($pl['time_start_go']-time()<$tmref)
			{
				$tmref = $pl['time_start_go']-time();
			}
			if($bl!='--' && $bl>0 && $pl['citygo']!=$u->info['city'])
			{
				$cbuy[$pl['id']] = 1;
			}
		}
	}
	
	if(isset($_GET['buy']) && $u->newAct($_GET['sd4'])==true)
	{
		$buy = mysql_fetch_array(mysql_query('SELECT * FROM `vokzal` WHERE `time_start_go` > "'.time().'" AND `citygo` != "'.$u->info['city'].'" AND `id` = "'.mysql_real_escape_string($_GET['buy']).'" LIMIT 1'));
		if(isset($buy['id']) && isset($cbuy[$buy['id']]))
		{
			if($buy['bilets']<=0)
			{
				$error = 'Билетов больше нет, загляните позже';
			}elseif($u->info['money']>=$buy['price1'])
			{
				$u->info['money'] -= $buy['price1'];
				$upd = mysql_query('UPDATE `users` SET `money` = "'.$u->info['money'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				if($upd)
				{
					//передаем Билет персонажу
					$error = 'Вы заплатили '.$buy['price1'].' кр. за билет в '.$u->city_name[$buy['tocity']].'<br>Отправка в '.date('d.m.Y H:i',$buy['time_start_go']).' по серверу';
					$ib = 'Дата отправления кареты в '.$u->city_name[$buy['tocity']].': '.date('d.m.Y в H:i',$buy['time_start_go']).'<br>Билет на имя: <b>'.$u->info['login'].'</b>';
					$ins = mysql_query('INSERT INTO `items_users` (`1price`,`maidin`,`data`,`uid`,`item_id`,`iznosMAX`,`lastUPD`,`secret_id`,`time_create`) VALUES ("'.$buy['price1'].'","'.$u->info['city'].'","info='.$ib.'|noodet=1","'.$u->info['id'].'","866","1","'.time().'","'.$buy['time_start_go'].'_b'.$buy['id'].'","'.time().'")');
					if($ins)
					{
						$error .= '<br>Предмет &quot;Билет&quot; был перемещен к Вам в инвентарь, в раздел &quot;прочее&quot;.';
						mysql_query('UPDATE `vokzal` SET `bilets` = "'.($buy['bilets']-1).'" WHERE `id` = "'.$buy['id'].'" LIMIT 1');
					}else{
						$error = 'Не удалось приобрести билет';
					}
				}else{
					$u->info['money'] += $buy['price1'];
					$error = 'Не удалось приобрести билет';
				}
			}else{
				$error = 'У Вас недостаточно денег';
			}
		}else{
			$error = 'Не удалось приобрести билет';
		}
	}
	
	if(isset($_GET['teleport']))
	{
		$tp = mysql_fetch_array(mysql_query('SELECT * FROM `teleport` WHERE `city` = "'.$u->info['city'].'" AND `cancel` = "0" AND `id` = "'.((int)$_GET['teleport']).'" LIMIT 1'));
		if(isset($tp['id']))
		{
			if($u->info['money']>=$tp['price1'])
			{
				$rm = mysql_fetch_array(mysql_query('SELECT * FROM `room` WHERE `name` = "Вокзал" AND `city` = "'.$tp['toCity'].'" LIMIT 1'));
				if(isset($rm['id']))
				{
					$u->info['money'] -= $tp['price1'];
					$u->info['city'] = $tp['toCity'];
					mysql_query('UPDATE `users` SET `money` = "'.$u->info['money'].'",`city` = "'.$u->info['city'].'",`room` = "'.$rm['id'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					$u->addAction((time()+$tp['time']*60),'teleport',$tp['toCity']);
					die('<script>location="main.php";</script>');
				}else{
					$error = 'Нельзя телепортироваться, возможно вокзал в этом городе был разрушен...';
				}
			}else{
				$error = 'У вас недостаточно средсв';
			}
		}else{
			$error = 'Нельзя телепортироваться...';
		}
	}
	
	$cst = ''; $zd = $u->testAction('`uid` = "'.$u->info['id'].'" AND `time` >= '.time().' AND `vars` = "teleport" LIMIT 1',1);
	$sp = mysql_query('SELECT * FROM `teleport` WHERE `city` = "'.$u->info['city'].'" AND `cancel` = "0"');
	while($pl = mysql_fetch_array($sp))
	{
		$col = 'e6e6e6" style="color:#B7B7B7;"';
		if(!isset($zd['id']))
		{
			$col = 'c9c9c9';
		}
		$cst .= '<tr>
			<td bgcolor="#'.$col.'" align="center">'.$pl['toCity'].'</td>
			<td bgcolor="#'.$col.'" align="center">'.$u->timeOut($pl['time']*60).'</td>
			<td bgcolor="#'.$col.'" align="center">'.$pl['price1'].' кр.</td>';
			if(isset($zd['id']))
			{
				$cst .= '<td bgcolor="#'.$col.'" align="center">Задержка еще '.$u->timeOut($zd['time']-time()).'</td>';
			}else{
				$cst .= '<td bgcolor="#'.$col.'" align="center"><a href="?teleport='.$pl['id'].'">Поехали!</a></td>';
			}
		    $cst .= '</tr>';
	}
	if($re!=''){ echo '<div align="right"><font color="red"><b>'.$re.'</b></font></div>'; } ?>
	<style type="text/css"> 
	
	.pH3			{ COLOR: #8f0000;  FONT-FAMILY: Arial;  FONT-SIZE: 12pt;  FONT-WEIGHT: bold; }
	.class_ {
		font-weight: bold;
		color: #C5C5C5;
		cursor:pointer;
	}
	.class_st {
		font-weight: bold;
		color: #659BA3;
		cursor:pointer;
	}
	.class__ {
		font-weight: bold;
		color: #FFFFFF;
		cursor:pointer;
		background-color: #659BA3;
	}
	.class__st {
		font-weight: bold;
		color: #FFFFFF;
		cursor:pointer;
		background-color: #659BA3;
		font-size: 10px;
	}
	.class_old {
		font-weight: bold;
		color: #919191;
		cursor:pointer;
	}
	.class__old {
		font-weight: bold;
		color: #FFFFFF;
		cursor:pointer;
		background-color: #838383;
		font-size: 10px;
	}	
	</style>
	<TABLE width="100%" cellspacing="0" cellpadding="0">
	<tr><td valign="top"><div align="center" class="pH3"><? echo $u->room['name'].' "'.$u->city_name[$u->info['city']].'"'; ?></div>
	<?php
	echo '<b style="color:red">'.$error.'</b>';
	if($cst!='')
	{
	?>
    <center>
	<b>Телепортация в другие города<? if(isset($zd['id'])){ echo ' </b>(Возможна через '.$u->timeOut($zd['time']-time()).')<b>'; } ?></b>
	</center>
	<? if(!isset($zd['id'])){ ?>
    <br />
	<table width="100%" border="0" cellspacing="1" cellpadding="0">
	  <tr>
	    <td width="25%" bgcolor="#81888e"><div align="center">пункт назначения</div></td>
	    <td width="25%" bgcolor="#81888e"><div align="center">время задержки телепортации</div></td>
	    <td width="25%" bgcolor="#81888e"><div align="center">цена телепортации</div></td>
	    <td width="25%" bgcolor="#81888e"><div align="center">Телепортироваться</div></td>
	    </tr>
	  <? echo $cst; ?>
	  </table>
	<br />
    <? } } unset($zd); ?>
    <center><b><br />Расписание движения карет на сегодня</b></center>
	<br />
	<table width="100%" border="0" cellspacing="1" cellpadding="0">
      <tr>
        <td width="16%" bgcolor="#81888e"><div align="center">время отправления</div></td>
        <td width="14%" bgcolor="#81888e"><div align="center">пункт назначения</div></td>
        <td width="14%" bgcolor="#81888e"><div align="center">время в пути</div></td>
        <td width="14%" bgcolor="#81888e"><div align="center">цена билета</div></td>
        <td width="14%" bgcolor="#81888e"><div align="center">требуется виза</div></td>
        <td width="14%" bgcolor="#81888e"><div align="center">осталось билетов</div></td>
        <td width="14%" bgcolor="#81888e"><div align="center">приобрести билет</div></td>
      </tr>
      <? echo $cs; ?>
    </table>
    <? if($tmref>600 && $tmref>0){ echo '<script>setTimer(\'location = location;\','.(1000*$tmref-600).');</script>'; } if($cs==''){ echo '<center><br>Сегодня нет свободных карет для перемещения в другие города</center>'; } ?>
    <br /><br />
    <small style="color:#999999;">
    - Для отправления в другой город Вы должны быть онлайн когда будет отправляться карета<br />
    - Если Вы опоздали на карету, тогда билет можно сдать в магазин за половину его стоимости<br />
    </small>
	<td width="280" valign="top">
    <TABLE cellspacing="0" cellpadding="0"><TD width="100%">&nbsp;</TD><TD>
	<table  border="0" cellpadding="0" cellspacing="0">
	<tr align="right" valign="top">
	<td>
	<!-- -->
	<? echo $goLis; ?>
	<!-- -->
	<table border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td nowrap="nowrap">
	<table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#DEDEDE">
	<tr>
	<td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td>
	<td bgcolor="#D3D3D3" nowrap><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=5.180.0.307&rnd=<? echo $code; ?>';" title="<? thisInfRm('5.180.0.307',1); ?>">Центральная Площадь</a></td>
	</tr>
	</table>
	</td>
	</tr>
	</table>
	</td></table>
	</td></table>
	<div>
	  <br />
      <div align="right">
      <small>
	  Масса: <?=$u->aves['now']?>/<?=$u->aves['max']?> &nbsp;<br />
	  У вас в наличии: <b style="color:#339900;"><?php echo round($u->info['money'],2); ?> кр.</b> &nbsp;
      </small>
      </div>
	  <br />
    <br />
	</div>
	</td>
	</table>
    <br>
	<div id="textgo" style="visibility:hidden;"></div>
<?
}
?>