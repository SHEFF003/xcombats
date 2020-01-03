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
	
	$itm = mysql_fetch_array(mysql_query('SELECT * FROM `items_main_data` WHERE `items_id` = "'.mysql_real_escape_string($_GET['edit_item_data']).'" LIMIT 1'));
	if(isset($itm['id'])) {
		if(isset($_POST['newdata'])) {
			mysql_query('UPDATE `items_main_data` SET `data` = "'.mysql_real_escape_string($_POST['newdata']).'" WHERE `id` = "'.mysql_real_escape_string($itm['id']).'" LIMIT 1');
			die('<script>window.close();</script>');
		}
		echo '<form method="post" action="item_edit_data.php?edit_item_data='.$itm['items_id'].'"><b>Номер предмета: '.$itm['items_id'].'</b><br><textarea name="newdata" rows="20" cols="100">'.$itm['data'].'</textarea><br><input type="submit" value="Сохранить"></form>';
	}
}
	
?>