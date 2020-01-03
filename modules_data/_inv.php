<?php
session_start();
if(!defined('GAME')) {
	die();
}

$u->info['marker'] = 'inv';

if( isset( $_SESSION['otdel'] ) && !isset($_GET['otdel']) && !isset($_GET['paged'])) {
	$_GET['otdel'] = $_SESSION['otdel'];
	$_GET['paged'] = $_SESSION['paged'];
}elseif( !isset( $_GET['otdel'] ) || ( $_GET['otdel']<1 && $_GET['otdel']>6 ) ) {
	$_GET['otdel'] = 1; // Если раздел не указан.
	$_GET['paged'] = $_SESSION['paged'] = 0;
}

if( isset($_GET['otdel']) ) {
	if( !isset($_GET['paged']) && (isset($_GET['use_pid']) || isset($_GET['sid']) || isset($_GET['oid']) || isset($_GET['usecopr']) || isset($_GET['delcop'])) ) {
		$_GET['paged'] = $_SESSION['paged']; // use item and load old paging
	} elseif(isset($_GET['paged']) && $_GET['paged']!='') {
		$_SESSION['paged'] = $_GET['paged']; // Задаем новую страницу.
	} elseif(isset($_SESSION['paged']) && $_SESSION['paged']!='' && $_SESSION['otdel']==$_GET['otdel']) {
		$_GET['paged'] = $_SESSION['paged']; // Если страница уже имеется в сессии, возвращаем её в текущую.
	} else {
		$_GET['paged'] = $_SESSION['paged'] = 0;
	}
}

$_SESSION['otdel'] = $_GET['otdel']; // для отладки.

