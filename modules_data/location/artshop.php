<?php
if(!defined('GAME'))
{
	die();
}

if($u->room['file']=='artshop')
{
	if(!isset($_GET['otdel'])) 
	{
		$_GET['otdel'] = 1;
	}
	
	$sid = 777;

	$error = '';
	
	/*if( isset($_GET['restartprice'])) {
		$sp = mysql_query('SELECT `s`.*,`m`.* FROM `items_shop` AS `s` LEFT JOIN `items_main` AS `m` ON `m`.`id` = `s`.`item_id` WHERE `s`.`sid` = "777"');
		
		while($pl = mysql_fetch_array($sp)) {
			$price2 = round(($pl['price2']/2.5),2);
			if( $price2 > 100 ) {
				mysql_query('UPDATE `items_shop` SET `price_2` = "'.$price2.'" WHERE `item_id` = "'.$pl['id'].'" AND `sid` = 777 LIMIT 1');
			}
		}
	}*/
	
	if(isset($_GET['buy']) && isset($u->bank['id']))
	{
		if($u->newAct($_GET['sd4'])==true)
		{
			$re = $u->buyItem($sid,(int)$_GET['buy'],(int)$_GET['x']);
		}else{
			$re = 'Вы уверены что хотите купить этот предмет?';
		}
	}elseif(isset($_GET['buy_vip']) && isset($u->bank['id']) && $u->stats['silver'] > 1)
	{
		if($u->newAct($_GET['sd4'])==true)
		{
			$re = $u->buyItem($sid,(int)$_GET['buy_vip'],(int)$_GET['x'],NULL,true);
		}else{
			$re = 'Вы уверены что хотите купить этот предмет?';
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
    .shop_menu_txt { background-color: #d5d5d5; }
	</style>
	<TABLE width="100%" cellspacing="0" cellpadding="0">
	<tr><td valign="top"><div align="center" class="pH3"><? echo $u->room['name']; ?></div>
	<?php
	echo '<b style="color:red">'.$error.'</b>';
	?>
	<br />
	<TABLE width="100%" cellspacing="0" cellpadding="4">
	<TR>
	<form name="F1" method="post">
	<TD valign="top" align="left">
    <? if(isset($u->bank['id']) && ($u->bank['money2']>0.00 || $u->info['admin']>0)){ ?>
	<!--Магазин-->
	<table width="100%" cellspacing="0" cellpadding="0" bgcolor="#a5a5a5">
	<div id="hint3" style="visibility:hidden"></div>
	<tr>
	<td align="center" height="21">
	<?php 
		/*названия разделов (сверху)*/
		if(!isset($_GET['gifts']) && isset($_GET['otdel'])) 
		{
			$otdels_small_array = array (1=>'<b>Отдел&nbsp;&quot;Оружие: кастеты,ножи&quot;</b>',2=>'<b>Отдел&nbsp;&quot;Оружие: топоры&quot;</b>',3=>'<b>Отдел&nbsp;&quot;Оружие: дубины,булавы&quot;</b>',4=>'<b>Отдел&nbsp;&quot;Оружие: мечи&quot;</b>',5=>'<b>Отдел&nbsp;&quot;Оружие: магические посохи&quot;</b>',6=>'<b>Отдел&nbsp;&quot;Одежда: сапоги&quot;</b>',7=>'<b>Отдел&nbsp;&quot;Одежда: перчатки&quot;</b>',8=>'<b>Отдел&nbsp;&quot;Одежда: рубахи&quot;</b>',28=>'<b>Отдел&nbsp;&quot;Одежда: плащи&quot;</b>',9=>'<b>Отдел&nbsp;&quot;Одежда: легкая броня&quot;</b>',10=>'<b>Отдел&nbsp;&quot;Одежда: тяжелая броня&quot;</b>',11=>'<b>Отдел&nbsp;&quot;Одежда: шлемы&quot;</b>',12=>'<b>Отдел&nbsp;&quot;Одежда: наручи&quot;</b>',13=>'<b>Отдел&nbsp;&quot;Одежда: пояса&quot;</b>',14=>'<b>Отдел&nbsp;&quot;Одежда: поножи&quot;</b>',15=>'<b>Отдел&nbsp;&quot;Щиты&quot;</b>',16=>'<b>Отдел&nbsp;&quot;Ювелирные товары: серьги&quot;</b>',17=>'<b>Отдел&nbsp;&quot;Ювелирные товары: ожерелья&quot;</b>',18=>'<b>Отдел&nbsp;&quot;Ювелирные товары: кольца&quot;</b>',19=>'<b>Отдел&nbsp;&quot;Заклинания: нейтральные&quot;</b>',20=>'<b>Отдел&nbsp;&quot;Заклинания: боевые и защитные&quot;</b>',21=>'<b>Отдел&nbsp;&quot;Амуниция&quot;</b>',22=>'<b>Отдел&nbsp;&quot;Амуниция: эликсиры&quot;</b>',23=>'<b>Отдел&nbsp;&quot;Подарки&quot;</b>',24=>'<b>Отдел&nbsp;&quot;Подарки: недобрые&quot;</b>',25=>'<b>Отдел&nbsp;&quot;Подарки: открытки&quot;</b>',26=>'<b>Отдел&nbsp;&quot;Подарки: упаковка&quot;</b>',27=>'<b>Отдел&nbsp;&quot;Подарки: фейерверки&quot;</b>');
			if(isset($otdels_small_array[$_GET['otdel']]))
			{
				echo $otdels_small_array[$_GET['otdel']];	
			}
			
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
		//Выводим вещи в магазине для покупки
		$u->shopItems($sid);
	?>
	</TABLE>	 
	</TD></TR>
	</TABLE>
    <div align="center">
      <? }else{ ?>
      <div align="center">Магазин является валютным, вы можете войти только имея еврокредиты. Укажите номер вашего счета в банке и пароль к нему.<br />
        <br />
        <?
		if(isset($_POST['bank']) && isset($u->bank['id']))
		{
			echo '<font color="red"><b>Банковский счет пуст, вход в магазин запрещен</b></font>';
		}elseif(isset($_POST['bank']) && !isset($u->bank['id']))
		{
			echo '<font color="red"><b>Неверный пароль от банковского счета.</b></font>';
		}
		?>
        <br /><br />
        <style>
		.w1{position:absolute;z-index:1102;}.1x1 {
	width:1px;
	height:1px;
	display:block;
}.wi1s0{width:5px;height:6px;background:url(http://img.xcombats.com/i/bneitral_03.gif) 1px 0px no-repeat;}.wi1s1{height:6px;background:url(http://img.xcombats.com/i/bneitral_05.gif);}.wi1s2{width:5px;height:6px;background:url(http://img.xcombats.com/i/bneitral_03.gif) -2px 0px no-repeat;}.wi1s3{width:5px;background:url(http://img.xcombats.com/i/bneitral_17.gif) 1px 0px repeat-y;}.wi1s4{width:5px;background:url(http://img.xcombats.com/i/bneitral_19.gif) -1px 0px repeat-y;background-position:right;background-color:#675600;}.wi1s5{width:5px;background:url(http://img.xcombats.com/i/bneitral_19.gif) 0px 0px repeat-y;}.wi1s6{height:6px;background:url(http://img.xcombats.com/i/bneitral_05.gif);}.wi1s7{background:#ddd5bf;}.wi1s8{background:url(http://img.xcombats.com/i/bneitral_05.gif);}.wi1s9{width:5px;height:6px;background:url(http://img.xcombats.com/i/bneitral_19.gif);}.wi1s10{background-color:#b1a993;white-space:nowrap;color:#003388;padding:2px 2px 2px 7px;min-width:140px;}.wi1s10 text{cursor:move;}.wi1s10 img{cursor:pointer;}.wi1s11{}.wi1s12{}
		</style>
        <table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="wi1s0"><img src="http://img.xcombats.com/1x1.gif" height="1"></td>
            <td class="wi1s1"><img src="http://img.xcombats.com/1x1.gif" height="1"></td>
            <td class="wi1s2"><img src="http://img.xcombats.com/1x1.gif" height="1"></td>
          </tr>
          <tr>
            <td class="wi1s3"><img src="http://img.xcombats.com/1x1.gif" height="1"></td>
            <td>            
    		<table width="300" border="0" cellspacing="0" cellpadding="0">
              <tr>
                  <td bgcolor="#B1A996"><div align="center"><strong>Счёт в банке</strong></div></td>
                </tr>
                <tr>
                  <td bgcolor="#DDD5C2" style="padding:5px;"><div align="center"><small>Выберите счёт и введите пароль<br />
                            <select name="bank" id="bank">
                            <?
                            $scet = mysql_query('SELECT `id` FROM `bank` WHERE `block` = "0" AND `uid` = "'.$u->info['id'].'"');
                            while ($num_scet = mysql_fetch_array($scet)) 
                            {
                                 echo "<option>".$u->getNum($num_scet['id'])."</option>";
                            }
                            ?>
                            </select>
                            <input style="margin-left:5px;" type="password" name="bankpsw" id="bankpsw" />
                            <label></label>
                      </small>
                          <input class="btnnew" style="margin-left:3px;" type="submit" name="button" id="button" value=" ok " />
                  </div></td>
                </tr>
            </table>
            
            </td>
            <td class="wi1s4"><img src="http://img.xcombats.com/1x1.gif" height="1"></td>
          </tr>
          <tr>
            <td class="wi1s5"><img src="http://img.xcombats.com/1x1.gif" height="1"></td>
            <td class="wi1s6"><img src="http://img.xcombats.com/1x1.gif" height="1"></td>
            <td class="wi1s8"><img src="http://img.xcombats.com/1x1.gif" height="1"></td>
          </tr>
          </table> 
        <br />
      </div>
      <? } ?>
    </div></TD>
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
	<td bgcolor="#D3D3D3" nowrap><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=1.180.0.13&rnd=<? echo $code; ?>';" title="<? thisInfRm('1.180.0.13',1); ?>">Березка</a></td>
	</tr>
	</table>
	</td>
	</tr>
	</table>
	</td></table>
	</td></table>
	<div><br />
     <? if(isset($u->bank['id'])){ ?>
      <div align="right">
      <small>
	  Масса: <?=$u->aves['now']?>/<?=$u->aves['max']?> &nbsp;<br />
	 	№<? echo $u->getNum($u->bank['id']); ?>: <b><? echo $u->bank['money1']; ?></b>кр. <b><? echo $u->bank['money2']; ?></b>екр. <a href="main.php?bank_exit=<? echo $code; ?>"><img src="http://img.xcombats.com/i/close_bank.gif" style="cursor:pointer;" title="Закрыть работу со счётом"></a></small>
      </small>
      </div>
	  <br /><center>
      <INPUT class="btnnew" TYPE="button" value="Обновить" onclick="location = '<? echo str_replace('exit','bait',$_SERVER['REQUEST_URI']); ?>';"><BR>
    	</center><br>
	<? if(isset($u->bank['id']) && ($u->bank['money2']>0.00 || $u->info['admin']>0)){ ?>
	  </div>
	<div style="background-color:#A5A5A5;padding:1"><center><B>Отделы магазина</B></center></div>
	<div style="line-height:17px;">
	<?php
		/*названия разделов (справа)*/
		$otdels_array = array (
		1=>'&nbsp;&nbsp;Кастеты,ножи',
		2=>'&nbsp;&nbsp;Топоры',
		3=>'&nbsp;&nbsp;Дубины,булавы',
		4=>'&nbsp;&nbsp;Мечи',
		5=>'&nbsp;&nbsp;Магические посохи',
		6=>'&nbsp;&nbsp;Сапоги',
		7=>'&nbsp;&nbsp;Перчатки',
		8=>'&nbsp;&nbsp;Рубахи',
		9=>'&nbsp;&nbsp;Легкая броня',
		10=>'&nbsp;&nbsp;Тяжелая броня',
		11=>'&nbsp;&nbsp;Шлемы',
		12=>'&nbsp;&nbsp;Наручи',
		13=>'&nbsp;&nbsp;Пояса',
		14=>'&nbsp;&nbsp;Поножи',
		15=>'&nbsp;&nbsp;Щиты',16=>'&nbsp;&nbsp;Серьги',
		17=>'&nbsp;&nbsp;Ожерелья',
		18=>'&nbsp;&nbsp;Кольца',
		19=>'&nbsp;&nbsp;Нейтральные',
		20=>'&nbsp;&nbsp;Боевые и защитные',
		21=>'&nbsp;&nbsp;Амуниция',
		22=>'&nbsp;&nbsp;Эликсиры',
		23=>'&nbsp;&nbsp;Подарки',24=>'&nbsp;&nbsp;Недобрые',25=>'&nbsp;&nbsp;Открытки',26=>'&nbsp;&nbsp;Упаковка',27=>'&nbsp;&nbsp;Фейерверки',28=>'&nbsp;&nbsp;Плащи и накидки'/*,29=>'Слоты смены: Постоянные',30=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Временные слоты смены'*/);
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
				if($i == 1) {
			  echo '<div class="shop_menu_txt"><img height="12" width="12" src="i/shop_ico/1.png"> <b>Оружие:</b></div>';
			} elseif($i == 6) {
			  echo '<div class="shop_menu_txt"><img height="12" width="12" src="i/shop_ico/2.png"> <b>Одежда:</b></div>';
			} elseif($i == 15) {
			  echo '<div class="shop_menu_txt"><img height="12" width="12" src="i/shop_ico/3.png"> <b>Щиты:</b></div>';
			} elseif($i == 16) {
			  echo '<div class="shop_menu_txt"><img height="12" width="12" src="i/shop_ico/4.png"> <b>Ювелирные товары:</b></div>';
			} elseif($i == 19) {
			  echo '<div class="shop_menu_txt"><img height="12" width="12" src="i/shop_ico/6.png"> <b>Заклинания:</b></div>';
			} elseif($i == 21) {
			  echo '<div class="shop_menu_txt"><img height="12" width="12" src="i/shop_ico/7.png"> <b>Амуниция:</b></div>';
			} elseif($i == 22) {
			  echo '<div class="shop_menu_txt"><img height="12" width="12" src="i/shop_ico/5.png"> <b>Эликсиры:</b></div>';
			} elseif($i == 23) {
			  echo '<div class="shop_menu_txt"><img height="12" width="12" src="i/shop_ico/8.png"> <b>Подарки:</b></div>';
			} elseif($i == 28) {
			  echo '<div class="shop_menu_txt"><img height="12" width="12" src="i/shop_ico/9.png"> <b>Дополнительно:</b></div>';
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
	}
	?>
	</div>
    <? } ?>
	</td>
	</table>
    <br>
	<div id="textgo" style="visibility:hidden;"></div>
<?
}
?>