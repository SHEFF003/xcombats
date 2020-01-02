<?
if(!defined('GAME'))
{
	die();
}

if( $itm['magic_inci'] == 'antipropuskpsh' ) {

	$test = mysql_fetch_array(mysql_query('SELECT `id`,`time` FROM `actions` WHERE `uid` = "'.$usr['id'].'" AND `vars` = "psh0" AND `time` > "'.time().'" LIMIT 1'));
	if(!isset($usr['id'])) {
		$u->error = 'Неудалось найти персонажа &quot;'.htmlspecialchars($_GET['login']).'&quot;.';
	}elseif( $u->info['align'] != 2 ) {
		if( !isset($test['id']) ) {
			$u->addAction( time()+300 ,'psh0',$usr['id'],$usr['id']);
			$test = mysql_fetch_array(mysql_query('SELECT `id`,`time` FROM `actions` WHERE `uid` = "'.$usr['id'].'" AND `vars` = "psh0" AND `time` > "'.time().'" LIMIT 1'));
		}
		$u->addAction(time(),'propuskpsh','',$usr['id']);
		$u->error = 'Все прошло успешно, задержки в пещеры увеличены на 24 часа для &quot;'.$usr['login'].'&quot;.';
		if($test['time'] < time()) {
			$test['time'] = time();
		}
		$test['time'] += 86400;
		mysql_query('UPDATE `actions` SET `time` = "'.$test['time'].'" WHERE `uid` = '.$usr['id'].' AND `id` = "'.$test['id'].'" LIMIT 1');
		mysql_query('UPDATE `items_users` SET `iznosNOW` = `iznosNOW` + 1 WHERE `id` = '.$itm['id'].' LIMIT 1');
	}else{
		$u->error = 'Хаосники не могут пользоваться этим свитком!';
	}
}
?>