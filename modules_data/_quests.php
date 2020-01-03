<?php
if(!defined('GAME'))
{
	die();
}
if(isset($_GET['start']))
{
	$pl = mysql_fetch_array(mysql_query('SELECT * FROM `quests` WHERE (`city` = "" OR `city` = "'.$u->info['city'].'") AND `delete` = "0" AND `min_lvl` <= '.$u->info['level'].' AND `max_lvl` >= '.$u->info['level'].' AND (`align` = "0" OR `align` = "'.floor($u->info['align']).'") AND `id` = "'.mysql_real_escape_string($_GET['start']).'" LIMIT 1'));
	if(isset($pl['id']) && $q->testGood($pl)==1)
	{
		$q->startq($pl['id']);
	}
}elseif(isset($_GET['end']))
{
	$q->endq((int)$_GET['end'],'end');
}
if($u->error!='')
{
	echo '<font color="red"><b>'.$u->error.'</b></font><br>';
}
?>
<table width="100%">
  <tr>
    <td align="center"><h3>Доступные задания, <?=$u->info['city']?></h3></td>
    <td width="150" align="right"><input type="button" value="обновить" onclick="location='main.php?quests=1';" />      <input type="button" value="Вернуться" onclick="location='main.php';" /></td>
  </tr>
  <tr>
  	<td><a href="main.php?skills=1&rz=6">Активные квесты</a></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
    <?
	$qsee = ''; $j = 0;
	$sp = mysql_query('SELECT * FROM `quests` WHERE (`city` = "" OR `city` = "'.$u->info['city'].'") AND `delete` = "0" AND `min_lvl` <= '.$u->info['level'].' AND `max_lvl` >= '.$u->info['level'].' AND (`align` = "0" OR `align` = "'.floor($u->info['align']).'")');
	while($pl = mysql_fetch_array($sp))
	{
	  if($j<20)
	  {
		  if($q->testGood($pl)==1)
		  {
			  $urq = '<a href="main.php?quests&minfo='.$pl['id'].'">Подробнее</a>';
			  if(isset($_GET['minfo']) && $_GET['minfo']==$pl['id'])
			  {
				$urq = '<a href="main.php?quests=1">Скрыть</a>';  
			  }
				$qsee .= '<tr>
				<td style="border-bottom:1px solid #CCCCCC;" width="300"><div align="left" style="margin-left:11px;">'.$pl['name'].'</div></td>
				<td width="75" bgcolor="#DADADA" style="border-bottom:1px solid #CCCCCC;"><div align="center"><small>'.$urq.'</small></div></td>
				<td style="border-bottom:1px solid #CCCCCC;"><small><b>Описание:</b> '.$pl['info'].'</small></td>
			  </tr>';
			  unset($urq);
			  if(isset($_GET['minfo']) && $_GET['minfo']==$pl['id'])
			  {
				if($pl['city']!='')
				{
					$gc = '<small>Город квеста: <img src="http://img.xcombats.com/i/city_ico/'.$pl['city'].'.gif" width="17" height="15"> '.$u->city_name[$pl['city']].'</small>';
				}else{
					$gc = '&nbsp;';
				}
				$di = $q->info($pl);
				$di .= '<a href="main.php?quests&start='.$pl['id'].'">Принять задание</a><br><br>';		
				$qsee .= '<tr>
				<td style="border-bottom:1px solid #CCCCCC;" width="300">'.$gc.'</td>
				<td width="75" style="border-bottom:1px solid #CCCCCC;">&nbsp;</td>
				<td style="border-bottom:1px solid #CCCCCC;">'.$di.'</td>
				</tr>';
				unset($gc,$di,$w);
			  }
			  $j++;
		  }
	  }
	}
	
	if($qsee == '')
	{
		$qsee = '<tr><td align="center" bgcolor="#EAEAEA">Для вас нет доступных заданий, приходите позже</td></tr>';
	}
	?>
    <table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#E1E1E1">
      <!-- -->
	  <?=$qsee?>
      <!-- -->
    </table>
    </td>
  </tr>
</table>
