<?php
if(!defined('GAME'))
{
	die();
}


if($u->room['file']=='a_hram')
{
	$shopProcent = 50;
	if(date('d',time())==22)
	{
		//$shopProcent = 1;
	}
	
	if(!isset($_GET['otdel']) && !isset($_GET['sale'])) 
	{
		$_GET['sale'] = 1;
	}elseif(isset($_GET['otdel']) && isset($_GET['sale'])){
		$_GET['sale'] = 1;
		unset($_GET['otdel']);
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

	$sid = 14;

	$error = '';
	
	if(isset($_GET['buy']))
	{
		if($u->newAct($_GET['sd4'])==true)
		{
			$re = $u->buyItem($sid,(int)$_GET['buy'],(int)$_GET['x']);
		}else{
			$re = 'Вы уверены что хотите купить этот предмет?';
		}
	}elseif(isset($_GET['sale']) && isset($_GET['sale1kr']))
	{
		$id = (int)$_GET['sale1kr'];
		$itm = mysql_fetch_array(mysql_query('SELECT `im`.*,`iu`.*,`iu`.`id` AS `id_user` FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `iu`.`id` = "'.mysql_real_escape_string($id).'" LIMIT 1'));
		$po = $u->lookStats($itm['data']);
		$po['toclan1'] = explode('#',$po['toclan']);
		$po['toclan1'] = $po['toclan1'][0];
		$see1 = 1;
		if($po['toclan1'] > 0) {
			$see1 = 0;
		}
		if($po['frompisher'] == 0) {
			$see1 = 0;
		}
		if($itm['gift'] > 0) {
			$see1 = 0;
		}	
		if(!isset($po['hprs'])) {
			$po['hprs'] = 0.001;
		}
		if(!isset($po['hprp'])) {
			$po['hprp'] = 0.0001;
		}
		$col = $u->itemsX($itm['id_user']);
		$prs1 = $u->floordec($po['hprs']*$col,2); //кр
		$rps1 = $u->floordec($po['hprp']*$col); //реп.
		if(isset($po['nosale']) || $see1 == 0)
		{
			$error = 'Не удалось продать предмет ...';
		}elseif($pl['type']<29 && ($po['srok'] > 0 || $pl['srok'] > 0))
		{
			$error = 'Не удалось продать предмет ...';
		}elseif(isset($itm['id']))
		{
			$colx = '';
			if($col > 0) {
				$colx = ' (x'.$col.')';
			}
			$error = 'Вы успешно обменяли предмет &quot;'.$itm['name'].''.$colx.'&quot; на '.$prs1.' кр.';
			$u->info['money'] += $prs1;
			
			mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
			mysql_query('UPDATE `items_users` SET `inGroup` = "0",`delete` = "'.time().'" WHERE `inGroup` = "'.$itm['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT '.$itm['group_max'].'');
			$u->addDelo(2,$u->info['id'],'&quot;<font color="green">System.shop</font>&quot;: Предмет &quot;'.$itm['name'].' (x'.$col.')&quot; [itm:'.$itm['id'].'] был продан в магазин за <B>'.$prs1.' кр.</B> (Храм Репутации).',time(),$u->info['city'],'System.shop',0,0);
					
			mysql_query('UPDATE `users` SET `money` = "'.$u->info['money'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
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
		if(isset($_GET['otdel'])) 
		{
			$otdels_small_array = array (1=>'<b>Отдел&nbsp;&quot;Оружие: кастеты,ножи&quot;</b>',2=>'<b>Отдел&nbsp;&quot;Оружие: топоры&quot;</b>',3=>'<b>Отдел&nbsp;&quot;Оружие: дубины,булавы&quot;</b>',4=>'<b>Отдел&nbsp;&quot;Оружие: мечи&quot;</b>',5=>'<b>Отдел&nbsp;&quot;Оружие: магические посохи&quot;</b>',6=>'<b>Отдел&nbsp;&quot;Одежда: сапоги&quot;</b>',7=>'<b>Отдел&nbsp;&quot;Одежда: перчатки&quot;</b>',8=>'<b>Отдел&nbsp;&quot;Одежда: рубахи&quot;</b>',9=>'<b>Отдел&nbsp;&quot;Одежда: легкая броня&quot;</b>',10=>'<b>Отдел&nbsp;&quot;Одежда: тяжелая броня&quot;</b>',11=>'<b>Отдел&nbsp;&quot;Одежда: шлемы&quot;</b>',12=>'<b>Отдел&nbsp;&quot;Одежда: наручи&quot;</b>',13=>'<b>Отдел&nbsp;&quot;Одежда: пояса&quot;</b>',14=>'<b>Отдел&nbsp;&quot;Одежда: поножи&quot;</b>',15=>'<b>Отдел&nbsp;&quot;Щиты&quot;</b>',16=>'<b>Отдел&nbsp;&quot;Ювелирные товары: серьги&quot;</b>',17=>'<b>Отдел&nbsp;&quot;Ювелирные товары: ожерелья&quot;</b>',18=>'<b>Отдел&nbsp;&quot;Ювелирные товары: кольца&quot;</b>',19=>'<b>Отдел&nbsp;&quot;Заклинания: нейтральные&quot;</b>',20=>'<b>Отдел&nbsp;&quot;Заклинания: боевые и защитные&quot;</b>',21=>'<b>Отдел&nbsp;&quot;Амуниция&quot;</b>',22=>'<b>Отдел&nbsp;&quot;Эликсиры&quot;</b>',23=>'<b>Отдел&nbsp;&quot;Подарки&quot;</b>',24=>'<b>Отдел&nbsp;&quot;Подарки: недобрые&quot;</b>',25=>'<b>Отдел&nbsp;&quot;Подарки: упаковка&quot;</b>',26=>'<b>Отдел&nbsp;&quot;Подарки: открытки&quot;</b>',27=>'<b>Отдел&nbsp;&quot;Подарки: фейерверки&quot;</b>');
			if(isset($otdels_small_array[$_GET['otdel']]))
			{
				echo $otdels_small_array[$_GET['otdel']];	
			}
			
		} elseif (isset($_GET['sale']) && $_GET['sale']) 
		{
			echo '
			<B>Отдел&nbsp;&quot;Обмена вещей&quot;</B><br>
			Здесь вы можете обменять свои вещи, за жалкие гроши или репутацию...<br>
			У вас в наличии: 
			';
		}
	?>
	</tr>
	<tr><td>
	<!--Рюкзак / Прилавок-->
	<table width="100%" CELLSPACING="1" CELLPADDING="1" bgcolor="#a5a5a5">
<?
		if(!isset($_GET['sale']))
		{
			//Выводим вещи в магазине для покупки
			$u->shopItems($sid);
		}else{
			//Выводим вещи в инвентаре для продажи
			$itmAll = $u->genInv(69,'`iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" ORDER BY `lastUPD` DESC');
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
	<td bgcolor="#D3D3D3" nowrap><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=1.180.0.9&rnd=<? echo $code; ?>';" title="<? thisInfRm('1.180.0.9',1); ?>">Центральная Площадь</a></td>
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
	<INPUT TYPE="button" value="Обменять вещи" onclick="location=\'?otdel='.$_GET['otdel'].'&sale=1\'">&nbsp;
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
		echo '</DIV>';
	?>
	</div>
	</td>
	</table>
    <br>
	<div id="textgo" style="visibility:hidden;"></div>
<?
}
?>