<?
//возможности (перечисляем)
$vz_id = array(
0=>'m1',
1=>'mm1',
2=>'m2',
3=>'mm2',
4=>'sm1',
5=>'sm2',
6=>'citym1',
7=>'citym2',
8=>'citysm1',
9=>'citysm2',
10=>'addld',
11=>'cityaddld',
12=>'seeld',
13=>'telegraf',
14=>'f1',
15=>'f2',
16=>'f3',
17=>'f4',
18=>'f5',
19=>'f6',
20=>'f7',
21=>'f8',
22=>'boi',
23=>'elka',
24=>'haos',
25=>'haosInf',
26=>'deletInfo',
27=>'zatoch',
28=>'banned',
29=>'unbanned',
30=>'readPerevod',
31=>'provItm',
32=>'provMsg',
33=>'trPass',
34=>'shaos',
35=>'szatoch',
36=>'editAlign',
37=>'priemIskl',
38=>'proverka',
39=>'marry',
40=>'ban0');
//названия возможностей
$vz = array(
'm1'=>'Заклятие молчания',
'mm1'=>'Заклятие молчания (3 дн.)',
'm2'=>'Заклятие форумного молчания',
'mm2'=>'Заклятие форумного молчания (3 дн.)',
'sm1'=>'Снять молчанку',
'sm2'=>'Снять форумную молчанку',
'citym1'=>'Заклятие молчания (междугородняя)',
'citym2'=>'Заклятие форумного молчания (междугородняя)',
'citysm1'=>'Снять молчанку (междугородняя)',
'citysm2'=>'Снять форумную молчанку (междугородняя)',
'addld'=>'Добавить запись в личное дело',
'cityaddld'=>'Добавить запись в личное дело (междугородняя)',
'seeld'=>'Просмотр личного дела',
'telegraf'=>'Телеграф',
'f1'=>'Форум. Ответ в ответе',
'f2'=>'Форум. Удаление ответа',
'f3'=>'Форум. Восстановление темы',
'f4'=>'Форум. Удаление темы',
'f5'=>'Форум. Перемещение темы',
'f6'=>'Форум. Прикрепление / Открепление темы',
'f7'=>'Форум. Возобновление обсуждения',
'f8'=>'Форум. Закрытие обсуждения',
'boi'=>'Модерация боев',
'elka'=>'Модерация ёлки',
'haos'=>'Хаос',
'haosInf'=>'Хаос (бессрочно)',
'deletInfo'=>'Снять / Наложить Обезличивание',
'zatoch'=>'Заточение персонажа',
'banned'=>'Блокировка персонажа',
'unbanned'=>'Разблокировка персонажа',
'readPerevod'=>'Просмотр переводов',
'provItm'=>'Проверка инвентаря',
'provMsg'=>'Проверка сообщений',
'trPass'=>'Требует пароль',
'shaos'=>'Снять хаос',
'szatoch'=>'Выпустить из заточения',
'editAlign'=>'Функции управленца',
'priemIskl'=>'Прием / Исключение',
'proverka'=>'Проверка на чистоту',
'marry'=>'Обвенчать / Развести',
'ban0'=>'Блокировка [0] уровней');

if(isset($_GET['save'],$_POST['alignSave']))
		{
			//сохраняем данные
			$sv = mysql_fetch_array(mysql_query('SELECT * FROM `moder` WHERE `id` = "'.mysql_real_escape_string($_POST['alignSave']).'" LIMIT 1'));
			if(isset($sv['id']) && ($sv['align'] < $u->info['align'] || $u->info['admin']>0))
			{
				$ud = '';
				$i = 0;
				while($i<count($vz_id))
				{
					if($vz_id[$i]!='editAlign' || $u->info['admin']>0)
					{
						if(isset($sv[$vz_id[$i]]))
						{
							if(isset($_POST[$vz_id[$i]]))
							{
								if($i==33)
								{
									//пароль на модераторскую панель
									if($_POST['trPassText']!='')
									{
										$ud .= '`'.$vz_id[$i].'`="'.mysql_real_escape_string(md5($_POST['trPassText'])).'",';
									}
								}else{
									$ud .= '`'.$vz_id[$i].'`="1",';
								}
							}else{
								if($i==33)
								{
									//пароль на модераторскую панель
									$ud .= '`'.$vz_id[$i].'`="",';
								}else{
									$ud .= '`'.$vz_id[$i].'`="0",';
								}
							}
						}
					}
					$i++;
				}
				$ud = rtrim($ud,',');
				$upd = mysql_query('UPDATE `moder` SET '.$ud.' WHERE `id` = "'.$sv['id'].'" LIMIT 1');
				if($upd)
				{
					$merror = 'Изменения были сохранены';
				}else{
					$merror = 'Ошибка сохранения';
				}
			}else{
				$merror = 'Ошибка. У Вас нет доступа';
			}
		}
