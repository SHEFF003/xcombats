<?php
if(!defined('GAME'))
{
	die();
}
session_start();
$zv = array(1=>'light',2=>'admin',3=>'dark');
if($u->info['clan']>0){
$res = mysql_fetch_array(mysql_query("SELECT * FROM `clan` WHERE `id` = '".mysql_real_escape_string($u->info['clan'])."' LIMIT 1"));
$clan = $res['name'];
}else{
$clan = "";
}
if($_POST['enter'] && $_POST['pass']) {
	$data = mysql_fetch_array(mysql_query("SELECT `id` FROM `moder` WHERE `align` = '".mysql_real_escape_string($u->info['align'])."' AND `trPass` = '".md5($_POST['pass'])."' LIMIT 1;"));
		if($data){
			$_SESSION['moder'] = md5(md5($u->info['id']));
		}else{
			echo'Ошибка входа.';
		}
}
if($u->info['admin']>0) {
	$atp = 'Приветствую тебя ангел';
}
if($u->info['align']=='0.99'){
	if ($u->info['sex'] == 0) {
		$atp = 'Мироздатель с нами, собрат';
	}else{
		$atp = 'Мироздатель с нами, сестра';
	}
}
if($u->info['align']>1 && $u->info['align']<2){
	if($u->info['sex'] == 0) {
		$atp = 'Да пребудет с тобой сила, брат';
	}else{
		$atp = 'Да пребудет с тобой сила, сестра';
	}
}
if ($u->info['align'] == '3') {
	if ($u->info['sex'] == 0) {
		$atp = 'Мусорщик с нами, собрат';
	}else{
		$atp = 'Мусорщик с нами, сестра';
	}
}
if($u->info['align']>3 && $u->info['align']<4){
	if($u->info['sex'] == 0) {
		$atp = 'Да пребудет с тобой сила, брат';
	}else{
		$atp = 'Да пребудет с тобой сила, сестра';
	}
}
$p = mysql_fetch_array(mysql_query('SELECT * FROM `moder` WHERE `align` = "'.$u->info['align'].'" LIMIT 1'));
$zv = array(1=>'light',2=>'admin',3=>'dark');
	$a = floor($p['align']);
	if($u->info['admin']>0)
	{
		$zv = $zv[2];
	}else{
		$zv = $zv[$a];
	}

?>
<SCRIPT src='http://<?=$c['img'];?>/js/commoninf.js'></SCRIPT>
<SCRIPT LANGUAGE="JavaScript" SRC="/js/sl2.22.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript1.2" SRC="http://<?=$c['img'];?>/js/keypad.js"></SCRIPT>
<style>
.modpow {
	background-color:#ddd5bf;
}
.mt {
	background-color:#b1a993;
	padding-left:10px;
	padding-right:10px;
	padding-top:5px;
	padding-bottom:5px;
}
.md {
	padding:10px;
}
</style>
<script>
function openMod(title,dat)
{
	var d = document.getElementById('useMagic');
	if(d!=undefined)
	{
		document.getElementById('modtitle').innerHTML = '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td valign="top">'+title+'</td><td width="30" valign="top"><div align="right"><a title="Закрыть окно" onClick="closeMod(); return false;" href="#">x</a></div></td></tr></table>';
		document.getElementById('moddata').innerHTML = dat;
		d.style.display = '';
	}
}

function closeMod()
{
	var d = document.getElementById('useMagic');
	if(d!=undefined)
	{
		document.getElementById('modtitle').innerHTML = '';
		document.getElementById('moddata').innerHTML = '';
		d.style.display = 'none';
	}
}
</script>

<TABLE width=100%>
<tr>
<TD align=center><h3><?=$atp;?> <SCRIPT>drwfl("<?=$u->info['login']?>",<?=$u->info['id']?>,"<?=$u->info['level']?>",<?=$u->info['align']?>,"<?=$clan?>")</SCRIPT> !</h3>
<TD width=100 align=right><INPUT style="width=30;" TYPE=button value="&rarr;" onclick="location='main.php?';">
</tr>
<tr>
<table>
<tr><td>
<?	//показываем панель модератора
	$go = 0;
