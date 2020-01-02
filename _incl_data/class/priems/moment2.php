<?
if(!defined('GAME'))
{
	die();
}

if($pl['id'] == 223) {
	//Рывок
	$btl->users[$btl->uids[$u->info['id']]]['tactic1'] += 3;
	$btl->stats[$btl->uids[$u->info['id']]]['tactic1'] = $btl->users[$btl->uids[$u->info['id']]]['tactic1'];
	$u->stats['tactic1'] = $btl->users[$btl->uids[$u->info['id']]]['tactic1'];
	$u->info['tactic1'] = $btl->users[$btl->uids[$u->info['id']]]['tactic1'];
	mysql_query('UPDATE `stats` SET `tactic1` = "'.$btl->users[$btl->uids[$u->info['id']]]['tactic1'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
}
?>