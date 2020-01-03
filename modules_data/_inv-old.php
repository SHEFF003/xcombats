<?php

session_start();
if(!defined('GAME'))
{
	die();
}



if(!isset($_GET['otdel']) || ($_GET['otdel']!=1 && $_GET['otdel']!=2 && $_GET['otdel']!=3 && $_GET['otdel']!=4 && $_GET['otdel']!=5 && $_GET['otdel']!=6))
{
	$_GET['otdel'] = 1; // Если раздел не указан.
	$_GET['paged'] = $_SESSION['paged'] = 0;
}

if(isset($_GET['otdel'])){
	if(!isset($_GET['paged']) && (isset($_GET['use_pid']) || isset($_GET['sid']) || isset($_GET['oid']) || isset($_GET['usecopr']) || isset($_GET['delcop']))){
		$_GET['paged'] = $_SESSION['paged']; // use item and load old paging 
	}
	elseif(isset($_GET['paged']) && $_GET['paged']!='')
	{ 
		$_SESSION['paged'] = $_GET['paged']; // Задаем новую страницу. 
	}
	elseif(isset($_SESSION['paged']) && $_SESSION['paged']!='' && $_SESSION['otdel']==$_GET['otdel'])
	{
		$_GET['paged'] = $_SESSION['paged']; // Если страница уже имеется в сессии, возвращаем её в текущую. 
	}
	else
	{
		$_GET['paged'] = $_SESSION['paged'] = 0; 
	}
}

$_SESSION['otdel'] = $_GET['otdel']; // для отладки.