?>
<table width="100%">
  <tr>
    <td align="center"><h3>Функции управленца</h3></td>
    <td width="150" align="right"><input type="button" value=">" onclick="location='main.php?<? echo $zv; ?>';" />
      <? if($u->info['admin']>0){ ?><input type="button" value="<? if($a==1){ echo 'PAL'; }else{ echo 'ARM'; } ?>" onclick="location='main.php?go=1&<? echo $zv; ?>&remod=<? echo $a; ?>';" /><? } ?><? if($p['trPass']!=''){ ?>
    <input type="button" value="X" title="Закрыть доступ" onclick="location='main.php?<? echo $zv.'&rnd='.$code; ?>&amp;exitMod=1';" /><? } ?></td>
  </tr>
  <tr>
    <td>
        <? 
		if($merror!='')
		{
			echo '<font color="red">'.$merror.'</font>';
		}
		?>
        <table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#E1E1E1">
	    <?
		$sp = mysql_query('SELECT * FROM `moder` WHERE `align`<='.$u->info['align'].' && `align`>'.$a.' ORDER BY `align` DESC LIMIT 20');
		while($pl = mysql_fetch_array($sp))
		{
		?>
            <tr>
              <td style="border-bottom:1px solid #CCCCCC;" width="250"><div align="left" style="margin-left:11px;"><? echo '<img src="http://img.xcombats.com/i/align/align'.$pl['align'].'.gif"> <small><b>'.$u->mod_nm[$a][$pl['align']].'</b></small>' ?></div><div align="left"></div></td>
              <td width="50" bgcolor="#DADADA" style="border-bottom:1px solid #CCCCCC;"><div align="center"><? if($u->info['align']>$pl['align'] || $u->info['admin']>0){ ?><a href="main.php?go=1&edit=<? echo $pl['id'].'&'.$zv; ?>">ред.</a><? }else{ echo '<b style="color:grey;">ред.</b>'; } ?></div></td>
              <td style="border-bottom:1px solid #CCCCCC;">Возможности: <? 
			  $voz = '';
			  $i = 0;
			  while($i<count($vz_id))
			  {
			  	if($pl[$vz_id[$i]]>0)
				{
					$voz .= '<b>'.$vz[$vz_id[$i]].'</b>, ';
				}
				$i++;
			  }
			  $voz = trim($voz,', ');
			  if($voz=='')
			  {
			  	$voz = 'красивый значек :-)';
			  }
			  echo '<small><font color="grey">'.$voz.'</font></small>';
			  
			   ?></td>
            </tr>
            <? if(isset($_GET['edit']) && $pl['id']==$_GET['edit']){ ?>
            <tr>
              <td valign="top" bgcolor="#F3F3F3" style="border-bottom:1px solid #CCCCCC; color:#757575;">Изменение возможностей:<Br /><a href="main.php?<? echo $zv; ?>&go=1" onClick="document.getElementById('saveDate').submit(); return false;">Сохранить изменения</a><br /><a href="main.php?<? echo $zv; ?>&go=1">Скрыть панель</a></td>
              <td valign="top" bgcolor="#F3F3F3" style="border-bottom:1px solid #CCCCCC;"></td>
              <td valign="top" bgcolor="#F3F3F3" style="border-bottom:1px solid #CCCCCC;">
              <form id="saveDate" name="saveDate" method="post" action="main.php?<? echo $zv.'&go=1&save='.$code; ?>">
              <?
			  $voz = '';
			  $i = 0;
			  while($i<count($vz_id))
			  {
				if($vz_id[$i]!='editAlign' || $u->info['admin']>0)
				{
					if($pl[$vz_id[$i]]>0)
					{
						$voz .= '<input name="'.$vz_id[$i].'" type="checkbox" value="1" checked>';
					}else{
						$voz .= '<input name="'.$vz_id[$i].'" type="checkbox" value="1">';
					}
					$voz .= ' '.$vz[$vz_id[$i]];
					if($i==33)
					{
						$voz .= ': <input name="trPassText" value="" type="password">';
					}
					$voz .= '<br>';
				}
				$i++;
			  }
			  echo $voz;
			  ?>
              <input name="alignSave" type="hidden" id="alignSave" value="<? echo $pl['id']; ?>" />
              </form>              </td>
            </tr>
        <?
			}
		}
	    ?>
      </table>    </td>
  </tr>
</table>