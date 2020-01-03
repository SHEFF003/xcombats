<?php
function GetRealIp(){
	if (!empty($_SERVER['HTTP_CLIENT_IP']))
		return $_SERVER['HTTP_CLIENT_IP'];
	else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		return $_SERVER['HTTP_X_FORWARDED_FOR'];
	return $_SERVER['REMOTE_ADDR'];
}

define('IP',GetRealIp());

include('_incl_data/__config.php');
define('GAME',true);
include('_incl_data/class/__db_connect.php');
include('_incl_data/class/__magic.php');
include('_incl_data/class/__user.php');
include('_incl_data/class/__filter_class.php');
include('_incl_data/class/__quest.php');

$free_stats = 125;

if($u->info['banned'] > 0) {
	header('location: /index.php');
	die();
}

$tjs = '';

if($u->info['bithday'] == '01.01.1800' && $u->info['inTurnirnew'] == 0) {
	unset($_GET,$_POST);
}

if($u->info['activ']>0) {
	die('Вам необходимо активировать персонажа.<br>Авторизируйтесь с главной страницы.');
}

if(isset($_POST['set_name'])) {
	$_POST['set_name'] = htmlspecialchars($_POST['set_name']);
	$set_x = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `ruine_sets` WHERE `uid` = "'.$u->info['id'].'" AND `name` != "'.mysql_real_escape_string($_POST['set_name']).'" LIMIT 1'));
	if( $set_x[0] > 20 ) {
		$u->error = 'Нельзя создавать более 20 профилей';
	}else{
		$set_x = mysql_fetch_array(mysql_query('SELECT `id` FROM `ruine_sets` WHERE `uid` = "'.$u->info['id'].'" AND `name` = "'.mysql_real_escape_string($_POST['set_name']).'" LIMIT 1'));
		//
		
		$f = $free_stats; // свободных статов
		$i = 1;
		while( $i <= 6 ) {
			$_POST['s'.$i] = round((int)$_POST['s'.$i]);
			if( $_POST['s'.$i] < 0 ) {
				$_POST['s'.$i] = 0;
			}
			$f -= $_POST['s'.$i];
			$i++;
		}
 		
		//	
		if( $f < 0 || $f > $free_stats ) {
			$u->error = 'Ошибка в распределении статов.';
		}elseif(isset($set_x['id'])) {
			mysql_query('UPDATE `ruine_sets` SET
				`s1` = "'.mysql_real_escape_string($_POST['s1']).'",
				`s2` = "'.mysql_real_escape_string($_POST['s2']).'",
				`s3` = "'.mysql_real_escape_string($_POST['s3']).'",
				`s4` = "'.mysql_real_escape_string($_POST['s4']).'",
				`s5` = "'.mysql_real_escape_string($_POST['s5']).'",
				`s6` = "'.mysql_real_escape_string($_POST['s6']).'",
				`free` = "'.mysql_real_escape_string($f).'",
				`time` = "'.time().'"
			WHERE `id` = "'.$set_x['id'].'" LIMIT 1');
			$u->error = 'Профиль &quot;'.$_POST['set_name'].'&quot; был успешно перезаписан!';
		}else{
			mysql_query('INSERT INTO `ruine_sets` (
				`uid`,`name`,`free`,`s1`,`s2`,`s3`,`s4`,`s5`,`s6`,`time`
			) VALUES (
				"'.$u->info['id'].'","'.mysql_real_escape_string($_POST['set_name']).'","'.mysql_real_escape_string($f).'",
				"'.mysql_real_escape_string($_POST['s1']).'",
				"'.mysql_real_escape_string($_POST['s2']).'",
				"'.mysql_real_escape_string($_POST['s3']).'",
				"'.mysql_real_escape_string($_POST['s4']).'",
				"'.mysql_real_escape_string($_POST['s5']).'",
				"'.mysql_real_escape_string($_POST['s6']).'",
				"'.time().'"
			)');
			$u->error = 'Профиль &quot;'.$_POST['set_name'].'&quot; был создан.';
		}
	}
}elseif( isset($_GET['use']) ) {
	$set_x = mysql_fetch_array(mysql_query('SELECT * FROM `ruine_sets` WHERE `uid` = "'.$u->info['id'].'" AND `id` = "'.mysql_real_escape_string($_GET['use']).'" LIMIT 1'));
	if(isset($set_x['id'])) {
		mysql_query('UPDATE `ruine_sets` SET `use` = 0 WHERE `uid` = "'.$u->info['id'].'"');
		mysql_query('UPDATE `ruine_sets` SET `use` = 1 WHERE `id` = "'.$set_x['id'].'" LIMIT 1');
		$u->error = 'Профиль &quot;'.$set_x['name'].'&quot; был установлен по умолчанию.';
	}
}elseif( isset($_GET['delete']) ) {
	$set_x = mysql_fetch_array(mysql_query('SELECT * FROM `ruine_sets` WHERE `uid` = "'.$u->info['id'].'" AND `id` = "'.mysql_real_escape_string($_GET['delete']).'" LIMIT 1'));
	if(isset($set_x['id'])) {
		mysql_query('DELETE FROM `ruine_sets` WHERE `id` = "'.$set_x['id'].'" LIMIT 1');
		$u->error = 'Профиль &quot;'.$set_x['name'].'&quot; был стерт.';
	}
}

?>
<!doctype html>
<html>
<head>
<meta charset="windows-1251">
<title>Старый Бойцовский Клуб - Профили характеристик</title>
<link href="http://img.xcombats.com/css/main.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/jquery.js"></script>
<script>
var free_stats = <?=$free_stats?>;
function test_free() {
	var i = 1;
	var a = 0;
	while( i <= 6 ) {
		a += Number($('#s'+i).val());
		i++;
	}
	$('#free_s').val( free_stats - a );
	if( free_stats - a > 0 ) {
		$('#result_ch').html('');
	}else if( free_stats - a == 0 ) {
		$('#result_ch').html('&nbsp;&nbsp;<font color="green"><b>Теперь можно сохранять</b></font>');
	}else{
		$('#result_ch').html('&nbsp;&nbsp;<font color="red"><b>Недостаточно характеристик!</b></font>');
	}
}
</script>
</head>

<body>
<h3>Профили характеристик</h3><br>
<?
if( $u->error != '' ) {
	echo '<div><font color="red"><b>'.$u->error.'</b></font></div><br>';
}
?>
Статы не позволяют сходить в руины? Раскиньте ваши статы  так, как вы хотите, и участвуйте в турнире! Выбранный по умолчанию профиль, загрузится сам. Вы можете создавать до двадцати профилей и менять их  за секунды до турнира!
<br><br>
<table style="border:1px solid #000;" width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td style="border-right:1px solid #000;" bgcolor="#b5b5b5">Название</td>
    <td style="border-right:1px solid #000;" bgcolor="#b5b5b5">По умолчанию</td>
    <td bgcolor="#b5b5b5">Удалить</td>
  </tr>
  <?
  $j = 0;
  $sp = mysql_query('SELECT * FROM `ruine_sets` WHERE `uid` = "'.$u->info['id'].'" ORDER BY `time` DESC');
  while( $pl = mysql_fetch_array($sp) ) {
  ?>
  <tr>
    <td style="border-right:1px solid #000;border-top:1px solid #000;" bgcolor="#d2d0d1"><?=$pl['name']?></td>
    <td style="border-right:1px solid #000;border-top:1px solid #000;" bgcolor="#d2d0d1">
	<? if( $pl['use'] == 0 ) { ?>
    <a href="http://xcombats.com/ruin_characteristics.php?use=<?=$pl['id']?>">Установить</a>
    <? }else{ ?>
    <b><font color="red">По умолчанию</font></b>
    <? } ?></td>
    <td style="border-top:1px solid #000;" bgcolor="#d2d0d1"><a href="http://xcombats.com/ruin_characteristics.php?delete=<?=$pl['id']?>">удалить</a></td>
  </tr>
  <? $j++; }  ?>
</table>
<? 
if( $j == 0 ) {
	echo '<div style="padding:5px;border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;background-color:#d2d0d1;" align="center">Нет сохраненных профилей</div>'; 
}
?>
<br>
<input type="button" value="Обновить" class="btnnew" onClick="location.href='http://xcombats.com/ruin_characteristics.php';">
<br><br>
<hr>
<br>
<form method="post" action="http://xcombats.com/ruin_characteristics.php">
Название: <input name="set_name" type="text" value="" class="textnew" style="width:144px;"><br><br>
<table style="border:1px solid #000;" width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td style="border-right:1px solid #000;" width="200" bgcolor="#B5B5B5">Характеристика</td>
    <td bgcolor="#B5B5B5">Значение</td>
  </tr>
  <tr>
    <td style="border-right:1px solid #000;border-top:1px solid #000;" bgcolor="#D2D0D1">Сила</td>
    <td style="border-top:1px solid #000;" bgcolor="#D2D0D1"><input onKeyUp="test_free();" onKeyDown="test_free();" id="s1" name="s1" type="text" value="" class="textnew" style="width:44px;"></td>
  </tr>
  <tr>
    <td style="border-right:1px solid #000;border-top:1px solid #000;" bgcolor="#D2D0D1">Ловкость</td>
    <td style="border-top:1px solid #000;" bgcolor="#D2D0D1"><input onKeyUp="test_free();" onKeyDown="test_free();" id="s2" name="s2" type="text" value="" class="textnew" style="width:44px;"></td>
  </tr>
  <tr>
    <td style="border-right:1px solid #000;border-top:1px solid #000;" bgcolor="#D2D0D1">Интуиция</td>
    <td style="border-top:1px solid #000;" bgcolor="#D2D0D1"><input onKeyUp="test_free();" onKeyDown="test_free();" id="s3" name="s3" type="text" value="" class="textnew" style="width:44px;"></td>
  </tr>
  <tr>
    <td style="border-right:1px solid #000;border-top:1px solid #000;" bgcolor="#D2D0D1">Выносливость</td>
    <td style="border-top:1px solid #000;" bgcolor="#D2D0D1"><input onKeyUp="test_free();" onKeyDown="test_free();" id="s4" name="s4" type="text" value="" class="textnew" style="width:44px;"></td>
  </tr> 
  <tr>
    <td style="border-right:1px solid #000;border-top:1px solid #000;" bgcolor="#D2D0D1">Интеллект</td>
    <td style="border-top:1px solid #000;" bgcolor="#D2D0D1"><input onKeyUp="test_free();" onKeyDown="test_free();" id="s5" name="s5" type="text" value="" class="textnew" style="width:44px;"></td>
  </tr>
  <tr>
    <td style="border-right:1px solid #000;border-top:1px solid #000;" bgcolor="#D2D0D1">Мудрость</td>
    <td style="border-top:1px solid #000;" bgcolor="#D2D0D1"><input onKeyUp="test_free();" onKeyDown="test_free();" id="s6" name="s6" type="text" value="" class="textnew" style="width:44px;"></td>
  </tr>
  <tr>
    <td style="border-right:1px solid #000;border-top:1px solid #000;" bgcolor="#D2D0D1">Свободных статов</td>
    <td style="border-top:1px solid #000;" bgcolor="#D2D0D1"><input  id="free_s" disabled="disabled" type="text" value="<?=$free_stats?>" class="textnew" style="width:44px;"><span id="result_ch"></span></td>
  </tr>
</table>
<br>
<input type="submit" value="Сохранить / Изменить" class="btnnew">
</form>
<script>test_free();</script>
</body>
</html>
