<?php
if(!defined('GAME'))die();

if(isset($file) && $file[0]=='nakova2.php'){
    if(isset($_GET['back'])) {
		mysql_query('UPDATE `stats` SET `x` = "3",`y` = "43",`s` = "3" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		header('location: main.php');
	}
	$test = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_actions` WHERE `dn` = "'.$u->info['dnow'].'" AND `vars` = "obj_nakova2_use" LIMIT 1'));
    if(isset($test['id']) && $test['uid'] != $u->info['id']) {
		mysql_query('UPDATE `stats` SET `x` = "3",`y` = "43",`s` = "3" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		header('location: main.php');
		die();
    }elseif(!isset($test['id'])) {
		if( $u->info['sex'] == 0 ) {
			$d->sys_chat('<b>'.$u->info['login'].'</b> воспользовался &quot;Наковальней&quot;, другим данный обьект стал недоступен');
		}else{
			$d->sys_chat('<b>'.$u->info['login'].'</b> воспользовалась &quot;Наковальней&quot;, другим данный обьект стал недоступен');
		}
		mysql_query('INSERT INTO `dungeon_actions` (`dn`,`uid`,`time`,`vars`) VALUES (
			"'.$u->info['dnow'].'","'.$u->info['id'].'","'.time().'","obj_nakova2_use"
		)');
    }
    if(!isset($_GET['otdel'])){
		$_GET['otdel'] = 20;
    }

    $sid = 700;

    $error = '';
    
    if( isset($_GET['buy']) ) {
	if( $u->newAct($_GET['sd4']) == true ){
	    $re = $u->buyItem($sid,(int)$_GET['buy'],(int)$_GET['x'],'sudba='.$u->info['login'].'|frompisher='.$d->info['id2'].'|nosale=1');
	}else{
	    $re = 'Вы уверены что хотите получить этот предмет?';
	}
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
	.pH3 { COLOR: #8f0000;  FONT-FAMILY: Arial;  FONT-SIZE: 12pt;  FONT-WEIGHT: bold; }
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
		<tr>
			<td valign="top">
				<div align="center" class="pH3">Наковальня</div><?php
				echo '<b style="color:red">'.$error.'</b>';
				?><br />
				<TABLE width="100%" cellspacing="0" cellpadding="4">
					<TR>
						<TD valign="top" align="left">
							<form name="F1" method="post">
								<!--Магазин-->
								<div id="hint3" style="visibility:hidden"></div>
								<table width="100%" cellspacing="0" cellpadding="0" bgcolor="#a5a5a5">
									<tr>
										<td align="center" height="21">
<?php	
	/*названия разделов (сверху)*/ 
	$otdels_small_array = array (
	//1 => '<b>Отдел&nbsp;&quot;Разное&quot;</b>', 
	//2 => '<b>Отдел&nbsp;&quot;Оружие: кастеты,ножи&quot;</b>',
	//3 => '<b>Отдел&nbsp;&quot;Оружие: топоры&quot;</b>',
	//4 => '<b>Отдел&nbsp;&quot;Оружие: дубины,булавы&quot;</b>',
	//5 => '<b>Отдел&nbsp;&quot;Оружие: мечи&quot;</b>',
	//6 => '<b>Отдел&nbsp;&quot;Оружие: магические посохи&quot;</b>',
	//
	//7 => '<b>Отдел&nbsp;&nbsp;Ресурсы: пещерные&nbsp;</b>',
	//
	//8 => '<b>Отдел&nbsp;&quot;Одежда: плащи и накидки&quot;</b>',
	//9 => '<b>Отдел&nbsp;&quot;Одежда: сапоги&quot;</b>',
	//10 => '<b>Отдел&nbsp;&quot;Одежда: перчатки&quot;</b>',
	//11 => '<b>Отдел&nbsp;&quot;Одежда: рубахи&quot;</b>',
	//12 => '<b>Отдел&nbsp;&quot;Одежда: легкая броня&quot;</b>',
	//13 => '<b>Отдел&nbsp;&quot;Одежда: тяжелая броня&quot;</b>',
	//14 => '<b>Отдел&nbsp;&quot;Одежда: шлемы&quot;</b>',
	//15 => '<b>Отдел&nbsp;&quot;Одежда: наручи&quot;</b>',
	//16 => '<b>Отдел&nbsp;&quot;Одежда: пояса&quot;</b>',
	//17 => '<b>Отдел&nbsp;&quot;Одежда: поножи&quot;</b>',
	//
	//18 => '<b>Отдел&nbsp;&quot;Щиты&quot;</b>',
	//
	19 => '<b>Отдел&nbsp;&quot;Ювелирные товары: серьги&quot;</b>',
	20 => '<b>Отдел&nbsp;&quot;Ювелирные товары: ожерелья&quot;</b>'
	//21 => '<b>Отдел&nbsp;&quot;Ювелирные товары: кольца&quot;</b>',
	//
	//22 => '<b>Отдел&nbsp;&quot;Заклинания: нейтральные&quot;</b>',
	//23 => '<b>Отдел&nbsp;&quot;Заклинания: боевые и защитные&quot;</b>',
	//24 => '<b>Отдел&nbsp;&quot;Заклинания: пирожки&quot;</b>',
	//25 => '<b>Отдел&nbsp;&quot;Заклинания: исцеляющие&quot;</b>',
	//26 => '<b>Отдел&nbsp;&quot;Заклинания: манящие&quot;</b>',
	//27 => '<b>Отдел&nbsp;&quot;Заклинания: стратегические&quot;</b>',
	//28 => '<b>Отдел&nbsp;&quot;Заклинания: тактические&quot;</b>',
	//29 => '<b>Отдел&nbsp;&quot;Заклинания: сервисные&quot;</b>',
	//
	//30 => '<b>Отдел&nbsp;&quot;Амуниция&quot;</b>',
	//31 => '<b>Отдел&nbsp;&quot;Эликсиры&quot;</b>',
	//32 => '<b>Отдел&nbsp;&quot;Подарки&quot;</b>',
	//33 => '<b>Отдел&nbsp;&quot;Подарки: недобрые&quot;</b>',
	//34 => '<b>Отдел&nbsp;&quot;Подарки: упаковка&quot;</b>',
	//35 => '<b>Отдел&nbsp;&quot;Подарки: открытки&quot;</b>',
	//36 => '<b>Отдел&nbsp;&quot;Подарки: фейерверки&quot;</b>',
	//37 => '<b>Отдел&nbsp;&quot;Усиление оружия: заточки&quot;</b>',
	//38 => '<b>Отдел&nbsp;&quot;Наставничество: образы&quot;</b>'
	
	); 
	if(isset($otdels_small_array[$_GET['otdel']])){
	echo $otdels_small_array[$_GET['otdel']];	
	} 
?>
										</td>
									</tr>
									<tr>
										<td>
											<!--Рюкзак / Прилавок-->
											<table width="100%" CELLSPACING="1" CELLPADDING="1" bgcolor="#a5a5a5"><?php
												//Выводим вещи в магазине для покупки
												$u->shopItems($sid);
											?></TABLE>	 
										</TD>
									</TR>
								</TABLE> 
							</FORM>
						</TD>
					</TR>
				</TABLE>
			</td>
			<td width="280" valign="top">
				<TABLE cellspacing="0" cellpadding="0">
					<tr>
						<TD width="100%">&nbsp;</TD>
						<TD>
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
															<td bgcolor="#D3D3D3" nowrap><a href="#" id="greyText" class="menutop" onclick="location='main.php?back=1&rnd=<?=$code?>';">Вернуться назад</a></td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<div><br />
					<div align="right">
						<small>
							Масса: <?=$u->aves['now']?>/<?=$u->aves['max']?> &nbsp;<br />
							У вас в наличии: <b style="color:#339900;"><?php echo round($u->info['money'],2); ?> кр.</b> &nbsp;
						</small>
					</div>
					<br />
					<INPUT TYPE="button" value="Обновить" onclick="location = '<? echo $_SERVER['REQUEST_URI']; ?>';"><BR>
				</div>
				<div style="background-color:#A5A5A5;padding:1"><center><B>Отделы магазина</B></center></div>
				<div style="line-height:17px;">
<?php
	/*названия разделов (справа)*/
	$otdels_array = array (
	//2=>'Кастеты и ножи',
	//3=>'Топоры',
	//4=>'Дубины и булавы',
	//5=>'Мечи',
	//6=>'Магические посохи',
	//7=>'Ресурсы',
	//8=>'Плащи и накидки '
	//9=>'&nbsp;Сапоги',
	//10=>'Перчатки',
	//11=>'Рубахи',
	//12=>'Легкая броня',
	//13=>'Тяжелая броня',
	//14=>'Шлемы',
	//15=>'Наручи',
	//16=>'Пояса',
	//17=>'Поножи',
	//18=>'Щиты',
	//19=>'Ювелирные товары: серьги',
	20=>'&nbsp;Ожерелья',
	21=>'&nbsp;Кольца'
	
	//22=>'Заклинания',		
	//23=>'боевые и защитные',
	//24=>'пирожки',
	//25=>'исцеляющие',
	//26=>'манящие',
	//27=>'стратегические',
	//28=>'тактические',
	//29=>'сервисные',
	//
	//30=>'Амуниция',
	//31=>'Эликсиры',
	//32=>'Подарки',
	//33=>'недобрые',
	//34=>'упаковка',
	//35=>'открытки',
	//36=>'фейерверки',
	//37=>'Усиление оружия: заточки'
	);
	//$otdels_array = array (1=>'Ювелирные товары: ожерелья',2=>' кольца');
	
	foreach($otdels_array as $key=>$val){
	if(isset($key) && isset($val)){
		if(isset($_GET['otdel']) && $_GET['otdel']==$key) {
		$color = 'C7C7C7';	
		} else {
		$color = 'e2e0e0';
		}
		echo '<A HREF="?otdel='.$key.'"><DIV style="background-color: #'.$color.'">'.$otdels_array[$key].'</DIV></A>';
	} 
	} 
?>
				</div>
			</td>
		</tr>
    </table>
	<br>
    <div id="textgo" style="visibility:hidden;"></div>
<?
}
?>