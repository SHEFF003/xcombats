<?php
if(!defined('GAME'))die();

if($u->room['file']=='commision2'){
	if(isset($u->stats['shopSale'],$_GET['sale'])){
		$bns = 0+$u->stats['shopSale'];
		if($bns!=0){
			if($bns>0){
				$bns = '+'.$bns;
			}
			$shopProcent -= $bns;
			if($shopProcent>99){ $shopProcent = 99; }
			if($shopProcent<1){ $shopProcent = 1; }
			echo '<div style="color:grey;"><b>У Вас действует бонус при продаже: '.$bns.'%</b><br><small>Вы сможете продавать предметы за '.(100-$shopProcent).'% от их стоимости</small></div>';
		}
	}
	
	if(!isset($_GET['otdel'])) $_GET['otdel'] = 1;
	$sid = 1;
	$error = '';
	
	 # Выполнение функции покупки предмета
	if(isset($_GET['buy'])){
		if($u->info['allLock'] > time()) {
			$re = '<div align="left">Вам запрещается пользоваться данным магазином до '.date('d.m.y H:i',$u->info['allLock']).'</div>';
		}elseif($u->info['align'] == 2 || $u->info['haos'] > time()) {
			$re = '<div align="left">Хаосникам запрещается пользоваться данным магазином</div>';
		}elseif($u->newAct($_GET['sd4'])==true){
			$re = $u->buyItemCommison($sid,(int)$_GET['itemid'],(int)$_GET['buy']);
		}else{
			$re = 'Вы уверены что хотите купить этот предмет?';
		}
	}
	
	 /*
	 *Выполнение функции "положить предмет в комисионку"
	 *Или забрать предме из коммисионки.
	 */
	if($u->info['align'] == 2 || $u->info['haos'] > time()) {
		$re = '<div align="left">Хаосникам запрещается пользоваться данным магазином</div>';
	}elseif(isset($_POST['PresTR'])){
		$u->commisonRent(mysql_real_escape_string($_POST['PresTR']),(int)$_POST['iid'],(int)$_POST['summTR']);
	}
	 	 
	if($re!=''){ echo '<div align="right"><font color="red"><b>'.$re.'</b></font></div>'; } ?>
	
	<script type="text/javascript">
	function AddCount(name, txt){
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
	
	.pH3 { COLOR: #8f0000; FONT-FAMILY: Arial; FONT-SIZE: 12pt; FONT-WEIGHT: bold; }
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
		if(!isset($_GET['sale']) && isset($_GET['otdel'])) {
			$otdels_small_array = array (1050=>'<b>Отдел&nbsp;&quot;Прочие предметы&quot;</b>',1=>'<b>Отдел&nbsp;&quot;Оружие: кастеты,ножи&quot;</b>',2=>'<b>Отдел&nbsp;&quot;Оружие: топоры&quot;</b>',3=>'<b>Отдел&nbsp;&quot;Оружие: дубины,булавы&quot;</b>',4=>'<b>Отдел&nbsp;&quot;Оружие: мечи&quot;</b>',5=>'<b>Отдел&nbsp;&quot;Оружие: магические посохи&quot;</b>',6=>'<b>Отдел&nbsp;&quot;Одежда: сапоги&quot;</b>',7=>'<b>Отдел&nbsp;&quot;Одежда: перчатки&quot;</b>',8=>'<b>Отдел&nbsp;&quot;Одежда: рубахи&quot;</b>',9=>'<b>Отдел&nbsp;&quot;Одежда: легкая броня&quot;</b>',10=>'<b>Отдел&nbsp;&quot;Одежда: тяжелая броня&quot;</b>',11=>'<b>Отдел&nbsp;&quot;Одежда: шлемы&quot;</b>',12=>'<b>Отдел&nbsp;&quot;Одежда: наручи&quot;</b>',13=>'<b>Отдел&nbsp;&quot;Одежда: пояса&quot;</b>',14=>'<b>Отдел&nbsp;&quot;Одежда: поножи&quot;</b>',15=>'<b>Отдел&nbsp;&quot;Щиты&quot;</b>',16=>'<b>Отдел&nbsp;&quot;Ювелирные товары: серьги&quot;</b>',17=>'<b>Отдел&nbsp;&quot;Ювелирные товары: ожерелья&quot;</b>',18=>'<b>Отдел&nbsp;&quot;Ювелирные товары: кольца&quot;</b>',19=>'<b>Отдел&nbsp;&quot;Заклинания: нейтральные&quot;</b>',20=>'<b>Отдел&nbsp;&quot;Заклинания: боевые и защитные&quot;</b>',21=>'<b>Отдел&nbsp;&quot;Амуниция&quot;</b>',22=>'<b>Отдел&nbsp;&quot;Эликсиры&quot;</b>',23=>'<b>Отдел&nbsp;&quot;Подарки&quot;</b>',24=>'<b>Отдел&nbsp;&quot;Подарки: недобрые&quot;</b>',25=>'<b>Отдел&nbsp;&quot;Подарки: упаковка&quot;</b>',26=>'<b>Отдел&nbsp;&quot;Подарки: открытки&quot;</b>',27=>'<b>Отдел&nbsp;&quot;Подарки: фейерверки&quot;</b>');
			if(isset($otdels_small_array[$_GET['otdel']])){
				echo $otdels_small_array[$_GET['otdel']];	
			}
		} 
	?>
	</tr>
	<tr><td>
	<!--Рюкзак / Прилавок-->
	<table width="100%" CELLSPACING="1" CELLPADDING="1" bgcolor="#a5a5a5">
	 <?php
	/*
		* Вывод списка вещей продаваемых в комисионке 
		* Вывод вещей которые уже сдали в комок
	*/	 	 	 	 	 
	if(!isset($_GET['toRent'])){
		/*
			* Выводим все вещи продоваемые в комке
			* В режиме предварительного просмотра
		*/
		$u->commisionShop($sid,"preview");
	}elseif($_GET['toRent'] == 1){
		/*
			* Выводим вещи из инвентарая 
			* которые хотим сдать в комок				 	 	 	 	 
		*/ 
		if($u->info['allLock'] < time()) {
			$itmAll = $u->genInv(30,'`iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `iu`.`gift` = "" ORDER BY `lastUPD` DESC');
		} else {
			$itmAll[0] = 0;
		}
		if($itmAll[0]==0){
	 	 $itmAllSee = '<tr><td align="center" bgcolor="#e2e0e0">ПУСТО</td></tr>';
		}else{
			$itmAllSee = $itmAll[2];
		}
		echo $itmAllSee;
	}elseif($_GET['toRent'] == 2){
		/*
			* Выводим вещи которые мы сдали в комок
		*/	 	 	 	 	 
		$itmAll = $u->genInv(31,'`iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="30" AND `iu`.`gift` = "" ORDER BY `lastUPD` DESC');
		if($itmAll[0]==0){
			$itmAllSee = '<tr><td align="center" bgcolor="#e2e0e0">ПУСТО</td></tr>';
		}else{
			$itmAllSee = $itmAll[2];
		}
	 	 echo $itmAllSee;	 	 	 	 	 	 	 	 	 	 
	}elseif($_GET['toRent'] == 3){
		/*
			* Выводим полный перечень вещей 
			* продоваемых в комке по определенному
			* выбранному айтему
		*/
		$u->commisionShop($sid,"full");	 	 	 	 	 	 	 	 	 
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
	<table border="0" cellpadding="0" cellspacing="0">
	<tr align="right" valign="top">
	<td>
	<!-- -->
	<? echo $goLis; ?>
	<!-- -->
	<table border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td nowrap="nowrap">
	<table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#DEDEDE">
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
	<div>
		<br />
		<div align="right">
			<small>
				Масса: <?=$u->aves['now']?>/<?=$u->aves['max']?> &nbsp;<br />
				У вас в наличии: <b style="color:#339900;"><?php echo round($u->info['money'],2); ?> кр.</b> &nbsp;
			</small>
		</div>
		<br />
		<?php
			/*кнопочки*/
			echo '<INPUT TYPE="button" value="Сдать вещи" onclick="location=\'?toRent=1\'">&nbsp; <INPUT TYPE="button" value="Забрать вещи" onclick="location=\'?toRent=2\'">&nbsp;';
		?>
	</div>
	<div style="background-color:#A5A5A5;padding:1"><center><B>Отделы магазина</B></center></div>
	<div style="line-height:17px;">
	<?php
		/*названия разделов (справа)*/
		$otdels_array = array (1=>'Оружие: кастеты,ножи',2=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;топоры',3=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;дубины,булавы',4=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;мечи',5=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;магические посохи',6=>'Одежда: сапоги',7=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;перчатки',8=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;рубахи',9=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;легкая броня',10=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;тяжелая броня',11=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;шлемы',12=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;наручи',13=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;пояса',14=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;поножи',15=>'Щиты',16=>'Ювелирные товары: серьги',17=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ожерелья',18=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;кольца',19=>'Заклинания',20=>'Эликсиры');
		$i=1;
		while ($i!=-1){
			if(isset($otdels_array[$i])){
				if(isset($_GET['otdel']) && $_GET['otdel']==$i) {
					$color = 'C7C7C7';	
				} else {
					$color = 'e2e0e0';
				}
				echo '<A HREF="?otdel='.$i.'"><DIV style="background-color: #'.$color.'">'.$otdels_array[$i].'</A></DIV>';
			} else {
				$i = -2;
			}
			$i++;
		}
		if(isset($_GET['otdel']) && $_GET['otdel']==1050) {
			$color = 'C7C7C7';	
		} else {
			$color = 'e2e0e0';
		}
		echo '<A HREF="?otdel=1050"><DIV style="background-color: #'.$color.'">Прочие предметы</A></DIV>';
	?>
	</div>
</td>
</table>
<br>
<div id="textgo" style="visibility:hidden;"></div>
<? } ?>