<?
if(!defined('GAME'))
{
	die();
}

//удал€ем предмет (1 из кучи) , а так-же добавл€ем + 1 к репутации
if($u->info['room'] == 322) {
	$u->deleteItem($itm['id']);
	mysql_query('UPDATE `rep` SET `rep2` = `rep2` + 1 WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
	$u->error = '¬ы успешно использовали &quot;'.$itm['name'].'&quot;...';
}else{
	$u->error = 'Ќельз€ использовать в этой локации...';
}
?>