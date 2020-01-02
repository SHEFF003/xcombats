<?
if(!defined('GAME'))
{
	die();
}

if($u->info['admin'] > 0)
{
		$uu = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['logingo']).'" LIMIT 1'));
		if(isset($uu['id']))
		{
			if( $_POST['logingo2'] < 0 ) {
				$_POST['logingo2'] = 0;
			}
			$upd = mysql_query('UPDATE `stats` SET `exp` = `exp` + "'.mysql_real_escape_string((int)$_POST['logingo2']).'" WHERE `id` = "'.$uu['id'].'" LIMIT 1');
			if($upd) {
				$uer = 'Вы рисанули опыт персонажу &quot;'.$uu['login'].'&quot; +'.((int)$_POST['logingo2']).' ед.';
			}else{
				$uer = 'Не удалось использовать данное заклятие';
			}
		}else{
			$uer = 'Персонаж не найден в этом городе';
		}
}else{
	$uer = 'У Вас нет прав на использование данного заклятия';
}	
?>