if(isset($_GET['go'])){$go = round($_GET['go']);}
if($go==2 && $u->info['admin']>0){
	include('moder/new/editor.php');
}
if($go==1 && $p['editAlign']==1){
	include('moder/new/editalign.php');
}
?>
<div id="useMagic" style="display:none; position:absolute; border:solid 1px #776f59; left: 50px; top: 186px;" class="modpow">
<div class="mt" id="modtitle"></div><div class="md" id="moddata"></div></div>
<?if($go==0){?>
<?if($u->info['align']>=0.99 && $u->info['align']<2 || $u->info['admin']>0){?>
<a href="#" onClick="openMod('<b>&quot;Исцеление&quot;</b>','<form action=\'main.php?<?=$zv?>=1&usemod=<? echo $code; ?>\' method=\'post\'>Логин: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br> <input style=\'float:right;\' type=\'submit\' name=\'usevampir\' value=\'Исп-ть\'></form>');"><img src="http://<?=$c['img'];?>/i/items/invoke_spellcure.gif" title="Исцеление" /></a>
<a href="#" onClick="openMod('<b>&quot;Рассеять Тьму&quot;</b>','<form action=\'main.php?<?=$zv?>=1&usemod=<? echo $code; ?>\' method=\'post\'>Логин: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br> <input style=\'float:right;\' type=\'submit\' name=\'usevampir\' value=\'Исп-ть\'></form>');"><img src="http://<?=$c['img'];?>/i/items/ATTACK.GIF" title="Рассеять Тьму" /></a>
<?}?>
<?if($u->info['align']>=3 && $u->info['align']<4 || $u->info['admin']>0){?>
<a href="#" onClick="openMod('<b>&quot;Вампиризм&quot;</b>','<form action=\'main.php?<?=$zv?>=1&usemod=<? echo $code; ?>\' method=\'post\'>Логин: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br> <input style=\'float:right;\' type=\'submit\' name=\'usevampir\' value=\'Исп-ть\'></form>');"><img src="http://<?=$c['img'];?>/i/items/vampir.gif" title="Вампиризм" /></a>
<a href="#" onClick="openMod('<b>&quot;Помочь Темному Собрату&quot;</b>','<form action=\'main.php?<?=$zv?>=1&usemod=<? echo $code; ?>\' method=\'post\'>Логин: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br> <input style=\'float:right;\' type=\'submit\' name=\'usevampir\' value=\'Исп-ть\'></form>');"><img src="http://<?=$c['img'];?>/i/items/ATTACK.GIF" title="Помочь темному собрату" /></a>
<?}?>
</td></tr></table>
<?}if(!$_SESSION['moder'] && $p['trPass']!=''){
?>
<TABLE align=center><TR><FORM action="main.php?<?=$zv?>" name="F2" method=POST><TD>
<FIELDSET><LEGEND><B><font color=blue>Проверка пароля</font></B> </LEGEND>
<TABLE>
<TR><TD valign=top>
<TABLE>
<TR><TD>Пароль</td><td> <INPUT style='width:90;' type=password value="" name=pass></td><TD style='padding: 0, 0, 3, 5'><img border=0 SRC="http://img.combats.com/i/misc/klav_transparent.gif" style='cursor: hand' onClick="KeypadShow(1, 'F2', 'pass', 'keypad2');"></TD></tr>
<TR><TD colspan=3 align=center><INPUT TYPE=submit value="Войти" name=enter></td></tr>
</TABLE>
</TD>
<TD><div id="keypad2" align=center style="display: none;"></div></TD></TR>
</TABLE>
</FIELDSET>
</TD></TR></TABLE></FORM>
<?
}else{
														/*подключаем скрипты к абилкам ;)*/
$uer = '';
	if(isset($_GET['usemod'])){
	$srok = array(5=>'5 минут',15=>'15 минут',30=>'30 минут',60=>'один час',180=>'три часа',360=>'шесть часов',720=>'двенадцать часов',1440=>'одни сутки',4320=>'трое суток');
	$srokt = array(1=>'1 день',3=>'3 дня',7=>'неделю',14=>'2 недели',30=>'месяц',60=>'2 месяца',365=>'год',24=>'бессрочно',6=>'часик');
	if(isset($_POST['usem1'])){include('moder/usem1.php');}
	elseif(isset($_POST['usem2'])){include('moder/usem2.php');}
	elseif(isset($_POST['usesm'])){include('moder/usesm.php');}
	elseif(isset($_POST['useban'])){include('moder/useban.php');}
	elseif(isset($_POST['useunban'])){include('moder/useunban.php');}
	elseif(isset($_POST['usehaos'])){include('moder/usehaos.php');}
	elseif(isset($_POST['useshaos'])){include('moder/useshaos.php');}
	
	}
														/*подключаем скрипты к абилкам ;)*/
	if($go==0) {
		if($u->info['admin']>0 || ($u->info['align']>1 && $u->info['align']<2) || ($u->info['align']>3 && $u->info['align']<4)){?>
			<h4>Наложить/Снять заклятия</h4>
			<table width="100%">
				<tr>
					<td>
						<? if($u->info['admin']>0){ echo '<a href="main.php?'.$zv.'&go=2"><img width="40" height="25" title="Редактировать квесты, задания и обучающие программы" src="http://img.xcombats.com/editor2.gif"></a>'; } ?>
						<? if($p['editAlign']==1){ echo '<a href="main.php?'.$zv.'&go=1"><img title="Редактировать возможности подчиненных" src="http://img.xcombats.com/editor.gif"></a>'; } ?>
						<? if($p['m1']==1 || $p['citym1']==1){ ?> <a href="#" onClick="openMod('<b>Заклятие молчания</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>Логин персонажа: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br>Время заклятия: &nbsp; <select style=\'margin-left:2px;\' name=\'time\'><option value=\'5\'>5 минут</option><option value=\'15\'>15 минут</option><option value=\'30\'>30 минут</option><option value=\'60\'>1 час</option><option value=\'180\'>3 часа</option><option value=\'360\'>6 часов</option><option value=\'720\'>12 часов</option><option value=\'1440\'>Сутки</option></select> <input type=\'submit\' name=\'usem1\' value=\'Исп-ть\'></form>');"><img src="http://<?=$c['img'];?>/i/items/sleep.gif" title="Заклятие молчания" /></a> <? } ?>
						<? if($p['m2']==1 || $p['citym2']==1){ ?> <a href="#" onClick="openMod('<b>Заклятие форумного молчания</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>Логин персонажа: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br>Время заклятия: &nbsp; <select style=\'margin-left:2px;\' name=\'time\'><option value=\'30\'>30 минут</option><option value=\'60\'>1 час</option><option value=\'180\'>3 часа</option><option value=\'360\'>6 часов</option><option value=\'720\'>12 часов</option><option value=\'1440\'>Сутки</option></select> <input type=\'submit\' name=\'usem2\' value=\'Исп-ть\'></form>');"><img src="http://<?=$c['img'];?>/i/items/sleepf.gif" title="Заклятие форумного молчания" /></a> <? } ?>
						<? if($p['sm1']==1 || $p['sm2']==1 || $p['citysm1']==1 || $p['citysm2']==1){ ?><a href="#" onClick="openMod('<b>Заклятие форумного молчания</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>Логин персонажа: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br>Снять заклятие: &nbsp; <select style=\'margin-left:2px;\' name=\'time\'><option value=\'1\'>чат</option><option value=\'2\'>форум</option><option value=\'3\'>чат + форум</option></select> <input type=\'submit\' name=\'usesm\' value=\'Исп-ть\'></form>');"><img src="http://<?=$c['img'];?>/i/items/sleep_off.gif" title="Снять заклятие молчания" /></a> <? } ?>
						<? if($p['banned']==1 || $p['ban0']==1){ ?> <a href="#" onClick="openMod('<b>Заклятие смерти</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>Логин персонажа: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br> <input style=\'float:right;\' type=\'submit\' name=\'useban\' value=\'Исп-ть\'></form>');"><img src="http://<?=$c['img'];?>/i/items/pal_button6.gif" title="Заклятье смерти" /></a> <? } ?>
						<? if($p['unbanned']==1){ ?> <a href="#" onClick="openMod('<b>Снять заклятие смерти</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>Логин персонажа: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br> <input style=\'float:right;\' type=\'submit\' name=\'useunban\' value=\'Исп-ть\'></form>');"><img src="http://<?=$c['img'];?>/i/items/pal_button7.gif" title="Снять заклятье смерти" /></a> <? } ?>
						<? if($p['haos']==1){ ?> <a href="#" onClick="openMod('<b>Отправить в хаос</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>Логин персонажа: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br>Время заклятия: &nbsp; <select style=\'margin-left:2px;\' name=\'time\'><option value=\'7\'>Неделя</option><option value=\'14\'>2 недели</option><option value=\'30\'>Месяц</option><option value=\'60\'>2 месяца</option><? if($p['haosInf']==1){ ?><option value=\'1\'>Бессрочно</option><? } ?> <input type=\'submit\' name=\'usehaos\' value=\'Исп-ть\'></form>');"><img src="http://<?=$c['img'];?>/i/items/pal_button4.gif" title="Отправить в хаос" /></a> <? } ?>
						<? if($p['shaos']==1){ ?> <a href="#" onClick="openMod('<b>Выпустить из хаоса</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>Логин персонажа: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br> <input style=\'float:right;\' type=\'submit\' name=\'useshaos\' value=\'Исп-ть\'></form>');"><img src="http://<?=$c['img'];?>/i/items/pal_button5.gif" title="Выпустить из хаоса" /></a> <? } ?>
						<? if($p['deletInfo']==1){ ?> <a href="#" onClick="openMod('<b>Обезличивание</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>Логин персонажа: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br>Время заклятия: &nbsp; <select style=\'margin-left:2px;\' name=\'time\'><option value=\'7\'>Неделя</option><option value=\'14\'>2 недели</option><option value=\'30\'>Месяц</option><option value=\'60\'>2 месяца</option><option value=\'1\'>Бессрочно</option> <input type=\'submit\' name=\'usedeletinfo\' value=\'Исп-ть\'></form>');"><img src="http://<?=$c['img'];?>/i/items/cui.gif" title="Обезличивание" /></a>
                        <a href="#" onClick="openMod('<b>Снять заклятие обезличивания</b>','<form action=\'main.php?<? echo $zv.'&usemod='.$code; ?>\' method=\'post\'>Логин персонажа: <input type=\'text\' style=\'width:144px;\' id=\'logingo\' name=\'logingo\'><br> <input style=\'float:right;\' type=\'submit\' name=\'unusedeletinfo\' value=\'Исп-ть\'></form>');"><img src="http://<?=$c['img'];?>/i/items/uncui.gif" title="Снять обезличивание" /></a> <? } ?>
       
						
						
						
						
					</td>
				</tr>
			</table>
<?
		}
	}
}?>


