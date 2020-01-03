<?php
define('GAME',true);
setlocale(LC_CTYPE ,"ru_RU.CP1251");
include('/var/www/xcombats/data/www/xcombats.com/_incl_data/__config.php');
include('/var/www/xcombats/data/www/xcombats.com/_incl_data/class/__db_connect.php');
include('/var/www/xcombats/data/www/xcombats.com/_incl_data/class/__user.php');

/*

	CRON Комиссионного магазина
	Действия:
	1.  Если предмет висит уже 2 и более недель, продавать предмет за 50% от его стоимости с учетом износа
		деньги отсылаются на почту.

*/

$time_last = 86400; //1 день

$sp = mysql_query('SELECT * FROM `items_auc` WHERE `time_end` = 0 AND `time` < "'.(time()-$time_last).'" ORDER BY `user_buy` ASC');
while( $pl = mysql_fetch_array($sp) ) {
	//
	if( $pl['x'] > 0 ) {
		$pl['name'] .= ' (x'.$pl['x'].')';
	}
	//
	$user = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `id` = "'.$pl['uid'].'" LIMIT 1'));
	if( $pl['user_buy'] > 0 ) {
		//Предмет купили
		$buyer = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `id` = "'.$pl['user_buy'].'" LIMIT 1'));
		if(isset($buyer['id'])) {
			mysql_query('UPDATE `items_users` SET `uid` = "'.$buyer['id'].'",`lastUPD` = "'.time().'" WHERE (`id` = "'.$pl['item_id'].'" OR `inGroup` = "'.$pl['item_id'].'") AND `uid` = 0');
			$u->send('','','','<font color=#009966 >Филиал Аукциона</font>',$buyer['login'],'Вы выиграли торги. Предмет &quot;'.$pl['name'].'&quot; за <b>'.$pl['price'].' кр.</b> был добавлен к вам в инвентарь.',time(),6,0,0,0,1,0);
		}
		if(isset($user['id'])) {
			$u->send('','','','<font color=#009966 >Филиал Аукциона</font>',$user['login'],'Предмет &quot;'.$pl['name'].'&quot; был продан на торгах, <b>'.$pl['price'].'</b> кр. за товар отправлены вам по почте.',time(),6,0,0,0,1,0);
			//
			mysql_query('INSERT INTO `items_users` (`delete`,`item_id`,`1price`,`uid`,`lastUPD`) VALUES ("0","1220","'.$pl['price'].'","-51'.$user['id'].'","'.time().'")');
			mysql_query('INSERT INTO `post` (`uid`,`sender_id`,`time`,`money`,`text`) VALUES (
				"'.$user['id'].'","0","'.time().'","'.$pl['price'].'","Филиал Аукциона: Предмет &quot;'.$pl['name'].'&quot; был продан за <b>'.$pl['price'].' кр</b>."
			)');
		}
	}else{
		//Предмет не купили, возвращаем его обратно
		if(isset($user['id'])) {
			mysql_query('UPDATE `items_users` SET `uid` = "'.$user['id'].'",`lastUPD` = "'.time().'" WHERE (`id` = "'.$pl['item_id'].'" OR `inGroup` = "'.$pl['item_id'].'") AND `uid` = 0');
			$u->send('','','','<font color=#009966 >Филиал Аукциона</font>',$user['login'],'Предмет &quot;'.$pl['name'].'&quot; не был продан, он возвращен вам в инвентарь.',time(),6,0,0,0,1,0);
		}
	}
	mysql_query('UPDATE `items_auc` SET `time_end` = "'.time().'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
}
?>