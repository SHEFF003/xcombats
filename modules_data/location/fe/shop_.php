<?php
if(!defined('GAME'))
{
	die();
}

if($u->room['file']=='fe/shop_')
{
	$shopProcent = 0;

	if(isset($_POST['itemgift']))
	{
		$to = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.mysql_real_escape_string('Рут').'" LIMIT 1'));
		if(isset($to['id']))
		{
			if($u->info['align'] == 2 || $u->info['haos'] > time()) {
				$re = '<div align="left">Хаосникам запрещается делать подарки другим игрокам</div>';
			}elseif($to['id']==$u->info['id'])
			{
				$re = '<div align="left">Очень щедро дарить что-то самому себе ;)</div>';
			}elseif($u->info['level']<1)
			{
				$re = '<div align="left">Дарить подарки можно начиная с 1-го уровня</div>';
			}else{
				$itm = mysql_fetch_array(mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE (`im`.`type` = "28" OR `im`.`type` = "38" OR `im`.`type` = "63" OR `im`.`type` = "64") AND `iu`.`id` = "'.mysql_real_escape_string($_POST['itemgift']).'" AND `iu`.`uid` = "'.$u->info['id'].'" AND `iu`.`gift` = "" AND `iu`.`delete` = "0" AND `iu`.`inOdet` = "0" AND `iu`.`inShop` = "0" LIMIT 1'));
				if(isset($itm['id']))
				{
					//$itm['data'] = '';
					
					$itm['gtxt1'] = $_POST['podarok2'];
					$itm['gtxt2'] = $_POST['txt'];
										
					$itm['gtxt1'] = str_replace('\x3C','<',$itm['gtxt1']);
					$itm['gtxt1'] = str_replace('\x3','>',$itm['gtxt1']);	
					$itm['gtxt1'] = htmlspecialchars($itm['gtxt1'],NULL,'cp1251');
					$itm['gtxt2'] = str_replace('\x3C','<',$itm['gtxt2']);
					$itm['gtxt2'] = str_replace('\x3','>',$itm['gtxt2']);	
					$itm['gtxt2'] = htmlspecialchars($itm['gtxt2'],NULL,'cp1251');
					
					$upd = mysql_query('UPDATE `items_users` SET `data` = "'.$itm['data'].'",`gtxt1` = "'.mysql_real_escape_string($itm['gtxt1']).'",`gtxt2` = "'.mysql_real_escape_string($itm['gtxt2']).'",`uid` = "'.$to['id'].'",`gift` = "'.$u->info['login'].'",`time_create` = "'.time().'" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
					if($upd)
					{
						$re = '<div align="left">Подарок не был успешно отправлен к &quot;'.$_POST['to_login'].'&quot;, мы его отправили администратору.</div>';
					}else{
						$re = '<div align="left">Не удалось сделать подарок</div>';
					}
				}else{
					$re = '<div align="left">Предмет не найден</div>';
				}
			}
		}else{
			$re = '<div align="left">Персонаж с таким логином не найден</div>';
		}
	}
	
	if(isset($u->stats['shopSale'],$_GET['sale']))
	{
		$bns = 0+$u->stats['shopSale'];
		if($bns!=0)
		{
			if($bns>0)
			{
				$bns = '+'.$bns;
			}
			$shopProcent -= $bns;
			if($shopProcent>99){ $shopProcent = 99; }
			if($shopProcent<1){ $shopProcent = 1; }
			echo '<div style="color:grey;"><b>У Вас действует бонус при продаже: '.$bns.'%</b><br><small>Вы сможете продавать предметы за '.(100-$shopProcent).'% от их стоимости</small></div>';
		}
	}
	
	if(!isset($_GET['otdel'])) 
	{
		$_GET['otdel'] = 1;
	}

	$sid = 300;
	/*
	if($u->info['id'] = 10001) {
		$sp = mysql_query('SELECT * FROM `items_shop` WHERE `sid` = 1 AND `kolvo` > 0');
		while($pl = mysql_fetch_array($sp)) {
			$test = mysql_fetch_array(mysql_query('SELECT `iid` FROM `items_shop` WHERE `sid` = 300 AND `item_id` = "'.$pl['item_id'].'" LIMIT 1'));
			if(!isset($test['iid'])) {
				//добавляем предмет
				mysql_query('INSERT INTO `items_shop` (`sid`,`item_id`,`kolvo`,
				`data2`,`magic_inc`,`timeOver`,`overType`,`secret_id`,`r`,`price_1`,`price_2`,`level`,`tr_items`,`max_buy`) VALUES (
									"300","'.$pl['item_id'].'","10000",
				"'.$pl['data2'].'","'.$pl['magic_inc'].'","'.$pl['timeOver'].'","'.$pl['overType'].'","'.$pl['secret_id'].'","'.$pl['r'].'","'.$pl['price_1'].'","'.$pl['price_2'].'","'.$pl['level'].'","'.$pl['tr_items'].'","'.$pl['max_buy'].'"
				)');
			}
		}
	}
	*/
	$error = '';
	
	if(isset($_GET['buy']))
	{
		if($u->newAct($_GET['sd4'])==true)
		{
			$re = $u->buyItem($sid,(int)$_GET['buy'],(int)$_GET['x']);
		}else{
			$re = 'Вы уверены что хотите купить этот предмет?';
		}
	}elseif(isset($_GET['sale']) && isset($_GET['item']) && $u->newAct($_GET['sd4']))
	{
		$id = (int)$_GET['item'];
		$itm = mysql_fetch_array(mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `iu`.`id` = "'.mysql_real_escape_string($id).'" LIMIT 1'));
		$po = $u->lookStats($itm['data']);		
		if(isset($po['nosale']))
		{
			$error = 'Не удалось продать предмет ...';
		}elseif($pl['type']<29 && ($po['srok'] > 0 || $pl['srok'] > 0))
		{
			$error = 'Не удалось продать предмет ...';
		}elseif(isset($po['frompisher']))
		{
			$error = 'Не удалось продать предмет ...';
		}elseif(isset($itm['id']))
		{
			if($itm['1price']>0)
			{
				$itm['price1'] = $itm['1price'];
			}
			$shpCena = $itm['price1'];
			
			$plmx = 0;
			if($itm['iznosMAXi']!=$itm['iznosMAX'] && $itm['iznosMAX']!=0)
			{
				$plmx = $itm['iznosMAX'];
			}else{
				$plmx = $itm['iznosMAXi'];
			}
			
			if($itm['iznosNOW']>0)
			{
				$prc1 = $itm['iznosNOW']/$plmx*100;
			}else{
				$prc1 = 0;
			}
			$shpCena = $shpCena/100*(100-$prc1);
			if($itm['iznosMAX']>0 && $itm['iznosMAXi']>0 && $itm['iznosMAXi']>$itm['iznosMAX'])
			{
				$shpCena = $shpCena/100*($itm['iznosMAX']/$itm['iznosMAXi']*100);
			}
			$shpCena = $u->round2($shpCena/100*(100-$shopProcent));
			if($shpCena<0)
			{
				$shpCena = 0;
			}
			$col = $u->itemsX($itm['id']);	
			if($col>0)
			{
				$shpCena = $shpCena*$col;
			}
			if($shpCena<0)
			{
				$shpCena = 0;
			}
			$upd2 = mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
			if($upd2)
			{
				$u->info['money'] += $shpCena;
				$upd = mysql_query('UPDATE `users` SET `money` = "'.$u->info['money'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				if($upd)
				{
					$error = 'Вы успешно продали предмет &quot;'.$itm['name'].' [x'.$col.']&quot; за '.$shpCena.' кр.';
					mysql_query('UPDATE `items_users` SET `inGroup` = "0",`delete` = "'.time().'" WHERE `inGroup` = "'.$itm['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT '.$itm['group_max'].'');
					$u->addDelo(2,$u->info['id'],'&quot;<font color="green">System.shop</font>&quot;: Предмет &quot;'.$itm['name'].' (x'.$col.')&quot; [itm:'.$itm['id'].'] был продан в магазин за <B>'.$shpCena.' кр.</B>.',time(),$u->info['city'],'System.shop',0,0);
				}else{
					$u->addDelo(2,$u->info['id'],'&quot;<font color="green">System.shop</font>&quot;: Предмет &quot;'.$itm['name'].' (x'.$col.')&quot; [itm:'.$itm['id'].'] был продан в магазин за <B>'.$shpCena.' кр.</B> (кредиты не переведены).',time(),$u->info['city'],'System.shop',0,0);
					$error = 'Не удалось продать предмет...';
				}
			}else{
				$error = 'Не удалось продать предмет...';
			}
		}else{
			$error = 'Предмет не найден в инвентаре.';
		}
	}
	
	if($re!=''){ echo '<div align="right"><font color="red"><b>'.$re.'</b></font></div>'; } ?>
	<script type="text/javascript">
	function AddCount(name, txt)
	{
		document.getElementById("hint4").innerHTML = '<table border=0 width=100% cellspacing=1 cellpadding=0 bgcolor="#CCC3AA"><tr><td align=center><B>Купить неск. штук</td><td width=20 align=right valign=top style="cursor: pointer" onclick="closehint3();"><BIG><B>x</TD></tr><tr><td colspan=2>'+
		'<form method=post><table border=0 width=100% cellspacing=0 cellpadding=0 bgcolor="#FFF6DD"><tr><INPUT TYPE="hidden" name="set" value="'+name+'"><td colspan=2 align=center><B><I>'+txt+'</td></tr><tr><td width=80% align=right>'+
		'Количество (шт.) <INPUT TYPE="text" NAME="count" id=count size=4></td><td width=20%>&nbsp;<INPUT TYPE="submit" value=" »» ">'+
		'</TD></TR></form></TABLE></td></tr></table>';
		document.getElementById("hint4").style.visibility = 'visible';
		document.getElementById("hint4").style.left = '100px';
		document.getElementById("hint4").style.top = '100px';
		document.getElementById("count").focus();
	}
	function closehint3() {
	document.getElementById('hint4').style.visibility='hidden';
	Hint3Name='';
	}	
	</script>
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
	<tr><td valign="top"><?php
	echo '<b style="color:red">'.$error.'</b>';
	?>
	<br />
	<TABLE width="100%" cellspacing="0" cellpadding="4">
	<TR>
	<form name="F1" method="post">
	<TD valign="top" align="left">
	<!--Магазин-->
	<table width="100%" cellspacing="0" cellpadding="0" bgcolor="#a5a5a5">
	<div id="hint3" style="visibility:hidden"></div>
	<tr>
	<td align="center" height="21">
    <?php	
		/*названия разделов (сверху)*/
		if(!isset($_GET['sale']) && !isset($_GET['gifts']) && isset($_GET['otdel'])) 
		{
			$otdels_small_array = array (1=>'<b>Отдел&nbsp;&quot;Оружие: кастеты,ножи&quot;</b>',2=>'<b>Отдел&nbsp;&quot;Оружие: топоры&quot;</b>',3=>'<b>Отдел&nbsp;&quot;Оружие: дубины,булавы&quot;</b>',4=>'<b>Отдел&nbsp;&quot;Оружие: мечи&quot;</b>',5=>'<b>Отдел&nbsp;&quot;Оружие: магические посохи&quot;</b>',6=>'<b>Отдел&nbsp;&quot;Одежда: сапоги&quot;</b>',7=>'<b>Отдел&nbsp;&quot;Одежда: перчатки&quot;</b>',8=>'<b>Отдел&nbsp;&quot;Одежда: рубахи&quot;</b>',9=>'<b>Отдел&nbsp;&quot;Одежда: легкая броня&quot;</b>',10=>'<b>Отдел&nbsp;&quot;Одежда: тяжелая броня&quot;</b>',11=>'<b>Отдел&nbsp;&quot;Одежда: шлемы&quot;</b>',12=>'<b>Отдел&nbsp;&quot;Одежда: наручи&quot;</b>',13=>'<b>Отдел&nbsp;&quot;Одежда: пояса&quot;</b>',14=>'<b>Отдел&nbsp;&quot;Одежда: поножи&quot;</b>',15=>'<b>Отдел&nbsp;&quot;Щиты&quot;</b>',16=>'<b>Отдел&nbsp;&quot;Ювелирные товары: серьги&quot;</b>',17=>'<b>Отдел&nbsp;&quot;Ювелирные товары: ожерелья&quot;</b>',18=>'<b>Отдел&nbsp;&quot;Ювелирные товары: кольца&quot;</b>',19=>'<b>Отдел&nbsp;&quot;Заклинания: нейтральные&quot;</b>',20=>'<b>Отдел&nbsp;&quot;Заклинания: боевые и защитные&quot;</b>',21=>'<b>Отдел&nbsp;&quot;Амуниция&quot;</b>',22=>'<b>Отдел&nbsp;&quot;Эликсиры&quot;</b>',23=>'<b>Отдел&nbsp;&quot;Подарки&quot;</b>',24=>'<b>Отдел&nbsp;&quot;Подарки: недобрые&quot;</b>',25=>'<b>Отдел&nbsp;&quot;Подарки: упаковка&quot;</b>',26=>'<b>Отдел&nbsp;&quot;Подарки: открытки&quot;</b>',27=>'<b>Отдел&nbsp;&quot;Подарки: фейерверки&quot;</b>');
			if(isset($otdels_small_array[$_GET['otdel']]))
			{
				echo $otdels_small_array[$_GET['otdel']];	
			}
			
		} elseif (isset($_GET['sale']) && $_GET['sale']) 
		{
			echo '
			<B>Отдел&nbsp;&quot;Скупка&quot;</B><br>
			Здесь вы можете продать свои вещи, за жалкие гроши...<br>
			У вас в наличии: 
			';
		} elseif (isset($_GET['gifts'])) 
		{
			echo '
			<B>Отдел&nbsp;&quot;Сделать подарки&quot;</B>';	
		}
	?>
	</tr>
	<tr><td>
	<!--Рюкзак / Прилавок-->
	<table width="100%" CELLSPACING="1" CELLPADDING="1" bgcolor="#a5a5a5">
    <?php
	if(isset($_GET['gifts']))
	{
	?>
    
<tr><td bgcolor="#D5D5D5">
Вы можете сделать подарок дорогому человеку. Ваш подарок будет отображаться в информации о персонаже.
<OL>
<LI>Укажите логин персонажа, которому хотите сделать подарок<BR>
Логин 
<INPUT TYPE=text NAME=to_login value="">
<LI>Цель подарка. Будет отображаться в информации о персонаже (не более 60 символов)<BR>
<INPUT TYPE=text NAME=podarok2 value="" maxlength=60 size=50>
<LI>Напишите текст сопроводительной записки (в информации о персонаже не отображается)<BR>
<TEXTAREA NAME=txt ROWS=6 COLS=80></TEXTAREA>
<LI>Выберите, от чьего имени подарок:<BR>
<INPUT TYPE=radio NAME=from value=0 checked> <B><?=$u->info['login']?></B> [<?=$u->info['level']?>]<BR>
<INPUT TYPE=radio NAME=from value=1 > анонимно<BR> 
<? if($u->info['clan']>0){ ?><INPUT TYPE=radio NAME=from value=2 > от имени клана<BR><? } ?>
<LI>Нажмите кнопку <B>Подарить</B> под предметом, который хотите преподнести в подарок:<BR>
</OL>
<input name="itemgift" id="itemgift" type="hidden" value="0" />
</td></tr>
    <?php
	}
		if(isset($_GET['gifts']))
		{
			$itmAll = $u->genInv(3,'`iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND (`im`.`type` = "28" OR `im`.`type` = "38" OR `im`.`type` = "63" OR `im`.`type` = "64") AND `iu`.`gift` = "" ORDER BY `iu`.`lastUPD`  DESC');
			if($itmAll[0]==0)
			{
				$itmAllSee = '<tr><td align="center" bgcolor="#e2e0e0">У вас нет подходящих предметов</td></tr>';
			}else{
				$itmAllSee = $itmAll[2];
			}
			echo $itmAllSee;
		}elseif(!isset($_GET['sale']))
		{
			//Выводим вещи в магазине для покупки
			$u->shopItems($sid);
		}else{
			//Выводим вещи в инвентаре для продажи
			$itmAll = $u->genInv(2,'`iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" ORDER BY `iu`.`lastUPD` DESC');
			if($itmAll[0]==0)
			{
				$itmAllSee = '<tr><td align="center" bgcolor="#e2e0e0">ПУСТО</td></tr>';
			}else{
				$itmAllSee = $itmAll[2];
			}
			echo $itmAllSee;
		}
	?>
	</TABLE>	 
	</TD></TR>
	</TABLE>
	</TD>
	</FORM>
	</TR>
	</TABLE>	
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
	<td bgcolor="#D3D3D3" nowrap><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=6.180.0.100&rnd=<? echo $code; ?>';" title="<? thisInfRm('6.180.0.100',1); ?>">Центральная Площадь</a></td>
	</tr>
	</table>
	</td>
	</tr>
	</table>
	</td></table>
	</td></table>
	<div><br />
      <div align="right">
      <small>
	  Масса: <?=$u->aves['now']?>/<?=$u->aves['max']?> &nbsp;<br />
	  У вас в наличии: <b style="color:#339900;"><?php echo round($u->info['money'],2); ?> кр.</b> &nbsp;
      </small>
      </div>
	  <br />
	  <?php
	/*кнопочки*/
	if(!isset($_GET['sale']))
	{
	echo '
	<INPUT TYPE="button" value="Продать вещи" onclick="location=\'?otdel='.$_GET['otdel'].'&sale=1\'">&nbsp;
	';
	} else {
	echo '
	<INPUT TYPE="button" value="Купить вещи" onclick="location=\'?otdel='.$_GET['otdel'].'\'">&nbsp;
	';
	}
	?>
    <INPUT TYPE="button" value="Обновить" onclick="location = '<? echo $_SERVER['REQUEST_URI']; ?>';"><BR>
	  </div>
	<div style="background-color:#A5A5A5;padding:1"><center><B>Отделы магазина</B></center></div>
	<div style="line-height:17px;">
	<?php
		/*названия разделов (справа)*/
		$otdels_array = array (1=>'Оружие: кастеты,ножи',2=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;топоры',3=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;дубины,булавы',4=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;мечи',5=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;магические посохи',6=>'Одежда: сапоги',7=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;перчатки',8=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;рубахи',9=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;легкая броня',10=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;тяжелая броня',11=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;шлемы',12=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;наручи',13=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;пояса',14=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;поножи',15=>'Щиты',16=>'Ювелирные товары: серьги',17=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ожерелья',18=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;кольца',19=>'Заклинания: нейтральные',20=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;боевые и защитные',21=>'Амуниция',22=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Эликсиры',23=>'Подарки',24=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;недобрые',25=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;упаковка',26=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;открытки',27=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;фейерверки');
		$i=1;
		while ($i!=-1)
		{
			if(isset($otdels_array[$i]))
			{
				if(isset($_GET['otdel']) && $_GET['otdel']==$i) 
				{
				$color = 'C7C7C7';	
				} else {
				$color = 'e2e0e0';
				}
			echo '
			<A HREF="?otdel='.$i.'"><DIV style="background-color: #'.$color.'">
			'.$otdels_array[$i].'
			</A></DIV>
			';
			} else {
			$i = -2;
			}
			$i++;
		}
		
		if(isset($_GET['gifts'])) 
		{
		$color = 'C7C7C7';	
		} 
		echo '<A HREF="?otdel=32&gifts=1"><DIV style="background-color: #'.$color.'">Сделать подарки</A></DIV>';
	?>
	</div>
	</td>
	</table>
    <br>
	<div id="textgo" style="visibility:hidden;"></div>
<?
}
?>