if( isset($_GET['delcop']) ) {
	mysql_query('DELETE FROM `complects_priem` WHERE `id` = "'.mysql_real_escape_string($_GET['delcop']).'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
} elseif( isset($_GET['usecopr']) ) {
	$cpr = mysql_fetch_array(mysql_query('SELECT * FROM `complects_priem` WHERE `id` = "'.mysql_real_escape_string($_GET['usecopr']).'" AND `uid` = "'.$u->info['id'].'" LIMIT 1'));
	if( isset($cpr['id']) ) {
		$u->info['priems'] = $cpr['priems'];
		mysql_query('UPDATE `stats` SET `priems` = "'.mysql_real_escape_string($cpr['priems']).'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
	}
}

//сохраняем комплект
if( isset($_POST['compname']) ) {
	$_POST['compname'] = htmlspecialchars($_POST['compname'],NULL,'cp1251');
	$_POST['compname'] = str_replace("'",'',$_POST['compname']);
	$_POST['compname'] = str_replace('"','',$_POST['compname']);
	$ptst = str_replace(' ','',$_POST['compname']);
	if( $ptst!='' ) {
		//Добавляем комплект
		$ptst = '';
		$sp = mysql_query('SELECT `inOdet`,`id` FROM `items_users` WHERE `uid` = "'.$u->info['id'].'" AND `delete` = "0" AND `inOdet` > 0 AND `inShop` = "0" ORDER BY `inOdet` ASC LIMIT 250');
		while ( $pl = mysql_fetch_array($sp) ) {
			$ptst .= $pl['inOdet'].'='.$pl['id'].'|';
		}
		$tcm = mysql_fetch_array(mysql_query('SELECT * FROM `save_com` WHERE `uid` = "'.$u->info['id'].'" AND `name` = "'.mysql_real_escape_string($_POST['compname']).'" AND `delete` = "0" LIMIT 1'));
		if( !isset($tcm['id']) ) {
			//добавляем новый комплект
			$ins = mysql_query('INSERT INTO `save_com` (`uid`,`time`,`name`,`val`,`type`) VALUES ("'.$u->info['id'].'","'.time().'","'.mysql_real_escape_string($_POST['compname']).'","'.$ptst.'","0")');
			if($ins) {
				$u->error = 'Комплект &quot;'.$_POST['compname'].'&quot; был успешно сохранен';
			} else {
				$u->error = 'Не удалось сохранить комплект по техническим причинам';	
			}
		}else{
			//изменяем существующий
			$ins = mysql_query('UPDATE `save_com` SET `val` = "'.$ptst.'" WHERE `id` = "'.$tcm['id'].'" LIMIT 1');
			if($ins)
			{
				$u->error = 'Комплект &quot;'.$_POST['compname'].'&quot; был успешно изменен';
			}else{
				$u->error = 'Не удалось изменить комплект по техническим причинам';	
			}	
		}
		unset($ptst,$tcm,$inc);
	}
}elseif(isset($_GET['delc1'])) {
	$cmpl = mysql_query('UPDATE `save_com` SET `delete` = "'.time().'" WHERE `uid` = "'.$u->info['id'].'" AND `delete` = "0" AND `id` = "'.mysql_real_escape_string($_GET['delc1']).'" LIMIT 1');
	if($cmpl)
	{
		$u->error = 'Комплект был успешно удален';		
	}
}
$filt='`iu`.`lastUPD` DESC';
if(isset($_GET['boxsort'])){
	switch($_GET['boxsort']){
		case'name':
			$filt='`im`.`name` ASC';
		break;
		case'cost':
			$filt='`im`.`price2` DESC, `im`.`price1` DESC';
		break;
		case'type':
			$filt='`im`.`inslot`';
		break;
	}
}

$pc = 3000;
$pg = round((int)@$_GET['paged']);
$pxc = $pg*$pc;
$nlim = '';
$pgs = mysql_fetch_array(mysql_query('SELECT COUNT(`iu`.`id`) FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON `im`.`id` = `iu`.`item_id` WHERE `iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `im`.`inRazdel`="'.mysql_real_escape_string($_GET['otdel']).'" ORDER BY '.$filt.' LIMIT 1'));
$pgs = $pgs[0];
$page_look = '';
$inventorySortBox = '<div id="inventorySortBox">
	Сортировка: <br/>
		<input type="button" onclick="inventoryAjax(\'main.php?inv=1&mAjax=true&boxsort=name&otdel=' . intval($_GET['otdel']) . '\');" value="названию" />
		<input type="button" onclick="inventoryAjax(\'main.php?inv=1&mAjax=true&boxsort=cost&otdel=' . intval($_GET['otdel']) . '\');" value="цене" />
		<input type="button" onclick="inventoryAjax(\'main.php?inv=1&mAjax=true&boxsort=type&otdel=' . intval($_GET['otdel']) . '\');" value="типу" />
</div>';

if(isset($_SESSION['paged']))$page_look = '<!-- PAGED SEE '.round((int)@$_SESSION['paged']).'-->'; else $page_look = '<!-- PAGED '.$_SESSION['paged'].' -->';
if($pgs > $pc) {
	$nlim = ' LIMIT '.$pxc.' , '.$pc.'';
	#$page_look .= '<table border=0 cellpadding=0 cellspacing=0 width=100% bgcolor="#A5A5A5"><tr><td width=99% align=center>';
	$page_look .= '<div style="padding:0px;">';
	$page_look .= 'Страницы: ';
	$i = 1;
	echo '<style>.pgdas { display:inline-block;background-color:#dadada; padding:2px 4px 1px 4px; font-size:12px;} .pgdas1 { display:inline-block;background-color:#a5a5a5; padding:2px 4px 1px 4px;  font-size:12px;}
	.pgdas { background: #dadada;background: -moz-linear-gradient(top,  #dadada 50%, #a5a5a5 99%);background: -webkit-gradient(linear, left top, left bottom, color-stop(50%,#dadada), color-stop(99%,#a5a5a5));background: -webkit-linear-gradient(top,  #dadada 50%,#a5a5a5 99%);background: -o-linear-gradient(top,  #dadada 50%,#a5a5a5 99%);background: -ms-linear-gradient(top,  #dadada 50%,#a5a5a5 99%);background: linear-gradient(to bottom,  #dadada 50%,#a5a5a5 99%);filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'#dadada\', endColorstr=\'#a5a5a5\',GradientType=0 );
}
	.pgdas1 { background: #a5a5a5;  }
	</style>';
	while($i <= ceil($pgs/$pc)) {
		if($i-1 == $pg) {
			$sep = 1;
		}else{
			$sep = '';
		}
		$page_look .= '<a class="pgdas'.$sep.'" href="javascript:void(0);" onclick="inventoryAjax(\'main.php?paged='.($i-1).'&inv&mAjax=true&otdel='.round($_GET['otdel']).'\');">'.$i.'</a> ';
			$i++;
	}
	$page_look .= '</div>';
#	$page_look .= '<td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr></table>';
}
$filt='`lastUPD` DESC';
if(isset($_GET['boxsort'])){
	switch($_GET['boxsort']){
		case'name':
			$filt='`name` ASC';
		break;
		case'cost':
			$filt='`price2` DESC, `price1` DESC';
		break;
		case'type':
			$filt='`inslot`';
		break;
	}
}
$itmAll = $itmAllSee = '';
if( isset($_GET['boxsort']) && $_GET['otdel']==5 ) {
	if($_POST['subfilter']) {
		$itmAll = $u->genInv(1,'`iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `name` LIKE "%'.addcslashes(mysql_real_escape_string($_POST['filter']), '%_').'%" ORDER BY `name` ASC');
	}
} else {
	$itmAll = $u->genInv(1,'`iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `im`.`inRazdel`="'.mysql_real_escape_string($_GET['otdel']).'" ORDER BY '.$filt.''.$nlim);
}

$itmAllSee = '<tr><td align="center" bgcolor="#e2e0e0">ПУСТО</td></tr>';
if($itmAll[0] > 0)
	$itmAllSee = $itmAll[2];
	$clrb = '';
	$clrba = '';
	if($u->aves['now'] >= $u->aves['max']) {
		$clrb = 'color:#BB0000;';
		$clrba = ' &nbsp; (У вас перегруз!)';
	}
$showItems = '<table width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top"><table style="" width="100%" cellspacing="0" cellpadding="4" bgcolor="#d4d2d2">
      <tr>
        <td width="20%"  ' . (($_GET['otdel'] != 1) ? 'style=""' : 'style=""') .'  align=center bgcolor="' . (($_GET['otdel'] == 1) ? '#a5a5a5' : '' ) .'"><a href="main.php?inv=1&otdel=1&rn=1.1">Обмундирование</a></td>
        <td width="20%"  ' . (($_GET['otdel'] != 2) ? 'style=""' : 'style=""') .'  align=center bgcolor="' . (($_GET['otdel'] == 2) ? '#a5a5a5' : '' ) .'"><a href="main.php?inv=1&otdel=2&rn=2.1">Заклятия</a></td>
        <td width="20%" ' . (($_GET['otdel'] != 3) ? 'style=""' : 'style=""') .' align=center bgcolor="' . (($_GET['otdel'] == 3) ? '#a5a5a5' : '' ) .'"><a href="main.php?inv=1&otdel=3&rn=3.1">Эликсиры</a></td>
        <td width="20%"  ' . (($_GET['otdel'] != 6) ? 'style=""' : 'style=""') .'  align=center bgcolor="' . (($_GET['otdel'] == 6) ? '#a5a5a5' : '' ) .'"><a href="main.php?inv=1&otdel=6&rn=6.1">Руны</a></td>
        <td width="20%"  ' . (($_GET['otdel'] != 4) ? 'style=""' : 'style="" ') .'  align=center bgcolor="' . (($_GET['otdel'] == 4) ? '#a5a5a5' : '' ) .'"><a href="main.php?inv=1&otdel=4&rn=4.1">Прочее</a></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center" ><table border="0" cellpadding="0" cellspacing="0" width="100%" style="padding-top:0px; border-left: 1px solid #A5A5A5; border-right: 1px solid #A5A5A5;" bgcolor="#a5a5a5">
      <tr>
        <td align="left" valign="middle" style="color:#2b2c2c; height: 18px;font-size:12px; padding:4px;'.$clrb.'">Масса: ' . (0+$u->aves['now']) . ' / ' . $u->aves['max'] . ' '.$clrba.'<!--, предметов: ' . $u->aves['items'] . '--></td>
		<td align="center" valign="middle" style="color:#2b2c2c; font-size:12px">' . $page_look . '</td>
		<td align="right" valign="middle" style="color:#2b2c2c; font-size:12px; position:relative;">
		<form id="line_filter" style="display:inline;" onsubmit="return false;" prc_adsf="true">
			Поиск по имени: <div style="display:inline-block; position:relative; ">
			<input type="text" id="inpFilterName"  placeholder="Введите название предмета..."  autofocus="autofocus" 	size="44" autocomplete="off">
			<img style="position:absolute; cursor:pointer; right: 2px; top: 3px; width: 12px; height: 12px;" onclick="document.getElementById(\'inpFilterName\').value=\'\';" title="Убрать фильтр (клавиша Esc)"  src="http://img.xcombats.com/i/clear.gif">
			<input type="submit" style="display: none" id="inpFilterName_submit" value="Фильтр" onclick="return false">
			<div class="autocomplete-suggestions" style="position: absolute; display: none;top: 15px; left:0px; margin:0px auto; right: 0px; font-size:12px; font-family: Tahoma; max-height: 300px; z-index: 9999;"></div>
			</div>
		</form>
		
			<input type="button" onclick="inventorySort(this);" style="margin:0px 2px;" value="Сортировка" />
			'.$inventorySortBox.'
		</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td valign="top" align="center">
		<div style="height:350px; border-top: 1px solid #A5A5A5;" id="itmAllSee"><table width="100%" border="0" cellspacing="1" align="center" cellpadding="0" bgcolor="#A5A5A5">' . (( $u->info['invBlock'] == 0 ) ? $itmAllSee : '<div align="center" style="padding:10px;background-color:#A5A5A5;"><form method="post" action="main.php?inv=1&otdel='.$_GET['otdel'].'&relockinvent"><b>Рюкзак закрыт.</b><br><img title="Замок для рюкзака" src="http://img.xcombats.com/i/items/box_lock.gif"> Введите пароль: <input id="relockInv" name="relockInv" type="password"><input type="submit" value="Открыть"></form></div>' ) . '</table></div></td>
  </tr>
</table>
<script language="JavaScript">
	if($.cookie(\'invFilterByName\')) $("#ShowInventory").hide();
	$(document).ready(function (){ $("#ShowInventory").show(); });
</script>
';
if(isset($_GET['mAjax'])){
	exit($showItems);
}
?>
<script type="text/javascript" src="js/jquery.1.11.js"></script>
<script type="text/javascript" src="js/jquery.cookie.1.4.1.js"></script>
<script type="text/javascript" src="js/jquery.autocomplete.js"></script>
<script> 
$.cookie('invFilterByName','');
var UpdateItemList;
	function inventorySort(e){
		if ( $('#inventorySortBox').css('display') =='none') {
			$('#inventorySortBox').show();
			$(e).addClass('focus');
		} else {
			$('#inventorySortBox').hide();
			$(e).removeClass('focus');
		} 
	}
	function inventoryHeight() {
		var height = $('#itmAll').height();
		var heW = $(window).height();
		heW = heW-148; // 1060
		height = height-120; // 462
		var heMax = $("#itmAllSee").children('table').height();
		if (heMax > height) {
			if (heW > height) {
				$("#itmAllSee").height(heW);	
			} else {
				$("#itmAllSee").height(height);
			}
		} else {
			$("#itmAllSee").height(heMax);
		}
	}
	$(window).ready(function(){
		inventoryHeight();
	});
	$(window).resize(function(){
		inventoryHeight();
	});
	
	function seetext(id) {
		var id = document.getElementById('close_text_itm'+id);
		if(id.style.display == 'none') {
			id.style.display = '';
		}else{
			id.style.display = 'none';
		}
	}
	
	function UpdateItemList(){
		var inv_names = [];
		var items = $('a.inv_name');
		$(items).each(function(){ if($.inArray($(this).text(), inv_names)<0) inv_names.push($(this).text()); });
		$('#inpFilterName').autocomplete({ lookup:inv_names, onSelect: invFilterByName });
	}
	function invFilterByName(){
		$.cookie('invFilterByName', ''); 
		var val = $('#inpFilterName').val();
		if (val == '') $("a.inv_name").parent().parent().stop().show();
		else {
			$.cookie('invFilterByName', val);
			$("a.inv_name:not(:contains('" + val + "'))").parents('.item').stop().css('background-color', '').hide();
			$("a.inv_name:contains('" + val + "')").parents('.item').stop().show(); 
		}
	}
	
	function inventoryAjax(url){
		$('#ShowInventory').html('<div align="center" style="padding:10px;background-color:#d4d2d2;color:grey;"><b>Загрузка...</b></div>');
		$.ajax({
			url: url,
			cache: false,
			dataType: 'html',
			success: function (html) {
				$('#ShowInventory').html(html);
				
				inventoryHeight();
				
				UpdateItemList();
			}
		});
	}
	
$(document).ready(function () {
	
	function UpdateItemList(){
		var inv_names = [];
		var items = $('a.inv_name');
		$(items).each(function(){ if($.inArray($(this).text(), inv_names)<0) inv_names.push($(this).text()); });
		$('#inpFilterName').autocomplete({ lookup:inv_names, onSelect: invFilterByName });
	}
	
	function invFilterByName(){
		$.cookie('invFilterByName', ''); 
		var val = $('#inpFilterName').val();
		if (val == '') $("a.inv_name").parent().parent().stop().show();
		else {
			$.cookie('invFilterByName', val);
			$("a.inv_name:not(:contains('" + val + "'))").parents('.item').stop().css('background-color', '').hide();
			$("a.inv_name:contains('" + val + "')").parents('.item').stop().show(); 
		}
	}
	
	UpdateItemList(); // пересчет предметов.
	invFilterByNameTimer=null;
	
	// просматриваем результат
	$('#line_filter').submit(function (){ $('#inpFilterName_submit').trigger('click'); });
	
	// Если в выпадающем списке предметов листаем при помощи клавиш Up и Down, автоматически просматриваем результат.
	$('#inpFilterName').keyup(function (e){ $('#inpFilterName_submit').trigger('click'); });
	
	// Запоминаем прошлый поиск предмета и активируем его при открытии инвентаря\сундука
	if ($.cookie('invFilterByName')) { $('#inpFilterName').val($.cookie('invFilterByName')); invFilterByName(); }
	
	// Автообновление в реальном времени при написании текста.
	$('#line_filter').click(function (){ window.clearInterval(invFilterByNameTimer); if($('#inpFilterName').val()=='')invFilterByName(); else invFilterByNameTimer=setTimeout(invFilterByName, 200); return false;} );
	
	/*
	
	var inv_names = [];
	$('a.inv_name').each(function(){ if($.inArray($(this).text(), inv_names)<0) inv_names.push($(this).text()); });
	$('#inpFilterName').autocomplete({lookup:inv_names,onSelect: invFilterByName});
	$('#inpFilterName').focus();
	$(document).keyup(function (e) {if (e.which == 13)invFilterByName(); if (e.which == 27) { $('#textSearch').click(); } });
	$('#line_filter').submit(function (){$('#inpFilterName_submit').trigger('click');});
	function invFilterByName(){
		$.cookie('invFilterByName', ''); 
		var val = $('#inpFilterName').val();
		if (val == '') $("a.inv_name").parent().parent().stop().show();
		else {
			$.cookie('invFilterByName', val);
			$("a.inv_name:not(:contains('" + val + "'))").parents('.item').stop().css('background-color', '').hide();
			$("a.inv_name:contains('" + val + "')").parents('.item').stop().show(); 
		}
	}
	invFilterByNameTimer=null;
	$('#line_filter').click(function (){window.clearInterval(invFilterByNameTimer);if($('#inpFilterName').val()=='')invFilterByName();else invFilterByNameTimer=setTimeout(invFilterByName, 200);return false;});
	$('#inpFilterName').keyup(function (e){ $('#inpFilterName_submit').trigger('click'); });
	if ($.cookie('invFilterByName')) {$('#inpFilterName').val($.cookie('invFilterByName'));invFilterByName();}
	if ($.cookie('invFilterByName')) {$('#inpFilterName').val($.cookie('invFilterByName'));invFilterByName();}
	*/
});

jQuery.expr[":"].contains = function (elem, i, match, array){
	return (elem.textContent || elem.innerText || jQuery.text(elem) || "").toLowerCase().indexOf(match[3].toLowerCase()) >= 0;
}
 
</script> 
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="250" valign="top" align="right">
        <div style="padding-top: 6px;" align="center"><? $usee = $u->getInfoPers($u->info['id'],0,0,1); if($usee!=false){ echo $usee[0]; }else{ echo 'information is lost.'; } 
		//if($u->info['level']>1 && $u->info['inTurnir'] == 0) { 
			include('_incl_data/class/_cron_.php');
			echo '<br>';
			$priem->seeMy(1);
		//}
		//if( $u->info['inTurnir'] > 0 ) {
		//	echo '<center><a href="/main.php?inv&remitem&otdel='.round((int)$_GET['otdel']).'">Снять все</a></center>';
		//}
		echo '<br>'.$u->info_remont();
		//if( $u->info['inTurnir'] == 0 ) {
			/*$bns = mysql_fetch_array(mysql_query('SELECT `id`,`time` FROM `aaa_bonus` WHERE `uid` = "'.$u->info['id'].'" AND `time` > '.time().' LIMIT 1'));
			if(isset($bns['id'])) {
				$bns2 = 'через '.$u->timeOut($bns['time']-time());
				$bns1 = '0';
				$bns3 = '';
			}else{
				$bns2 = '';
				$bns1 = '';
				$bns3 = ' onclick="location.href=\'main.php?inv=1&takebns='.$u->info['nextAct'].'\'"';
			}
			if(isset($_GET['takebns']) && $u->newAct($_GET['takebns'])==true && !isset($bns['id'])) {
				$u->takeBonus();
				$bns2 = '<div style="width:112px" align="center">через '.$u->timeOut( 2 * 3600 ).'</div>';
				$bns1 = '0';
				$bns3 = '';
			}
			?>
			<div align="center">
			<div class="on2gb"<?=$bns3?>>
			<div class="on1gb<?=$bns1?>">
				<small class="on1gbt<?=$bns1?>"><?=$bns2?></small>
			</div>
			</div>
			</div>
			<?*/
		//}
		?>
	    </div> 
			<div align="center"><?php echo $c['counters']; ?></div> 
		</td>
	    <td width="242" valign="top" align="left"><? include('stats_inv.php'); ?></td>    
		<td valign="top"  id="itmAll">
			<div style="z-index: 2; position: relative; width:100%; display:table; box-sizing: border-box; margin: 0px; padding: 0px 5px 3px 5px;">
				<div style="display:table-cell;"><!-- Кнопки возврата и другие--></div>
				<div style="display:table-cell; text-align: right;">
					<?
					if ($u->info['admin'] > 0) { ?><input class="btnnew2" type="button" onclick="location.href='main.php?worklist'" style="color:red" value="Рабочий план" /> &nbsp; <? }
					if($u->info['animal'] != 0) {
						echo '<input class="btnnew" type="button" onclick="top.frames[\'main\'].location=\'main.php?pet=1&rnd='.$code.'\'" value="Зверь" />';
					}
					?><input class="btnnew" type="button" onclick="top.frames['main'].location='main.php?skills&amp;side=1&amp;rn=<? echo $code; ?>'"  value="Умения" /><?
					if ($u->info['inTurnir'] == 0) { ?><input class="btnnew" type="button" onclick="top.frames['main'].location='main.php?obraz&rnd=<? echo $code; ?>'" value="Образ" /><? }
					$gl = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `reimage` WHERE ((`uid` = "'.$u->info['id'].'" AND `clan` = "0") OR (`clan` = "'.$u->info['clan'].'" AND `clan` > 0)) AND `good` > 0 AND `bad` = "0" LIMIT 1'));
					if($gl[0] > 0) { ?><input class="btnnew" type="button" onclick="top.frames['main'].location='main.php?galery&rnd=<? echo $code; ?>'" value="Галерея" /><? } unset($gl); 
					if ($u->info['inTurnir'] == 0) { ?><input class="btnnew2" type="button" onclick="location.href='main.php?referals'" value="Заработок" /><? }
					?><input class="btnnew" type="button" onclick="top.frames['main'].location='main.php'" value="Вернуться" />
					<!--
					<input class="btnnew" type="button" onclick="top.frames['main'].location='main.php?anketa&amp;rn=<? echo $code; ?>'" value="Анкета" />
					<input class="btnnew" type="button" onclick="top.frames['main'].location='main.php?act_trf=1&amp;rn=<? echo $code; ?>'" value="Отчет о переводах" />
					<input class="btnnew" type="button" style="font-weight:bold;" value="Безопасность" onclick="top.frames['main'].location='main.php?security&amp;rn=<? echo $code; ?>'" />
					<input class="btnnew" type="button" style="background-color:#A9AFC0" onClick="alert('Раздел отсутствует');" value="Подсказки" />
					-->
				</div>
			</div>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" noresize="noresize">
				<? if( $u->error != '' )  { ?>
				<tr>
					<td>
						<div style="min-height:18px;padding:2px 4px;"><font color="#FF0000"><b><? echo $u->error; ?></b></font></div>
					</td>
				</tr>
				<? } ?> 
				<tr>
					<td id="ShowInventory"><?php echo $showItems; ?></td>
				</tr>
			</table>
		</td>
	</tr>
</table>