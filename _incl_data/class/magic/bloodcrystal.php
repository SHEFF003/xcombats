<?
if(!defined('GAME'))
{
	die();
}

//удаляем предмет (1 из кучи) , а так-же добавляем + 1 к репутации
if($u->info['room'] == 322) {
	$u->deleteItem($itm['id']);
	mysql_query('UPDATE `rep` SET `rep2` = `rep2` + 1 WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
	$u->error = 'Вы успешно использовали &quot;'.$itm['name'].'&quot;...';
}else{
	$u->error = 'Нельзя использовать в этой локации...';
}
?>