<?php
define('GAME',true);
setlocale(LC_CTYPE ,"ru_RU.CP1251");
include('/var/www/xcombats/data/www/xcombats.com/_incl_data/__config.php');
include('/var/www/xcombats/data/www/xcombats.com/_incl_data/class/__db_connect.php');
include('/var/www/xcombats/data/www/xcombats.com/_incl_data/class/__user.php');

/*

	CRON ������������� ��������
	��������:
	1.  ���� ������� ����� ��� 2 � ����� ������, ��������� ������� �� 50% �� ��� ��������� � ������ ������
		������ ���������� �� �����.

*/

$time_last = 86400; //1 ����

$sp = mysql_query('SELECT * FROM `items_auc` WHERE `time_end` = 0 AND `time` < "'.(time()-$time_last).'" ORDER BY `user_buy` ASC');
while( $pl = mysql_fetch_array($sp) ) {
	//
	if( $pl['x'] > 0 ) {
		$pl['name'] .= ' (x'.$pl['x'].')';
	}
	//
	$user = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `id` = "'.$pl['uid'].'" LIMIT 1'));
	if( $pl['user_buy'] > 0 ) {
		//������� ������
		$buyer = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `id` = "'.$pl['user_buy'].'" LIMIT 1'));
		if(isset($buyer['id'])) {
			mysql_query('UPDATE `items_users` SET `uid` = "'.$buyer['id'].'",`lastUPD` = "'.time().'" WHERE (`id` = "'.$pl['item_id'].'" OR `inGroup` = "'.$pl['item_id'].'") AND `uid` = 0');
			$u->send('','','','<font color=#009966 >������ ��������</font>',$buyer['login'],'�� �������� �����. ������� &quot;'.$pl['name'].'&quot; �� <b>'.$pl['price'].' ��.</b> ��� �������� � ��� � ���������.',time(),6,0,0,0,1,0);
		}
		if(isset($user['id'])) {
			$u->send('','','','<font color=#009966 >������ ��������</font>',$user['login'],'������� &quot;'.$pl['name'].'&quot; ��� ������ �� ������, <b>'.$pl['price'].'</b> ��. �� ����� ���������� ��� �� �����.',time(),6,0,0,0,1,0);
			//
			mysql_query('INSERT INTO `items_users` (`delete`,`item_id`,`1price`,`uid`,`lastUPD`) VALUES ("0","1220","'.$pl['price'].'","-51'.$user['id'].'","'.time().'")');
			mysql_query('INSERT INTO `post` (`uid`,`sender_id`,`time`,`money`,`text`) VALUES (
				"'.$user['id'].'","0","'.time().'","'.$pl['price'].'","������ ��������: ������� &quot;'.$pl['name'].'&quot; ��� ������ �� <b>'.$pl['price'].' ��</b>."
			)');
		}
	}else{
		//������� �� ������, ���������� ��� �������
		if(isset($user['id'])) {
			mysql_query('UPDATE `items_users` SET `uid` = "'.$user['id'].'",`lastUPD` = "'.time().'" WHERE (`id` = "'.$pl['item_id'].'" OR `inGroup` = "'.$pl['item_id'].'") AND `uid` = 0');
			$u->send('','','','<font color=#009966 >������ ��������</font>',$user['login'],'������� &quot;'.$pl['name'].'&quot; �� ��� ������, �� ��������� ��� � ���������.',time(),6,0,0,0,1,0);
		}
	}
	mysql_query('UPDATE `items_auc` SET `time_end` = "'.time().'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
}
?>