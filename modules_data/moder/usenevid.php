<?
if(!defined('GAME'))
{
	die();
}

if($p['invis'] == 1)
{
	if($u->info['invis']!=1 && $u->info['invis']<time()) {
		$uer = 'Вы успешно включили невидимку';
		$u->info['invis'] = 1;
	}else{
		$uer = 'Вы успешно выключили невидимку';
		$u->info['invis'] = 0;
	}
	mysql_query('UPDATE `users` SET `invis` = "'.$u->info['invis'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
}else{
	$uer = 'У Вас нет прав на использование данного заклятия';
}	
?>