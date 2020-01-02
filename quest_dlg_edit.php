<?php
/*

	Ядро для обработки данных.
	Обработка поединков, обработка заявок, обработка ботов, обработка пещер, обработка турниров, обработка временных генераций

*/

define('GAME',true);

include('_incl_data/__config.php');
include('_incl_data/class/__db_connect.php');
include('_incl_data/class/__user.php');
//include('_incl_data/class/bot.logic.php');


if( $u->info['admin'] > 0 ) {
	
	$itm = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_dlg` WHERE `id` = "'.mysql_real_escape_string($_GET['pid']).'" LIMIT 1'));
	if(isset($itm['id'])) {
		if($itm['qid'] == 0) {
			if(isset($_POST['newdata'])) {
				mysql_query('UPDATE `dungeon_dlg` SET `text` = "'.mysql_real_escape_string($_POST['newdata']).'" WHERE `id` = "'.mysql_real_escape_string($itm['id']).'" LIMIT 1');
				die('<script>window.close();</script>');
			}elseif(isset($_GET['delete']) && $_GET['delete'] == 'true') {
				//Удаляем саму страницу диалога
				mysql_query('DELETE FROM `dungeon_dlg` WHERE `id` = "'.mysql_real_escape_string($itm['id']).'" LIMIT 1');
				//Удаляем ответы страницы диалога
				mysql_query('DELETE FROM `dungeon_dlg` WHERE `qid` = "'.mysql_real_escape_string($itm['id']).'"');
				die('<script>window.close();</script>');
			}
			echo '<form method="post" action="?pid='.$itm['id'].'"><b>Номер диалога: '.$itm['id'].'</b><br><textarea name="newdata" rows="20" cols="100">'.$itm['text'].'</textarea><br><input type="submit" value="Сохранить"></form><div><a href="?pid='.((int)$_GET['pid']).'&delete=true">Удалить страницу диалога</a></div>';
		}else{
			if(isset($_POST['newdata'])) {
				mysql_query('UPDATE `dungeon_dlg` SET `text` = "'.mysql_real_escape_string($_POST['newdata']).'",`action` = "'.mysql_real_escape_string($_POST['newdata2']).'",`tr` = "'.mysql_real_escape_string($_POST['newdata3']).'",`sort` = "'.mysql_real_escape_string((int)$_POST['newdata4']).'" WHERE `id` = "'.mysql_real_escape_string($itm['id']).'" LIMIT 1');
				die('<script>window.close();</script>');
			}elseif(isset($_GET['delete']) && $_GET['delete'] == 'true') {
				//Удаляем саму страницу диалога
				mysql_query('DELETE FROM `dungeon_dlg` WHERE `id` = "'.mysql_real_escape_string($itm['id']).'" LIMIT 1');
				die('<script>window.close();</script>');
			}
			echo '<form method="post" action="?pid='.$itm['id'].'"><b>Номер варианта ответа: '.$itm['id'].'</b><br><textarea name="newdata" rows="3" cols="100">'.$itm['text'].'</textarea><br>Действия:<textarea name="newdata2" rows="5" cols="100">'.$itm['action'].'</textarea><br>Требует:<textarea name="newdata3" rows="5" cols="100">'.$itm['tr'].'</textarea><br>Приоритет вывода: <input name="newdata4" type="text" value="'.$itm['sort'].'"><br><input type="submit" value="Сохранить"></form><div><a href="?pid='.((int)$_GET['pid']).'&delete=true">Удалить вариант ответа</a></div>';
		}
	}else{
		echo 'Диалог не найден.';
	}
}
	
?>