if(isset($_GET['delcop'])) {
	mysql_query('DELETE FROM `complects_priem` WHERE `id` = "'.mysql_real_escape_string($_GET['delcop']).'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
}elseif(isset($_GET['usecopr'])) {
	$cpr = mysql_fetch_array(mysql_query('SELECT * FROM `complects_priem` WHERE `id` = "'.mysql_real_escape_string($_GET['usecopr']).'" AND `uid` = "'.$u->info['id'].'" LIMIT 1'));
	if(isset($cpr['id'])) {
		$u->info['priems'] = $cpr['priems'];
		mysql_query('UPDATE `stats` SET `priems` = "'.mysql_real_escape_string($cpr['priems']).'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
	}
}

/*
$u->add_buf('',0);
echo time();
$u->add_buf('',1,$u->info['id'],'inv');
*/

//сохраняем комплект
if(isset($_POST['compname']))
{
	$_POST['compname'] = htmlspecialchars($_POST['compname'],NULL,'cp1251');
	$_POST['compname'] = str_replace("'",'',$_POST['compname']);
	$_POST['compname'] = str_replace('"','',$_POST['compname']);
	$ptst = str_replace(' ','',$_POST['compname']);
	if($ptst!='')
	{
		//Добавляем комплект
		$ptst = '';
		$sp = mysql_query('SELECT `inOdet`,`id` FROM `items_users` WHERE `uid` = "'.$u->info['id'].'" AND `delete` = "0" AND `inOdet` > 0 AND `inShop` = "0" ORDER BY `inOdet` ASC LIMIT 250');
		while($pl = mysql_fetch_array($sp))
		{
			$ptst .= $pl['inOdet'].'='.$pl['id'].'|';
		}
		$tcm = mysql_fetch_array(mysql_query('SELECT * FROM `save_com` WHERE `uid` = "'.$u->info['id'].'" AND `name` = "'.mysql_real_escape_string($_POST['compname']).'" AND `delete` = "0" LIMIT 1'));
		if(!isset($tcm['id']))
		{
			//добавляем новый комплект
			$ins = mysql_query('INSERT INTO `save_com` (`uid`,`time`,`name`,`val`,`type`) VALUES ("'.$u->info['id'].'","'.time().'","'.mysql_real_escape_string($_POST['compname']).'","'.$ptst.'","0")');
			if($ins)
			{
				$u->error = 'Комплект &quot;'.$_POST['compname'].'&quot; был успешно сохранен';
			}else{
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
}elseif(isset($_GET['delc1']))
{
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
	
$pc = 20;
$pg = round((int)@$_GET['paged']);
$pxc = $pg*$pc;
$nlim = '';
$pgs = mysql_fetch_array(mysql_query('SELECT COUNT(`iu`.`id`) FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON `im`.`id` = `iu`.`item_id` WHERE `iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `im`.`inRazdel`="'.mysql_real_escape_string($_GET['otdel']).'" ORDER BY '.$filt.' LIMIT 1'));
$pgs = $pgs[0];
$page_look = '';



if(isset($_SESSION['paged']))$page_look = '<!-- PAGED SEE '.round((int)@$_SESSION['paged']).'-->'; else $page_look = '<!-- PAGED '.$_SESSION['paged'].' -->';
if($pgs > $pc) {
	$nlim = ' LIMIT '.$pxc.' , '.$pc.'';
	$page_look .= '<table border=0 cellpadding=0 cellspacing=0 width=100% bgcolor="#A5A5A5"><tr><td width=99% align=center>';
	$page_look .= '<div style="padding:10px;">';
	$page_look .= 'Страницы: ';
	$i = 1;
	echo '<style>.pgdas { display:inline-block;background-color:#AEAEAE; padding:3px 7px 3px 7px; } .pgdas1 { display:inline-block;background-color:#EAEAEA; padding:3px 7px 3px 7px; }</style>';
	while($i <= ceil($pgs/$pc)) {
		if($i-1 == $pg) {
			$sep = 1;
		}else{
			$sep = '';
		}
		$page_look .= '<a class="pgdas'.$sep.'" href="javascript:void(0);" onclick="inventoryAjax(\'main.php?paged='.($i-1).'&inv&mAjax=true&otdel='.round($_GET['otdel']).'\');">'.$i.'</a> &nbsp;';
			$i++;
	}
	$page_look .= '</div>';
	$page_look .= '<td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr></table>';
}
$itmAll = $itmAllSee = '';
if(isset($_GET['boxsort']) && $_GET['otdel']==5) {
	if($_POST['subfilter']) {
		$itmAll = $u->genInv(1,'`iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `name` LIKE "%'.addcslashes(mysql_real_escape_string($_POST['filter']), '%_').'%" ORDER by `name` ASC');
	}
}else{
	$itmAll = $u->genInv(1,'`iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `im`.`inRazdel`="'.mysql_real_escape_string($_GET['otdel']).'" ORDER BY '.$filt.''.$nlim);
}

$itmAllSee = '<tr><td align="center" bgcolor="#e2e0e0">ПУСТО</td></tr>';
if($itmAll[0] > 0)
	$itmAllSee = $itmAll[2];
	
$showItems = '<table width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top"><table width="100%" cellspacing="0" cellpadding="5" bgcolor="#c7c7c7">
      <tr>
        <td width="20%" align=center bgcolor="' . (($_GET['otdel'] == 1) ? '#A5A5A5' : '' ) .'"><a href="javascript:void(0);" onclick="inventoryAjax(\'main.php?inv=1&mAjax=true&otdel=1&rn=1.1\');">Обмундирование</a></td>
        <td width="20%" align=center bgcolor="' . (($_GET['otdel'] == 2) ? '#A5A5A5' : '' ) .'"><a href="javascript:void(0);" onclick="inventoryAjax(\'main.php?inv=1&mAjax=true&otdel=2&rn=2.1\');">Заклятия</a></td>
        <td width="20%" align=center bgcolor="' . (($_GET['otdel'] == 3) ? '#A5A5A5' : '' ) .'"><a href="javascript:void(0);" onclick="inventoryAjax(\'main.php?inv=1&mAjax=true&otdel=3&rn=3.1\');">Эликсиры</a></td>
        <td width="20%" align=center bgcolor="' . (($_GET['otdel'] == 6) ? '#A5A5A5' : '' ) .'"><a href="javascript:void(0);" onclick="inventoryAjax(\'main.php?inv=1&mAjax=true&otdel=6&rn=6.1\');">Руны</a></td>
        <td width="20%" align=center bgcolor="' . (($_GET['otdel'] == 4) ? '#A5A5A5' : '' ) .'"><a href="javascript:void(0);" onclick="inventoryAjax(\'main.php?inv=1&mAjax=true&otdel=4&rn=4.1\');">Прочее</a></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center"><table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#A5A5A5">
      <tr>
        <td width="99%" align="center"><b>Рюкзак (масса:' . (0+$u->aves['now']) . ' / ' . $u->aves['max'] . ', предметов: ' . $u->aves['items'] . ')</b>
      </tr>
    </table>' .	$page_look . '</td>
  </tr>
  <tr>
    <td valign="top" align="center"><table width="100%" border="0" cellspacing="1" align="center" cellpadding="0" bgcolor="#A5A5A5">' . (( $u->info['invBlock'] == 0 ) ? $itmAllSee : '<div align="center" style="padding:10px;background-color:#A5A5A5;"><form method="post" action="main.php?inv=1&otdel='.$_GET['otdel'].'&relockinvent"><b>Рюкзак закрыт.</b><br><img title="Замок для рюкзака" src="http://img.xcombats.com/i/items/box_lock.gif"> Введите пароль: <input id="relockInv" name="relockInv" type="password"><input type="submit" value="Открыть"></form></div>' ) . '</table>
      <table width="100%"  bgcolor="#A5A5A5">
        <tr>
          <td align="left">&nbsp;&nbsp;Выровнять по
            <input type="button" onclick="inventoryAjax(\'main.php?inv=1&mAjax=true&boxsort=name&otdel=' . intval($_GET['otdel']) . '\');" value="названию" />
            <input type="button" onclick="inventoryAjax(\'main.php?inv=1&mAjax=true&boxsort=cost&otdel=' . intval($_GET['otdel']) . '\');" value="цене" />
            <input type="button" onclick="inventoryAjax(\'main.php?inv=1&mAjax=true&boxsort=type&otdel=' . intval($_GET['otdel']) . '\');" value="типу" /></td>
          </tr>
        </table></td>
  </tr>
</table>';
if(isset($_GET['mAjax'])){
	exit($showItems);
}
?>
<script type="text/javascript" src="js/jquery.js"></script>
<script>
function inventoryAjax(url){
	$('#ShowInventory').html('<div align="center" style="padding:10px;background-color:#A5A5A5;"><b>Загрузка...</b></div>');
	$.ajax({
		url: url,
		cache: false,
		dataType: 'html',
		success: function (html) {
			$('#ShowInventory').html(html);
		}
	});
}
function seetext(id) {
	var id = document.getElementById('close_text_itm'+id);
	if(id.style.display == 'none') {
		id.style.display = '';
	}else{
		id.style.display = 'none';
	}
}
</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="250" valign="top" align="right"><div align="center"><? $usee = $u->getInfoPers($u->info['id'],0,0,1); if($usee!=false){ echo $usee[0]; }else{ echo 'information is lost.'; } 
	if($u->info['level']>1 && $u->info['inTurnir'] == 0)
	{ 
		include('_incl_data/class/_cron_.php');
		$priem->seeMy(1);
	}
	if( $u->info['inTurnir'] > 0 ) {
		echo '<center><a href="/main.php?inv&remitem&otdel='.round((int)$_GET['otdel']).'">Снять все</a></center>';
	}
	echo '<br>'.$u->info_remont();
	if( $u->info['inTurnir'] == 0 ) {
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
	}
	?>
    </div></td>
    <td width="242" valign="top" align="left"><? if( $u->info['inTurnir'] == 0) { include('stats_inv.php'); }else{ include('stats_inv2.php'); } ?></td>    
    <td valign="top">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" noresize="noresize">
      <? if( $u->error != '' )  { ?>
      <tr>
      	<td>
        <div style="min-height:18px;padding-left:0px;"><font color="#FF0000"><b><? echo $u->error; ?></b></font></div>
        </td>
      </tr>
      <? } ?>
      <tr>
            <td width="60%" height="45" align="right" valign="top">
            <?
                if($u->info['animal'] != 0)
                {
                	echo ' <input class="btnnew" type="button" onclick="top.frames[\'main\'].location=\'main.php?pet=1&rnd='.$code.'\'" value="Зверь" />';
                }
                ?>
                <? if ($u->info['inTurnir'] == 0) { ?>
              	<input class="btnnew" type="button" onclick="top.frames['main'].location='main.php?obraz&rnd=<? echo $code; ?>'" value="Образ" />
                <? } ?>
				<? 
				$gl = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `reimage` WHERE ((`uid` = "'.$u->info['id'].'" AND `clan` = "0") OR `clan` = "'.$u->info['clan'].'") AND `good` > 0 AND `bad` = "0" LIMIT 1'));
				if($gl[0] > 0) { ?>
                <input class="btnnew" type="button" onclick="top.frames['main'].location='main.php?galery&rnd=<? echo $code; ?>'" value="Галерея" />
                <? } unset($gl); ?>
                <input class="btnnew" type="button" onclick="top.frames['main'].location='main.php?skills&amp;side=1&amp;rn=<? echo $code; ?>'"  value="Умения" />
                <input class="btnnew" type="button" onclick="top.frames['main'].location='main.php?anketa&amp;rn=<? echo $code; ?>'" value="Анкета" />
                <? if ($u->info['inTurnir'] == 0) { ?>
                <input class="btnnew2" style="background-color:#A9AFC0" type="button" onclick="location.href='main.php?referals'" value="Наставничество" />
                <? } ?>
				<input class="btnnew" type="button" onclick="top.frames['main'].location='main.php?act_trf=1&amp;rn=<? echo $code; ?>'" value="Отчет о переводах" />
                <input class="btnnew" type="button" style="font-weight:bold;" value="Безопасность" onclick="top.frames['main'].location='main.php?security&amp;rn=<? echo $code; ?>'" />
                <input class="btnnew" type="button" style="background-color:#A9AFC0" onClick="alert('Раздел отсутствует');" value="Подсказки" />
                
                <input class="btnnew" type="button" onclick="top.frames['main'].location='main.php'" value="Вернуться" />            </td>
      </tr>
  <tr>
    <td id="ShowInventory"><?php echo $showItems; ?></td>
  </tr>
  <tr>
    <td>
      <div align="right"><?php echo $c['counters']; ?></div>
    </td>
  </tr>
</table>