<?php
	header('Content-Type: text/html; charset=windows-1251');
		
	define('GAME',true);
	include('_incl_data/__config.php');	
	include('_incl_data/class/__db_connect.php');	
	include('_incl_data/class/__user.php');
	include('_incl_data/class/__filter_class.php');
	include('_incl_data/class/__chat_class.php');	
	
	if(!isset($u->info['id']) || $u->info['banned']>0)
	{
		die(json_encode(array('js'=>'top.location="bk?exit='.$code.'";')));
	}elseif($u->info['ip']!=$_SERVER['HTTP_X_REAL_IP'])
	{
		
	}
	
	if($u->info['activ']>0) {
		die('Вам необходимо активировать персонажа.<br>Авторизируйтесь с главной страницы.');
	}
	
	if($u->info['repass'] > 0) {
		die();
	}

	if(isset($_GET['items'])) {
		$itm = mysql_fetch_array(mysql_query('SELECT * FROM `fastpanel` WHERE `uid` = "'.$u->info['id'].'" LIMIT 1'));
		if(!isset($itm['id'])) {
			mysql_query('INSERT INTO `fastpanel` (`uid`) VALUES ("'.$u->info['id'].'")');
		}
		//
		$r = explode('|',$_GET['items']);
		$i = 0; $v = '';
		while( $i <= 10 ) {
			if(isset($r[$i])) {
				$id = round((int)$r[$i]);
				$id = mysql_fetch_array(mysql_query('SELECT `id` FROM `items_users` WHERE `id` = "'.mysql_real_escape_string($id).'" AND `uid` = "'.$u->info['id'].'" AND `delete` = 0 AND `inShop` = 0 LIMIT 1'));
				if(isset($id['id'])) {
					$v .= $id['id'].'|';
				}else{
					$v .= '0|';
				}
			}else{
				$v .= '0|';
			}
			$i++;
		}
		$v = rtrim($v,'|');
		mysql_query('UPDATE `fastpanel` SET `data` = "'.mysql_real_escape_string($v).'" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
	}
		
?>