<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Отменить
	Отменяет последнее изменение НР
*/
$pvr = array();
//Действие при клике

$pvr['hp'] = -floor($btl->users[$btl->uids[$u->info['id']]]['last_hp']);
$pvr['hpSee'] = '--';
$pvr['hpNow'] = floor($btl->stats[$btl->uids[$u->info['id']]]['hpNow']);
$pvr['hpAll'] = $btl->stats[$btl->uids[$u->info['id']]]['hpAll'];
	
$pvr['hpTr'] = $pvr['hpAll'] - $pvr['hpNow'];
if( $pvr['hpTr'] > 0 ) {
	//Требуется хилл
	if( $pvr['hpTr'] < $pvr['hp'] ) {
		$pvr['hp'] = $pvr['hpTr'];
	}
	if( $pvr['hp'] > 0 ) {
		$pvr['hpSee'] = '+'.$pvr['hp'];
	}else{
		$pvr['hpSee'] = ''.$pvr['hp'];
	}
	$pvr['hpNow'] += $pvr['hp'];
}else{
	//Требуется откат НР
	$pvr['hpSee'] = $pvr['hp'];
	$pvr['hpNow'] += $pvr['hp'];
}

$btl->users[$btl->uids[$u->info['id']]]['last_hp'] = $pvr['hp'];
	
if( $pvr['hpNow'] > $pvr['hpAll'] ) {
	$pvr['hpNow'] = $pvr['hpAll'];
}elseif( $pvr['hpNow'] < 0 ) {
	$pvr['hpNow'] = 0;
}
	
$u->info['hpNow'] = $pvr['hpNow'];
$u->stats['hpNow'] = $pvr['hpNow'];
$btl->stats[$btl->uids[$u->info['id']]]['hpNow'] = $pvr['hpNow'];
	
mysql_query('UPDATE `stats` SET `last_hp` = "'.$btl->users[$btl->uids[$u->info['id']]]['last_hp'].'",`hpNow` = "'.$u->info['hpNow'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
	
$btl->priemAddLog( $id, 1, 2, $u->info['id'], $u->info['enemy'],
	'Отменить',
	'{tm1} '.$btl->addlt(1 , 17 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL).' <font Color=#006699><b>'.$pvr['hpSee'].'</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']',
	($btl->hodID)
);

//Отнимаем тактики
$this->mintr($pl);

unset($pvr);
?>